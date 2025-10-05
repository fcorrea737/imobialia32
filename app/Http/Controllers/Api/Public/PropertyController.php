<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicPropertyResource;
use App\Models\Property;
use App\Models\Tenant; // <-- Importe o Tenant
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Lista os imóveis disponíveis de um tenant específico.
     */
    public function index(Request $request, string $tenant_slug)
    {
        // 1. Encontra o tenant pelo slug ou falha (retornando 404)
        $tenant = Tenant::where('slug', $tenant_slug)->firstOrFail();

        // 2. Busca os imóveis APENAS daquele tenant
        $properties = $tenant->properties() // <-- MÁGICA: Busca através do relacionamento
            ->where('status', 'available')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return PublicPropertyResource::collection($properties);
    }

    /**
     * Exibe um imóvel específico de um tenant.
     */
    public function show(string $tenant_slug, Property $property)
    {
        // Garante que o imóvel encontrado pertence ao tenant da URL
        if ($property->tenant->slug !== $tenant_slug) {
            abort(404);
        }

        // Garante que o imóvel está disponível
        if ($property->status !== 'available') {
            abort(404);
        }

        return new PublicPropertyResource($property);
    }
}
