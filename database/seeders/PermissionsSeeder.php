<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Criar Permissões
        Permission::create(['name' => 'imoveis_ver']);
        Permission::create(['name' => 'imoveis_criar']);
        Permission::create(['name' => 'imoveis_editar']);
        Permission::create(['name' => 'imoveis_deletar']);

        // Criar Papéis (Roles) e atribuir permissões
        $corretorRole = Role::create(['name' => 'Corretor']);
        $corretorRole->givePermissionTo(['imoveis_ver', 'imoveis_criar', 'imoveis_editar']);

        $adminRole = Role::create(['name' => 'Administrador']);
        $adminRole->givePermissionTo(Permission::all()); // Admin pode tudo

        
    }
}
