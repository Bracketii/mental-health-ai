<?php

namespace App\Livewire\Auth;

use App\Models\Coach;
use Livewire\Component;
use App\Models\Question;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class QuestionsFlow extends Component
{
    public $questions;
    public $currentQuestionIndex = 0;
    public $selectedOptions = [];
    public $stage = 'question'; // Possible values: 'question', 'intermediate', 'coach_selection'

    // Properties for coach selection
    public $coaches;
    public $selectedCoachId;

    // Properties for carousel functionality
    public $currentCoachSlide = 0;

    public function mount()
    {
        $gender = Session::get('gender');

        if (!$gender) {
            // Redirect back to home if gender is not selected
            return redirect()->route('home')->with('error', 'Please select your gender first.');
        }

        // Fetch all questions ordered by the 'order' field
        $this->questions = Question::orderBy('order')->with('options')->get();

        // Fetch all coaches and shuffle them for random order
        $this->coaches = Coach::all()->shuffle();
    }

    public function answerQuestion($optionId)
    {
        $question = $this->questions[$this->currentQuestionIndex];
        $this->selectedOptions[$question->id] = $optionId;

        // Check if the next stage is to show intermediate or proceed with questions
        if ($this->currentQuestionIndex < count($this->questions) - 1 && $this->stage == 'question') {
            $this->currentQuestionIndex++;
        } elseif ($this->stage == 'question') {
            $this->stage = 'intermediate';
        }
    }

    public function nextQuestion()
    {
        $question = $this->questions[$this->currentQuestionIndex];
        if (!isset($this->selectedOptions[$question->id])) {
            session()->flash('error', 'Please answer the current question before proceeding.');
            return;
        }

        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    // Handle coach selection from the carousel
    public function selectCoach($coachId)
    {
        $this->selectedCoachId = $coachId;

        // Validate the selected coach
        $this->validate([
            'selectedCoachId' => 'required|exists:coaches,id',
        ]);

        // After selecting a coach, proceed to finish the questionnaire
        $this->stage = 'question';
    }

    public function finishQuestionnaire()
    {
        // Ensure all questions are answered
        $lastQuestion = $this->questions[count($this->questions) - 1];
        if (!isset($this->selectedOptions[$lastQuestion->id])) {
            session()->flash('error', 'Please complete all questions before finishing.');
            return;
        }

        // Ensure a coach has been selected
        if (!$this->selectedCoachId) {
            session()->flash('error', 'Please select a coach before finishing.');
            return;
        }

        // Record answers and coach selection for logged-in users
        if (Auth::check()) {
            $this->recordAnswers(Auth::id());
            $this->recordCoach(Auth::id());
        } else {
            // Store the selected options and coach in session for guest users
            Session::put('selectedOptions', $this->selectedOptions);
            Session::put('selectedCoachId', $this->selectedCoachId);
        }

        // Redirect based on user state
        return Auth::check() ? redirect()->route('checkout') : redirect()->route('coach.generated');
    }

    private function recordAnswers($userId)
    {
        foreach ($this->selectedOptions as $questionId => $optionId) {
            UserAnswer::updateOrCreate(
                [
                    'user_id' => $userId,
                    'question_id' => $questionId,
                ],
                ['option_id' => $optionId]
            );
        }
    }

    private function recordCoach($userId)
    {
        // Assign the selected coach to the user
        $user = Auth::user();
        $user->coach_id = $this->selectedCoachId;
        $user->save();
    }

    public function continueFromIntermediate()
    {
        $this->stage = 'coach_selection';
    }

    // Carousel navigation methods
    public function previousCoach()
    {
        if ($this->currentCoachSlide > 0) {
            $this->currentCoachSlide--;
        }
    }

    public function nextCoach()
    {
        if ($this->currentCoachSlide < count($this->coaches) - 1) {
            $this->currentCoachSlide++;
        }
    }

    public function render()
    {
        return view('livewire.auth.questions-flow')->layout('layouts.guest');
    }
}
