<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-neutral-200">Manage Questionnaires</h2>
        <x-button wire:click="openAddModal" class="bg-blue-600 hover:bg-blue-500">
            <i class='bx bx-plus'></i> Add New Question
        </x-button>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <x-alert type="success" class="mb-4">
            {{ session('message') }}
        </x-alert>
    @endif

    <!-- Questions Table with Sorting -->
    <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-1">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase">Question</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase">Options</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody wire:sortable="reorderQuestions" class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse ($questions as $question)
                        <tr wire:sortable.item="{{ $question->id }}">
                            <td wire:sortable.handle class="px-6 py-4 text-sm text-gray-700 dark:text-neutral-200 cursor-move">
                                <i class="bx bx-move"></i>
                                {{ $question->order }}
                            </td>
                            <td class="px-6 py-4 text-lg text-gray-700 dark:text-neutral-200">{{ $question->text }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-neutral-200">
                                <ul>
                                    @foreach ($question->options as $option)
                                        <li>{{ $option->text }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-6 py-4 text-sm text-right space-y-2">
                                <x-button wire:click="editQuestion({{ $question->id }})" class="bg-yellow-600 hover:bg-yellow-500 !p-1">
                                    <i class='bx bx-edit'></i>
                                </x-button>
                                <x-button wire:click="confirmQuestionDeletion({{ $question->id }})" class="bg-red-600 hover:bg-red-500 !p-1">
                                    <i class='bx bx-trash'></i>
                                </x-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-neutral-400">No questions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    @if ($showModal)
        <x-modal wire:model="showModal" id="question-modal" maxWidth="lg">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-700 dark:text-neutral-200">{{ $editing ? 'Edit' : 'Add' }} Question</h3>
                <form wire:submit.prevent="{{ $editing ? 'updateQuestion' : 'addQuestion' }}">
                    <div class="space-y-4">
                        <x-input-group>
                            <x-label for="text" value="Question" />
                            <x-input id="text" wire:model.defer="text" class="w-full" />
                            <x-input-error for="text" />
                        </x-input-group>

                        <x-input-group>
                            <x-label for="options" value="Options" />
                            <div>
                                @foreach ($options as $index => $option)
                                    <div class="flex items-center space-x-2 mb-2">
                                        <x-input wire:model.defer="options.{{ $index }}" class="w-full" />
                                        <x-button wire:click.prevent="removeOption({{ $index }})" class="bg-red-600 hover:bg-red-500">
                                            <i class='bx bx-minus'></i>
                                        </x-button>
                                    </div>
                                @endforeach
                                <x-button wire:click.prevent="addOption" class="bg-blue-600 hover:bg-blue-500 mt-2">
                                    <i class='bx bx-plus'></i> Add Option
                                </x-button>
                            </div>
                            <x-input-error for="options.*" />
                        </x-input-group>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <x-button wire:click="resetForm" class="bg-gray-300 hover:bg-gray-400 text-gray-800">
                            Cancel
                        </x-button>
                        <x-button type="submit" class="bg-blue-600 hover:bg-blue-500">
                            {{ $editing ? 'Update' : 'Add' }} Question
                        </x-button>
                    </div>
                </form>
            </div>
        </x-modal>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($confirmingQuestionDeletion)
        <x-modal wire:model="confirmingQuestionDeletion" id="delete-question-modal" maxWidth="md">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-700 dark:text-neutral-200 mb-4">Confirm Deletion</h3>
                <p class="text-gray-600 dark:text-neutral-400">
                    Are you sure you want to delete the question: <strong>{{ $questionToDelete->text }}</strong>? This action cannot be undone.
                </p>
                <div class="flex justify-end mt-6 space-x-2">
                    <x-button wire:click="$set('confirmingQuestionDeletion', false)" class="bg-gray-300 hover:bg-gray-400 text-gray-800">
                        Cancel
                    </x-button>
                    <x-button wire:click="deleteQuestion" class="bg-red-600 hover:bg-red-500 text-white">
                        Delete
                    </x-button>
                </div>
            </div>
        </x-modal>
    @endif
</div>
