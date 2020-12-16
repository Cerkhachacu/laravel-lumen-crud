<?php

namespace App\Repositories\Eloquent;

use App\Entities\DrivingLicense;
use App\Traits\ApiResponserTest;
use App\Traits\TransformDataTest;
use App\Repositories\DrivingLicenseRepositoryInterface;
use App\Transformers\DrivingLicenseTransformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DrivingLicenseRepository extends BaseRepository implements DrivingLicenseRepositoryInterface
{
    use ApiResponserTest, TransformDataTest;
    /**
    * UserRepository constructor.
    *
    * @param DrivingLicense $model
    */
   public function __construct(DrivingLicense $model)
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
       if(!$DrivingLicense = $this->model::find($id)) return $this->notFound('DrivingLicense', 404, $id);
       $DrivingLicense = $this->item($DrivingLicense, new DrivingLicenseTransformer());
       return $this->responseJSON('DrivingLicense with id = '.$id.' found', $DrivingLicense);
   }

   public function paginator($limit = 5)
   {
        $paginate = $this->model::orderBy('created_at', 'desc')->paginate($limit);
        $paginate = $this->paginate($paginate, new DrivingLicenseTransformer());
        return $this->responseJSON('List of data found', $paginate);
    }

    public function store($data)
    {
        try {
            //code...
            $new_DrivingLicense = new $this->model;
            $new_DrivingLicense = Validator::make($data, $new_DrivingLicense->storeRules);
            if($new_DrivingLicense->fails()) return $this->validationError($new_DrivingLicense->errors());
            $new_DrivingLicense = ['name' => $data['name'], 'last_updated_by'=> (int)$data['lastUpdatedBy']];
            $new_DrivingLicense = $this->model::create($new_DrivingLicense);
            $new_DrivingLicense = $this->item($new_DrivingLicense, new DrivingLicenseTransformer());
            return $this->responseJSON("Data is stored successfully!", $new_DrivingLicense, 201);
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
            $DrivingLicense = new $this->model;
            $DrivingLicense = Validator::make($data, $DrivingLicense->updateRules);
            if($DrivingLicense->fails()) return $this->validationError($DrivingLicense->errors());
            if(!$DrivingLicense = $this->model::find($id)) return $this->notFound('DrivingLicense', 404, $id);
            $DrivingLicense->update([
                'name' => $data['name'] ? $data['name']: $DrivingLicense->name,
                'last_updated_by' => $data['lastUpdatedBy'] ? (int)$data['lastUpdatedBy']: (int)$DrivingLicense->last_updated_by
            ]);
            $DrivingLicense = $this->item($DrivingLicense, new DrivingLicenseTransformer());
            return $this->responseJSON('DrivingLicense with id = '.$id.' is updated', $DrivingLicense);
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    public function destroy($id)
    {
        if(!$DrivingLicense = $this->model::find($id)) return $this->notFound('DrivingLicense', 404, $id);
        $DrivingLicense->delete();
        return $this->responseJSON('Delete success', ['id'=> $id]);
    }
}
