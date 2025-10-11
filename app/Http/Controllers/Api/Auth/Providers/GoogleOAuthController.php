<?php

namespace App\Http\Controllers\Api\Auth\Providers;

use App\Constants\Flags;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\FlagServices;
use App\Services\ProfileServices;
use App\Services\UserServices;
use DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class GoogleOAuthController extends Controller
{
    public function __construct(
        protected $userService = new UserServices(),
        protected $profileService = new ProfileServices(),
        protected $flagsServices = new FlagServices()
    ) {
    }

    /**
     * @OA\Get(
     *   path="/api/v1/auth/providers/google",
     *   summary="Autorizar Google OAuth2",
     *   tags={"Autenticação", "OAuth2"},
     *
     *   @OA\Response(
     *     response=200,
     *     description="Abre a página de seleção de conta google",
     *   )
     * )
     */
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * @OA\Get(
     *   path="/api/v1/auth/providers/google/callback",
     *   summary="Autenticar Google OAuth2",
     *   tags={"Autenticação", "OAuth2"},
     *
     *   @OA\Response(
     *     response=200,
     *     description="Retorna o usuário criado e autenticado pelo provedor google",
     *
     *     @OA\JsonContent(ref="#/components/schemas/UserResponse")
     *   )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback()
    {
        DB::beginTransaction();
        try {
            $providerUser = Socialite::driver('google')->stateless()->user();
            $user = $this->userService->getByEmail($providerUser->email);

            if (!$user) {
                $baseUsername = $this->generateUsernameFromName($providerUser->name);
                $username = $this->generateUniqueUsername($baseUsername);

                $user = $this->userService->store([
                    'username' => $username,
                    'email' => $providerUser->email,
                    'password' => Str::random(12),
                ]);

                $this->profileService->store([
                    'name' => $providerUser->name,
                    'avatar' => $providerUser->avatar,
                    'user_id' => $user->id
                ]);

                $this->flagsServices->assignToUser($user, [Flags::GoogleAccountProvider]);
            }

            $accessToken = JWTAuth::fromUser($user);
            $refreshToken = JWTAuth::claims(['refresh' => true])->fromUser($user);

            $cookies = \App\Helpers\CookieHelper::create(
                $accessToken,
                $refreshToken,
                15,
                60 * 24 * 7
            );

            DB::commit();

            return $user->toResource(UserResource::class)
                ->additional(['token' => $accessToken])
                ->response()
                ->withCookie($cookies['token'])
                ->withCookie($cookies['refreshToken']);
        } catch (\Exception $e) {
            DB::rollBack();
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
        if (!preg_match('/^[a-z]/', $username)) {
            $username = "u{$username}";
        }

        return $username;
    }

    protected function generateUniqueUsername(string $base): string
    {
        $username = $base;
        $counter = 1;

        while ($this->userService->getByUsername($username)) {
            $username = "{$base}{$counter}";
            $counter++;
        }

        return $username;
    }
}
