<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //

        public function index()
        {
            //
            $categories = Category::all();

            return response()->json([
                'status' => true,
                'success' => true,
                'data' => CategoryResource::collection($categories)
            ]);
        }
        public function categoryAllEvents()
        {
            //
            $categories = Category::with('events')->get();

            return response()->json([
                'status' => true,
                'success' => true,
                'data' => CategoryResource::collection($categories)
            ]);
        }

            public function show($id)
            {
                //
                $category = Category::find($id);

                if (!$category) {
                    return response()->json([
                        'status' => false,
                        'success' => false,
                        'message' => 'Category not found'
                    ], 404);
                }

                return response()->json([
                    'status' => true,
                    'success' => true,
                    'data' => new CategoryResource($category)
                ]);
            }
                public function showCategoryWithEvents($id)
                {
                    //
                    $category = Category::with('events')->find($id);

                    if (!$category) {
                        return response()->json([
                            'status' => false,
                            'success' => false,
                            'message' => 'Category not found'
                        ], 404);
                    }

                    return response()->json([
                        'status' => true,
                        'success' => true,
                        'data' => new CategoryResource($category)
                    ]);
                }
            public function create(Request $request)
            {
                //
                $validator = Validator::make($request->all(), [
                    'title' => 'required|string|max:255|unique:categories,title',
                ]);
                if($validator->fails()){
                    return response()->json([
                        'status' => false,
                        'success' => false,
                        'message' => $validator->errors()
                    ], 400);
                }
                $category = Category::create([
                    'title' => $request->title,
                ]);

                return response()->json([
                    'status' => true,
                    'success' => true,
                    'message' => 'Category created successfully',
                    'data' => new CategoryResource($category)
                ], 201);



                }
                public function update(Request $request, $id)
                {
                    //
                    $category = Category::find($id);


                    if (!$category) {
                        return response()->json([
                            'status' => false,
                            'success' => false,
                            'message' => 'Category not found'
                        ], 404);
                    }

                    $validator = Validator::make($request->all(), [
                        'title' => 'required|string|max:255|unique:categories,title,' . $id,
                    ]);

                    if ($validator->fails()) {
                        return response()->json([
                            'status' => false,
                            'success' => false,
                            'message' => $validator->errors()
                        ], 400);
                    }

                    $category->update([
                        'title' => $request->title,
                    ]);

                    return response()->json([
                        'status' => true,
                        'success' => true,
                        'message' => 'Category updated successfully',
                        'data' => new CategoryResource($category)
                    ], 200);
                }
                public function destroy($id)
                {
                    //
                    $category = Category::find($id);

                    if (!$category) {
                        return response()->json([
                            'status' => false,
                            'success' => false,
                            'message' => 'Category not found'
                        ], 404);
                    }

                    $category->delete();

                    return response()->json([
                        'status' => true,
                        'success' => true,
                        'message' => 'Category deleted successfully'
                    ], 200);
                }
            }

