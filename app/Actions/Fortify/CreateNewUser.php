<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\UserAnswer;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        // Validate the input including gender
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'gender' => ['required', 'in:male,female,non_binary'],
        ])->validate();

        // Retrieve the selected options from the session
        $selectedOptions = Session::get('selectedOptions', []);

        // Create the user
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'gender' => $input['gender'],
        ]);

        // Store user answers if available
        foreach ($selectedOptions as $questionId => $optionId) {
            UserAnswer::create([
                'user_id' => $user->id,
                'question_id' => $questionId,
                'option_id' => $optionId,
            ]);
        }

        // Clear session data
        Session::forget(['gender', 'selectedOptions']);

        return $user;
    }
}
