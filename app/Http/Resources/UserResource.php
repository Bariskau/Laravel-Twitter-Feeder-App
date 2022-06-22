<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->uuid,
            'email'             => $this->email,
            'name'              => $this->name,
            'phone_number'      => $this->phone_number,
            'email_verified_at' => $this->email_verified_at,
            'sms_verified_at'   => $this->sms_verified_at,
        ];
    }
}
