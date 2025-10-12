<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="ServiceResponse",
 *     type="object",
 *     title="Response-Body Serviço",
 *     @OA\Property(property="id", type="integer", example=10),
 *     @OA\Property(property="name", type="string", example="Corte Masculino"),
 *     @OA\Property(property="description", type="string", example="Serviço de corte de cabelo para homens."),
 *
 *     @OA\Property(
 *         property="prices",
 *         type="object",
 *         @OA\Property(property="price", type="number", format="float", example=50.00),
 *         @OA\Property(property="max", type="number", format="float", example=70.00),
 *         @OA\Property(property="min", type="number", format="float", example=30.00)
 *     ),

 *     @OA\Property(
 *         property="provider",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=7),
 *         @OA\Property(property="name", type="string", example="Carlos Ferreira"),
 *         @OA\Property(property="username", type="string", example="carlos_ferreira"),
 *         @OA\Property(property="bio", type="string", example="Barbeiro profissional desde 2010."),
 *         @OA\Property(property="avatar", type="string", format="uri", example="https://cdn.exemplo.com/avatar.jpg"),
 *         @OA\Property(property="phone", type="string", example="+55 11 91234-5678")
 *     ),

 *     @OA\Property(
 *         property="address",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=3),
 *         @OA\Property(property="state", type="string", example="SP"),
 *         @OA\Property(property="city", type="string", example="São Paulo"),
 *         @OA\Property(property="street", type="string", example="Rua das Palmeiras"),
 *         @OA\Property(property="neighborhood", type="string", example="Bela Vista"),
 *         @OA\Property(property="number", type="string", example="101"),
 *         @OA\Property(property="address", type="string", example="Rua das Palmeiras, 101 - Bela Vista, São Paulo - SP")
 *     ),

 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-02T15:00:00Z"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-30T08:30:00Z")
 * )
 */
class ServiceResource extends JsonResource
{
     /**
      * Transform the resource into an array.
      *
      * @return array<string, mixed>
      */
     public function toArray(Request $request): array
     {
          return [
               "id" => $this->id,
               "name" => $this->name,
               "description" => $this->description,
               "prices" => [
                    "price" => $this->price,
                    "max" => $this->max_price,
                    "min" => $this->min_price
               ],
               "provider" => [
                    "id" => $this->user->id,
                    "name" => $this->user->profile->name,
                    "username" => $this->user->username,
                    "bio" => $this->user->profile->bio,
                    "avatar" => $this->user->profile->avatar,
                    "phone" => $this->user->profile->phone,
               ],
               "address" => [
                    "id" => $this->address->id,
                    "state" => $this->address->state,
                    "city" => $this->address->city,
                    "street" => $this->address->street,
                    "neighborhood" => $this->address->neighborhood,
                    "number" => $this->number,
                    "address" => $this->address->address,
               ],
               "created_at" => $this->created_at,
               "updated_at" => $this->updated_at,
          ];
     }
}
