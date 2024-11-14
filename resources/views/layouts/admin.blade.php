<!-- resources/views/layouts/admin.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} | Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles

        <!-- Boxicons -->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100 dark:bg-neutral-900">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <div class="hidden lg:flex lg:flex-shrink-0">
                <div class="flex flex-col w-64 bg-white dark:bg-neutral-800 border-r border-gray-200 dark:border-neutral-700">
                    <!-- Sidebar Header -->
                    <div class="flex items-center h-16 px-4">
                        <a href="{{ route('admin.dashboard') }}" class="flex-shrink-0 text-2xl font-bold">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>
                    <!-- Sidebar Navigation -->
                    <div class="flex flex-col flex-1 overflow-y-auto">
                        <nav class="flex-1 px-2 py-4 space-y-2">
                            <ul class="space-y-2">
                                <!-- Dashboard Link -->
                                <li>
                                    <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                                        <i class='bx bx-home-alt'></i>
                                        <span class="ml-3">Dashboard</span>
                                    </x-nav-link>
                                </li>

                                <!-- Users Accordion -->
                                <li x-data="{ open: false }" class="space-y-1">
                                    <button @click="open = !open" class="w-full flex items-center p-2 text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                                        <i class='bx bx-user'></i>
                                        <span class="ml-3">Users</span>
                                        <i class='bx bx-chevron-down ml-auto' :class="{'rotate-180': open}" class="transition-transform duration-200"></i>
                                    </button>
                                    <ul x-show="open" x-transition:enter="transition ease-out duration-200" 
                                        x-transition:enter-start="opacity-0 max-h-0" 
                                        x-transition:enter-end="opacity-100 max-h-screen" 
                                        x-transition:leave="transition ease-in duration-150" 
                                        x-transition:leave-start="opacity-100 max-h-screen" 
                                        x-transition:leave-end="opacity-0 max-h-0" 
                                        class="pl-8 mt-1 space-y-1 overflow-hidden">
                                        <li>
                                            <x-nav-link href="" :active="request()->routeIs('admin.users.*')">
                                                <i class='bx bx-user-circle'></i>
                                                <span class="ml-3">All Users</span>
                                            </x-nav-link>
                                        </li>
                                        <li>
                                            <x-nav-link href="" :active="request()->routeIs('admin.users.create')">
                                                <i class='bx bx-user-plus'></i>
                                                <span class="ml-3">Add New User</span>
                                            </x-nav-link>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Projects Accordion -->
                                <li x-data="{ open: false }" class="space-y-1">
                                    <button @click="open = !open" class="w-full flex items-center p-2 text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                                        <i class='bx bx-folder'></i>
                                        <span class="ml-3">Projects</span>
                                        <i class='bx bx-chevron-down ml-auto' :class="{'rotate-180': open}" class="transition-transform duration-200"></i>
                                    </button>
                                    <ul x-show="open" x-transition:enter="transition ease-out duration-200" 
                                        x-transition:enter-start="opacity-0 max-h-0" 
                                        x-transition:enter-end="opacity-100 max-h-screen" 
                                        x-transition:leave="transition ease-in duration-150" 
                                        x-transition:leave-start="opacity-100 max-h-screen" 
                                        x-transition:leave-end="opacity-0 max-h-0" 
                                        class="pl-8 mt-1 space-y-1 overflow-hidden">
                                        <li>
                                            <x-nav-link href="" :active="request()->routeIs('admin.projects.*')">
                                                <i class='bx bx-folder-open'></i>
                                                <span class="ml-3">All Projects</span>
                                            </x-nav-link>
                                        </li>
                                        <li>
                                            <x-nav-link href="" :active="request()->routeIs('admin.projects.create')">
                                                <i class='bx bx-folder-plus'></i>
                                                <span class="ml-3">Add New Project</span>
                                            </x-nav-link>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Settings Link -->
                                <li>
                                    <x-nav-link href="" :active="request()->routeIs('admin.settings')">
                                        <i class='bx bx-cog'></i>
                                        <span class="ml-3">Settings</span>
                                    </x-nav-link>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- End Sidebar -->

            <!-- Main Content -->
            <div class="flex flex-col flex-1 w-full">
                <!-- Header -->
                <header class="sticky top-0 z-50 flex items-center justify-between px-4 py-2 bg-white border-b border-gray-200 dark:bg-neutral-800 dark:border-neutral-700">
                    <!-- Left Section -->
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button type="button" class="lg:hidden p-2 text-gray-600 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700 rounded-md focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" @click="$dispatch('toggle-sidebar')">
                            <span class="sr-only">Open sidebar</span>
                            <!-- Menu Icon -->
                            <i class='bx bx-menu'></i>
                        </button>
                        <!-- End Mobile menu button -->
                    </div>

                    <!-- Right Section -->
                    <div class="flex items-center space-x-4">
                        <!-- User Dropdown -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-gray-700 dark:text-neutral-300 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-full">
                                    <img class="w-8 h-8 rounded-full" src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . 'admin' . '&color=7F9CF5&background=EBF4FF' }}" alt="{{ auth()->user()->name ?? 'Admin' }}" />
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400 dark:text-neutral-500">
                                    Manage Account
                                </div>

                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    Profile
                                </x-dropdown-link>

                                <x-dropdown-link href="">
                                    Settings
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        Log Out
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                        <!-- End User Dropdown -->
                    </div>
                </header>
                <!-- End Header -->

                <!-- Main Content Area -->
                <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none">
                    <div class="py-6 px-4 sm:px-6 lg:px-8">
                        {{ $slot }}
                    </div>
                </main>
                <!-- End Main Content Area -->
            </div>
            <!-- End Main Content -->
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
