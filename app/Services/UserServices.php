<?php

namespace App\Services;

use App\Constants\Flags;
use App\Models\User;
use App\Repositories\UserRepository;

class UserServices
{
    private $flagService;
    public function __construct(protected $repository = new UserRepository())
    {
        $this->flagService = new FlagServices();
    }

    public function getById(int $id): ?User
    {
        return $this->repository->getById($id);
    }

    public function store(array $data): User
    {
        $user = $this->repository->store($data);
        $this->flagService->assignToUser($user, [
            Flags::Can_Authenticate,
            Flags::Account_Task_Level_1
        ]);

        return $user;
    }
}
