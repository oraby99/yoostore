<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'phone'       => $this->phone,
            'street'      => $this->street,
            'landmark'    => $this->landmark,
            'area'        => $this->area,
            'country'     => $this->country,
            'flat_no'     => $this->flat_no,
            'address_type'=> $this->address_type,
            'is_default'  => $this->is_default,
        ];
    }
}
