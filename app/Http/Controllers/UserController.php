<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Entities\User;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Show all user data.
     *
     * @return List of user data
     */
    public function index(Request $req)
    {
        try {
            //code...
            $limit = empty($req->input('limit')) ? 5 : $req->input('limit');
            $user = $this->paginate(User::orderBy('created_at', 'desc')->paginate($limit), new UserTransformer());
            return $this->responseJSON('List of data found', $user);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Show a single user data.
     *
     * @return Single user data
     */
    public function show($id)
    {
        try {
            //code...
            if(!$user = User::find($id)) return $this->notFound('User', 404, $id);
            $user = $this->item($user, new UserTransformer());
            return $this->responseJSON('User with id = '. $id . ' is found', $user);
        } catch (\Exception $ex) {
            //throw $th;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
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
        DB::beginTransaction();
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|unique:users|max:255|email',
                'password' => 'required',
                'role' => 'required|exists:roles,id',
            ]);
            if ($validator->fails()) {
                return $this->validationError($validator->errors());
            }
            $new_user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'role' => $request->input('role'),
            ]);
            $new_user = $this->item($new_user, new UserTransformer());
            DB::commit();
            return $this->responseJSON('Data is stored successfully!', $new_user);
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
                'first_name' => 'max:255',
                'last_name' => 'max:255',
                'email' => 'unique:users|max:255',
                'role' => 'exists:roles,id'
            ]);
            if ($validator->fails()) {
                return $this->validationError($validator->errors());
            }
            if($user = User::find($id)) return $this->notFound('User', 404, $id);
            $user->update([
                'first_name' => $request->input('first_name') ? $request->input('first_name'):$user->first_name,
                'last_name' => $request->input('last_name') ? $request->input('last_name'):$user->last_name,
                'email' => $request->input('email') ? $request->input('email'):$user->email,
                'password' => Hash::make($request->password) ? Hash::make($request->password):$user->password,
                'role' => $request->input('role') ? $request->input('role'):$user->role
            ]);
            $user = $this->item($user, new UserTransformer());
            DB::commit();
            return $this->responseJSON('User with id = '. $id . ' is found', $user);
        } catch (\Exception $ex) {
            //throw $th;
            DB::rollback();
            return $this->otherError($ex->getMessage(), $ex->getCode());
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
         DB::beginTransaction();
         try {
             //code...
             if(!$user = User::find($id)) return $this->notFound('User', 404, $id);
             $user->delete();
             DB::commit();
             return $this->responseJSON('Delete success', ['id'=> $id]);
         } catch (\Exception $ex) {
             //throw $ex;
             DB::rollback();
             return $this->otherError($ex->getMessage(), $ex->getCode());
         }
     }
}
