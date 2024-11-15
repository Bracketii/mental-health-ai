<!-- resources/views/welcome.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta and Title -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Run On Empathy') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400&family=Satisfy&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <!-- Remove overflow hidden to allow scrolling -->
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: auto; /* Allow scrolling */
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 flex flex-col min-h-screen overflow-y-auto
    [&::-webkit-scrollbar]:w-1
    [&::-webkit-scrollbar-track]:bg-gray-900
    [&::-webkit-scrollbar-thumb]:bg-gray-600">

    <!-- Main Container -->
    <div class="flex flex-col flex-grow">
        <!-- Hero Section -->
        <div class="flex flex-col lg:flex-row flex-grow lg:h-[680px]">
            <!-- Left Column -->
            <div class="lg:w-1/2 bg-[#1F0052] text-white flex flex-col p-8">
                <!-- Content Container -->
                <div class="container-sm mx-auto flex flex-col pr-5">
                    <!-- Logo -->
                    <h1 class="text-4xl font-normal text-white mb-6">run on empathy</h1>

                    <!-- Main Content -->
                    <div class="mt-24 pr-44">
                        <!-- Heading -->
                        <h2 class="text-5xl font-semibold mb-4 leading-snug">
                            Create your own Mental Health<br>
                            <span class="bg-gradient-to-r from-indigo-400 to-white bg-clip-text text-transparent">AI Coach</span>
                        </h2>

                        <!-- Description -->
                        <p class="mb-6">
                            Run On Empathy empowers you to build a personalized mental health coach tailored to your unique needs. Begin your journey towards better mental well-being with our intuitive platform.
                        </p>

                        <!-- Selection Prompt -->
                        <p class="mb-4">Start by selecting how you identify:</p>

                        <!-- Gender Selection Buttons -->
                        @livewire('gender-selection-buttons')
                    </div>
                </div>
            </div>

            <!-- Right Column with Overlay -->
            <div class="w-full lg:w-1/2 relative">
                <!-- Image with overlay -->
                <div class="relative w-full h-full">
                    <img src="{{ asset('images/home-bg.png') }}" alt="Mental Health Illustration" class="w-full h-full object-cover">
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 opacity-70"></div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white py-4 shadow-inner container mx-auto">
            <div class="px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between">
                <!-- App Name and Rights -->
                <div class="text-gray-600 text-center md:text-left">
                    <span class="font-semibold">Run On Empathy</span> &copy; {{ date('Y') }} All rights reserved.
                </div>

                <!-- Navigation Links -->
                <div class="flex flex-wrap justify-center md:justify-end mt-4 md:mt-0 space-x-6">
                    <a href="/login" wire:navigate class="text-gray-600 hover:text-gray-800">Login</a>
                    <a href="#" class="text-gray-600 hover:text-gray-800">Terms</a>
                    <a href="#" class="text-gray-600 hover:text-gray-800">Privacy</a>
                    <a href="#" class="text-gray-600 hover:text-gray-800">Contact</a>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>
</html>
