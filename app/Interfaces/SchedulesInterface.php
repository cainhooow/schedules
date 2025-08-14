<?php

namespace App\Interfaces;

interface SchedulesInterface
{
    public function index();
    public function getById(int $id);
    public function store(array $data);
    public function update(int $id, array $data);
    public function setAvailable(int $id, bool $available);
    public function delete(int $id);
}
