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
    <body class="font-sans text-gray-900 antialiased bg-gray-100 dark:bg-neutral-900" x-data="{ sidebarOpen: false }">
        <div class="flex h-screen">
            <!-- Mobile Sidebar -->
            <div x-cloak x-show="sidebarOpen" class="fixed inset-0 flex z-40 lg:hidden" role="dialog" aria-modal="true">
                <!-- Overlay -->
                <div x-show="sidebarOpen" class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false" aria-hidden="true"></div>

                <!-- Sidebar -->
                <div x-show="sidebarOpen" class="relative flex-1 flex flex-col max-w-xs w-full bg-white dark:bg-neutral-800">
                    <!-- Close button -->
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <span class="sr-only">Close sidebar</span>
                            <i class='bx bx-x text-white text-2xl'></i>
                        </button>
                    </div>

                    <!-- Sidebar Header -->
                    <div class="flex items-center h-16 px-4">
                        <a href="{{ route('admin.dashboard') }}" class="flex-shrink-0 text-2xl font-bold">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>

                    <!-- Sidebar Navigation -->
                    <div class="flex flex-col flex-1 overflow-y-auto">
                        <nav class="flex-1 px-2 py-4 space-y-2">
                            <ul class="space-y-4">
                                <!-- Dashboard Link -->
                                <li>
                                    <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                                        <i class='bx bx-home-alt'></i>
                                        <span class="ml-3">Dashboard</span>
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link href="{{ route('admin.conversation-monitor') }}" :active="request()->routeIs('admin.conversation-monitor')">
                                        <i class='bx bx-home-alt'></i>
                                        <span class="ml-3">Chat Monitor</span>
                                    </x-nav-link>
                                </li>

                                <!-- Users Accordion -->
                                <li x-data="{ open: false }" class="space-y-1">
                                    <button @click="open = !open" class="w-full flex items-center p-2 text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                                        <i class='bx bx-user'></i>
                                        <span class="ml-3">Users</span>
                                        <i :class="{'rotate-180': open}" class='bx bx-chevron-down ml-auto transition-transform duration-200'></i>
                                    </button>
                                    <ul x-show="open" x-transition class="pl-2 mt-1 space-y-3 overflow-hidden">
                                        <li>
                                            <x-nav-link href="{{ url('/admin/users') }}" :active="request()->routeIs('admin.users.*')">
                                                <i class='bx bx-user-circle'></i>
                                                <span class="ml-3">All Users</span>
                                            </x-nav-link>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Questions Accordion -->
                                <li x-data="{ open: false }" class="space-y-1">
                                    <button @click="open = !open" class="w-full flex items-center p-2 text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                                        <i class='bx bx-folder'></i>
                                        <span class="ml-3">Questionnaires</span>
                                        <i :class="{'rotate-180': open}" class='bx bx-chevron-down ml-auto transition-transform duration-200'></i>
                                    </button>
                                    <ul x-show="open" x-transition class="pl-2 mt-1 space-y-3 overflow-hidden">
                                        <li>
                                            <x-nav-link href="{{ url('/admin/questionnaires') }}" :active="request()->routeIs('admin.questionnaires.*')">
                                                <i class='bx bx-folder-open'></i>
                                                <span class="ml-3">All Questions</span>
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

                <!-- Dummy element to force sidebar to shrink to fit close icon -->
                <div class="flex-shrink-0 w-14" aria-hidden="true"></div>
            </div>
            <!-- End Mobile Sidebar -->

            <!-- Desktop Sidebar -->
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
                            <ul class="space-y-4">
                                <!-- Dashboard Link -->
                                <li>
                                    <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                                        <i class='bx bx-home-alt'></i>
                                        <span class="ml-3">Dashboard</span>
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link href="{{ route('admin.conversation-monitor') }}" :active="request()->routeIs('admin.conversation-monitor')">
                                        <i class='bx bx-message-square-dots'></i>
                                        <span class="ml-3">Chat Monitor</span>
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link href="{{ route('admin.subscriptions-monitor') }}" :active="request()->routeIs('admin.subscriptions-monitor')">
                                        <i class='bx bx-wallet' ></i>
                                        <span class="ml-3">Subscriptions</span>
                                    </x-nav-link>
                                </li>

                                <!-- Users Accordion -->
                                <li x-data="{ open: false }" class="space-y-1">
                                    <button @click="open = !open" class="w-full flex items-center p-2 text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                                        <i class='bx bx-user'></i>
                                        <span class="ml-3">Users</span>
                                        <i :class="{'rotate-180': open}" class='bx bx-chevron-down ml-auto transition-transform duration-200'></i>
                                    </button>
                                    <ul x-show="open" x-transition class="pl-2 mt-1 space-y-3 overflow-hidden">
                                        <li>
                                            <x-nav-link href="{{ url('/admin/users') }}" :active="request()->routeIs('admin.users.*')">
                                                <i class='bx bx-user-circle'></i>
                                                <span class="ml-3">All Users</span>
                                            </x-nav-link>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Questions Accordion -->
                                <li x-data="{ open: false }" class="space-y-1">
                                    <button @click="open = !open" class="w-full flex items-center p-2 text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                                        <i class='bx bx-folder'></i>
                                        <span class="ml-3">Questionnaires</span>
                                        <i :class="{'rotate-180': open}" class='bx bx-chevron-down ml-auto transition-transform duration-200'></i>
                                    </button>
                                    <ul x-show="open" x-transition class="pl-2 mt-1 space-y-3 overflow-hidden">
                                        <li>
                                            <x-nav-link href="{{ url('/admin/questionnaires') }}" :active="request()->routeIs('admin.questionnaires.*')">
                                                <i class='bx bx-folder-open'></i>
                                                <span class="ml-3">All Questions</span>
                                            </x-nav-link>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Dashboard Link -->
                                <li>
                                    <x-nav-link href="{{ route('admin.coaches.index') }}" :active="request()->routeIs('admin.coaches.index')">
                                        <i class='bx bx-heart'></i>
                                        <span class="ml-3">Coaches</span>
                                    </x-nav-link>
                                </li>

                                <!-- Settings Accordion -->
                                <li x-data="{ open: false }" class="space-y-1">
                                    <button @click="open = !open" class="w-full flex items-center p-2 text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                                        <i class='bx bx-cog'></i>
                                        <span class="ml-3">Settings</span>
                                        <i :class="{'rotate-180': open}" class='bx bx-chevron-down ml-auto transition-transform duration-200'></i>
                                    </button>
                                    <ul x-show="open" x-transition class="pl-2 mt-1 space-y-3 overflow-hidden">
                                        <li>
                                            <x-nav-link href="{{ route('admin.system-messages') }}" :active="request()->routeIs('admin.system-messages.*')">
                                                <i class='bx bxs-circle' style="font-size: 7px"></i>
                                                <span class="ml-3">System Messages</span>
                                            </x-nav-link>
                                        </li>
                                        <li>
                                            <x-nav-link href="{{ route('admin.gpt-management') }}" :active="request()->routeIs('admin.gpt-management.*')">
                                                <i class='bx bxs-circle' style="font-size: 7px"></i>
                                                <span class="ml-3">GPT Engine</span>
                                            </x-nav-link>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- End Desktop Sidebar -->

            <!-- Main Content -->
            <div class="flex flex-col flex-1 w-full">
                <!-- Header -->
                <header class="sticky top-0 z-50 flex items-center justify-between px-4 py-2 bg-white border-b border-gray-200 dark:bg-neutral-800 dark:border-neutral-700">
                    <!-- Left Section -->
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button type="button" class="lg:hidden p-2 text-gray-600 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700 rounded-md focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" @click="sidebarOpen = true">
                            <span class="sr-only">Open sidebar</span>
                            <!-- Menu Icon -->
                            <i class='bx bx-menu'></i>
                        </button>
                    </div>

                    <!-- Right Section -->
                    <div class="flex items-center space-x-4">
                        <!-- User Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 dark:text-neutral-300 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-full">
                                <img class="w-8 h-8 rounded-full" src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . 'Admin' . '&color=7F9CF5&background=EBF4FF' }}" alt="{{ auth()->user()->name ?? 'Admin' }}" />
                            </button>

                            <!-- Dropdown menu -->
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-md shadow-lg py-1 z-50">
                                <!-- Account Management -->
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
                            </div>
                        </div>
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
        <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>
    </body>
</html>
