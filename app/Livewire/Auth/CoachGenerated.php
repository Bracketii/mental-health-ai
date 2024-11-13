<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Session;

class CoachGenerated extends Component
{
    public $email;

    protected $rules = [
        'email' => 'required|email',
    ];

    public function submitEmail()
    {
        $this->validate();

        // Store the email in session to pass to the next step
        Session::put('pending_email', $this->email);

        // Redirect to the checkout page
        return redirect()->route('checkout');
    }

    public function render()
    {
        return view('livewire.auth.coach-generated')->layout('layouts.guest');
    }
}
