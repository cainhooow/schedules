<?php

namespace App\Http\Controllers\Api;

use App\Constants\FlagConstant;
use App\Helpers\CookieHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\FlagServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(protected $service = new UserServices(), protected $flagsService = new FlagServices()) {}

    public function user()
    {
        $user = $this->service->getById(Auth::user()->id);
        return new UserResource($user);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        try {
            if (!$accessToken = JWTAuth::attempt($data)) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], Response::HTTP_UNAUTHORIZED);
            }

            $user = User::where('email', $data['email'])->first();
            if (!$this->flagsService->userHas($user, FlagConstant::CAN_AUTHENTICATE)) {
                return response()->json([
                    'message' => 'Você não possui permissão para utilizar esse recurso'
                ], Response::HTTP_UNAUTHORIZED);
            }

            $refreshToken = JWTAuth::claims(['refresh' => true])->fromUser($user);

            $cookies = CookieHelper::create(
                $accessToken,
                $refreshToken,
                15,
                60 * 24 * 7
            );

            return response()->json([
                'user' => $user,
                'token' => $accessToken
            ])
                ->withCookie($cookies['token'])
                ->withCookie($cookies['refreshToken']);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Invalid token'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = $this->service->store($data);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            compact('user', 'token')
        ], Response::HTTP_CREATED);
    }
}
