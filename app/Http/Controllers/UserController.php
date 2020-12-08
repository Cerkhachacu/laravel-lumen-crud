<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Entities\User;

class UserController extends Controller
{
    /**
     * Show all user data.
     *
     * @return List of user data
     */
    public function index()
    {
        $user = User::orderBy('created_at', 'desc')->paginate(5);
        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => $user
        ], 200);
    }

    /**
     * Show a single user data.
     *
     * @return Single user data
     */
    public function show($id)
    {

        if(!$user = User::find($id)) return response()->json(['success'=> false, 'message'=> 'user not found'], 404);
        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => $user
        ],200);
    }

    /**
     * Adding a new user
     *
     * @request first_name, last_name, email, password, role
     *
     * @return message success
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|unique:users|max:255|email',
            'password' => 'required',
            'role' => 'required|exists:roles,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'code' => 422,
                'message' => $validator->errors()
            ], 422);
        }
        if(!$new_user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
        ])) return response()->json(['code'=>500, 'message'=>$new_user], 500);
        return response()->json([
            'code' => 201,
            'success'=> true,
            'message'=> 'A new user added successfully',
            'data' => $new_user
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
            'first_name' => 'max:255',
            'last_name' => 'max:255',
            'email' => 'unique:users|max:255',
            'role' => 'exists:roles,id'
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
            $user = User::find($id);
            $user->update([
                'first_name' => $request->input('first_name') ? $request->input('first_name'):$user->first_name,
                'last_name' => $request->input('last_name') ? $request->input('last_name'):$user->last_name,
                'email' => $request->input('email') ? $request->input('email'):$user->email,
                'password' => Hash::make($request->password) ? Hash::make($request->password):$user->password,
                'role' => $request->input('role') ? $request->input('role'):$user->role
            ]);
            return response()->json([
                'success'=> true,
                'message'=> 'update success',
                'data' => $user
            ], 201);
            } catch (ModelNotFoundException $ex) {
            //throw $th;
            return response()->json([
                'code'=> 404,
                'success'=> false,
                'message'=> 'User is not exist'
            ]);
        }
    }

    /**
     *
     * Delete user by user id
     *
     * @require user id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
        if(!User::destroy($id)) return response()->json(['success' => false, 'message' => 'User not found'], 404);
        return response()->json([
            'code' => 200,
            'success'=> true,
            'message'=> 'Delete Success'
        ], 200);
     }
}
