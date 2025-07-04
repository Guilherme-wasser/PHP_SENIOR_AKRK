<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // ⚡ Cria o papel 'admin' se não existir
        $role = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'api'] // cuidado com o guard
        );

        // ⚡ Cria/atualiza o usuário
        $user = User::updateOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('secret'),
                'role'     => 'admin',
            ]
        );

        // ⚡ Atribui o papel
        $user->assignRole($role);
    }
}
