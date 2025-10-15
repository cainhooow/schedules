<?php

namespace App\Services;

use App\Repositories\AccountStatsRepository;

class AccountStatsServices
{
     public function __construct(protected $repository = new AccountStatsRepository())
     {

     }

     public function getPendingAccountTasks(int $user_id): array
     {
          return $this->repository->getPendingAccountTasks($user_id);
     }

     public function getSchedulesCalendar(int $user_id): array {
          return $this->repository->getSchedulesCalendar($user_id);
     }

     public function getServicesDemands(int $user_id): array {
          return $this->repository->getServicesDemands($user_id);
     }
}
