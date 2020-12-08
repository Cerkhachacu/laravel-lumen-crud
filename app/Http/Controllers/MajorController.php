<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use App\Entities\Major;

class MajorController extends Controller
{
    /**
     * Show all major data.
     *
     * @return List of major data
     */
    public function index()
    {
        $major = Major::orderBy('created_at', 'desc')->paginate(5);
        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => $major
        ], 200);
    }

    /**
     * Show a single major data.
     *
     * @return Single major data
     */
    public function show($id)
    {
        try {
            //code...
            $major = Major::find($id);
            return response()->json([
                'code' => 200,
                'success' => true,
                'data' => $major
            ],200);
        } catch (ModelNotFoundException $ex) {
            //throw $th;
            return response()->json([
                'code'=> 404,
                'success'=> false,
                'message'=> 'Language is not exist'
            ]);
        }
    }

    /**
     * Adding a new major
     *
     * @request name, last_updated_by, email, password, role
     *
     * @return message success
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:majors',
            'last_updated_by' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'code' => 422,
                'message' => $validator->errors()
            ], 422);
        }
        if(!$new_major = Major::create([
            'name' => $request->input('name'),
            'last_updated_by' => $request->input('last_updated_by')
        ])) return response()->json(['code'=>500, 'message'=>$new_major], 500);
        return response()->json([
            'code' => 201,
            'success'=> true,
            'message'=> 'A new major added successfully',
            'data' => $new_major
            ], 201);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'unique:majors|max:255',
            'last_updated_by' => 'exists:users,id|required'
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
            $major = Major::find($id);
            $major->update([
                'name' => $request->input('name') ? $request->input('name'):$major->name,
                'last_updated_by' => $request->input('last_updated_by') ? $request->input('last_updated_by'):$major->last_updated_by
            ]);
            return response()->json([
                'success'=> true,
                'message'=> 'update success',
                'data' => $major
            ], 201);
        } catch (ModelNotFoundException $ex) {
            //throw $th;
            return response()->json([
                'code'=> 404,
                'success'=> false,
                'message'=> 'Language is not exist'
            ]);
        }
    }

    /**
     *
     * Delete major by major id
     *
     * @require major id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
        if(!Major::destroy($id)) return response()->json(['success' => false, 'message' => 'major not found'], 404);
        return response()->json([
            'code' => 200,
            'success'=> true,
            'message'=> 'Delete Success'
        ], 200);
     }
}
