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

    public function getByEmail(string $email): ?User
    {
        return $this->repository->getByEmail($email);
    }

    public function getByUsername(string $username): ?User {
        return $this->repository->getByUsername($username);
    }

    public function store(array $data): User
    {
        $user = $this->repository->store($data);
        $this->flagService->assignToUser($user, [
            Flags::CanAuthenticate,
            Flags::AccountTaskLevel1
        ]);

        return $user;
    }
}
