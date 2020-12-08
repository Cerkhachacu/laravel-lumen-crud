<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use App\Entities\Remote;

class RemoteController extends Controller
{
    /**
     * Show all remote data.
     *
     * @return List of remote data
     */
    public function index()
    {
        $remote = Remote::orderBy('created_at', 'desc')->paginate(5);
        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => $remote
        ], 200);
    }

    /**
     * Show a single remote data.
     *
     * @return Single remote data
     */
    public function show($id)
    {
        try {
            //code...
            $remote = Remote::find($id);
            return response()->json([
                'code' => 200,
                'success' => true,
                'data' => $remote
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
     * Adding a new remote
     *
     * @request name, last_updated_by
     *
     * @return message success
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:remotes|max:255',
            'last_updated_by' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'code' => 422,
                'message' => $validator->errors()
            ], 422);
        }
        $new_remote = Remote::create([
            'name' => $request->input('name'),
            'last_updated_by' => $request->input('last_updated_by')
        ]);
        return response()->json([
            'code' => 201,
            'success'=> true,
            'message'=> 'A new remote added successfully',
            'data' => $new_remote
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
            'name' => 'unique:remotes|max:255',
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
            $remote = Remote::find($id);
            $remote->update([
                'name' => $request->input('name') ? $request->input('name'):$remote->name,
                'last_updated_by' => $request->input('last_updated_by') ? $request->input('last_updated_by'):$remote->last_updated_by
            ]);
            return response()->json([
                'success'=> true,
                'message'=> 'update success',
                'data' => $remote
            ], 201);
        } catch (ModelNotFoundException $ex) {
            //throw $th;
            return response()->json([
                'code'=> 404,
                'success'=> false,
                'message'=> 'Remote is not exist'
            ]);
        }
    }

    /**
     *
     * Delete remote by remote id
     *
     * @require remote id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
        if(!Remote::destroy($id)) return response()->json(['success' => false, 'message' => 'remote not found'], 404);
        return response()->json([
            'code' => 200,
            'success'=> true,
            'message'=> 'Delete Success'
        ], 200);
     }
}
