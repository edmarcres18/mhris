/**
 * Messaging functionality for the MHRIS messaging feature
 * This class handles all the real-time features of the messaging system
 */
class Messaging {
    constructor(userId) {
        this.userId = userId;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        this.activeConversationId = null;
        this.messageForm = document.getElementById('message-form');
        this.messageInput = document.getElementById('message-input');
        this.chatMessages = document.getElementById('chat-messages');
        this.typingTimeout = null;
        this.attachmentInput = document.getElementById('attachment');
        
        this.initialize();
    }
    
    /**
     * Initialize the messaging functionality
     */
    initialize() {
        // Set up message form submission
        if (this.messageForm) {
            this.messageForm.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }
        
        // Set up typing indicator
        if (this.messageInput) {
            this.messageInput.addEventListener('input', () => this.handleTyping());
        }
        
        // Auto-resize the textarea
        if (this.messageInput) {
            this.messageInput.addEventListener('input', () => {
                this.messageInput.style.height = 'auto';
                this.messageInput.style.height = Math.min(this.messageInput.scrollHeight, 120) + 'px';
            });
        }
        
        // Get the active conversation ID from the URL if available
        const pathParts = window.location.pathname.split('/');
        if (pathParts.length > 2 && pathParts[1] === 'messaging') {
            const conversationId = pathParts[2];
            if (!isNaN(conversationId)) {
                this.activeConversationId = conversationId;
                this.joinConversation(conversationId);
                this.markMessagesAsRead();
            }
        }
        
        // Set up scroll event to mark messages as read when they become visible
        if (this.chatMessages) {
            this.chatMessages.addEventListener('scroll', () => this.handleScroll());
        }
    }
    
    /**
     * Join a conversation channel
     * @param {number} conversationId - The ID of the conversation to join
     */
    joinConversation(conversationId) {
        // Leave previous conversation channel if exists
        if (this.activeConversationId && this.activeConversationId !== conversationId) {
            this.leaveConversation();
        }
        
        this.activeConversationId = conversationId;
        
        // If Echo is available, set up real-time listeners
        if (typeof window.Echo !== 'undefined') {
        window.Echo.private(`chat.${conversationId}`)
            .listen('.message.sent', (data) => {
                this.handleNewMessage(data);
            })
            .listen('.message.read', (data) => {
                this.handleMessageRead(data);
            })
            .listen('.user.typing', (data) => {
                this.handleUserTyping(data);
            });
        }
    }
    
    /**
     * Leave the current conversation channel
     */
    leaveConversation() {
        if (this.activeConversationId && typeof window.Echo !== 'undefined') {
            window.Echo.leave(`chat.${this.activeConversationId}`);
        }
        this.activeConversationId = null;
    }
    
    /**
     * Handle form submission
     * @param {Event} e - The submit event
     */
    handleFormSubmit(e) {
        // If using regular form submission, don't prevent default
        // If using AJAX, prevent default and handle manually
        if (e.submitter.dataset.ajax === 'true') {
            e.preventDefault();
            this.sendMessage();
        }
    }
    
    /**
     * Send a message via AJAX
     */
    sendMessage() {
        if (!this.activeConversationId) return;
        
        const formData = new FormData(this.messageForm);
        
        fetch(`/api/messages/send`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': this.csrfToken,
                'Accept': 'application/json',
            },
            body: formData,
            credentials: 'same-origin',
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear the input field
                this.messageInput.value = '';
                this.messageInput.style.height = 'auto';
                
                // Clear the attachment
                if (this.attachmentInput) {
                    this.attachmentInput.value = '';
                    const attachmentPreview = document.getElementById('attachment-preview');
                    if (attachmentPreview) {
                        attachmentPreview.classList.add('hidden');
                    }
                }
                
                // If we're not using real-time updates, append the message manually
                if (typeof window.Echo === 'undefined') {
                    this.appendMessage(data.message);
                }
                
                // Reset the typing indicator
                this.sendTypingIndicator(false);
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
        });
    }
    
    /**
     * Handle new message received
     * @param {Object} data - The message data
     */
    handleNewMessage(data) {
        // Don't handle messages from self as they're already in the UI
        if (data.user_id === this.userId) return;
        
        this.appendMessage(data);
        this.markMessageAsRead(data.id);
    }
    
    /**
     * Append a message to the chat
     * @param {Object} message - The message object
     */
    appendMessage(message) {
        if (!this.chatMessages) return;
        
        const isMine = message.user_id === this.userId;
        const dateFormatted = this.formatDate(new Date(message.created_at));
        
        // Check if we need to add a date separator
        const lastDateSeparator = this.chatMessages.querySelector('.date-separator:last-child');
        const lastDateValue = lastDateSeparator ? lastDateSeparator.dataset.date : null;
        
        if (!lastDateValue || lastDateValue !== dateFormatted.date) {
            const dateSeparator = document.createElement('div');
            dateSeparator.className = 'flex justify-center my-4 date-separator';
            dateSeparator.dataset.date = dateFormatted.date;
            dateSeparator.innerHTML = `
                <div class="bg-white border border-gray-200 text-gray-500 text-xs px-4 py-1 rounded-lg shadow-sm">
                    ${dateFormatted.formatted}
                </div>
            `;
            this.chatMessages.appendChild(dateSeparator);
        }
        
        const messageElement = document.createElement('div');
        
        if (isMine) {
            // My message
            messageElement.className = 'flex items-start flex-row-reverse mb-4 max-w-xs md:max-w-md ml-auto group';
            messageElement.dataset.messageId = message.id;
            
            if (message.attachment) {
                const isImage = message.attachment_type && message.attachment_type.startsWith('image/');
                const isPdf = message.attachment_type === 'application/pdf';
                
                messageElement.innerHTML = `
                    <div class="message-bubble-mine px-3 py-2 text-sm">
                        ${isImage ? `
                            <a href="${message.attachment}" target="_blank" class="block">
                                <img src="${message.attachment}" class="rounded-lg max-w-[250px]" alt="Image">
                            </a>
                        ` : `
                            <div class="flex items-center">
                                <i class="fas fa-${isPdf ? 'file-pdf text-red-500' : 'file text-blue-500'} text-xl mr-3"></i>
                                <div>
                                    <div class="font-medium">${message.attachment_name || 'Attachment'}</div>
                                    <div class="text-xs text-gray-400">
                                        ${isPdf ? 'PDF Document' : 'File'}
                                    </div>
                                </div>
                            </div>
                        `}
                        ${message.body ? `<div class="mt-2">${message.body}</div>` : ''}
                    </div>
                    <div class="text-xs text-gray-400 mt-1 mr-1 text-right flex items-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        ${dateFormatted.time}
                        <i class="fas fa-check${message.is_read ? '-double text-blue-500' : ''} ml-1"></i>
                    </div>
                `;
            } else {
                messageElement.innerHTML = `
                    <div class="message-bubble-mine px-3 py-2 text-sm">
                        ${message.body}
                    </div>
                    <div class="text-xs text-gray-400 mt-1 mr-1 text-right flex items-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        ${dateFormatted.time}
                        <i class="fas fa-check${message.is_read ? '-double text-blue-500' : ''} ml-1"></i>
                    </div>
                `;
            }
        } else {
            // Message from other user
            messageElement.className = 'flex items-start mb-4 max-w-xs md:max-w-md group';
            messageElement.dataset.messageId = message.id;
            
            const userAvatar = message.user_avatar || '/images/default-avatar.png';
            
            if (message.attachment) {
                const isImage = message.attachment_type && message.attachment_type.startsWith('image/');
                const isPdf = message.attachment_type === 'application/pdf';
                
                messageElement.innerHTML = `
                    <div class="flex-shrink-0 mr-2 mt-1">
                        <img src="${userAvatar}" class="w-8 h-8 rounded-full" alt="${message.user_name}">
                    </div>
                    <div>
                        <div class="message-bubble-other px-3 py-2 text-sm text-gray-800">
                            ${isImage ? `
                                <a href="${message.attachment}" target="_blank" class="block">
                                    <img src="${message.attachment}" class="rounded-lg max-w-[250px]" alt="Image">
                                </a>
                            ` : `
                                <div class="flex items-center">
                                    <i class="fas fa-${isPdf ? 'file-pdf text-red-500' : 'file text-blue-500'} text-xl mr-3"></i>
                                    <div>
                                        <div class="font-medium">${message.attachment_name || 'Attachment'}</div>
                                        <div class="text-xs text-gray-500">
                                            ${isPdf ? 'PDF Document' : 'File'}
                                        </div>
                                    </div>
                                </div>
                            `}
                            ${message.body ? `<div class="mt-2">${message.body}</div>` : ''}
                        </div>
                        <div class="text-xs text-gray-400 mt-1 ml-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            ${dateFormatted.time}
                        </div>
                    </div>
                `;
            } else {
                messageElement.innerHTML = `
                    <div class="flex-shrink-0 mr-2 mt-1">
                        <img src="${userAvatar}" class="w-8 h-8 rounded-full" alt="${message.user_name}">
                    </div>
                    <div>
                        <div class="message-bubble-other px-3 py-2 text-sm text-gray-800">
                            ${message.body}
                        </div>
                        <div class="text-xs text-gray-400 mt-1 ml-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            ${dateFormatted.time}
                        </div>
                    </div>
                `;
            }
        }
        
        this.chatMessages.appendChild(messageElement);
        this.scrollToBottom();
    }
    
    /**
     * Format a date for display
     * @param {Date} date - The date to format
     * @returns {Object} - The formatted date strings
     */
    formatDate(date) {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        
        // Get date part
        const dateFormatted = date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        // Get date in YYYY-MM-DD format for comparison
        const dateValue = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
        
        // Get time part
        const timeFormatted = date.toLocaleTimeString('en-US', { 
            hour: 'numeric', 
            minute: '2-digit', 
            hour12: true 
        });
        
        // Determine if it's today or yesterday
        let displayDate = dateFormatted;
        if (date.getFullYear() === today.getFullYear() && 
            date.getMonth() === today.getMonth() && 
            date.getDate() === today.getDate()) {
            displayDate = 'Today';
        } else if (date.getFullYear() === yesterday.getFullYear() && 
                date.getMonth() === yesterday.getMonth() && 
                date.getDate() === yesterday.getDate()) {
            displayDate = 'Yesterday';
        }
        
        return {
            formatted: displayDate,
            time: timeFormatted,
            date: dateValue
        };
    }
    
    /**
     * Scroll to the bottom of the chat
     */
    scrollToBottom() {
        if (this.chatMessages) {
            this.chatMessages.scrollTop = this.chatMessages.scrollHeight;
        }
    }
    
    /**
     * Handle typing in the message input
     */
    handleTyping() {
        if (!this.activeConversationId) return;
        
                clearTimeout(this.typingTimeout);
        
        // Send typing indicator (true = is typing)
        this.sendTypingIndicator(true);
        
        // Set a timeout to clear the typing indicator after 3 seconds of inactivity
        this.typingTimeout = setTimeout(() => {
            this.sendTypingIndicator(false);
        }, 3000);
    }
    
    /**
     * Send typing indicator status
     * @param {boolean} isTyping - Whether the user is typing
     */
    sendTypingIndicator(isTyping) {
        if (!this.activeConversationId) return;
        
        fetch('/api/messages/typing', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                conversation_id: this.activeConversationId,
                is_typing: isTyping
                }),
                credentials: 'same-origin',
        }).catch(error => {
            console.error('Error sending typing indicator:', error);
        });
    }
    
    /**
     * Handle user typing event
     * @param {Object} data - The typing data
     */
    handleUserTyping(data) {
        if (data.user_id === this.userId) return;
        
        const typingIndicator = document.getElementById('typing-indicator');
        
        if (data.is_typing) {
            // Show typing indicator
            if (!typingIndicator) {
                const newTypingIndicator = document.createElement('div');
                newTypingIndicator.id = 'typing-indicator';
                newTypingIndicator.className = 'flex items-start mb-4 max-w-xs md:max-w-md';
                newTypingIndicator.innerHTML = `
                    <div class="flex-shrink-0 mr-2 mt-1">
                        <img src="${data.user_avatar || '/images/default-avatar.png'}" class="w-8 h-8 rounded-full" alt="${data.user_name}">
                    </div>
                    <div class="message-bubble-other px-3 py-2 text-sm typing-indicator">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                `;
                this.chatMessages.appendChild(newTypingIndicator);
                this.scrollToBottom();
            }
        } else {
            // Hide typing indicator
            if (typingIndicator) {
                typingIndicator.remove();
            }
        }
    }
    
    /**
     * Handle message read event
     * @param {Object} data - The read data
     */
    handleMessageRead(data) {
        // Update read receipts for messages
        document.querySelectorAll(`[data-message-id="${data.message_id}"]`).forEach(element => {
            const readIndicator = element.querySelector('.fa-check');
            if (readIndicator) {
                readIndicator.classList.remove('fa-check');
                readIndicator.classList.add('fa-check-double', 'text-blue-500');
            }
        });
    }
    
    /**
     * Mark messages as read when scrolled into view
     */
    handleScroll() {
        if (!this.activeConversationId) return;
        
        const unreadMessages = document.querySelectorAll('.message-bubble-other:not(.read)');
        const messageIds = [];
        
        unreadMessages.forEach(message => {
            const messageElement = message.closest('[data-message-id]');
            if (messageElement && this.isElementInViewport(message)) {
                messageIds.push(messageElement.dataset.messageId);
                message.classList.add('read');
            }
        });
        
        if (messageIds.length > 0) {
            this.markMessagesAsRead(messageIds);
        }
    }
    
    /**
     * Check if an element is in the viewport
     * @param {Element} element - The element to check
     * @returns {boolean} - Whether the element is visible
     */
    isElementInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    /**
     * Mark specified message as read
     * @param {number} messageId - The ID of the message to mark as read
     */
    markMessageAsRead(messageId) {
        if (!this.activeConversationId) return;
        
        this.markMessagesAsRead([messageId]);
    }
    
    /**
     * Mark all visible messages as read
     */
    markMessagesAsRead(specificIds = null) {
        if (!this.activeConversationId) return;
        
        let messageIds = specificIds;
        
        // If no specific IDs provided, get all unread messages in view
        if (!messageIds) {
            messageIds = [];
            document.querySelectorAll('.message-bubble-other:not(.read)').forEach(message => {
                const messageElement = message.closest('[data-message-id]');
                if (messageElement && this.isElementInViewport(message)) {
                    messageIds.push(messageElement.dataset.messageId);
                    message.classList.add('read');
                }
            });
        }
        
        if (messageIds.length > 0) {
            fetch('/api/messages/read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    message_ids: messageIds
                }),
                credentials: 'same-origin',
            }).catch(error => {
                console.error('Error marking messages as read:', error);
            });
        }
    }
}

// Initialize messaging when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're on the messaging page
    if (window.location.pathname.startsWith('/messaging')) {
        // Get the current user ID from a data attribute on the body or a hidden input
        const userId = document.body.dataset.userId || document.querySelector('input[name="user_id"]')?.value;
        
        if (userId) {
            window.messagingApp = new Messaging(userId);
        }
    }
}); 