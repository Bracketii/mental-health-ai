<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Dashboard extends Component
{
 // Metrics
 public $totalUsers;
 public $totalActiveSessions;
 public $totalSubscriptions;
 public $totalRevenue;

 // Other properties (dummy data)
 public $activeProjects = 75; // Replace with real data if available
 public $pendingTasks = 120; // Replace with real data if available

 // Sample Data for Recent Activities (optional)
 public $recentActivities = [
     'User John Doe subscribed to the Premium plan.',
     'Jane Smith created a new project: "Mental Wellness App".',
     'Project Alpha was updated by Michael Brown.',
     'User Emily Davis provided feedback on the AI responses.',
 ];

 // Sample Data for Q&A (optional)
 public $recentQnA = [
     ['user' => 'John Doe', 'question' => 'How can I manage stress effectively?', 'date' => '2024-04-01'],
     ['user' => 'Jane Smith', 'question' => 'What are some meditation techniques?', 'date' => '2024-04-02'],
     ['user' => 'Emily Davis', 'question' => 'How does the AI personalize responses?', 'date' => '2024-04-03'],
 ];

 // Sample Data for User Feedback (optional)
 public $recentFeedback = [
     ['user' => 'John Doe', 'feedback' => 'The AI has been very helpful in managing my anxiety.', 'rating' => 5, 'date' => '2024-04-01'],
     ['user' => 'Jane Smith', 'feedback' => 'Great insights on meditation.', 'rating' => 4, 'date' => '2024-04-02'],
     ['user' => 'Emily Davis', 'feedback' => 'AI responses are quick and accurate.', 'rating' => 5, 'date' => '2024-04-03'],
 ];

 // Sample Data for Analytics (optional)
 public $monthlyUsers = [100, 150, 200, 250, 300, 350, 400, 450, 500, 550, 600, 650];
 public $monthlySubscriptions = [80, 120, 160, 200, 240, 280, 320, 360, 400, 440, 480, 520];

 /**
  * Mount method to initialize component data.
  */
 public function mount()
 {
     $this->fetchMetrics();
 }

 /**
  * Fetch real-time metrics from the database.
  */
  public function fetchMetrics()
  {
      // Total Users
      $this->totalUsers = Cache::remember('total_users', now()->addMinutes(10), function () {
          return User::count();
      });
  
      // Total Active Sessions
      $this->totalActiveSessions = Cache::remember('total_active_sessions', now()->addMinutes(10), function () {
          return DB::table('sessions')
              ->distinct('user_id')
              ->whereNotNull('user_id')
              ->count('user_id');
      });
  
      // Total Subscriptions
      $this->totalSubscriptions = Cache::remember('total_subscriptions', now()->addMinutes(10), function () {
          return Subscription::active()->count();
      });

        
        $this->totalRevenue = Cache::remember('total_revenue', now()->addMinutes(10), function () {
            return Subscription::active()
                ->join('subscription_items', 'subscriptions.id', '=', 'subscription_items.subscription_id')
                ->join('users', 'subscriptions.user_id', '=', 'users.id')
                ->where('users.role', 'user') // Ensure only admin subscriptions are considered
                ->sum(DB::raw('subscription_items.quantity * (subscription_items.stripe_price::numeric / 100)'));
        });
        
  }

 /**
  * Render the component view.
  */
 public function render()
 {
     return view('livewire.admin.dashboard')->layout('layouts.admin');
 }
}
