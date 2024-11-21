<?php

namespace App\Livewire\Admin\SystemMessageManagement;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SystemMessage;

class Index extends Component
{
    use WithPagination;

    public $type;
    public $content;
    public $messageToEdit;
    public $editing = false;
    public $showModal = false;

    public $confirmingMessageDeletion = false;
    public $messageToDelete;

    protected $rules = [
        'type' => 'required|string|max:50|unique:system_messages,type',
        'content' => 'required|string',
    ];

    protected $messages = [
        'type.unique' => 'The message type must be unique.',
    ];

    public function render()
    {
        $systemMessages = SystemMessage::orderBy('type')->paginate(10);

        return view('livewire.admin.system-message-management.index', compact('systemMessages'))
            ->layout('layouts.admin');
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->editing = false;
        $this->showModal = true;
    }

    public function addMessage()
    {
        $this->validate();

        SystemMessage::create([
            'type' => $this->type,
            'content' => $this->content,
        ]);

        $this->resetForm();
        $this->showModal = false;
        session()->flash('message', 'System message added successfully.');
    }

    public function editMessage(SystemMessage $message)
    {
        $this->messageToEdit = $message;
        $this->type = $message->type;
        $this->content = $message->content;
        $this->editing = true;
        $this->showModal = true;

        // Update validation rule to ignore the current message's type
        $this->rules['type'] = 'required|string|max:50' . $message->id;
    }

    public function updateMessage()
    {
        $this->validate();

        $message = $this->messageToEdit;
        $message->update([
            'type' => $this->type,
            'content' => $this->content,
        ]);

        $this->resetForm();
        $this->showModal = false;
        session()->flash('message', 'System message updated successfully.');
    }

    public function confirmMessageDeletion($id)
    {
        $this->messageToDelete = SystemMessage::findOrFail($id);
        $this->confirmingMessageDeletion = true;
    }

    public function deleteMessage()
    {
        $this->messageToDelete->delete();

        $this->confirmingMessageDeletion = false;
        $this->messageToDelete = null;
        session()->flash('message', 'System message deleted successfully.');
    }

    public function resetForm()
    {
        $this->reset(['type', 'content', 'messageToEdit', 'editing', 'showModal']);
        $this->resetValidation();
        // Reset validation rules to default
        $this->rules['type'] = 'required|string|max:50|unique:system_messages,type';
    }
}
