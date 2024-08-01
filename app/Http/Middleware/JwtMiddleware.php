<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = JWTAuth::parseToken();
            $user = $token->authenticate();
        } catch (Exception $e) {
            return response()->json([
                'status' => [
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthorized',
                ],
                'data' => [],
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
