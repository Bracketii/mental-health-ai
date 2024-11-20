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
        // Skip for logged-in users
        if (Auth::check()) {
            return redirect()->route('dashboard')->with('success', 'Subscription successful! Welcome back.');
        }

        $this->email = session('email');
        $this->plan = session('plan');

        if (!$this->email || !$this->plan) {
            return redirect()->route('coach.generated')->with('error', 'Invalid session data.');
        }
    }

    public function submitPassword()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            return redirect()->route('coach.generated')->with('error', 'User not found.');
        }

        $user->password = Hash::make($this->password);
        $user->save();

        $this->recordAnswers($user->id);

        Session::forget(['selectedOptions', 'selected_plan']);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Account created and subscription successful!');
    }

    private function recordAnswers($userId)
    {
        $selectedOptions = Session::get('selectedOptions', []);
        foreach ($selectedOptions as $questionId => $optionId) {
            UserAnswer::create([
                'user_id' => $userId,
                'question_id' => $questionId,
                'option_id' => $optionId,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.auth.password-setup')->layout('layouts.guest');
    }
}
