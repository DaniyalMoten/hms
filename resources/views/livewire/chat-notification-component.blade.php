<li class="px-sm-3 px-2" wire:poll.3000ms="updateUnreadCount">
    <a data-turbo="false" href="{{ route('chat.index') }}" class="btn hide-arrow p-0 position-relative d-flex align-items-center py-4">
        <i class="fa-solid fa-comment-dots text-primary fs-2"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge badge-circle bg-danger end-1">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </a>
</li>
