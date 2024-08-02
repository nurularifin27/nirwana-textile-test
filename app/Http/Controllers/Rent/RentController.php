<?php

namespace App\Http\Controllers\Rent;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RentController extends Controller
{

    public function index(Request $request){
        try {
            $rents = Rent::with('user','book')->get();

            return $this->sendSuccessResponse($rents,"Successfully get list rent!");
        } catch (\Exception $e) {
            return $this->sendErrorResponse();
        }
    }

    public function store(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:rents',
                'user_id' => 'required|exists:users,id',
                'book_id' => 'required|exists:books,id',
                'rent_date' => 'required|date',
                'return_date' => 'required|date'
            ]);

            if ($validator->fails()) {
                return $this->sendBadRequestResponse($validator->errors());
            }

            $rent = Rent::create($request->all());

            return $this->sendSuccessResponse($rent,"Successfully create rent!");
        } catch (\Exception $e) {
            return $this->sendErrorResponse();
        }
    }

    public function show(Rent $rent){
        try {
            return $this->sendSuccessResponse($rent->load('user','book'),"Successfully get rent!");
        } catch (\Exception $e) {
            return $this->sendErrorResponse();
        }
    }

    public function update(Request $request, Rent $rent){
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'book_id' => 'required',
                'rent_date' => 'required|date',
                'return_date' => 'required|date',
                'actual_return_date' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                return $this->sendBadRequestResponse($validator->errors());
            }

            $rent->update($request->all());

            return $this->sendSuccessResponse($rent,"Successfully update rent!");
        } catch (\Exception $e) {
            return $this->sendErrorResponse();
        }
    }

    public function destroy(Rent $rent){
        try {
            $rent->delete();

            return $this->sendSuccessResponse([],"Successfully delete rent!");
        } catch (\Exception $e) {
            return $this->sendErrorResponse();
        }
    }
}