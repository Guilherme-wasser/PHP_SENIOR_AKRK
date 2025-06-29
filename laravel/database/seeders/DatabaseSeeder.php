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
        // Chama os seeders específicos
        $this->call([
            AdminUserSeeder::class,
            FundSeeder::class,
        ]);

        // Opcional: factory de teste, se quiser
        User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
