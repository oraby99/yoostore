<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'id'                            => $this->id,
            'name'                          => $this->name,
            'email'                         => $this->email,
            'country_code'                  => $this->country_code,
            'phone'                         => $this->phone,
            'token'                         => $this->when(isset($this->token), $this->token),
            'profile_image'                 => $this->profile_image == null ? asset('admin/images/profile.png') : asset('storage/' . $this->profile_image),
            'device_token'                  => $this->device_token,
        ];
    }
}
