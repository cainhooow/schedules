<?php

namespace App\Services;

use App\Repositories\ScheduleRepository;

class ScheduleServices
{
     public function __construct(protected $repository = new ScheduleRepository())
     {
     }

     public function index()
     {
          return $this->repository->index();
     }

     public function getById(int $scheduleId)
     {
          return $this->repository->getById($scheduleId);
     }

     public function store(array $data)
     {
          return $this->repository->store($data);
     }

     public function update(int $scheduleId, array $data)
     {
          return $this->repository->update($scheduleId, $data);
     }

     public function setAvailabe(int $scheduleId, bool $isAvailable)
     {
          return $this->repository->setAvailable($scheduleId, $isAvailable);
     }

     public function delete(int $scheduleId)
     {
          return $this->repository->delete($scheduleId);
     }
}
