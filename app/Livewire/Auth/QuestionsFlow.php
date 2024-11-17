<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\Question;
use Illuminate\Support\Facades\Session;

class QuestionsFlow extends Component
{
    public $questions;
    public $currentQuestionIndex = 0;
    public $selectedOptions = [];
    public $stage = 'question'; // Possible values: 'question', 'intermediate', 'loading'

    public function mount()
    {
        $gender = Session::get('gender');

        if (!$gender) {
            // Redirect back to home if gender is not selected
            return redirect()->route('home')->with('error', 'Please select your gender first.');
        }

        // Fetch all questions ordered by the 'order' field
        $this->questions = Question::orderBy('order')->with('options')->get();
    }

    public function answerQuestion($optionId)
    {
        $question = $this->questions[$this->currentQuestionIndex];
        $this->selectedOptions[$question->id] = $optionId;
    
        // After answering the 5th question (index 4), show intermediate step
        if ($this->currentQuestionIndex == 14 && $this->stage == 'question') {
            $this->stage = 'intermediate';
        } elseif ($this->currentQuestionIndex < count($this->questions) - 1 && $this->stage == 'question') {
            $this->currentQuestionIndex++;
        }
        // Do not automatically finish the questionnaire on the last question
    }
    public function nextQuestion()
    {
        // Ensure the current question is answered before moving to the next
        $question = $this->questions[$this->currentQuestionIndex];
        if (!isset($this->selectedOptions[$question->id])) {
            $this->dispatch('error');
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

    public function finishQuestionnaire()
    {
        // Ensure the current question is answered
        $question = $this->questions[$this->currentQuestionIndex];
        if (!isset($this->selectedOptions[$question->id])) {
            $this->dispatch('finish');
            return;
        }
    
        // Store the selected options in session
        Session::put('selectedOptions', $this->selectedOptions);
    
        // Set stage to loading
        $this->stage = 'loading';
    }
    

    public function continueFromIntermediate()
    {
        $this->stage = 'question';
        $this->currentQuestionIndex++;
    }

    public function redirectToCoach()
    {
        // Redirect to the coach generated page
        return redirect()->route('coach.generated');
    }

    public function render()
    {
        return view('livewire.auth.questions-flow')->layout('layouts.guest');
    }
}
