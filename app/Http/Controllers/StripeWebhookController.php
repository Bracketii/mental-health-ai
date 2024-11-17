<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;

class StripeWebhookController extends CashierWebhookController
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');
    
        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['status' => 'invalid signature'], 400);
        }
    
        $eventType = $event->type;
        $session = $event->data->object;
    
        if ($eventType == 'checkout.session.completed' && $session->mode == 'subscription') {
            // Retrieve the subscription
            $subscriptionId = $session->subscription;
            $subscription = Cashier::stripe()->subscriptions->retrieve($subscriptionId);
    
            // Get customer email from metadata
            $email = $session->metadata->email ?? null;
            $planKey = $session->metadata->plan ?? 'default';
    
            if (!$email) {
                \Log::warning('Email not found in checkout.session.completed webhook.');
                return response()->json(['status' => 'email not found'], 200);
            }
    
            // Find the user by email
            $user = User::where('email', $email)->first();
    
            if (!$user) {
                \Log::warning('User not found for email: ' . $email);
                return response()->json(['status' => 'user not found'], 200);
            }
    
            // Update or create the subscription in your database
            $user->subscriptions()->updateOrCreate(
                ['stripe_id' => $subscription->id],
                [
                    'name' => $planKey,
                    'stripe_status' => $subscription->status,
                    'stripe_price' => $subscription->items->data[0]->price->id,
                    'quantity' => $subscription->quantity,
                    'ends_at' => $subscription->cancel_at ? \Carbon\Carbon::createFromTimestamp($subscription->cancel_at) : null,
                ]
            );
    
            \Log::info('Subscription synced for user: ' . $user->email);
        }
    
        return response()->json(['status' => 'success'], 200);
    }
    
}