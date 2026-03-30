<?php

namespace App\Http\Livewire;

use App\Models\Appointment;
use App\Models\ChatMessage;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatComponent extends Component
{
    public $contacts = [];
    public $selectedContactId = null;
    public $selectedContact = null;
    public $chatMessages = [];
    public $newMessage = '';

    public function mount($userId = null)
    {
        $this->loadContacts();
        
        if ($userId) {
            $this->selectContact($userId);
        }
    }

    public function loadContacts()
    {
        $user = Auth::user();
        if (!$user) return;

        $this->contacts = [];

        if ($user->owner_type == Doctor::class) {
            $doctorId = $user->owner_id;
            $patientIds = Appointment::where('doctor_id', $doctorId)->pluck('patient_id')->unique();
            
            $this->contacts = User::where('owner_type', Patient::class)
                ->whereIn('owner_id', $patientIds)
                ->get();
                
        } elseif ($user->owner_type == Patient::class) {
            $patientId = $user->owner_id;
            $doctorIds = Appointment::where('patient_id', $patientId)->pluck('doctor_id')->unique();
            
            $this->contacts = User::where('owner_type', Doctor::class)
                ->whereIn('owner_id', $doctorIds)
                ->get();
        }

        $userId = Auth::id();
        foreach ($this->contacts as $contact) {
            $contact->unread_count = ChatMessage::where('sender_id', $contact->id)
                ->where('receiver_id', $userId)
                ->whereNull('read_at')
                ->count();
        }
    }

    public function selectContact($contactId)
    {
        $isAllowed = false;
        foreach ($this->contacts as $contact) {
            if ($contact->id == $contactId) {
                $isAllowed = true;
                break;
            }
        }

        if (!$isAllowed) {
            return;
        }

        $this->selectedContactId = $contactId;
        $this->selectedContact = User::find($contactId);
        $this->loadMessages();
    }

    public function loadMessages()
    {
        if (!$this->selectedContactId) return;

        $userId = Auth::id();
        
        $this->chatMessages = ChatMessage::where(function($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $this->selectedContactId);
        })->orWhere(function($query) use ($userId) {
            $query->where('sender_id', $this->selectedContactId)
                  ->where('receiver_id', $userId);
        })->orderBy('created_at', 'asc')->get();
        
        // Mark as read
        ChatMessage::where('sender_id', $this->selectedContactId)
            ->where('receiver_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function sendMessage()
    {
        $this->validate([
            'newMessage' => 'required|string|max:1000'
        ]);

        if (!$this->selectedContactId) return;

        ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedContactId,
            'message' => $this->newMessage,
        ]);

        $this->newMessage = '';
        $this->loadMessages();
    }

    public function render()
    {
        if ($this->selectedContactId) {
            $this->loadMessages();
        }

        return view('livewire.chat-component')
            ->extends('layouts.app')
            ->section('content');
    }
}
