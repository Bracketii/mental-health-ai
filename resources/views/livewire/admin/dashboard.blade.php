<!-- resources/views/livewire/admin/dashboard.blade.php -->

<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Users Card -->
        <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-6 flex items-center space-x-4">
            <div class="p-3 bg-blue-100 rounded-full dark:bg-blue-700">
                <i class='bx bx-user text-blue-500 dark:text-white text-3xl'></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">Total Users</h3>
                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</p>
            </div>
        </div>

        <!-- Total Active Sessions Card -->
        <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-6 flex items-center space-x-4">
            <div class="p-3 bg-green-100 rounded-full dark:bg-green-700">
                <i class='bx bx-time-five text-green-500 dark:text-white text-3xl'></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">Active Sessions</h3>
                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalActiveSessions) }}</p>
            </div>
        </div>

        <!-- Total Subscriptions Card -->
        <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-6 flex items-center space-x-4">
            <div class="p-3 bg-yellow-100 rounded-full dark:bg-yellow-700">
                <i class='bx bx-credit-card text-yellow-500 dark:text-white text-3xl'></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">Total Subscriptions</h3>
                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalSubscriptions) }}</p>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-6 flex items-center space-x-4">
            <div class="p-3 bg-purple-100 rounded-full dark:bg-purple-700">
                <i class='bx bx-dollar text-purple-500 dark:text-white text-3xl'></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">Total Revenue</h3>
                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($totalRevenue, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">Recent Activities</h3>
            <x-button class="bg-blue-600 hover:bg-blue-500">View All</x-button>
        </div>
        <ul class="space-y-3">
            @forelse($recentActivities as $activity)
                <li class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class='bx bx-check-circle text-green-500 text-xl'></i>
                    </div>
                    <div>
                        <p class="text-gray-700 dark:text-neutral-200">{{ $activity }}</p>
                    </div>
                </li>
            @empty
                <li class="text-gray-500 dark:text-neutral-400">No recent activities.</li>
            @endforelse
        </ul>
    </div>

    <!-- Recent Q&A -->
    <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">Recent Questions & Answers</h3>
            <x-button class="bg-blue-600 hover:bg-blue-500">View All</x-button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">
                            User
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">
                            Question
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">
                            Date
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($recentQnA as $qna)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $qna['user'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-200">
                                {{ $qna['question'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-neutral-400">
                                {{ \Carbon\Carbon::parse($qna['date'])->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-neutral-400 text-center">
                                No recent Q&A.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- User Feedback -->
    <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">User Feedback</h3>
            <x-button class="bg-blue-600 hover:bg-blue-500">View All</x-button>
        </div>
        <ul class="space-y-3">
            @forelse($recentFeedback as $feedback)
                <li class="bg-gray-50 dark:bg-neutral-700 p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($feedback['user']) }}&color=7F9CF5&background=EBF4FF" alt="{{ $feedback['user'] }}">
                            <span class="text-sm font-medium text-gray-700 dark:text-neutral-200">{{ $feedback['user'] }}</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            @for ($i = 0; $i < $feedback['rating']; $i++)
                                <i class='bx bxs-star text-yellow-400'></i>
                            @endfor
                            @for ($i = 0; $i < 5 - $feedback['rating']; $i++)
                                <i class='bx bx-star text-gray-300'></i>
                            @endfor
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-600 dark:text-neutral-300">{{ $feedback['feedback'] }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">{{ \Carbon\Carbon::parse($feedback['date'])->format('M d, Y') }}</p>
                </li>
            @empty
                <li class="text-gray-500 dark:text-neutral-400">No user feedback available.</li>
            @endforelse
        </ul>
    </div>
</div>
