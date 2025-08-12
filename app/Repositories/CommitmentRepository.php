<?php

namespace App\Repositories;

use App\Interfaces\CommitmentInterface;
use App\Models\Commitment;

class CommitmentRepository implements CommitmentInterface
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
