<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class, 
            AdminUserSeeder::class,
            FundSeeder::class,
        ]);

        // Test User apenas se ainda não existir
        \App\Models\User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'     => 'Test User',
                'password' => bcrypt('password'),  // se quiser autenticar
                'role'     => 'user',
            ]
        );
    }
}
