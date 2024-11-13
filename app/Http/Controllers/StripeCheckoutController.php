<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class StripeCheckoutController extends Controller
{

    public function checkout(Request $request)
    {
        $planKey = $request->query('plan');

        // Define your plans with actual Stripe Price IDs
        $plans = [
            '6_month' => [
                'name' => '6 Month Plan',
                'price' => 1.11 * 180, // $1.11 per day * 180 days
                'stripe_price_id' => 'price_1QKkIZKTnE0q7EuUAJHAe1cS', // Replace with your actual Stripe Price ID
            ],
            '3_month' => [
                'name' => '3 Month Plan',
                'price' => 1.25 * 90, // $1.25 per day * 90 days
                'stripe_price_id' => 'price_1QKkIZKTnE0q7EuUAJHAe1cS', // Replace with your actual Stripe Price ID
            ],
            '1_month' => [
                'name' => '1 Month Plan',
                'price' => 1.99 * 30, // $1.99 per day * 30 days
                'stripe_price_id' => 'price_1QKkIZKTnE0q7EuUAJHAe1cS', // Replace with your actual Stripe Price ID
            ],
        ];

        if (!isset($plans[$planKey])) {
            return redirect()->route('checkout')->with('error', 'Invalid plan selected.');
        }

        $plan = $plans[$planKey];

        // Store selected plan in session
        Session::put('selected_plan', $planKey);

        // Retrieve email from session
        $email = Session::get('pending_email');

        if (!$email) {
            return redirect()->route('coach.generated')->with('error', 'Email not provided.');
        }

        // Create Stripe Checkout Session
        $checkout = Cashier::stripe()->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'customer_email' => $email,
            'line_items' => [
                [
                    'price' => $plan['stripe_price_id'],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'subscription',
            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel'),
            'metadata' => [
                'email' => $email,
                'plan' => $planKey,
            ],
        ]);

        return redirect($checkout->url);
    }

    public function success(Request $request)
    {
        $session_id = $request->query('session_id');
    
        if (!$session_id) {
            return redirect()->route('home')->with('error', 'No session ID provided.');
        }
    
        // Retrieve the Checkout Session from Stripe
        $session = Cashier::stripe()->checkout->sessions->retrieve($session_id);
    
        // Get customer email from metadata
        $email = $session->metadata->email ?? Session::get('pending_email');
    
        if (!$email) {
            return redirect()->route('coach.generated')->with('error', 'Email not provided.');
        }
    
        // Find or create the user
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'User', // You may want to collect the name during email submission
                'password' => Hash::make(Str::random(16)), // Temporary password; user will set it later
                'gender' => Session::get('gender'),
            ]
        );
    
        // Set the Stripe customer ID
        $user->stripe_id = $session->customer;
        $user->save();
    
        // Clear session data
        Session::forget('pending_email');
        Session::forget('selected_plan');
    
        // Log the user in and redirect to password setup
        Auth::login($user);
    
        return redirect()->route('password.setup')->with(['email' => $email, 'plan' => $session->metadata->plan]);
    }

    public function cancel()
    {
        return redirect()->route('coach.generated')->with('error', 'Payment was canceled.');
    }

}
