@props(['type' => 'info', 'dismissible' => false])

@php
$alertClasses = [
    'success' => 'bg-green-100 text-green-700 border-green-400',
    'error' => 'bg-red-100 text-red-700 border-red-400',
    'warning' => 'bg-yellow-100 text-yellow-700 border-yellow-400',
    'info' => 'bg-blue-100 text-blue-700 border-blue-400',
][$type];
@endphp

<div {{ $attributes->merge(['class' => "$alertClasses border px-4 py-3 rounded relative"]) }}>
    <strong class="font-bold">
        @if ($type === 'success') Success! @elseif ($type === 'error') Error! @elseif ($type === 'warning') Warning! @else Info! @endif
    </strong>
    <span class="block sm:inline">{{ $slot }}</span>
    
    @if ($dismissible)
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3 focus:outline-none" x-on:click="$el.parentElement.remove()">
            <svg class="fill-current h-6 w-6 text-gray-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M14.348 14.849a1 1 0 01-1.414 0L10 11.914l-2.933 2.935a1 1 0 11-1.414-1.414L8.086 10 5.151 7.066a1 1 0 111.414-1.414L10 8.086l2.935-2.934a1 1 0 111.414 1.414L11.914 10l2.934 2.935a1 1 0 010 1.414z"/>
            </svg>
        </button>
    @endif
</div>
