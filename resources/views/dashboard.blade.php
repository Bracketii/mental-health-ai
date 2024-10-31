<!-- resources/views/dashboard.blade.php -->

<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-neutral-900 overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Chat Interface -->
                <div class="h-auto flex flex-col">
                    <livewire:chat.coach-chat />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
