<?php

namespace App\Repositories\Eloquent;

use App\Entities\Seniority;
use App\Traits\ApiResponserTest;
use App\Traits\TransformDataTest;
use App\Repositories\SeniorityRepositoryInterface;
use App\Transformers\SeniorityTransformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SeniorityRepository extends BaseRepository implements SeniorityRepositoryInterface
{
    use ApiResponserTest, TransformDataTest;
    /**
    * UserRepository constructor.
    *
    * @param Seniority $model
    */
   public function __construct(Seniority $model)
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
       if(!$Seniority = $this->model::find($id)) return $this->notFound('Seniority', 404, $id);
       $Seniority = $this->item($Seniority, new SeniorityTransformer());
       return $this->responseJSON('Seniority with id = '.$id.' found', $Seniority);
   }

   public function paginator($limit = 5)
   {
        $paginate = $this->model::orderBy('created_at', 'desc')->paginate($limit);
        $paginate = $this->paginate($paginate, new SeniorityTransformer());
        return $this->responseJSON('List of data found', $paginate);
    }

    public function store($data)
    {
        try {
            //code...
            $new_Seniority = new $this->model;
            $new_Seniority = Validator::make($data, $new_Seniority->storeRules);
            if($new_Seniority->fails()) return $this->validationError($new_Seniority->errors());
            $new_Seniority = ['name' => $data['name'], 'last_updated_by'=> $data['lastUpdatedBy']];
            $new_Seniority = $this->model::create($new_Seniority);
            $new_Seniority = $this->item($new_Seniority, new SeniorityTransformer());
            return $this->responseJSON("Data is stored successfully!", $new_Seniority, 201);
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
            $Seniority = new $this->model;
            $Seniority = Validator::make($data, $Seniority->updateRules);
            if($Seniority->fails()) return $this->validationError($Seniority->errors());
            if(!$Seniority = $this->model::find($id)) return $this->notFound('Seniority', 404, $id);
            $Seniority->update([
                'name' => $data['name'] ? $data['name']: $Seniority->name,
                'last_updated_by' => $data['lastUpdatedBy'] ? (int)$data['lastUpdatedBy']: (int)$Seniority->last_updated_by
            ]);
            $Seniority = $this->item($Seniority, new SeniorityTransformer());
            return $this->responseJSON('Seniority with id = '.$id.' is updated', $Seniority);
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    public function destroy($id)
    {
        if(!$Seniority = $this->model::find($id)) return $this->notFound('Seniority',  $id);
        $Seniority->delete();
        return $this->responseJSON('Delete success', ['id'=> $id]);
    }
}
