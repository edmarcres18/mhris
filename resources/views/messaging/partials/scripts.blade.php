@push('scripts')
<script>
    // Global variables to avoid redeclaration errors
    var CURRENT_USER_ID = "{{ auth()->id() }}";
    CURRENT_USER_ID = parseInt(CURRENT_USER_ID);
    var IS_GROUP_CHAT = "{{ isset($activeConversation) && $activeConversation->is_group ? 'true' : 'false' }}";
    IS_GROUP_CHAT = (IS_GROUP_CHAT === "true");
    var DEFAULT_AVATAR = "{{ asset('images/default-avatar.png') }}";
    var messageInput, messageActionsMenu, currentMessageId, currentMessageText, currentMessageUser;
    var selectedUsers = []; // Array to store selected users for new conversation
    
    // Conversation Context Menu variables
    var conversationContextMenu;
    var currentConversationId;
    var isMuted = false; // Track mute state
    
    // Emoji picker variables
    var emojiButton;
    var emojiHoverMenu;
    var emojiSidebar;
    var emojiSidebarOverlay;
    var closeEmojiSidebarBtn;
    var emojiTabs;
    var emojiTabContents;
    var emojiCategories;
    var emojiSearchInput;
    var gifSearchInput;
    var emojiHoverTimer;
    var emojiLeaveTimer;
    var recentEmojis = []; // Array to store recently used emojis
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DOM elements and setup event handlers
        
        // Auto-scroll to bottom of chat
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) {
            setTimeout(() => {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 100);
        }
        
        // Emoji picker functionality
        initializeEmojiPicker();
        
        // New Message Button Functionality
        const newMessageBtn = document.getElementById('new-message-btn');
        const startConversationBtn = document.getElementById('start-conversation-btn');
        const newConversationModal = document.getElementById('new-conversation-modal');
        const closeModalBtn = document.getElementById('close-modal');
        const userSearchInput = document.getElementById('user-search');
        const userSearchResults = document.getElementById('user-search-results');
        const selectedUsersContainer = document.getElementById('selected-users-container');
        const newConversationForm = document.getElementById('new-conversation-form');
        const isGroupField = document.getElementById('is_group');
        const groupNameContainer = document.getElementById('group-name-container');
        
        // Show new conversation modal
        function openNewConversationModal() {
            if (newConversationModal) {
                newConversationModal.classList.remove('hidden');
                // Clear previous search and selections
                if (userSearchInput) userSearchInput.value = '';
                if (userSearchResults) userSearchResults.classList.add('hidden');
                if (selectedUsersContainer) selectedUsersContainer.innerHTML = '';
                selectedUsers = [];
                if (isGroupField) isGroupField.value = '0';
                if (groupNameContainer) groupNameContainer.style.display = 'none';
                
                // Focus the search input
                setTimeout(() => {
                    if (userSearchInput) userSearchInput.focus();
                }, 100);
            }
        }
        
        // Hide new conversation modal
        function closeNewConversationModal() {
            if (newConversationModal) {
                newConversationModal.classList.add('hidden');
            }
        }
        
        // Attach event listeners to the buttons
        if (newMessageBtn) {
            newMessageBtn.addEventListener('click', openNewConversationModal);
        }
        
        if (startConversationBtn) {
            startConversationBtn.addEventListener('click', openNewConversationModal);
        }
        
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeNewConversationModal);
        }
        
        // Close modal when clicking outside
        if (newConversationModal) {
            newConversationModal.addEventListener('click', function(e) {
                if (e.target === newConversationModal) {
                    closeNewConversationModal();
                }
            });
        }
        
        // User search functionality
        if (userSearchInput) {
            let searchTimeout;
            
            userSearchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                const query = e.target.value.trim();
                
                if (query.length < 2) {
                    userSearchResults.classList.add('hidden');
                    return;
                }
                
                searchTimeout = setTimeout(() => {
                    searchUsers(query);
                }, 300);
            });
            
            // Search users via AJAX
            function searchUsers(query) {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch(`/messaging/users/search?query=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(users => {
                    renderUserSearchResults(users);
                })
                .catch(error => {
                    console.error('Error searching users:', error);
                });
            }
            
            // Render user search results
            function renderUserSearchResults(users) {
                if (!userSearchResults) return;
                
                if (users.length === 0) {
                    userSearchResults.innerHTML = `
                        <div class="p-3 text-sm text-gray-500 text-center">
                            No users found
                        </div>
                    `;
                    userSearchResults.classList.remove('hidden');
                    return;
                }
                
                let html = '';
                users.forEach(user => {
                    // Skip already selected users
                    if (selectedUsers.some(selected => selected.id === user.id)) {
                        return;
                    }
                    
                    html += `
                        <div class="user-result p-2 hover:bg-gray-50 cursor-pointer flex items-center transition-colors duration-200" 
                             data-user-id="${user.id}" 
                             data-user-name="${user.name}" 
                             data-user-email="${user.email}"
                             data-user-avatar="${user.adminlte_image}">
                            <div class="flex-shrink-0 mr-3">
                                <img src="${user.adminlte_image}" class="w-8 h-8 rounded-full object-cover shadow-sm" alt="${user.name}">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-gray-800">${user.name}</div>
                                <div class="text-xs text-gray-500 truncate">${user.email}</div>
                            </div>
                        </div>
                    `;
                });
                
                userSearchResults.innerHTML = html;
                userSearchResults.classList.remove('hidden');
                
                // Add click event to user results
                const userResults = userSearchResults.querySelectorAll('.user-result');
                userResults.forEach(result => {
                    result.addEventListener('click', function() {
                        const userId = this.getAttribute('data-user-id');
                        const userName = this.getAttribute('data-user-name');
                        const userEmail = this.getAttribute('data-user-email');
                        const userAvatar = this.getAttribute('data-user-avatar');
                        
                        // Add user to selected users if not already there
                        if (!selectedUsers.some(user => user.id === userId)) {
                            const user = {
                                id: userId,
                                name: userName,
                                email: userEmail,
                                avatar: userAvatar
                            };
                            
                            selectedUsers.push(user);
                            addSelectedUserToUI(user);
                            
                            // Clear search input and results
                            userSearchInput.value = '';
                            userSearchResults.classList.add('hidden');
                            
                            // Show group name field if more than one user selected
                            if (selectedUsers.length > 1) {
                                isGroupField.value = '1';
                                groupNameContainer.style.display = 'block';
                            } else {
                                isGroupField.value = '0';
                                groupNameContainer.style.display = 'none';
                            }
                            
                            // Focus back on search input
                            userSearchInput.focus();
                        }
                    });
                });
            }
            
            // Add selected user to UI
            function addSelectedUserToUI(user) {
                if (!selectedUsersContainer) return;
                
                const userElement = document.createElement('div');
                userElement.className = 'flex items-center bg-primary-50 rounded-full py-1 px-3 text-sm';
                userElement.innerHTML = `
                    <span class="mr-1">${user.name}</span>
                    <button type="button" class="text-gray-500 hover:text-red-500 focus:outline-none" data-user-id="${user.id}">
                        <i class="fas fa-times-circle"></i>
                    </button>
                    <input type="hidden" name="users[]" value="${user.id}">
                `;
                
                // Add remove user handler
                const removeBtn = userElement.querySelector('button');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        const userId = this.getAttribute('data-user-id');
                        // Remove from array
                        selectedUsers = selectedUsers.filter(user => user.id !== userId);
                        // Remove from UI
                        userElement.remove();
                        
                        // Update group status
                        if (selectedUsers.length > 1) {
                            isGroupField.value = '1';
                            groupNameContainer.style.display = 'block';
                        } else {
                            isGroupField.value = '0';
                            groupNameContainer.style.display = 'none';
                        }
                    });
                }
                
                selectedUsersContainer.appendChild(userElement);
            }
            
            // Form submission validation
            if (newConversationForm) {
                newConversationForm.addEventListener('submit', function(e) {
                    if (selectedUsers.length === 0) {
                        e.preventDefault();
                        alert('Please select at least one user to chat with.');
                        return false;
                    }
                    
                    // If it's a group chat, require a group name
                    if (selectedUsers.length > 1) {
                        const groupNameInput = groupNameContainer.querySelector('input[name="name"]');
                        if (groupNameInput && groupNameInput.value.trim() === '') {
                            e.preventDefault();
                            alert('Please enter a name for this group chat.');
                            groupNameInput.focus();
                            return false;
                        }
                    }
                    
                    return true;
                });
            }
        }
        
        // Initialize Emoji Picker Functionality
        function initializeEmojiPicker() {
            emojiButton = document.getElementById('emoji-button');
            emojiHoverMenu = document.getElementById('emoji-hover-menu');
            emojiSidebar = document.getElementById('emoji-sidebar');
            emojiSidebarOverlay = document.getElementById('emoji-sidebar-overlay');
            closeEmojiSidebarBtn = document.getElementById('close-emoji-sidebar');
            emojiTabs = document.querySelectorAll('.emoji-tab');
            emojiTabContents = document.querySelectorAll('.emoji-content');
            emojiCategories = document.querySelectorAll('.emoji-category');
            emojiSearchInput = document.getElementById('emoji-search-input');
            gifSearchInput = document.getElementById('gif-search-input');
            messageInput = document.getElementById('message-input');
            
            // Exit if any required element is missing
            if (!emojiButton || !emojiHoverMenu || !emojiSidebar || !messageInput) return;
            
            // Load recently used emojis from localStorage
            loadRecentEmojis();
            
            // Load emojis from API
            loadEmojisFromAPI();
            
            // Emoji Button click - open the full sidebar
            emojiButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Hide hover menu if visible
                hideEmojiHoverMenu();
                
                // Open sidebar
                openEmojiSidebar();
            });
            
            // Emoji Button hover - show quick emoji menu
            emojiButton.addEventListener('mouseenter', function() {
                // Clear any existing leave timer
                if (emojiLeaveTimer) {
                    clearTimeout(emojiLeaveTimer);
                    emojiLeaveTimer = null;
                }
                
                // Set timer to show hover menu (small delay feels more natural)
                emojiHoverTimer = setTimeout(() => {
                    if (!emojiSidebar.classList.contains('open')) {
                        showEmojiHoverMenu();
                    }
                }, 300);
            });
            
            // Handle mouse leave on emoji button
            emojiButton.addEventListener('mouseleave', function() {
                // Clear show timer if exists
                if (emojiHoverTimer) {
                    clearTimeout(emojiHoverTimer);
                    emojiHoverTimer = null;
                }
                
                // Set timer to hide menu (with delay so user can move to the menu)
                emojiLeaveTimer = setTimeout(() => {
                    if (!emojiHoverMenu.matches(':hover')) {
                        hideEmojiHoverMenu();
                    }
                }, 300);
            });
            
            // Keep menu open when hovering over it
            emojiHoverMenu.addEventListener('mouseenter', function() {
                if (emojiLeaveTimer) {
                    clearTimeout(emojiLeaveTimer);
                    emojiLeaveTimer = null;
                }
            });
            
            // Hide menu when mouse leaves it
            emojiHoverMenu.addEventListener('mouseleave', function() {
                emojiLeaveTimer = setTimeout(() => {
                    hideEmojiHoverMenu();
                }, 300);
            });
            
            // Handle emoji selection from hover menu
            emojiHoverMenu.addEventListener('click', function(e) {
                if (e.target.classList.contains('emoji-item')) {
                    insertEmoji(e.target.textContent);
                    hideEmojiHoverMenu();
                    addToRecentEmojis(e.target.textContent);
                }
            });
            
            // Close emoji sidebar when clicking the close button
            if (closeEmojiSidebarBtn) {
                closeEmojiSidebarBtn.addEventListener('click', closeEmojiSidebar);
            }
            
            // Close emoji sidebar when clicking overlay
            if (emojiSidebarOverlay) {
                emojiSidebarOverlay.addEventListener('click', closeEmojiSidebar);
            }
            
            // Handle tab switching in emoji sidebar
            if (emojiTabs.length > 0) {
                emojiTabs.forEach(tab => {
                    tab.addEventListener('click', function() {
                        const tabName = this.getAttribute('data-tab');
                        
                        // Update active tab
                        emojiTabs.forEach(t => t.classList.remove('active'));
                        this.classList.add('active');
                        
                        // Show corresponding content
                        emojiTabContents.forEach(content => {
                            if (content.id === `${tabName}-tab-content`) {
                                content.classList.remove('hidden');
                            } else {
                                content.classList.add('hidden');
                            }
                        });
                    });
                });
            }
            
            // Handle category switching in emoji tab
            if (emojiCategories.length > 0) {
                emojiCategories.forEach(category => {
                    category.addEventListener('click', function() {
                        const categoryName = this.getAttribute('data-category');
                        
                        // Update active category
                        emojiCategories.forEach(c => c.classList.remove('active'));
                        this.classList.add('active');
                        
                        // Scroll to the corresponding emoji group
                        const emojiGroup = document.getElementById(`${categoryName}-emojis`);
                        if (emojiGroup) {
                            emojiGroup.scrollIntoView({ behavior: 'smooth' });
                        }
                    });
                });
            }
            
            // Handle emoji search
            if (emojiSearchInput) {
                emojiSearchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    searchEmojis(searchTerm);
                });
            }
            
            // Handle GIF search
            if (gifSearchInput) {
                let gifSearchTimeout;
                
                gifSearchInput.addEventListener('input', function() {
                    clearTimeout(gifSearchTimeout);
                    
                    const searchTerm = this.value.trim();
                    if (searchTerm.length < 2) return;
                    
                    gifSearchTimeout = setTimeout(() => {
                        searchGifs(searchTerm);
                    }, 500);
                });
            }
            
            // Handle sticker selection
            document.querySelectorAll('.sticker-item').forEach(item => {
                item.addEventListener('click', function() {
                    const imgSrc = this.querySelector('img').src;
                    insertSticker(imgSrc);
                    closeEmojiSidebar();
                });
            });
            
            // Handle GIF selection
            document.querySelectorAll('.gif-item').forEach(item => {
                item.addEventListener('click', function() {
                    const imgSrc = this.querySelector('img').src;
                    insertGif(imgSrc);
                    closeEmojiSidebar();
                });
            });
        }
        
        // Load emojis from API
        function loadEmojisFromAPI() {
            const apiUrl = 'https://emoji-api.com/emojis?access_key=3ca09f4cbf5ddb2e777322fdb74b7c2ce6dc3862';
            const emojiCache = localStorage.getItem('emojiApiCache');
            const cacheTimestamp = localStorage.getItem('emojiApiCacheTimestamp');
            const cacheExpiry = 24 * 60 * 60 * 1000; // 24 hours in milliseconds
            
            // Show loading state in the emoji content area
            const emojiContent = document.getElementById('emojis-tab-content');
            if (emojiContent) {
                emojiContent.innerHTML = `
                    <div class="flex items-center justify-center p-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary-500"></div>
                        <span class="ml-3 text-gray-600">Loading emojis...</span>
                    </div>
                `;
            }
            
            // Also update hover menu with loading indicator
            const emojiHoverMenu = document.getElementById('emoji-hover-menu');
            if (emojiHoverMenu) {
                emojiHoverMenu.innerHTML = `
                    <div class="flex items-center justify-center p-4 w-full">
                        <div class="animate-spin rounded-full h-6 w-6 border-t-2 border-b-2 border-primary-500"></div>
                    </div>
                `;
            }
            
            // Check if we have a valid cache
            if (emojiCache && cacheTimestamp && (Date.now() - parseInt(cacheTimestamp) < cacheExpiry)) {
                try {
                    const emojis = JSON.parse(emojiCache);
                    populateEmojiContent(emojis);
                    populateEmojiHoverMenu(emojis);
                    return;
                } catch (e) {
                    console.error('Error parsing cached emojis:', e);
                    // Continue to fetch from API if cache parsing fails
                }
            }
            
            // Fetch from API
            fetch(apiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`API request failed with status ${response.status}`);
                    }
                    return response.json();
                })
                .then(emojis => {
                    // Cache the results
                    localStorage.setItem('emojiApiCache', JSON.stringify(emojis));
                    localStorage.setItem('emojiApiCacheTimestamp', Date.now().toString());
                    
                    // Populate the UI
                    populateEmojiContent(emojis);
                    populateEmojiHoverMenu(emojis);
                })
                .catch(error => {
                    console.error('Error fetching emojis:', error);
                    
                    // Show error state
                    if (emojiContent) {
                        emojiContent.innerHTML = `
                            <div class="text-center p-8">
                                <div class="text-red-500 text-4xl mb-2"><i class="fas fa-exclamation-circle"></i></div>
                                <p class="text-gray-700 mb-2">Failed to load emojis</p>
                                <button id="retry-emoji-load" class="px-4 py-2 bg-primary-500 text-white rounded-md hover:bg-primary-600 transition-colors duration-200">
                                    Retry
                                </button>
                            </div>
                        `;
                        
                        // Add retry button functionality
                        const retryButton = document.getElementById('retry-emoji-load');
                        if (retryButton) {
                            retryButton.addEventListener('click', loadEmojisFromAPI);
                        }
                    }
                    
                    // Show basic emojis in hover menu as fallback
                    if (emojiHoverMenu) {
                        emojiHoverMenu.innerHTML = `
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
                        `;
                        
                        // Add click handlers
                        document.querySelectorAll('#emoji-hover-menu .emoji-item').forEach(item => {
                            item.addEventListener('click', function() {
                                insertEmoji(this.textContent);
                                hideEmojiHoverMenu();
                                addToRecentEmojis(this.textContent);
                            });
                        });
                    }
                });
        }
        
        // Populate emoji content with data from API
        function populateEmojiContent(emojis) {
            const emojiContent = document.getElementById('emojis-tab-content');
            if (!emojiContent) return;
            
            // Group emojis by category
            const groupedEmojis = {};
            emojis.forEach(emoji => {
                const group = emoji.group;
                if (!groupedEmojis[group]) {
                    groupedEmojis[group] = [];
                }
                groupedEmojis[group].push(emoji);
            });
            
            // Clear content
            emojiContent.innerHTML = '';
            
            // Add recent emojis section first
            const recentSection = document.createElement('div');
            recentSection.className = 'emoji-group';
            recentSection.id = 'recent-emojis';
            recentSection.innerHTML = `
                <div class="emoji-group-title">Recently Used</div>
                <div class="emoji-grid">
                    <div class="text-gray-500 text-sm text-center py-4 col-span-6">
                        No recently used emojis
                    </div>
                </div>
            `;
            emojiContent.appendChild(recentSection);
            
            // Update recent emojis if we have any
            updateRecentEmojisDisplay();
            
            // Map emoji-api.com groups to our category buttons
            const groupMapping = {
                'smileys-emotion': 'smileys',
                'people-body': 'people',
                'animals-nature': 'animals',
                'food-drink': 'food',
                'travel-places': 'travel',
                'activities': 'activities',
                'objects': 'objects',
                'symbols': 'symbols',
                'flags': 'flags'
            };
            
            // Add sections for each group
            Object.keys(groupedEmojis).forEach(group => {
                // Skip groups with no emojis
                if (groupedEmojis[group].length === 0) return;
                
                // Format group name for display
                const formattedGroupName = group
                    .split('-')
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' & ');
                
                // Map to our category ID
                const categoryId = groupMapping[group] || group;
                
                const groupSection = document.createElement('div');
                groupSection.className = 'emoji-group';
                groupSection.id = `${categoryId}-emojis`;
                
                let groupHTML = `<div class="emoji-group-title">${formattedGroupName}</div>`;
                groupHTML += '<div class="emoji-grid">';
                
                // Add emojis to grid
                groupedEmojis[group].forEach(emoji => {
                    // Emoji character might be unicode encoded, so we need to decode it
                    let character = emoji.character;
                    
                    // Handle special case for unicode escaped strings
                    if (character.includes('\\u')) {
                        try {
                            character = JSON.parse(`"${character}"`);
                        } catch (e) {
                            console.warn('Failed to parse emoji character:', emoji.character);
                        }
                    }
                    
                    groupHTML += `<div class="emoji-item" data-slug="${emoji.slug}" title="${emoji.unicodeName}">${character}</div>`;
                });
                
                groupHTML += '</div>';
                groupSection.innerHTML = groupHTML;
                emojiContent.appendChild(groupSection);
            });
            
            // Add click handlers to emoji items
            document.querySelectorAll('#emojis-tab-content .emoji-item').forEach(item => {
                item.addEventListener('click', function() {
                    if (this.textContent) {
                        insertEmoji(this.textContent);
                        addToRecentEmojis(this.textContent);
                    }
                });
            });
        }
        
        // Populate emoji hover menu with popular emojis
        function populateEmojiHoverMenu(emojis) {
            const emojiHoverMenu = document.getElementById('emoji-hover-menu');
            if (!emojiHoverMenu) return;
            // Clear current content
            emojiHoverMenu.innerHTML = '';
            // Popular emoji categories
            const popularCategories = ['smileys-emotion', 'people-body'];
            // Get a limited set of popular emojis
            const popularEmojis = [];
            emojis.forEach(emoji => {
                if (popularCategories.includes(emoji.group) && popularEmojis.length < 24) {
                    popularEmojis.push(emoji);
                }
            });
            // Add to hover menu
            popularEmojis.forEach(emoji => {
                let character = emoji.character;
                if (character.includes('\\u')) {
                    try {
                        character = JSON.parse(`"${character}"`);
                    } catch (e) {
                        console.warn('Failed to parse emoji character:', emoji.character);
                    }
                }
                const emojiItem = document.createElement('div');
                emojiItem.className = 'emoji-item';
                emojiItem.setAttribute('title', emoji.unicodeName);
                emojiItem.textContent = character;
                // Do NOT add a click handler here!
                emojiHoverMenu.appendChild(emojiItem);
            });
        }
        
        // Show emoji hover menu
        function showEmojiHoverMenu() {
            emojiHoverMenu.classList.add('show');
        }
        
        // Hide emoji hover menu
        function hideEmojiHoverMenu() {
            emojiHoverMenu.classList.remove('show');
        }
        
        // Open emoji sidebar
        function openEmojiSidebar() {
            emojiSidebar.classList.add('open');
            emojiSidebarOverlay.classList.add('open');
            
            // Update recent emojis
            updateRecentEmojisDisplay();
        }
        
        // Close emoji sidebar
        function closeEmojiSidebar() {
            emojiSidebar.classList.remove('open');
            emojiSidebarOverlay.classList.remove('open');
        }
        
        // Insert emoji into message input
        function insertEmoji(emoji) {
            if (!messageInput) return;
            
            // Get cursor position
            const startPos = messageInput.selectionStart;
            const endPos = messageInput.selectionEnd;
            
            // Insert emoji at cursor position
            const currentValue = messageInput.value;
            messageInput.value = currentValue.substring(0, startPos) + emoji + currentValue.substring(endPos);
            
            // Set cursor position after the inserted emoji
            const newPos = startPos + emoji.length;
            messageInput.setSelectionRange(newPos, newPos);
            
            // Focus the input
            messageInput.focus();
            
            // Trigger input event to resize textarea
            const event = new Event('input', { bubbles: true });
            messageInput.dispatchEvent(event);
        }
        
        // Insert sticker as attachment
        function insertSticker(stickerUrl) {
            // Implementation would depend on your attachment handling system
            // For this example, we'll just add it as a message
            if (messageInput) {
                messageInput.value += `[sticker:${stickerUrl}]`;
                messageInput.focus();
            }
            
            // Show toast notification
            showToast('Sticker feature is a placeholder. Implementation would depend on your attachment system.');
        }
        
        // Insert GIF as attachment
        function insertGif(gifUrl) {
            // Implementation would depend on your attachment handling system
            // For this example, we'll just add it as a message
            if (messageInput) {
                messageInput.value += `[gif:${gifUrl}]`;
                messageInput.focus();
            }
            
            // Show toast notification
            showToast('GIF feature is a placeholder. Implementation would depend on your attachment system.');
        }
        
        // Search emojis
        function searchEmojis(searchTerm) {
            // Get all emoji items from the content area
            const emojiItems = document.querySelectorAll('#emojis-tab-content .emoji-item');
            const emojiGroups = document.querySelectorAll('#emojis-tab-content .emoji-group');
            
            if (searchTerm === '') {
                // Show all emoji groups
                emojiGroups.forEach(group => {
                    group.style.display = 'block';
                });
                
                // Remove search results container if it exists
                const searchResults = document.getElementById('emoji-search-results');
                if (searchResults) {
                    searchResults.remove();
                }
                
                return;
            }
            
            // Hide all standard emoji groups
            emojiGroups.forEach(group => {
                if (group.id !== 'emoji-search-results') {
                    group.style.display = 'none';
                }
            });
            
            // Create or get search results container
            let searchResults = document.getElementById('emoji-search-results');
            if (!searchResults) {
                searchResults = document.createElement('div');
                searchResults.id = 'emoji-search-results';
                searchResults.className = 'emoji-group';
                
                const title = document.createElement('div');
                title.className = 'emoji-group-title';
                title.textContent = 'Search Results';
                
                const grid = document.createElement('div');
                grid.className = 'emoji-grid';
                
                searchResults.appendChild(title);
                searchResults.appendChild(grid);
                
                document.getElementById('emojis-tab-content').prepend(searchResults);
            } else {
                // Clear existing results
                const grid = searchResults.querySelector('.emoji-grid');
                if (grid) {
                    grid.innerHTML = '';
                }
            }
            
            // Filter emojis and add to search results
            let matchCount = 0;
            
            emojiItems.forEach(item => {
                const title = item.getAttribute('title') || '';
                const slug = item.getAttribute('data-slug') || '';
                
                // Check if emoji matches search term
                if (title.toLowerCase().includes(searchTerm) || 
                    slug.toLowerCase().includes(searchTerm) ||
                    item.textContent.includes(searchTerm)) {
                    
                    // Clone the item and add to search results
                    const clone = item.cloneNode(true);
                    clone.addEventListener('click', function() {
                        insertEmoji(this.textContent);
                        addToRecentEmojis(this.textContent);
                    });
                    
                    searchResults.querySelector('.emoji-grid').appendChild(clone);
                    matchCount++;
                }
            });
            
            // Show appropriate message if no results
            if (matchCount === 0) {
                searchResults.querySelector('.emoji-grid').innerHTML = `
                    <div class="text-gray-500 text-sm text-center py-4 col-span-6">
                        No matching emojis found
                    </div>
                `;
            }
        }
        
        // Search GIFs using Giphy API (placeholder)
        function searchGifs(searchTerm) {
            // This would typically use Giphy API or similar
            // For this example, we'll just show placeholder results
            const gifGrid = document.querySelector('#gifs-tab-content .gif-grid');
            
            // Show loading state
            gifGrid.innerHTML = `
                <div class="col-span-2 text-center py-4">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-primary-500"></div>
                    <p class="mt-2 text-sm text-gray-500">Searching for "${searchTerm}"...</p>
                </div>
            `;
            
            // Simulate API call delay
            setTimeout(() => {
                // Placeholder results
                gifGrid.innerHTML = `
                    <div class="gif-item">
                        <img src="https://media1.giphy.com/media/GeimqsH0TLDt4tScGw/giphy.gif" alt="Search Result">
                    </div>
                    <div class="gif-item">
                        <img src="https://media0.giphy.com/media/3oz8xRF0v9WMAUVLNK/giphy.gif" alt="Search Result">
                    </div>
                    <div class="gif-item">
                        <img src="https://media2.giphy.com/media/Cmr1OMJ2FN0B2/giphy.gif" alt="Search Result">
                    </div>
                    <div class="gif-item">
                        <img src="https://media2.giphy.com/media/3oEjHFOscgNwdSRRDy/giphy.gif" alt="Search Result">
                    </div>
                `;
                
                // Add click handlers to new GIF items
                document.querySelectorAll('#gifs-tab-content .gif-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const imgSrc = this.querySelector('img').src;
                        insertGif(imgSrc);
                        closeEmojiSidebar();
                    });
                });
            }, 1000);
        }
        
        // Load recently used emojis from localStorage
        function loadRecentEmojis() {
            const savedEmojis = localStorage.getItem('recentEmojis');
            if (savedEmojis) {
                try {
                    recentEmojis = JSON.parse(savedEmojis);
                } catch (e) {
                    recentEmojis = [];
                }
            }
            
            // Update display
            updateRecentEmojisDisplay();
        }
        
        // Add emoji to recently used list
        function addToRecentEmojis(emoji) {
            // Remove emoji if it already exists in the list
            recentEmojis = recentEmojis.filter(e => e !== emoji);
            
            // Add to the beginning of the list
            recentEmojis.unshift(emoji);
            
            // Limit the list to 12 items
            if (recentEmojis.length > 12) {
                recentEmojis = recentEmojis.slice(0, 12);
            }
            
            // Save to localStorage
            localStorage.setItem('recentEmojis', JSON.stringify(recentEmojis));
            
            // Update the display
            updateRecentEmojisDisplay();
        }
        
        // Update the recent emojis display
        function updateRecentEmojisDisplay() {
            const recentEmojiGrid = document.querySelector('#recent-emojis .emoji-grid');
            if (!recentEmojiGrid) return;
            
            if (recentEmojis.length === 0) {
                recentEmojiGrid.innerHTML = `
                    <div class="text-gray-500 text-sm text-center py-4 col-span-6">
                        No recently used emojis
                    </div>
                `;
                return;
            }
            
            // Clear grid
            recentEmojiGrid.innerHTML = '';
            
            // Add recent emojis
            recentEmojis.forEach(emoji => {
                const emojiItem = document.createElement('div');
                emojiItem.className = 'emoji-item';
                emojiItem.textContent = emoji;
                emojiItem.addEventListener('click', function() {
                    insertEmoji(this.textContent);
                    addToRecentEmojis(this.textContent);
                });
                recentEmojiGrid.appendChild(emojiItem);
            });
        }
        
        // Message actions functionality
        messageActionsMenu = document.getElementById('message-actions-menu');
        currentMessageId = null;
        currentMessageText = '';
        currentMessageUser = '';
        
        // Conversation context menu functionality
        conversationContextMenu = document.getElementById('conversation-context-menu');
        const conversationMenuBtn = document.getElementById('conversation-menu-btn');
        const viewProfileBtn = document.getElementById('view-profile-btn');
        const toggleMuteBtn = document.getElementById('toggle-mute-btn');
        const sharedFilesBtn = document.getElementById('shared-files-btn');
        const blockUserBtn = document.getElementById('block-user-btn');
        
        // Get active conversation ID
        if (document.querySelector('[data-active="true"]')) {
            currentConversationId = document.querySelector('[data-active="true"]').dataset.conversationId;
            
            // Check if this conversation is already muted in local storage
            const mutedConversations = JSON.parse(localStorage.getItem('mutedConversations') || '[]');
            isMuted = mutedConversations.includes(currentConversationId);
            
            // Update mute button text
            updateMuteButtonText();
        }
        
        // Show conversation context menu
        if (conversationMenuBtn) {
            conversationMenuBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Position the menu
                const rect = conversationMenuBtn.getBoundingClientRect();
                conversationContextMenu.style.top = `${rect.bottom + 5}px`;
                
                // Position menu left or right based on available space
                if (rect.left + 224 > window.innerWidth) {
                    conversationContextMenu.style.right = `${window.innerWidth - rect.right}px`;
                    conversationContextMenu.style.left = 'auto';
                } else {
                    conversationContextMenu.style.left = `${rect.left - 200 + rect.width}px`;
                    conversationContextMenu.style.right = 'auto';
                }
                
                // Show the menu with a fade-in effect
                conversationContextMenu.classList.remove('hidden');
                conversationContextMenu.style.opacity = '0';
                conversationContextMenu.style.transform = 'translateY(-5px)';
                
                // Trigger reflow to enable transition
                conversationContextMenu.offsetHeight;
                
                conversationContextMenu.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                conversationContextMenu.style.opacity = '1';
                conversationContextMenu.style.transform = 'translateY(0)';
                
                // Hide message actions menu if open
                if (messageActionsMenu && !messageActionsMenu.classList.contains('hidden')) {
                    messageActionsMenu.classList.add('hidden');
                }
                
                // Add global click listener to hide menu
                setTimeout(() => {
                    window.addEventListener('click', hideConversationMenu);
                }, 0);
            });
        }
        
        // Hide conversation context menu
        function hideConversationMenu() {
            if (conversationContextMenu) {
                conversationContextMenu.style.opacity = '0';
                conversationContextMenu.style.transform = 'translateY(-5px)';
                
                setTimeout(() => {
                    conversationContextMenu.classList.add('hidden');
                }, 200);
                
                window.removeEventListener('click', hideConversationMenu);
            }
        }
        
        // Handle View Profile button
        if (viewProfileBtn) {
            viewProfileBtn.addEventListener('click', function() {
                // Hide menu first
                hideConversationMenu();
                
                if (!currentConversationId) return;
                
                // For groups, show group info modal
                if (IS_GROUP_CHAT === true) {
                    showGroupInfoModal(currentConversationId);
                } else {
                    // For direct messages, get the other user's ID
                    const otherUserId = getOtherUserIdFromConversation();
                    if (otherUserId) {
                        showUserProfileModal(otherUserId);
                    }
                }
            });
        }
        
        // Handle Mute/Unmute Notifications button
        if (toggleMuteBtn) {
            toggleMuteBtn.addEventListener('click', function() {
                // Toggle mute state
                isMuted = !isMuted;
                
                // Update local storage with muted conversations
                let mutedConversations = JSON.parse(localStorage.getItem('mutedConversations') || '[]');
                
                if (isMuted) {
                    // Add to muted list if not already there
                    if (!mutedConversations.includes(currentConversationId)) {
                        mutedConversations.push(currentConversationId);
                    }
                    showToast('Notifications muted for this conversation');
                } else {
                    // Remove from muted list
                    mutedConversations = mutedConversations.filter(id => id !== currentConversationId);
                    showToast('Notifications unmuted for this conversation');
                }
                
                // Update local storage
                localStorage.setItem('mutedConversations', JSON.stringify(mutedConversations));
                
                // Update mute button text
                updateMuteButtonText();
                
                // Hide menu
                hideConversationMenu();
            });
        }
        
        // Handle View Shared Files button
        if (sharedFilesBtn) {
            sharedFilesBtn.addEventListener('click', function() {
                // Hide menu first
                hideConversationMenu();
                
                if (!currentConversationId) return;
                
                // Show shared files modal
                showSharedFilesModal(currentConversationId);
            });
        }
        
        // Handle Block User button
        if (blockUserBtn) {
            blockUserBtn.addEventListener('click', function() {
                // Hide menu first
                hideConversationMenu();
                
                if (!currentConversationId || IS_GROUP_CHAT === true) return;
                
                // Show confirmation dialog
                if (confirm('Are you sure you want to block this user? You will no longer receive messages from them.')) {
                    // Get the other user's ID
                    const otherUserId = getOtherUserIdFromConversation();
                    if (otherUserId) {
                        blockUser(otherUserId);
                    }
                }
            });
        }
        
        // Set up User Profile Modal
        const userProfileModal = document.getElementById('user-profile-modal');
        const closeProfileModal = document.getElementById('close-profile-modal');
        const viewFullProfile = document.getElementById('view-full-profile');
        
        if (closeProfileModal) {
            closeProfileModal.addEventListener('click', function() {
                userProfileModal.classList.add('hidden');
            });
        }
        
        if (viewFullProfile) {
            viewFullProfile.addEventListener('click', function() {
                // Get user ID from the modal data
                const userId = userProfileModal.dataset.userId;
                if (userId) {
                    // Open full profile page in new tab
                    window.open(`/users/${userId}`, '_blank');
                }
            });
        }
        
        // Set up Shared Files Modal
        const sharedFilesModal = document.getElementById('shared-files-modal');
        const closeFilesModal = document.getElementById('close-files-modal');
        const filesTabs = document.querySelectorAll('.files-tab-btn');
        const filesTabContents = document.querySelectorAll('.files-tab-content');
        
        if (closeFilesModal) {
            closeFilesModal.addEventListener('click', function() {
                sharedFilesModal.classList.add('hidden');
            });
        }
        
        // Handle tabs in shared files modal
        if (filesTabs.length > 0) {
            filesTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    filesTabs.forEach(t => t.classList.remove('active', 'text-primary-500', 'border-primary-500'));
                    filesTabs.forEach(t => t.classList.add('text-gray-500', 'border-transparent'));
                    
                    // Add active class to clicked tab
                    tab.classList.add('active', 'text-primary-500', 'border-primary-500');
                    tab.classList.remove('text-gray-500', 'border-transparent');
                    
                    // Hide all tab contents
                    filesTabContents.forEach(content => content.classList.add('hidden'));
                    
                    // Show selected tab content
                    const tabName = tab.dataset.tab;
                    const tabContent = document.getElementById(`${tabName}-tab`);
                    if (tabContent) {
                        tabContent.classList.remove('hidden');
                    }
                });
            });
        }
        
        // Helper function to update mute button text
        function updateMuteButtonText() {
            const muteLabel = document.querySelector('.mute-label');
            if (muteLabel) {
                muteLabel.textContent = isMuted ? 'Unmute Notifications' : 'Mute Notifications';
            }
            
            // Also update icon
            const muteIcon = toggleMuteBtn.querySelector('i');
            if (muteIcon) {
                muteIcon.className = isMuted ? 'fas fa-bell mr-3 text-gray-500 w-5' : 'fas fa-bell-slash mr-3 text-gray-500 w-5';
            }
        }
        
        // Helper function to get other user ID from direct conversation
        function getOtherUserIdFromConversation() {
            if (IS_GROUP_CHAT === true) return null;
            
            const conversationItem = document.querySelector(`[data-conversation-id="${currentConversationId}"]`);
            if (!conversationItem) return null;
            
            // Extract other user ID from conversation participants
            // This is a simplified approach - in a real app, you'd likely fetch this from the server
            const participantsData = conversationItem.dataset.participants;
            if (participantsData) {
                try {
                    const participants = JSON.parse(participantsData);
                    const otherUser = participants.find(p => p.id !== CURRENT_USER_ID);
                    return otherUser ? otherUser.id : null;
                } catch (e) {
                    console.error('Failed to parse participants data', e);
                    return null;
                }
            }
            
            // Fallback: make an API call to get the other participant
            return fetchOtherParticipant(currentConversationId);
        }
        
        // Function to fetch other participant via API
        function fetchOtherParticipant(conversationId) {
            // This would be implemented to make an AJAX call to your server
            // For demo purposes, we'll just return null
            console.log('Would fetch other participant for conversation:', conversationId);
            return null;
        }
        
        // Function to show user profile modal
        function showUserProfileModal(userId) {
            const profileModal = document.getElementById('user-profile-modal');
            const profileAvatar = document.getElementById('profile-avatar');
            const profileName = document.getElementById('profile-name');
            const profileStatus = document.getElementById('profile-status');
            const profileEmail = document.getElementById('profile-email');
            const profilePhone = document.getElementById('profile-phone');
            const profileDepartment = document.getElementById('profile-department');
            const profilePosition = document.getElementById('profile-position');
            
            if (!profileModal) return;
            
            // Show loading state
            profileName.textContent = 'Loading...';
            profileStatus.textContent = '';
            profileEmail.textContent = '';
            profilePhone.textContent = '';
            profileDepartment.textContent = '';
            profilePosition.textContent = '';
            profileAvatar.src = DEFAULT_AVATAR;
            
            // Store the user ID on the modal for later use
            profileModal.dataset.userId = userId;
            
            // Show the modal
            profileModal.classList.remove('hidden');
            
            // Fetch user details
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(`/api/users/${userId}/profile`, {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch user profile');
                }
                return response.json();
            })
            .then(user => {
                // Populate the modal with user data
                if (user) {
                    profileName.textContent = `${user.first_name} ${user.last_name}`;
                    profileStatus.textContent = user.last_seen >= (Date.now() - 2 * 60 * 1000) ? 'Online' : 'Offline';
                    profileEmail.textContent = user.email || 'N/A';
                    profilePhone.textContent = user.phone || 'N/A';
                    profileDepartment.textContent = user.department?.name || 'N/A';
                    profilePosition.textContent = user.position?.name || 'N/A';
                    
                    if (user.profile_image) {
                        profileAvatar.src = user.profile_image;
                    }
                    
                    // Update status color
                    if (user.last_seen >= (Date.now() - 2 * 60 * 1000)) {
                        profileStatus.classList.add('text-green-500');
                        profileStatus.classList.remove('text-gray-500');
                    } else {
                        profileStatus.classList.remove('text-green-500');
                        profileStatus.classList.add('text-gray-500');
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching user profile:', error);
                showToast('Could not load user profile', 'error');
                
                // Show error state
                profileName.textContent = 'User not found';
                profileStatus.textContent = 'Error loading profile';
                profileStatus.classList.remove('text-green-500');
                profileStatus.classList.add('text-red-500');
            });
        }
        
        // Function to show group info modal
        function showGroupInfoModal(conversationId) {
            // This would be implemented to show a modal with group details
            // For demo purposes, we'll just show a toast
            showToast(`Viewing group information for conversation ${conversationId}`, 'info');
            
            // In a real implementation, you'd make an AJAX call to get group details
            // and then populate and show a modal
        }
        
        // Function to show shared files modal
        function showSharedFilesModal(conversationId) {
            const filesModal = document.getElementById('shared-files-modal');
            const mediaGrid = document.getElementById('media-grid');
            const documentsList = document.getElementById('documents-list');
            const linksList = document.getElementById('links-list');
            const mediaEmpty = document.getElementById('media-empty');
            const documentsEmpty = document.getElementById('documents-empty');
            const linksEmpty = document.getElementById('links-empty');
            
            if (!filesModal) return;
            
            // Show loading states
            mediaGrid.innerHTML = `<div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                <i class="fas fa-spinner fa-spin text-2xl"></i>
            </div>`;
            
            documentsList.innerHTML = `<div class="bg-gray-50 p-3 rounded-lg flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <div class="ml-3 flex-1">
                    <div class="h-4 bg-gray-200 rounded w-2/3 mb-1"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/3"></div>
                </div>
            </div>`;
            
            linksList.innerHTML = `<div class="bg-gray-50 p-3 rounded-lg flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <div class="ml-3 flex-1">
                    <div class="h-4 bg-gray-200 rounded w-2/3 mb-1"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/3"></div>
                </div>
            </div>`;
            
            mediaEmpty.classList.add('hidden');
            documentsEmpty.classList.add('hidden');
            linksEmpty.classList.add('hidden');
            
            // Show the modal
            filesModal.classList.remove('hidden');
            
            // Fetch shared files
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(`/messaging/${conversationId}/files`, {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch shared files');
                }
                return response.json();
            })
            .then(data => {
                // Process and display the files
                
                // Media files (images, videos)
                if (data.media && data.media.length > 0) {
                    mediaGrid.innerHTML = '';
                    data.media.forEach(item => {
                        const isImage = item.type.startsWith('image/');
                        const isVideo = item.type.startsWith('video/');
                        
                        let html = `<div class="aspect-square bg-gray-100 rounded-lg overflow-hidden relative group">`;
                        
                        if (isImage) {
                            html += `<img src="${item.url}" class="w-full h-full object-cover" alt="${item.name}">`;
                        } else if (isVideo) {
                            html += `
                                <video class="w-full h-full object-cover" poster="${item.thumbnail || ''}">
                                    <source src="${item.url}" type="${item.type}">
                                </video>
                                <div class="absolute inset-0 flex items-center justify-center text-white bg-black bg-opacity-20">
                                    <i class="fas fa-play-circle text-3xl"></i>
                                </div>
                            `;
                        }
                        
                        html += `
                            <div class="absolute inset-0 bg-black bg-opacity-60 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                <a href="${item.url}" target="_blank" class="text-white bg-primary-500 hover:bg-primary-600 w-8 h-8 rounded-full flex items-center justify-center mx-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="${item.url}" download="${item.name}" class="text-white bg-green-500 hover:bg-green-600 w-8 h-8 rounded-full flex items-center justify-center mx-1">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>`;
                        
                        mediaGrid.innerHTML += html;
                    });
                } else {
                    mediaGrid.innerHTML = '';
                    mediaEmpty.classList.remove('hidden');
                }
                
                // Documents (PDFs, DOCs, etc.)
                if (data.documents && data.documents.length > 0) {
                    documentsList.innerHTML = '';
                    data.documents.forEach(item => {
                        const fileIcon = getFileIcon(item.type);
                        const fileDate = new Date(item.created_at).toLocaleDateString();
                        
                        const html = `
                            <div class="bg-white border border-gray-200 p-3 rounded-lg flex items-center hover:bg-gray-50 transition-colors duration-200">
                                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center ${fileIcon.bg} ${fileIcon.color}">
                                    <i class="${fileIcon.icon}"></i>
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-gray-800 truncate">${item.name}</h4>
                                        <span class="text-xs text-gray-500 ml-2 flex-shrink-0">${fileDate}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 truncate">${formatFileSize(item.size)}</p>
                                </div>
                                <div class="ml-2 flex-shrink-0">
                                    <a href="${item.url}" target="_blank" class="text-gray-500 hover:text-primary-500 w-8 h-8 flex items-center justify-center">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="${item.url}" download="${item.name}" class="text-gray-500 hover:text-green-500 w-8 h-8 flex items-center justify-center">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        `;
                        
                        documentsList.innerHTML += html;
                    });
                } else {
                    documentsList.innerHTML = '';
                    documentsEmpty.classList.remove('hidden');
                }
                
                // Links
                if (data.links && data.links.length > 0) {
                    linksList.innerHTML = '';
                    data.links.forEach(item => {
                        const linkDate = new Date(item.created_at).toLocaleDateString();
                        
                        const html = `
                            <div class="bg-white border border-gray-200 p-3 rounded-lg flex items-center hover:bg-gray-50 transition-colors duration-200">
                                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center bg-blue-100 text-blue-500">
                                    <i class="fas fa-link"></i>
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-gray-800 truncate">${item.title || 'Link'}</h4>
                                        <span class="text-xs text-gray-500 ml-2 flex-shrink-0">${linkDate}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 truncate">${item.url}</p>
                                </div>
                                <div class="ml-2 flex-shrink-0">
                                    <a href="${item.url}" target="_blank" class="text-gray-500 hover:text-primary-500 w-8 h-8 flex items-center justify-center">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                        `;
                        
                        linksList.innerHTML += html;
                    });
                } else {
                    linksList.innerHTML = '';
                    linksEmpty.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error fetching shared files:', error);
                showToast('Could not load shared files', 'error');
                
                // Show empty states
                mediaGrid.innerHTML = '';
                documentsList.innerHTML = '';
                linksList.innerHTML = '';
                mediaEmpty.classList.remove('hidden');
                documentsEmpty.classList.remove('hidden');
                linksEmpty.classList.remove('hidden');
            });
        }
        
        // Helper function to get file icon
        function getFileIcon(mimeType) {
            let icon = 'fas fa-file';
            let bg = 'bg-gray-100';
            let color = 'text-gray-500';
            
            if (mimeType.startsWith('image/')) {
                icon = 'fas fa-file-image';
                bg = 'bg-green-100';
                color = 'text-green-500';
            } else if (mimeType.startsWith('video/')) {
                icon = 'fas fa-file-video';
                bg = 'bg-red-100';
                color = 'text-red-500';
            } else if (mimeType === 'application/pdf') {
                icon = 'fas fa-file-pdf';
                bg = 'bg-red-100';
                color = 'text-red-500';
            } else if (mimeType.includes('word') || mimeType === 'application/msword' || 
                    mimeType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                icon = 'fas fa-file-word';
                bg = 'bg-blue-100';
                color = 'text-blue-500';
            } else if (mimeType.includes('excel') || mimeType === 'application/vnd.ms-excel' || 
                    mimeType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                icon = 'fas fa-file-excel';
                bg = 'bg-green-100';
                color = 'text-green-500';
            } else if (mimeType.includes('powerpoint') || mimeType === 'application/vnd.ms-powerpoint' || 
                    mimeType === 'application/vnd.openxmlformats-officedocument.presentationml.presentation') {
                icon = 'fas fa-file-powerpoint';
                bg = 'bg-orange-100';
                color = 'text-orange-500';
            } else if (mimeType === 'application/zip' || mimeType === 'application/x-zip-compressed') {
                icon = 'fas fa-file-archive';
                bg = 'bg-yellow-100';
                color = 'text-yellow-500';
            } else if (mimeType.startsWith('audio/')) {
                icon = 'fas fa-file-audio';
                bg = 'bg-purple-100';
                color = 'text-purple-500';
            } else if (mimeType === 'text/plain') {
                icon = 'fas fa-file-alt';
                bg = 'bg-gray-100';
                color = 'text-gray-500';
            } else if (mimeType === 'text/html' || mimeType === 'application/xhtml+xml') {
                icon = 'fas fa-file-code';
                bg = 'bg-indigo-100';
                color = 'text-indigo-500';
            }
            
            return { icon, bg, color };
        }
        
        // Helper function to format file size
        function formatFileSize(bytes) {
            if (!bytes) return '0 Bytes';
            
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        // Function to block a user
        function blockUser(userId) {
            // This would be implemented to block a user via API
            // For demo purposes, we'll just show a toast
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/api/users/block', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ user_id: userId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('User has been blocked successfully', 'success');
                    
                    // Optionally redirect back to the conversations list
                    window.location.href = '/messaging';
                } else {
                    showToast(data.message || 'Failed to block user', 'error');
                }
            })
            .catch(error => {
                console.error('Error blocking user:', error);
                showToast('Could not block user', 'error');
            });
        }
        
        // Toast notification function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-4 py-2 rounded-lg text-sm shadow-lg z-50';
            
            if (type === 'error') toast.className += ' bg-red-600';
            if (type === 'success') toast.className += ' bg-green-600';
            if (type === 'info') toast.className += ' bg-gray-800';
            
            toast.textContent = message;
            document.body.appendChild(toast);
            
            // Animate in
            toast.style.opacity = '0';
            toast.style.transform = 'translate(-50%, 20px)';
            toast.style.transition = 'opacity 0.3s, transform 0.3s';
            
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translate(-50%, 0)';
            }, 10);
            
            // Remove after 3 seconds
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translate(-50%, -20px)';
                
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // Show message actions menu
        window.showMessageActions = function(button, event) {
            event.preventDefault();
            event.stopPropagation();
            
            // Get message info
            currentMessageId = button.getAttribute('data-message-id');
            const messageContainer = document.querySelector(`.message-container[data-message-id="${currentMessageId}"]`);
            
            if (!messageContainer) return;
            
            // Get message text content
            const messageBubble = messageContainer.querySelector('.message-bubble-mine, .message-bubble-other');
            if (messageBubble) {
                // Skip quoted message part if exists
                const quotedMessage = messageBubble.querySelector('.quoted-message');
                if (quotedMessage) {
                    // Clone the bubble to avoid modifying the DOM
                    const bubbleClone = messageBubble.cloneNode(true);
                    bubbleClone.removeChild(bubbleClone.querySelector('.quoted-message'));
                    currentMessageText = bubbleClone.textContent.trim();
                } else {
                    currentMessageText = messageBubble.textContent.trim();
                }
            }
            
            // Get user name (from avatar alt or group display)
            if (messageContainer.querySelector('img')) {
                currentMessageUser = messageContainer.querySelector('img').getAttribute('alt');
            } else if (messageContainer.querySelector('.text-primary-500')) {
                currentMessageUser = messageContainer.querySelector('.text-primary-500').textContent.trim();
            } else {
                currentMessageUser = 'User';
            }
            
            // Position the menu relative to the button
            const rect = button.getBoundingClientRect();
            messageActionsMenu.style.top = `${rect.bottom + 5}px`;
            
            // Position menu left or right based on available space
            if (rect.left + 192 > window.innerWidth) {
                messageActionsMenu.style.right = `${window.innerWidth - rect.right}px`;
                messageActionsMenu.style.left = 'auto';
            } else {
                messageActionsMenu.style.left = `${rect.left}px`;
                messageActionsMenu.style.right = 'auto';
            }
            
            // Show the menu with a fade-in effect
            messageActionsMenu.classList.remove('hidden');
            messageActionsMenu.style.opacity = '0';
            messageActionsMenu.style.transform = 'translateY(-5px)';
            
            // Trigger reflow to enable transition
            messageActionsMenu.offsetHeight;
            
            messageActionsMenu.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
            messageActionsMenu.style.opacity = '1';
            messageActionsMenu.style.transform = 'translateY(0)';
            
            // Add global click listener to hide menu
            setTimeout(() => {
                window.addEventListener('click', hideMessageActions);
            }, 0);
        };
        
        // Hide message actions menu
        function hideMessageActions() {
            if (messageActionsMenu) {
                messageActionsMenu.style.opacity = '0';
                messageActionsMenu.style.transform = 'translateY(-5px)';
                
                setTimeout(() => {
                    messageActionsMenu.classList.add('hidden');
                }, 200);
                
                window.removeEventListener('click', hideMessageActions);
            }
        }
        
        // Reply action
        const actionReply = document.getElementById('action-reply');
        const replyPreview = document.getElementById('reply-preview');
        const replyUserName = document.getElementById('reply-user-name');
        const replyText = document.getElementById('reply-text');
        const replyToField = document.getElementById('reply_to');
        const cancelReply = document.getElementById('cancel-reply');
        messageInput = document.getElementById('message-input');
        
        if (actionReply && replyPreview && replyUserName && replyText && replyToField && cancelReply) {
            actionReply.addEventListener('click', function() {
                // Set reply data
                replyToField.value = currentMessageId;
                replyUserName.textContent = currentMessageUser;
                replyText.textContent = currentMessageText;
                
                // Show reply preview
                replyPreview.classList.remove('hidden');
                
                // Focus input field
                messageInput.focus();
                
                // Hide actions menu
                hideMessageActions();
            });
            
            cancelReply.addEventListener('click', function() {
                clearReply();
            });
            
            // Clear reply data
            function clearReply() {
                replyToField.value = '';
                replyPreview.classList.add('hidden');
            }
            
            // Also clear reply on form submit
            const messageForm = document.getElementById('message-form');
            if (messageForm) {
                const originalSubmit = messageForm.onsubmit;
                messageForm.addEventListener('submit', function() {
                    // Allow normal submission to proceed
                    setTimeout(clearReply, 100);
                });
            }
        }
        
        // Copy text action
        const actionCopy = document.getElementById('action-copy');
        if (actionCopy) {
            actionCopy.addEventListener('click', function() {
                if (currentMessageText) {
                    navigator.clipboard.writeText(currentMessageText)
                        .then(() => {
                            // Show a toast notification
                            const toast = document.createElement('div');
                            toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-4 py-2 rounded-full text-sm shadow-lg z-50';
                            toast.innerText = 'Text copied to clipboard';
                            document.body.appendChild(toast);
                            
                            // Remove after 2 seconds
                            setTimeout(() => {
                                toast.style.opacity = '0';
                                toast.style.transition = 'opacity 0.3s';
                                setTimeout(() => toast.remove(), 300);
                            }, 2000);
                        })
                        .catch(err => console.error('Failed to copy text: ', err));
                }
                
                hideMessageActions();
            });
        }
        
        // Forward action
        const actionForward = document.getElementById('action-forward');
        if (actionForward) {
            actionForward.addEventListener('click', function() {
                // Show forward modal - implementation would go here
                // For now, show a toast notification
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-4 py-2 rounded-full text-sm shadow-lg z-50';
                toast.innerText = 'Forward feature coming soon';
                document.body.appendChild(toast);
                
                // Remove after 2 seconds
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity 0.3s';
                    setTimeout(() => toast.remove(), 300);
                }, 2000);
                
                hideMessageActions();
            });
        }
        
        // Delete action
        const actionDelete = document.getElementById('action-delete');
        if (actionDelete) {
            actionDelete.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete this message?')) {
                    // Delete message via API
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    fetch(`/api/messages/${currentMessageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove message from DOM
                            const messageElement = document.querySelector(`.message-container[data-message-id="${currentMessageId}"]`);
                            if (messageElement) {
                                messageElement.style.opacity = '0';
                                messageElement.style.height = '0';
                                messageElement.style.marginBottom = '0';
                                messageElement.style.overflow = 'hidden';
                                messageElement.style.transition = 'opacity 0.3s, height 0.3s, margin 0.3s';
                                
                                setTimeout(() => {
                                    messageElement.remove();
                                }, 300);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting message:', error);
                        // Show error toast
                        const toast = document.createElement('div');
                        toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-red-600 text-white px-4 py-2 rounded-full text-sm shadow-lg z-50';
                        toast.innerText = 'Error deleting message';
                        document.body.appendChild(toast);
                        
                        // Remove after 2 seconds
                        setTimeout(() => {
                            toast.style.opacity = '0';
                            toast.style.transition = 'opacity 0.3s';
                            setTimeout(() => toast.remove(), 300);
                        }, 2000);
                    });
                }
                
                hideMessageActions();
            });
        }
        
        // Initialize messaging app object for use by other scripts
        window.messagingApp = window.messagingApp || {};
        
        // Function to append a new message to the chat
        window.messagingApp.appendMessage = function(message) {
            if (!chatMessages || !message) return;
            
            // Check if message already exists in DOM
            if (document.querySelector(`[data-message-id="${message.id}"]`)) {
                return;
            }
            
            // Get current date to check if we need a new date separator
            const messageDate = new Date(message.created_at);
            const messageDateStr = messageDate.toISOString().split('T')[0];
            const lastDateSeparator = Array.from(document.querySelectorAll('.day-separator')).pop();
            let needsDateSeparator = true;
            
            if (lastDateSeparator) {
                const lastDateText = lastDateSeparator.querySelector('span').textContent.trim();
                // This is a simplified check - you might need to adjust based on your date format
                if (lastDateText.includes(messageDate.toLocaleDateString())) {
                    needsDateSeparator = false;
                }
            }
            
            // Add date separator if needed
            if (needsDateSeparator) {
                const dateSeparator = document.createElement('div');
                dateSeparator.className = 'day-separator';
                dateSeparator.innerHTML = `
                    <span class="bg-white px-4 py-1 text-xs text-gray-500 rounded-full border border-gray-200 shadow-sm">
                        ${messageDate.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}
                    </span>
                `;
                chatMessages.appendChild(dateSeparator);
            }
            
            // Handle system messages specially
            if (message.is_system) {
                const messageElement = document.createElement('div');
                messageElement.className = 'flex justify-center my-3 message-container';
                messageElement.setAttribute('data-message-id', message.id);
                
                let systemContent = '';
                
                if (message.system_action === 'user_added' && message.affected_user) {
                    systemContent = `<span class="font-medium">${message.user_name}</span> added <span class="font-medium">${message.affected_user.first_name} ${message.affected_user.last_name}</span> to the group`;
                } else if (message.system_action === 'group_created') {
                    systemContent = `<i class="fas fa-users mr-1"></i> ${message.body || 'Group created'}`;
                } else {
                    systemContent = message.body;
                }
                
                messageElement.innerHTML = `
                    <div class="bg-gray-100 text-gray-600 text-xs px-4 py-2 rounded-full text-center max-w-sm system-message">
                        ${systemContent}
                    </div>
                `;
                
                chatMessages.appendChild(messageElement);
            }
            // Create message element based on whether it's from current user or not
            else {
                const isMine = message.user_id === CURRENT_USER_ID;
                const messageElement = document.createElement('div');
                messageElement.className = isMine 
                    ? 'flex items-start flex-row-reverse mb-4 group message-container' 
                    : 'flex items-start mb-4 group message-container';
                messageElement.setAttribute('data-message-id', message.id);
                
                if (isMine) {
                    // My message HTML
                    let content = '<div class="relative">';
                    
                    if (message.attachment) {
                        // Attachment handling
                        const isImage = message.attachment_type && message.attachment_type.startsWith('image/');
                        const isPdf = message.attachment_type === 'application/pdf';
                        
                        content += `
                            <div class="message-bubble-mine px-4 py-3 text-sm max-w-[85%] md:max-w-[70%] lg:max-w-[60%]">
                                ${message.reply_to && message.reply_to_message ? `
                                    <div class="quoted-message mb-2 p-2 rounded bg-white bg-opacity-60 border-l-2 border-primary-400 text-xs text-gray-600">
                                        <div class="font-medium text-primary-600">${message.reply_to_message.user_name}</div>
                                        <div class="truncate">${message.reply_to_message.attachment_name || message.reply_to_message.body}</div>
                                    </div>
                                ` : ''}
                                
                                ${isImage 
                                    ? `<div class="message-image-container" onclick="openImageModal('${message.attachment}')">
                                        <img src="${message.attachment}" loading="lazy" class="message-image rounded-lg" alt="Image">
                                       </div>`
                                    : `<div class="flex items-center bg-white bg-opacity-80 p-3 rounded-lg shadow-sm">
                                        <i class="fas fa-${isPdf ? 'file-pdf text-red-500' : 'file text-primary-500'} text-xl mr-3"></i>
                                        <div>
                                            <div class="font-medium text-gray-800">${message.attachment_name || 'Attachment'}</div>
                                            <div class="text-xs text-gray-500">
                                                ${isPdf ? 'PDF Document' : 'File'}
                                            </div>
                                        </div>
                                       </div>`
                                }
                                ${message.body ? `<div class="mt-2">${message.body}</div>` : ''}
                            </div>
                        `;
                    } else {
                        content += `
                            <div class="message-bubble-mine px-4 py-3 text-sm max-w-[85%] md:max-w-[70%] lg:max-w-[60%]">
                                ${message.reply_to && message.reply_to_message ? `
                                    <div class="quoted-message mb-2 p-2 rounded bg-white bg-opacity-60 border-l-2 border-primary-400 text-xs text-gray-600">
                                        <div class="font-medium text-primary-600">${message.reply_to_message.user_name}</div>
                                        <div class="truncate">${message.reply_to_message.attachment_name || message.reply_to_message.body}</div>
                                    </div>
                                ` : ''}
                                ${message.body}
                            </div>
                        `;
                    }
                    
                    // Add message actions button
                    content += `
                        <div class="absolute right-0 top-0 mt-2 -mr-10 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <button class="message-action-btn w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-500 hover:text-gray-700 transition-colors duration-200"
                                    data-message-id="${message.id}" 
                                    onclick="showMessageActions(this, event)">
                                <i class="fas fa-ellipsis-v text-xs"></i>
                            </button>
                        </div>
                    </div>
                    `;
                    
                    content += `
                        <div class="text-xs text-gray-400 mt-1 mr-1 text-right flex items-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            ${messageDate.toLocaleTimeString([], {hour: 'numeric', minute:'2-digit'})}
                            <i class="fas fa-check${message.is_read ? '-double text-primary-500' : ''} ml-1 text-xs"></i>
                        </div>
                    `;
                    
                    messageElement.innerHTML = content;
                } else {
                    // Message from other user
                    const userName = message.user_name || 'Unknown User';
                    const userAvatar = message.user_avatar || DEFAULT_AVATAR;
                    
                    let content = `
                        <div class="flex-shrink-0 mr-2 mt-1">
                            <img src="${userAvatar}" loading="lazy" class="w-8 h-8 rounded-full object-cover shadow-sm" alt="${userName}">
                        </div>
                        <div class="max-w-[85%] md:max-w-[70%] lg:max-w-[60%] relative">
                    `;
                    
                    // Add user name if in group chat
                    if (IS_GROUP_CHAT) {
                        content += `<div class="text-xs font-medium text-primary-500 mb-1">${userName}</div>`;
                    }
                    
                    if (message.attachment) {
                        // Attachment handling
                        const isImage = message.attachment_type && message.attachment_type.startsWith('image/');
                        const isPdf = message.attachment_type === 'application/pdf';
                        
                        content += `
                            <div class="message-bubble-other px-4 py-3 text-sm text-gray-800">
                                ${message.reply_to && message.reply_to_message ? `
                                    <div class="quoted-message mb-2 p-2 rounded bg-gray-100 border-l-2 border-primary-400 text-xs text-gray-600">
                                        <div class="font-medium text-primary-600">${message.reply_to_message.user_name}</div>
                                        <div class="truncate">${message.reply_to_message.attachment_name || message.reply_to_message.body}</div>
                                    </div>
                                ` : ''}
                                
                                ${isImage 
                                    ? `<a href="${message.attachment}" target="_blank" class="block">
                                        <img src="${message.attachment}" loading="lazy" class="rounded-lg max-w-full" alt="Image">
                                       </a>`
                                    : `<div class="flex items-center">
                                        <i class="fas fa-${isPdf ? 'file-pdf text-red-500' : 'file text-primary-500'} text-xl mr-3"></i>
                                        <div>
                                            <div class="font-medium">${message.attachment_name || 'Attachment'}</div>
                                            <div class="text-xs text-gray-500">
                                                ${isPdf ? 'PDF Document' : 'File'}
                                            </div>
                                        </div>
                                       </div>`
                                }
                                ${message.body ? `<div class="mt-2">${message.body}</div>` : ''}
                            </div>
                        `;
                    } else {
                        content += `
                            <div class="message-bubble-other px-4 py-3 text-sm text-gray-800">
                                ${message.reply_to && message.reply_to_message ? `
                                    <div class="quoted-message mb-2 p-2 rounded bg-gray-100 border-l-2 border-primary-400 text-xs text-gray-600">
                                        <div class="font-medium text-primary-600">${message.reply_to_message.user_name}</div>
                                        <div class="truncate">${message.reply_to_message.attachment_name || message.reply_to_message.body}</div>
                                    </div>
                                ` : ''}
                                ${message.body}
                            </div>
                        `;
                    }
                    
                    // Add message actions button
                    content += `
                        <div class="absolute right-0 top-0 mt-2 -mr-10 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <button class="message-action-btn w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-500 hover:text-gray-700 transition-colors duration-200"
                                    data-message-id="${message.id}" 
                                    onclick="showMessageActions(this, event)">
                                <i class="fas fa-ellipsis-v text-xs"></i>
                            </button>
                        </div>
                    
                        <div class="text-xs text-gray-400 mt-1 ml-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            ${messageDate.toLocaleTimeString([], {hour: 'numeric', minute:'2-digit'})}
                        </div>
                    </div>
                    `;
                    
                    messageElement.innerHTML = content;
                }
                
                chatMessages.appendChild(messageElement);
            }
            
            // Scroll to bottom after adding message
            setTimeout(() => {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 50);
        };
        
        // Function to mark messages as read
        window.messagingApp.markMessagesAsRead = function(messageIds) {
            if (!messageIds || !messageIds.length) return;
            
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/messaging/mark-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ messageIds })
            })
            .then(response => response.json())
            .catch(error => console.error('Error marking messages as read:', error));
        };
        
        // Mark all messages in a conversation as read
        window.messagingApp.markAllAsRead = function(conversationId) {
            if (!conversationId) return;
            
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(`/messaging/${conversationId}/mark-all-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .catch(error => console.error('Error marking conversation as read:', error));
        };
        
        // Search contacts functionality with debounce
        const searchInput = document.getElementById('search-contacts');
        if (searchInput) {
            let debounceTimer;
            
            searchInput.addEventListener('input', function(e) {
                clearTimeout(debounceTimer);
                
                debounceTimer = setTimeout(() => {
                    const searchTerm = e.target.value.toLowerCase().trim();
                    const contactItems = document.querySelectorAll('.contact-item');
                    
                    contactItems.forEach(item => {
                        const name = item.querySelector('h3').textContent.toLowerCase();
                        const message = item.querySelector('p').textContent.toLowerCase();
                        
                        if (name.includes(searchTerm) || message.includes(searchTerm)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                }, 200);
            });
        }
        
        // Add typing indicator functionality
        function setupTypingIndicator() {
            if (!messageInput || !window.Echo) return;
            
            const activeConversationEl = document.querySelector('[data-active="true"]');
            if (!activeConversationEl) return;
            
            const conversationId = activeConversationEl.dataset.conversationId;
            const typingIndicator = document.getElementById('typing-indicator');
            const typingTimeout = 1500; // Reduced to 1.5 seconds for better responsiveness
            const typingThrottle = 1000; // Send typing status at most once per second
            
            let typingTimer;
            let throttleTimer;
            let isTyping = false;
            let lastTypingTime = 0;
            
            // Active typing users tracker with auto-expiration
            const activeTypers = new Map(); // userId -> timeout
            
            // Function to send typing status to server - with throttling
            function sendTypingStatus(typingStatus) {
                const now = Date.now();
                
                // Skip if we've sent typing status recently (throttle)
                if (typingStatus && now - lastTypingTime < typingThrottle) {
                    return;
                }
                
                lastTypingTime = now;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch('/api/messages/typing', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        conversation_id: conversationId,
                        is_typing: typingStatus
                    })
                })
                .catch(function(error) {
                    console.error('Error sending typing status:', error);
                });
            }
            
            // Update typing indicator display based on active typers
            function updateTypingIndicator() {
                if (!typingIndicator) return;
                
                // Remove expired typers first
                const now = Date.now();
                for (const [userId, expiry] of activeTypers.entries()) {
                    if (now > expiry) {
                        activeTypers.delete(userId);
                    }
                }
                
                if (activeTypers.size === 0) {
                    // No one is typing, hide the indicator
                    typingIndicator.style.display = 'none';
                    return;
                }
                
                // Someone is typing, show the indicator 
                typingIndicator.style.display = 'flex';
                
                // Update avatar if we have user info for the active typer
                const typerId = [...activeTypers.keys()][0];
                const typerInfo = activeTypers.get(typerId);
                
                if (typerInfo) {
                    // Update avatar
                    if (typerInfo.avatar) {
                        const avatarContainer = typingIndicator.querySelector('.flex-shrink-0');
                        if (avatarContainer) {
                            avatarContainer.innerHTML = `<img src="${typerInfo.avatar}" loading="lazy" class="w-8 h-8 rounded-full object-cover shadow-sm" alt="${typerInfo.name || 'User'}">`;
                        }
                    }
                    
                    // Update name
                    const nameElement = typingIndicator.querySelector('.typing-name');
                    if (nameElement) {
                        if (activeTypers.size === 1) {
                            nameElement.textContent = `${typerInfo.name || 'Someone'} is typing...`;
                        } else {
                            nameElement.textContent = `${activeTypers.size} people are typing...`;
                        }
                    }
                }
                
                // Make sure chat scrolls to show the typing indicator
                requestAnimationFrame(() => {
                    if (chatMessages) {
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }
                });
            }
            
            // More responsive input tracking with key events
            const handleUserTyping = () => {
                clearTimeout(typingTimer);
                
                if (!isTyping) {
                    isTyping = true;
                    sendTypingStatus(true);
                }
                
                typingTimer = setTimeout(function() {
                    isTyping = false;
                    sendTypingStatus(false);
                }, typingTimeout);
            };
            
            // Listen for input events AND keydown for better responsiveness
            messageInput.addEventListener('input', handleUserTyping);
            messageInput.addEventListener('keydown', function(e) {
                // Only trigger on actual typing keys, not navigation
                const isModifier = e.ctrlKey || e.altKey || e.metaKey;
                const isNavigation = e.key === 'ArrowUp' || e.key === 'ArrowDown' || 
                                    e.key === 'ArrowLeft' || e.key === 'ArrowRight' ||
                                    e.key === 'Home' || e.key === 'End' || e.key === 'PageUp' ||
                                    e.key === 'PageDown';
                
                if (!isModifier && !isNavigation && e.key !== 'Tab' && e.key !== 'Escape') {
                    handleUserTyping();
                }
            });
            
            // Reset typing when form is submitted
            const messageForm = document.getElementById('message-form');
            if (messageForm) {
                messageForm.addEventListener('submit', function() {
                    clearTimeout(typingTimer);
                    isTyping = false;
                    sendTypingStatus(false);
                });
            }
            
            // Also reset typing when user loses focus
            messageInput.addEventListener('blur', function() {
                if (isTyping) {
                    clearTimeout(typingTimer);
                    isTyping = false;
                    sendTypingStatus(false);
                }
            });
            
            // Listen for typing events from other users
            window.Echo.private(`chat.${conversationId}`)
                .listen('.user.typing', function(data) {
                    if (data.user_id === CURRENT_USER_ID) return;
                    
                    // Get user info for the typing indicator
                    const userName = data.user_name || 'Someone';
                    const userAvatar = data.user_avatar || DEFAULT_AVATAR;
                    
                    if (data.is_typing) {
                        // Add/refresh this user to active typers with expiration
                        const expiryTime = Date.now() + (typingTimeout * 2); // Double the timeout for safety
                        activeTypers.set(data.user_id, { 
                            expiry: expiryTime,
                            name: userName,
                            avatar: userAvatar
                        });
                    } else {
                        // User stopped typing, remove from active typers
                        activeTypers.delete(data.user_id);
                    }
                    
                    // Update the typing indicator
                    updateTypingIndicator();
                });
            
            // Periodically clean up expired typing statuses
            setInterval(updateTypingIndicator, 1000);
        }
        
        // Auto-resize textarea with maximum height
        if (messageInput) {
            // Initial height
            messageInput.style.height = 'auto';
            messageInput.style.height = Math.min(messageInput.scrollHeight, 120) + 'px';
            
            // Toggle between microphone and send button based on textarea content
            const microphoneBtn = document.getElementById('microphone-btn');
            const sendMessageBtn = document.getElementById('send-message-btn');
            
            // Function to toggle button visibility
            function toggleSendButton() {
                if (messageInput.value.trim() === '') {
                    microphoneBtn.style.display = 'flex';
                    sendMessageBtn.style.display = 'none';
                } else {
                    microphoneBtn.style.display = 'none';
                    sendMessageBtn.style.display = 'flex';
                }
            }
            
            // Initial check
            toggleSendButton();
            
            messageInput.addEventListener('input', function() {
                // Auto-resize
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';
                
                // Toggle buttons
                toggleSendButton();
            });
            
            // Reset height when form is submitted
            const messageForm = document.getElementById('message-form');
            if (messageForm) {
                messageForm.addEventListener('submit', function() {
                    setTimeout(() => {
                        messageInput.style.height = 'auto';
                        messageInput.style.height = Math.min(messageInput.scrollHeight, 120) + 'px';
                        toggleSendButton();
                    }, 0);
                });
            }
        }
        
        // Setup typing indicator
        setupTypingIndicator();
        
        // Ensure responsive layout on window resize
        function handleResize() {
            const isMobile = window.innerWidth < 768;
            const sidebar = document.getElementById('sidebar');
            const chatContainer = document.getElementById('chat-container');
            const activeConversationEl = document.querySelector('[data-active="true"]');
            
            if (isMobile && activeConversationEl) {
                sidebar.style.transform = 'translateX(-100%)';
                chatContainer.style.marginLeft = '0';
            } else {
                sidebar.style.transform = '';
                chatContainer.style.marginLeft = '';
            }
            
            // Reset textarea height
            if (messageInput) {
                messageInput.style.height = 'auto';
                messageInput.style.height = Math.min(messageInput.scrollHeight, 120) + 'px';
            }
        }
        
        // Handle initial load and window resize
        handleResize();
        window.addEventListener('resize', handleResize);
        window.addEventListener('orientationchange', function() {
            setTimeout(handleResize, 300);
        });

        // Search Messages Functionality
        const searchMessagesBtn = document.getElementById('search-messages-btn');
        const searchMessagesContainer = document.getElementById('search-messages-container');
        const searchMessagesInput = document.getElementById('search-messages-input');
        const closeSearchBtn = document.getElementById('close-search-btn');
        const searchResults = document.getElementById('search-results');
        let searchTimeout;
        
        // Toggle search interface
        if (searchMessagesBtn) {
            searchMessagesBtn.addEventListener('click', function() {
                searchMessagesContainer.classList.toggle('hidden');
                if (!searchMessagesContainer.classList.contains('hidden')) {
                    searchMessagesInput.focus();
                }
            });
        }
        
        // Close search
        if (closeSearchBtn) {
            closeSearchBtn.addEventListener('click', function() {
                searchMessagesContainer.classList.add('hidden');
                searchMessagesInput.value = '';
                searchResults.classList.add('hidden');
            });
        }
        
        // Search messages when typing
        if (searchMessagesInput) {
            searchMessagesInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                
                const query = this.value.trim();
                if (query.length < 2) {
                    searchResults.classList.add('hidden');
                    return;
                }
                
                // Show "Searching..." indicator
                searchResults.innerHTML = '<div class="text-xs text-gray-500 px-2 py-1">Searching...</div>';
                searchResults.classList.remove('hidden');
                
                // Debounce search to prevent too many requests
                searchTimeout = setTimeout(() => {
                    performMessageSearch(query);
                }, 500);
            });
        }
        
        // Search messages function
        function performMessageSearch(query) {
            const conversationId = document.querySelector('[data-active="true"]')?.dataset.conversationId;
            if (!conversationId) return;
            
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(`/messaging/${conversationId}/search?query=${encodeURIComponent(query)}`, {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.results.length === 0) {
                    searchResults.innerHTML = '<div class="text-sm text-gray-500 p-4 text-center">No messages found</div>';
                    return;
                }
                
                let html = `<div class="text-xs text-gray-500 px-2 py-1">${data.count} results found</div>`;
                
                data.results.forEach(message => {
                    html += `
                        <div class="search-result-item p-3 hover:bg-gray-50 cursor-pointer border-t border-gray-100 transition-colors duration-200" 
                             data-message-id="${message.id}" 
                             data-timestamp="${message.timestamp}">
                            <div class="flex items-start">
                                <div class="text-xs text-gray-500 shrink-0 w-24">${message.created_at}</div>
                                <div class="ml-2 flex-1">
                                    <div class="font-medium text-sm text-gray-800">${message.user_name}</div>
                                    <div class="text-sm text-gray-600">${message.highlighted_body}</div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                searchResults.innerHTML = html;
                
                // Add click handlers to search results
                document.querySelectorAll('.search-result-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const messageId = this.dataset.messageId;
                        const timestamp = this.dataset.timestamp;
                        
                        // Close search
                        searchMessagesContainer.classList.add('hidden');
                        
                        // Scroll to message
                        scrollToMessage(messageId, timestamp);
                    });
                });
            })
            .catch(error => {
                console.error('Error searching messages:', error);
                searchResults.innerHTML = '<div class="text-sm text-red-500 p-4 text-center">An error occurred while searching</div>';
            });
        }
        
        // Scroll to specific message and highlight it
        function scrollToMessage(messageId, timestamp) {
            const messageElement = document.querySelector(`.message-container[data-message-id="${messageId}"]`);
            
            if (messageElement) {
                // Scroll to message with offset for header
                const headerHeight = document.querySelector('.sticky').offsetHeight + 20;
                const messageTop = messageElement.offsetTop - headerHeight;
                
                document.getElementById('chat-messages').scrollTop = messageTop;
                
                // Flash highlight effect
                messageElement.classList.add('bg-yellow-100');
                setTimeout(() => {
                    messageElement.classList.remove('bg-yellow-100');
                    messageElement.classList.add('bg-yellow-50');
                    
                    setTimeout(() => {
                        messageElement.classList.remove('bg-yellow-50');
                    }, 2000);
                }, 1000);
            } else {
                // Message not in current view, might need to load more history
                // For now, just scroll to approximately the right time
                const messages = document.querySelectorAll('.message-container');
                if (messages.length > 0) {
                    // Find closest message by timestamp
                    let closestMessage = messages[0];
                    let closestDiff = Infinity;
                    
                    messages.forEach(msg => {
                        const msgId = msg.dataset.messageId;
                        const msgTime = parseInt(msg.dataset.timestamp || 0);
                        const diff = Math.abs(msgTime - timestamp);
                        
                        if (diff < closestDiff) {
                            closestDiff = diff;
                            closestMessage = msg;
                        }
                    });
                    
                    // Scroll to closest message
                    const headerHeight = document.querySelector('.sticky').offsetHeight + 20;
                    const messageTop = closestMessage.offsetTop - headerHeight;
                    document.getElementById('chat-messages').scrollTop = messageTop;
                }
            }
        }
        
        // Audio & Video Call Functionality
        const audioCallBtn = document.getElementById('audio-call-btn');
        const videoCallBtn = document.getElementById('video-call-btn');
        const callInterface = document.getElementById('call-interface');
        const callStatus = document.getElementById('call-status');
        const callDurationDisplay = document.getElementById('call-duration');
        const endCallBtn = document.getElementById('end-call-btn');
        const toggleMicBtn = document.getElementById('toggle-mic-btn');
        const toggleVideoBtn = document.getElementById('toggle-video-btn');
        const toggleSpeakerBtn = document.getElementById('toggle-speaker-btn');
        const toggleChatBtn = document.getElementById('toggle-chat-btn');
        const toggleFullscreenBtn = document.getElementById('toggle-fullscreen-btn');
        const localStreamVideo = document.getElementById('local-stream');
        const remoteStreamVideo = document.getElementById('remote-stream');
        const localStreamContainer = document.getElementById('local-stream-container');
        const remoteStreamContainer = document.getElementById('remote-stream-container');
        const remoteUserName = document.getElementById('remote-user-name');
        const incomingCallModal = document.getElementById('incoming-call-modal');
        const callerName = document.getElementById('caller-name');
        const callerAvatar = document.getElementById('caller-avatar');
        const callTypeDisplay = document.getElementById('call-type-display');
        const acceptCallBtn = document.getElementById('accept-call-btn');
        const declineCallBtn = document.getElementById('decline-call-btn');
        
        // WebRTC configuration
        const iceServers = {
            iceServers: [
                // Free STUN servers - good for local development
                { urls: 'stun:stun.l.google.com:19302' },
                { urls: 'stun:stun1.l.google.com:19302' },
                { urls: 'stun:stun2.l.google.com:19302' },
                // For production, add your TURN servers from the server config
                // Server-side TURN config would be added here in production
            ]
        };
        
        // Initialize an audio call
        if (audioCallBtn) {
            audioCallBtn.addEventListener('click', function() {
                if (IS_GROUP_CHAT) {
                    alert('Audio calls in group chats are not implemented in this demo.');
                    return;
                }
                initiateCall('audio');
            });
        }
        
        // Initialize a video call
        if (videoCallBtn) {
            videoCallBtn.addEventListener('click', function() {
                if (IS_GROUP_CHAT) {
                    alert('Video calls in group chats are not implemented in this demo.');
                    return;
                }
                initiateCall('video');
            });
        }
        
        // Initiate a call (audio or video)
        function initiateCall(type) {
            const conversationId = document.querySelector('[data-active="true"]')?.dataset.conversationId;
            if (!conversationId) return;
            
            callType = type;
            isCallInitiator = true;
            
            // Show call interface with appropriate setup
            setupCallInterface(type);
            
            // Request user media
            const constraints = {
                audio: true,
                video: type === 'video'
            };
            
            // Show loading state
            callStatus.textContent = 'Initializing...';
            
            // Get user media and setup local stream
            navigator.mediaDevices.getUserMedia(constraints)
                .then(stream => {
                    // Store local stream for later use
                    localStream = stream;
                    
                    // Display local video if it's a video call
                    if (type === 'video') {
                        localStreamVideo.srcObject = stream;
                        localStreamContainer.classList.remove('hidden');
                    } else {
                        // For audio calls, still get video permission but don't display
                        localStreamContainer.classList.add('hidden');
                    }
                    
                    // Update UI to calling state
                    callStatus.textContent = 'Calling...';
                    
                    // Send call request to server
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    fetch(`/messaging/${conversationId}/${type}-call`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Store call ID and participants
                            activeCallId = data.call_id;
                            callParticipants = data.participants;
                            
                            // Initialize WebRTC connection
                            initializePeerConnection();
                            
                            // Create and send offer to the other user
                            createOffer();
                            
                            // Start call timer
                            startCallTimer();
                            
                            // Update UI
                            const otherUser = callParticipants[0];
                            if (otherUser) {
                                callStatus.textContent = `Calling ${otherUser.name}...`;
                                if (remoteUserName) remoteUserName.textContent = otherUser.name;
                            }
                        } else {
                            // Handle error
                            alert(data.message || 'Failed to initiate call.');
                            endCall();
                        }
                    })
                    .catch(error => {
                        console.error('Error initiating call:', error);
                        alert('An error occurred while trying to initiate the call.');
                        endCall();
                    });
                })
                .catch(error => {
                    console.error('Error accessing media devices:', error);
                    alert('Could not access camera/microphone. Please check permissions.');
                    callInterface.classList.add('hidden');
                });
        }
        
        // Setup call interface based on call type
        function setupCallInterface(type) {
            // Reset call state
            callDuration = 0;
            callDurationDisplay.textContent = '00:00';
            
            // Configure interface based on call type
            if (type === 'audio') {
                // Audio call setup
                callStatus.textContent = 'Audio Call';
                localStreamContainer.classList.add('hidden');
                remoteStreamContainer.classList.add('hidden');
                toggleVideoBtn.classList.add('hidden');
            } else {
                // Video call setup
                callStatus.textContent = 'Video Call';
                localStreamContainer.classList.remove('hidden');
                remoteStreamContainer.classList.remove('hidden');
                toggleVideoBtn.classList.remove('hidden');
            }
            
            // Show call interface
            callInterface.classList.remove('hidden');
        }
        
        // Handle incoming calls
        if (window.Echo) {
            // Get conversation ID if we're in a conversation
            const conversationId = document.querySelector('[data-active="true"]')?.dataset.conversationId;
            
            if (conversationId) {
                window.Echo.private(`chat.${conversationId}`)
                    .listen('.call.initiated', data => {
                        // Ignore if we initiated the call
                        if (data.initiator.id === CURRENT_USER_ID) return;
                        
                        // If already in a call, automatically decline
                        if (activeCallId) {
                            // Send decline message
                            sendCallSignal(data.call_id, {
                                type: 'call_declined',
                                reason: 'busy'
                            });
                            return;
                        }
                        
                        // Store call info
                        activeCallId = data.call_id;
                        callType = data.call_type;
                        isCallInitiator = false;
                        callParticipants = [data.initiator];
                        
                        // Show incoming call modal
                        showIncomingCallModal(data);
                    })
                    .listen('.call.signal', data => {
                        // Only process signals for active call
                        if (!activeCallId || data.call_id !== activeCallId) return;
                        
                        handleCallSignal(data);
                    });
            }
        }
        
        // Show incoming call modal
        function showIncomingCallModal(data) {
            // Set caller info
            if (callerName) callerName.textContent = data.initiator.name;
            if (callerAvatar) callerAvatar.src = data.initiator.avatar || DEFAULT_AVATAR;
            if (callTypeDisplay) callTypeDisplay.textContent = `Incoming ${data.call_type} call`;
            
            // Show modal
            if (incomingCallModal) incomingCallModal.classList.remove('hidden');
            
            // Play ringtone
            playRingtone();
        }
        
        // Accept incoming call
        if (acceptCallBtn) {
            acceptCallBtn.addEventListener('click', function() {
                // Hide incoming call modal
                incomingCallModal.classList.add('hidden');
                
                // Stop ringtone
                stopRingtone();
                
                // Setup call interface
                setupCallInterface(callType);
                callStatus.textContent = 'Connecting...';
                
                // Get user media
                const constraints = {
                    audio: true,
                    video: callType === 'video'
                };
                
                navigator.mediaDevices.getUserMedia(constraints)
                    .then(stream => {
                        // Store local stream
                        localStream = stream;
                        
                        // Display local video if it's a video call
                        if (callType === 'video') {
                            localStreamVideo.srcObject = stream;
                            localStreamContainer.classList.remove('hidden');
                        } else {
                            localStreamContainer.classList.add('hidden');
                        }
                        
                        // Initialize WebRTC connection
                        initializePeerConnection();
                        
                        // Send call accepted signal
                        sendCallSignal(activeCallId, {
                            type: 'call_accepted'
                        });
                        
                        // Update UI
                        const otherUser = callParticipants[0];
                        if (otherUser) {
                            callStatus.textContent = `Connected with ${otherUser.name}`;
                            if (remoteUserName) remoteUserName.textContent = otherUser.name;
                        }
                    })
                    .catch(error => {
                        console.error('Error accessing media devices:', error);
                        alert('Could not access camera/microphone. Please check permissions.');
                        endCall();
                    });
            });
        }
        
        // Decline incoming call
        if (declineCallBtn) {
            declineCallBtn.addEventListener('click', function() {
                // Hide incoming call modal
                incomingCallModal.classList.add('hidden');
                
                // Stop ringtone
                stopRingtone();
                
                // Send decline signal
                sendCallSignal(activeCallId, {
                    type: 'call_declined',
                    reason: 'rejected'
                });
                
                // Reset call state
                resetCallState();
            });
        }
        
        // End active call
        if (endCallBtn) {
            endCallBtn.addEventListener('click', function() {
                // Send call ended signal
                if (activeCallId) {
                    sendCallSignal(activeCallId, {
                        type: 'call_ended'
                    });
                }
                
                endCall();
            });
        }
        
        // Toggle microphone
        if (toggleMicBtn) {
            toggleMicBtn.addEventListener('click', function() {
                if (!localStream) return;
                
                const audioTracks = localStream.getAudioTracks();
                if (audioTracks.length === 0) return;
                
                const isEnabled = audioTracks[0].enabled;
                audioTracks.forEach(track => {
                    track.enabled = !isEnabled;
                });
                
                // Update button UI
                toggleMicBtn.innerHTML = !isEnabled ? 
                    '<i class="fas fa-microphone"></i>' : 
                    '<i class="fas fa-microphone-slash"></i>';
                
                toggleMicBtn.classList.toggle('bg-red-600', !audioTracks[0].enabled);
                toggleMicBtn.classList.toggle('bg-gray-700', audioTracks[0].enabled);
            });
        }
        
        // Toggle video
        if (toggleVideoBtn) {
            toggleVideoBtn.addEventListener('click', function() {
                if (!localStream || callType !== 'video') return;
                
                const videoTracks = localStream.getVideoTracks();
                if (videoTracks.length === 0) return;
                
                const isEnabled = videoTracks[0].enabled;
                videoTracks.forEach(track => {
                    track.enabled = !isEnabled;
                });
                
                // Update button UI
                toggleVideoBtn.innerHTML = !isEnabled ? 
                    '<i class="fas fa-video"></i>' : 
                    '<i class="fas fa-video-slash"></i>';
                
                toggleVideoBtn.classList.toggle('bg-red-600', !videoTracks[0].enabled);
                toggleVideoBtn.classList.toggle('bg-gray-700', videoTracks[0].enabled);
            });
        }
        
        // Toggle speaker (simplified - mobile devices only support this properly)
        if (toggleSpeakerBtn) {
            toggleSpeakerBtn.addEventListener('click', function() {
                // This is a simplified implementation
                // On mobile devices, this would switch between earpiece and speakerphone
                alert('Speaker toggle is fully supported only on mobile devices.');
                
                // Toggle button appearance anyway for demo
                toggleSpeakerBtn.innerHTML = toggleSpeakerBtn.innerHTML.includes('volume-up') ? 
                    '<i class="fas fa-volume-down"></i>' : 
                    '<i class="fas fa-volume-up"></i>';
            });
        }
        
        // Toggle chat (show/hide chat during call)
        if (toggleChatBtn) {
            toggleChatBtn.addEventListener('click', function() {
                // Toggle between call interface and chat
                callInterface.classList.add('minimized');
                
                // Create a minimized call view
                const minimizedCall = document.createElement('div');
                minimizedCall.className = 'fixed bottom-4 right-4 bg-primary-600 text-white rounded-lg p-3 shadow-lg z-40 flex items-center cursor-pointer';
                minimizedCall.innerHTML = `
                    <div class="mr-2">
                        <i class="fas fa-${callType === 'video' ? 'video' : 'phone'} mr-2"></i>
                        <span>${callDurationDisplay.textContent}</span>
                    </div>
                    <button class="bg-red-500 hover:bg-red-600 rounded-full w-8 h-8 flex items-center justify-center ml-2">
                        <i class="fas fa-phone-slash"></i>
                    </button>
                `;
                
                // Add click handler to restore call view
                minimizedCall.addEventListener('click', function(e) {
                    if (e.target.closest('button')) {
                        // End call if end button clicked
                        endCall();
                        minimizedCall.remove();
                    } else {
                        // Restore call interface
                        callInterface.classList.remove('minimized');
                        minimizedCall.remove();
                    }
                });
                
                document.body.appendChild(minimizedCall);
            });
        }
        
        // Toggle fullscreen
        if (toggleFullscreenBtn) {
            toggleFullscreenBtn.addEventListener('click', function() {
                if (!document.fullscreenElement) {
                    callInterface.requestFullscreen().catch(err => {
                        console.error(`Error attempting to enable fullscreen: ${err.message}`);
                    });
                } else {
                    document.exitFullscreen();
                }
            });
            
            // Update fullscreen button icon
            document.addEventListener('fullscreenchange', function() {
                toggleFullscreenBtn.innerHTML = document.fullscreenElement ? 
                    '<i class="fas fa-compress"></i>' : 
                    '<i class="fas fa-expand"></i>';
            });
        }
        
        // Initialize WebRTC peer connection
        function initializePeerConnection() {
            // Create RTCPeerConnection
            peerConnection = new RTCPeerConnection(iceServers);
            
            // Add local stream tracks to peer connection
            if (localStream) {
                localStream.getTracks().forEach(track => {
                    peerConnection.addTrack(track, localStream);
                });
            }
            
            // Handle ICE candidates
            peerConnection.onicecandidate = event => {
                if (event.candidate) {
                    // Send ICE candidate to the other peer
                    sendCallSignal(activeCallId, {
                        type: 'ice_candidate',
                        candidate: event.candidate
                    });
                }
            };
            
            // Handle connection state changes
            peerConnection.onconnectionstatechange = event => {
                switch(peerConnection.connectionState) {
                    case 'connected':
                        console.log('WebRTC connected!');
                        callStatus.textContent = 'Connected';
                        startCallTimer();
                        break;
                    case 'disconnected':
                        console.log('WebRTC disconnected');
                        callStatus.textContent = 'Disconnected';
                        break;
                    case 'failed':
                        console.log('WebRTC connection failed');
                        callStatus.textContent = 'Connection failed';
                        endCall();
                        break;
                }
            };
            
            // Handle remote stream
            peerConnection.ontrack = event => {
                // Display remote stream
                remoteStream = event.streams[0];
                remoteStreamVideo.srcObject = remoteStream;
                remoteStreamContainer.classList.remove('hidden');
                
                // Update UI
                callStatus.textContent = 'Connected';
            };
        }
        
        // Create and send offer (for call initiator)
        function createOffer() {
            if (!peerConnection) return;
            
            peerConnection.createOffer()
                .then(offer => peerConnection.setLocalDescription(offer))
                .then(() => {
                    // Send offer to the other peer
                    sendCallSignal(activeCallId, {
                        type: 'offer',
                        sdp: peerConnection.localDescription
                    });
                })
                .catch(error => {
                    console.error('Error creating offer:', error);
                    alert('Failed to create call offer.');
                    endCall();
                });
        }
        
        // Create and send answer (for call receiver)
        function createAnswer() {
            if (!peerConnection) return;
            
            peerConnection.createAnswer()
                .then(answer => peerConnection.setLocalDescription(answer))
                .then(() => {
                    // Send answer to the other peer
                    sendCallSignal(activeCallId, {
                        type: 'answer',
                        sdp: peerConnection.localDescription
                    });
                })
                .catch(error => {
                    console.error('Error creating answer:', error);
                    alert('Failed to create call answer.');
                    endCall();
                });
        }
        
        // Send WebRTC signaling data
        function sendCallSignal(callId, data) {
            if (!callId) return;
            
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/api/calls/signal', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    call_id: callId,
                    signal: data
                })
            }).catch(error => {
                console.error('Error sending call signal:', error);
            });
        }
        
        // Handle incoming WebRTC signals
        function handleCallSignal(data) {
            if (!data || !data.signal) return;
            
            const signal = data.signal;
            
            switch (signal.type) {
                case 'offer':
                    // Set remote description and create answer
                    if (peerConnection && signal.sdp) {
                        peerConnection.setRemoteDescription(new RTCSessionDescription(signal.sdp))
                            .then(() => createAnswer())
                            .catch(error => {
                                console.error('Error handling offer:', error);
                                endCall();
                            });
                    }
                    break;
                    
                case 'answer':
                    // Set remote description for the initiator
                    if (peerConnection && signal.sdp) {
                        peerConnection.setRemoteDescription(new RTCSessionDescription(signal.sdp))
                            .catch(error => {
                                console.error('Error handling answer:', error);
                                endCall();
                            });
                    }
                    break;
                    
                case 'ice_candidate':
                    // Add ICE candidate
                    if (peerConnection && signal.candidate) {
                        peerConnection.addIceCandidate(new RTCIceCandidate(signal.candidate))
                            .catch(error => {
                                console.error('Error adding ICE candidate:', error);
                            });
                    }
                    break;
                    
                case 'call_accepted':
                    // Call was accepted by the other party
                    callStatus.textContent = 'Call connected';
                    startCallTimer();
                    break;
                    
                case 'call_declined':
                    // Call was declined
                    const reason = signal.reason || 'declined';
                    alert(`Call was ${reason === 'busy' ? 'declined: user is busy' : 'declined'}`);
                    endCall();
                    break;
                    
                case 'call_ended':
                    // Other party ended the call
                    endCall();
                    break;
            }
        }
        
        // Start call timer
        function startCallTimer() {
            // Reset duration
            callDuration = 0;
            callDurationDisplay.textContent = '00:00';
            
            // Clear existing timer
            if (callTimer) clearInterval(callTimer);
            
            // Start new timer
            callTimer = setInterval(() => {
                callDuration++;
                
                const minutes = Math.floor(callDuration / 60);
                const seconds = callDuration % 60;
                
                callDurationDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }, 1000);
        }
        
        // End call and clean up
        function endCall() {
            // Hide call interface
            if (callInterface) callInterface.classList.add('hidden');
            if (incomingCallModal) incomingCallModal.classList.add('hidden');
            
            // Stop call timer
            if (callTimer) {
                clearInterval(callTimer);
                callTimer = null;
            }
            
            // Close peer connection
            if (peerConnection) {
                peerConnection.close();
                peerConnection = null;
            }
            
            // Stop local media streams
            if (localStream) {
                localStream.getTracks().forEach(track => track.stop());
                localStream = null;
            }
            
            // Stop remote stream
            if (remoteStream) {
                remoteStream.getTracks().forEach(track => track.stop());
                remoteStream = null;
            }
            
            // Reset video elements
            if (localStreamVideo) localStreamVideo.srcObject = null;
            if (remoteStreamVideo) remoteStreamVideo.srcObject = null;
            
            // Reset call state
            resetCallState();
            
            // Stop ringtone if playing
            stopRingtone();
            
            // Remove any minimized call view
            const minimizedCall = document.querySelector('.minimized-call');
            if (minimizedCall) minimizedCall.remove();
        }
        
        // Reset call state variables
        function resetCallState() {
            activeCallId = null;
            isCallInitiator = false;
            callType = null;
            callDuration = 0;
            callParticipants = [];
        }
        
        // Play ringtone for incoming calls with fallback options
        let ringtone;
        function playRingtone() {
            if (!ringtone) {
                // Try loading from multiple potential sources for compatibility
                const potentialSources = [
                    '/media/sounds/ringtone.mp3', // Secure route
                    '/sounds/ringtone.mp3',       // Direct public path
                    '/public/sounds/ringtone.mp3' // Potential production path
                ];
                
                // Check for media support
                if (!window.Audio) {
                    console.warn('Audio playback not supported in this browser');
                    return;
                }
                
                ringtone = new Audio();
                ringtone.loop = true;
                
                // Add multiple sources for browser compatibility
                const sourceElement = document.createElement('source');
                sourceElement.src = potentialSources[0];
                sourceElement.type = 'audio/mpeg';
                ringtone.appendChild(sourceElement);
                
                // Set backup source as direct attribute
                ringtone.src = potentialSources[0];
                
                // Handle loading error by trying the next source
                let sourceIndex = 0;
                ringtone.addEventListener('error', function() {
                    sourceIndex++;
                    if (sourceIndex < potentialSources.length) {
                        console.log(`Trying alternative ringtone source: ${potentialSources[sourceIndex]}`);
                        ringtone.src = potentialSources[sourceIndex];
                        ringtone.load();
                        ringtone.play().catch(e => console.warn('Ringtone playback failed:', e));
                    } else {
                        console.error('All ringtone sources failed to load');
                    }
                });
            }
            
            // Try to play with proper error handling for mobile browsers
            const playPromise = ringtone.play();
            
            if (playPromise !== undefined) {
                playPromise.catch(error => {
                    console.warn('Ringtone playback was prevented:', error);
                    
                    // On mobile, auto-play is often blocked until user interaction
                    // Add a visual indication that would normally be accompanied by sound
                    const callAnimation = document.querySelector('.call-ringing-animation');
                    if (callAnimation) {
                        callAnimation.classList.add('active');
                    }
                    
                    // Vibrate the device if supported (mobile only)
                    if (navigator.vibrate) {
                        // Vibrate pattern: 500ms vibration, 200ms pause, repeat
                        try {
                            navigator.vibrate([500, 200, 500, 200, 500]);
                        } catch (e) {
                            console.warn('Vibration failed:', e);
                        }
                    }
                });
            }
        }
        
        // Stop ringtone with proper cleanup
        function stopRingtone() {
            if (ringtone) {
                try {
                    ringtone.pause();
                    ringtone.currentTime = 0;
                } catch (e) {
                    console.warn('Error stopping ringtone:', e);
                }
            }
            
            // Stop vibration if it was started
            if (navigator.vibrate) {
                try {
                    navigator.vibrate(0); // Stop vibration
                } catch (e) {
                    console.warn('Error stopping vibration:', e);
                }
            }
            
            // Remove visual indicators
            const callAnimation = document.querySelector('.call-ringing-animation');
            if (callAnimation) {
                callAnimation.classList.remove('active');
            }
        }
        
        // Handle page unload during call
        window.addEventListener('beforeunload', function(e) {
            if (activeCallId) {
                // End call properly when leaving the page
                sendCallSignal(activeCallId, {
                    type: 'call_ended'
                });
                
                endCall();
                
                // Show confirmation to user
                e.preventDefault();
                e.returnValue = 'You are in an active call. Are you sure you want to leave?';
                return e.returnValue;
            }
        });

        // Attachment Preview Handling
        document.getElementById('attachment').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const preview = document.getElementById('attachment-preview');
            const previewName = document.getElementById('attachment-preview-name');
            const previewSize = document.getElementById('attachment-preview-size');
            const previewIcon = document.getElementById('attachment-preview-icon');
            const imagePreviewContainer = document.getElementById('image-preview-container');
            const imagePreview = document.getElementById('image-preview');

            // Show preview container
            preview.classList.remove('hidden');

            // Set file name and size
            previewName.textContent = file.name;
            previewSize.textContent = formatFileSize(file.size);

            // Handle image preview
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.classList.remove('hidden');
                    previewIcon.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                // Handle other file types
                imagePreviewContainer.classList.add('hidden');
                previewIcon.classList.remove('hidden');
                
                // Set appropriate icon based on file type
                const icon = getFileIcon(file.type);
                previewIcon.innerHTML = `<i class="fas ${icon} text-gray-500 text-xl"></i>`;
            }
        });

        // Remove attachment
        document.getElementById('remove-attachment').addEventListener('click', function() {
            const input = document.getElementById('attachment');
            const preview = document.getElementById('attachment-preview');
            const imagePreviewContainer = document.getElementById('image-preview-container');
            const previewIcon = document.getElementById('attachment-preview-icon');
            
            input.value = '';
            preview.classList.add('hidden');
            imagePreviewContainer.classList.add('hidden');
            previewIcon.classList.remove('hidden');
        });

        // Helper function to format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Helper function to get appropriate file icon
        function getFileIcon(fileType) {
            if (fileType.startsWith('image/')) return 'fa-image';
            if (fileType.startsWith('video/')) return 'fa-video';
            if (fileType.startsWith('audio/')) return 'fa-music';
            if (fileType.includes('pdf')) return 'fa-file-pdf';
            if (fileType.includes('word') || fileType.includes('document')) return 'fa-file-word';
            if (fileType.includes('excel') || fileType.includes('sheet')) return 'fa-file-excel';
            if (fileType.includes('powerpoint') || fileType.includes('presentation')) return 'fa-file-powerpoint';
            if (fileType.includes('zip') || fileType.includes('compressed')) return 'fa-file-archive';
            return 'fa-file';
        }

        // Update event handlers for file attachment click
        document.addEventListener('DOMContentLoaded', function() {
            // ... existing DOM ready code ...
            
            // Update file attachment click handlers
            document.querySelectorAll('.file-attachment-item').forEach(function(element) {
                element.addEventListener('click', function() {
                    const messageId = this.getAttribute('data-message-id');
                    
                    if (messageId) {
                        // Open file viewer with message ID
                        openFileViewer(messageId, null, null);
                    } else {
                        // Fallback to old way using data attributes
                        const fileUrl = this.getAttribute('data-file-url');
                        const fileName = this.getAttribute('data-file-name');
                        const fileType = this.getAttribute('data-file-type');
                        
                        if (fileUrl && fileType) {
                            openFileViewer(fileUrl, fileName, fileType);
                        }
                    }
                });
            });
            
            // Handle dynamically added file attachments
            document.addEventListener('click', function(e) {
                const attachment = e.target.closest('.file-attachment-item');
                if (attachment) {
                    const messageId = attachment.getAttribute('data-message-id');
                    
                    if (messageId) {
                        // Open file viewer with message ID
                        openFileViewer(messageId, null, null);
                    } else {
                        // Fallback to old way
                        const fileUrl = attachment.getAttribute('data-file-url');
                        const fileName = attachment.getAttribute('data-file-name');
                        const fileType = attachment.getAttribute('data-file-type');
                        
                        if (fileUrl && fileType) {
                            openFileViewer(fileUrl, fileName, fileType);
                            e.preventDefault();
                            e.stopPropagation();
                        }
                    }
                }
            });
            });
    
    // Add event handlers for file attachments
    document.querySelectorAll('.file-attachment-item').forEach(function(element) {
        element.addEventListener('click', function() {
            const messageId = this.getAttribute('data-message-id');
            
            if (messageId) {
                // Open file viewer with message ID
                openFileViewer(messageId, null, null);
            } else {
                // Fallback to old way using data attributes
                const fileUrl = this.getAttribute('data-file-url');
                const fileName = this.getAttribute('data-file-name');
                const fileType = this.getAttribute('data-file-type');
                
                if (fileUrl && fileType) {
                    openFileViewer(fileUrl, fileName, fileType);
                }
            }
        });
    });
});

// Image Modal Functions
    function openImageModal(imageUrl) {
    // Use the new file viewer for images too
    if (document.getElementById('fileViewerModal')) {
        // Extract filename from URL
        const fileName = imageUrl.split('/').pop();
        openFileViewer(imageUrl, fileName, 'image/jpeg');
        return;
    }
    
    // Fallback to old image modal if new file viewer isn't available
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    
    // Set image source
    modalImg.src = imageUrl;
    
    // Show modal with fade-in effect
    modal.classList.add('show');
    
    // Disable body scroll
    document.body.style.overflow = 'hidden';
    
    // Add event listener to close on background click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeImageModal();
        }
    });
    
    // Add keyboard event to close on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    
    // Hide modal with fade-out effect
    modal.classList.remove('show');
    
    // Re-enable body scroll
    document.body.style.overflow = '';
    
    // Clear image source after animation completes
    setTimeout(() => {
        document.getElementById('modalImage').src = '';
    }, 300);
}
    
    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        
        // Hide modal with fade-out effect
        modal.classList.remove('show');
        
        // Re-enable body scroll
        document.body.style.overflow = '';
        
        // Clear image source after animation completes
        setTimeout(() => {
            document.getElementById('modalImage').src = '';
        }, 300);
    }

    // File Viewer Modal Functions
    function openFileViewer(fileUrl, fileName, fileType) {
    const modal = document.getElementById('fileViewerModal');
    if (!modal) {
        console.error('File viewer modal not found');
        return;
    }
    
    // Initialize or get elements
    const fileViewerTitle = document.getElementById('file-viewer-title') || createElementIfMissing('file-viewer-title', 'div', 'File Viewer', modal);
    const fileViewerIcon = document.getElementById('file-viewer-icon') || createElementIfMissing('file-viewer-icon', 'div', '<i class="fas fa-file"></i>', modal);
    const fileViewerContent = document.getElementById('file-viewer-content') || createElementIfMissing('file-viewer-content', 'div', '', modal);
    let fileViewerLoading = document.getElementById('file-viewer-loading');
    const fileViewerDownload = document.getElementById('file-viewer-download') || createDownloadButton(modal);
    
    // Create loading spinner if missing
    if (!fileViewerLoading) {
        fileViewerLoading = document.createElement('div');
        fileViewerLoading.id = 'file-viewer-loading';
        fileViewerLoading.className = 'file-viewer-loading';
        fileViewerLoading.innerHTML = '<div class="file-viewer-spinner"></div>';
        fileViewerContent.appendChild(fileViewerLoading);
    }
    
    // If modal is already shown or in the process of closing, ensure we cancel any ongoing close
    if (modal.classList.contains('show') || modal.getAttribute('data-closing') === 'true') {
        modal.removeAttribute('data-closing');
        
        // If content exists and we're reopening the same file, don't reload
        const currentFile = modal.getAttribute('data-current-file');
        if (currentFile === fileUrl) {
            return; // No need to reload the same file
        }
    }
    
    // Set current file attribute to track which file is being viewed
    modal.setAttribute('data-current-file', fileUrl);
    modal.removeAttribute('data-file-loaded'); // Reset loaded state
    
    // Reset content while preserving loading indicator
    Array.from(fileViewerContent.children).forEach(child => {
        if (child.id !== 'file-viewer-loading') {
            fileViewerContent.removeChild(child);
        }
    });
    
    // Make sure the loading spinner is visible
    fileViewerLoading.style.display = 'flex';
    
    // Show modal
    modal.classList.add('show');
    
    // Disable body scroll
    document.body.style.overflow = 'hidden';
    
    // Check if we're using a message ID instead of a direct URL
    const isMessageId = !fileUrl.startsWith('http') && !fileUrl.startsWith('/');
    
    if (isMessageId) {
        // Fetch file information from the server
        fetch(`/messaging/attachment/${fileUrl}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update file information
                    fileViewerTitle.textContent = data.name || fileName || 'File';
                    fileViewerIcon.innerHTML = `<i class="fas ${getFileIconClass(data.type)}"></i>`;
                    fileViewerDownload.setAttribute('href', data.url);
                    fileViewerDownload.setAttribute('download', data.name);
                    
                    // Load file content based on type
                    loadFileContent(data.url, data.type, data.name, fileViewerContent, fileViewerLoading);
                } else {
                    showUnsupportedContent(fileViewerContent, data.error || 'Error loading file', '#');
                    fileViewerLoading.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching file information:', error);
                showUnsupportedContent(fileViewerContent, 'Error loading file', '#');
                fileViewerLoading.style.display = 'none';
            });
    } else {
        // If direct URL is provided, use the passed parameters
        fileViewerTitle.textContent = fileName || 'File';
        fileViewerIcon.innerHTML = `<i class="fas ${getFileIconClass(fileType)}"></i>`;
        fileViewerDownload.setAttribute('href', fileUrl);
        fileViewerDownload.setAttribute('download', fileName || 'download');
        
        // Load file content
        loadFileContent(fileUrl, fileType, fileName, fileViewerContent, fileViewerLoading);
    }
}

function loadFileContent(fileUrl, fileType, fileName, contentContainer, loadingElement) {
    // Handle different file types
    if (fileType.startsWith('image/')) {
        loadImageFile(fileUrl, contentContainer, loadingElement);
    } else if (fileType === 'application/pdf') {
        loadPdfFile(fileUrl, contentContainer, loadingElement);
    } else if (
        fileType.includes('word') || 
        fileType.includes('excel') || 
        fileType.includes('powerpoint') ||
        fileType.includes('text/') ||
        fileType.includes('document') ||
        fileType.includes('spreadsheet') ||
        fileType.includes('presentation')
    ) {
        loadOfficeFile(fileUrl, fileType, fileName, contentContainer, loadingElement);
    } else if (
        fileType.includes('text/') || 
        fileType === 'application/json' || 
        fileType === 'text/csv' ||
        fileType === 'application/sql'
    ) {
        loadTextFile(fileUrl, fileType, fileName, contentContainer, loadingElement);
    } else {
        loadUnsupportedFile(fileUrl, fileType, fileName, contentContainer, loadingElement);
    }
}

function getFileIconClass(fileType) {
    if (!fileType) return 'fa-file';
    
    if (fileType.startsWith('image/')) return 'fa-file-image text-primary-500';
    if (fileType === 'application/pdf') return 'fa-file-pdf text-red-500';
    if (fileType.includes('word') || fileType.includes('document')) return 'fa-file-word text-blue-500';
    if (fileType.includes('excel') || fileType.includes('spreadsheet') || fileType.includes('csv')) return 'fa-file-excel text-green-500';
    if (fileType.includes('powerpoint') || fileType.includes('presentation')) return 'fa-file-powerpoint text-orange-500';
    if (fileType.includes('zip') || fileType.includes('compressed') || fileType.includes('tar') || fileType.includes('7z') || fileType.includes('rar')) return 'fa-file-archive text-yellow-600';
    if (fileType.includes('text/') || fileType === 'application/json') return 'fa-file-alt text-gray-700';
    if (fileType.includes('sql')) return 'fa-database text-purple-500';
    if (fileType.startsWith('audio/')) return 'fa-file-audio text-blue-400';
    if (fileType.startsWith('video/')) return 'fa-file-video text-red-400';
    if (fileType.includes('html') || fileType.includes('xml')) return 'fa-file-code text-gray-800';
    return 'fa-file text-gray-500';
}

function loadImageFile(fileUrl, contentContainer, loadingElement) {
    const img = new Image();
    img.className = 'file-viewer-image';
    img.src = fileUrl;
    
    img.onload = function() {
        loadingElement.style.display = 'none';
        contentContainer.appendChild(img);
    };
    
    img.onerror = function() {
        loadingElement.style.display = 'none';
        showUnsupportedContent(contentContainer, 'Failed to load image', fileUrl);
    };
}

function loadPdfFile(fileUrl, contentContainer, loadingElement) {
    const iframe = document.createElement('iframe');
    iframe.className = 'file-viewer-pdf';
    iframe.src = fileUrl + '?view=1'; // Add view parameter to indicate inline viewing
    
    iframe.onload = function() {
        loadingElement.style.display = 'none';
    };
    
    iframe.onerror = function() {
        loadingElement.style.display = 'none';
        showUnsupportedContent(contentContainer, 'Failed to load PDF', fileUrl);
    };
    
    contentContainer.appendChild(iframe);
}

function loadOfficeFile(fileUrl, fileType, fileName, contentContainer, loadingElement) {
    // Try to use Google Docs Viewer for Office documents
    const encodedUrl = encodeURIComponent(window.location.origin + fileUrl);
    const googleViewerUrl = `https://docs.google.com/viewer?url=${encodedUrl}&embedded=true`;
    
    const iframe = document.createElement('iframe');
    iframe.className = 'file-viewer-pdf'; // Reuse the pdf viewer styling
    iframe.src = googleViewerUrl;
    
    // Set up a timeout to check if Google Docs viewer works
    const googleViewerTimeout = setTimeout(() => {
        // If we reach this timeout, Google Docs viewer likely failed
        // Fallback to Microsoft Office Online viewer or just download option
        tryMicrosoftViewer(fileUrl, fileType, fileName, contentContainer);
    }, 5000);
    
    iframe.onload = function() {
        clearTimeout(googleViewerTimeout);
        loadingElement.style.display = 'none';
    };
    
    contentContainer.appendChild(iframe);
}

function tryMicrosoftViewer(fileUrl, fileType, fileName, contentContainer) {
    // Try Microsoft Office Online viewer as a fallback
    const encodedUrl = encodeURIComponent(window.location.origin + fileUrl);
    let msViewerUrl = null;
    
    if (fileType.includes('word') || fileType.includes('document')) {
        msViewerUrl = `https://view.officeapps.live.com/op/view.aspx?src=${encodedUrl}`;
    } else if (fileType.includes('excel') || fileType.includes('spreadsheet')) {
        msViewerUrl = `https://view.officeapps.live.com/op/view.aspx?src=${encodedUrl}`;
    } else if (fileType.includes('powerpoint') || fileType.includes('presentation')) {
        msViewerUrl = `https://view.officeapps.live.com/op/view.aspx?src=${encodedUrl}`;
    }
    
    if (msViewerUrl) {
        const iframe = contentContainer.querySelector('iframe');
        if (iframe) {
            iframe.src = msViewerUrl;
        }
    }
}

function loadTextFile(fileUrl, fileType, fileName, contentContainer, loadingElement) {
    // Fetch the text file content
    fetch(fileUrl)
        .then(response => response.text())
        .then(text => {
            loadingElement.style.display = 'none';
            
            const preElement = document.createElement('pre');
            preElement.className = 'file-viewer-text';
            preElement.textContent = text;
            
            contentContainer.appendChild(preElement);
            
            // Add syntax highlighting for code
            if (typeof hljs !== 'undefined') {
                if (fileType === 'application/json') {
                    preElement.className += ' language-json';
                } else if (fileType.includes('sql')) {
                    preElement.className += ' language-sql';
                } else if (fileType === 'text/csv') {
                    preElement.className += ' language-csv';
                } else if (fileType.includes('html')) {
                    preElement.className += ' language-html';
                } else if (fileType.includes('javascript')) {
                    preElement.className += ' language-javascript';
                } else if (fileType.includes('css')) {
                    preElement.className += ' language-css';
                }
                
                hljs.highlightElement(preElement);
            }
        })
        .catch(error => {
            loadingElement.style.display = 'none';
            showUnsupportedContent(contentContainer, 'Failed to load text file', fileUrl);
        });
}

function loadUnsupportedFile(fileUrl, fileType, fileName, contentContainer, loadingElement) {
    loadingElement.style.display = 'none';
    showUnsupportedContent(contentContainer, 'This file type cannot be previewed', fileUrl);
}

function showUnsupportedContent(contentContainer, message, fileUrl) {
    contentContainer.innerHTML = `
        <div class="file-viewer-unsupported">
            <div class="file-viewer-unsupported-icon">
                <i class="fas fa-file-download"></i>
            </div>
            <h3>${message}</h3>
            <p>Please download the file to view it.</p>
            <a href="${fileUrl}" download class="file-viewer-download-prompt">
                <i class="fas fa-download mr-2"></i> Download File
            </a>
        </div>
    `;
}

function closeFileViewer() {
    const modal = document.getElementById('fileViewerModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
    
    // Instead of clearing content immediately, set a flag to indicate we're closing
    modal.setAttribute('data-closing', 'true');
    
    // Clear content after animation completes but preserve loading element
    setTimeout(() => {
        if (modal.classList.contains('show')) {
            // If modal was reopened during close animation, don't clear content
            return;
        }
        
        const fileViewerContent = document.getElementById('file-viewer-content');
        const fileViewerLoading = document.getElementById('file-viewer-loading');
        
        // Save loading spinner
        const loadingElement = fileViewerLoading.cloneNode(true);
        
        // Clear content
        fileViewerContent.innerHTML = '';
        
        // Re-add loading spinner
        fileViewerContent.appendChild(loadingElement);
        
        // Reset modal state
        modal.removeAttribute('data-closing');
        
        // Reset download link
        const fileViewerDownload = document.getElementById('file-viewer-download');
        if (fileViewerDownload) {
            fileViewerDownload.setAttribute('href', '#');
            fileViewerDownload.removeAttribute('download');
        }
    }, 300);
}

// Handle file content display in the modal with proper error handling
function handleFileResponse(fileUrl, fileName, fileType, response) {
    const fileViewerContent = document.getElementById('file-viewer-content');
    const fileViewerLoading = document.getElementById('file-viewer-loading');
    const fileViewerIcon = document.getElementById('file-viewer-icon');
    const fileViewerDownload = document.getElementById('file-viewer-download');
    const fileViewerTitle = document.getElementById('file-viewer-title');
    
    // Error checking
    if (!fileViewerContent || !fileViewerLoading || !fileViewerIcon || !fileViewerDownload) {
        console.error('Missing file viewer elements');
        return;
    }
    
    try {
        // Set file title and download link
        if (fileViewerTitle) {
            fileViewerTitle.textContent = fileName || 'File Viewer';
        }
        
        fileViewerDownload.href = fileUrl;
        fileViewerDownload.setAttribute('download', fileName || 'download');
        
        // Set icon based on file type/extension
        let iconClass = 'fas fa-file';
        let fileExtension = '';
        
        if (fileName) {
            fileExtension = fileName.split('.').pop().toLowerCase();
        } else if (fileType) {
            // Map MIME types to extensions
            const mimeExtMap = {
                'application/pdf': 'pdf',
                'application/msword': 'doc',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'docx',
                'application/vnd.ms-excel': 'xls',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'xlsx',
                'application/vnd.ms-powerpoint': 'ppt',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation': 'pptx',
                'text/plain': 'txt',
                'text/html': 'html',
                'text/csv': 'csv',
                'image/jpeg': 'jpg',
                'image/png': 'png',
                'image/gif': 'gif'
            };
            fileExtension = mimeExtMap[fileType] || '';
        }
        
        // Set icon class based on file type
        switch (fileExtension) {
            case 'pdf':
                iconClass = 'fas fa-file-pdf text-red-500';
                break;
            case 'doc':
            case 'docx':
                iconClass = 'fas fa-file-word text-blue-500';
                break;
            case 'xls':
            case 'xlsx':
                iconClass = 'fas fa-file-excel text-green-500';
                break;
            case 'ppt':
            case 'pptx':
                iconClass = 'fas fa-file-powerpoint text-orange-500';
                break;
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'bmp':
            case 'svg':
                iconClass = 'fas fa-file-image text-purple-500';
                break;
            case 'mp4':
            case 'avi':
            case 'mov':
            case 'wmv':
                iconClass = 'fas fa-file-video text-blue-500';
                break;
            case 'mp3':
            case 'wav':
            case 'ogg':
                iconClass = 'fas fa-file-audio text-blue-500';
                break;
            case 'zip':
            case 'rar':
            case '7z':
            case 'tar':
            case 'gz':
                iconClass = 'fas fa-file-archive text-amber-500';
                break;
            case 'html':
            case 'css':
            case 'js':
                iconClass = 'fas fa-file-code text-indigo-500';
                break;
            case 'txt':
            case 'md':
                iconClass = 'fas fa-file-alt text-gray-500';
                break;
            case 'sql':
                iconClass = 'fas fa-database text-blue-600';
                break;
            case 'json':
                iconClass = 'fas fa-brackets-curly text-yellow-600';
                break;
            default:
                iconClass = 'fas fa-file text-gray-500';
        }
        
        // Update the icon
        fileViewerIcon.innerHTML = `<i class="${iconClass}"></i>`;
        
        // Determine file type categories
        const isImage = /^image\//.test(fileType) || ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'].includes(fileExtension);
        const isPdf = fileType === 'application/pdf' || fileExtension === 'pdf';
        const isOfficeDoc = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(fileExtension) || 
                        /ms-word|ms-excel|ms-powerpoint/.test(fileType);
        const isText = /^text\//.test(fileType) || ['txt', 'md', 'css', 'html', 'htm', 'xml', 'json', 'js', 'ts', 'sql', 'log', 'csv'].includes(fileExtension);
        
        // Remove all content except loading indicator
        Array.from(fileViewerContent.children).forEach(child => {
            if (child.id !== 'file-viewer-loading') {
                fileViewerContent.removeChild(child);
            }
        });
        
        // Display content based on file type
        if (isImage) {
            // Image viewer
            const img = document.createElement('img');
            img.className = 'file-viewer-image';
            img.src = fileUrl;
            img.alt = fileName || 'Image';
            
            // Show loading until image loads
            img.onload = () => {
                fileViewerLoading.style.display = 'none';
            };
            img.onerror = () => {
                fileViewerLoading.style.display = 'none';
                const errorDiv = document.createElement('div');
                errorDiv.className = 'file-viewer-error';
                errorDiv.textContent = 'Error loading image';
                fileViewerContent.appendChild(errorDiv);
            };
            
            fileViewerContent.appendChild(img);
        } 
        else if (isPdf) {
            // PDF viewer
            const embed = document.createElement('embed');
            embed.src = fileUrl;
            embed.type = 'application/pdf';
            embed.className = 'file-viewer-pdf';
            
            // Hide loading spinner after embed loads or timeout
            setTimeout(() => {
                fileViewerLoading.style.display = 'none';
            }, 1500);
            
            fileViewerContent.appendChild(embed);
        }
        else if (isOfficeDoc) {
            // Office document viewer using Google Docs or Microsoft Office Online
            const iframe = document.createElement('iframe');
            iframe.className = 'file-viewer-office';
            
            // Use Google Docs Viewer with fallback to Microsoft Office Online
            const encodedUrl = encodeURIComponent(fileUrl);
            iframe.src = `https://docs.google.com/viewer?url=${encodedUrl}&embedded=true`;
            
            // Handle load and errors
            iframe.onload = () => {
                fileViewerLoading.style.display = 'none';
            };
            iframe.onerror = () => {
                // Try Microsoft Office Online as fallback
                iframe.src = `https://view.officeapps.live.com/op/embed.aspx?src=${encodedUrl}`;
            };
            
            // Fallback - hide loading after timeout
            setTimeout(() => {
                fileViewerLoading.style.display = 'none';
            }, 5000);
            
            fileViewerContent.appendChild(iframe);
        }
        else if (isText) {
            // Text/code viewer with syntax highlighting
            const pre = document.createElement('pre');
            pre.className = 'file-viewer-text';
            
            // Apply syntax highlighting class based on file extension
            const highlightMap = {
                'js': 'language-javascript',
                'ts': 'language-typescript',
                'html': 'language-html',
                'css': 'language-css',
                'json': 'language-json',
                'sql': 'language-sql',
                'php': 'language-php',
                'xml': 'language-xml',
                'md': 'language-markdown',
                'txt': 'language-plain'
            };
            
            const highlightClass = highlightMap[fileExtension] || '';
            if (highlightClass) {
                pre.classList.add(highlightClass);
            }
            
            // Set content and apply highlighting if available
            let content = response || 'File content could not be loaded';
            pre.textContent = content;
            
            if (window.Prism && highlightClass) {
                try {
                    pre.innerHTML = Prism.highlight(
                        content, 
                        Prism.languages[highlightClass.replace('language-', '')], 
                        highlightClass.replace('language-', '')
                    );
                } catch (e) {
                    console.warn('Syntax highlighting failed:', e);
                }
            }
            
            fileViewerLoading.style.display = 'none';
            fileViewerContent.appendChild(pre);
        }
        else {
            // Generic file display for unsupported types
            const downloadDiv = document.createElement('div');
            downloadDiv.className = 'file-viewer-download-container';
            
            // Large file icon
            const iconDiv = document.createElement('div');
            iconDiv.className = 'file-viewer-big-icon';
            iconDiv.innerHTML = `<i class="${iconClass}"></i>`;
            
            // File info display
            const fileInfo = document.createElement('div');
            fileInfo.className = 'file-viewer-file-info';
            fileInfo.innerHTML = `
                <div class="file-viewer-filename">${fileName || 'Unknown file'}</div>
                <div class="file-viewer-filetype">${fileType || 'Unknown type'}</div>
            `;
            
            // Download button
            const downloadButton = document.createElement('a');
            downloadButton.href = fileUrl;
            downloadButton.download = fileName || 'download';
            downloadButton.className = 'file-viewer-download-button';
            downloadButton.innerHTML = 'Download File <i class="fas fa-download ml-2"></i>';
            
            // Assemble the elements
            downloadDiv.appendChild(iconDiv);
            downloadDiv.appendChild(fileInfo);
            downloadDiv.appendChild(downloadButton);
            
            fileViewerLoading.style.display = 'none';
            fileViewerContent.appendChild(downloadDiv);
        }
        
        // Mark as successfully loaded
        const modal = document.getElementById('fileViewerModal');
        if (modal) {
            modal.setAttribute('data-file-loaded', 'true');
        }
    } catch (err) {
        console.error('Error displaying file:', err);
        
        // Show error message with download option
        if (fileViewerLoading) {
            fileViewerLoading.style.display = 'none';
        }
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'file-viewer-error';
        errorDiv.innerHTML = `
            <div class="text-red-500 mb-3">
                <i class="fas fa-exclamation-triangle text-3xl"></i>
            </div>
            <h3 class="text-lg font-medium mb-2">Error Loading File</h3>
            <p class="text-gray-700 mb-4">The file could not be displayed properly.</p>
            <a href="${fileUrl}" download="${fileName || 'download'}" 
                class="bg-primary-500 text-white px-4 py-2 rounded-md hover:bg-primary-600 inline-flex items-center">
                <i class="fas fa-download mr-2"></i> Download Instead
            </a>
        `;
        
        fileViewerContent.appendChild(errorDiv);
    }
}

// Helper function to create elements if missing
function createElementIfMissing(id, tagName, innerHTML, parent) {
    const element = document.createElement(tagName);
    element.id = id;
    element.innerHTML = innerHTML;
    if (parent) {
        parent.appendChild(element);
    }
    return element;
}

// Helper function to create download button
function createDownloadButton(modal) {
    const button = document.createElement('a');
    button.id = 'file-viewer-download';
    button.className = 'file-viewer-action-btn';
    button.innerHTML = '<i class="fas fa-download"></i>';
    button.href = '#';
    const actions = document.querySelector('.file-viewer-actions');
    if (actions) {
        actions.appendChild(button);
    } else if (modal) {
        modal.appendChild(button);
    }
    return button;
}

// Add event listeners for file viewer once DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize modal elements and ensure they exist
    initializeFileViewer();
    
    // Setup file viewer close button
    const closeBtn = document.getElementById('file-viewer-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeFileViewer);
    }
    
    // Setup print button
    const printBtn = document.getElementById('file-viewer-print');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            const iframe = document.querySelector('.file-viewer-content iframe');
            if (iframe) {
                try {
                    iframe.contentWindow.print();
                } catch (err) {
                    console.warn('Could not access iframe content for printing:', err);
                    window.print(); // Fallback to regular print
                }
            } else {
                window.print();
            }
        });
    }
    
    // Close on clicking background
    const fileViewerModal = document.getElementById('fileViewerModal');
    if (fileViewerModal) {
        fileViewerModal.addEventListener('click', function(e) {
            if (e.target === fileViewerModal) {
                closeFileViewer();
            }
        });
    }
    
    // Close on ESC key - with event namespacing to avoid duplicate handlers
    const escHandler = function(e) {
        if (e.key === 'Escape' && document.getElementById('fileViewerModal').classList.contains('show')) {
            closeFileViewer();
            e.preventDefault(); // Prevent other handlers
        }
    };
    
    // Remove any existing handlers to avoid duplicates
    document.removeEventListener('keydown', escHandler);
    document.addEventListener('keydown', escHandler);
});

// Initialize and verify all modal elements exist
function initializeFileViewer() {
    const modal = document.getElementById('fileViewerModal');
    if (!modal) return;
    
    const requiredElements = [
        { id: 'file-viewer-title', defaultContent: 'File Viewer' },
        { id: 'file-viewer-icon', defaultContent: '<i class="fas fa-file"></i>' },
        { id: 'file-viewer-content', defaultContent: '' },
        { id: 'file-viewer-download', verify: elem => elem.tagName === 'A' },
        { id: 'file-viewer-close', verify: elem => elem.tagName === 'BUTTON' },
        { id: 'file-viewer-print', verify: elem => elem.tagName === 'BUTTON' }
    ];
    
    // Verify and repair required elements
    requiredElements.forEach(item => {
        let element = document.getElementById(item.id);
        
        if (!element) {
            console.warn(`File viewer element ${item.id} is missing. Creating it.`);
            element = document.createElement(item.id.includes('button') ? 'button' : 'div');
            element.id = item.id;
            
            if (item.defaultContent) {
                element.innerHTML = item.defaultContent;
            }
            
            // For specific elements like buttons or links
            if (item.id === 'file-viewer-download') {
                element = document.createElement('a');
                element.id = item.id;
                element.href = '#';
                element.className = 'file-viewer-action-btn';
                element.innerHTML = '<i class="fas fa-download"></i>';
            }
            
            modal.appendChild(element);
        } else if (item.verify && !item.verify(element)) {
            console.warn(`File viewer element ${item.id} is not the correct type.`);
        }
    });
    
    // Ensure loading spinner exists
    let loadingElem = document.getElementById('file-viewer-loading');
    if (!loadingElem) {
        loadingElem = document.createElement('div');
        loadingElem.id = 'file-viewer-loading';
        loadingElem.className = 'file-viewer-loading';
        loadingElem.innerHTML = '<div class="file-viewer-spinner"></div>';
        
        const contentElem = document.getElementById('file-viewer-content');
        if (contentElem) {
            contentElem.appendChild(loadingElem);
        } else {
            modal.appendChild(loadingElem);
        }
    }
}

// Image Modal Functions
function openImageModal(imageUrl) {
    // Use the new file viewer for images too
    if (document.getElementById('fileViewerModal')) {
        // Extract filename from URL
        const fileName = imageUrl.split('/').pop();
        openFileViewer(imageUrl, fileName, 'image/jpeg');
        return;
    }
    
    // Fallback to old image modal if new file viewer isn't available
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    
    // Set image source
    modalImg.src = imageUrl;
    
    // Show modal with fade-in effect
    modal.classList.add('show');
    
    // Disable body scroll
    document.body.style.overflow = 'hidden';
    
    // Add event listener to close on background click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeImageModal();
        }
    });
    
    // Add keyboard event to close on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
}
</script>
@endpush