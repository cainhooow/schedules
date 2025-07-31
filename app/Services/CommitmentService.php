<?php

namespace App\Services;

use App\Repositories\CommitmentRepository;

class CommitmentService
{
    public function __construct(
        protected CommitmentRepository $repository
    ) {}

    public function getAll()
    {
        return $this->repository->index();
    }
}
