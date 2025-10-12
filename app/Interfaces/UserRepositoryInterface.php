<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
     public function index();
     public function store(array $data);
     public function getById(int $id);
}
