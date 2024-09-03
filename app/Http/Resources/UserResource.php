<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'country_code'  => $this->country_code,
            'phone'         => $this->phone,
            'token'         => $this->when(isset($this->token), $this->token),
            'profile_image' => $this->getProfileImageUrl(),
            'device_token'  => $this->device_token,
        ];
    }
    private function getProfileImageUrl(): string
    {
        if ($this->profile_image == null) {
            return url('images/yoostore.png');
        }
        if (Str::startsWith($this->profile_image, ['http://', 'https://'])) {
            return $this->profile_image;
        }
        return url($this->profile_image);
    }
}


