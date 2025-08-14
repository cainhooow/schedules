<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommitmentResource extends JsonResource
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
            'comment' => $this->comment,
            'schedule' => new SchedulesResource($this->schedule),
            'service' => new ServiceResource($this->service),
            'customer' => [
                'id' => $this->customer->id,
                'username' => $this->customer->username,
                'name' => $this->customer->profile->name,
            ],
            'created_at' => $this->created_at,
            'updated_at'=> $this->updated_at,
        ];
    }
}
