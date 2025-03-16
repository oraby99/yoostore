<?php

namespace App\Http\Resources;

use App\Models\Address;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    protected $userId;

    public function __construct($resource, $userId = null)
    {
        parent::__construct($resource);
        $this->userId = $userId;
    }

    public function toArray($request)
    {
        $defaultAddress = Address::where('user_id', $this->userId)
                                ->where('is_default', 1)
                                ->first();

        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'product' => new ProductResource($this->product, $this->userId),
            //'default_address' => $defaultAddress ? new AddressResource($defaultAddress) : null,
        ];
    }
}