<?php

namespace App\Interfaces;

interface AccountStatsRepositoryInterface {
     public function getPendingAccountTasks(int $user_id): array;
     public function getSchedulesCalendar(int $user_id);
}
