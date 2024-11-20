<!-- resources/views/livewire/auth/questions-flow.blade.php -->

<x-authentication-card>
    <x-slot name="logo">
        <x-authentication-card-logo />
    </x-slot>

    <!-- Display Session Error Messages -->
    @if (session()->has('error'))
        <div class="mb-4 text-red-500">
            {{ session('error') }}
        </div>
    @endif

    <!-- Question Stage -->
    @if ($stage === 'question')
        <!-- Progress Indicator -->
        <div class="mb-4">
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-indigo-500 h-2.5 rounded-full" style="width: {{ (($currentQuestionIndex + 1) / count($questions)) * 100 }}%"></div>
            </div>
            <p class="text-sm text-gray-600 mt-1">{{ ($currentQuestionIndex + 1) }} of {{ count($questions) }} questions answered</p>
        </div>

        <!-- Current Question -->
        <div class="p-4 bg-white">
            <p class="text-sm font-semibold mb-4 text-center">Question {{ $currentQuestionIndex + 1 }} of {{ count($questions) }}</p>
            <h2 class="mb-6 text-2xl text-center">{{ $questions[$currentQuestionIndex]->text }}</h2>
            <div class="flex flex-col space-y-4">
                @php
                    $questionId = $questions[$currentQuestionIndex]->id;
                    $selectedOptionId = $selectedOptions[$questionId] ?? null;
                @endphp

                @foreach ($questions[$currentQuestionIndex]->options as $option)
                    @php
                        $isSelected = $selectedOptionId == $option->id;
                    @endphp
                    <button
                        wire:click="answerQuestion({{ $option->id }})"
                        class="px-4 py-2 rounded-lg transition text-left {{ $isSelected ? 'bg-indigo-500 hover:bg-indigo-600 text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-700' }}"
                    >
                        {{ $option->text }}
                    </button>
                @endforeach
            </div>
            <div class="mt-6 text-center text-gray-500">
                Gender Selected: <span class="font-medium capitalize">{{ Session::get('gender') }}</span>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between mt-6">
            @if ($currentQuestionIndex > 0)
                <button
                    wire:click="previousQuestion"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition"
                >
                    Previous
                </button>
            @else
                <div></div>
            @endif

            @if ($currentQuestionIndex < count($questions) - 1)
                <button
                    wire:click="nextQuestion"
                    class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition"
                >
                    Next
                </button>
            @else
                <button
                    wire:click="finishQuestionnaire"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                >
                    Finish
                </button>
            @endif
        </div>
    @elseif ($stage === 'intermediate')
        <!-- Intermediate Stage -->
        <div class="p-6 bg-white text-center">
            <h2 class="text-2xl font-semibold mb-4">Take a Short Break</h2>
            <p class="mb-6">
                You've answered the first part of the questionnaire. Take a moment to relax before continuing.
            </p>
            <button
                wire:click="continueFromIntermediate"
                class="px-6 py-3 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition"
            >
                Continue
            </button>
        </div>
    @elseif ($stage === 'coach_selection')
        <!-- Coach Selection Stage -->
        <div class="p-6 bg-white text-center">
            <h2 class="text-2xl font-semibold mb-4">Select Your Mental Health Coach</h2>
            <p class="mb-6 text-gray-600">Choose a coach that best suits your needs.</p>

            <!-- Carousel for Coach Selection with Alpine.js -->
            <div
                class="relative w-full max-w-md mx-auto"
                x-data="{ currentSlide: 0 }"
                x-init="currentSlide = 0"
            >
                <!-- Coach Cards -->
                <div class="flex transition-transform duration-500 ease-in-out" :style="'transform: translateX(-' + (currentSlide * 100) + '%);'">
                    @foreach ($coaches as $coach)
                        <div class="flex-shrink-0 w-full px-4">
                            <div class="coach-card mx-auto max-w-md bg-gray-100 rounded-lg shadow-md p-6 text-center">
                                <img
                                    src="{{ asset($coach->avatar) }}"
                                    alt="{{ $coach->name }}"
                                    class="w-24 h-24 rounded-full mx-auto object-cover mb-4"
                                >
                                <h3 class="text-xl font-semibold">{{ $coach->name }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $coach->designation }}</p>
                                <p class="text-sm text-gray-500 mb-4">{{ $coach->bio }}</p>
                                <button
                                    wire:click="selectCoach({{ $coach->id }})"
                                    class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition"
                                >
                                    Select
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Left Arrow -->
                <button
                    @click="currentSlide = (currentSlide > 0) ? currentSlide - 1 : currentSlide"
                    class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-gray-300 bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-2 focus:outline-none"
                    :disabled="currentSlide === 0"
                >
                    <!-- Left Arrow Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Right Arrow -->
                <button
                    @click="currentSlide = (currentSlide < {{ count($coaches) - 1 }}) ? currentSlide + 1 : currentSlide"
                    class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-gray-300 bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-2 focus:outline-none"
                    :disabled="currentSlide === {{ count($coaches) - 1 }}"
                >
                    <!-- Right Arrow Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- No Finish button here; after selecting a coach, continue to questions -->
        </div>
    @elseif ($stage === 'finish')
        <!-- Finish Stage -->
        <div class="p-6 bg-white rounded-lg shadow-md text-center">
            <h2 class="text-2xl font-semibold mb-4">Review and Submit</h2>
            <p class="mb-6 text-gray-600">
                You've completed the questionnaire and selected your coach. Click below to submit your responses.
            </p>
            <button
                wire:click="finishQuestionnaire"
                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
            >
                Submit
            </button>
        </div>
    @endif
    <!-- Additional Styles for Coach Carousel -->
<style>
    /* Customize the carousel arrows */
    .relative button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Hide scrollbar if any */
    .coach-slider::-webkit-scrollbar {
        display: none;
    }

    /* Hover effect for coach cards */
    .coach-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .coach-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
</style>

</x-authentication-card>

