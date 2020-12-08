<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Entities\Language;

class LanguageController extends Controller
{
    /**
     * Show all language data.
     *
     * @return List of language data
     */
    public function index()
    {
        $language = Language::orderBy('created_at', 'desc')->paginate(5);
        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => $language
        ], 200);
    }

    /**
     * Show a single language data.
     *
     * @return Single language data
     */
    public function show($id)
    {
        try {
            //code...
            $language = Language::find($id);
            return response()->json([
                'code' => 200,
                'success' => true,
                'data' => $language
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
     * Adding a new language
     *
     * @request name, last_updated_by, email, password, role
     *
     * @return message success
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:languages',
            'last_updated_by' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'code' => 422,
                'message' => $validator->errors()
            ], 422);
        }
        if(!$new_language = Language::create([
            'name' => $request->input('name'),
            'last_updated_by' => $request->input('last_updated_by')
        ])) return response()->json(['code'=>500, 'message'=>$new_language], 500);
        return response()->json([
            'code' => 201,
            'success'=> true,
            'message'=> 'A new language added successfully',
            'data' => $new_language
            ], 201);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'unique:languages|max:255',
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
            $language = Language::find($id);
            $language->update([
                'name' => $request->input('name') ? $request->input('name'):$language->name,
                'last_updated_by' => $request->input('last_updated_by') ? $request->input('last_updated_by'):$language->last_updated_by
            ]);
            return response()->json([
                'success'=> true,
                'message'=> 'update success',
                'data' => $language
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
     * Delete language by language id
     *
     * @require language id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
        if(!Language::destroy($id)) return response()->json(['success' => false, 'message' => 'language not found'], 404);
        return response()->json([
            'code' => 200,
            'success'=> true,
            'message'=> 'Delete Success'
        ], 200);
     }
}
