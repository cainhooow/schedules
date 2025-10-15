<?php

namespace App\Repositories;

use App\Constants\CommitmentStatus;
use App\Interfaces\AccountStatsRepositoryInterface;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountStatsRepository implements AccountStatsRepositoryInterface
{
     private $userModel;
     private $servicesModel;

     public function __construct()
     {
          $this->userModel = new User();
          $this->servicesModel = new Service();
     }

     public function getPendingAccountTasks(int $user_id): array
     {
          $user = $this->userModel->find($user_id);

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
          $services = $this->servicesModel->with(['schedules', 'commitments.customer'])
               ->where('user_id', $user_id)
               ->get();

          foreach ($services as $service) {
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
                              'customer' => $commitment->customer->profile->name,
                              'service_id' => $service->id,
                              'schedule_id' => $schedule->id,
                              'comment' => $commitment->comment,
                              'status' => $commitment->status,
                         ],
                         'event_date' => $start->toIso8601String(),
                         'event_end' => $end->toIso8601String(),
                    ];
               }
          }

          usort(
               $calendar,
               fn($a, $b) =>
               strcmp($a['event_date'], $b['event_date'])
          );

          return $calendar;
     }

     /**
      * @param int $user_id
      */
     public function getServicesDemands(int $user_id): array
     {
          $services = $this->servicesModel
               ->where('user_id', $user_id)->with(['schedules'])->get();

          $data = [];

          foreach ($services as $service) {
               $data['commitments'] = [
                    'total' => $service->commitments->count(),
                    'scheduled' => $service->commitments->where('status', CommitmentStatus::SCHEDULED->value)->count(),
                    'canceled' => $service->commitments->where('status', CommitmentStatus::CANCELED->value)->count(),
                    'closed' => $service->commitments->where('status', CommitmentStatus::CLOSED->value)->count(),
                    'per_month' => DB::table('commitments')
                         ->where('service_id', $service->id)
                         ->selectRaw('DATE_TRUNC(\'month\', commitments.created_at) as month, COUNT(*) as total')
                         ->groupBy('month')
                         ->orderBy('month')
                         ->get()
                         ->map(
                              fn($row) =>
                              [
                                   'month' => Carbon::parse($row->month)->format('Y-m'),
                                   'total' => $row->total
                              ]
                         )
               ];

               $data['total_available_schedules'] = $service->schedules->count();
          }

          return $data;
     }
}
