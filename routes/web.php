<?php

use App\Livewire\Auth\QuestionsFlow;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\RegistrationFlow;

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
