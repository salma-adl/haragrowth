<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'adminhara1@gmail.com',
            'password' => bcrypt('password'),
            'is_superuser' => true,
        ]);
        $admin->assignRole('admin');

        $teraphist = User::create([
            'name' => 'Teraphist',
            'email' => 'teraphisthara1@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $teraphist->assignRole('therapist');
    }
}
