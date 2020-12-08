<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use App\Entities\Opportunity;

class OpportunityController extends Controller
{
    /**
     * Show all opportunity data.
     *
     * @return List of opportunity data
     */
    public function index()
    {
        $opportunity = Opportunity::orderBy('created_at', 'desc')->paginate(5);
        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => $opportunity
        ], 200);
    }

    /**
     * Show a single opportunity data.
     *
     * @return Single opportunity data
     */
    public function show($id)
    {
        try {
            //code...
            if(!$opportunity = Opportunity::find($id)) return response()->json(['success'=> false, 'message'=> 'opportunity not found'], 404);
            return response()->json([
                'code' => 200,
                'success' => true,
                'data' => $opportunity
            ],200);
        } catch (ModelNotFoundException $ex) {
            //throw $th;
            return response()->json([
                'code'=> 404,
                'success'=> false,
                'message'=> 'Opportunity is not exist'
            ]);
        }
    }

    /**
     * Adding a new opportunity
     *
     * @request name, last_updated_by, email, password, role
     *
     * @return message success
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:opportunities',
            'last_updated_by' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'code' => 422,
                'message' => $validator->errors()
            ], 422);
        }
        if(!$new_opportunity = Opportunity::create([
            'name' => $request->input('name'),
            'last_updated_by' => $request->input('last_updated_by')
        ])) return response()->json(['code'=>500, 'message'=>$new_opportunity], 500);
        return response()->json([
            'code' => 201,
            'success'=> true,
            'message'=> 'A new opportunity added successfully',
            'data' => $new_opportunity
            ], 201);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'max:255',
            'last_updated_by' => 'max:255',
            'email' => 'unique:opportunities|max:255'
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
            $opportunity = Opportunity::find($id);
            $opportunity->update([
                'name' => $request->input('name') ? $request->input('name'):$opportunity->name,
                'last_updated_by' => $request->input('last_updated_by') ? $request->input('last_updated_by'):$opportunity->last_updated_by
            ]);
            return response()->json([
                'success'=> true,
                'message'=> 'update success',
                'data' => $opportunity
            ], 201);
        } catch (ModelNotFoundException $ex) {
            //throw $th;
            return response()->json([
                'code'=> 404,
                'success'=> false,
                'message'=> 'Opportunity is not exist'
            ]);
        }
    }

    /**
     *
     * Delete opportunity by opportunity id
     *
     * @require opportunity id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
        if(!Opportunity::destroy($id)) return response()->json(['success' => false, 'message' => 'opportunity not found'], 404);
        return response()->json([
            'code' => 200,
            'success'=> true,
            'message'=> 'Delete Success'
        ], 200);
     }
}
