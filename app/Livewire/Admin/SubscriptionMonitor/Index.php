<?php

namespace App\Livewire\Admin\SubscriptionMonitor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    use WithPagination;

    // Define properties for search and filtering
    public $search = '';
    public $selectedPlan = '';
    public $selectedStatus = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $sortField = 'created_at'; // Updated to subscription's own field
    public $sortDirection = 'desc';

    protected $paginationTheme = 'tailwind'; // Use Tailwind for pagination

    // Updating properties resets the pagination
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedPlan()
    {
        $this->resetPage();
    }

    public function updatingSelectedStatus()
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
        // Fetch distinct subscription plans for filters
        $plans = Subscription::select('name')->distinct()->orderBy('name')->pluck('name');

        // Define possible subscription statuses (based on Stripe)
        $statuses = [
            'active',
            'past_due',
            'canceled',
            'incomplete',
            'incomplete_expired',
            'trialing'
        ];

        // Query subscriptions with applied filters and search
        $subscriptions = Subscription::with(['user', 'user.coach', 'user.subscriptions'])
            ->when($this->search, function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->selectedPlan, function($query) {
                $query->where('name', $this->selectedPlan);
            })
            ->when($this->selectedStatus, function($query) {
                $query->where('stripe_status', $this->selectedStatus);
            })
            ->when($this->dateFrom, function($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15); // Adjust pagination as needed

        return view('livewire.admin.subscription-monitor.index', [
            'subscriptions' => $subscriptions,
            'plans' => $plans,
            'statuses' => $statuses,
        ])->layout('layouts.admin');
    }

    /**
     * Cancel a user's subscription.
     *
     * @param int $subscriptionId
     * @return void
     */
    public function cancelSubscription($subscriptionId)
    {
        try {
            $subscription = Subscription::findOrFail($subscriptionId);
            $subscription->cancel();

            session()->flash('message', 'Subscription canceled successfully.');
            Log::channel('admin')->info('Subscription canceled', ['subscription_id' => $subscriptionId]);
        } catch (\Exception $e) {
            Log::channel('admin')->error('Error canceling subscription', ['error' => $e->getMessage()]);
            session()->flash('error', 'An error occurred while canceling the subscription.');
        }
    }

    /**
     * Confirm cancellation of a subscription.
     *
     * @param int $subscriptionId
     * @return void
     */
    public $subscriptionToCancel = null;
    public $showCancelModal = false;

    public function confirmCancellation($subscriptionId)
    {
        $this->subscriptionToCancel = $subscriptionId;
        $this->showCancelModal = true;
    }

    public function closeCancelModal()
    {
        $this->subscriptionToCancel = null;
        $this->showCancelModal = false;
    }

    public function performCancellation()
    {
        if ($this->subscriptionToCancel) {
            $this->cancelSubscription($this->subscriptionToCancel);
            $this->closeCancelModal();
        }
    }
}
