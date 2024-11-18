<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if an admin user already exists
        if (!User::where('email', 'admin@runonempathy.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@runonempathy.com',
                'password' => Hash::make('password'), // Use a secure password
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }
    }
}
