<!-- resources/views/livewire/checkout.blade.php -->

<x-authentication-card>
    <x-slot name="logo">
        <x-authentication-card-logo />
    </x-slot>

    <div class="text-center mb-6">
        <h2 class="text-2xl font-semibold">Select Your Plan</h2>
        <p class="mt-2 text-gray-600">Choose a subscription plan that suits you best.</p>
    </div>

    <div class="space-y-6">
        @foreach($plans as $key => $plan)
            <div class="border rounded-lg p-4 flex flex-col md:flex-row items-center justify-between">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-xl font-semibold">{{ $plan['name'] }}</h3>
                    <p class="text-gray-600">{{ $plan['description'] }}</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold">${{ number_format($plan['price'], 2) }}</p>
                    <p class="text-sm text-gray-500">{{ $plan['discount'] }}</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <x-button wire:click="selectPlan('{{ $key }}')" class="bg-blue-600 hover:bg-blue-700">
                        CLAIM MY PLAN
                    </x-button>
                </div>
            </div>
        @endforeach
    </div>
</x-authentication-card>
