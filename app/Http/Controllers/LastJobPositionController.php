<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Entities\LastJobPosition;
use App\Transformers\LastJobPositionTransformer;

class LastJobPositionController extends Controller
{
    /**
     * Show all lastjobposition data.
     *
     * @return List of lastjobposition data
     */
    public function index(Request $req)
    {
        try {
            //code...
            $limit = empty($req->input('limit')) ? 5 : $req->input('limit');
            $lastjobposition = LastJobPosition::orderBy('created_at', 'desc')->paginate($limit);
            $response = $this->paginate($lastjobposition, new LastJobPositionTransformer());
            return $this->responseJSON('List of data found', $response);
        } catch (\Exception $ex) {
            //throw $ex;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Show a single lastjobposition data.
     *
     * @return Single lastjobposition data
     */
    public function show($id)
    {
        try {
            //code...
            if(!$lastjobposition = LastJobPosition::find($id)) return $this->notFound('Last Job Position', 404, $id);
            $response = $this->item($lastjobposition, new LastJobPositionTransformer());
            return $this->responseJSON('Last job position with id = '. $id .' found', $response);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Adding a new lastjobposition
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
                'name' => 'required|max:255|unique:lastjobpositions',
                'last_updated_by' => 'required|exists:users,id'
            ]);
            if ($validator->fails()) {
                return $this->validationError($validator->errors());
            }
            $new_lastjobposition = LastJobPosition::create([
                'name' => $request->input('name'),
                'last_updated_by' => $request->input('last_updated_by')
            ]);
            $new_lastjobposition = $this->item($new_lastjobposition, new LastJobPositionTransformer());
            DB::commit();
            return $this->responseJSON('Data is stored successfully', $new_lastjobposition);
        } catch (\Exception $ex) {
            //throw $ex;
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
                'name' => 'unique:lastjobpositions|max:255',
                'last_updated_by' => 'required|exists:users,id'
            ]);
            if ($validator->fails()) {
                return $this->validationError($validator->errors());
            }
            if($lastjobposition = LastJobPosition::find($id)) return $this->notFound('Last Job Position', 404, $id);
            $lastjobposition->update([
                'name' => $request->input('name') ? $request->input('name'):$lastjobposition->name,
                'last_updated_by' => $request->input('last_updated_by') ? $request->input('last_updated_by'):$lastjobposition->last_updated_by
            ]);
            $response = $this->item($lastjobposition, new LastJobPositionTransformer());
            DB::commit();
            return $this->responseJSON('Last job position with id = '. $id . ' is updated', $response);
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     *
     * Delete lastjobposition by lastjobposition id
     *
     * @require lastjobposition id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
         DB::beginTransaction();
        try {
            //code...
            if(!$lastjob = LastJobPosition::find($id)) return $this->notFound('Last Job Position', 404, $id);
            $lastjob->delete();
            DB::commit();
            return $this->responseJSON('Delete success!', ['id'=>$id]);
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
     }
}
