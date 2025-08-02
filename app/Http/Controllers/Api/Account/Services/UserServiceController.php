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
    ) {}

    public function index()
    {
        return ServiceResource::collection(Auth::user()->services);
    }
    public function show(int $serviceId)
    {
        return new ServiceResource($this->service->getById($serviceId));
    }
    public function store(ServiceRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $createdService = $this->service->store(Auth::user(), $data);

        return new ServiceResource($createdService);
    }
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

    public function destroy(int $serviceId) {}
}
