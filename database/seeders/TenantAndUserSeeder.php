<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TenantAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Cria uma imobiliária (Tenant)
        $tenant = Tenant::create([
            'name' => 'Imobiliária Exemplo',
            'slug' => 'imobiliaria-exemplo',
            'plan' => 'basic',
            'address_street' => 'Rua das Flores',
            'address_number' => '123',
            'address_complement' => 'Sala 1',
            'address_neighborhood' => 'Centro',
            'address_city' => 'São Paulo',
            'address_state' => 'SP',
            'address_zipcode' => '01000-000',
        ]);

        // Cria um usuário para a imobiliária
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Administrador Exemplo',
            'email' => 'admin@imobiliaria.com',
            'password' => Hash::make('senha123'),
            'system_role' => 'internal',
            'phone' => '11999999999',
            'document_number' => '123.456.789-00',
            'address_street' => 'Rua das Flores',
            'address_number' => '123',
            'address_complement' => 'Sala 1',
            'address_neighborhood' => 'Centro',
            'address_city' => 'São Paulo',
            'address_state' => 'SP',
            'address_zipcode' => '01000-000',
        ]);
    }
}
