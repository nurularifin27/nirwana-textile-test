<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendSuccessResponse($data , $message = null){
        $response = [
            'status' => [
                'code' => 200,
                'message' => $message,
            ],
            'data' => $data,
        ];

        return response()->json($response, 200);
    }

    public function sendBadRequestResponse($errors){
        $response = [
            'status' => [
                'code' => 400,
                'message' => 'Validation Failed',
            ],
            'data' => [],
            'errors' => $errors
        ];

        return response()->json($response, 400);
    }

    public function sendErrorResponse($code = 500, $message = 'Internal Server Error', $errors = null){
        $response = [
            'status' => [
                'code' => $code,
                'message' => $message,
            ],
            'data' => [],
            'errors' => $errors
        ];

        return response()->json($response, $code);
    }
}
