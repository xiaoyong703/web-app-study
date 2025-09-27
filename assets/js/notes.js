// Notes Management System
class NotesManager {
    constructor() {
        this.notes = [];
        this.files = [];
        this.currentView = 'all';
        this.currentNote = null;
        this.currentFile = null;
        this.uploadQueue = [];
        this.isUploading = false;
        
        this.init();
    }
    
    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.bindEvents();
            this.loadNotes();
            this.loadStorageInfo();
            this.setupDragAndDrop();
            this.initializeEditor();
        });
    }
    
    bindEvents() {
        // Navigation tabs
        document.addEventListener('click', (e) => {
            if (e.target.matches('.nav-tab')) {
                this.switchView(e.target.dataset.view);
            }
            
            if (e.target.matches('.note-card') || e.target.closest('.note-card')) {
                const noteCard = e.target.closest('.note-card');
                const noteId = noteCard.dataset.noteId;
                const fileId = noteCard.dataset.fileId;
                
                if (noteId) {
                    this.viewNote(noteId);
                } else if (fileId) {
                    this.viewFile(fileId);
                }
            }
        });
        
        // Search and filters
        document.getElementById('notes-search').addEventListener('input', (e) => {
            this.searchNotes(e.target.value);
        });
        
        document.getElementById('subject-filter').addEventListener('change', (e) => {
            this.filterBySubject(e.target.value);
        });
        
        document.getElementById('sort-by').addEventListener('change', (e) => {
            this.sortNotes(e.target.value);
        });
        
        // Note form
        document.getElementById('note-form').addEventListener('submit', (e) => {
            this.saveNote(e);
        });
        
        // File upload
        document.getElementById('bulk-file-input').addEventListener('change', (e) => {
            this.handleFileSelection(e.target.files);
        });
        
        document.getElementById('note-attachments').addEventListener('change', (e) => {
            this.previewAttachments(e.target.files);
        });
        
        // Editor toolbar
        document.addEventListener('click', (e) => {
            if (e.target.matches('.editor-btn')) {
                const command = e.target.dataset.command;
                if (command) {
                    this.execEditorCommand(command);
                }
            }
        });
    }
    
    async loadNotes() {
        try {
            const response = await fetch('api/notes/get-notes.php');
            const data = await response.json();
            
            if (data.success) {
                this.notes = data.notes || [];
                this.files = data.files || [];
                this.renderNotes();
            }
        } catch (error) {
            console.error('Error loading notes:', error);
            this.showNotification('Failed to load notes', 'error');
        }
    }
    
    renderNotes() {
        const notesGrid = document.getElementById('notes-grid');
        const emptyState = document.getElementById('notes-empty');
        
        let itemsToRender = [];
        
        switch (this.currentView) {
            case 'all':
                itemsToRender = [...this.notes, ...this.files];
                break;
            case 'text':
                itemsToRender = this.notes;
                break;
            case 'files':
                itemsToRender = this.files;
                break;
            case 'images':
                itemsToRender = this.files.filter(file => this.isImageFile(file.name));
                break;
            case 'pdfs':
                itemsToRender = this.files.filter(file => file.name.toLowerCase().endsWith('.pdf'));
                break;
        }
        
        if (itemsToRender.length === 0) {
            notesGrid.style.display = 'none';
            emptyState.style.display = 'block';
            return;
        }
        
        notesGrid.style.display = 'grid';
        emptyState.style.display = 'none';
        
        notesGrid.innerHTML = itemsToRender.map(item => {
            return item.type === 'note' || !item.type ? 
                this.createNoteCard(item) : this.createFileCard(item);
        }).join('');
    }
    
    createNoteCard(note) {
        const preview = this.getTextPreview(note.content);
        const tags = note.tags ? note.tags.split(',').map(tag => tag.trim()) : [];
        
        return `
            <div class="note-card text-note" data-note-id="${note.id}">
                <div class="note-header">
                    <div class="note-title">${note.title || 'Untitled Note'}</div>
                    <div class="note-actions">
                        <button class="note-action-btn" onclick="editNote(${note.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="note-action-btn" onclick="deleteNote(${note.id})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="note-preview">
                    ${preview}
                </div>
                
                <div class="note-footer">
                    <div class="note-meta">
                        <span class="subject-tag ${note.subject?.toLowerCase()}">${note.subject || 'General'}</span>
                        <span class="note-date">${this.formatDate(note.updated_at || note.created_at)}</span>
                    </div>
                    
                    ${tags.length > 0 ? `
                        <div class="note-tags-preview">
                            ${tags.slice(0, 3).map(tag => `<span class="tag">${tag}</span>`).join('')}
                            ${tags.length > 3 ? '<span class="tag-more">+' + (tags.length - 3) + '</span>' : ''}
                        </div>
                    ` : ''}
                </div>
                
                ${note.attachment_count > 0 ? `
                    <div class="note-attachments-indicator">
                        <i class="fas fa-paperclip"></i>
                        <span>${note.attachment_count} file${note.attachment_count > 1 ? 's' : ''}</span>
                    </div>
                ` : ''}
            </div>
        `;
    }
    
    createFileCard(file) {
        const fileIcon = this.getFileIcon(file.name);
        const fileSize = this.formatFileSize(file.size);
        
        return `
            <div class="note-card file-card" data-file-id="${file.id}">
                <div class="file-icon-large">
                    ${this.isImageFile(file.name) ? 
                        `<img src="${file.thumbnail_url || file.url}" alt="${file.name}" class="file-thumbnail">` :
                        `<i class="fas fa-${fileIcon}"></i>`
                    }
                </div>
                
                <div class="file-info">
                    <div class="file-name" title="${file.name}">${this.truncateFileName(file.name)}</div>
                    <div class="file-meta">
                        <span class="file-size">${fileSize}</span>
                        <span class="file-date">${this.formatDate(file.uploaded_at)}</span>
                    </div>
                </div>
                
                <div class="file-actions">
                    <button class="file-action-btn" onclick="downloadFile(${file.id})" title="Download">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="file-action-btn" onclick="shareFile(${file.id})" title="Share">
                        <i class="fas fa-share"></i>
                    </button>
                    <button class="file-action-btn" onclick="deleteFile(${file.id})" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                
                ${file.subject ? `
                    <div class="file-subject">
                        <span class="subject-tag ${file.subject.toLowerCase()}">${file.subject}</span>
                    </div>
                ` : ''}
            </div>
        `;
    }
    
    switchView(view) {
        // Update active tab
        document.querySelectorAll('.nav-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        document.querySelector(`[data-view="${view}"]`).classList.add('active');
        
        this.currentView = view;
        this.renderNotes();
    }
    
    async viewNote(noteId) {
        try {
            const response = await fetch(`api/notes/get-note.php?id=${noteId}`);
            const data = await response.json();
            
            if (data.success) {
                this.currentNote = data.note;
                this.showNoteViewer(data.note);
            }
        } catch (error) {
            console.error('Error loading note:', error);
            this.showNotification('Failed to load note', 'error');
        }
    }
    
    showNoteViewer(note) {
        const modal = document.getElementById('note-viewer-modal');
        
        document.getElementById('viewer-note-title').textContent = note.title;
        document.getElementById('viewer-note-subject').textContent = note.subject || 'General';
        document.getElementById('viewer-note-subject').className = `subject-tag ${(note.subject || 'general').toLowerCase()}`;
        document.getElementById('viewer-note-date').textContent = this.formatDate(note.updated_at);
        document.getElementById('viewer-note-content').innerHTML = note.content || '<p>No content</p>';
        
        // Show attachments
        if (note.attachments && note.attachments.length > 0) {
            document.getElementById('viewer-attachments').style.display = 'block';
            document.getElementById('attachment-list').innerHTML = note.attachments.map(attachment => `
                <div class="attachment-item">
                    <div class="attachment-icon">
                        <i class="fas fa-${this.getFileIcon(attachment.name)}"></i>
                    </div>
                    <div class="attachment-info">
                        <div class="attachment-name">${attachment.name}</div>
                        <div class="attachment-size">${this.formatFileSize(attachment.size)}</div>
                    </div>
                    <div class="attachment-actions">
                        <button class="btn btn-sm btn-outline" onclick="downloadAttachment(${attachment.id})">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
            `).join('');
        } else {
            document.getElementById('viewer-attachments').style.display = 'none';
        }
        
        // Show tags
        if (note.tags) {
            const tags = note.tags.split(',').map(tag => tag.trim());
            document.getElementById('viewer-tags').style.display = 'block';
            document.getElementById('tag-list').innerHTML = tags.map(tag => 
                `<span class="tag">${tag}</span>`
            ).join('');
        } else {
            document.getElementById('viewer-tags').style.display = 'none';
        }
        
        modal.style.display = 'block';
    }
    
    async viewFile(fileId) {
        try {
            const response = await fetch(`api/notes/get-file.php?id=${fileId}`);
            const data = await response.json();
            
            if (data.success) {
                this.currentFile = data.file;
                this.showFileViewer(data.file);
            }
        } catch (error) {
            console.error('Error loading file:', error);
            this.showNotification('Failed to load file', 'error');
        }
    }
    
    showFileViewer(file) {
        const modal = document.getElementById('file-viewer-modal');
        const content = document.getElementById('file-viewer-content');
        
        document.getElementById('file-viewer-title').textContent = file.name;
        
        // Display file based on type
        if (this.isImageFile(file.name)) {
            content.innerHTML = `<img src="${file.url}" alt="${file.name}" class="file-preview-image">`;
        } else if (file.name.toLowerCase().endsWith('.pdf')) {
            content.innerHTML = `
                <iframe src="${file.url}" class="pdf-preview" frameborder="0"></iframe>
                <div class="pdf-fallback">
                    <p>Cannot display PDF? <a href="${file.url}" target="_blank">Open in new tab</a></p>
                </div>
            `;
        } else if (this.isTextFile(file.name)) {
            // Load text content
            this.loadTextFileContent(file.url, content);
        } else {
            content.innerHTML = `
                <div class="file-preview-placeholder">
                    <div class="file-icon-huge">
                        <i class="fas fa-${this.getFileIcon(file.name)}"></i>
                    </div>
                    <h3>${file.name}</h3>
                    <p>File size: ${this.formatFileSize(file.size)}</p>
                    <p>This file type cannot be previewed in the browser.</p>
                    <button class="btn btn-primary" onclick="downloadCurrentFile()">
                        <i class="fas fa-download"></i> Download File
                    </button>
                </div>
            `;
        }
        
        modal.style.display = 'block';
    }
    
    async saveNote(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('title', document.getElementById('note-title').value);
        formData.append('subject', document.getElementById('note-subject').value);
        formData.append('tags', document.getElementById('note-tags').value);
        formData.append('content', document.getElementById('note-content').innerHTML);
        
        // Add attachments
        const attachments = document.getElementById('note-attachments').files;
        for (let file of attachments) {
            formData.append('attachments[]', file);
        }
        
        if (this.currentNote) {
            formData.append('id', this.currentNote.id);
        }
        
        try {
            const response = await fetch('api/notes/save-note.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Note saved successfully!', 'success');
                this.closeNoteModal();
                this.loadNotes();
                
                // Award points for note creation
                this.awardNotePoints();
            } else {
                this.showNotification(data.message || 'Failed to save note', 'error');
            }
        } catch (error) {
            console.error('Error saving note:', error);
            this.showNotification('Error saving note', 'error');
        }
    }
    
    setupDragAndDrop() {
        const uploadZone = document.getElementById('upload-zone');
        
        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.classList.add('drag-over');
        });
        
        uploadZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('drag-over');
        });
        
        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('drag-over');
            
            const files = e.dataTransfer.files;
            this.handleFileSelection(files);
        });
        
        // Also setup drag and drop for the main notes area
        const notesGrid = document.getElementById('notes-grid');
        notesGrid.addEventListener('dragover', (e) => {
            e.preventDefault();
        });
        
        notesGrid.addEventListener('drop', (e) => {
            e.preventDefault();
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.openUploadModal();
                this.handleFileSelection(files);
            }
        });
    }
    
    handleFileSelection(files) {
        this.uploadQueue = [];
        
        Array.from(files).forEach(file => {
            if (this.validateFile(file)) {
                this.uploadQueue.push(file);
            }
        });
        
        this.displayUploadQueue();
        
        const startUploadBtn = document.getElementById('start-upload-btn');
        startUploadBtn.disabled = this.uploadQueue.length === 0;
    }
    
    validateFile(file) {
        const maxSize = 10 * 1024 * 1024; // 10MB
        const allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif'
        ];
        
        if (file.size > maxSize) {
            this.showNotification(`File "${file.name}" is too large. Maximum size is 10MB.`, 'error');
            return false;
        }
        
        if (!allowedTypes.includes(file.type) && !this.isAllowedFileExtension(file.name)) {
            this.showNotification(`File type "${file.type}" is not supported.`, 'error');
            return false;
        }
        
        return true;
    }
    
    isAllowedFileExtension(fileName) {
        const allowedExtensions = ['.pdf', '.doc', '.docx', '.ppt', '.pptx', '.xls', '.xlsx', '.txt', '.jpg', '.jpeg', '.png', '.gif'];
        const extension = fileName.toLowerCase().substring(fileName.lastIndexOf('.'));
        return allowedExtensions.includes(extension);
    }
    
    displayUploadQueue() {
        const uploadQueue = document.getElementById('upload-queue');
        
        uploadQueue.innerHTML = this.uploadQueue.map((file, index) => `
            <div class="upload-queue-item">
                <div class="file-info">
                    <div class="file-icon">
                        <i class="fas fa-${this.getFileIcon(file.name)}"></i>
                    </div>
                    <div class="file-details">
                        <div class="file-name">${file.name}</div>
                        <div class="file-size">${this.formatFileSize(file.size)}</div>
                    </div>
                </div>
                <button class="remove-file-btn" onclick="window.notesManager.removeFromQueue(${index})">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `).join('');
    }
    
    removeFromQueue(index) {
        this.uploadQueue.splice(index, 1);
        this.displayUploadQueue();
        
        const startUploadBtn = document.getElementById('start-upload-btn');
        startUploadBtn.disabled = this.uploadQueue.length === 0;
    }
    
    async startUpload() {
        if (this.isUploading || this.uploadQueue.length === 0) return;
        
        this.isUploading = true;
        const progressContainer = document.getElementById('upload-progress');
        const progressFill = progressContainer.querySelector('.progress-fill');
        const progressPercent = document.getElementById('upload-percent');
        const progressStatus = document.getElementById('upload-status');
        
        progressContainer.style.display = 'block';
        
        for (let i = 0; i < this.uploadQueue.length; i++) {
            const file = this.uploadQueue[i];
            
            progressStatus.textContent = `Uploading ${file.name}...`;
            
            try {
                await this.uploadSingleFile(file, (progress) => {
                    const totalProgress = ((i + progress / 100) / this.uploadQueue.length) * 100;
                    progressFill.style.width = totalProgress + '%';
                    progressPercent.textContent = Math.round(totalProgress) + '%';
                });
            } catch (error) {
                console.error('Error uploading file:', error);
                this.showNotification(`Failed to upload ${file.name}`, 'error');
            }
        }
        
        // Upload complete
        progressStatus.textContent = 'Upload complete!';
        progressFill.style.width = '100%';
        progressPercent.textContent = '100%';
        
        setTimeout(() => {
            progressContainer.style.display = 'none';
            this.closeUploadModal();
            this.loadNotes();
            this.loadStorageInfo();
        }, 2000);
        
        this.isUploading = false;
        this.showNotification('All files uploaded successfully!', 'success');
    }
    
    async uploadSingleFile(file, onProgress) {
        return new Promise((resolve, reject) => {
            const formData = new FormData();
            formData.append('file', file);
            
            const xhr = new XMLHttpRequest();
            
            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const progress = (e.loaded / e.total) * 100;
                    onProgress(progress);
                }
            });
            
            xhr.addEventListener('load', () => {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            resolve(response);
                        } else {
                            reject(new Error(response.message));
                        }
                    } catch (error) {
                        reject(error);
                    }
                } else {
                    reject(new Error('Upload failed'));
                }
            });
            
            xhr.addEventListener('error', () => {
                reject(new Error('Upload failed'));
            });
            
            xhr.open('POST', 'api/notes/upload-file.php');
            xhr.send(formData);
        });
    }
    
    initializeEditor() {
        const editor = document.getElementById('note-content');
        
        editor.addEventListener('paste', (e) => {
            e.preventDefault();
            const text = e.clipboardData.getData('text/plain');
            document.execCommand('insertText', false, text);
        });
    }
    
    execEditorCommand(command) {
        document.execCommand(command, false, null);
    }
    
    previewAttachments(files) {
        const preview = document.getElementById('attachment-preview');
        
        preview.innerHTML = Array.from(files).map(file => `
            <div class="attachment-preview-item">
                <div class="attachment-icon">
                    <i class="fas fa-${this.getFileIcon(file.name)}"></i>
                </div>
                <div class="attachment-info">
                    <div class="attachment-name">${file.name}</div>
                    <div class="attachment-size">${this.formatFileSize(file.size)}</div>
                </div>
            </div>
        `).join('');
    }
    
    async loadStorageInfo() {
        try {
            const response = await fetch('api/notes/get-storage-info.php');
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('storage-used').textContent = 
                    this.formatFileSize(data.used_storage);
            }
        } catch (error) {
            console.error('Error loading storage info:', error);
        }
    }
    
    searchNotes(query) {
        if (!query.trim()) {
            this.renderNotes();
            return;
        }
        
        const filtered = this.notes.filter(note => 
            note.title.toLowerCase().includes(query.toLowerCase()) ||
            note.content.toLowerCase().includes(query.toLowerCase()) ||
            (note.tags && note.tags.toLowerCase().includes(query.toLowerCase()))
        );
        
        const filteredFiles = this.files.filter(file =>
            file.name.toLowerCase().includes(query.toLowerCase())
        );
        
        this.renderFilteredResults([...filtered, ...filteredFiles]);
    }
    
    filterBySubject(subject) {
        if (!subject) {
            this.renderNotes();
            return;
        }
        
        const filtered = [...this.notes, ...this.files].filter(item => 
            item.subject === subject
        );
        
        this.renderFilteredResults(filtered);
    }
    
    renderFilteredResults(items) {
        const notesGrid = document.getElementById('notes-grid');
        const emptyState = document.getElementById('notes-empty');
        
        if (items.length === 0) {
            notesGrid.style.display = 'none';
            emptyState.style.display = 'block';
            return;
        }
        
        notesGrid.style.display = 'grid';
        emptyState.style.display = 'none';
        
        notesGrid.innerHTML = items.map(item => {
            return item.type === 'note' || !item.type ? 
                this.createNoteCard(item) : this.createFileCard(item);
        }).join('');
    }
    
    // Utility functions
    getTextPreview(html) {
        const temp = document.createElement('div');
        temp.innerHTML = html;
        const text = temp.textContent || temp.innerText || '';
        return text.length > 150 ? text.substring(0, 150) + '...' : text;
    }
    
    getFileIcon(fileName) {
        const extension = fileName.toLowerCase().split('.').pop();
        const iconMap = {
            'pdf': 'file-pdf',
            'doc': 'file-word',
            'docx': 'file-word',
            'ppt': 'file-powerpoint',
            'pptx': 'file-powerpoint',
            'xls': 'file-excel',
            'xlsx': 'file-excel',
            'txt': 'file-alt',
            'jpg': 'file-image',
            'jpeg': 'file-image',
            'png': 'file-image',
            'gif': 'file-image'
        };
        return iconMap[extension] || 'file';
    }
    
    isImageFile(fileName) {
        const extension = fileName.toLowerCase().split('.').pop();
        return ['jpg', 'jpeg', 'png', 'gif'].includes(extension);
    }
    
    isTextFile(fileName) {
        const extension = fileName.toLowerCase().split('.').pop();
        return ['txt'].includes(extension);
    }
    
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 1) return 'Yesterday';
        if (diffDays < 7) return diffDays + ' days ago';
        
        return date.toLocaleDateString();
    }
    
    truncateFileName(fileName) {
        return fileName.length > 30 ? fileName.substring(0, 27) + '...' : fileName;
    }
    
    awardNotePoints() {
        if (window.gamificationManager) {
            window.gamificationManager.awardPoints(15, 'note', 'Created/updated study note');
        }
    }
    
    // Modal functions
    openCreateNoteModal() {
        this.currentNote = null;
        document.getElementById('note-modal-title').textContent = 'Create New Note';
        document.getElementById('note-form').reset();
        document.getElementById('note-content').innerHTML = '';
        document.getElementById('attachment-preview').innerHTML = '';
        document.getElementById('note-modal').style.display = 'block';
    }
    
    closeNoteModal() {
        document.getElementById('note-modal').style.display = 'none';
    }
    
    openUploadModal() {
        document.getElementById('upload-modal').style.display = 'block';
    }
    
    closeUploadModal() {
        document.getElementById('upload-modal').style.display = 'none';
        this.uploadQueue = [];
        document.getElementById('upload-queue').innerHTML = '';
        document.getElementById('start-upload-btn').disabled = true;
    }
    
    closeNoteViewer() {
        document.getElementById('note-viewer-modal').style.display = 'none';
        this.currentNote = null;
    }
    
    closeFileViewer() {
        document.getElementById('file-viewer-modal').style.display = 'none';
        this.currentFile = null;
    }
    
    showNotification(message, type = 'info') {
        if (typeof showNotification === 'function') {
            showNotification(message, type);
        } else {
            console.log(message);
        }
    }
}

// Global functions
function openCreateNoteModal() {
    window.notesManager.openCreateNoteModal();
}

function closeNoteModal() {
    window.notesManager.closeNoteModal();
}

function openUploadModal() {
    window.notesManager.openUploadModal();
}

function closeUploadModal() {
    window.notesManager.closeUploadModal();
}

function closeNoteViewer() {
    window.notesManager.closeNoteViewer();
}

function closeFileViewer() {
    window.notesManager.closeFileViewer();
}

function startUpload() {
    window.notesManager.startUpload();
}

function editNote(noteId) {
    // Implement edit functionality
    window.notesManager.viewNote(noteId);
}

function deleteNote(noteId) {
    if (confirm('Are you sure you want to delete this note?')) {
        // Implement delete functionality
    }
}

function downloadFile(fileId) {
    window.open(`api/notes/download-file.php?id=${fileId}`, '_blank');
}

function shareFile(fileId) {
    // Implement share functionality
}

function deleteFile(fileId) {
    if (confirm('Are you sure you want to delete this file?')) {
        // Implement delete functionality
    }
}

// Initialize notes manager
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.notes-page')) {
        window.notesManager = new NotesManager();
    }
});