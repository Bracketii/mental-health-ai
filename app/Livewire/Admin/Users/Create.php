<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Create extends Component
{
    use AuthorizesRequests;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $gender;
    public $role = 'user'; // Default role

    /**
     * Mount the component and authorize access.
     */
    public function mount()
    {
        $this->authorize('create', User::class);
    }

    /**
     * Define validation rules.
     */
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'gender' => ['nullable', Rule::in(['male', 'female', 'non_binary'])],
            'role' => ['required', Rule::in(['user', 'admin'])],
        ];
    }

    /**
     * Create a new user.
     */
    public function createUser()
    {
        $this->authorize('create', User::class);

        $validatedData = $this->validate();

        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'gender' => $validatedData['gender'],
            'role' => $validatedData['role'],
        ]);

        session()->flash('message', 'User created successfully.');

        // Reset form fields
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'gender', 'role']);

        // Redirect to user list
        return redirect()->route('admin.users.index');
    }

    public function redirectToUsersList()
    {
        return redirect()->route('admin.users.index');
    }


    /**
     * Render the component view.
     */
    public function render()
    {
        return view('livewire.admin.users.create')->layout('layouts.admin');
    }
}
