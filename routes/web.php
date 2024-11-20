<?php

use App\Livewire\Checkout;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Users\Index;
use App\Livewire\Admin\Users\Create;
use App\Livewire\Auth\PasswordSetup;
use App\Livewire\Auth\QuestionsFlow;
use App\Livewire\Auth\CoachGenerated;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\RegistrationFlow;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\StripeCheckoutController;
use App\Livewire\Admin\CoachManagement\Index as CoachIndex;
use \App\Livewire\Admin\Questionnaires\Index as QuestionnairesIndex;
use App\Livewire\Admin\ContextManagement\Index as ContextManagementIndex;
use App\Livewire\Admin\SystemMessageManagement\Index as SystemMessageIndex;
use App\Livewire\Admin\ConversationMonitor\Index as ConversationMonitorIndex;
use App\Livewire\Admin\SubscriptionMonitor\Index as SubscriptionMonitorIndex;

Route::get('/', function () {
    return view('welcome');
});

// **Define the 'home' route**
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Admin routes ----------------------------------------------------
    Route::prefix('admin')->middleware(['admin'])->group(function () {
        Route::get('dashboard', Dashboard::class)->name('admin.dashboard');

        // Users Management
        Route::get('users', Index::class)->name('admin.users.index');
        Route::get('users/create', Create::class)->name('admin.users.create');

        // Questionnaires Management
        Route::get('questionnaires', QuestionnairesIndex::class)->name('admin.questionnaires.index');

        // Questionnaires Management
        Route::get('coaches', CoachIndex::class)->name('admin.coaches.index');

        Route::get('system-messages', SystemMessageIndex::class)->name('admin.system-messages');

        Route::get('gpt-management', ContextManagementIndex::class)->name('admin.gpt-management');

        Route::get('conversation-monitor', ConversationMonitorIndex::class)->name('admin.conversation-monitor');

        Route::get('subscriptions-monitor', SubscriptionMonitorIndex::class)->name('admin.subscriptions-monitor');
    });
    // End Admin routes ------------------------------------------------
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

