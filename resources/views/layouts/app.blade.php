<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased [&::-webkit-scrollbar]:w-2
    [&::-webkit-scrollbar-track]:bg-ap1
    [&::-webkit-scrollbar-thumb]:bg-ap2
    dark:[&::-webkit-scrollbar-track]:bg-slate-700
    dark:[&::-webkit-scrollbar-thumb]:bg-slate-500">
        <x-banner />

        <div class="min-h-screen bg-ap1">
            @livewire('navigation-menu')
            <!-- Page Content -->
            <main class=" mx-5 sm:mx-0">
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
