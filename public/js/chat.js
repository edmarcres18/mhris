document.addEventListener('DOMContentLoaded', () => {
    const chatMessages = document.querySelector('.chat-messages');
    const chatForm = document.querySelector('.chat-form');
    const chatInput = document.querySelector('.chat-input');
    const sendButton = document.querySelector('.send-button');
    const fileUploadButton = document.querySelector('.file-upload-button');
    const fileInput = document.querySelector('#file-input');
    const fileUploadOverlay = document.querySelector('.file-upload-overlay');
    const filePreviewContainer = document.querySelector('.file-preview-container');
    const filePreviewList = document.querySelector('.file-preview-list');
    const typingIndicator = document.querySelector('.typing-indicator');
    let isProcessing = false;

    // Auto-resize textarea
    chatInput.addEventListener('input', () => {
        chatInput.style.height = 'auto';
        chatInput.style.height = chatInput.scrollHeight + 'px';
    });

    // Handle form submission
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = chatInput.value.trim();
        const files = fileInput.files;

        if (!message && (!files || files.length === 0)) {
            showError('Please enter a message or select files');
            return;
        }

        if (isProcessing) {
            showError('Please wait for the current request to complete');
            return;
        }

        try {
            isProcessing = true;
            sendButton.disabled = true;
            showTypingIndicator();

            const formData = new FormData();
            formData.append('message', message);
            if (files && files.length > 0) {
                Array.from(files).forEach(file => {
                    formData.append('files[]', file);
                });
            }

            addMessage('user', message);
            chatInput.value = '';
            chatInput.style.height = 'auto';
            clearFileInput();

            const response = await fetch('/chat', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const reader = response.body.getReader();
            const decoder = new TextDecoder();
            let aiMessage = '';

            while (true) {
                const {value, done} = await reader.read();
                if (done) break;

                const text = decoder.decode(value);
                const lines = text.split('\n');

                for (const line of lines) {
                    if (line.startsWith('data: ')) {
                        const data = line.slice(5);
                        if (data === '[DONE]') continue;

                        try {
                            const parsed = JSON.parse(data);
                            if (parsed.text) {
                                aiMessage += parsed.text;
                                updateLastAiMessage(aiMessage);
                            }
                        } catch (e) {
                            console.error('Error parsing SSE data:', e);
                        }
                    }
                }
            }

        } catch (error) {
            console.error('Error:', error);
            showError('An error occurred while processing your request');
        } finally {
            isProcessing = false;
            sendButton.disabled = false;
            hideTypingIndicator();
        }
    });

    // File upload handling
    fileUploadButton.addEventListener('click', () => {
        fileUploadOverlay.classList.add('active');
    });

    document.querySelector('.file-upload-close').addEventListener('click', () => {
        fileUploadOverlay.classList.remove('active');
    });

    document.querySelector('.file-upload-area').addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', handleFileSelect);

    // Utility functions
    function addMessage(role, content) {
        const message = document.createElement('div');
        message.className = `message ${role}`;
        message.innerHTML = `
            <div class="message-avatar">
                <i class="fas fa-${role === 'user' ? 'user' : 'robot'}"></i>
            </div>
            <div class="message-content">
                <div class="message-header">
                    <span class="message-sender">${role === 'user' ? 'You' : 'AI Assistant'}</span>
                    <span class="message-time">${new Date().toLocaleTimeString()}</span>
                </div>
                <div class="message-text">${content}</div>
            </div>
        `;
        chatMessages.appendChild(message);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function updateLastAiMessage(content) {
        const lastMessage = chatMessages.querySelector('.message.ai:last-child');
        if (lastMessage) {
            lastMessage.querySelector('.message-text').innerHTML = content;
        } else {
            addMessage('ai', content);
        }
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function handleFileSelect(e) {
        const files = e.target.files;
        if (!files || files.length === 0) return;

        filePreviewList.innerHTML = '';
        filePreviewContainer.classList.add('active');

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            const preview = createFilePreview(file);
            filePreviewList.appendChild(preview);

            if (file.type.startsWith('image/')) {
                reader.onload = (e) => {
                    const img = preview.querySelector('img');
                    if (img) img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function createFilePreview(file) {
        const div = document.createElement('div');
        div.className = 'file-preview-item';
        div.innerHTML = `
            <div class="file-preview-item-inner">
                <div class="file-preview-thumbnail">
                    ${file.type.startsWith('image/') ? 
                        `<img src="" alt="${file.name}">` : 
                        `<i class="fas fa-file"></i>`
                    }
                </div>
                <div class="file-preview-name">${file.name}</div>
                <div class="file-preview-size">${formatFileSize(file.size)}</div>
                <button class="file-preview-remove" data-name="${file.name}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        div.querySelector('.file-preview-remove').addEventListener('click', () => {
            removeFile(file.name);
        });

        return div;
    }

    function removeFile(fileName) {
        const dt = new DataTransfer();
        const files = fileInput.files;

        for (let i = 0; i < files.length; i++) {
            if (files[i].name !== fileName) {
                dt.items.add(files[i]);
            }
        }

        fileInput.files = dt.files;
        if (fileInput.files.length === 0) {
            filePreviewContainer.classList.remove('active');
        }

        const preview = filePreviewList.querySelector(`[data-name="${fileName}"]`).parentElement.parentElement;
        preview.remove();
    }

    function clearFileInput() {
        fileInput.value = '';
        filePreviewList.innerHTML = '';
        filePreviewContainer.classList.remove('active');
        fileUploadOverlay.classList.remove('active');
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function showTypingIndicator() {
        typingIndicator.style.display = 'flex';
    }

    function hideTypingIndicator() {
        typingIndicator.style.display = 'none';
    }

    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger alert-dismissible fade show';
        errorDiv.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        `;
        document.querySelector('.chat-container').insertBefore(errorDiv, chatMessages);
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }
}));