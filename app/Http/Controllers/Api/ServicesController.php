<?php

namespace App\Http\Controllers\Api;

use App\Constants\Flags;
use App\Exceptions\InvalidScheduleException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommitmentRequest;
use App\Http\Resources\CommitmentResource;
use App\Http\Resources\SchedulesResource;
use App\Http\Resources\ServiceResource;
use App\Services\CommitmentServices;
use App\Services\FlagServices;
use App\Services\ServiceServices;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class ServicesController extends Controller
{
     //
     public function __construct(
          protected ServiceServices $serviceServices = new ServiceServices(),
          protected CommitmentServices $commitmentServices = new CommitmentServices(),
          protected FlagServices $flagsServices = new FlagServices(),
     ) {
     }

     /**
      * @OA\Get(
      *   path="/api/v1/services",
      *   tags={"Serviços"},
      *   summary="Listagem de serviços",
      *   @OA\Response(
      *     response=200,
      *     description="Retorna a lista de serviços disponiveis na plataforma, criada por usuários.",
      *     @OA\JsonContent(
      *       type="array",
      *       @OA\Items(ref="#/components/schemas/ServiceResponse")
      *     )
      *   )
      * )
      */
     public function index()
     {
          return ServiceResource::collection($this->serviceServices->index());
     }

     /**
      * @OA\Get(
      *   path="/api/v1/services/{serviceId}",
      *   tags={"Serviços"},
      *   summary="Informações de um serviço",
      *   @OA\Parameter(
      *     name="serviceId",
      *     description="Id do serviço para buscar",
      *     in="path",
      *     required=true,
      *     @OA\Schema(type="string")
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
          return new ServiceResource($this->serviceServices->getById($serviceId));
     }

     /**
      * @OA\Get(
      *   path="/api/v1/services/{serviceId}/schedules",
      *   tags={"Serviços"},
      *   summary="Informações de um horario",
      *   @OA\Parameter(
      *     name="serviceId",
      *     in="path",
      *     required=true,
      *     @OA\Schema(type="string")
      *   ),
      *   @OA\Response(
      *     response=200,
      *     description="OK",
      *     @OA\JsonContent(ref="#/components/schemas/ScheduleResponse")
      *   )
      * )
      */
     public function getSchedules(int $serviceId)
     {
          return SchedulesResource::collection($this->serviceServices->getById($serviceId)->schedules);
     }

     /**
      * @OA\Post(
      *   path="/api/v1/services/{serviceId}/{scheduleId}",
      *   tags={"Serviços"},
      *   summary="Agendar um serviço(Em produção)",
      *   @OA\Parameter(
      *     name="serviceId",
      *     in="path",
      *     required=true,
      *     @OA\Schema(type="string")
      *   ),
      *   @OA\Parameter(
      *     name="scheduleId",
      *     in="path",
      *     required=true,
      *     @OA\Schema(type="string")
      *   ),
      *   @OA\Response(
      *     response=200,
      *     description="OK",
      *     @OA\JsonContent(
      *       type="string",
      *       required={"name"},
      *       @OA\Property(property="name", type="string")
      *     )
      *   )
      * )
      */
     public function toSchedule(CommitmentRequest $request, int $serviceId, int $scheduleId)
     {
          $data = $request->validated();

          $data['service_id'] = $serviceId;
          $data['schedule_id'] = $scheduleId;
          $data['customer_id'] = Auth::user()->id;

          try {
               $createdCommitment = $this->commitmentServices->store($data);
               return new CommitmentResource($createdCommitment);
          } catch (InvalidScheduleException $e) {
               return response()->json([
                    'error' => true,
                    'message' => $e->getMessage()
               ], Response::HTTP_BAD_REQUEST);
          }
     }

     /**
      * @OA\Patch(
      *   path="/api/v1/services/{serviceId}/{scheduleId}",
      *   tags={"Serviços"},
      *   summary="Atualizar um serviço",
      *    @OA\Parameter(
      *      name="serviceId",
      *      in="path",
      *      required=true,
      *      @OA\Schema(type="string")
      *    ),
      *    @OA\Parameter(
      *      name="scheduleId",
      *      in="path",
      *      required=true,
      *      @OA\Schema(type="string")
      *    ),
      *    @OA\Response(
      *      response=200,
      *      description="OK",
      *    ),
      *    @OA\Response(
      *      response=401,
      *      description="UNAUTHORIZED",
      *    ),
      *    @OA\Response(
      *      response=404,
      *      description="NOT FOUND",
      *    )
      * )
      * @param \App\Http\Requests\CommitmentRequest $request
      * @param int $serviceId
      * @param int $scheduleId
      * @return \Illuminate\Http\JsonResponse
      */
     public function updateCommitment(CommitmentRequest $request, int $serviceId, int $scheduleId)
     {
          $data = $request->validated();
          $user = Auth::user();

          if (!$service = $this->serviceServices->getById(intval($serviceId))) {
               return response()->json([
                    'error' => true,
                    'message' => 'Não foi possivel encontrar este serviço'
               ], Response::HTTP_NOT_FOUND);
          }

          if (!$schedule = $this->commitmentServices->getById(intval($scheduleId))) {
               return response()->json([
                    'error' => true,
                    'message' => 'Nao foi possivel econtrar este compromisso'
               ], Response::HTTP_NOT_FOUND);
          }

          if (intval($serviceId) !== $schedule->service->id) {
               return response()->json([
                    'error' => true,
                    'message' => 'Este compromisso pertence a outro serviço',
               ], Response::HTTP_UNAUTHORIZED);
          }

          $isCustomer = $this->flagsServices->userHas($user, Flags::Customer);
          if ($isCustomer && $schedule->customer->id !== $user->id || $service->user->id !== $user->id) {
               return response()->json([
                    'error' => true,
                    'message' => 'Este compromisso pertence a outro usuário'
               ], Response::HTTP_UNAUTHORIZED);
          }

          try {
               $this->commitmentServices->update($schedule->id, $data);

               return response()->json([
                    'error' => false,
                    'message' => 'O compromisso foi atualizado com sucesso'
               ], Response::HTTP_OK);
          } catch (Exception $e) {
               return response()->json([
                    'error' => true,
                    'message' => $e->getMessage()
               ]);
          }
     }
}
