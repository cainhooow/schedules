<?php

namespace App\Helpers;

use App\Constants\JWTSessionConstant;
use Illuminate\Support\Facades\Cookie;

class CookieHelper
{

    public static function create(string $token, string $refreshToken, int $tokenTime, int $refreshTokenTime)
    {
        $tokens[] = [];

        $tokens['token'] = cookie(
            JWTSessionConstant::SESSION_NAME,
            $token,
            $tokenTime,
            "/",
            null,
            false,
            true,
            false,
            "Lax"
        );

        $tokens['refreshToken'] = cookie(
            JWTSessionConstant::SESSION_REFRESH_NAME,
            $refreshToken,
            $refreshTokenTime,
            "/",
            null,
            false,
            true,
            false,
            "Lax"
        );

        return $tokens;
    }

    public static function forget()
    {
        Cookie::forget(JWTSessionConstant::SESSION_NAME);
        Cookie::forget(JWTSessionConstant::SESSION_REFRESH_NAME);
    }
}
