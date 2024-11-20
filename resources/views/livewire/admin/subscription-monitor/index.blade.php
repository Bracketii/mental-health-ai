<!-- resources/views/livewire/admin/subscription-monitor/index.blade.php -->

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-neutral-200">Subscriptions Monitor</h2>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <x-label for="search" value="Search by User Name or Email" />
                <x-input id="search" type="text" wire:model.debounce.300ms="search" placeholder="Search..." class="w-full" />
            </div>

            <!-- Filter by Plan -->
            <div>
                <x-label for="selectedPlan" value="Filter by Subscription Plan" />
                <select id="selectedPlan" wire:model="selectedPlan" class="block w-full mt-1 rounded-md border-gray-300 dark:border-neutral-700 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <option value="">All Plans</option>
                    @foreach ($plans as $plan)
                        <option value="{{ $plan }}">{{ $plan }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Status -->
            <div>
                <x-label for="selectedStatus" value="Filter by Subscription Status" />
                <select id="selectedStatus" wire:model="selectedStatus" class="block w-full mt-1 rounded-md border-gray-300 dark:border-neutral-700 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <option value="">All Statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date From -->
            <div>
                <x-label for="dateFrom" value="Subscription Start Date From" />
                <x-input id="dateFrom" type="date" wire:model="dateFrom" class="w-full" />
            </div>

            <!-- Date To -->
            <div>
                <x-label for="dateTo" value="Subscription Start Date To" />
                <x-input id="dateTo" type="date" wire:model="dateTo" class="w-full" />
            </div>
        </div>
    </div>

    <!-- Subscriptions Table -->
    <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
            <thead class="bg-gray-50 dark:bg-neutral-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('users.name')">
                        User
                        @if($sortField === 'users.name')
                            @if($sortDirection === 'asc')
                                <i class='bx bx-chevron-up inline'></i>
                            @else
                                <i class='bx bx-chevron-down inline'></i>
                            @endif
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('name')">
                        Plan
                        @if($sortField === 'name')
                            @if($sortDirection === 'asc')
                                <i class='bx bx-chevron-up inline'></i>
                            @else
                                <i class='bx bx-chevron-down inline'></i>
                            @endif
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('stripe_status')">
                        Status
                        @if($sortField === 'stripe_status')
                            @if($sortDirection === 'asc')
                                <i class='bx bx-chevron-up inline'></i>
                            @else
                                <i class='bx bx-chevron-down inline'></i>
                            @endif
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                        Started At
                        @if($sortField === 'created_at')
                            @if($sortDirection === 'asc')
                                <i class='bx bx-chevron-up inline'></i>
                            @else
                                <i class='bx bx-chevron-down inline'></i>
                            @endif
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('ends_at')">
                        Trial Ends At
                        @if($sortField === 'ends_at')
                            @if($sortDirection === 'asc')
                                <i class='bx bx-chevron-up inline'></i>
                            @else
                                <i class='bx bx-chevron-down inline'></i>
                            @endif
                        @endif
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">
                        Payment Method
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                @forelse ($subscriptions as $subscription)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($subscription->user->profile_photo_path)
                                    <img class="h-10 w-10 rounded-full" src="{{ Storage::url($subscription->user->profile_photo_path) }}" alt="{{ $subscription->user->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-neutral-600 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">{{ strtoupper(substr($subscription->user->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-neutral-200">{{ $subscription->user->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-neutral-400">{{ $subscription->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-700 text-blue-800 dark:text-blue-200">
                                {{ $subscription->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $status = $subscription->stripe_status;
                            @endphp
                            @if($status === 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-700 text-green-800 dark:text-green-200">
                                    Active
                                </span>
                            @elseif($status === 'past_due')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-700 text-yellow-800 dark:text-yellow-200">
                                    Past Due
                                </span>
                            @elseif($status === 'canceled')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-700 text-red-800 dark:text-red-200">
                                    Canceled
                                </span>
                            @elseif($status === 'incomplete' || $status === 'incomplete_expired')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    Incomplete
                                </span>
                            @elseif($status === 'trialing')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 dark:bg-indigo-700 text-indigo-800 dark:text-indigo-200">
                                    Trialing
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $subscription->created_at }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($subscription->ends_at)
                                {{ $subscription->ends_at }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($subscription->stripe_payment_method)
                                <div class="flex items-center justify-center space-x-2">
                                    <img src="https://img.icons8.com/color/24/000000/{{ strtolower($subscription->stripe_payment_method->card->brand) }}.png" alt="{{ $subscription->stripe_payment_method->card->brand }}" class="h-5 w-5">
                                    <span class="text-sm">{{ strtoupper($subscription->stripe_payment_method->card->brand) }} •••• {{ $subscription->stripe_payment_method->card->last4 }}</span>
                                </div>
                            @else
                                <span class="text-sm text-gray-500 dark:text-neutral-400">No Payment Method</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if(in_array($subscription->stripe_status, ['active', 'trialing', 'past_due']))
                                <x-button wire:click="confirmCancellation({{ $subscription->id }})" class="bg-red-600 hover:bg-red-500 text-white text-xs px-2 py-1 rounded">
                                    <i class='bx bx-x'></i> Cancel
                                </x-button>
                            @else
                                <span class="text-sm text-gray-500 dark:text-neutral-400">N/A</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-neutral-400">
                            No subscriptions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $subscriptions->links() }}
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="fixed bottom-4 right-4 bg-green-100 dark:bg-green-700 text-green-800 dark:text-green-200 px-4 py-2 rounded shadow">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-100 dark:bg-red-700 text-red-800 dark:text-red-200 px-4 py-2 rounded shadow">
            {{ session('error') }}
        </div>
    @endif

    <!-- Confirmation Modal for Cancellation -->
    @if ($showCancelModal && $subscriptionToCancel)
        <x-modal wire:model="showCancelModal" id="cancel-confirmation-modal" maxWidth="md">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-700 dark:text-neutral-200 mb-4">Confirm Cancellation</h3>
                <p class="text-gray-600 dark:text-neutral-400">
                    Are you sure you want to cancel this subscription? This action cannot be undone.
                </p>
                <div class="flex justify-end mt-6 space-x-2">
                    <x-button wire:click="closeCancelModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800">
                        Cancel
                    </x-button>
                    <x-button wire:click="performCancellation" class="bg-red-600 hover:bg-red-500 text-white">
                        Confirm Cancel
                    </x-button>
                </div>
            </div>
        </x-modal>
    @endif
</div>
