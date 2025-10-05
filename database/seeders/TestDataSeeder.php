<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pega os papéis que já foram criados pelo PermissionsSeeder
        $adminRole = Role::where('name', 'Administrador')->firstOrFail();

        // --- Imobiliária A ---
        DB::transaction(function () use ($adminRole) {
            // 1. Criar a Imobiliária (Tenant)
            $tenantA = Tenant::create([
                'name' => 'Imóveis de Sucesso',
                'slug' => 'imoveis-de-sucesso',
                'address_city' => 'São Paulo',
                'address_state' => 'SP',
                'address_zipcode' => '01000-000',
            ]);

            // 2. Criar o Usuário Administrador
            $adminA = User::create([
                'tenant_id' => $tenantA->id,
                'name' => 'João da Silva (Admin A)',
                'email' => 'admin@imobiliaria-a.com',
                'password' => bcrypt('password'),
                'system_role' => 'internal',
            ]);
            $adminA->assignRole($adminRole);

            // 3. Criar um Proprietário para a Imobiliária A
            $ownerA = User::create([
                'tenant_id' => $tenantA->id,
                'name' => 'Carlos Andrade (Prop. A)',
                'email' => 'carlos.a@email.com',
                'password' => bcrypt('password'),
                'system_role' => 'owner',
            ]);

            // 4. Criar um Imóvel para a Imobiliária A
            Property::create([
                'tenant_id' => $tenantA->id,
                'owner_id' => $ownerA->id,
                'property_type' => 'Apartamento',
                'listing_type' => 'for_rent',
                'status' => 'available',
                'address_street' => 'Avenida Paulista',
                'address_number' => '1500',
                'address_neighborhood' => 'Bela Vista',
                'address_city' => 'São Paulo',
                'address_state' => 'SP',
                'address_zipcode' => '01310-200',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'rental_price' => 4500.00,
            ]);
        });

        // --- Imobiliária B ---
        DB::transaction(function () use ($adminRole) {
            // 1. Criar a Imobiliária (Tenant)
            $tenantB = Tenant::create([
                'name' => 'Top Imóveis RJ',
                'slug' => 'top-imoveis-rj',
                'address_city' => 'Rio de Janeiro',
                'address_state' => 'RJ',
                'address_zipcode' => '20000-000',
            ]);

            // 2. Criar o Usuário Administrador
            $adminB = User::create([
                'tenant_id' => $tenantB->id,
                'name' => 'Ana Costa (Admin B)',
                'email' => 'admin@imobiliaria-b.com',
                'password' => bcrypt('password'),
                'system_role' => 'internal',
            ]);
            $adminB->assignRole($adminRole);

            // 3. Criar um Proprietário para a Imobiliária B
            $ownerB = User::create([
                'tenant_id' => $tenantB->id,
                'name' => 'Pedro Martins (Prop. B)',
                'email' => 'pedro.m@email.com',
                'password' => bcrypt('password'),
                'system_role' => 'owner',
            ]);

            // 4. Criar um Imóvel para a Imobiliária B
            Property::create([
                'tenant_id' => $tenantB->id,
                'owner_id' => $ownerB->id,
                'property_type' => 'Casa',
                'listing_type' => 'for_sale',
                'status' => 'available',
                'address_street' => 'Avenida Copacabana',
                'address_number' => '1024',
                'address_neighborhood' => 'Copacabana',
                'address_city' => 'Rio de Janeiro',
                'address_state' => 'RJ',
                'address_zipcode' => '22020-001',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'sale_price' => 2500000.00,
            ]);
        });
    }
}
