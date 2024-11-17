<!-- resources/views/components/authentication-card.blade.php -->
@props(['fullWidth' => false])

<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#EADABD]">
    <div>
        {{ $logo }}
    </div>

    <div class="{{ $fullWidth ? 'max-w-4xl w-4/5 mb-8 sm:mb-0 sm:w-full' : 'w-4/5 sm:max-w-md' }} mt-6 px-6 py-4 bg-white shadow-md rounded-lg overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
