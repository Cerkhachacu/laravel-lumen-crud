<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Entities\Category;

class CategoryController extends Controller
{
    /**
     * Show all category data.
     *
     * @return List of category data
     */
    public function index()
    {
        $category = Category::orderBy('created_at', 'desc')->paginate(5);
        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => $category
        ], 200);
    }

    /**
     * Show a single category data.
     *
     * @return Single category data
     */
    public function show($id)
    {
        try {
            //code...
            $category = Category::find($id);
            return response()->json([
                'code' => 200,
                'success' => true,
                'data' => $category
            ],200);

        } catch (ModelNotFoundException $ex) {
            //throw $th;
            return response()->json([
                'code'=> 404,
                'success'=> false,
                'message'=> 'Category is not exist'
            ]);
        }
    }

    /**
     * Adding a new category
     *
     * @request name, last_updated_by, email, password, role
     *
     * @return message success
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories|max:255',
            'last_updated_by' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success'=> false,
                'code' => 422,
                'message' => $validator->errors()
            ], 422);
        }
        $new_category = Category::create([
            'name' => $request->input('name'),
            'last_updated_by' => $request->input('last_updated_by')
        ]);
        return response()->json([
            'code' => 201,
            'success'=> true,
            'message'=> 'A new category added successfully',
            'data' => $new_category
            ], 201);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'max:255|unique:categories',
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
            $category = Category::find($id);
            $category->update([
                'name' => $request->input('name') ? $request->input('name'):$category->name,
                'last_updated_by' => $request->input('last_updated_by') ? $request->input('last_updated_by'):$category->last_updated_by
            ]);
            return response()->json([
                'success'=> true,
                'message'=> 'update success',
                'data' => $category
            ], 201);
        } catch (ModelNotFoundException $ex) {
            //throw $th;
            return response()->json([
                'code'=> 404,
                'success'=> false,
                'message'=> 'Category is not exist'
            ]);
        }
    }

    /**
     *
     * Delete category by category id
     *
     * @require category id
     *
     * @return message delete success
     */

     public function destroy($id)
     {
        if(!Category::destroy($id)) return response()->json(['success' => false, 'message' => 'category not found'], 404);
        return response()->json([
            'code' => 200,
            'success'=> true,
            'message'=> 'Delete Success'
        ], 200);
     }
}
