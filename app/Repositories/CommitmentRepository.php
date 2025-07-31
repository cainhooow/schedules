<?php

namespace App\Repositories;

use App\Interfaces\CommitmentInterface;
use App\Models\Commitment;

class CommitmentRepository implements CommitmentInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new Commitment();
    }

    public function index()
    {
        return $this->model->all();
    }
}
