<?php

namespace App\Repositories\Eloquent;

use App\Entities\Experience;
use App\Traits\ApiResponserTest;
use App\Traits\TransformDataTest;
use App\Repositories\ExperienceRepositoryInterface;
use App\Transformers\ExperienceTransformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ExperienceRepository extends BaseRepository implements ExperienceRepositoryInterface
{
    use ApiResponserTest, TransformDataTest;
    /**
    * UserRepository constructor.
    *
    * @param Experience $model
    */
   public function __construct(Experience $model)
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
       if(!$Experience = $this->model::find($id)) return $this->notFound('Experience', 404, $id);
       $Experience = $this->item($Experience, new ExperienceTransformer());
       return $this->responseJSON('Experience with id = '.$id.' found', $Experience);
   }

   public function paginator($limit = 5)
   {
        $paginate = $this->model::orderBy('created_at', 'desc')->paginate($limit);
        $paginate = $this->paginate($paginate, new ExperienceTransformer());
        return $this->responseJSON('List of data found', $paginate);
    }

    public function store($data)
    {
        try {
            //code...
            $new_Experience = new $this->model;
            $new_Experience = Validator::make($data, $new_Experience->storeRules);
            if($new_Experience->fails()) return $this->validationError($new_Experience->errors());
            $new_Experience = ['name' => $data['name'], 'last_updated_by'=> $data['lastUpdatedBy']];
            $new_Experience = $this->model::create($new_Experience);
            $new_Experience = $this->item($new_Experience, new ExperienceTransformer());
            return $this->responseJSON("Data is stored successfully!", $new_Experience, 201);
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
            $Experience = new $this->model;
            $Experience = Validator::make($data, $Experience->updateRules);
            if($Experience->fails()) return $this->validationError($Experience->errors());
            if(!$Experience = $this->model::find($id)) return $this->notFound('Experience', 404, $id);
            $Experience->update([
                'name' => $data['name'] ? $data['name']: $Experience->name,
                'last_updated_by' => $data['lastUpdatedBy'] ? (int)$data['lastUpdatedBy']: (int)$Experience->last_updated_by
            ]);
            $Experience = $this->item($Experience, new ExperienceTransformer());
            return $this->responseJSON('Experience with id = '.$id.' is updated', $Experience);
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    public function destroy($id)
    {
        if(!$Experience = $this->model::find($id)) return $this->notFound('Experience', 404, $id);
        $Experience->delete();
        return $this->responseJSON('Delete success', ['id'=> $id]);
    }
}
