<!-- resources/views/livewire/admin/coach-management/index.blade.php -->

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-neutral-200">Manage Coaches</h2>
        <x-button wire:click="openAddModal" class="bg-blue-600 hover:bg-blue-500">
            <i class='bx bx-plus'></i> Add New Coach
        </x-button>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <x-alert type="success" class="mb-4">
            {{ session('message') }}
        </x-alert>
    @endif

    <!-- Coaches Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($coaches as $coach)
            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-4 flex flex-col">
                <!-- Coach Avatar -->
                @if($coach->avatar)
                    <img src="{{ asset('storage/' . $coach->avatar) }}" alt="{{ $coach->name }}" class="w-24 h-24 rounded-full mb-4 object-cover mx-auto">
                @else
                    <div class="w-24 h-24 rounded-full bg-gray-300 dark:bg-neutral-600 flex items-center justify-center mb-4 mx-auto">
                        <span class="text-gray-700 dark:text-neutral-200 text-2xl font-semibold">{{ strtoupper(substr($coach->name, 0, 1)) }}</span>
                    </div>
                @endif

                <!-- Coach Details -->
                <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200 text-center">{{ $coach->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-neutral-400 mb-2 text-center">{{ $coach->designation }}</p>
                <p class="text-sm text-gray-600 dark:text-neutral-300 mb-4 text-center">{{ Str::limit($coach->bio, 100) }}</p> <!-- Displaying limited bio -->

                <!-- Action Buttons -->
                <div class="flex justify-center space-x-2 mt-auto">
                    <x-button wire:click="editCoach({{ $coach->id }})" class="bg-yellow-600 hover:bg-yellow-500 !p-2">
                        <i class='bx bx-edit'></i>
                    </x-button>
                    <x-button wire:click="confirmCoachDeletion({{ $coach->id }})" class="bg-red-600 hover:bg-red-500 !p-2">
                        <i class='bx bx-trash'></i>
                    </x-button>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center text-gray-500 dark:text-neutral-400">No coaches found.</div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $coaches->links() }}
    </div>

    <!-- Add/Edit Modal -->
    @if ($showModal)
        <x-modal wire:model="showModal" id="coach-modal" maxWidth="lg">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-700 dark:text-neutral-200">{{ $editing ? 'Edit' : 'Add' }} Coach</h3>
                <form wire:submit.prevent="{{ $editing ? 'updateCoach' : 'addCoach' }}">
                    <div class="space-y-4">
                        <!-- Name Input -->
                        <x-input-group>
                            <x-label for="name" value="Name" />
                            <x-input id="name" wire:model.defer="name" class="w-full" />
                            <x-input-error for="name" />
                        </x-input-group>

                        <!-- Designation Input -->
                        <x-input-group>
                            <x-label for="designation" value="Designation" />
                            <x-input id="designation" wire:model.defer="designation" class="w-full" />
                            <x-input-error for="designation" />
                        </x-input-group>

                        <!-- Bio Input -->
                        <x-input-group>
                            <x-label for="bio" value="Biography" />
                            <textarea id="bio" wire:model.defer="bio" class="block w-full border-gray-300 dark:border-neutral-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white" rows="4" placeholder="Enter a short biography or description..."></textarea>
                            <x-input-error for="bio" />
                        </x-input-group>

                        <!-- Avatar Upload -->
                        <x-input-group>
                            <x-label for="avatar" value="Avatar" />
                            <input type="file" wire:model.defer="avatar" id="avatar" class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100
                            ">
                            <x-input-error for="avatar" />
                            <div class="mt-2">
                                @if ($avatar)
                                    <img src="{{ $avatar->temporaryUrl() }}" alt="Preview" class="w-16 h-16 rounded-full object-cover">
                                @elseif($editing && $coachToEdit->avatar)
                                    <img src="{{ asset('storage/' . $coachToEdit->avatar) }}" alt="Current Avatar" class="w-16 h-16 rounded-full object-cover">
                                @endif
                            </div>
                        </x-input-group>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <x-button wire:click="resetForm" type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800">
                            Cancel
                        </x-button>
                        <x-button type="submit" class="bg-blue-600 hover:bg-blue-500">
                            {{ $editing ? 'Update' : 'Add' }} Coach
                        </x-button>
                    </div>
                </form>
            </div>
        </x-modal>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($confirmingCoachDeletion)
        <x-modal wire:model="confirmingCoachDeletion" id="delete-coach-modal" maxWidth="md">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-700 dark:text-neutral-200 mb-4">Confirm Deletion</h3>
                <p class="text-gray-600 dark:text-neutral-400">
                    Are you sure you want to delete the coach: <strong>{{ $coachToDelete->name }}</strong>? This action cannot be undone.
                </p>
                <div class="flex justify-end mt-6 space-x-2">
                    <x-button wire:click="$set('confirmingCoachDeletion', false)" class="bg-gray-300 hover:bg-gray-400 text-gray-800">
                        Cancel
                    </x-button>
                    <x-button wire:click="deleteCoach" class="bg-red-600 hover:bg-red-500 text-white">
                        Delete
                    </x-button>
                </div>
            </div>
        </x-modal>
    @endif
</div>
