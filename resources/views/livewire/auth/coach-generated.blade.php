<!-- resources/views/livewire/auth/coach-generated.blade.php -->

<x-authentication-card>
    <x-slot name="logo">
        <x-authentication-card-logo />
    </x-slot>

    <div class="text-center mb-6">
        <h2 class="text-2xl font-semibold">Your Mental Health Coach is Ready!</h2>
        <p class="mt-2 text-gray-600">We've generated your personalized mental health coach based on your responses.</p>
    </div>

    <!-- Email Submission Form -->
    <form wire:submit.prevent="submitEmail">
        <div class="mb-4">
            <x-label for="email" value="Enter your email to proceed" />

            <x-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="email" required autofocus />
            
            <x-input-error for="email" class="mt-2" />
        </div>

        <div class="flex justify-center">
            <x-button>
                Submit Email
            </x-button>
        </div>
    </form>
</x-authentication-card>
