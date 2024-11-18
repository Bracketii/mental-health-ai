<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-neutral-200">Create New User</h2>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <x-alert type="success" class="mb-4">
            {{ session('message') }}
        </x-alert>
    @endif

    <!-- User Creation Form -->
    <x-card class="max-w-7xl mx-auto p-6">
        <form wire:submit.prevent="createUser">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <x-label for="name" value="Name" />
                    <x-input id="name" wire:model.defer="name" class="mt-1 w-full" />
                    <x-input-error for="name" />
                </div>

                <!-- Email -->
                <div>
                    <x-label for="email" value="Email" />
                    <x-input id="email" type="email" wire:model.defer="email" class="mt-1 w-full" />
                    <x-input-error for="email" />
                </div>

                <!-- Password -->
                <div>
                    <x-label for="password" value="Password" />
                    <x-input id="password" type="password" wire:model.defer="password" class="mt-1 w-full" />
                    <x-input-error for="password" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-label for="password_confirmation" value="Confirm Password" />
                    <x-input id="password_confirmation" type="password" wire:model.defer="password_confirmation" class="mt-1 w-full" />
                    <x-input-error for="password_confirmation" />
                </div>

                <!-- Gender -->
                <div>
                    <x-label for="gender" value="Gender" />
                    <x-select id="gender" wire:model.defer="gender" :options="[
                        '' => 'Select Gender',
                        'male' => 'Male',
                        'female' => 'Female',
                        'non_binary' => 'Non-Binary'
                    ]" class="mt-1 w-full" />
                    <x-input-error for="gender" />
                </div>

                <!-- Role -->
                <div>
                    <x-label for="role" value="Role" />
                    <x-select id="role" wire:model.defer="role" :options="[
                        'user' => 'User',
                        'admin' => 'Admin'
                    ]" class="mt-1 w-full" />
                    <x-input-error for="role" />
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <x-button wire:click="redirectToUsersList" class="bg-gray-300 hover:bg-gray-400 text-gray-800">
                    Cancel
                </x-button>
                <x-button type="submit" class="bg-blue-600 hover:bg-blue-500">
                    <i class='bx bx-user-plus'></i>
                    Create User
                </x-button>
            </div>
        </form>
    </x-card>
</div>
