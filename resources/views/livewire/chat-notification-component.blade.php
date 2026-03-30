<li class="px-sm-3 px-2" wire:poll.3000ms="updateUnreadCount">
    <a data-turbo="false" href="{{ route('chat.index') }}"
        class="btn hide-arrow p-0 position-relative d-flex align-items-center py-4">
        <i class="fa-solid fa-comment-dots text-primary fs-2"></i>
        @if ($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge badge-circle bg-danger end-1">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </a>

    <script>
        if (!window.chatToastListenerAdded) {
            window.addEventListener('new-chat-message', event => {
                let toastContainer = document.getElementById('chat-toast-container');
                if (!toastContainer) {
                    toastContainer = document.createElement('div');
                    toastContainer.id = 'chat-toast-container';
                    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                    toastContainer.style.zIndex = '1050';
                    document.body.appendChild(toastContainer);
                }

                let toastHtml = `
                <div class="toast border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
                  <div class="toast-header bg-white border-bottom-0">
                    <i class="fa-solid fa-comment-dots text-primary me-2"></i>
                    <strong class="me-auto text-dark" style="color: black !important;">${event.detail.sender}</strong>
                    <small class="text-muted">just now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                  </div>
                  <div class="toast-body bg-light rounded-bottom">
                    ${event.detail.message}
                  </div>
                </div>`;

                let tempDiv = document.createElement('div');
                tempDiv.innerHTML = toastHtml;
                let toastEl = tempDiv.firstElementChild;
                toastContainer.appendChild(toastEl);

                if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                    var toast = new bootstrap.Toast(toastEl, {
                        autohide: true,
                        delay: 8000
                    });
                    toast.show();

                    toastEl.addEventListener('hidden.bs.toast', function() {
                        toastEl.remove();
                    });
                }
            });
            window.chatToastListenerAdded = true;
        }
    </script>
</li>
