<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);
    
            if ($validator->fails()) {
                return $this->sendBadRequestResponse($validator->errors());
            }
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            return $this->sendSuccessResponse($user,"User registered successfully!");
        } catch (\Exception $e) {
            return $this->sendErrorResponse();
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string|min:8',
            ]);
    
            if ($validator->fails()) {
                return $this->sendBadRequestResponse($validator->errors());
            }

            $credentials = $request->only('email', 'password');
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            $now = Carbon::now();
            $response = [
                "type" => "Bearer",
                "token" => $token,
                "expired_at" => $now->addMinute(env('JWT_TTL'))
            ];

            return $this->sendSuccessResponse($response,"User login successfully!");
            
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }
}
