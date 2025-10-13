<?php

namespace App\Repositories;

use App\Interfaces\AccountStatsRepositoryInterface;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;

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

     public function getSchedulesCalendar(int $user_id)
     {
          $service = Service::with(['schedules', 'commitments.customer'])->where('user_id', $user_id)->get();

          foreach ($service->commitments as $commitment) {
               $schedule = $commitment->schedule;
               if (!$schedule)
                    continue;

               $start = Carbon::parse($schedule->start_time);
               $end = Carbon::parse($schedule->end_time);

               $calendar[] = [
                    'event' => [
                         'type' => 'commitment',
                         'title' => $service->name,
                         'customer' => $commitment->costumer?->name,
                         'service_id' => $service->id,
                         'schedule_id' => $schedule->id,
                         'comment' => $commitment->comment,
                         'status' => $commitment->status,
                    ],
                    'event_date' => $start->toIso8601String(),
                    'event_end' => $end->toIso8601String(),
               ];
          }

          usort(
               $calendar,
               fn($a, $b) =>
               strcmp($a['event_date'], $b['event_date'])
          );

          return $calendar;
     }
}
