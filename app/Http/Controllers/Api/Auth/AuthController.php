<?php

namespace App\Http\Controllers\Api\Auth;

use App\Constants\Flags;
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
    /**
     * @OA\Get(
     *     path="/api/v1/me",
     *     tags={"Autenticação"},
     *     summary="Usuário autenticado",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna o usuário autenticado atualmente(Cookie/Authorization)",
     *         @OA\JsonContent(ref="#/components/schemas/UserResponse"),
     *     ),
     *     @OA\Response(response=403, description="O User-Access Token não esta presente no request header ou cookie"),
     *     @OA\Response(response=401, description="O usuário não esta com uma conta logada")
     * )
     */
    public function user()
    {
        $user = $this->service->getById(Auth::user()->id);
        return new UserResource($user);
    }
    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="Autenticar um usuário",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login de usuario feito com succeso",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erros de validação",
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        try {
            if (!$accessToken = JWTAuth::attempt($data)) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], Response::HTTP_UNAUTHORIZED);
            }

            $user = $this->service->getByEmail($data['email']);
            if (!$this->flagsService->userHas($user, Flags::Can_Authenticate)) {
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

            return $user->toResource(UserResource::class)
                ->additional(['token' => $accessToken])
                ->response()
                ->withCookie($cookies['token'])
                ->withCookie($cookies['refreshToken']);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Invalid token'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     summary="Registrar novo usuário",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erros de validação"
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = $this->service->store($data);
        $token = JWTAuth::fromUser($user);

        return $user->toResource(UserResource::class)
            ->additional(['token' => $token]);
    }
}
