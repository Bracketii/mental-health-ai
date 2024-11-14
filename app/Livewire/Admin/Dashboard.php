<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Dashboard extends Component
{
    // Sample Data for Dashboard Cards
    public $totalUsers = 1500;
    public $activeProjects = 75;
    public $pendingTasks = 120;
    public $totalSubscriptions = 800;
    public $activeSubscriptions = 750;
    public $canceledSubscriptions = 50;

    // Sample Data for Recent Activities
    public $recentActivities = [
        'User John Doe subscribed to the Premium plan.',
        'Jane Smith created a new project: "Mental Wellness App".',
        'Project Alpha was updated by Michael Brown.',
        'User Emily Davis provided feedback on the AI responses.',
    ];

    // Sample Data for Q&A
    public $recentQnA = [
        ['user' => 'John Doe', 'question' => 'How can I manage stress effectively?', 'date' => '2024-04-01'],
        ['user' => 'Jane Smith', 'question' => 'What are some meditation techniques?', 'date' => '2024-04-02'],
        ['user' => 'Emily Davis', 'question' => 'How does the AI personalize responses?', 'date' => '2024-04-03'],
    ];

    // Sample Data for User Feedback
    public $recentFeedback = [
        ['user' => 'John Doe', 'feedback' => 'The AI has been very helpful in managing my anxiety.', 'rating' => 5, 'date' => '2024-04-01'],
        ['user' => 'Jane Smith', 'feedback' => 'Great insights on meditation.', 'rating' => 4, 'date' => '2024-04-02'],
        ['user' => 'Emily Davis', 'feedback' => 'AI responses are quick and accurate.', 'rating' => 5, 'date' => '2024-04-03'],
    ];

    // Sample Data for Analytics
    public $monthlyUsers = [100, 150, 200, 250, 300, 350, 400, 450, 500, 550, 600, 650];
    public $monthlySubscriptions = [80, 120, 160, 200, 240, 280, 320, 360, 400, 440, 480, 520];

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('layouts.admin');
    }
}
