<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Entities\Role;
use App\Transformers\RoleTransformer;

class RoleController extends Controller
{
    /**
     * Show all role data.
     *
     * @return List of role data
     */
    public function index(Request $req)
    {
        try {
            //code...
            $limit = empty($req->input('limit')) ? 5 : $req->input('limit');
            $response = $this->paginate(Role::paginate($limit), new RoleTransformer());
            return $this->responseJSON('List of data found', $response);
        } catch (\Exception $ex) {
            //throw $ex;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Show a single role data.
     *
     * @return Single role data
     */
    public function show($id)
    {
        try {
            //code...
            if(!$role = Role::find($id)) return $this->notFound('Role', 404, $id);
            $role = $this->item($role, new RoleTransformer());
            return $this->responseJSON('List of data found', $role);
            } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Adding a new role
     *
     * @request name, last_update_by
     *
     * @return message success
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:roles|max:255',
                'last_updated_by' => 'required|exists:users,id',
            ]);
            if ($validator->fails()) {
                return $this->validationError($validator->errors());
            }
            $new_role = Role::create([
                'name' => $request->input('name'),
                'last_updated_by' => $request->input('last_updated_by')
            ]);
            $new_role = $this->item($new_role, new RoleTransformer());
            DB::commit();
            return $this->responseJSON('Data is stored successfully!', $new_role, 201);
        } catch (\Exception $ex) {
            //throw $ex;
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
                'name' => 'max:255',
                'last_updated_by' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->validationError($validator->errors());
            }
            if($role = Role::find($id)) return $this->notFound('Role', 404, $id);
            $role->update([
                'name' => $request->input('name') ? $request->input('name'):$role->name,
                'last_updated_by' => $request->input('last_updated_by') ? $request->input('last_updated_by'):$role->last_updated_by
            ]);
            $role = $this->item($role, new RoleTransformer());
            DB::commit();
            return $this->responseJSON('Role with id = ' . $id . ' is updated', $role);
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     *
     * Delete role by role id
     *
     * @require role id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
         DB::beginTransaction();
        try {
            //code...
            if(!$role = Role::find($id)) return $this->notFound('Role', 404, $id);
            $role->delete();
            DB::commit();
            return $this->responseJSON('Delete success', ['id'=> $id]);
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
     }
}
