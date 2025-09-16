<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Luka',
            'surname' => 'Petrović',
            'email' => 'gost@example.com',
            'phone' => '060111222',
            'password' => Hash::make('password'),
            'role_id' => 1,
        ]);

        User::factory()->create([
            'name' => 'Jelena',
            'surname' => 'Stojanović',
            'email' => 'recepcioner@example.com',
            'phone' => '060222333',
            'password' => Hash::make('password'),
            'role_id' => 2,
        ]);

        User::factory()->create([
            'name' => 'Darko',
            'surname' => 'Marković',
            'email' => 'menadzer@example.com',
            'phone' => '060333444',
            'password' => Hash::make('password'),
            'role_id' => 3,
        ]);
    }
}
