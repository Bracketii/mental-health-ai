@props([
    'options' => [], // An array of options [value => label]
    'placeholder' => 'Select an option', // Placeholder text
    'name' => '', // Name for the select input
    'model' => '', // Livewire model binding
])

<div class="relative">
    <select
        name="{{ $name }}"
        {{ $model ? "wire:model.defer=$model" : '' }}
        {{ $attributes->merge(['class' => 'block w-full border-gray-300 dark:border-neutral-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-neutral-200']) }}>
        @if ($placeholder)
            <option value="" disabled>{{ $placeholder }}</option>
        @endif
        @foreach ($options as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </select>
</div>
