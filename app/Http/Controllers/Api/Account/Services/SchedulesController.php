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

    public function index()
    {
        $service = request()->attributes->get('authorized_serviceId');
        return SchedulesResource::collection($service->schedules);
    }

    public function show(int $serviceId, int $scheduleId)
    {
        if (!$schedule = $this->service->getById($scheduleId)) {
            return response()->json(['Não foi possivel encontrar este horario'], Response::HTTP_NOT_FOUND);
        }

        $this->authorize('view', $schedule);
        return new SchedulesResource($schedule);
    }

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
