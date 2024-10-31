<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;

class GenderSelectionButtons extends Component
{
    public function selectGender($gender)
    {
        $validGenders = ['male', 'female', 'non_binary'];
        if (!in_array($gender, $validGenders)) {
            session()->flash('error', 'Invalid gender selection.');
            return;
        }

        Session::put('gender', $gender);
        return redirect()->route('questions.flow');
    }
    public function render()
    {
        return view('livewire.gender-selection-buttons');
    }
}
