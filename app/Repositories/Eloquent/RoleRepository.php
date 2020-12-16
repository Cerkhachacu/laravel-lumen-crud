<?php

namespace App\Repositories\Eloquent;

use App\Entities\Role;
use App\Traits\ApiResponserTest;
use App\Traits\TransformDataTest;
use App\Repositories\RoleRepositoryInterface;
use App\Transformers\RoleTransformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    use ApiResponserTest, TransformDataTest;
    /**
    * UserRepository constructor.
    *
    * @param Role $model
    */
   public function __construct(Role $model)
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
       if(!$Role = $this->model::find($id)) return $this->notFound('Role', 404, $id);
       $Role = $this->item($Role, new RoleTransformer());
       return $this->responseJSON('Role with id = '.$id.' found', $Role);
   }

   public function paginator($limit = 5)
   {
        $paginate = $this->model::orderBy('created_at', 'desc')->paginate($limit);
        $paginate = $this->paginate($paginate, new RoleTransformer());
        return $this->responseJSON('List of data found', $paginate);
    }

    public function store($data)
    {
        try {
            //code...
            $new_Role = new $this->model;
            $new_Role = Validator::make($data, $new_Role->storeRules);
            if($new_Role->fails()) return $this->validationError($new_Role->errors());
            $new_Role = ['name' => $data['name'], 'last_updated_by'=> $data['lastUpdatedBy']];
            $new_Role = $this->model::create($new_Role);
            $new_Role = $this->item($new_Role, new RoleTransformer());
            return $this->responseJSON("Data is stored successfully!", $new_Role, 201);
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
            $Role = new $this->model;
            $Role = Validator::make($data, $Role->updateRules);
            if($Role->fails()) return $this->validationError($Role->errors());
            if(!$Role = $this->model::find($id)) return $this->notFound('Role', 404, $id);
            $Role->update([
                'name' => $data['name'] ? $data['name']: $Role->name,
                'last_updated_by' => $data['lastUpdatedBy'] ? (int)$data['lastUpdatedBy']: (int)$Role->last_updated_by
            ]);
            $Role = $this->item($Role, new RoleTransformer());
            return $this->responseJSON('Role with id = '.$id.' is updated', $Role);
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    public function destroy($id)
    {
        if(!$Role = $this->model::find($id)) return $this->notFound('Role', 404, $id);
        $Role->delete();
        return $this->responseJSON('Delete success', ['id'=> $id]);
    }
}
