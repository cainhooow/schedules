<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        Log::info(json_encode($this));
        return [
            'name' => $this->username,
            'email' => $this->email,
            'profile' => $this->profile ? new ProfileResource($this->profile) : null,
            'flags' => $this->flags ? $this->flags->map(fn($flag) => $flag->name) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
