<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Subscription;
use Illuminate\Database\Seeder;
use App\Models\SubscriptionItem;
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
        $admin = User::firstOrCreate(
            ['email' => 'admin@runonempathy.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // Use a secure password
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Check if the admin user already has a subscription
        if (!$admin->subscriptions()->exists()) {
            // Add a subscription for the admin user
            $subscription = Subscription::create([
                'name' => 'Admin Plan',
                'user_id' => $admin->id,
                'type' => 'subscription',
                'stripe_id' => 'sub_123456789', // Replace with actual Stripe subscription ID
                'stripe_status' => 'active',
                'stripe_price' => 'price_123456789', // Replace with actual Stripe price ID
                'quantity' => 1,
                'trial_ends_at' => now()->addDays(300), // Optional trial period
                'ends_at' => null, // Admin subscription is active indefinitely
            ]);

            // Add subscription items for the admin subscription
            SubscriptionItem::create([
                'subscription_id' => $subscription->id,
                'stripe_id' => 'si_123456789', // Replace with actual Stripe subscription item ID
                'stripe_product' => 'prod_123456789', // Replace with actual Stripe product ID
                'stripe_price' => 'price_123456789', // Replace with actual Stripe price ID
                'quantity' => 1,
            ]);
        }
    }
}
