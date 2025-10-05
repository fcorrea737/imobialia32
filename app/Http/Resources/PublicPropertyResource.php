<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicPropertyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'code' => $this->code,
            'type' => $this->property_type,
            'listingType' => $this->listing_type,
            'address' => [
                'street' => $this->address_street,
                'neighborhood' => $this->address_neighborhood,
                'city' => $this->address_city,
                'state' => $this->address_state,
            ],
            'details' => [
                'bedrooms' => $this->bedrooms,
                'suites' => $this->suites,
                'bathrooms' => $this->bathrooms,
                'garageSpots' => $this->garage_spots,
                'usableArea' => $this->usable_area_sqm,
                'acceptsPets' => $this->accepts_pets,
            ],
            'pricing' => [
                'rentalPrice' => $this->rental_price,
                'salePrice' => $this->sale_price,
                'condoFee' => $this->condo_fee,
            ],
            'description' => $this->description,
            'amenities' => $this->amenities,
        ];
    }
}
