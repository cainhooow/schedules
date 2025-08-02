<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserResponse",
 *     type="object",
 *     title="Response-Body Usuário",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="KairosDev"),
 *     @OA\Property(property="email", type="string", example="kairos@example.com"),
 *     @OA\Property(
 *         property="profile",
 *         ref="#/components/schemas/ProfileResponse"
 *     ),
 *     @OA\Property(
 *         property="flags",
 *         type="array",
 *         @OA\Items(type="string")
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-02T15:03:01Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-02T15:03:01Z")
 * )
 */
class UserResource extends JsonResource
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
            'name' => $this->username,
            'email' => $this->email,
            'profile' => $this->profile ? new ProfileResource($this->profile) : null,
            'flags' => $this->flags ? $this->flags->map(fn($flag) => $flag->name) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
