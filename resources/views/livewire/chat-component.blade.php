<div>
    <style>
        .chat-container {
            background: #065fb9ff;
            height: 75vh;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e5e5;
        }

        .chat-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .chat-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-scroll::-webkit-scrollbar-thumb {
            background: #bcc0c4;
            border-radius: 10px;
        }

        .msg-bubble {
            padding: 10px 16px;
            font-size: 15px;
            display: inline-block;
            max-width: 75%;
            word-wrap: break-word;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .msg-sent {
            background-color: #0084ff;
            color: #ffffff;
            border-radius: 20px 20px 4px 20px;
        }

        .msg-received {
            background-color: #e4e6eb;
            color: #050505;
            border-radius: 20px 20px 20px 4px;
        }

        .chat-input {
            background-color: #f0f2f5;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
        }

        .chat-input:focus {
            background-color: #f0f2f5;
            box-shadow: none;
        }

        .send-btn {
            color: #0084ff;
            font-size: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            padding: 5px 10px;
            border-radius: 50%;
            transition: background 0.2s;
        }

        .send-btn:hover {
            background-color: #f0f2f5;
        }

        .contact-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border-radius: 10px;
            border: 1px solid #e5e5e5;
        }

        .contact-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
    @if (!$selectedContactId)
        <!-- CONTACTS DIRECTORY -->
        <div class="container mt-4" style="width:1064px;" wire:poll.5000ms="loadContacts">
            <div class="row mb-3    ">
                <div class="col-12">
                    <h3 class="fw-bold">My Appointments / Doctors</h3>
                    <p class="text-muted">Select a doctor to start a secure chat.</p>
                </div>
            </div>
            <div class="row">
                @forelse($contacts as $contact)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card contact-card h-100 text-center p-4 position-relative">
                            @if ($contact->unread_count > 0)
                                <span class="position-absolute top-0 end-0 mt-2 me-2 badge rounded-pill bg-danger"
                                    style="z-index: 1;">
                                    {{ $contact->unread_count > 9 ? '9+' : $contact->unread_count }} New
                                </span>
                            @endif
                            <img src="{{ $contact->api_image_url }}" alt="Avatar" class="rounded-circle mx-auto mb-3"
                                width="80" height="80" style="object-fit: cover;">
                            <h5 class="fw-bold text-dark">{{ $contact->full_name }}</h5>
                            <p class="text-muted small mb-2">{{ explode('\\', $contact->owner_type)[2] ?? '' }}</p>
                            @if ($contact->unread_count > 0)
                                <p class="text-danger small fw-bold mb-3">
                                    <i class="fas fa-envelope text-danger"></i> 
                                    {{ $contact->unread_count }} New: "{{ \Illuminate\Support\Str::limit($contact->last_unread_message, 25) }}"
                                </p>
                            @else
                                <div class="mb-3"></div> <!-- spacing to maintain height -->
                            @endif
                            <div class="mt-auto">
                                <a href="{{ route('chat', $contact->id) }}"
                                    class="btn btn-primary w-100 rounded-pill {{ $contact->unread_count > 0 ? 'btn-danger border-0' : '' }}">
                                    <i class="fas fa-comment-dots me-2"></i> Chat
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info py-4 text-center">
                            <i class="fas fa-calendar-times fs-2 mb-2"></i>
                            <h5>No Appointments Found</h5>
                            <p class="mb-0">You do not have any appointments. You can only chat with doctors you have
                                booked.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    @else
        <!-- SINGLE CHAT WINDOW -->
        <div class="card mt-4 bg-white chat-container border-0 mx-auto" style="max-width: 1680px;">
            <div class="row g-0 h-100">
                <!-- Full Width Chat Area -->
                <div class="col-12 h-100 d-flex flex-column bg-white">
                    <!-- Chat Header -->
                    <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-white shadow-sm"
                        style="z-index: 10;">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('chat.index') }}" class="btn btn-light rounded-circle me-3">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <img src="{{ $selectedContact->api_image_url }}" alt="Avatar" class="rounded-circle me-3"
                                width="44" height="44" style="object-fit: cover;">
                            <div>
                                <h5 class="mb-0 fw-bold" style="font-size: 16px; color: #050505;">
                                    {{ $selectedContact->full_name }}</h5>
                                <small class="text-success"><i class="fas fa-circle" style="font-size: 8px;"></i>
                                    Online</small>
                            </div>
                        </div>
                    </div>
                    <!-- Messages Box -->
                    <div class="flex-grow-1 p-4  chat-scroll"
                        style="overflow-y: auto; background-color: #ffffff; width:1064px;"
                        wire:poll.2000ms="loadMessages">
                        @forelse($chatMessages as $msg)
                            @if ($msg->sender_id === auth()->id())
                                <!-- Sent Message (Right) -->
                                <div class="d-flex justify-content-end mb-3">
                                    <div class="d-flex flex-column align-items-end">
                                        <div class="msg-bubble msg-sent">
                                            {{ $msg->message }}
                                        </div>
                                        <div class="text-muted mt-1" style="font-size: 11px;">
                                            {{ $msg->created_at->format('h:i A') }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Received Message (Left) -->
                                <div class="d-flex justify-content-start mb-3">
                                    <img src="{{ $selectedContact->api_image_url }}" alt="Avatar"
                                        class="rounded-circle align-self-end me-2 mb-4" width="28" height="28">
                                    <div class="d-flex flex-column align-items-start">
                                        <div class="msg-bubble msg-received">
                                            {{ $msg->message }}
                                        </div>
                                        <div class="text-muted mt-1" style="font-size: 11px;">
                                            {{ $msg->created_at->format('h:i A') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted">
                                <img src="{{ $selectedContact->api_image_url }}" alt="Avatar"
                                    class="rounded-circle mb-3 border shadow-sm" width="90" height="90">
                                <h5>Say Hi to {{ $selectedContact->full_name }}!</h5>
                                <p style="font-size: 13px;">You are connected.</p>
                            </div>
                        @endforelse
                    </div>
                    <!-- Chat Input -->
                    <div class="p-3 bg-white" style="border-top: 1px solid #e5e5e5;">
                        <form wire:submit.prevent="sendMessage" class="d-flex align-items-center">
                            <input type="text" wire:model.defer="newMessage" class="form-control chat-input me-3"
                                placeholder="Type a message..." autocomplete="off" required>
                            <button type="submit" class="send-btn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
