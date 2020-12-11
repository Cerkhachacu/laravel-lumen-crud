<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Entities\Seniority;
use App\Transformers\SeniorityTransformer;

class SeniorityController extends Controller
{
    /**
     * Show all seniority data.
     *
     * @return List of seniority data
     */
    public function index(Request $req)
    {
        try {
            //code...
            $limit = empty($req->input('limit')) ? 5 : $req->input('limit');
            $seniority = Seniority::orderBy('created_at', 'desc')->paginate($limit);
            $seniority = $this->paginate($seniority, new SeniorityTransformer());
            return $this->responseJSON('List of data found', $seniority);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Show a single seniority data.
     *
     * @return Single seniority data
     */
    public function show($id)
    {
        try {
            //code...
            if($seniority = Seniority::find($id)) return $this->notFound('Seniority', 404, $id);
            $seniority = $this->item($seniority, new SeniorityTransformer());
            return $this->responseJSON('Seniority with id = '. $id . ' found', $seniority);
        } catch (\Exception $ex) {
            //throw $th;
            return $this($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Adding a new seniority
     *
     * @request name, last_updated_by
     *
     * @return message success
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:senioritys|max:255',
                'last_updated_by' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->validationError($validator->errors());
            }
            $new_seniority = Seniority::create([
                'name' => $request->input('name'),
                'last_updated_by' => $request->input('last_updated_by')
            ]);
            $new_seniority = $this->item($new_seniority, new SeniorityTransformer());
            DB::commit();
            return $this->responseJSON('Data is stored successfully!', $new_seniority, 201);
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     *
     * Update a single item
     *
     * @require id
     *
     * @return message update success
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'name' => 'unique:senioritys|max:255',
                'last_updated_by' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationError($validator->errors);
            }
            if($seniority = Seniority::find($id)) return $this->notFound('Seniority', 404, $id);
            $seniority->update([
                'name' => $request->input('name') ? $request->input('name'):$seniority->name,
                'last_updated_by' => $request->input('last_updated_by') ? $request->input('last_updated_by'):$seniority->last_updated_by
            ]);
            $seniority = $this->item($seniority, new SeniorityTransformer());
            DB::commit();
            return $this->responseJSON('Seniority with id = '. $id . ' is udpated', $seniority);
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     *
     * Delete seniority by seniority id
     *
     * @require seniority id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
         DB::beginTransaction();
        try {
            //code...
            if($seniority = Seniority::find($id)) return $this->notFound('Seniority', 404, $id);
            $seniority->delete();
            DB::commit();
            return $this->responseJSON('Delete success', ['id'=> $id]);
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
     }
}
