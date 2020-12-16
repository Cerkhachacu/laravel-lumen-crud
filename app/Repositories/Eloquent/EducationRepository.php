<?php

namespace App\Repositories\Eloquent;

use App\Entities\Education;
use App\Traits\ApiResponserTest;
use App\Traits\TransformDataTest;
use App\Repositories\EducationRepositoryInterface;
use App\Transformers\EducationTransformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EducationRepository extends BaseRepository implements EducationRepositoryInterface
{
    use ApiResponserTest, TransformDataTest;
    /**
    * UserRepository constructor.
    *
    * @param Education $model
    */
   public function __construct(Education $model)
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
       if(!$education = $this->model::find($id)) return $this->notFound('Education', 404, $id);
       $education = $this->item($education, new EducationTransformer());
       return $this->responseJSON('Education with id = '.$id.' found', $education);
   }

   public function paginator($limit = 5)
   {
        $paginate = $this->model::orderBy('created_at', 'desc')->paginate($limit);
        $paginate = $this->paginate($paginate, new EducationTransformer());
        return $this->responseJSON('List of data found', $paginate);
    }

    public function store($data)
    {
        try {
            //code...
            $new_education = new $this->model;
            $new_education = Validator::make($data, $new_education->storeRules);
            if($new_education->fails()) return $this->validationError($new_education->errors());
            $new_education = ['name' => $data['name'], 'last_updated_by'=> (int)$data['lastUpdatedBy']];
            $new_education = $this->model::create($new_education);
            $new_education = $this->item($new_education, new EducationTransformer());
            return $this->responseJSON("Data is stored successfully!", $new_education, 201);
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
            $education = new $this->model;
            $education = Validator::make($data, $education->updateRules);
            if($education->fails()) return $this->validationError($education->errors());
            if(!$education = $this->model::find($id)) return $this->notFound('Education', 404, $id);
            $education->update([
                'name' => $data['name'] ? $data['name']: $education->name,
                'last_updated_by' => $data['lastUpdatedBy'] ? (int)$data['lastUpdatedBy']: (int)$education->last_updated_by
            ]);
            $education = $this->item($education, new EducationTransformer());
            return $this->responseJSON('Education with id = '.$id.' is updated', $education);
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    public function destroy($id)
    {
        if(!$education = $this->model::find($id)) return $this->notFound('Education', 404, $id);
        $education->delete();
        return $this->responseJSON('Delete success', ['id'=> $id]);
    }
}
