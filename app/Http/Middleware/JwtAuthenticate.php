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
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie(JwtSessions::SESSION_NAME);

        if (!$token) {
            $authHeader = $request->header('Authorization');

            if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                $token = $matches[1];
            }
        }

        if (!$token) {
            return response()->json(['error' => 'token_absent'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user) {
                return response()->json(['error' => 'user_not_found'], Response::HTTP_UNAUTHORIZED);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'token_expired'], Response::HTTP_UNAUTHORIZED);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'token_invalid'], Response::HTTP_UNAUTHORIZED);
        } catch (JWTException $e) {
            Log::error("JWtAuthenticate Middleware Error: {$e->getMessage()}");
            return response()->json(['error' => 'token_error', 'message' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
