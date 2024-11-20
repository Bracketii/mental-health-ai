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
            'avatar' => 'https://img.freepik.com/free-photo/closeup-young-female-professional-making-eye-contact-against-colored-background_662251-651.jpg', // Ensure you have this image in the public directory
            'bio' => 'Dr. Emily specializes in cognitive behavioral therapy and mindfulness-based stress reduction.',
        ]);

        Coach::create([
            'name' => 'John Doe',
            'designation' => 'Certified Mental Health Counselor',
            'avatar' => 'https://t3.ftcdn.net/jpg/03/95/29/76/360_F_395297652_J7Bo5IVAkYo1LFzPjEhldbOPNstxYx4i.jpg',
            'bio' => 'John focuses on helping clients manage anxiety and develop resilience through personalized coaching.',
        ]);
    }
}
