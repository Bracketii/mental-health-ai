<!-- resources/views/livewire/admin/conversation-monitor/index.blade.php -->

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-neutral-200">Conversation Monitor</h2>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <x-label for="search" value="Search by User Name or Email" />
                <x-input id="search" type="text" wire:model.debounce.300ms="search" placeholder="Search..." class="w-full" />
            </div>

            <!-- Filter by User -->
            <div>
                <x-label for="selectedUser" value="Filter by User" />
                <select id="selectedUser" wire:model="selectedUser" class="block w-full mt-1 rounded-md border-gray-300 dark:border-neutral-700 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <option value="">All Users</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Coach -->
            <div>
                <x-label for="selectedCoach" value="Filter by Coach" />
                <select id="selectedCoach" wire:model="selectedCoach" class="block w-full mt-1 rounded-md border-gray-300 dark:border-neutral-700 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <option value="">All Coaches</option>
                    @foreach ($coaches as $coach)
                        <option value="{{ $coach->type }}">{{ $coach->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Subscription -->
            <div>
                <x-label for="selectedSubscription" value="Filter by Subscription" />
                <select id="selectedSubscription" wire:model="selectedSubscription" class="block w-full mt-1 rounded-md border-gray-300 dark:border-neutral-700 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <option value="">All Subscriptions</option>
                    @foreach ($subscriptions as $subscription)
                        <option value="{{ $subscription->name }}">{{ $subscription->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date From -->
            <div>
                <x-label for="dateFrom" value="Date From" />
                <x-input id="dateFrom" type="date" wire:model="dateFrom" class="w-full" />
            </div>

            <!-- Date To -->
            <div>
                <x-label for="dateTo" value="Date To" />
                <x-input id="dateTo" type="date" wire:model="dateTo" class="w-full" />
            </div>
        </div>
    </div>

    <!-- Conversations Table -->
    <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
            <thead class="bg-gray-50 dark:bg-neutral-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('user.name')">
                        User
                        @if($sortField === 'user.name')
                            @if($sortDirection === 'asc')
                                <i class='bx bx-chevron-up inline'></i>
                            @else
                                <i class='bx bx-chevron-down inline'></i>
                            @endif
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('coach_type')">
                        Coach Type
                        @if($sortField === 'coach_type')
                            @if($sortDirection === 'asc')
                                <i class='bx bx-chevron-up inline'></i>
                            @else
                                <i class='bx bx-chevron-down inline'></i>
                            @endif
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('user_message')">
                        User Message
                        @if($sortField === 'user_message')
                            @if($sortDirection === 'asc')
                                <i class='bx bx-chevron-up inline'></i>
                            @else
                                <i class='bx bx-chevron-down inline'></i>
                            @endif
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('ai_response')">
                        AI Response
                        @if($sortField === 'ai_response')
                            @if($sortDirection === 'asc')
                                <i class='bx bx-chevron-up inline'></i>
                            @else
                                <i class='bx bx-chevron-down inline'></i>
                            @endif
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                        Date
                        @if($sortField === 'created_at')
                            @if($sortDirection === 'asc')
                                <i class='bx bx-chevron-up inline'></i>
                            @else
                                <i class='bx bx-chevron-down inline'></i>
                            @endif
                        @endif
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">
                        Subscription
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                @forelse ($conversations as $conversation)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($conversation->user->profile_photo_path)
                                    <img class="h-10 w-10 rounded-full" src="{{ Storage::url($conversation->user->profile_photo_path) }}" alt="{{ $conversation->user->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-neutral-600 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">{{ substr($conversation->user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-neutral-200">{{ $conversation->user->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-neutral-400">{{ $conversation->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-700 text-blue-800 dark:text-blue-200">
                                {{ ucfirst(str_replace('_', ' ', $conversation->coach_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-pre-wrap break-words max-w-xs">
                            {{ $conversation->user_message }}
                        </td>
                        <td class="px-6 py-4 whitespace-pre-wrap break-words max-w-xs">
                            {{ $conversation->ai_response }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $conversation->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($conversation->user->subscriptions->count() > 0)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-700 text-green-800 dark:text-green-200">
                                    {{ $conversation->user->subscriptions->first()->name }}
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    No Subscription
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-neutral-400">
                            No conversations found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $conversations->links() }}
        </div>
    </div>
</div>
