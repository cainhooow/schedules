<?php

namespace App\Interfaces;

interface CommitmentRepositoryInterface
{
     public function index();

     public function getById(int $id);

     public function getAllByServiceId(int $serviceId);

     public function getAllByCustomerId(int $customerId);

     public function getAllByProviderId(int $providerId);

     public function store(array $data);

     public function update(int $id, array $data);

     public function delete(int $id);
}
