<!-- resources/views/livewire/checkout.blade.php -->

<x-authentication-card :fullWidth="true">
    <x-slot name="logo">
        <x-authentication-card-logo />
    </x-slot>

    <div class="text-center mb-6">
        <h2 class="text-2xl font-semibold">Select Your Plan</h2>
        <p class="mt-2 text-gray-600">Choose a subscription plan that suits you best.</p>
    </div>

    <div class="space-y-6">
        @foreach($plans as $key => $plan)
            <div class="border rounded-lg p-4 flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                <div class="text-center md:text-left">
                    <h3 class="text-xl font-semibold">{{ $plan['name'] }}</h3>
                    <p class="text-gray-600">{{ $plan['description'] }}</p>
                    <p class="text-green-600 font-semibold">{{ $plan['discount'] }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500 line-through">(${{ number_format($plan['original_daily_price'], 2) }})</p>
                    <p class="text-3xl font-bold">${{ number_format($plan['daily_price'], 2) }}</p>
                    <p class="text-sm text-gray-500">per day</p>
                </div>
                <div class="text-center md:text-right">
                    <x-button wire:click="selectPlan('{{ $key }}')" class="bg-blue-600 hover:bg-blue-700">
                        CLAIM MY PLAN
                    </x-button>
                </div>
            </div>
        @endforeach
    </div>
</x-authentication-card>
