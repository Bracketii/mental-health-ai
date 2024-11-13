<!-- resources/views/livewire/auth/questions-flow.blade.php -->

<x-authentication-card>
    <x-slot name="logo">
        <x-authentication-card-logo />
    </x-slot>

    <!-- Optional: Display Session Error Messages -->
    @if (session()->has('error'))
        <div class="mb-4 text-red-500">
            {{ session('error') }}
        </div>
    @endif

    <!-- Progress Indicator -->
    <div class="mb-4">
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ (($currentQuestionIndex + 1) / count($questions)) * 100 }}%"></div>
        </div>
        <p class="text-sm text-gray-600 mt-1">{{ ($currentQuestionIndex + 1) }} of {{ count($questions) }} questions answered</p>
    </div>

    <!-- Current Question -->
    <div class="p-2 bg-white">
        <p class="text-sm font-semibold mb-4 text-center">Question {{ $currentQuestionIndex + 1 }} of {{ count($questions) }}</p>
        <h2 class="mb-6 text-2xl text-center">{{ $questions[$currentQuestionIndex]->text }}</h2>
        <div class="flex flex-col space-y-4">
            @foreach ($questions[$currentQuestionIndex]->options as $option)
                <button wire:click="answerQuestion({{ $option->id }})" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition text-left">
                    {{ $option->text }}
                </button>
            @endforeach
        </div>
        <div class="mt-6 text-center text-gray-500">
            Gender Selected: <span class="font-medium capitalize">{{ Session::get('gender') }}</span>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="flex justify-between mt-4">
        @if ($currentQuestionIndex > 0)
            <button wire:click="previousQuestion" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                Previous
            </button>
        @else
            <div></div>
        @endif

        @if ($currentQuestionIndex < count($questions) - 1)
            <button wire:click="nextQuestion" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Next
            </button>
        @else
            <button wire:click="finishQuestionnaire" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Finish
            </button>
        @endif
    </div>
</x-authentication-card>
