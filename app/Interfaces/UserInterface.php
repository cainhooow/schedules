<?php

namespace App\Interfaces;

interface UserInterface
{
    public function index();
    public function store(array $data);
    public function getById(int $id);
}
