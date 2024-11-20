<!-- resources/views/livewire/admin/system-message-management/index.blade.php -->

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-neutral-200">Manage System Messages</h2>
        {{-- <x-button wire:click="openAddModal" class="bg-blue-600 hover:bg-blue-500">
            <i class='bx bx-plus'></i> Add New Message
        </x-button> --}}
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <x-alert type="success" class="mb-4">
            {{ session('message') }}
        </x-alert>
    @endif

    <!-- System Messages Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($systemMessages as $message)
            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-4 flex flex-col">
                <!-- Message Type -->
                <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">{{ ucfirst($message->type) }}</h3>
                <!-- Message Content -->
                <p class="text-sm text-gray-600 dark:text-neutral-300 mt-2">{{ $message->content }}</p>
                <!-- Action Buttons -->
                <div class="flex space-x-2 mt-4">
                    <x-button wire:click="editMessage({{ $message->id }})" class="bg-yellow-600 hover:bg-yellow-500 !p-2">
                        <i class='bx bx-edit'></i>
                    </x-button>
                    <x-button wire:click="confirmMessageDeletion({{ $message->id }})" class="bg-red-600 hover:bg-red-500 !p-2">
                        <i class='bx bx-trash'></i>
                    </x-button>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-500 dark:text-neutral-400">No system messages found.</div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $systemMessages->links() }}
    </div>

    <!-- Add/Edit Modal -->
    @if ($showModal)
        <x-modal wire:model="showModal" id="system-message-modal" maxWidth="lg">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-700 dark:text-neutral-200">{{ $editing ? 'Edit' : 'Add' }} System Message</h3>
                <form wire:submit.prevent="{{ $editing ? 'updateMessage' : 'addMessage' }}">
                    <div class="space-y-4">
                        <!-- Type Input -->
                        <x-input-group>
                            <x-label for="type" value="Type" />
                            <x-input id="type" wire:model.defer="type" class="w-full" placeholder="e.g., welcome, prompt" />
                            <x-input-error for="type" />
                        </x-input-group>

                        <!-- Content Input -->
                        <x-input-group>
                            <x-label for="content" value="Content" />
                            <textarea id="content" wire:model.defer="content" class="block w-full border-gray-300 dark:border-neutral-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white" rows="4" placeholder="Enter the message content..."></textarea>
                            <x-input-error for="content" />
                        </x-input-group>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <x-button wire:click="resetForm" type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800">
                            Cancel
                        </x-button>
                        <x-button type="submit" class="bg-blue-600 hover:bg-blue-500">
                            {{ $editing ? 'Update' : 'Add' }} Message
                        </x-button>
                    </div>
                </form>
            </div>
        </x-modal>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($confirmingMessageDeletion)
        <x-modal wire:model="confirmingMessageDeletion" id="delete-message-modal" maxWidth="md">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-700 dark:text-neutral-200 mb-4">Confirm Deletion</h3>
                <p class="text-gray-600 dark:text-neutral-400">
                    Are you sure you want to delete the system message: <strong>{{ ucfirst($messageToDelete->type) }}</strong>? This action cannot be undone.
                </p>
                <div class="flex justify-end mt-6 space-x-2">
                    <x-button wire:click="$set('confirmingMessageDeletion', false)" class="bg-gray-300 hover:bg-gray-400 text-gray-800">
                        Cancel
                    </x-button>
                    <x-button wire:click="deleteMessage" class="bg-red-600 hover:bg-red-500 text-white">
                        Delete
                    </x-button>
                </div>
            </div>
        </x-modal>
    @endif
</div>
