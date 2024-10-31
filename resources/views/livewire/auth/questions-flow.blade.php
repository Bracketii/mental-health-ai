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
    <div class="p-2 bg-whit">
        <h2 class="text-2xl font-semibold mb-4">Question {{ $currentQuestionIndex + 1 }} of {{ count($questions) }}</h2>
        <p class="mb-6">{{ $questions[$currentQuestionIndex]->text }}</p>
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
</x-authentication-card>

