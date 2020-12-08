<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Entities\Education;

class EducationController extends Controller
{
    /**
     * Show all education data.
     *
     * @return List of education data
     */
    public function index()
    {
        $education = Education::orderBy('created_at', 'desc')->paginate(5);
        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => $education
        ], 200);
    }

    /**
     * Show a single education data.
     *
     * @return Single education data
     */
    public function show($id)
    {
        try {
            //code...
            $education = Education::find($id);
            return response()->json([
                'code' => 200,
                'success' => true,
                'data' => $education
            ],200);
        } catch (ModelNotFoundException $ex) {
            //throw $th;
            return response()->json([
                'code'=> 404,
                'success'=> false,
                'message'=> 'Education is not exist'
            ]);
        }
    }

    /**
     * Adding a new education
     *
     * @request name, last_updated_by, email, password, role
     *
     * @return message success
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'unique:educations|required|max:255',
            'last_updated_by' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'code' => 422,
                'message' => $validator->errors()
            ], 422);
        }
        if(!$new_education = Education::create([
            'name' => $request->input('name'),
            'last_updated_by' => $request->input('last_updated_by')
        ])) return response()->json(['code'=>500, 'message'=>$new_education], 500);
        return response()->json([
            'code' => 201,
            'success'=> true,
            'message'=> 'A new education added successfully',
            'data' => $new_education
            ], 201);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'unique:educations|max:255',
            'last_updated_by' => 'required|exists:users,id'
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
            $education = Education::find($id);
            $education->update([
                'name' => $request->input('name') ? $request->input('name'):$education->name,
                'last_updated_by' => $request->input('last_updated_by') ? $request->input('last_updated_by'):$education->last_updated_by
            ]);
            return response()->json([
                'success'=> true,
                'message'=> 'update success',
                'data' => $education
            ], 201);
        } catch (ModelNotFoundException $ex) {
            //throw $th;
            return response()->json([
                'code'=> 404,
                'success'=> false,
                'message'=> 'Education is not exist'
            ]);
        }
    }

    /**
     *
     * Delete education by education id
     *
     * @require education id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
        if(!Education::destroy($id)) return response()->json(['success' => false, 'message' => 'education not found'], 404);
        return response()->json([
            'code' => 200,
            'success'=> true,
            'message'=> 'Delete Success'
        ], 200);
     }
}
