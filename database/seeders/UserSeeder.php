<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed an admin user
        User::create([
            'user_id' => '00000',
            'username' => 'Admin',
            'email' => 'admin@adopt.com',
            'password' => bcrypt('Admin123'), 
            'phone' => '0812345678901',
            'picture' => 'images/default.png',
            'terms' => true,
            'is_admin' => true, 
        ]);

        User::factory()
        ->withReference()
        ->count(10)
        ->create();

    }
}