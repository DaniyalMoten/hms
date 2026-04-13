<?php
namespace App\Http\Livewire;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
class ChatNotificationComponent extends Component
{
    public $unreadCount = 0;
    public $lastMessageId = null;
    public function mount()
    {
        if (Auth::check()) {
            $latestUnread = ChatMessage::where('receiver_id', Auth::id())
                ->whereNull('read_at')
                ->orderBy('id', 'desc')
                ->first();
            $this->lastMessageId = $latestUnread ? $latestUnread->id : 0;
        }
        $this->updateUnreadCount();
    }
    public function updateUnreadCount()
    {
        if (Auth::check()) {
            $this->unreadCount = ChatMessage::where('receiver_id', Auth::id())
                ->whereNull('read_at')
                ->count();
            if ($this->lastMessageId !== null) {
                $newMessages = ChatMessage::with('sender')
                    ->where('receiver_id', Auth::id())
                    ->where('id', '>', $this->lastMessageId)
                    ->get();                
                if ($newMessages->count() > 0) {
                    foreach ($newMessages as $msg) {
                        $this->dispatchBrowserEvent('new-chat-message', [
                            'sender' => $msg->sender->full_name ?? 'New Message',
                            'message' => \Illuminate\Support\Str::limit($msg->message, 50)
                        ]);
                    }                    
                    $this->lastMessageId = $newMessages->max('id');
                }
            }
        }
    }
    public function render()
    {
        return view('livewire.chat-notification-component');
    }
}