@props(['fullWidth' => false])

<button {{ $attributes->merge([
        'type' => 'submit', 
        'class' => 'inline-flex items-center justify-center px-4 py-2 bg-ap4 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-apt5 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150' . ($fullWidth ? ' w-full' : '')
    ]) }}>
    {{ $slot }}
</button>
