<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Entities\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|unique:users|max:255',
                'password' => 'required',
                'c_password' => 'required|same:password',
                'role_id' => 'required|exists:roles,id',
                'is_active' => 'required'
            ]);
            if($validator->fails()) return $this->validationError($validator->errors());

            $new_user = $request->except('c_password');
            $new_user['password'] = Hash::make($new_user['password']);
            $new_user['role_id'] = (int)$new_user['role_id'];
            $new_user = User::create($new_user);
            $new_user->attributes['token'] =  $new_user->createToken('MyApp')->accessToken;
            DB::commit();
            return $this->responseJSON('Register successfully', $new_user, 201);
        } catch (\Exception $ex) {
            //throw $ex;
            DB::rollBack();
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }
    public function login(Request $request)
    {
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'email'=> 'required|max:255',
                'password'=> 'required|min:4'
            ]);
            if($validator->fails()) return $this->validationError($validator->errors());
            if(!$user=User::where('email', '=', $request->email)->firstOrFail()) return $this->notFound('User', 404, 1, 'Email is not registered');
            if(!Hash::check($request->password, $user['password'])) return $this->notFound('User', 401, 1, 'Password is incorrect!');
            $user = ['email'=> $user->email, 'token'=> 'Bearer ' . $user->createToken('MyApp')->accessToken];
            return $this->responseJSON('Login Success', $user);

        } catch (\Exception $ex) {
            //throw $ex;
            return $this->otherError($ex->getMessage(), $ex->getCode());
        }
    }
}
