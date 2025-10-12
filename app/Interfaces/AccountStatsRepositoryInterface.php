<?php

namespace App\Interfaces;

interface AccountStatsRepositoryInterface {
     public function getPendingAccountTasks(int $user_id): array;
}
