<?php

namespace App\Livewire;

use Livewire\Component;

class Checkout extends Component
{
    public $plan;

    public $plans = [
        '6_month' => [
            'name' => '6 Month Plan',
            'duration_days' => 180,
            'original_daily_price' => 1.11,
            'daily_price' => 0.28,
            'stripe_price_id' => 'price_1QKkIZKTnE0q7EuUAJHAe1cS',
            'description' => 'Billed every 6 months',
            'discount' => 'Save 75%',
        ],
        '3_month' => [
            'name' => '3 Month Plan',
            'duration_days' => 90,
            'original_daily_price' => 1.25,
            'daily_price' => 0.41,
            'stripe_price_id' => 'price_1QKkIZKTnE0q7EuUAJHAe1cS',
            'description' => 'Billed every 3 months',
            'discount' => 'Save 67%',
        ],
        '1_month' => [
            'name' => '1 Month Plan',
            'duration_days' => 30,
            'original_daily_price' => 1.99,
            'daily_price' => 0.99,
            'stripe_price_id' => 'price_1QKkIZKTnE0q7EuUAJHAe1cS',
            'description' => 'Billed every 1 month',
            'discount' => 'Save 50%',
        ],
    ];

    public function selectPlan($plan)
    {
        $this->plan = $plan;

        // Redirect to Stripe Checkout
        return redirect()->route('stripe.checkout', ['plan' => $plan]);
    }

    public function render()
    {
        return view('livewire.checkout')->layout('layouts.guest');
    }
}
