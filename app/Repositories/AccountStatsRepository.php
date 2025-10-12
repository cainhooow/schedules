<?php

namespace App\Repositories;

use App\Interfaces\AccountStatsRepositoryInterface;
use App\Models\User;

class AccountStatsRepository implements AccountStatsRepositoryInterface
{
     public function getPendingAccountTasks(int $user_id): array
     {
          $user = User::with("flags")->find($user_id);

          if (!$user) {
               return [];
          }

          // ['Canxxxx', 'CanYYY', 'AccountTaskLevel1']
          $userFlags = $user->flags->pluck('name');
          $allTasks = collect([
               'AccountTaskLevel1',
               'AccountTaskLevel2',
               'AccountTaskLevel3',
          ]);

          $pendinTasks = $allTasks->diff($userFlags);
          return $pendinTasks->values()->toArray();
     }
}
