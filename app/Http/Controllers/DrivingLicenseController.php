<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Entities\DrivingLicense;
use Illuminate\Support\Facades\DB;
use App\Transformers\DrivingLicenseTransformer;

class DrivingLicenseController extends Controller
{
    /**
     * Show all drivinglicense data.
     *
     * @return List of drivinglicense data
     */
    public function index(Request $req)
    {
        try {
            //code...
            $limit = empty($req->input('limit')) ? 5 : $req->input('limit');
            $drivinglicense = DrivingLicense::orderBy('created_at', 'desc')->paginate($limit);
            $data = $this->paginate($drivinglicense, new DrivingLicenseTransformer());
            return $this->showResult('List of data found', $data);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Show a single drivinglicense data.
     *
     * @return Single drivinglicense data
     */
    public function show($id)
    {
        try {
            //code...
            if(!$drivinglicense = DrivingLicense::find($id)) return $this->notFound('Driving License', 404, $id);
            $response=$this->item($drivinglicense, new DrivingLicenseTransformer());
            return $this->showResult('Certification with id = '.$id.'is found', $response);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Adding a new drivinglicense
     *
     * @request name, last_updated_by, email, password, role
     *
     * @return message success
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:categories|max:255',
                'last_updated_by' => 'required|exists:users,id'
            ]);
            if ($validator->fails()) {
                return $this->validationError($validator->errors());

            }
            $new_drivinglicense = DrivingLicense::create([
                'name' => $request->input('name'),
                'last_updated_by' => $request->input('last_updated_by')
            ]);
            $response = $this->item($new_drivinglicense, new DrivingLicenseTransformer());
            DB::commit();
            return $this->showResult('Data is stored', $response, 201);
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Update a sinle data
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'name' => 'max:255|unique:categories',
                'last_updated_by' => 'required|exists:users,id'
            ]);
            if ($validator->fails()) {
                return $this->validationError($validator->errors());
            }
            if($drivinglicense = DrivingLicense::find($id)) return $this->notFound('Driving License', 404, $id);
            $drivinglicense->update([
                'name' => $request->input('name') ? $request->input('name'):$drivinglicense->name,
                'last_updated_by' => $request->input('last_updated_by') ? $request->input('last_updated_by'):$drivinglicense->last_updated_by
            ]);
            $response = $this->item($drivinglicense, new DrivingLicenseTransformer());
            DB::commit();
            return $this->showResult('Driving License with id = '.$id.' is updated successfully', $response);
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     *
     * Delete drivinglicense by drivinglicense id
     *
     * @require drivinglicense id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
        try {
            //code...
            if($deleteLicense=!DrivingLicense::find($id)) return $this->notFound('Certification', 404, $id) ;
            $deleteLicense->delete();
            DB::commit();
            return $this->responseJSON('Delete success', ['id'=> $id]);
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
     }
}
