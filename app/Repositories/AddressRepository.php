<?php

namespace App\Repositories;

use App\Interfaces\AddressRepositoryInterface;
use App\Models\Address;

class AddressRepository implements AddressRepositoryInterface
{
     private $model;
     public function __construct()
     {
          $this->model = new Address();
     }

     public function index()
     {
          return $this->model->all();
     }

     public function getById(int $id)
     {
          return $this->model->with('user')->find($id);
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

     public function hasDefault(int $userId)
     {
          return $this->model->with('user')
               ->where(['user_id' => $userId, 'default' => true])->first();
     }
}
