<?php

namespace App\Services;

use App\Repositories\AccountStatsRepository;

class AccountStatisticsServices
{
     public function __construct(protected $repository = new AccountStatsRepository())
     {

     }

     public function getPendingAccountTasks(int $user_id): array
     {
          return $this->repository->getPendingAccountTasks($user_id);
     }
}
