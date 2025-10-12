<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
     private $model;

     public function __construct()
     {
          $this->model = new User;
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

     public function getByEmail(string $email)
     {
          return $this->model->with(['flags', 'profile'])->where('email', $email)->first();
     }

     public function getByUsername(string $username)
     {
          return $this->model->with(['flags', 'profile'])->where('username', $username)->first();
     }
}
