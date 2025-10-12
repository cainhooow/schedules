<?php

namespace App\Policies;

use App\Models\AvailableSchedules;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;

class SchedulePolicy
{
     public function viewAny(User $user): bool
     {
          return true;
     }

     public function view(User $user, AvailableSchedules $schedule): bool
     {
          return $user->id === $schedule->service->user_id ? true : throw new HttpResponseException(
               response()->json([
                    'message' => 'Você não tem permissão para acessar este recurso ou o recurso é invalido'
               ], 403)
          );
     }

     public function create(User $user): bool
     {
          return true;
     }

     public function update(User $user, AvailableSchedules $schedule): bool
     {
          return $user->id === $schedule->service->user_id ? true : throw new HttpResponseException(
               response()->json([
                    'message' => 'Você não tem permissão para acessar este recurso ou o recurso é invalido'
               ], 403)
          );
     }

     public function delete(User $user, AvailableSchedules $schedule): bool
     {
          return $user->id === $schedule->service->user_id ? true : throw new HttpResponseException(
               response()->json([
                    'message' => 'Você não tem permissão para acessar este recurso ou o recurso é invalido'
               ], 403)
          );
          ;
     }
}
