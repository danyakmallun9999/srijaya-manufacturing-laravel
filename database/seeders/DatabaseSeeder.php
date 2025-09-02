<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'adminsrijaya@gmail.com',
            'role' => 'admin',
        ]);

        // User::factory()->create([
        //     'name' => 'user',
        //     'email' => 'user@gmail.com',
        // ]);

        // User::factory()->create([
        //     'name' => 'manager',
        //     'email' => 'manager@gmail.com',
        // ]);
    }
}
