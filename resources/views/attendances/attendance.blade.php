@extends('layouts.app')

@section('styles')
<style>
    .attendance-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    }
    
    .upload-area {
        border: 2px dashed #6b21a8;
        border-radius: 8px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        min-height: 200px;
        position: relative;
    }
    
    .upload-area:hover {
        background-color: #f0f1f5;
        border-color: #7c3aed;
    }
    
    .upload-area.dragover {
        background-color: #eff6ff;
        border-color: #3b82f6;
    }
    
    .upload-area input[type="file"] {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }
    
    .image-preview-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }
    
    .image-preview {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        aspect-ratio: 4/3;
        background-color: #f8f9fa;
    }
    
    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .image-preview:hover img {
        transform: scale(1.05);
    }
    
    .image-preview .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #ef4444;
        opacity: 0;
        transition: opacity 0.2s ease;
        z-index: 2;
    }
    
    .image-preview:hover .remove-btn {
        opacity: 1;
    }
    
    .image-preview .image-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 8px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        font-size: 12px;
        transition: transform 0.3s ease;
        transform: translateY(100%);
        z-index: 1;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }
    
    .image-preview:hover .image-caption {
        transform: translateY(0);
    }
    
    .progress-area {
        margin-top: 15px;
    }
    
    .loader {
        width: 100%;
        height: 4px;
        border-radius: 2px;
        background-color: #e2e8f0;
        overflow: hidden;
        position: relative;
    }
    
    .loader .progress {
        position: absolute;
        height: 100%;
        background: linear-gradient(90deg, #6b21a8, #7c3aed);
        border-radius: 2px;
        transition: width 0.3s ease;
    }
    
    .photo-code-notice {
        border-left: 4px solid #f59e0b;
        background-color: #fffbeb;
        padding: 12px 15px;
        margin-bottom: 16px;
        border-radius: 6px;
    }
    
    .file-controls {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
    }
    
    .batch-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .pagination-controls {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
        gap: 10px;
    }
    
    .pagination-controls button {
        background: #f1f5f9;
        border: none;
        border-radius: 4px;
        padding: 5px 10px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .pagination-controls button:hover:not(:disabled) {
        background: #e2e8f0;
    }
    
    .pagination-controls button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .pagination-info {
        font-size: 14px;
        color: #6b7280;
    }
    
    .image-preview .status-badge {
        position: absolute;
        top: 5px;
        left: 5px;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: bold;
        z-index: 2;
    }
    
    .image-preview .status-badge.valid {
        background-color: rgba(52, 211, 153, 0.8);
        color: white;
    }
    
    .image-preview .status-badge.invalid {
        background-color: rgba(239, 68, 68, 0.8);
        color: white;
    }
    
    .image-preview .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 3;
    }
    
    .spinner {
        width: 24px;
        height: 24px;
        border: 3px solid #6b21a8;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    
    .file-size-warning {
        color: #ea580c;
        font-size: 12px;
        margin-top: 4px;
    }
    
    .card-stats {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .stat-item {
        flex: 1;
        background: #f8fafc;
        border-radius: 6px;
        padding: 12px;
        text-align: center;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .stat-value {
        font-size: 22px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 5px;
    }
    
    .stat-label {
        font-size: 13px;
        color: #64748b;
    }
    
    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .image-preview-container {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
    }
    
    @media (max-width: 992px) {
        .card-stats {
            flex-wrap: wrap;
        }
        
        .stat-item {
            flex-basis: calc(50% - 8px);
        }
    }
    
    @media (max-width: 768px) {
        .image-preview-container {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }
        
        .batch-actions {
            flex-wrap: wrap;
        }
    }
    
    @media (max-width: 576px) {
        .upload-area {
            min-height: 150px;
        }
        
        .image-preview-container {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
        }
        
        .stat-item {
            flex-basis: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card attendance-container">
                <div class="card-header bg-white">
                    <h4 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-calendar-check text-purple mr-2"></i>
                        Attendance Image Upload
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Upload multiple attendance images by selecting files or dragging and dropping them below. You can upload up to 50 images at once.
                    </div>
                    
                    <div class="photo-code-notice">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-triangle text-warning mr-2 mt-1"></i>
                            <div>
                                <h6 class="font-weight-bold mb-1">Important Notice</h6>
                                <p class="mb-0">Only images from <strong>Timemark Attendance</strong> that have a valid photo code can be uploaded. Other images will be rejected by the system.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-stats d-none" id="uploadStats">
                        <div class="stat-item">
                            <div class="stat-value" id="totalImagesCount">0</div>
                            <div class="stat-label">Total Images</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="validImagesCount">0</div>
                            <div class="stat-label">Valid Images</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="invalidImagesCount">0</div>
                            <div class="stat-label">Invalid Images</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="totalSizeDisplay">0 KB</div>
                            <div class="stat-label">Total Size</div>
                        </div>
                    </div>
                    
                    <div class="upload-area d-flex flex-column align-items-center justify-content-center p-4" id="uploadArea">
                        <div class="text-center">
                            <i class="fas fa-cloud-upload-alt text-primary" style="font-size: 48px;"></i>
                            <h5 class="mt-3">Drag & Drop Attendance Images</h5>
                            <p class="text-muted">or click to browse files</p>
                            <small class="d-block mt-2">Supported formats: JPG, PNG, JPEG (Max 20MB per image)</small>
                            <small class="d-block mt-1 text-warning"><i class="fas fa-tag mr-1"></i>Only Timemark Attendance images with photo code</small>
                        </div>
                        <input type="file" id="fileInput" multiple accept="image/*">
                    </div>
                    
                    <div class="batch-actions d-none" id="batchActions">
                        <button class="btn btn-sm btn-outline-danger" id="removeAllBtn">
                            <i class="fas fa-trash-alt mr-1"></i> Remove All
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" id="removeInvalidBtn">
                            <i class="fas fa-filter mr-1"></i> Remove Invalid
                        </button>
                        <div class="ml-auto">
                            <select id="sortImages" class="form-control form-control-sm">
                                <option value="name">Sort by Name</option>
                                <option value="size">Sort by Size</option>
                                <option value="date">Sort by Date Added</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="progress-area d-none" id="progressArea">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span id="progressText">Uploading...</span>
                            <span id="progressPercentage">0%</span>
                        </div>
                        <div class="loader">
                            <div class="progress" id="progressBar" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <div class="image-preview-container mt-4" id="previewContainer"></div>
                    
                    <div class="pagination-controls d-none" id="paginationControls">
                        <button id="prevPage" disabled><i class="fas fa-chevron-left mr-1"></i> Previous</button>
                        <div class="pagination-info" id="paginationInfo">Page 1 of 1</div>
                        <button id="nextPage" disabled>Next <i class="fas fa-chevron-right ml-1"></i></button>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted" id="uploadStatus">No files selected</span>
                        <button class="btn btn-primary" id="uploadBtn" disabled>
                            <span class="normal-state">
                                <i class="fas fa-upload mr-2"></i>Upload Files
                            </span>
                            <span class="loading-state d-none">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Uploading...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const previewContainer = document.getElementById('previewContainer');
        const uploadBtn = document.getElementById('uploadBtn');
        const uploadStatus = document.getElementById('uploadStatus');
        const progressArea = document.getElementById('progressArea');
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        const progressPercentage = document.getElementById('progressPercentage');
        const batchActions = document.getElementById('batchActions');
        const removeAllBtn = document.getElementById('removeAllBtn');
        const removeInvalidBtn = document.getElementById('removeInvalidBtn');
        const sortImages = document.getElementById('sortImages');
        const paginationControls = document.getElementById('paginationControls');
        const prevPage = document.getElementById('prevPage');
        const nextPage = document.getElementById('nextPage');
        const paginationInfo = document.getElementById('paginationInfo');
        const uploadStats = document.getElementById('uploadStats');
        const totalImagesCount = document.getElementById('totalImagesCount');
        const validImagesCount = document.getElementById('validImagesCount');
        const invalidImagesCount = document.getElementById('invalidImagesCount');
        const totalSizeDisplay = document.getElementById('totalSizeDisplay');
        
        let selectedFiles = [];
        let currentPage = 1;
        const itemsPerPage = 24;
        const maxFileSize = 20 * 1024 * 1024; // 20MB
        const maxTotalFiles = 50;
        
        // Drag and drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            uploadArea.classList.add('dragover');
        }
        
        function unhighlight() {
            uploadArea.classList.remove('dragover');
        }
        
        uploadArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }
        
        fileInput.addEventListener('change', function() {
            handleFiles(this.files);
        });
        
        function handleFiles(files) {
            if (files.length === 0) return;
            
            // Check if adding these files would exceed the maximum
            if (selectedFiles.length + files.length > maxTotalFiles) {
                showAlert(`You can upload a maximum of ${maxTotalFiles} files. You're trying to add ${files.length} more files to your current ${selectedFiles.length} files.`, 'error');
                return;
            }
            
            const newFiles = Array.from(files).filter(file => {
                // Only accept image files
                return file.type.startsWith('image/');
            });
            
            if (newFiles.length === 0) {
                showAlert('Please select valid image files (JPG, PNG, JPEG).', 'error');
                return;
            }
            
            // Check file sizes
            const oversizedFiles = newFiles.filter(file => file.size > maxFileSize);
            if (oversizedFiles.length > 0) {
                showAlert(`${oversizedFiles.length} file(s) exceed the maximum size of 20MB and will be excluded.`, 'warning');
                newFiles.forEach(file => {
                    if (file.size > maxFileSize) {
                        file.isValid = false;
                        file.validationMessage = 'File exceeds 20MB size limit';
                    }
                });
            }
            
            // Add each file with validation info
            newFiles.forEach(file => {
                file.id = Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                file.dateAdded = new Date();
                
                // Simulating photo code validation (in a real app, this would be done on the server)
                // For demo purposes, we'll consider files less than 1MB as having a valid photo code
                if (file.size < 1000000 && !file.hasOwnProperty('isValid')) {
                    file.isValid = true;
                } else if (!file.hasOwnProperty('isValid')) {
                    file.isValid = Math.random() > 0.3; // Randomly mark some as invalid for demonstration
                    if (!file.isValid && !file.validationMessage) {
                        file.validationMessage = 'No valid photo code detected';
                    }
                }
            });
            
            selectedFiles = [...selectedFiles, ...newFiles];
            
            // Show batch actions once files are added
            batchActions.classList.remove('d-none');
            uploadStats.classList.remove('d-none');
            
            updateUploadStatus();
            updateStats();
            renderPreviews();
        }
        
        function updateStats() {
            const validFiles = selectedFiles.filter(file => file.isValid);
            const invalidFiles = selectedFiles.filter(file => !file.isValid);
            const totalSize = selectedFiles.reduce((sum, file) => sum + file.size, 0);
            
            totalImagesCount.textContent = selectedFiles.length;
            validImagesCount.textContent = validFiles.length;
            invalidImagesCount.textContent = invalidFiles.length;
            totalSizeDisplay.textContent = formatFileSize(totalSize);
        }
        
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        function renderPreviews() {
            // Apply sorting
            sortSelectedFiles();
            
            // Calculate pagination
            const totalPages = Math.ceil(selectedFiles.length / itemsPerPage);
            
            // Update pagination controls
            if (totalPages > 1) {
                paginationControls.classList.remove('d-none');
                paginationInfo.textContent = `Page ${currentPage} of ${totalPages}`;
                prevPage.disabled = currentPage === 1;
                nextPage.disabled = currentPage === totalPages;
            } else {
                paginationControls.classList.add('d-none');
            }
            
            // Get current page items
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, selectedFiles.length);
            const currentPageItems = selectedFiles.slice(startIndex, endIndex);
            
            // Clear preview container
            previewContainer.innerHTML = '';
            
            // Add each preview item
            currentPageItems.forEach((file, index) => {
                const actualIndex = startIndex + index;
                
                // Create preview element
                const preview = document.createElement('div');
                preview.className = 'image-preview';
                preview.dataset.id = file.id;
                
                // Status badge (valid/invalid)
                const statusBadge = document.createElement('div');
                statusBadge.className = `status-badge ${file.isValid ? 'valid' : 'invalid'}`;
                statusBadge.textContent = file.isValid ? 'Valid' : 'Invalid';
                preview.appendChild(statusBadge);
                
                // Create loading overlay
                const loadingOverlay = document.createElement('div');
                loadingOverlay.className = 'loading-overlay';
                loadingOverlay.innerHTML = '<div class="spinner"></div>';
                preview.appendChild(loadingOverlay);
                
                // Create remove button
                const removeBtn = document.createElement('div');
                removeBtn.className = 'remove-btn';
                removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                removeBtn.dataset.id = file.id;
                removeBtn.addEventListener('click', function() {
                    removeFile(file.id);
                });
                preview.appendChild(removeBtn);
                
                // Create caption
                const caption = document.createElement('div');
                caption.className = 'image-caption';
                caption.textContent = file.name;
                if (!file.isValid && file.validationMessage) {
                    caption.textContent += ` - ${file.validationMessage}`;
                }
                preview.appendChild(caption);
                
                // Create a placeholder div for the image
                const img = document.createElement('img');
                img.alt = file.name;
                
                // Use lazy loading for images
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    loadingOverlay.remove(); // Remove loading overlay when image loads
                };
                
                // Add the image to the preview
                preview.appendChild(img);
                previewContainer.appendChild(preview);
                
                // Start reading the file data after the DOM update
                setTimeout(() => {
                    reader.readAsDataURL(file);
                }, 10);
            });
            
            // Enable upload button if files are selected and at least one is valid
            const hasValidFiles = selectedFiles.some(file => file.isValid);
            uploadBtn.disabled = selectedFiles.length === 0 || !hasValidFiles;
        }
        
        function sortSelectedFiles() {
            const sortBy = sortImages.value;
            
            switch (sortBy) {
                case 'name':
                    selectedFiles.sort((a, b) => a.name.localeCompare(b.name));
                    break;
                case 'size':
                    selectedFiles.sort((a, b) => b.size - a.size);
                    break;
                case 'date':
                    selectedFiles.sort((a, b) => b.dateAdded - a.dateAdded);
                    break;
            }
        }
        
        function removeFile(fileId) {
            const index = selectedFiles.findIndex(file => file.id === fileId);
            if (index !== -1) {
                selectedFiles.splice(index, 1);
                
                // Update pagination if needed
                const totalPages = Math.ceil(selectedFiles.length / itemsPerPage);
                if (currentPage > totalPages && totalPages > 0) {
                    currentPage = totalPages;
                }
                
                updateUploadStatus();
                updateStats();
                renderPreviews();
                
                // Hide batch actions if no files left
                if (selectedFiles.length === 0) {
                    batchActions.classList.add('d-none');
                    uploadStats.classList.add('d-none');
                }
            }
        }
        
        function updateUploadStatus() {
            if (selectedFiles.length === 0) {
                uploadStatus.textContent = 'No files selected';
            } else {
                const validFiles = selectedFiles.filter(file => file.isValid);
                uploadStatus.textContent = `${selectedFiles.length} file(s) selected (${validFiles.length} valid)`;
            }
        }
        
        // Batch actions
        removeAllBtn.addEventListener('click', function() {
            if (selectedFiles.length === 0) return;
            
            Swal.fire({
                title: 'Remove all files?',
                text: "Are you sure you want to remove all selected files?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, remove all',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    selectedFiles = [];
                    currentPage = 1;
                    updateUploadStatus();
                    updateStats();
                    renderPreviews();
                    batchActions.classList.add('d-none');
                    uploadStats.classList.add('d-none');
                }
            });
        });
        
        removeInvalidBtn.addEventListener('click', function() {
            const invalidFiles = selectedFiles.filter(file => !file.isValid);
            if (invalidFiles.length === 0) return;
            
            Swal.fire({
                title: 'Remove invalid files?',
                text: `Are you sure you want to remove ${invalidFiles.length} invalid files?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, remove invalid',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    selectedFiles = selectedFiles.filter(file => file.isValid);
                    currentPage = 1;
                    updateUploadStatus();
                    updateStats();
                    renderPreviews();
                    
                    if (selectedFiles.length === 0) {
                        batchActions.classList.add('d-none');
                        uploadStats.classList.add('d-none');
                    }
                }
            });
        });
        
        // Sorting
        sortImages.addEventListener('change', function() {
            renderPreviews();
        });
        
        // Pagination
        prevPage.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                renderPreviews();
            }
        });
        
        nextPage.addEventListener('click', function() {
            const totalPages = Math.ceil(selectedFiles.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderPreviews();
            }
        });
        
        uploadBtn.addEventListener('click', function() {
            if (selectedFiles.length === 0) return;
            
            const validFiles = selectedFiles.filter(file => file.isValid);
            if (validFiles.length === 0) {
                showAlert('No valid files to upload. Please select valid files.', 'error');
                return;
            }
            
            // Simulate upload process
            uploadBtn.disabled = true;
            uploadBtn.classList.add('is-loading');
            progressArea.classList.remove('d-none');
            
            // Create a FormData object for a real implementation
            // const formData = new FormData();
            // validFiles.forEach(file => {
            //     formData.append('images[]', file);
            // });
            
            // Simulate progress updates
            let progress = 0;
            const totalFiles = validFiles.length;
            const increment = totalFiles <= 10 ? 10 : 5; // Faster for fewer files
            const interval = setInterval(() => {
                progress += increment;
                progressBar.style.width = `${progress}%`;
                progressPercentage.textContent = `${progress}%`;
                
                if (progress >= 100) {
                    clearInterval(interval);
                    setTimeout(() => {
                        uploadComplete();
                    }, 500);
                }
            }, 200);
            
            // For real implementation, you would use fetch or XMLHttpRequest:
            /*
            fetch('/upload-attendance-images', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                uploadComplete(data);
            })
            .catch(error => {
                showAlert('Upload failed: ' + error.message, 'error');
                uploadBtn.disabled = false;
                uploadBtn.classList.remove('is-loading');
                progressArea.classList.add('d-none');
            });
            */
        });
        
        function uploadComplete() {
            progressText.textContent = 'Upload Complete!';
            
            // Show success message
            showAlert('Images uploaded successfully!', 'success');
            
            // Reset the form after a delay
            setTimeout(() => {
                selectedFiles = [];
                currentPage = 1;
                updateUploadStatus();
                updateStats();
                renderPreviews();
                uploadBtn.classList.remove('is-loading');
                progressArea.classList.add('d-none');
                progressBar.style.width = '0%';
                progressText.textContent = 'Uploading...';
                progressPercentage.textContent = '0%';
                batchActions.classList.add('d-none');
                uploadStats.classList.add('d-none');
            }, 2000);
        }
        
        function showAlert(message, type) {
            // Use SweetAlert2 if available in the layout
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'error',
                    title: type === 'success' ? 'Success' : type === 'warning' ? 'Warning' : 'Error',
                    text: message,
                    timer: 3000,
                    timerProgressBar: true
                });
            } else {
                // Fallback to alert
                alert(message);
            }
        }
    });
</script>
@endsection
