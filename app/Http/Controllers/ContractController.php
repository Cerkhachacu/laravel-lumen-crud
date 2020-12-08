<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Entities\Contract;

class ContractController extends Controller
{
    /**
     * Show all contract data.
     *
     * @return List of contract data
     */
    public function index()
    {
        $contract = Contract::orderBy('created_at', 'desc')->paginate(5);
        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => $contract
        ], 200);
    }

    /**
     * Show a single contract data.
     *
     * @return Single contract data
     */
    public function show($id)
    {
        try {
            //code...
            $contract = Contract::find($id);
            return response()->json([
                'code' => 200,
                'success' => true,
                'data' => $contract
            ],200);
        } catch (ModelNotFoundException $ex) {
            //throw $th;
            return response()->json([
                'code'=> 404,
                'success'=> false,
                'message'=> 'Contract is not exist'
            ]);
        }
    }

    /**
     * Adding a new contract
     *
     * @request name, last_updated_by, email, password, role
     *
     * @return message success
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:contracts',
            'last_updated_by' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'code' => 422,
                'message' => $validator->errors()
            ], 422);
        }
        if(!$new_contract = Contract::create([
            'name' => $request->input('name'),
            'last_updated_by' => $request->input('last_updated_by')
        ])) return response()->json(['code'=>500, 'message'=>$new_contract], 500);
        return response()->json([
            'code' => 201,
            'success'=> true,
            'message'=> 'A new contract added successfully',
            'data' => $new_contract
            ], 201);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'max:255|unique:contracts',
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
            $contract = Contract::find($id);
            $contract->update([
                'name' => $request->input('name') ? $request->input('name'):$contract->name,
                'last_updated_by' => $request->input('last_updated_by') ? $request->input('last_updated_by'):$contract->last_updated_by
            ]);
            return response()->json([
                'success'=> true,
                'message'=> 'update success',
                'data' => $contract
            ], 201);
        } catch (ModelNotFoundException $ex) {
            //throw $th;
            return response()->json([
                'code'=> 404,
                'success'=> false,
                'message'=> 'Contract is not exist'
            ]);
        }
    }

    /**
     *
     * Delete Contract by Contract id
     *
     * @require Contract id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
        if(!Contract::destroy($id)) return response()->json(['success' => false, 'message' => 'Contract not found'], 404);
        return response()->json([
            'code' => 200,
            'success'=> true,
            'message'=> 'Delete Success'
        ], 200);
     }
}
