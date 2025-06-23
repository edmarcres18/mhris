/**
 * Chat Real-time Handler
 * 
 * This script handles real-time updates for the chat interface using Laravel Echo and Pusher
 */

class ChatRealtime {
    constructor() {
        this.userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
        this.conversationId = document.querySelector('meta[name="conversation-id"]')?.getAttribute('content');
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.typingTimeout = null;
        this.messageContainer = document.getElementById('chat-messages');
        this.typingIndicator = document.getElementById('typing-indicator');
        this.messageForm = document.getElementById('message-form');
        this.messageInput = document.getElementById('message-input');
        
        // Initialize if we have the required elements
        if (this.userId && window.Echo) {
            this.initialize();
        }
    }
    
    initialize() {
        // Listen for events on the user's private channel
        window.Echo.private(`user.${this.userId}`)
            .listen('.message.sent', (data) => {
                this.handleNewMessage(data);
            });
        
        // If we're in a conversation, join that channel too
        if (this.conversationId) {
            window.Echo.private(`chat.${this.conversationId}`)
                .listen('.message.sent', (data) => {
                    this.handleNewMessage(data);
                })
                .listen('.message.read', (data) => {
                    this.handleMessageRead(data);
                })
                .listen('.user.typing', (data) => {
                    this.handleUserTyping(data);
                });
                
            // Set up typing indicator
            if (this.messageInput) {
                this.messageInput.addEventListener('input', () => {
                    this.sendTypingIndicator(true);
                });
            }
            
            // Mark messages as read when the conversation is opened
            this.markMessagesAsRead();
        }
        
        // Set up message form submission
        if (this.messageForm) {
            this.messageForm.addEventListener('submit', (e) => {
                const messageText = this.messageInput.value.trim();
                
                // If the message is empty and no attachments, prevent submission
                if (!messageText && !this.hasAttachments()) {
                    e.preventDefault();
                }
                
                // Clear typing indicator if the form is submitted
                this.sendTypingIndicator(false);
            });
        }
    }
    
    handleNewMessage(data) {
        // If the message is for the current conversation, add it to the UI
        if (this.conversationId && data.conversation_id == this.conversationId) {
            // In a real implementation, you'd dynamically add the message to the UI
            // For simplicity, we'll just reload the page
            this.reloadWithScrollPosition();
            
            // Mark the message as read
            if (data.sender_id != this.userId) {
                this.markMessageAsRead(data.id);
            }
        } else {
            // Update the conversation list or show a notification
            this.showNewMessageNotification(data);
        }
    }
    
    handleMessageRead(data) {
        // Update read receipts in the UI
        const readReceipts = document.querySelectorAll(`.read-receipt[data-message-id="${data.id}"]`);
        readReceipts.forEach(receipt => {
            receipt.innerHTML = '<i class="fas fa-check-double text-xs text-[var(--telegram-blue)]"></i>';
        });
    }
    
    handleUserTyping(data) {
        // Only show typing indicator if it's not the current user
        if (data.user_id != this.userId && this.typingIndicator) {
            if (data.is_typing) {
                this.typingIndicator.classList.remove('hidden');
            } else {
                this.typingIndicator.classList.add('hidden');
            }
        }
    }
    
    sendTypingIndicator(isTyping) {
        if (!this.conversationId) return;
        
        // Clear previous timeout
        if (this.typingTimeout) {
            clearTimeout(this.typingTimeout);
        }
        
        // Send typing indicator
        fetch(`/api/messages/typing`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                conversation_id: this.conversationId,
                is_typing: isTyping,
            }),
            credentials: 'same-origin',
        });
        
        // Set timeout to automatically set typing to false after 5 seconds
        if (isTyping) {
            this.typingTimeout = setTimeout(() => {
                this.sendTypingIndicator(false);
            }, 5000);
        }
    }
    
    markMessagesAsRead() {
        if (!this.conversationId) return;
        
        // Get all unread message IDs
        const unreadMessages = document.querySelectorAll('.message-bubble-other[data-read="false"]');
        const messageIds = Array.from(unreadMessages).map(el => el.dataset.messageId);
        
        if (messageIds.length === 0) return;
        
        // Mark messages as read
        fetch(`/api/messages/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                message_ids: messageIds,
            }),
            credentials: 'same-origin',
        });
    }
    
    markMessageAsRead(messageId) {
        fetch(`/api/messages/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                message_ids: [messageId],
            }),
            credentials: 'same-origin',
        });
    }
    
    hasAttachments() {
        const fileInputs = this.messageForm.querySelectorAll('input[type="file"]');
        for (const input of fileInputs) {
            if (input.files.length > 0) {
                return true;
            }
        }
        return false;
    }
    
    reloadWithScrollPosition() {
        // Store the current scroll position
        const scrollPosition = this.messageContainer?.scrollTop || 0;
        sessionStorage.setItem('chatScrollPosition', scrollPosition);
        
        // Reload the page
        window.location.reload();
    }
    
    showNewMessageNotification(data) {
        // In a real implementation, you'd show a notification or update the UI
        // For simplicity, we'll just add a badge to the conversation in the sidebar
        const conversationItem = document.querySelector(`.tg-sidebar-item[data-conversation-id="${data.conversation_id}"]`);
        if (conversationItem) {
            let badgeContainer = conversationItem.querySelector('.tg-badge-container');
            let badge = conversationItem.querySelector('.tg-badge');
            
            if (!badgeContainer) {
                badgeContainer = document.createElement('div');
                badgeContainer.classList.add('tg-badge-container', 'flex', 'items-center');
                
                badge = document.createElement('div');
                badge.classList.add('tg-badge');
                badge.textContent = '1';
                
                badgeContainer.appendChild(badge);
                conversationItem.querySelector('.flex-1').appendChild(badgeContainer);
            } else {
                const count = parseInt(badge.textContent) || 0;
                badge.textContent = count + 1;
            }
            
            // Update last message preview
            const messagePreview = conversationItem.querySelector('.text-xs.truncate');
            if (messagePreview) {
                messagePreview.innerHTML = data.message;
            }
        }
    }
    
    static init() {
        document.addEventListener('DOMContentLoaded', () => {
            window.chatRealtime = new ChatRealtime();
        });
    }
}

// Initialize the chat realtime handler
ChatRealtime.init(); 