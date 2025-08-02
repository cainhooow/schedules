<?php

namespace App\Repositories;

use App\Interfaces\ServiceInterface;
use App\Models\Service;

class ServiceRepository implements ServiceInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new Service();
    }

    public function index()
    {
        return $this->model->all();
    }
    public function getById(int $id)
    {
        return $this->model->find($id);
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
