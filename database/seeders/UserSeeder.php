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
            'name' => 'Milan',
            'surname' => 'Gost',
            'email' => 'gost@example.com',
            'phone' => '060111222',
            'password' => Hash::make('password'),
            'role_id' => 1,
        ]);

        User::factory()->create([
            'name' => 'Rita',
            'surname' => 'Recep',
            'email' => 'recepcioner@example.com',
            'phone' => '060222333',
            'password' => Hash::make('password'),
            'role_id' => 2,
        ]);

        User::factory()->create([
            'name' => 'Maja',
            'surname' => 'Men',
            'email' => 'menadzer@example.com',
            'phone' => '060333444',
            'password' => Hash::make('password'),
            'role_id' => 3,
        ]);
    }
}
