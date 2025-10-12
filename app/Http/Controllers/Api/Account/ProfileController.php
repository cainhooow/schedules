<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Services\ProfileServices;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
     public function __construct(protected $service = new ProfileServices())
     {
     }
     /**
      * @OA\Get(
      *   path="/api/v1/me/profile",
      *   tags={"Perfil de usuário"},
      *   summary="Informações do perfil de usuário",
      *   @OA\Response(
      *     response=200,
      *     description="OK",
      *     @OA\JsonContent(ref="#/components/schemas/ProfileResponse")
      *   )
      * )
      */
     public function index()
     {
          return new ProfileResource(Auth::user()->profile);
     }
     /**
      * @OA\Post(
      *   path="/api/v1/me/profile",
      *   tags={"Perfil de usuário"},
      *   summary="Criando um perfil",
      *   @OA\RequestBody(
      *     required=true,
      *     @OA\JsonContent(ref="#/components/schemas/ProfileRequest")
      *   ),
      *   @OA\Response(
      *     response=200,
      *     description="OK",
      *     @OA\JsonContent(ref="#/components/schemas/ProfileResponse")
      *   )
      * )
      */
     public function store(ProfileRequest $request)
     {
          $data = $request->validated();
          $data['user_id'] = Auth::user()->id;
          $profileCreated = $this->service->store($data);

          return new ProfileResource($profileCreated);
     }
     /**
      * @OA\Put(
      *   path="/api/v1/me/profile",
      *   tags={"Perfil de usuário"},
      *   summary="Atualizando um perfil",
      *   @OA\Response(
      *     response=200,
      *     description="OK",
      *     @OA\JsonContent(ref="#/components/schemas/ProfileResponse")
      *   )
      * )
      */
     public function update(ProfileRequest $request)
     {
          $data = $request->validated();
          $profileUpdated = $this->service->update(Auth::user()->profile->id, $data);

          if (!$profileUpdated) {
               return response()->json([
                    'error' => true,
                    'message' => 'Não foi possivel atualizar o perfil, tente novamente mais tarde'
               ], Response::HTTP_NOT_FOUND);
          }

          return new ProfileResource(
               $this->service->getById(Auth::user()->profile->id)
          );
     }

     public function destroy()
     {
     }
}
