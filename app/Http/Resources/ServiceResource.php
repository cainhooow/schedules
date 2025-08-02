<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
                "id" => $this->id,
                "name" => $this->user->profile->name,
                "username" => $this->user->username,
                "bio" => $this->user->profile->bio,
                "avatar" => $this->user->profile->avatar,
                "phone" => $this->user->profile->phone,
            ],
            "address" => [
                "id" => $this->id,
                "state" => $this->address->state,
                "city" => $this->address->city,
                "street" => $this->address->street,
                "neighborhood" => $this->address->neighborhood,
                "number" => $this->number,
                "address" => $this->address->address,
            ],
            "updated_at" => $this->created_at,
            "created_at" => $this->created_at,
        ];
    }
}
