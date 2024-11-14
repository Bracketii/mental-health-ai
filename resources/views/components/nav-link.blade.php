<!-- resources/views/components/nav-link.blade.php -->

@props(['href', 'active' => false])

@php
    $classes = ($active)
                ? 'flex items-center p-2 text-base text-blue-600 bg-blue-50 rounded-lg dark:bg-neutral-700 dark:text-blue-400'
                : 'flex items-center p-2 text-base text-gray-700  hover:bg-gray-50 rounded-lg dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-blue-400';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
