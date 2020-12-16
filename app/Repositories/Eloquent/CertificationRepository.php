<?php

namespace App\Repositories\Eloquent;

use App\Entities\Certification;
use App\Traits\ApiResponserTest;
use App\Traits\TransformDataTest;
use App\Repositories\CertificationRepositoryInterface;
use App\Transformers\CertificationTransformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CertificationRepository extends BaseRepository implements CertificationRepositoryInterface
{
    use ApiResponserTest, TransformDataTest;
    /**
    * UserRepository constructor.
    *
    * @param Certification $model
    */
   public function __construct(Certification $model)
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
       if(!$Certification = $this->model::find($id)) return $this->notFound('Certification', 404, $id);
       $Certification = $this->item($Certification, new CertificationTransformer());
       return $this->responseJSON('Certification with id = '.$id.' found', $Certification);
   }

   public function paginator($limit = 5)
   {
        $paginate = $this->model::orderBy('created_at', 'desc')->paginate($limit);
        $paginate = $this->paginate($paginate, new CertificationTransformer());
        return $this->responseJSON('List of data found', $paginate);
    }

    public function store($data)
    {
        try {
            //code...
            $new_Certification = new $this->model;
            $new_Certification = Validator::make($data, $new_Certification->storeRules);
            if($new_Certification->fails()) return $this->validationError($new_Certification->errors());
            $new_Certification = ['name' => $data['name'], 'last_updated_by'=> $data['lastUpdatedBy']];
            $new_Certification = $this->model::create($new_Certification);
            $new_Certification = $this->item($new_Certification, new CertificationTransformer());
            return $this->responseJSON("Data is stored successfully!", $new_Certification, 201);
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
            $Certification = new $this->model;
            $Certification = Validator::make($data, $Certification->updateRules);
            if($Certification->fails()) return $this->validationError($Certification->errors());
            if(!$Certification = $this->model::find($id)) return $this->notFound('Certification', 404, $id);
            $Certification->update([
                'name' => $data['name'] ? $data['name']: $Certification->name,
                'last_updated_by' => $data['lastUpdatedBy'] ? (int)$data['lastUpdatedBy']: (int)$Certification->last_updated_by
            ]);
            $Certification = $this->item($Certification, new CertificationTransformer());
            return $this->responseJSON('Certification with id = '.$id.' is updated', $Certification);
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    public function destroy($id)
    {
        if(!$Certification = $this->model::find($id)) return $this->notFound('Certification', 404, $id);
        $Certification->delete();
        return $this->responseJSON('Delete success', ['id'=> $id]);
    }
}
