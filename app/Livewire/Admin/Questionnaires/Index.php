<?php

namespace App\Livewire\Admin\Questionnaires;

use Livewire\Component;
use App\Models\Question;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $text;
    public $questionToEdit;
    public $options = [];
    public $editing = false;
    public $showModal = false;

    public $confirmingQuestionDeletion = false;
    public $questionToDelete;

    protected $rules = [
        'text' => 'required|string|max:255',
        'options.*' => 'required|string|max:255',
    ];

    public function render()
    {
        $questions = Question::with('options')->orderBy('order')->get();

        return view('livewire.admin.questionnaires.index', compact('questions'))->layout('layouts.admin');
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->editing = false;
        $this->showModal = true;
    }

    public function addQuestion()
    {
        $this->validate();

        $question = Question::create(['text' => $this->text, 'order' => Question::max('order') + 1]);
        foreach ($this->options as $option) {
            $question->options()->create(['text' => $option]);
        }

        $this->resetForm();
        session()->flash('message', 'Question added successfully.');
    }

    public function editQuestion(Question $question)
    {
        $this->questionToEdit = $question;
        $this->text = $question->text;
        $this->options = $question->options->pluck('text')->toArray();
        $this->editing = true;
        $this->showModal = true;
    }

    public function updateQuestion()
    {
        $this->validate();

        $this->questionToEdit->update(['text' => $this->text]);
        $this->questionToEdit->options()->delete();

        foreach ($this->options as $option) {
            $this->questionToEdit->options()->create(['text' => $option]);
        }

        $this->resetForm();
        session()->flash('message', 'Question updated successfully.');
    }

    public function confirmQuestionDeletion($id)
    {
        $this->questionToDelete = Question::findOrFail($id);
        $this->confirmingQuestionDeletion = true;
    }

    public function deleteQuestion()
    {
        $this->questionToDelete->delete();
        $this->confirmingQuestionDeletion = false;
        $this->questionToDelete = null;
        session()->flash('message', 'Question deleted successfully.');
    }

    public function resetForm()
    {
        $this->reset(['text', 'options', 'questionToEdit', 'editing', 'showModal']);
    }

    public function reorderQuestions($order)
    {
        foreach ($order as $item) {
            Question::where('id', $item['value'])->update(['order' => $item['order']]);
        }
        session()->flash('message', 'Questions reordered successfully.');
    }

    public function addOption()
    {
        $this->options[] = ''; // Add a new empty option
    }

    public function removeOption($index)
    {
        unset($this->options[$index]); // Remove the option at the given index
        $this->options = array_values($this->options); // Re-index the array
    }
}
