<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\FlagRepository;

class FlagServices
{
    public function __construct(protected $repository = new FlagRepository()) {}

    /**
     * @param \App\Constants\Flags[] $flags
     */
    public function assignToUser(User $user, array $flags)
    {
        $flagNames = $this->selectIdsByName($flags);
        $user->flags()->attach($flagNames);
    }

    /**
     * @param \App\Constants\Flags[] $flags
     */
    public function removeFromUser(User $user, array $flags)
    {
        $flagNames = $this->selectIdsByName($flags);
        $user->flags()->detach($flagNames);
    }

    /**
     * @param \App\Constants\Flags $flag
     */
    public function userHas(User $user, string $flag)
    {
        return $user->flags()->where('name', $flag)->exists();
    }

    /**
     * @param \App\Constants\Flags[] $flags
     */
    public function selectIdsByName(array $flags)
    {
        return $this->repository->selectIdsByName($flags);
    }
}
