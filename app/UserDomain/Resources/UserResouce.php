<?php

namespace App\UserDomain\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResouce extends JsonResource
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
            'address_delivery' => $this->address_delivery,
            'address_billing' => $this->address_billing,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'user_id' => $this->user_id,
            'balance' => $this->balance
        ];
    }
}
