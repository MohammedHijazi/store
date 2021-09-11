<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{

    public function index()
    {
        return Response::json(Category::all(),200);
    }


    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
           'name'=>'required',
            'parent_id'=>'nullable|int|exist:categories,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }

        $category=Category::create($request->all());
        $category->refresh();

        return Response::json($category,201);
    }


    public function show($id)
    {

        return Response::json(Category::findOrFail($id),200);
    }


    public function update(Request $request, $id)
    {
        $validator=Validator::make($request->all(),[
            'name'=>'sometimes|required',
            'parent_id'=>'nullable|int|exist:categories,id',
            'description'=>'max:500'
        ]);
        $category=Category::findOrFail($id);
        $category->update($request->all());
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }
        return Response::json([
            'message'=>'Category Updated',
            'category'=>$category
        ]);
    }


    public function destroy($id)
    {
        $category=Category::findOrFail($id);
        $category->delete();
        return Response::json([
            'message'=>'category deleted'
        ],200);
    }
}
