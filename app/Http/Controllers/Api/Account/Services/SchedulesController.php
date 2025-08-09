<?php

namespace App\Http\Controllers\Api\Account\Services;

use App\Exceptions\InvalidScheduleException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SchedulesRequest;
use App\Http\Resources\SchedulesResource;
use App\Services\ScheduleServices;
use App\Services\ServiceServices;
use Symfony\Component\HttpFoundation\Response;

class SchedulesController extends Controller
{
    public function __construct(
        protected $service = new ScheduleServices(),
        protected $userServices = new ServiceServices()
    ) {}

    /**
     * @OA\Get(
     *   path="/api/v1/me/services/{serviceId}/schedules",
     *   tags={"Horarios de serviço disponiveis"},
     *   summary="Listar horarios do serviço",
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
     *       type="array",
     *       @OA\Items(ref="#/components/schemas/ScheduleResponse")
     *     )
     *   )
     * )
     */
    public function index()
    {
        $service = request()->attributes->get('authorized_serviceId');
        return SchedulesResource::collection($service->schedules);
    }
    /**
     * @OA\Get(
     *   path="/api/v1/me/services/{serviceId}/schedules/{scheduleId}",
     *   tags={"Horarios de serviço disponiveis"},
     *   summary="Informações sobre um horario",
     *   @OA\Parameter(
     *     name="serviceId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="scheduleId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/ScheduleResponse")
     *   )
     * )
     */
    public function show(int $serviceId, int $scheduleId)
    {
        if (!$schedule = $this->service->getById($scheduleId)) {
            return response()->json(['Não foi possivel encontrar este horario'], Response::HTTP_NOT_FOUND);
        }

        $this->authorize('view', $schedule);
        return new SchedulesResource($schedule);
    }
    /**
     * @OA\Post(
     *   path="/api/v1/me/services/{serviceId}/schedules/{scheduleId}",
     *   tags={"Horarios de serviço disponiveis"},
     *   summary="Criando um horario",
     *   @OA\Parameter(
     *     name="serviceId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="scheduleId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/ScheduleRequest")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/ScheduleResponse")
     *   )
     * )
     */
    public function store(SchedulesRequest $request, int $serviceId)
    {
        $data = $request->validated();
        $data['service_id'] = $serviceId;

        try {
            $createdSchedule = $this->service->store($data);
            return new SchedulesResource($createdSchedule);
        } catch (InvalidScheduleException $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }
    /**
     * @OA\Patch(
     *   path="/api/v1/me/services/{serviceId}/schedules/{scheduleId}",
     *   tags={"Horarios de serviço disponiveis"},
     *   summary="Atualizando um horario",
     *   @OA\Parameter(
     *     name="serviceId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="scheduleId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/ScheduleRequest")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/ScheduleResponse")
     *   )
     * )
     */
    public function update(SchedulesRequest $request, int $serviceId, int $scheduleId)
    {
        if (!$schedule = $this->service->getById($scheduleId)) {
            return response()->json(['Não foi possivel encontrar este horario'], Response::HTTP_NOT_FOUND);
        }

        $this->authorize('update', $schedule);

        $data = $request->validated();
        $data['service_id'] = $serviceId;

        try {
            $this->service->update($scheduleId, $data);
            return response()->json(
                ['message' => 'Horario atualizado com sucesso']
            );
        } catch (InvalidScheduleException $e) {
            return response()->json(
                ['error' => true, 'message' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    /**
     * @OA\Delete(
     *   path="/api/v1/me/services/{serviceId}/schedules/{scheduleId}",
     *   tags={"Horarios de serviço disponiveis"},
     *   summary="Deletando um horario",
     *   @OA\Parameter(
     *     name="serviceId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="scheduleId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="message", type="string")
     *     )
     *   )
     * )
     */
    public function destroy(int $serviceId, int $scheduleId)
    {
        if (!$schedule = $this->service->getById($scheduleId)) {
            return response()->json(['Não foi possivel encontrar este horario'], Response::HTTP_NOT_FOUND);
        }

        $this->authorize('delete', $schedule);

        try {
            $this->service->delete($schedule->id);
            return response()->json(
                ['message' => 'Horario deletado com sucesso']
            );
        } catch (InvalidScheduleException $e) {
            return response()->json(
                ['error' => true, 'message' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
