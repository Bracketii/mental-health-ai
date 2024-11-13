<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class PasswordSetup extends Component
{
    public $password;
    public $password_confirmation;
    public $email;
    public $plan;

    protected $rules = [
        'password' => 'required|min:8|confirmed',
    ];

    public function mount()
    {
        $this->email = session('email');
        $this->plan = session('plan');

        if (!$this->email || !$this->plan) {
            return redirect()->route('coach.generated')->with('error', 'Invalid session data.');
        }
    }

    public function submitPassword()
    {
        $this->validate();

        // Find the user
        $user = User::where('email', $this->email)->first();

        if (!$user) {
            return redirect()->route('coach.generated')->with('error', 'User not found.');
        }

        // Update the user's password
        $user->password = Hash::make($this->password);
        $user->save();

        // Retrieve selectedOptions from session
        $selectedOptions = Session::get('selectedOptions', []);

        if (!empty($selectedOptions)) {
            foreach ($selectedOptions as $question_id => $option_id) {
                UserAnswer::create([
                    'user_id' => $user->id,
                    'question_id' => $question_id,
                    'option_id' => $option_id,
                ]);
            }
        }

        // Clear session data
        Session::forget('selectedOptions');
        Session::forget('selected_plan');
        // 'pending_email' was already cleared in the success controller

        // Log the user in
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Account created and subscription successful! Welcome to Emotica.');
    }

    public function render()
    {
        return view('livewire.auth.password-setup')->layout('layouts.guest');
    }
}
