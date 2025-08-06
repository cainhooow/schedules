<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema (
 *     schema="ScheduleResponse",
 *     type="object",
 *     title="Response-Body Disponibilidade",
 *     @OA\Property(property="id", type="integer", example="123"),
 *     @OA\Property(property="day_of_week", type="string", example="monday"),
 *     @OA\Property(property="start_time", type="string", example="08:30:00"),
 *     @OA\Property(property="end_time", type="string", example="12:30:00"),
 *     @OA\Property(property="available", type="boolean", example="false"),
 *     @OA\Property(property="created_at", type="date"),
 *     @OA\Property(property="updated_at", type="date")
 * )
 */
class SchedulesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'day_of_week' => $this->day_of_week,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'available' => $this->available,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
