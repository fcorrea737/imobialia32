<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Por enquanto, vamos permitir. Mais tarde, podemos colocar a lógica de permissão aqui.
        // ex: return auth()->user()->can('imoveis_criar');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'owner_id' => 'required|string|exists:users,uuid', // UUID do proprietário
            'property_type' => 'required|string|max:255',
            'property_type' => 'required|string|max:255',
            'listing_type' => 'required|string|in:for_rent,for_sale,both',
            'address_street' => 'required|string|max:255',
            'address_number' => 'required|string|max:50',
            'address_neighborhood' => 'required|string|max:255',
            'address_city' => 'required|string|max:255',
            'address_state' => 'required|string|size:2',
            'address_zipcode' => 'required|string|max:10',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'rental_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'condo_fee' => 'nullable|numeric|min:0',
            'iptu_value' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'accepts_pets' => 'sometimes|boolean',
        ];
    }
}
