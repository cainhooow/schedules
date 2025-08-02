<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="AddressResponse",
 *     type="object",
 *     title="Response-Body Endereço de usuário",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="address", type="string", example="Rua das Flores, 123"),
 *     @OA\Property(property="state", type="string", example="SP"),
 *     @OA\Property(property="city", type="string", example="São Paulo"),
 *     @OA\Property(property="street", type="string", example="Rua das Flores"),
 *     @OA\Property(property="neighborhood", type="string", example="Jardim das Rosas"),
 *     @OA\Property(property="number", type="string", example="123A"),
 *     @OA\Property(property="default", type="boolean", example=true)
 * )
 */
class AddressResource extends JsonResource
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
            'address' => $this->address,
            'state' => $this->state,
            'city' => $this->city,
            'street' => $this->street,
            'neighborhood' => $this->neighborhood,
            'number' => $this->number,
            'default' => $this->default,
        ];
    }
}
