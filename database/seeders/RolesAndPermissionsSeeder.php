<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa o cache do Spatie antes de rodar
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Cria os papéis (Roles)
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleSindico = Role::create(['name' => 'sindico']);
        $roleConselho = Role::create(['name' => 'conselho_fiscal']);
        $roleMorador = Role::create(['name' => 'morador']);

        // (Opcional) Você pode criar permissões específicas no futuro, 
        // como: Permission::create(['name' => 'aprovar contas']);
        // Mas por enquanto, vamos proteger o sistema com base apenas nos Roles.
    }
}