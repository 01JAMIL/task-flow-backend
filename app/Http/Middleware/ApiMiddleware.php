<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'status' => 'ERROR',
                'code' => 401,
                'message' => 'You are not authorized to access this route. Access token required',
            ], 401);
        }

        try {
            $user = Auth::guard('api')->user();

            if (!$user) {
                // Invalid token
                return response()->json([
                    'status' => 'ERROR',
                    'code' => 401,
                    'message' => 'Invalid access token',
                ], 401);
            }
            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'code' => 401,
                'message' => 'The token is invalid or has expired',
            ], 401);
        }
    }
}
