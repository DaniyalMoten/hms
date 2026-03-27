<?php

namespace App\Http\Livewire;

use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatNotificationComponent extends Component
{
    public $unreadCount = 0;

    public function mount()
    {
        $this->updateUnreadCount();
    }

    public function updateUnreadCount()
    {
        if (Auth::check()) {
            $this->unreadCount = ChatMessage::where('receiver_id', Auth::id())
                ->whereNull('read_at')
                ->count();
        }
    }

    public function render()
    {
        return view('livewire.chat-notification-component');
    }
}
