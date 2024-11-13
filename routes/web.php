<?php

use App\Livewire\Checkout;
use App\Livewire\Auth\PasswordSetup;
use App\Livewire\Auth\QuestionsFlow;
use App\Livewire\Auth\CoachGenerated;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\RegistrationFlow;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\StripeCheckoutController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


// Route to handle questions based on gender
Route::get('/questions', QuestionsFlow::class)->name('questions.flow');
Route::get('/coach-generated', CoachGenerated::class)->name('coach.generated');
Route::get('/checkout', Checkout::class)->name('checkout');

Route::get('/stripe/checkout', [StripeCheckoutController::class, 'checkout'])->name('stripe.checkout');
Route::get('/stripe/success', [StripeCheckoutController::class, 'success'])->name('stripe.success');
Route::get('/stripe/cancel', [StripeCheckoutController::class, 'cancel'])->name('stripe.cancel');

Route::get('/password-setup', PasswordSetup::class)->name('password.setup');
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('stripe.webhook');