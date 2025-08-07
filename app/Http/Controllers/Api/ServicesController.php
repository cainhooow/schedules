<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SchedulesResource;
use App\Http\Resources\ServiceResource;
use App\Services\ServiceServices;
use Illuminate\Support\Facades\Request;

class ServicesController extends Controller
{
    //
    public function __construct(
        protected $service = new ServiceServices()
    ) {}
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
        return ServiceResource::collection($this->service->index());
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
        return new ServiceResource($this->service->getById($serviceId));
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
        return SchedulesResource::collection($this->service->getById($serviceId)->schedules);
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
    public function toSchedule(Request $request, int $serviceId, int $scheduleId) {}
}
