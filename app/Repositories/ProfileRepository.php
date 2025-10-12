<?php

namespace App\Repositories;

use App\Interfaces\ProfileRepositoryInterface;
use App\Models\Profile;

class ProfileRepository implements ProfileRepositoryInterface
{
     private $model;

     public function __construct()
     {
          $this->model = new Profile();
     }

     public function index()
     {
     }
     public function getByid(int $id)
     {
          return $this->model->find($id);
     }

     public function store(array $data)
     {
          return $this->model->with(['users'])->create($data);
     }

     public function update(int $id, array $data)
     {
          return $this->model->find($id)->update($data);
     }

     public function delete(int $id)
     {
     }
}
