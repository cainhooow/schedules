<?php

namespace App\Http\Middleware;

use App\Constants\JwtSessions;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!$token = $request->cookie(JwtSessions::SESSION_NAME)) {
            return response()->json(['error' => 'token_absent'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = JWTAuth::setToken($token)->authenticate();
            if (!$user) {
                return response()->json(['error' => 'user_not_found'], Response::HTTP_BAD_REQUEST);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'token_expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'token_invalid'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'token_absent', 'message' => $e->getMessage()], 401);
        }

        return $next($request);
    }
}
