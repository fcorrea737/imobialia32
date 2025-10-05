<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid, // Usamos o UUID como ID público
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'documentNumber' => $this->document_number,
            // Podemos adicionar o endereço completo se ele existir
            'fullAddress' => $this->whenNotNull($this->address_street, "{$this->address_street}, {$this->address_number} - {$this->address_city}"),
        ];
    }
}
