<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid, // Expondo o UUID publicamente
            'code' => $this->code,
            'type' => $this->property_type,
            'listingType' => $this->listing_type,
            'address' => [
                'street' => $this->address_street,
                'number' => $this->address_number,
                'city' => $this->address_city,
                'state' => $this->address_state,
                'zipcode' => $this->address_zipcode,
            ],
            'details' => [
                'bedrooms' => $this->bedrooms,
                'bathrooms' => $this->bathrooms,
                'usableArea' => $this->usable_area_sqm,
                'acceptsPets' => $this->accepts_pets,
            ],
            'pricing' => [
                'rentalPrice' => $this->rental_price,
                'salePrice' => $this->sale_price,
                'condoFee' => $this->condo_fee,
            ],
            // Carrega os dados do proprietário apenas se eles foram pré-carregados no controller
            'owner' => new UserResource($this->whenLoaded('owner')),
            'createdAt' => $this->created_at->toIso8601String(),
        ];
    }
}
