@extends('layouts.messaging')

@php
    /**
     * Format message text to turn URLs and emails into clickable links
     * 
     * @param string $text The message text to format
     * @return string The formatted text with clickable links
     */
    function formatMessageLinks($text) {
        if (empty($text)) return $text;
        
        // URL pattern: matches http://, https:// and plain domain formats
        $urlPattern = '/(https?:\/\/[^\s<]+[^\s<\.)]|[a-zA-Z0-9]+([\-\.]{1}[a-zA-Z0-9]+)*\.[a-zA-Z]{2,63}(:[0-9]{1,5})?(\/[^\s<]*)?)/i';
        
        // Email pattern - refined to be more precise
        $emailPattern = '/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,63})/i';
        
        // Check if the text is just an email and nothing else (without trying to reuse the pattern)
        $trimmedText = trim($text);
        if (preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,63}$/i', $trimmedText)) {
            // This is just an email by itself - apply special formatting
            return '<a href="mailto:' . htmlspecialchars($trimmedText, ENT_QUOTES, 'UTF-8') . '" class="standalone-email-link" onclick="window.open(this.href); return false;">' . htmlspecialchars($trimmedText, ENT_QUOTES, 'UTF-8') . '</a>';
        }
        
        // First replace URLs
        $text = preg_replace_callback($urlPattern, function($matches) {
            $url = $matches[0];
            // Add http:// prefix if it doesn't have a protocol
            if (!preg_match('/^https?:\/\//i', $url)) {
                $fullUrl = 'http://' . $url;
            } else {
                $fullUrl = $url;
            }
            
            return '<a href="' . htmlspecialchars($fullUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer" class="text-primary-500 hover:underline">' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '</a>';
        }, $text);
        
        // Then replace emails with enhanced styling
        $text = preg_replace_callback($emailPattern, function($matches) {
            $email = $matches[0];
            return '<a href="mailto:' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '" class="inline-email-link" onclick="window.open(this.href); return false;">' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '</a>';
        }, $text);
        
        return $text;
    }
@endphp

@section('sidebar')
<div class="h-full flex flex-col bg-white shadow-lg rounded-xl overflow-hidden lg:max-w-xs w-full min-w-0">
    <!-- Sidebar Header -->
    <div class="p-4 border-b border-gray-100 flex items-center sticky top-0 bg-white z-20 shadow-sm">
        <div class="relative w-full">
            <input type="text" class="w-full bg-gray-50 rounded-lg py-2.5 px-4 pl-10 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:bg-white border border-gray-200 text-sm transition-all duration-200" 
                   placeholder="Search conversations..." id="search-contacts">
            <div class="absolute left-3 top-2.5 text-gray-400">
                <i class="fas fa-search text-base"></i>
            </div>
        </div>
    </div>
    <!-- Contact List -->
    <div class="flex-1 overflow-y-auto custom-scrollbar bg-gray-50">
        @if(isset($conversations) && $conversations->count() > 0)
            <div class="divide-y divide-gray-100">
            @foreach($conversations as $conversation)
                @php
                    if ($conversation->is_group) {
                        $displayName = $conversation->name;
                        $avatarUrl = $conversation->avatar ? asset('storage/' . $conversation->avatar) : asset('images/default-avatar.png');
                    } else {
                        $otherParticipant = $conversation->participants->where('user_id', '!=', auth()->id())->first();
                        $displayName = $otherParticipant ? $otherParticipant->user->first_name . ' ' . $otherParticipant->user->last_name : 'Unknown User';
                        $avatarUrl = $otherParticipant && $otherParticipant->user->adminlte_image() 
                            ? $otherParticipant->user->adminlte_image()
                            : asset('images/default-avatar.png');
                        $isOnline = $otherParticipant && $otherParticipant->user->last_seen >= now()->subMinutes(2);
                    }
                    $lastMessage = $conversation->lastMessage;
                    $isActive = isset($activeConversation) && $activeConversation->id === $conversation->id;
                    $unreadCount = $conversation->unreadMessagesCount(auth()->id());
                @endphp
                <a href="{{ route('messaging.show', $conversation->id) }}" 
                   class="contact-item flex items-center gap-3 px-4 py-3 transition-all duration-150 rounded-lg cursor-pointer hover:bg-primary-50/60 {{ $isActive ? 'bg-primary-50/80' : '' }} relative group"
                   data-conversation-id="{{ $conversation->id }}" 
                   data-is-group="{{ $conversation->is_group ? 'true' : 'false' }}"
                   data-active="{{ $isActive ? 'true' : 'false' }}">
                    <div class="relative flex-shrink-0">
                        @if($conversation->is_group)
                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold shadow-sm bg-gradient-to-br from-primary-400 to-primary-600 text-lg">
                                {{ substr($displayName, 0, 2) }}
                            </div>
                        @else
                            <div class="relative">
                                <img src="{{ $avatarUrl }}" loading="lazy" class="w-12 h-12 rounded-full object-cover shadow border-2 border-white" alt="{{ $displayName }}">
                                @if(isset($isOnline) && $isOnline)
                                    <span class="absolute bottom-0 right-0 bg-green-500 h-3 w-3 rounded-full border-2 border-white ring-2 ring-white"></span>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center">
                            <h3 class="text-base font-semibold truncate {{ $isActive ? 'text-primary-600' : 'text-gray-800' }}">{{ $displayName }}</h3>
                            <span class="text-xs text-gray-400 ml-2">
                                {{ $lastMessage ? $lastMessage->created_at->format('g:i A') : '' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-xs {{ $unreadCount > 0 ? 'text-gray-900 font-semibold' : 'text-gray-500' }} truncate max-w-[180px]">
                                @if($lastMessage)
                                    @if($lastMessage->user_id === auth()->id())
                                        <span class="font-medium {{ $isActive ? 'text-primary-600' : 'text-gray-600' }}">You: </span>
                                    @elseif($conversation->is_group)
                                        <span class="font-medium">{{ $lastMessage->user->first_name }} {{ $lastMessage->user->last_name }}: </span>
                                    @endif
                                    @if($lastMessage->attachment)
                                        <i class="fas fa-paperclip mr-1"></i>
                                        {{ $lastMessage->attachment_name ?: 'Attachment' }}
                                    @else
                                        {{ $lastMessage->body }}
                                    @endif
                                @endif
                            </p>
                            @if($unreadCount > 0)
                                <span class="ml-2 flex items-center justify-center bg-primary-500 text-white text-xs font-bold rounded-full h-5 w-5 shadow">{{ $unreadCount }}</span>
                            @elseif($lastMessage && $lastMessage->user_id === auth()->id())
                                <span class="ml-2 text-{{ $lastMessage->is_read ? 'primary' : 'gray' }}-400">
                                    <i class="fas fa-check{{ $lastMessage->is_read ? '-double' : '' }} text-xs"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
            </div>
        @else
            <div class="p-10 text-center text-gray-400 flex flex-col items-center justify-center">
                <div class="mb-4 text-6xl text-gray-200">
                    <i class="far fa-comment-dots"></i>
                </div>
                <p class="font-medium text-lg">No conversations yet.</p>
                <p class="text-sm mt-1">Start a new conversation!</p>
            </div>
        @endif
        <!-- Folders Section -->
        <div class="px-4 pt-5 pb-2 border-t border-gray-100 bg-gray-50">
            <h4 class="text-xs font-bold text-primary-500 uppercase tracking-wider mb-2">Folders</h4>
            <div class="flex flex-col gap-2">
                <button class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-primary-50/60 transition-colors w-full text-left">
                    <span class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-500 text-lg"><i class="fas fa-users"></i></span>
                    <span>
                        <span class="block text-sm font-semibold">All Groups</span>
                        <span class="block text-xs text-gray-400">View all groups</span>
                    </span>
                </button>
                <button class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-purple-50/60 transition-colors w-full text-left">
                    <span class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-500 text-lg"><i class="fas fa-bullhorn"></i></span>
                    <span>
                        <span class="block text-sm font-semibold">Channels</span>
                        <span class="block text-xs text-gray-400">View all channels</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <!-- New Chat Button -->
    <div class="p-4 sticky bottom-0 bg-white border-t border-gray-100 shadow-sm z-10">
        <button class="w-full bg-primary-500 hover:bg-primary-600 active:bg-primary-700 text-white rounded-lg py-2.5 px-4 flex items-center justify-center font-semibold text-base transition-colors duration-200 shadow" 
                id="new-message-btn">
            <i class="fas fa-pen mr-2"></i> New Message
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="h-full flex flex-col bg-gray-50">
    @if(isset($activeConversation))
        @php
            // Determine the conversation display name and avatar
            if ($activeConversation->is_group) {
                $displayName = $activeConversation->name;
                $avatarUrl = $activeConversation->avatar ? asset('storage/' . $activeConversation->avatar) : asset('images/default-avatar.png');
                $status = $activeConversation->participants->count() . ' participants';
            } else {
                // For direct messages, show the other user's name
                $otherParticipant = $activeConversation->participants->where('user_id', '!=', auth()->id())->first();
                $displayName = $otherParticipant ? $otherParticipant->user->first_name . ' ' . $otherParticipant->user->last_name : 'Unknown User';
                $avatarUrl = $otherParticipant && $otherParticipant->user->adminlte_image() 
                    ? $otherParticipant->user->adminlte_image()
                    : asset('images/default-avatar.png');
                $isOnline = $otherParticipant && $otherParticipant->user->last_seen >= now()->subMinutes(2);
                $status = $isOnline ? 'online' : 'offline';
            }
        @endphp
    
        <!-- Chat Header -->
        <div class="bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between shadow-sm sticky top-0 z-10">
            <div class="flex items-center">
                <button class="chat-back-button mr-3 md:hidden text-gray-600 hover:text-gray-800 transition-colors duration-200 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div class="relative mr-3 flex-shrink-0">
                    @if($activeConversation->is_group)
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-medium shadow-sm" 
                             style="background: linear-gradient(to bottom right, #3390ec, #5c6bc0)">
                            {{ substr($displayName, 0, 2) }}
                        </div>
                    @else
                        <div class="relative">
                            <img src="{{ $avatarUrl }}" loading="lazy" class="w-10 h-10 rounded-full object-cover shadow-sm" alt="{{ $displayName }}">
                            @if(!$activeConversation->is_group && isset($isOnline) && $isOnline)
                                <div class="absolute bottom-0 right-0 bg-green-500 h-2.5 w-2.5 rounded-full border-2 border-white"></div>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="min-w-0">
                    <h2 class="font-medium text-gray-800 truncate chat-header-name">{{ $displayName }}</h2>
                    <p class="text-xs {{ $status === 'online' ? 'text-green-600' : 'text-gray-500' }}">
                        @if($status === 'online')
                            <span class="inline-flex items-center">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                                {{ $status }}
                            </span>
                        @else
                            {{ $status }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-1">
                <button class="text-gray-600 hover:text-gray-800 hover:bg-gray-100 w-9 h-9 rounded-full flex items-center justify-center transition-colors duration-200" id="search-messages-btn" aria-label="Search messages">
                    <i class="fas fa-search"></i>
                </button>
                <button class="text-gray-600 hover:text-gray-800 hover:bg-gray-100 w-9 h-9 rounded-full flex items-center justify-center transition-colors duration-200" id="conversation-menu-btn" aria-label="More options">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>
        </div>
        
        <!-- Conversation Context Menu (Hidden by default) -->
        <div id="conversation-context-menu" class="hidden fixed z-50 bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 w-56 text-sm">
            <button id="view-profile-btn" class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center text-gray-700 border-b border-gray-100">
                <i class="fas fa-user mr-3 text-gray-500 w-5"></i> View Profile
            </button>
            <button id="toggle-mute-btn" class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center text-gray-700 border-b border-gray-100">
                <i class="fas fa-bell-slash mr-3 text-gray-500 w-5"></i> <span class="mute-label">Mute Notifications</span>
            </button>
            <button id="shared-files-btn" class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center text-gray-700 border-b border-gray-100">
                <i class="fas fa-file mr-3 text-gray-500 w-5"></i> View Shared Files
            </button>
            <button id="block-user-btn" class="w-full text-left px-4 py-3 hover:bg-red-50 flex items-center text-red-600">
                <i class="fas fa-ban mr-3 w-5"></i> Block User
            </button>
        </div>
        
        <!-- Search Messages Interface (Hidden by default) -->
        <div id="search-messages-container" class="hidden bg-white border-b border-gray-200 p-3">
            <div class="relative">
                <input type="text" id="search-messages-input" class="w-full bg-gray-50 rounded-lg py-2 px-4 pl-10 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white border border-gray-200 text-sm transition-all duration-200" 
                       placeholder="Search in conversation...">
                <div class="absolute left-3 top-2.5 text-gray-500">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <button id="close-search-btn" class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="search-results" class="mt-2 max-h-72 overflow-y-auto hidden">
                <div class="text-xs text-gray-500 px-2 py-1">Searching...</div>
            </div>
        </div>
        
        <!-- Chat Messages Area -->
        <div class="flex-1 overflow-y-auto overflow-x-hidden p-4 space-y-3" id="chat-messages">
            <!-- Date Separator -->
            <div class="day-separator">
                <span class="bg-white px-4 py-1 text-xs text-gray-500 rounded-full border border-gray-200 shadow-sm">
                    Today
                </span>
            </div>
            
            <!-- System Message -->
            <div class="flex justify-center my-4">
                <div class="bg-gray-100 text-gray-500 text-xs px-4 py-2 rounded-lg text-center max-w-xs">
                    Messages and calls are end-to-end encrypted. No one outside of this chat can read or listen to them.
                </div>
            </div>
            
            @php
                function isSingleEmoji(
                    $text
                ) {
                    // Remove whitespace
                    $trimmed = trim($text);
                    // Regex for a single emoji (unicode)
                    // This is a simplified version and may not cover all emoji edge cases
                    return preg_match('/^\p{Emoji}+$/u', $trimmed) && (mb_strlen($trimmed) <= 3);
                }
            @endphp
            
            @if($activeConversation->messages->count() > 0)
                @php
                    $lastDate = null;
                    $messages = $activeConversation->messages()->with('user')->orderBy('created_at', 'asc')->get();
                @endphp
                
                @foreach($messages as $message)
                    @php
                        $currentDate = $message->created_at->format('Y-m-d');
                        $showDateSeparator = $lastDate !== $currentDate;
                        $lastDate = $currentDate;
                        $isMine = $message->user_id === auth()->id();
                        $userName = $message->user->first_name . ' ' . $message->user->last_name;
                        $userAvatar = $message->user->adminlte_image() 
                            ? $message->user->adminlte_image()
                            : asset('images/default-avatar.png');
                        $isToday = $message->created_at->isToday();
                    @endphp
                    
                    @if($showDateSeparator)
                        <div class="day-separator">
                            <span class="bg-white px-4 py-1 text-xs text-gray-500 rounded-full border border-gray-200 shadow-sm">
                                {{ $isToday ? 'Today' : $message->created_at->format('F j, Y') }}
                            </span>
                        </div>
                    @endif
                    
                    @if($message->is_system)
                        <!-- System message -->
                        <div class="flex justify-center my-3 message-container" data-message-id="{{ $message->id }}">
                            <div class="bg-gray-100 text-gray-600 text-xs px-4 py-2 rounded-full text-center max-w-sm system-message">
                                @if($message->system_action == 'user_added' && $message->affectedUser)
                                    <span class="font-medium">{{ $message->user->first_name }} {{ $message->user->last_name }}</span> 
                                    added 
                                    <span class="font-medium">{{ $message->affectedUser->first_name }} {{ $message->affectedUser->last_name }}</span> 
                                    to the group
                                @elseif($message->system_action == 'group_created')
                                    <i class="fas fa-users mr-1"></i> {{ $message->body ?: 'Group created' }}
                                @else
                                    {{ $message->getFormattedSystemMessage() }}
                                @endif
                            </div>
                        </div>
                    @elseif($isMine)
                        <!-- My message -->
                        <div class="flex items-start flex-row-reverse mb-4 group message-container" data-message-id="{{ $message->id }}">
                            <div class="relative max-w-[85%] md:max-w-[70%] lg:max-w-[60%]">
                                @if($message->attachment)
                                    @php
                                        $attachmentUrl = asset('storage/' . $message->attachment);
                                        $isImage = Str::startsWith($message->attachment_type, 'image/');
                                        $isPdf = $message->attachment_type === 'application/pdf';
                                    @endphp
                                    
                                    <div class="message-bubble-mine px-4 py-3 text-sm break-words">
                                        @if($message->reply_to)
                                            <div class="quoted-message mb-2 p-2 rounded bg-white bg-opacity-60 border-l-2 border-primary-400 text-xs text-gray-600">
                                                @php
                                                    $replyToMessage = \App\Models\Message::with('user')->find($message->reply_to);
                                                @endphp
                                                @if($replyToMessage)
                                                    <div class="font-medium text-primary-600">{{ $replyToMessage->user->first_name }} {{ $replyToMessage->user->last_name }}</div>
                                                    <div class="truncate">{{ $replyToMessage->attachment ? ($replyToMessage->attachment_name ?: 'Attachment') : Str::limit($replyToMessage->body, 100) }}</div>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        @if($isImage)
                                            <div class="message-image-container" onclick="openImageModal('{{ $attachmentUrl }}')">
                                                <img src="{{ $attachmentUrl }}" loading="lazy" class="message-image rounded-lg max-w-full" alt="Image">
                                            </div>
                                        @else
                                            <div class="flex items-center bg-white bg-opacity-80 p-3 rounded-lg shadow-sm cursor-pointer file-attachment-item" 
                                                 data-file-url="{{ $attachmentUrl }}" 
                                                 data-file-name="{{ $message->attachment_name }}" 
                                                 data-file-type="{{ $isPdf ? 'application/pdf' : 'application/octet-stream' }}"
                                                 data-message-id="{{ $message->id }}">
                                                <i class="fas fa-{{ $isPdf ? 'file-pdf text-red-500' : 'file text-primary-500' }} text-xl mr-3"></i>
                                                <div class="overflow-hidden">
                                                    <div class="font-medium text-gray-800 truncate">{{ $message->attachment_name }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $isPdf ? 'PDF Document' : 'File' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($message->body)
                                            <div class="mt-2 break-words{{ isset($message->body) && isSingleEmoji($message->body) ? ' single-emoji' : '' }}">{!! isSingleEmoji($message->body) ? $message->body : formatMessageLinks($message->body) !!}</div>
                                        @endif
                                    </div>
                                @else
                                    <div class="message-bubble-mine px-4 py-3 text-sm break-words">
                                        @if($message->reply_to)
                                            <div class="quoted-message mb-2 p-2 rounded bg-white bg-opacity-70 border-l-2 border-primary-400 text-xs text-gray-600 shadow-sm">
                                                @php
                                                    $replyToMessage = \App\Models\Message::with('user')->find($message->reply_to);
                                                @endphp
                                                @if($replyToMessage)
                                                    <div class="font-medium text-primary-600">{{ $replyToMessage->user->first_name }} {{ $replyToMessage->user->last_name }}</div>
                                                    <div class="truncate">{{ $replyToMessage->attachment ? ($replyToMessage->attachment_name ?: 'Attachment') : Str::limit($replyToMessage->body, 100) }}</div>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="message-text break-words{{ isset($message->body) && isSingleEmoji($message->body) ? ' single-emoji' : '' }}">{!! isSingleEmoji($message->body) ? $message->body : formatMessageLinks($message->body) !!}</div>
                                    </div>
                                @endif
                                
                                <!-- Message actions menu -->
                                <div class="absolute right-0 top-0 mt-2 -mr-10 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <button class="message-action-btn w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-500 hover:text-gray-700 transition-colors duration-200"
                                            data-message-id="{{ $message->id }}" 
                                            onclick="showMessageActions(this, event)">
                                        <i class="fas fa-ellipsis-v text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="text-xs text-gray-400 mt-1 mr-1 text-right flex items-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                {{ $message->created_at->format('g:i A') }}
                                <i class="fas fa-check{{ $message->is_read ? '-double text-primary-500' : '' }} ml-1 text-xs"></i>
                            </div>
                        </div>
                    @else
                        <!-- Message from other user -->
                        <div class="flex items-start mb-4 group message-container" data-message-id="{{ $message->id }}">
                            <div class="flex-shrink-0 mr-2 mt-1">
                                <img src="{{ $userAvatar }}" loading="lazy" class="w-8 h-8 rounded-full object-cover shadow-sm" alt="{{ $userName }}">
                            </div>
                            <div class="max-w-[85%] md:max-w-[70%] lg:max-w-[60%] relative">
                                @if($activeConversation->is_group)
                                    <div class="text-xs font-medium text-primary-500 mb-1">{{ $userName }}</div>
                                @endif
                                
                                @if($message->attachment)
                                    @php
                                        $attachmentUrl = asset('storage/' . $message->attachment);
                                        $isImage = Str::startsWith($message->attachment_type, 'image/');
                                        $isPdf = $message->attachment_type === 'application/pdf';
                                    @endphp
                                    
                                    <div class="message-bubble-other px-4 py-3 text-sm text-gray-800 break-words">
                                        @if($message->reply_to)
                                            <div class="quoted-message mb-2 p-2 rounded bg-gray-100 border-l-2 border-primary-400 text-xs text-gray-600 shadow-sm">
                                                @php
                                                    $replyToMessage = \App\Models\Message::with('user')->find($message->reply_to);
                                                @endphp
                                                @if($replyToMessage)
                                                    <div class="font-medium text-primary-600">{{ $replyToMessage->user->first_name }} {{ $replyToMessage->user->last_name }}</div>
                                                    <div class="truncate">{{ $replyToMessage->attachment ? ($replyToMessage->attachment_name ?: 'Attachment') : Str::limit($replyToMessage->body, 100) }}</div>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        @if($isImage)
                                            <div class="message-image-container" onclick="openImageModal('{{ $attachmentUrl }}')">
                                                <img src="{{ $attachmentUrl }}" loading="lazy" class="message-image rounded-lg max-w-full" alt="Image">
                                            </div>
                                        @else
                                            <div class="flex items-center bg-white bg-opacity-80 p-3 rounded-lg shadow-sm cursor-pointer file-attachment-item" 
                                                 data-file-url="{{ $attachmentUrl }}" 
                                                 data-file-name="{{ $message->attachment_name }}" 
                                                 data-file-type="{{ $isPdf ? 'application/pdf' : 'application/octet-stream' }}"
                                                 data-message-id="{{ $message->id }}">
                                                <i class="fas fa-{{ $isPdf ? 'file-pdf text-red-500' : 'file text-primary-500' }} text-xl mr-3"></i>
                                                <div class="overflow-hidden">
                                                    <div class="font-medium text-gray-800 truncate">{{ $message->attachment_name }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $isPdf ? 'PDF Document' : 'File' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($message->body)
                                            <div class="mt-2 break-words{{ isset($message->body) && isSingleEmoji($message->body) ? ' single-emoji' : '' }}">{!! isSingleEmoji($message->body) ? $message->body : formatMessageLinks($message->body) !!}</div>
                                        @endif
                                    </div>
                                @else
                                    <div class="message-bubble-other px-4 py-3 text-sm text-gray-800 break-words">
                                        @if($message->reply_to)
                                            <div class="quoted-message mb-2 p-2 rounded bg-gray-100 border-l-2 border-primary-400 text-xs text-gray-600 shadow-sm">
                                                @php
                                                    $replyToMessage = \App\Models\Message::with('user')->find($message->reply_to);
                                                @endphp
                                                @if($replyToMessage)
                                                    <div class="font-medium text-primary-600">{{ $replyToMessage->user->first_name }} {{ $replyToMessage->user->last_name }}</div>
                                                    <div class="truncate">{{ $replyToMessage->attachment ? ($replyToMessage->attachment_name ?: 'Attachment') : Str::limit($replyToMessage->body, 100) }}</div>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="message-text break-words{{ isset($message->body) && isSingleEmoji($message->body) ? ' single-emoji' : '' }}">{!! isSingleEmoji($message->body) ? $message->body : formatMessageLinks($message->body) !!}</div>
                                    </div>
                                @endif
                                
                                <!-- Message actions menu -->
                                <div class="absolute right-0 top-0 mt-2 -mr-10 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <button class="message-action-btn w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-500 hover:text-gray-700 transition-colors duration-200"
                                            data-message-id="{{ $message->id }}" 
                                            onclick="showMessageActions(this, event)">
                                        <i class="fas fa-ellipsis-v text-xs"></i>
                                    </button>
                                </div>
                                
                                <div class="text-xs text-gray-400 mt-1 ml-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    {{ $message->created_at->format('g:i A') }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="flex justify-center items-center h-64">
                    <div class="text-gray-500 text-center">
                        <div class="text-6xl mb-4"><i class="far fa-comments"></i></div>
                        <p>No messages yet</p>
                        <p class="text-sm mt-1">Start the conversation!</p>
                    </div>
                </div>
            @endif
            
            <!-- Typing Indicator -->
            <div id="typing-indicator" class="flex items-start mb-4" style="display: none;">
                <div class="flex-shrink-0 mr-2 mt-1">
                    <div class="w-8 h-8 rounded-full bg-gray-200"></div>
                </div>
                <div class="typing-indicator">
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                    <div class="typing-name text-xs text-gray-500 ml-2 opacity-75"></div>
                </div>
            </div>
        </div>
        
        <!-- Chat Input -->
        <div class="bg-white border-t border-gray-200 p-3 sticky bottom-0 shadow-sm">
            <form action="{{ route('messaging.store-message', $activeConversation->id) }}" method="POST" enctype="multipart/form-data" id="message-form" class="relative">
                @csrf
                
                <!-- Reply Preview -->
                <div id="reply-preview" class="hidden mb-2 p-2 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-medium text-primary-600" id="reply-user-name"></div>
                            <div class="text-xs text-gray-600 truncate" id="reply-text"></div>
                        </div>
                        <button type="button" id="cancel-reply" class="text-gray-500 hover:text-red-500 w-6 h-6 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors duration-200 flex-shrink-0 ml-2">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Attachment Preview -->
                <div id="attachment-preview" class="hidden mb-2">
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div id="attachment-preview-icon" class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-file text-gray-500 text-xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div id="attachment-preview-name" class="text-sm font-medium text-gray-700 truncate"></div>
                                    <div id="attachment-preview-size" class="text-xs text-gray-500"></div>
                                </div>
                            </div>
                            <button type="button" id="remove-attachment" class="text-gray-500 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- Image Preview -->
                        <div id="image-preview-container" class="hidden mt-2">
                            <img id="image-preview" class="max-h-40 rounded-lg object-contain" src="" alt="Preview">
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <div class="relative">
                        <button type="button" id="emoji-button" class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 w-10 h-10 flex items-center justify-center rounded-full transition-colors duration-200">
                            <i class="far fa-smile text-xl"></i>
                        </button>
                        
                        <!-- Emoji Hover Menu -->
                        <div id="emoji-hover-menu" class="emoji-hover-menu">
                            <!-- Frequently used emojis will be loaded here -->
                            <div class="emoji-item">😀</div>
                            <div class="emoji-item">😂</div>
                            <div class="emoji-item">❤️</div>
                            <div class="emoji-item">👍</div>
                            <div class="emoji-item">🙏</div>
                            <div class="emoji-item">🔥</div>
                            <div class="emoji-item">🎉</div>
                            <div class="emoji-item">😍</div>
                            <div class="emoji-item">😊</div>
                            <div class="emoji-item">🤔</div>
                            <div class="emoji-item">👌</div>
                            <div class="emoji-item">👏</div>
                            <div class="emoji-item">😭</div>
                            <div class="emoji-item">🥰</div>
                            <div class="emoji-item">😎</div>
                            <div class="emoji-item">🤗</div>
                            <div class="emoji-item">🤣</div>
                            <div class="emoji-item">😇</div>
                            <div class="emoji-item">😘</div>
                            <div class="emoji-item">🙄</div>
                        </div>
                    </div>
                    <div class="relative flex-1 mx-2">
                        <textarea 
                            name="message"
                            class="w-full border border-gray-200 rounded-lg py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none text-sm transition-all duration-200"
                            placeholder="Type a message..."
                            rows="1"
                            id="message-input"
                            style="max-height: 120px; min-height: 42px;"
                        ></textarea>
                        <div class="absolute right-2 bottom-2 flex">
                            <label for="attachment" class="text-gray-400 hover:text-gray-600 w-8 h-8 flex items-center justify-center cursor-pointer rounded-full hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-paperclip"></i>
                                <input type="file" id="attachment" name="attachment" class="hidden">
                            </label>
                        </div>
                    </div>
                    <button type="button" id="microphone-btn" class="bg-primary-500 hover:bg-primary-600 active:bg-primary-700 text-white rounded-full w-10 h-10 flex items-center justify-center transition-colors duration-200 shadow-sm mx-2">
                        <i class="fas fa-microphone"></i>
                    </button>
                    <button type="submit" id="send-message-btn" class="bg-primary-500 hover:bg-primary-600 active:bg-primary-700 text-white rounded-full w-10 h-10 flex items-center justify-center transition-colors duration-200 shadow-sm mx-2" style="display: none;">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                
                <!-- Hidden reply_to field -->
                <input type="hidden" name="reply_to" id="reply_to" value="">
            </form>
        </div>
        
        <!-- Emoji Sidebar -->
        <div id="emoji-sidebar">
            <div class="emoji-sidebar-header">
                <h3 class="font-medium text-gray-800">Emoji & Stickers</h3>
                <button id="close-emoji-sidebar" class="text-gray-500 hover:text-gray-700 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="emoji-sidebar-tabs">
                <div class="emoji-tab active" data-tab="emojis">
                    <i class="far fa-smile mr-1"></i> Emojis
                </div>
                <div class="emoji-tab" data-tab="stickers">
                    <i class="fas fa-sticky-note mr-1"></i> Stickers
                </div>
                <div class="emoji-tab" data-tab="gifs">
                    <i class="fas fa-film mr-1"></i> GIFs
                </div>
            </div>
            
            <div class="emoji-search">
                <input type="text" placeholder="Search..." id="emoji-search-input">
            </div>
            
            <div class="emoji-categories">
                <div class="emoji-category active" data-category="recent">Recent</div>
                <div class="emoji-category" data-category="smileys">Smileys</div>
                <div class="emoji-category" data-category="people">People</div>
                <div class="emoji-category" data-category="animals">Animals</div>
                <div class="emoji-category" data-category="food">Food</div>
                <div class="emoji-category" data-category="travel">Travel</div>
                <div class="emoji-category" data-category="activities">Activities</div>
                <div class="emoji-category" data-category="objects">Objects</div>
                <div class="emoji-category" data-category="symbols">Symbols</div>
                <div class="emoji-category" data-category="flags">Flags</div>
            </div>
            
            <div class="emoji-content" id="emojis-tab-content">
                <!-- Emojis content -->
                <div class="emoji-group" id="recent-emojis">
                    <div class="emoji-group-title">Recently Used</div>
                    <div class="emoji-grid">
                        <div class="emoji-item">😀</div>
                        <div class="emoji-item">😂</div>
                        <div class="emoji-item">❤️</div>
                        <div class="emoji-item">👍</div>
                        <div class="emoji-item">🙏</div>
                        <div class="emoji-item">🔥</div>
                    </div>
                </div>
                
                <div class="emoji-group" id="smileys-emojis">
                    <div class="emoji-group-title">Smileys & Emotion</div>
                    <div class="emoji-grid">
                        <div class="emoji-item">😀</div>
                        <div class="emoji-item">😁</div>
                        <div class="emoji-item">😂</div>
                        <div class="emoji-item">🤣</div>
                        <div class="emoji-item">😃</div>
                        <div class="emoji-item">😄</div>
                        <div class="emoji-item">😅</div>
                        <div class="emoji-item">😆</div>
                        <div class="emoji-item">😉</div>
                        <div class="emoji-item">😊</div>
                        <div class="emoji-item">😋</div>
                        <div class="emoji-item">😎</div>
                        <div class="emoji-item">😍</div>
                        <div class="emoji-item">😘</div>
                        <div class="emoji-item">😗</div>
                        <div class="emoji-item">😙</div>
                        <div class="emoji-item">😚</div>
                        <div class="emoji-item">🙂</div>
                        <div class="emoji-item">🤗</div>
                        <div class="emoji-item">🤔</div>
                        <div class="emoji-item">😐</div>
                        <div class="emoji-item">😑</div>
                        <div class="emoji-item">😶</div>
                        <div class="emoji-item">🙄</div>
                        <!-- More emojis would be added here -->
                    </div>
                </div>
                
                <!-- More emoji groups would be added here -->
            </div>
            
            <div class="emoji-content hidden" id="stickers-tab-content">
                <!-- Stickers content -->
                <div class="sticker-grid">
                    <div class="sticker-item">
                        <img src="https://c.tenor.com/Ig9L8nyRKbgAAAAj/cat-gray.gif" alt="Sticker">
                    </div>
                    <div class="sticker-item">
                        <img src="https://c.tenor.com/I3RJ5kVPj5oAAAAj/peach-goma.gif" alt="Sticker">
                    </div>
                    <div class="sticker-item">
                        <img src="https://c.tenor.com/xzHubN8aEpAAAAAj/backhand-index-pointing-right-joypixels.gif" alt="Sticker">
                    </div>
                    <div class="sticker-item">
                        <img src="https://c.tenor.com/QA6mPKs100MAAAAj/tkthao219-bubududu.gif" alt="Sticker">
                    </div>
                    <div class="sticker-item">
                        <img src="https://c.tenor.com/s-XyqNCtm3cAAAAj/dm4uz3-foekoe.gif" alt="Sticker">
                    </div>
                    <div class="sticker-item">
                        <img src="https://c.tenor.com/WwIUw3YlVwUAAAAj/brown-cony.gif" alt="Sticker">
                    </div>
                    <!-- More stickers would be added here -->
                </div>
            </div>
            
            <div class="emoji-content hidden" id="gifs-tab-content">
                <!-- GIFs content -->
                <div class="gif-search mb-4">
                    <input type="text" placeholder="Search GIFs..." id="gif-search-input" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div class="gif-grid">
                    <div class="gif-item">
                        <img src="https://media2.giphy.com/media/3oEjHFOscgNwdSRRDy/giphy.gif" alt="GIF">
                    </div>
                    <div class="gif-item">
                        <img src="https://media2.giphy.com/media/Cmr1OMJ2FN0B2/giphy.gif" alt="GIF">
                    </div>
                    <div class="gif-item">
                        <img src="https://media1.giphy.com/media/GeimqsH0TLDt4tScGw/giphy.gif" alt="GIF">
                    </div>
                    <div class="gif-item">
                        <img src="https://media0.giphy.com/media/3oz8xRF0v9WMAUVLNK/giphy.gif" alt="GIF">
                    </div>
                    <!-- More GIFs would be added here -->
                </div>
            </div>
        </div>
        
        <!-- Emoji Sidebar Overlay -->
        <div id="emoji-sidebar-overlay"></div>
        
        <!-- Message Actions Menu (Hidden by default) -->
        <div id="message-actions-menu" class="hidden fixed z-50 bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 w-48 text-sm">
            <button id="action-reply" class="w-full text-left px-4 py-2.5 hover:bg-gray-50 flex items-center text-gray-700">
                <i class="fas fa-reply mr-3 text-gray-500"></i> Reply
            </button>
            <button id="action-copy" class="w-full text-left px-4 py-2.5 hover:bg-gray-50 flex items-center text-gray-700">
                <i class="fas fa-copy mr-3 text-gray-500"></i> Copy Text
            </button>
            <button id="action-forward" class="w-full text-left px-4 py-2.5 hover:bg-gray-50 flex items-center text-gray-700">
                <i class="fas fa-share mr-3 text-gray-500"></i> Forward
            </button>
            <div class="border-t border-gray-200"></div>
            <button id="action-delete" class="w-full text-left px-4 py-2.5 hover:bg-red-50 flex items-center text-red-600">
                <i class="fas fa-trash-alt mr-3"></i> Delete
            </button>
        </div>
    @else
        <!-- No active conversation -->
        <div class="flex items-center justify-center h-full">
            <div class="text-center p-8 max-w-md mx-auto">
                <div class="text-6xl text-gray-300 mb-6">
                    <i class="far fa-comments"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-700 mb-2">Your Messages</h3>
                <p class="text-gray-500 mb-8">Send private messages to other users</p>
                <button id="start-conversation-btn" class="bg-primary-500 hover:bg-primary-600 active:bg-primary-700 text-white py-2.5 px-6 rounded-lg shadow-sm transition-colors duration-200">
                    Start a conversation
                </button>
            </div>
        </div>
    @endif
</div>

<!-- New Conversation Modal -->
<div id="new-conversation-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden backdrop-blur-sm">
    <div class="bg-white rounded-lg w-full max-w-md mx-4 shadow-xl animate-fade-in">
        <div class="border-b border-gray-200 px-4 py-3 flex justify-between items-center">
            <h3 class="font-medium">New Message</h3>
            <button type="button" class="text-gray-500 hover:text-gray-700 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors duration-200" id="close-modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <form id="new-conversation-form" action="{{ route('messaging.create-conversation') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">To:</label>
                    <div class="flex flex-wrap gap-2 mb-2 border border-gray-200 p-2 rounded-md min-h-[42px]" id="selected-users-container"></div>
                    <input type="text" id="user-search" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm mt-2 transition-all duration-200" placeholder="Type a name or email">
                    <div id="user-search-results" class="mt-2 border border-gray-200 rounded-md max-h-48 overflow-y-auto hidden shadow-sm"></div>
                </div>
                
                <div class="mb-4" id="group-name-container" style="display: none;">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Group Name:</label>
                    <input type="text" name="name" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm transition-all duration-200" placeholder="Enter group name">
                </div>
                
                <input type="hidden" name="is_group" id="is_group" value="0">
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-primary-500 hover:bg-primary-600 active:bg-primary-700 text-white py-2 px-4 rounded-md transition-colors duration-200 shadow-sm">
                        Start Chat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Profile Modal -->
<div id="user-profile-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden backdrop-blur-sm">
    <div class="bg-white rounded-lg w-full max-w-md mx-4 shadow-xl animate-fade-in">
        <div class="border-b border-gray-200 px-4 py-3 flex justify-between items-center">
            <h3 class="font-medium">User Profile</h3>
            <button type="button" class="text-gray-500 hover:text-gray-700 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors duration-200" id="close-profile-modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <div class="flex flex-col items-center mb-4">
                <div class="w-24 h-24 rounded-full overflow-hidden mb-3">
                    <img id="profile-avatar" src="{{ asset('images/default-avatar.png') }}" class="w-full h-full object-cover" alt="User">
                </div>
                <h2 id="profile-name" class="text-xl font-bold"></h2>
                <p id="profile-status" class="text-sm text-gray-500"></p>
            </div>
            
            <div class="border-t border-gray-100 pt-4">
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 mr-3">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Email</p>
                            <p id="profile-email" class="text-sm"></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-500 mr-3">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Phone</p>
                            <p id="profile-phone" class="text-sm"></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-500 mr-3">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Department</p>
                            <p id="profile-department" class="text-sm"></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-500 mr-3">
                            <i class="fas fa-id-badge"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Position</p>
                            <p id="profile-position" class="text-sm"></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex space-x-2">
                <button type="button" id="view-full-profile" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-md transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-user mr-2"></i> Full Profile
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Shared Files Modal -->
<div id="shared-files-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden backdrop-blur-sm">
    <div class="bg-white rounded-lg w-full max-w-2xl mx-4 shadow-xl animate-fade-in">
        <div class="border-b border-gray-200 px-4 py-3 flex justify-between items-center">
            <h3 class="font-medium">Shared Files</h3>
            <button type="button" class="text-gray-500 hover:text-gray-700 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors duration-200" id="close-files-modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-4">
                <ul class="flex -mb-px">
                    <li class="mr-2">
                        <button class="files-tab-btn active inline-block py-2 px-4 text-primary-500 border-b-2 border-primary-500 font-medium" data-tab="media">
                            <i class="fas fa-image mr-1"></i> Media
                        </button>
                    </li>
                    <li class="mr-2">
                        <button class="files-tab-btn inline-block py-2 px-4 text-gray-500 hover:text-gray-700 border-b-2 border-transparent font-medium" data-tab="documents">
                            <i class="fas fa-file mr-1"></i> Documents
                        </button>
                    </li>
                    <li>
                        <button class="files-tab-btn inline-block py-2 px-4 text-gray-500 hover:text-gray-700 border-b-2 border-transparent font-medium" data-tab="links">
                            <i class="fas fa-link mr-1"></i> Links
                        </button>
                    </li>
                </ul>
            </div>
            
            <!-- Media Tab Content -->
            <div id="media-tab" class="files-tab-content">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="media-grid">
                    <!-- Media files will be loaded here -->
                    <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                        <i class="fas fa-spinner fa-spin text-2xl"></i>
                    </div>
                </div>
                <div id="media-empty" class="hidden text-center py-8 text-gray-500">
                    <i class="fas fa-photo-video text-4xl mb-2"></i>
                    <p>No media files shared in this conversation</p>
                </div>
            </div>
            
            <!-- Documents Tab Content -->
            <div id="documents-tab" class="files-tab-content hidden">
                <div class="space-y-3" id="documents-list">
                    <!-- Documents will be loaded here -->
                    <div class="bg-gray-50 p-3 rounded-lg flex items-center">
                        <div class="flex-shrink-0 w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <div class="h-4 bg-gray-200 rounded w-2/3 mb-1"></div>
                            <div class="h-3 bg-gray-200 rounded w-1/3"></div>
                        </div>
                    </div>
                </div>
                <div id="documents-empty" class="hidden text-center py-8 text-gray-500">
                    <i class="fas fa-file-alt text-4xl mb-2"></i>
                    <p>No documents shared in this conversation</p>
                </div>
            </div>
            
            <!-- Links Tab Content -->
            <div id="links-tab" class="files-tab-content hidden">
                <div class="space-y-3" id="links-list">
                    <!-- Links will be loaded here -->
                    <div class="bg-gray-50 p-3 rounded-lg flex items-center">
                        <div class="flex-shrink-0 w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <div class="h-4 bg-gray-200 rounded w-2/3 mb-1"></div>
                            <div class="h-3 bg-gray-200 rounded w-1/3"></div>
                        </div>
                    </div>
                </div>
                <div id="links-empty" class="hidden text-center py-8 text-gray-500">
                    <i class="fas fa-link text-4xl mb-2"></i>
                    <p>No links shared in this conversation</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Fullscreen Modal -->
<div id="imageModal" class="image-modal">
    <span class="close-modal" onclick="closeImageModal()">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<!-- File Viewer Modal (Google Drive-like) -->
<div id="fileViewerModal" class="file-viewer-modal">
    <div class="file-viewer-header">
        <div class="file-viewer-header-left">
            <div class="file-viewer-icon" id="file-viewer-icon">
                <i class="fas fa-file"></i>
            </div>
            <div class="file-viewer-title" id="file-viewer-title">File Name</div>
        </div>
        <div class="file-viewer-actions">
            <button class="file-viewer-action-btn" id="file-viewer-print" title="Print">
                <i class="fas fa-print"></i>
            </button>
            <a href="#" class="file-viewer-action-btn" id="file-viewer-download" download title="Download">
                <i class="fas fa-download"></i>
            </a>
            <button class="file-viewer-action-btn" id="file-viewer-close" title="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="file-viewer-content" id="file-viewer-content">
        <div class="file-viewer-loading" id="file-viewer-loading">
            <div class="file-viewer-spinner"></div>
        </div>
        
        <!-- Content will be dynamically inserted here -->
    </div>
</div>

@include('messaging.partials.scripts')
@include('messaging.partials.styles')

@endsection