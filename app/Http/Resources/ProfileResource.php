<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ProfileResponse",
 *     type="object",
 *     title="Response-Body Perfil de usuário",
 *     @OA\Property(property="id", type="integer", example=123),
 *     @OA\Property(property="name", type="string", example="Caio Augusto"),
 *     @OA\Property(property="bio", type="string", example="Desenvolvedor backend e entusiasta de tecnologia."),
 *     @OA\Property(property="phone", type="string", example="+55 11 00000-5678"),
 *     @OA\Property(property="avatar", type="string", format="uri", example="https://cdn.exemplo.com/avatars/123.jpg")
 * )
 */
class ProfileResource extends JsonResource
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
            'name' => $this->name,
            'bio' => $this->bio,
            'phone' => $this->phone,
            'avatar' => $this->avatar
        ];
    }
}
