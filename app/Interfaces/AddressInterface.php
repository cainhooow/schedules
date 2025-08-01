<?php

namespace App\Interfaces;

interface AddressInterface
{
    public function index();
    public function getById(int $id);
    public function store(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
