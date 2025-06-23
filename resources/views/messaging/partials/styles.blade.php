@push('styles')
<style>
    /* Call interface styles */
    #call-interface {
        transition: all 0.3s ease;
    }
    
    /* Message link styles */
    .message-text a {
        color: #3390ec;
        text-decoration: none;
        transition: all 0.2s ease;
        word-break: break-word;
    }
    
    .message-text a:hover {
        text-decoration: underline;
        color: #2d7fd1;
    }
    
    .message-text a:active {
        color: #186ab8;
    }

    /* Email link styles */
    .email-link {
        display: inline-flex;
        align-items: center;
        background-color: rgba(79, 70, 229, 0.1);
        border-radius: 4px;
        padding: 2px 6px;
        color: #4f46e5 !important;
        font-weight: 500;
        margin: 0 1px;
    }

    .email-link:hover {
        background-color: rgba(79, 70, 229, 0.15);
        text-decoration: none !important;
    }

    .email-link .email-icon {
        display: inline-flex;
        font-size: 0.9em;
        margin-right: 4px;
        opacity: 0.75;
    }
    
    /* Standalone email styling - matches the image exactly */
    .standalone-email-link {
        display: inline-block;
        width: 100%;
        background-color: #e7f2ff !important;
        color: #1877f2 !important;
        border-radius: 18px 4px 18px 18px;
        padding: 8px 12px;
        font-size: 0.95em;
        text-decoration: none !important;
        word-break: break-all;
        position: relative;
    }
    
    .standalone-email-link:hover {
        background-color: #deeeff !important;
    }
    
    .standalone-email-link:active {
        background-color: #d5e9ff !important;
    }
    
    /* Email links that are inline with other text */
    .inline-email-link {
        color: #1877f2 !important;
        text-decoration: underline !important;
        font-weight: 500;
    }
    
    /* Rest of the existing styles */
    #call-interface.minimized {
        opacity: 0;
        pointer-events: none;
    }
    
    /* Video containers */
    #local-stream-container {
        width: 160px;
        height: 160px;
        z-index: 2;
    }
    
    #remote-stream-container {
        width: 320px;
        height: 320px;
    }
    
    @media (min-width: 640px) {
        #local-stream-container {
            width: 240px;
            height: 240px;
        }
        
        #remote-stream-container {
            width: 480px;
            height: 480px;
        }
    }
    
    /* Picture-in-picture layout for active call */
    .call-active #local-stream-container {
        position: absolute;
        bottom: 16px;
        right: 16px;
        width: 120px;
        height: 120px;
        border: 2px solid white;
        border-radius: 8px;
        z-index: 2;
    }
    
    .call-active #remote-stream-container {
        width: 100%;
        height: 100%;
        max-width: 800px;
        max-height: 600px;
    }
    
    /* Call ringing animation */
    .call-ringing-animation {
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0 auto;
    }
    
    .call-ringing-circle {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 3px solid #3390ec;
        border-radius: 50%;
        opacity: 0;
        animation: ripple 2s infinite ease-out;
    }
    
    .call-ringing-circle:nth-child(2) {
        animation-delay: 0.5s;
    }
    
    .call-ringing-circle:nth-child(3) {
        animation-delay: 1s;
    }
    
    @keyframes ripple {
        0% {
            transform: scale(0.5);
            opacity: 0;
        }
        40% {
            opacity: 0.6;
        }
        100% {
            transform: scale(1.2);
            opacity: 0;
        }
    }
    
    /* Search result highlight animation */
    .message-container.highlight {
        animation: highlight-pulse 2s;
    }
    
    @keyframes highlight-pulse {
        0% { background-color: rgba(250, 204, 21, 0.4); }
        50% { background-color: rgba(250, 204, 21, 0.2); }
        100% { background-color: transparent; }
    }

    /* Enhanced message styles */
    .message-bubble-mine {
        background-color: #e7f2ff;
        border-radius: 18px 4px 18px 18px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .message-bubble-other {
        background-color: #ffffff;
        border-radius: 4px 18px 18px 18px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    /* Consistent image sizes in messages - matching the example image */
    .message-image-container {
        max-width: 240px;
        max-height: 240px;
        overflow: hidden;
        margin: 0 auto;
        border-radius: 10px;
        cursor: pointer;
    }

    .message-image {
        width: 100%;
        height: auto;
        max-height: 240px;
        object-fit: contain;
        display: block;
        transition: transform 0.2s ease;
    }

    .message-image:hover {
        transform: scale(1.01);
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
        .message-image-container {
            max-width: 200px;
            max-height: 200px;
        }
        
        .message-image {
            max-height: 200px;
        }
    }
    
    /* Image modal for fullscreen viewing */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        background-color: rgba(0, 0, 0, 0.9);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .image-modal.show {
        display: flex;
        opacity: 1;
        align-items: center;
        justify-content: center;
    }
    
    .modal-content {
        display: block;
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
    }
    
    .close-modal {
        position: absolute;
        top: 20px;
        right: 30px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        z-index: 10000;
    }
    
    .close-modal:hover,
    .close-modal:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

    /* Message transitions */
    .message-container {
        transition: background-color 0.3s ease;
    }

    /* Better spacing for group conversations */
    .message-container + .message-container {
        margin-top: 8px;
    }

    /* Improve message group separation */
    .day-separator {
        margin: 24px 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Message text styling for better readability */
    .message-text {
        line-height: 1.5;
        word-break: break-word;
        white-space: pre-wrap;
    }

    /* Improve attachment styling */
    .message-bubble-mine .message-text,
    .message-bubble-other .message-text {
        margin-top: 4px;
    }

    /* Add subtle hover effect to messages */
    .message-container:hover .message-bubble-mine,
    .message-container:hover .message-bubble-other {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Improve typography to match professional appearance */
    .message-bubble-mine, 
    .message-bubble-other {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        letter-spacing: 0;
        font-size: 14px;
        line-height: 1.5;
        font-weight: 400;
    }

    .message-bubble-mine {
        background-color: #e1f5fe;
        border-radius: 18px 18px 4px 18px;
    }

    .message-bubble-other {
        background-color: #f5f5f5;
        border-radius: 18px 18px 18px 4px;
    }

    /* Match timestamp styling from image */
    .message-container .text-gray-400 {
        font-size: 0.7rem;
        color: #999 !important;
        margin-top: 4px;
        opacity: 1;
        font-weight: 400;
    }

    /* Message text style adjustments */
    .message-text {
        font-size: 14px;
        line-height: 1.5;
        white-space: pre-wrap;
        word-break: break-word;
        color: #333;
    }

    /* Chat input enhancements */
    #message-input {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        letter-spacing: 0.01em;
        resize: none;
        overflow: hidden;
    }

    /* Subtle highlight for active conversation */
    .contact-item[data-active="true"] {
        border-left: 3px solid #3390ec;
    }

    /* Emoji hover menu styling */
    .emoji-hover-menu {
        position: absolute;
        bottom: 60px;
        left: 0;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        z-index: 100;
        width: 320px;
        padding: 12px;
        max-height: 200px;
        overflow-y: auto;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 8px;
        opacity: 0;
        transform: translateY(10px);
        pointer-events: none;
        transition: opacity 0.2s ease, transform 0.2s ease;
        border: 1px solid rgba(0, 0, 0, 0.08);
    }
    
    #emoji-hover-menu.show {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }
    
    .emoji-item {
        width: 32px;
        height: 32px;
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.1s ease;
        user-select: none;
    }
    
    .emoji-item:hover {
        transform: scale(1.2);
        background-color: #f5f5f5;
        border-radius: 8px;
    }
    
    /* Emoji sidebar styling */
    #emoji-sidebar {
        position: fixed;
        right: 0;
        bottom: 0;
        width: 320px;
        max-width: 100vw;
        height: 400px;
        background-color: white;
        z-index: 40;
        display: none;
        flex-direction: column;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
        box-shadow: 0 -2px 20px rgba(0,0,0,0.15);
        overflow: hidden;
        transform: translateY(100%);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    #emoji-sidebar.open {
        display: flex;
        transform: translateY(0);
    }
    
    .emoji-sidebar-header {
        padding: 16px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .emoji-sidebar-tabs {
        display: flex;
        padding: 8px 16px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .emoji-tab {
        padding: 6px 12px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 14px;
        margin-right: 8px;
        transition: background-color 0.2s;
    }
    
    .emoji-tab.active {
        background-color: #edf4fc;
        color: #3390ec;
        font-weight: 500;
    }
    
    .emoji-tab:not(.active):hover {
        background-color: #f5f5f5;
    }
    
    .emoji-search {
        padding: 8px 16px;
    }
    
    .emoji-search input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        font-size: 14px;
        outline: none;
        transition: all 0.2s;
    }
    
    .emoji-search input:focus {
        border-color: #3390ec;
        box-shadow: 0 0 0 2px rgba(51, 144, 236, 0.2);
    }
    
    .emoji-categories {
        display: flex;
        padding: 4px 16px;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .emoji-categories::-webkit-scrollbar {
        display: none;
    }
    
    .emoji-category {
        padding: 6px 10px;
        font-size: 13px;
        white-space: nowrap;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: all 0.15s;
    }
    
    .emoji-category.active {
        color: #3390ec;
        border-bottom-color: #3390ec;
        font-weight: 500;
    }
    
    .emoji-content {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
    }
    
    .emoji-group {
        margin-bottom: 16px;
    }
    
    .emoji-group-title {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 8px;
    }
    
    .emoji-grid {
        display: grid;
        grid-template-columns: repeat(8, 1fr);
        gap: 4px;
    }
    
    .emoji-item {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 18px;
        transition: all 0.1s;
        user-select: none;
        border-radius: 4px;
    }
    
    .emoji-item:hover {
        background-color: #f0f2f5;
        transform: scale(1.1);
    }
    
    #emoji-sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.3);
        z-index: 39;
        display: none;
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
    }
    
    /* GIF & Sticker styles */
    .sticker-grid, .gif-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }
    
    .sticker-item, .gif-item {
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.1s;
    }
    
    .sticker-item:hover, .gif-item:hover {
        transform: scale(1.05);
    }
    
    .sticker-item img, .gif-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Image modal */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 100;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        overflow: hidden;
    }
    
    .modal-content {
        margin: auto;
        display: block;
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }
    
    .close-modal {
        position: absolute;
        top: 20px;
        right: 30px;
        color: white;
        font-size: 40px;
        font-weight: bold;
        transition: 0.2s;
        cursor: pointer;
        z-index: 101;
    }
    
    /* Message image styling */
    .message-image {
        max-width: 260px;
        max-height: 300px;
        border-radius: 8px;
        cursor: pointer;
        transition: filter 0.2s;
    }
    
    .message-image:hover {
        filter: brightness(0.9);
    }
    
    .message-image-container {
        position: relative;
        display: inline-block;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    @media (max-width: 640px) {
        #emoji-sidebar {
            width: 100%;
            height: 60vh;
        }
        
        .emoji-grid {
            grid-template-columns: repeat(7, 1fr);
        }
        
        .message-image {
            max-width: 80%;
        }
    }

    .single-emoji {
        font-size: 2.75rem;
        line-height: 1.1;
        text-align: center;
        padding: 0.5rem 0;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 3.5rem;
        min-width: 3.5rem;
        background: none;
        box-shadow: none;
    }
    @media (max-width: 640px) {
        .single-emoji {
            font-size: 2.1rem;
            min-height: 2.5rem;
            min-width: 2.5rem;
        }
    }

    /* Google Drive-like File Viewer */
    .file-viewer-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        background-color: rgba(0, 0, 0, 0.85);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .file-viewer-modal.show {
        display: flex;
        flex-direction: column;
        opacity: 1;
    }

    .file-viewer-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #fff;
        height: 64px;
        padding: 0 16px;
        border-bottom: 1px solid #e0e0e0;
    }

    .file-viewer-header-left {
        display: flex;
        align-items: center;
    }

    .file-viewer-title {
        font-size: 16px;
        font-weight: 500;
        color: #202124;
        margin-left: 12px;
        max-width: 500px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .file-viewer-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .file-viewer-action-btn {
        background: none;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #5f6368;
        transition: background-color 0.2s;
    }

    .file-viewer-action-btn:hover {
        background-color: #f1f3f4;
        color: #202124;
    }

    .file-viewer-action-btn.primary {
        background-color: #1a73e8;
        color: white;
    }

    .file-viewer-action-btn.primary:hover {
        background-color: #1765cc;
    }

    .file-viewer-icon {
        width: 24px;
        height: 24px;
        margin-right: 4px;
    }

    .file-viewer-content {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: auto;
        background-color: #f8f9fa;
        position: relative;
    }

    .file-viewer-loading {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(255, 255, 255, 0.8);
        z-index: 1;
    }

    .file-viewer-spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-left: 4px solid #1a73e8;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    .file-viewer-image {
        max-width: 95%;
        max-height: 85vh;
        object-fit: contain;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .file-viewer-pdf {
        width: 100%;
        height: calc(100vh - 64px);
        border: none;
    }

    .file-viewer-unsupported {
        background-color: white;
        padding: 32px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .file-viewer-unsupported-icon {
        font-size: 48px;
        color: #5f6368;
        margin-bottom: 16px;
    }

    .file-viewer-download-prompt {
        margin-top: 16px;
        display: inline-block;
        background-color: #1a73e8;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .file-viewer-download-prompt:hover {
        background-color: #1765cc;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @media (max-width: 768px) {
        .file-viewer-header {
            height: 56px;
        }
        
        .file-viewer-title {
            max-width: 200px;
        }
        
        .file-viewer-pdf {
            height: calc(100vh - 56px);
        }
    }

    /* Add styles for text file viewing and improved responsive design */
    .file-viewer-text {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 4px;
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Consolas', 'source-code-pro', monospace;
        font-size: 0.875rem;
        line-height: 1.5;
        overflow: auto;
        max-width: 95%;
        margin: 0 auto;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        white-space: pre-wrap;
        max-height: 80vh;
    }

    .file-viewer-modal {
        z-index: 9999;
    }

    /* Improve responsive design for mobile devices */
    @media (max-width: 640px) {
        .file-viewer-header {
            height: 56px;
            padding: 0 12px;
        }
        
        .file-viewer-title {
            max-width: 160px;
            font-size: 14px;
        }
        
        .file-viewer-action-btn {
            width: 36px;
            height: 36px;
        }
        
        .file-viewer-pdf {
            height: calc(100vh - 56px);
        }
        
        .file-viewer-text {
            font-size: 0.75rem;
            padding: 0.75rem;
        }
        
        .file-viewer-image {
            max-height: 80vh;
        }
        
        .file-viewer-unsupported {
            padding: 24px 16px;
        }
    }

    /* Handle file type-specific styles */
    .language-json, .language-sql, .language-html, 
    .language-javascript, .language-css, .language-xml {
        background: #282c34;
        color: #abb2bf;
        border-radius: 6px;
    }

    /* File viewer status indicators */
    .file-viewer-loading-text {
        text-align: center;
        color: #6b7280;
        margin-top: 8px;
        font-size: 14px;
    }

    /* File status indicator */
    .file-status {
        display: inline-flex;
        align-items: center;
        margin-left: 8px;
        font-size: 12px;
        color: #6b7280;
    }

    .file-status-icon {
        margin-right: 4px;
    }

    /* File viewer action button hover effects */
    .file-viewer-action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .file-viewer-action-btn:active {
        transform: translateY(0);
    }

    /* File viewer content improvements */
    .file-viewer-content {
        padding: 0.5rem;
    }

    /* Message link styles */
    .message-text a {
        color: #3390ec;
        text-decoration: none;
        transition: all 0.2s ease;
        word-break: break-word;
    }
    
    .message-text a:hover {
        text-decoration: underline;
        color: #2d7fd1;
    }
    
    .message-text a:active {
        color: #186ab8;
    }
</style>
@endpush