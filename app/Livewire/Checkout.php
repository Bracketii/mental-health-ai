<?php

namespace App\Livewire;

use Livewire\Component;

class Checkout extends Component
{
    public $name;
    public $email;
    public $password;
    public $plan;

    public $plans = [
        '6_month' => [
            'name' => '6 Month Plan',
            'price' => 1.11 * 180, // $1.11 per day * 180 days
            'stripe_price_id' => 'price_1QKkIZKTnE0q7EuUAJHAe1cS', // Replace with your Stripe Price ID
            'description' => 'Billed every 6 months',
            'discount' => 'Save 75%',
        ],
        '3_month' => [
            'name' => '3 Month Plan',
            'price' => 1.25 * 90, // $1.25 per day * 90 days
            'stripe_price_id' => 'price_1QKkIZKTnE0q7EuUAJHAe1cS', // Replace with your Stripe Price ID
            'description' => 'Billed every 3 months',
            'discount' => 'Save 67%',
        ],
        '1_month' => [
            'name' => '1 Month Plan',
            'price' => 1.99 * 30, // $1.99 per day * 30 days
            'stripe_price_id' => 'price_1QKkIZKTnE0q7EuUAJHAe1cS', // Replace with your Stripe Price ID
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
