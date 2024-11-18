<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    public $confirmingUserDeletion = false;
    public $userToDelete;

    protected $paginationTheme = 'tailwind'; // Or 'bootstrap' based on your setup

    protected $listeners = [
        'userDeleted' => '$refresh',
    ];

    /**
     * Mount the component and authorize access.
     */
    public function mount()
    {
        $this->authorize('viewAny', User::class);
    }

    /**
     * Render the component view.
     */
    public function render()
    {
        // Eager load subscriptions to avoid N+1 problem
        $users = User::with('subscriptions')->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.users.index', compact('users'))->layout('layouts.admin');
    }

    /**
     * Confirm deletion of a user.
     *
     * @param int $userId
     */
    public function confirmUserDeletion($userId)
    {
        $this->userToDelete = User::findOrFail($userId);
        $this->authorize('delete', $this->userToDelete);
        $this->confirmingUserDeletion = true;
    }

    /**
     * Delete the confirmed user.
     */
    public function deleteUser()
    {
        // Ensure the user exists
        if (!$this->userToDelete) {
            session()->flash('error', 'User not found.');
            return;
        }

        // Authorize deletion
        $this->authorize('delete', $this->userToDelete);

        // Prevent deletion of self
        if (auth()->user()->id === $this->userToDelete->id) {
            session()->flash('error', 'You cannot delete yourself.');
            $this->confirmingUserDeletion = false;
            $this->userToDelete = null;
            return;
        }

        // Delete the user
        $this->userToDelete->delete();

        // Reset deletion confirmation
        $this->confirmingUserDeletion = false;
        $this->userToDelete = null;

        // Flash success message
        session()->flash('message', 'User deleted successfully.');

        // Emit event to refresh the user list
        $this->dispatch('userDeleted');
    }
}
