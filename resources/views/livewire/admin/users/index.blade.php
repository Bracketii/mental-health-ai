<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-neutral-200">Users Management</h2>
        <x-button>
            <a href="{{ route('admin.users.create') }}">
                Add New User</a>
        </x-button>
    </div>

    <!-- Success and Error Messages -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Users Table -->
    <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-0">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">Gender</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">Subscriptions</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $user->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-200">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-200">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-200">{{ ucfirst($user->gender ?? 'N/A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-200">{{ ucfirst($user->role) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-200">
                                @if ($user->subscriptions->isNotEmpty())
                                    @foreach ($user->subscriptions as $subscription)
                                        <span class="inline-block bg-green-200 dark:bg-green-700 text-green-800 dark:text-green-100 text-xs px-2 py-1 rounded-full">{{ $subscription->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-gray-500 dark:text-neutral-400">No Subscriptions</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                @if (auth()->user()->id !== $user->id)
                                    <x-button wire:click="confirmUserDeletion({{ $user->id }})" class="bg-red-600 hover:bg-red-500 text-xs">
                                        Delete
                                    </x-button>
                                @else
                                    <span class="text-gray-500 dark:text-neutral-400">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-neutral-400">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if ($confirmingUserDeletion)
    <x-modal wire:model="confirmingUserDeletion" id="delete-user-modal" maxWidth="md">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-700 dark:text-neutral-200 mb-4">Confirm Deletion</h3>
            <p class="text-gray-600 dark:text-neutral-400">
                Are you sure you want to delete <strong>{{ $userToDelete->name }}</strong>? This action cannot be undone.
            </p>
            <div class="flex justify-end mt-6 space-x-2">
                <x-button wire:click="$set('confirmingUserDeletion', false)" class="bg-gray-300 hover:bg-gray-400 text-gray-800">
                    Cancel
                </x-button>
                <x-button wire:click="deleteUser" class="bg-red-600 hover:bg-red-500 text-white">
                    Delete
                </x-button>
            </div>
        </div>
    </x-modal>
    
    @endif
</div>
