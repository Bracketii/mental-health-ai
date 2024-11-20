<!-- resources/views/livewire/admin/context-management/index.blade.php -->

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-neutral-200 w-1/2">
            Mental Health Core Engine<br>
            <span class="text-sm text-gray-500 italic">
                Please handle this data with care, as it is integral to the Retrieval-Augmented Generation (RAG) architecture, which forms the core of the chat system.
            </span>
        </h2>
        <div class="flex space-x-2">
            <x-button wire:click="openEditModal" class="bg-blue-600 hover:bg-blue-500">
                <i class='bx bx-edit'></i> Edit Engine
            </x-button>
            <x-button wire:click="confirmReset" class="bg-red-600 hover:bg-red-500">
                <i class='bx bx-reset'></i> Reset to Default
            </x-button>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <x-alert type="success" class="mb-4">
            {{ session('message') }}
        </x-alert>
    @endif

    <!-- Error Message -->
    @if (session()->has('error'))
        <x-alert type="error" class="mb-4">
            {{ session('error') }}
        </x-alert>
    @endif

    <!-- Context Display -->
    <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200 mb-4">Current Mental Health Context</h3>
        <p class="text-wrap break-words text-gray-600 dark:text-neutral-300 w-auto">@markdown($contextContent)</p>
    </div>

    <!-- Edit Modal -->
    @if ($showModal)
        <x-modal wire:model="showModal" id="edit-context-modal" maxWidth="3xl">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-700 dark:text-neutral-200 mb-4">Edit Mental Health Context</h3>
                <form wire:submit.prevent="saveContext">
                    <div class="space-y-4">
                        <x-input-group>
                            <x-label for="contextContent" value="Context Content" />
                            <textarea id="contextContent" wire:model.defer="contextContent" class="block w-full border-gray-300 dark:border-neutral-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:text-white" rows="15" placeholder="Enter the mental health context here..."></textarea>
                            <x-input-error for="contextContent" />
                        </x-input-group>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <x-button wire:click="$set('showModal', false)" type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800">
                            Cancel
                        </x-button>
                        <x-button type="submit" class="bg-blue-600 hover:bg-blue-500">
                            Save Changes
                        </x-button>
                    </div>
                </form>
            </div>
        </x-modal>
    @endif

    <!-- Reset Confirmation Modal -->
    @if ($confirmingReset)
        <x-modal wire:model="confirmingReset" id="reset-confirmation-modal" maxWidth="md">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-700 dark:text-neutral-200 mb-4">Confirm Reset</h3>
                <p class="text-gray-600 dark:text-neutral-400">
                    Are you sure you want to reset the mental health context to its default content? This will overwrite any existing changes and regenerate the vector store.
                </p>
                <div class="flex justify-end mt-6 space-x-2">
                    <x-button wire:click="cancelReset" class="bg-gray-300 hover:bg-gray-400 text-gray-800">
                        Cancel
                    </x-button>
                    <x-button wire:click="resetContext" class="bg-red-600 hover:bg-red-500 text-white">
                        Reset to Default
                    </x-button>
                </div>
            </div>
        </x-modal>
    @endif
</div>
