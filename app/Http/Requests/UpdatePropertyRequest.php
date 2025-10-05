<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Pega o imóvel que está sendo acessado pela rota (Route Model Binding)
        $property = $this->route('property');

        // Garante que o usuário autenticado é o dono do imóvel que ele está tentando editar.
        // Esta é a nossa regra de segurança!
        return $this->user()->id === $property->owner_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            
            'property_type' => 'sometimes|string|max:255',
            'listing_type' => 'sometimes|string|in:for_rent,for_sale,both',
            'address_street' => 'sometimes|string|max:255',
            'address_number' => 'sometimes|string|max:50',
            'address_neighborhood' => 'sometimes|string|max:255',
            'address_city' => 'sometimes|string|max:255',
            'address_state' => 'sometimes|string|size:2',
            'address_zipcode' => 'sometimes|string|max:10',
            'bedrooms' => 'sometimes|integer|min:0',
            'bathrooms' => 'sometimes|integer|min:0',
            'rental_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'accepts_pets' => 'sometimes|boolean',
        ];
    }
}
