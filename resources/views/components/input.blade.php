<!-- resources/views/components/input.blade.php -->

@props(['type' => 'text'])

<input type="{{ $type }}" {{ $attributes->merge(['class' => 'block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white']) }}>
