<?php

namespace Database\Seeders;

use App\Models\Coach;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CoachesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coach::create([
            'name' => 'Dr. Emily Smith',
            'designation' => 'Licensed Clinical Psychologist',
            'avatar' => null, // Ensure you have this image in the public directory
            'bio' => 'Dr. Emily specializes in cognitive behavioral therapy and mindfulness-based stress reduction.',
        ]);

        Coach::create([
            'name' => 'John Doe',
            'designation' => 'Certified Mental Health Counselor',
            'avatar' => null,
            'bio' => 'John focuses on helping clients manage anxiety and develop resilience through personalized coaching.',
        ]);
    }
}
