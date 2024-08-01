<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{

    public function index(Request $request){
        try {
            $books = Book::with('categories')->get();

            return $this->sendSuccessResponse($books,"Successfully get list book!");
        } catch (\Exception $e) {
            return $this->sendErrorResponse();
        }
    }

    public function store(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:books',
                'title' => 'required',
                'description' => 'nullable',
                'categories' => 'required|array|min:1',
                'categories.*' => 'exists:categories,id'
            ]);

            if ($validator->fails()) {
                return $this->sendBadRequestResponse($validator->errors());
            }

            $book = Book::create($request->all());
            $book->categories()->attach($request->input('categories'));

            return $this->sendSuccessResponse($book,"Successfully create book!");
        } catch (\Exception $e) {
            return $this->sendErrorResponse();
        }
    }

    public function show(Book $book){
        try {
            return $this->sendSuccessResponse($book->load('categories'),"Successfully get book!");
        } catch (\Exception $e) {
            return $this->sendErrorResponse();
        }
    }

    public function update(Request $request, Book $book){
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:books,code,'.$book->id,
                'title' => 'required',
                'description' => 'nullable',
                'categories' => 'required|array|min:1',
                'categories.*' => 'exists:categories,id'
            ]);

            if ($validator->fails()) {
                return $this->sendBadRequestResponse($validator->errors());
            }

            $book->update($request->all());
            $book->categories()->sync($request->input('categories'));

            return $this->sendSuccessResponse($book,"Successfully update book!");
        } catch (\Exception $e) {
            return $this->sendErrorResponse();
        }
    }

    public function destroy(Book $book){
        try {
            $book->categories()->detach();
            $book->delete();

            return $this->sendSuccessResponse([],"Successfully delete book!");
        } catch (\Exception $e) {
            return $this->sendErrorResponse();
        }
    }
}