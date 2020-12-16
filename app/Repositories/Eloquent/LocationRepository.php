<?php

namespace App\Repositories\Eloquent;

use App\Entities\Location;
use App\Traits\ApiResponserTest;
use App\Traits\TransformDataTest;
use App\Repositories\LocationRepositoryInterface;
use App\Transformers\LocationTransformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LocationRepository extends BaseRepository implements LocationRepositoryInterface
{
    use ApiResponserTest, TransformDataTest;
    /**
    * UserRepository constructor.
    *
    * @param Location $model
    */
   public function __construct(Location $model)
   {
       parent::__construct($model);
   }

   /**
    * @return Collection
    */
   public function all(): Collection
   {
       return $this->model->all();
   }

   public function show($id)
   {
       if(!$Location = $this->model::find($id)) return $this->notFound('Location', 404, $id);
       $Location = $this->item($Location, new LocationTransformer());
       return $this->responseJSON('Location with id = '.$id.' found', $Location);
   }

   public function paginator($limit = 5)
   {
        $paginate = $this->model::orderBy('created_at', 'desc')->paginate($limit);
        $paginate = $this->paginate($paginate, new LocationTransformer());
        return $this->responseJSON('List of data found', $paginate);
    }

    public function store($data)
    {
        try {
            //code...
            $new_Location = new $this->model;
            $new_Location = Validator::make($data, $new_Location->storeRules);
            if($new_Location->fails()) return $this->validationError($new_Location->errors());
            $new_Location = ['name' => $data['name'], 'last_updated_by'=> $data['lastUpdatedBy']];
            $new_Location = $this->model::create($new_Location);
            $new_Location = $this->item($new_Location, new LocationTransformer());
            return $this->responseJSON("Data is stored successfully!", $new_Location, 201);
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

   public function update($data, $id)
    {
        try {
            //code...
            $Location = new $this->model;
            $Location = Validator::make($data, $Location->updateRules);
            if($Location->fails()) return $this->validationError($Location->errors());
            if(!$Location = $this->model::find($id)) return $this->notFound('Location', 404, $id);
            $Location->update([
                'name' => $data['name'] ? $data['name']: $Location->name,
                'last_updated_by' => $data['lastUpdatedBy'] ? (int)$data['lastUpdatedBy']: (int)$Location->last_updated_by
            ]);
            $Location = $this->item($Location, new LocationTransformer());
            return $this->responseJSON('Location with id = '.$id.' is updated', $Location);
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    public function destroy($id)
    {
        if(!$Location = $this->model::find($id)) return $this->notFound('Location', 404, $id);
        $Location->delete();
        return $this->responseJSON('Delete success', ['id'=> $id]);
    }
}
