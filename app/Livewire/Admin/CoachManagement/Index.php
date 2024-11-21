<?php

namespace App\Livewire\Admin\CoachManagement;

use App\Models\Coach;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination;

    public $name;
    public $designation;
    public $uploadedAvatarPath; // Holds the uploaded avatar path
    public $bio;
    public $coachToEdit;
    public $editing = false;
    public $showModal = false;

    public $confirmingCoachDeletion = false;
    public $coachToDelete;

    protected $rules = [
        'name' => 'required|string|max:255',
        'designation' => 'required|string|max:255',
        'bio' => 'nullable|string|max:1000',
        'uploadedAvatarPath' => 'nullable|string', // Validate as a string path
    ];

    public function render()
    {
        $coaches = Coach::orderBy('name')->paginate(10);

        return view('livewire.admin.coach-management.index', compact('coaches'))
            ->layout('layouts.admin');
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->editing = false;
        $this->showModal = true;
    }

    public function addCoach()
    {
        $this->validate();

        try {
            Coach::create([
                'name' => $this->name,
                'designation' => $this->designation,
                'bio' => $this->bio,
                'avatar' => $this->uploadedAvatarPath, // Use the uploaded avatar path
            ]);

            session()->flash('message', 'Coach added successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add coach: ' . $e->getMessage());
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function editCoach(Coach $coach)
    {
        $this->coachToEdit = $coach;
        $this->name = $coach->name;
        $this->designation = $coach->designation;
        $this->bio = $coach->bio;
        $this->uploadedAvatarPath = $coach->avatar;
        $this->editing = true;
        $this->showModal = true;
    }

    public function updateCoach()
    {
        $this->validate();

        try {
            $coach = $this->coachToEdit;

            if ($this->uploadedAvatarPath !== $coach->avatar && $coach->avatar) {
                // Delete the old avatar
                Storage::disk('public')->delete($coach->avatar);
            }

            $coach->update([
                'name' => $this->name,
                'designation' => $this->designation,
                'bio' => $this->bio,
                'avatar' => $this->uploadedAvatarPath,
            ]);

            session()->flash('message', 'Coach updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Update failed: ' . $e->getMessage());
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function resetForm()
    {
        $this->reset(['name', 'designation', 'uploadedAvatarPath', 'bio', 'coachToEdit', 'editing']);
        $this->resetValidation();
    }
}
