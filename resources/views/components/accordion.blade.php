<!-- resources/views/components/accordion.blade.php -->

@props(['trigger'])

@php
    // Generate a unique ID for the accordion
    $id = Str::uuid();
@endphp

<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between p-2 text-left text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700 rounded-lg focus:outline-none focus:bg-gray-100 dark:focus:bg-neutral-700">
        {{ $trigger }}
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.584l3.71-3.353a.75.75 0 111.04 1.084l-4 3.6a.75.75 0 01-1.04 0l-4-3.6a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
        </svg>
        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 9.416l-3.71 3.353a.75.75 0 11-1.04-1.084l4-3.6a.75.75 0 011.04 0l4 3.6a.75.75 0 01-.02 1.06z" clip-rule="evenodd" />
        </svg>
    </button>

    <div x-show="open" class="pl-4">
        {{ $content }}
    </div>
</div>
