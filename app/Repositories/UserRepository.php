<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;

class UserRepository implements UserInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function index()
    {
        return $this->model->with(['flags', 'profile'])->all();
    }
    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function getById(int $id)
    {
        return $this->model->with(['flags', 'profile'])->find($id);
    }
}
