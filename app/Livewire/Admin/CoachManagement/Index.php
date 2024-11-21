<?php

namespace App\Livewire\Admin\CoachManagement;

use App\Models\Coach;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $name;
    public $designation;
    public $avatar;
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
        'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:10240', // Max 10MB
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
            // Handle avatar upload if exists
            $avatarPath = null;
            if ($this->avatar) {
                $avatarPath = $this->avatar->store('avatars', 'public');
            }

            Coach::create([
                'name' => $this->name,
                'designation' => $this->designation,
                'bio' => $this->bio,
                'avatar' => $avatarPath,
            ]);

            session()->flash('message', 'Coach added successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Avatar upload failed: ' . $e->getMessage());
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
        $this->avatar = null;
        $this->editing = true;
        $this->showModal = true;
    }

    public function updateCoach()
    {
        $this->validate();

        try {
            $coach = $this->coachToEdit;

            // Handle avatar upload if exists
            if ($this->avatar) {
                // Delete old avatar if exists
                if ($coach->avatar) {
                    Storage::disk('public')->delete($coach->avatar);
                }
                $avatarPath = $this->avatar->store('avatars', 'public');
                $coach->avatar = $avatarPath;
            }

            $coach->name = $this->name;
            $coach->designation = $this->designation;
            $coach->bio = $this->bio;
            $coach->save();

            session()->flash('message', 'Coach updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Update failed: ' . $e->getMessage());
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function confirmCoachDeletion($id)
    {
        $this->coachToDelete = Coach::findOrFail($id);
        $this->confirmingCoachDeletion = true;
    }

    public function deleteCoach()
    {
        try {
            $coach = $this->coachToDelete;

            // Delete avatar if exists
            if ($coach->avatar) {
                Storage::disk('public')->delete($coach->avatar);
            }

            $coach->delete();
            session()->flash('message', 'Coach deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Deletion failed: ' . $e->getMessage());
        }

        $this->confirmingCoachDeletion = false;
        $this->coachToDelete = null;
    }

    public function resetForm()
    {
        $this->reset(['name', 'designation', 'avatar', 'bio', 'coachToEdit', 'editing']);
        $this->resetValidation();
    }
}
