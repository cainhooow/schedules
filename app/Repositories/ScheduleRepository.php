<?php

namespace App\Repositories;

use App\Exceptions\InvalidScheduleException;
use App\Interfaces\SchedulesInterface;
use App\Models\AvailableSchedules;

class ScheduleRepository implements SchedulesInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new AvailableSchedules();
    }
    public function index()
    {
        return $this->model->all();
    }

    public function getById(int $id)
    {
        return $this->model->find($id);
    }

    public function getByWeek(int $serviceId, string $week)
    {
        return $this->model->where([
            "service_id" => $serviceId,
            "day_of_week" => $week
        ])->all();
    }

    public function store(array $data)
    {
        if ($this->hasTimeConflict($data)) {
            throw new InvalidScheduleException("O horario de day_of_week:{$data['day_of_week']}, start:{$data['start_time']} end:{$data['end_time']} conflita com um horario já existente");
        }

        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        if ($this->hasTimeConflict($data, $id)) {
            throw new InvalidScheduleException("O horario de day_of_week:{$data['day_of_week']}, start:{$data['start_time']} end:{$data['end_time']} conflita com um horario já existente");
        }

        return $this->model->find($id)->update($data);
    }

    public function delete(int $id)
    {
        return $this->model->find($id)->delete();
    }

    private function hasTimeConflict(array $data, ?int $ignoreId = null)
    {
        $query = $this->model
            ->where('service_id', $data['service_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('start_time', '<', $data['end_time'])
            ->where('end_time', '>', $data['start_time']);

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
}
