<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use App\Entities\Role;

class RoleController extends Controller
{
    /**
     * Show all role data.
     *
     * @return List of role data
     */
    public function index()
    {
        $role = Role::orderBy('created_at', 'desc')->paginate(5);
        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => $role
        ], 200);
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
            } catch (ModelNotFoundException $ex) {
            //throw $th;
            return response()->json([
                'code'=> 404,
                'success'=> false,
                'message'=> 'Role is not exist'
            ]);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|max:255',
            'last_updated_by' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'code' => 422,
                'message' => $validator->errors()
            ], 422);
        }
        if(!$new_role = Role::create([
            'name' => $request->input('name'),
            'last_updated_by' => $request->input('last_updated_by')
        ])) return response()->json(['code'=>500, 'message'=>$new_role], 500);
        return response()->json([
            'code' => 201,
            'success'=> true,
            'message'=> 'A new role added successfully',
            'data' => $new_role
            ], 201);
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
        $validator = Validator::make($request->all(), [
            'name' => 'max:255',
            'last_updated_by' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'success'=> false,
                'message' => $validator->errors()
            ]);
        }
        try {
            //code...
            $role = Role::find($id);
            $role->update([
                'name' => $request->input('name') ? $request->input('name'):$role->name,
                'last_updated_by' => $request->input('last_updated_by') ? $request->input('last_updated_by'):$role->last_updated_by
            ]);
            return response()->json([
                'success'=> true,
                'message'=> 'update success',
                'data' => $role
            ], 201);
        } catch (ModelNotFoundException $ex) {
            //throw $th;
            return response()->json([
                'code'=> 404,
                'success'=> false,
                'message'=> 'Role is not exist'
            ]);
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
        if(!Role::destroy($id)) return response()->json(['success' => false, 'message' => 'role not found'], 404);
        return response()->json([
            'code' => 200,
            'success'=> true,
            'message'=> 'Delete Success'
        ], 200);
     }
}
