<?php

namespace App\Http\Controllers\Api\Auth\Providers;

use App\Http\Controllers\Controller;
use App\Services\UserServices;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class GoogleOAuthController extends Controller
{
    public function __construct(protected $service = new UserServices) {}

    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {
        try {
            $providerUser = Socialite::driver('google')->stateless()->user();

            $user = $this->service->getByEmail($providerUser->email);

            if (! $user) {
                $password = Hash::make(Str::random(12));

                $baseUsername = $this->generateUsernameFromName($providerUser->name);
                $username = $this->generateUniqueUsername($baseUsername);

                $user = $this->service->store([
                    'name' => $providerUser->name,
                    'username' => $username,
                    'email' => $providerUser->email,
                    'password' => $password,
                ]);
            }

            $accessToken = JWTAuth::fromUser($user);
            $refreshToken = JWTAuth::claims(['refresh' => true])->fromUser($user);

            $cookies = \App\Helpers\CookieHelper::create(
                $accessToken,
                $refreshToken,
                15,
                60 * 24 * 7
            );

            return $user->toResource(\App\Http\Resources\UserResource::class)
                ->additional(['token' => $accessToken])
                ->response()
                ->withCookie($cookies['token'])
                ->withCookie($cookies['refreshToken']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    protected function generateUsernameFromName(string $name): string
    {
        $username = Str::of($name)->ascii()->lower()->__toString();
        $username = preg_replace('/\s+/', '_', $username);
        $username = preg_replace('/[^a-z0-9._]/', '', $username);
        if (! preg_match('/^[a-z]/', $username)) {
            $username = 'u'.$username;
        }

        return $username;
    }

    protected function generateUniqueUsername(string $base): string
    {
        $username = $base;
        $counter = 1;

        while ($this->service->getByUsername($username)) {
            $username = $base.$counter;
            $counter++;
        }

        return $username;
    }
}
