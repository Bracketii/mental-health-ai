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

    @if ($stage === 'question')
        <!-- Progress Indicator -->
        <div class="mb-4">
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-ap4 h-2.5 rounded-full" style="width: {{ (($currentQuestionIndex + 1) / count($questions)) * 100 }}%"></div>
            </div>
            <p class="text-sm text-gray-600 mt-1">{{ ($currentQuestionIndex + 1) }} of {{ count($questions) }} questions answered</p>
        </div>

        <x-action-message on="error" class="mb-4 text-red-600 text-xl text-center">
            {{ __('Select current question and move forward') }}
        </x-action-message>
        <x-action-message on="finish" class="mb-4 text-red-600 text-xl text-center">
            {{ __('select current question and finish') }}
        </x-action-message>
        
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
                    <x-secondary-button
                        wire:click="answerQuestion({{ $option->id }})"
                        class="px-4 py-2 rounded-lg transition text-left {{ $isSelected ? '!bg-indigo-500 hover:bg-indigo-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}"
                    >
                        {{ $option->text }}
                    </x-secondary-button>
                @endforeach
            </div>
            <div class="mt-6 text-center text-gray-500">
                Gender Selected: <span class="font-medium capitalize">{{ Session::get('gender') }}</span>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between mt-4">
            @if ($currentQuestionIndex > 0)
                <x-button wire:click="previousQuestion" class="px-4 py-2 bg-ap4 rounded-lg hover:bg-ap5 transition">
                    Previous
                </x-button>
            @else
                <div></div>
            @endif

            @if ($currentQuestionIndex < count($questions) - 1)
                <x-button wire:click="nextQuestion" class="px-4 py-2 bg-ap4 text-white rounded-lg hover:bg-ap5 transition">
                    Next
                </x-button>
            @else
                <x-button wire:click="finishQuestionnaire" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-800 transition">
                    Finish
                </x-button>
            @endif
        </div>
    @elseif ($stage === 'intermediate')
        <!-- Intermediate Screen -->
        <div class="p-6 bg-white text-center">
            <h2 class="text-2xl font-semibold mb-4">Take a Short Break</h2>
            <p class="mb-6">
                You've answered the first part of the questionnaire. Take a moment to relax before continuing.
            </p>
            <x-button wire:click="continueFromIntermediate" class="px-6 py-3 bg-ap4 text-white rounded-lg hover:bg-ap5 transition">
                Continue
            </x-button>
        </div>
    @elseif ($stage === 'loading')
        <!-- Loading Screen -->
        <div class="p-6 bg-white flex flex-col items-center justify-center h-auto"
             x-data="{
                percentage: 0,
                spinnerActive: true,
                steps: [
                    { text: 'Initializing...', completed: false },
                    { text: 'Creating Coach Profile...', completed: false },
                    { text: 'Finalizing...', completed: false },
                ],
                startLoading() {
                    let delay = 3000; // 3 seconds per step
                    let totalSteps = this.steps.length;

                    this.steps.forEach((step, index) => {
                        setTimeout(() => {
                            this.steps[index].completed = true;
                            this.percentage = Math.round(((index + 1) / totalSteps) * 100);

                            if (index === this.steps.length - 1) {
                                setTimeout(() => {
                                    this.spinnerActive = false;
                                    this.$wire.redirectToCoach();
                                }, 1000); // Additional delay after final step
                            }
                        }, delay * (index + 1));
                    });
                }
            }"
             x-init="startLoading()"
        >
            <!-- Large Spinner -->
            <div class="flex flex-col items-center justify-center space-y-2">
                <div x-show="spinnerActive" class="animate-spin rounded-full h-24 w-24 border-t-4 border-blue-500 border-solid"></div>

                <!-- Percentage Display -->
                <h2 class="text-4xl font-bold text-gray-700" x-text="`${percentage}%`"></h2>

                <!-- Progress Steps -->
                <ul class="space-y-4 mt-6">
                    <template x-for="(step, index) in steps" :key="index">
                        <li class="flex items-center">
                            <span x-text="step.text" class="flex-1 text-lg font-medium"></span>
                            <span x-show="step.completed" class="text-green-500 transition-opacity duration-500">
                                <!-- Checkmark Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                        </li>
                    </template>
                </ul>
            </div>

            <!-- Message -->
            <div class="text-center text-gray-500 mt-8">
                Please wait while we generate your personalized AI coach.
            </div>
        </div>
    @endif
</x-authentication-card>
