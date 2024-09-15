<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'typeprice' => $this->typeprice,
            'typeimage' => $this->typeimage ? url('storage/' . $this->typeimage) : null,
            'typename' => $this->typename,
            'typestock' => $this->typestock,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

