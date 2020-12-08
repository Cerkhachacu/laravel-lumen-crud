<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Entities\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|max:255',
            'lastName' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
            'password' => 'required',
            'cPassword' => 'required|same:password',
            'role' => 'required'
        ]);
        if($validator->fails()) return response()->json([
            'code'=> 422,
            'success'=> false,
            'message'=> 'Validation Error',
            'errors' => $validator->errors()
        ], 422);

        $new_user = $request->except('c_password');
        $new_user['password'] = Hash::make($new_user['password']);
        $user = User::create($new_user);

        $data['token'] =  $user->createToken('MyApp')->accessToken;
        $data['email'] =  $user->name;

        return response()->json([
            'code'=> 201,
            'success' => true,
            'message' => 'Register success',
            'data' => $data
        ], 201);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=> 'required|max:255',
            'password'=> 'required|min:4'
        ]);
        if($validator->fails()) return response()->json([
            'code'=> 422,
            'success'=> true,
            'message'=> 'validation error',
            'errors'=> $validator->errors()
        ]);
        try {
            //code...
            $check_user=User::where('email', '=', $request->email)->firstOrFail();
            if(!Hash::check($request->password, $check_user['password'])) return response()->json([
                'code'=> 401,
                'success'=> false,
                'message'=> 'Password does not match'
            ], 401);
            return response()->json([
                'code'=> 200,
                'success'=> true,
                'message'=> 'Login success',
                'data' => [
                    'email'=> $check_user->email,
                    'token'=> $check_user->createToken('MyApp')->accessToken
                ]
            ]);
        } catch (ModelNotFoundException $ex) {
            //throw $th;
            return response()->json([
                'code'=> 404,
                'success'=> false,
                'message'=> 'email is not registered'
            ]);
        }
    }
}
