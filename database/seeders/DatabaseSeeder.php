<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\QuestionsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            QuestionsTableSeeder::class,
            AdminUserSeeder::class,
            CoachesTableSeeder::class,
            SystemMessageSeeder::class,
        ]);
    }
}
