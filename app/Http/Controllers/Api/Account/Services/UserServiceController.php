<?php

namespace App\Http\Controllers\Api\Account\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Services\ServiceServices;
use Illuminate\Support\Facades\Auth;

class UserServiceController extends Controller
{
    public function __construct(protected $service = new ServiceServices()) {}

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
    public function update(ServiceRequest $request, int $serviceId) {}

    public function destroy(int $serviceId) {}
}
