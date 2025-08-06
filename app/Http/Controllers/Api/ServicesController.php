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

    public function index()
    {
        return ServiceResource::collection($this->service->index());
    }

    public function show(int $serviceId)
    {
        return new ServiceResource($this->service->getById($serviceId));
    }

    public function getSchedules(int $serviceId)
    {
        return SchedulesResource::collection($this->service->getById($serviceId)->schedules);
    }

    public function toSchedule(Request $request, int $serviceId, int $scheduleId) {
        
    }
}
