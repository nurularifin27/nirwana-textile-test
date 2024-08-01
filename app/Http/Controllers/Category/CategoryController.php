<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request){
        try {
            $categories = Category::all();

            return $this->sendSuccessResponse($categories,"Successfully get list category!");
        } catch (\Exception $e) {
            return $this->sendErrorResponse();
        }
    }

    public function store(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:categories',
                'description' => 'nullable',
            ]);

            if ($validator->fails()) {
                return $this->sendBadRequestResponse($validator->errors());
            }

            $category = Category::create($request->all());

            return $this->sendSuccessResponse($category,"Successfully create category!");
        } catch (\Exception $e) {
            return $this->sendErrorResponse();
        }
    }

    public function show(Category $category){
        try {
            return $this->sendSuccessResponse($category,"Successfully get category!");
        } catch (\Exception $e) {
            return $this->sendErrorResponse();
        }
    }

    public function update(Request $request, Category $category){
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:categories,name,'.$category->id,
                'description' => 'nullable',
            ]);

            if ($validator->fails()) {
                return $this->sendBadRequestResponse($validator->errors());
            }

            $category->update($request->all());

            return $this->sendSuccessResponse($category,"Successfully update category!");
        } catch (\Exception $e) {
            return $this->sendErrorResponse();
        }
    }

    public function destroy(Category $category){
        try {
            $category->delete();

            return $this->sendSuccessResponse([],"Successfully delete category!");
        } catch (\Exception $e) {
            return $this->sendErrorResponse();
        }
    }
}
