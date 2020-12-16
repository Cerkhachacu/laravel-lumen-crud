<?php

namespace App\Repositories\Eloquent;

use App\Entities\LastJobPosition;
use App\Traits\ApiResponserTest;
use App\Traits\TransformDataTest;
use App\Repositories\LastJobPositionRepositoryInterface;
use App\Transformers\LastJobPositionTransformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LastJobPositionRepository extends BaseRepository implements LastJobPositionRepositoryInterface
{
    use ApiResponserTest, TransformDataTest;
    /**
    * UserRepository constructor.
    *
    * @param LastJobPosition $model
    */
   public function __construct(LastJobPosition $model)
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
       if(!$LastJobPosition = $this->model::find($id)) return $this->notFound('LastJobPosition', 404, $id);
       $LastJobPosition = $this->item($LastJobPosition, new LastJobPositionTransformer());
       return $this->responseJSON('LastJobPosition with id = '.$id.' found', $LastJobPosition);
   }

   public function paginator($limit = 5)
   {
        $paginate = $this->model::orderBy('created_at', 'desc')->paginate($limit);
        $paginate = $this->paginate($paginate, new LastJobPositionTransformer());
        return $this->responseJSON('List of data found', $paginate);
    }

    public function store($data)
    {
        try {
            //code...
            $new_LastJobPosition = new $this->model;
            $new_LastJobPosition = Validator::make($data, $new_LastJobPosition->storeRules);
            if($new_LastJobPosition->fails()) return $this->validationError($new_LastJobPosition->errors());
            $new_LastJobPosition = ['name' => $data['name'], 'last_updated_by'=> $data['lastUpdatedBy']];
            $new_LastJobPosition = $this->model::create($new_LastJobPosition);
            $new_LastJobPosition = $this->item($new_LastJobPosition, new LastJobPositionTransformer());
            return $this->responseJSON("Data is stored successfully!", $new_LastJobPosition, 201);
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
            $LastJobPosition = new $this->model;
            $LastJobPosition = Validator::make($data, $LastJobPosition->updateRules);
            if($LastJobPosition->fails()) return $this->validationError($LastJobPosition->errors());
            if(!$LastJobPosition = $this->model::find($id)) return $this->notFound('LastJobPosition', 404, $id);
            $LastJobPosition->update([
                'name' => $data['name'] ? $data['name']: $LastJobPosition->name,
                'last_updated_by' => $data['lastUpdatedBy'] ? (int)$data['lastUpdatedBy']: (int)$LastJobPosition->last_updated_by
            ]);
            $LastJobPosition = $this->item($LastJobPosition, new LastJobPositionTransformer());
            return $this->responseJSON('LastJobPosition with id = '.$id.' is updated', $LastJobPosition);
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    public function destroy($id)
    {
        if(!$LastJobPosition = $this->model::find($id)) return $this->notFound('LastJobPosition', 404, $id);
        $LastJobPosition->delete();
        return $this->responseJSON('Delete success', ['id'=> $id]);
    }
}
