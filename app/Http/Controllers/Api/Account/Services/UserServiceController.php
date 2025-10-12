<?php

namespace App\Http\Controllers\Api\Account\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Services\FlagServices;
use App\Services\ServiceServices;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserServiceController extends Controller
{
     public function __construct(
          protected $service = new ServiceServices(),
          protected $flagsService = new FlagServices()
     ) {
     }

     /**
      * @OA\Get(
      *   path="/api/v1/me/services",
      *   tags={"Serviços do usuário"},
      *   summary="Listar serviços do usuário",
      *   @OA\Response(
      *     response=200,
      *     description="OK",
      *     @OA\JsonContent(
      *       type="array",
      *       @OA\Items(ref="#/components/schemas/ServiceResponse")
      *     )
      *   )
      * )
      */
     public function index()
     {
          return ServiceResource::collection(Auth::user()->services);
     }
     /**
      * @OA\Get(
      *   path="/api/v1/me/services/{serviceId}",
      *   tags={"Serviços do usuário"},
      *   summary="Informações sobre o serviço",
      *   @OA\Parameter(
      *     name="serviceId",
      *     in="path",
      *     required=true,
      *     @OA\Schema(type="integer")
      *   ),
      *   @OA\Response(
      *     response=200,
      *     description="OK",
      *     @OA\JsonContent(ref="#/components/schemas/ServiceResponse")
      *   )
      * )
      */
     public function show(int $serviceId)
     {
          return new ServiceResource($this->service->getById($serviceId));
     }
     /**
      * @OA\Post(
      *   path="/api/v1/me/services",
      *   tags={"Serviços do usuário"},
      *   summary="Criando um serviço",
      *   @OA\RequestBody(
      *     required=true,
      *     @OA\JsonContent(ref="#/components/schemas/ServiceRequest")
      *   ),
      *   @OA\Response(
      *     response=200,
      *     description="OK",
      *     @OA\JsonContent(ref="#/components/schemas/ServiceResponse")
      *   )
      * )
      */
     public function store(ServiceRequest $request)
     {
          $data = $request->validated();
          $data['user_id'] = Auth::user()->id;
          $createdService = $this->service->store(Auth::user(), $data);

          return new ServiceResource($createdService);
     }
     /**
      * @OA\Put(
      *   path="/api/v1/me/services/{serviceId}",
      *   tags={"Serviços do usuário"},
      *   summary="Atualizando um serviço",
      *   @OA\Parameter(
      *     name="serviceId",
      *     in="path",
      *     required=true,
      *     @OA\Schema(type="integer")
      *   ),
      *   @OA\RequestBody(
      *     required=true,
      *     @OA\JsonContent(ref="#/components/schemas/ServiceRequest")
      *   ),
      *   @OA\Response(
      *     response=200,
      *     description="OK",
      *     @OA\JsonContent(
      *       type="string",
      *       @OA\Property(property="message", type="string")
      *     )
      *   )
      * )
      */
     public function update(ServiceRequest $request, int $serviceId)
     {
          $data = $request->validated();
          $service = $this->service->getById($serviceId);

          if (Auth::user()->id !== $service->user->id) {
               return response()->json([
                    'message' => 'Você não possui permissão para este recurso'
               ], Response::HTTP_UNAUTHORIZED);
          }

          $isUpdated = $this->service->update($serviceId, $data);
          if (!$isUpdated) {
               return response()->json(
                    [
                         'message' => 'Não foi possivel atualizar este serviço. Tente novamente mais tarde'
                    ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
               );
          }

          return response(['message' => 'Serviço atualizado com sucesso']);
     }
     /**
      * @OA\Delete(
      *   path="/api/v1/me/services/{serviceId}",
      *   tags={"Serviços do usuário"},
      *   summary="Deletando um serviço",
      *   @OA\Parameter(
      *     name="serviceId",
      *     in="path",
      *     required=true,
      *     @OA\Schema(type="integer")
      *   ),
      *   @OA\Response(
      *     response=200,
      *     description="OK",
      *     @OA\JsonContent(
      *       type="string",
      *       @OA\Property(property="message", type="string")
      *     )
      *   )
      * )
      */
     public function destroy(int $serviceId)
     {
     }
}
