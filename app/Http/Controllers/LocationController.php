<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use App\Entities\Location;

class LocationController extends Controller
{
    /**
     * Show all location data.
     *
     * @return List of location data
     */
    public function index()
    {
        $location = Location::orderBy('created_at', 'desc')->paginate(5);
        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => $location
        ], 200);
    }

    /**
     * Show a single location data.
     *
     * @return Single location data
     */
    public function show($id)
    {
        try {
            //code...
            $location = Location::find($id);
            return response()->json([
                'code' => 200,
                'success' => true,
                'data' => $location
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
     * Adding a new location
     *
     * @request name, last_updated_by, email, password, role
     *
     * @return message success
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:locations',
            'last_updated_by' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'code' => 422,
                'message' => $validator->errors()
            ], 422);
        }
        if(!$new_location = Location::create([
            'name' => $request->input('name'),
            'last_updated_by' => $request->input('last_updated_by')
        ])) return response()->json(['code'=>500, 'message'=>$new_location], 500);
        return response()->json([
            'code' => 201,
            'success'=> true,
            'message'=> 'A new location added successfully',
            'data' => $new_location
            ], 201);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'unique:locations|max:255',
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
            $location = Location::find($id);
            $location->update([
                'name' => $request->input('name') ? $request->input('name'):$location->name,
                'last_updated_by' => $request->input('last_updated_by') ? $request->input('last_updated_by'):$location->last_updated_by
            ]);
            return response()->json([
                'success'=> true,
                'message'=> 'update success',
                'data' => $location
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
     * Delete location by location id
     *
     * @require location id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
        if(!Location::destroy($id)) return response()->json(['success' => false, 'message' => 'location not found'], 404);
        return response()->json([
            'code' => 200,
            'success'=> true,
            'message'=> 'Delete Success'
        ], 200);
     }
}
