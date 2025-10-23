<?php

namespace App\Repositories;

use App\Constants\CommitmentStatus;
use App\Interfaces\CommitmentRepositoryInterface;
use App\Models\Commitment;
use Illuminate\Database\Eloquent\Builder;

class CommitmentRepository implements CommitmentRepositoryInterface
{
     private $model;

     public function __construct()
     {
          $this->model = new Commitment();
     }

     public function index()
     {
          return $this->model->all();
     }

     public function getById(int $id)
     {
          return $this->model->find($id);
     }

     public function getByStatus(CommitmentStatus $status)
     {
          return $this->model->where(['status' => $status->value]);
     }

     public function getByScheduleIdWhereDate(int $schedule_id, string $date): Builder|Commitment
     {
          return $this->model->where(['schedule_id' => $schedule_id])->whereDate('schedule_for', $date);
     }

     public function getAllByServiceId(int $serviceId)
     {
          return $this->model->where(["service_id" => $serviceId])->all();
     }

     public function getAllByCustomerId(int $customerId)
     {
          return $this->model->where(["customer_id" => $customerId])->all();
     }

     public function getAllByProviderId(int $providerId)
     {
          return $this->model->where(["service_provider_id" => $providerId])->all();
     }

     public function store(array $data)
     {
          return $this->model->create($data);
     }

     public function update(int $id, array $data)
     {
          return $this->model->find($id)->update($data);
     }

     public function delete(int $id)
     {
          return $this->model->find($id)->delete();
     }
}
