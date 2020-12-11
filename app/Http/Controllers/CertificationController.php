<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Entities\Certification;
use Illuminate\Support\Facades\DB;
use App\Transformers\CertificationTransformer;

class CertificationController extends Controller
{
    /**
     * Show all certification data.
     *
     * @return List of certification data
     */
    public function index(Request $req)
    {
        try {
            //code...
            $limit = empty($req->input('limit')) ? 5 : $req->input('limit');
            $response = $this->paginate(Certification::orderBy('updated_at', 'desc')->paginate($limit), new CertificationTransformer());
            return $this->responseJSON('List of data found', $response);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Show a single certification data.
     *
     * @return Single certification data
     */
    public function show($id)
    {
        try {
            //code...
            if(!$certification = Certification::find($id)) return $this->notFound('certification', 404, $id);
            $result=$this->item($certification, new CertificationTransformer());
            return $this->responseJSON('Certification found', $result, 200);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Adding a new certification
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
                'name' => 'required|max:255|unique:certifications',
                'last_updated_by' => 'required|exists:users,id'
            ]);
            if ($validator->fails()) {
                return $this->validationError($validator->errors());
            }
            $new_certification = Certification::create([
                'name' => $request->input('name'),
                'last_updated_by' => $request->input('last_updated_by')
            ]);
            $new_certification= $this->item($new_certification, new CertificationTransformer());
            DB::commit();
            return $this->responseJSON('Data is stored', $new_certification, 201);
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'name' => 'max:255|unique:certifications',
                'last_updated_by' => 'required|exists:users,id'
            ]);
            if ($validator->fails()) {
                return $this->validationError($validator->errors());
            }
            if($certification = Certification::find($id)) return $this->notFound('Certification', 404, $id);
            $certification->update([
                'name' => $request->input('name') ? $request->input('name'):$certification->name,
                'last_updated_by' => $request->input('last_updated_by') ? $request->input('last_updated_by'):$certification->last_updated_by
            ]);
            $updatedCertification = $this->item($certification, new CertificationTransformer());
            DB::commit();
            return $this->responseJSON('Certification with id = ' . $id . ' is updated successfully', $updatedCertification);
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     *
     * Delete certification by certification id
     *
     * @require certification id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
        try {
            //code...
            if(!$certification = Certification::find($id)) return $this->notFound('Certification', 404, $id);
            $certification->delete();
            DB::commit();
            return $this->responseJSON('Delete success', []);
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
     }
}
