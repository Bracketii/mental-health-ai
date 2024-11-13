<!-- resources/views/livewire/auth/password-setup.blade.php -->

<x-authentication-card>
    <x-slot name="logo">
        <x-authentication-card-logo />
    </x-slot>

    <div class="text-center mb-6">
        <h2 class="text-2xl font-semibold">Set Up Your Password</h2>
        <p class="mt-2 text-gray-600">Create a password to secure your account.</p>
    </div>

    <form wire:submit.prevent="submitPassword">
        <div class="mb-4">
            <x-label for="password" value="Password" />

            <x-input id="password" type="password" class="mt-1 block w-full" wire:model.defer="password" required autocomplete="new-password" />
            
            <x-input-error for="password" class="mt-2" />
        </div>

        <div class="mb-6">
            <x-label for="password_confirmation" value="Confirm Password" />

            <x-input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model.defer="password_confirmation" required autocomplete="new-password" />
            
            <x-input-error for="password_confirmation" class="mt-2" />
        </div>

        <div class="flex justify-center">
            <x-button>
                Create Account
            </x-button>
        </div>
    </form>
</x-authentication-card>
