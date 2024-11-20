<?php

namespace App\Livewire\Admin\ConversationMonitor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ConversationHistory;
use App\Models\User;
use App\Models\Coach;
use App\Models\Subscription;

class Index extends Component
{
    use WithPagination;

    // Define properties for search and filtering
    public $search = '';
    public $selectedUser = '';
    public $selectedCoach = '';
    public $selectedSubscription = '';
    public $dateFrom = '';
    public $dateTo = '';

    // To track sorting
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $paginationTheme = 'tailwind'; // Use Tailwind for pagination

    // Updating the search resets the pagination
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedUser()
    {
        $this->resetPage();
    }

    public function updatingSelectedCoach()
    {
        $this->resetPage();
    }

    public function updatingSelectedSubscription()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    /**
     * Toggle sorting direction.
     *
     * @param string $field
     * @return void
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function render()
    {
        // Fetch distinct users, coaches, and subscriptions for filters
        $users = User::orderBy('name')->get();
        $coaches = Coach::orderBy('name')->get();
        $subscriptions = Subscription::orderBy('name')->get(); // Assuming you have a Subscription model

        // Query conversations with applied filters and search
        $conversations = ConversationHistory::with(['user', 'user.subscriptions', 'user.coach'])
            ->when($this->search, function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->selectedUser, function($query) {
                $query->where('user_id', $this->selectedUser);
            })
            ->when($this->selectedCoach, function($query) {
                $query->where('coach_type', $this->selectedCoach);
            })
            ->when($this->selectedSubscription, function($query) {
                $query->whereHas('user.subscriptions', function($q) {
                    $q->where('name', $this->selectedSubscription);
                });
            })
            ->when($this->dateFrom, function($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15); // Adjust pagination as needed

        return view('livewire.admin.conversation-monitor.index', [
            'conversations' => $conversations,
            'users' => $users,
            'coaches' => $coaches,
            'subscriptions' => $subscriptions,
        ])->layout('layouts.admin');
    }
}
