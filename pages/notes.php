<?php
// Notes Management System
?>
<div class="notes-page">
    <!-- Notes Header -->
    <div class="notes-header">
        <div class="header-content">
            <h1><i class="fas fa-sticky-note"></i> Study Notes</h1>
            <p>Organize, upload, and access all your study materials in one place</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="openCreateNoteModal()">
                <i class="fas fa-plus"></i> New Note
            </button>
            <button class="btn btn-outline" onclick="openUploadModal()">
                <i class="fas fa-cloud-upload-alt"></i> Upload Files
            </button>
        </div>
    </div>

    <!-- Notes Navigation -->
    <div class="notes-nav">
        <div class="nav-tabs">
            <button class="nav-tab active" data-view="all">
                <i class="fas fa-th-large"></i> All Notes
            </button>
            <button class="nav-tab" data-view="text">
                <i class="fas fa-font"></i> Text Notes
            </button>
            <button class="nav-tab" data-view="files">
                <i class="fas fa-file"></i> Uploaded Files
            </button>
            <button class="nav-tab" data-view="images">
                <i class="fas fa-image"></i> Images
            </button>
            <button class="nav-tab" data-view="pdfs">
                <i class="fas fa-file-pdf"></i> PDFs
            </button>
        </div>
        
        <div class="notes-filters">
            <div class="search-box">
                <input type="text" id="notes-search" class="form-control" placeholder="Search notes and files...">
                <i class="fas fa-search"></i>
            </div>
            
            <select id="subject-filter" class="form-control">
                <option value="">All Subjects</option>
                <option value="Math">Math</option>
                <option value="Science">Science</option>
                <option value="English">English</option>
                <option value="History">History</option>
                <option value="Other">Other</option>
            </select>
            
            <select id="sort-by" class="form-control">
                <option value="recent">Recently Updated</option>
                <option value="created">Recently Created</option>
                <option value="name">Name A-Z</option>
                <option value="size">File Size</option>
            </select>
        </div>
    </div>

    <!-- Notes Grid -->
    <div class="notes-content">
        <div id="notes-grid" class="notes-grid">
            <!-- Notes will be populated by JavaScript -->
        </div>
        
        <div id="notes-empty" class="empty-state" style="display: none;">
            <i class="fas fa-sticky-note"></i>
            <h3>No notes yet</h3>
            <p>Create your first note or upload study materials to get started</p>
            <button class="btn btn-primary" onclick="openCreateNoteModal()">
                <i class="fas fa-plus"></i> Create Note
            </button>
        </div>
    </div>
</div>

<!-- Create/Edit Note Modal -->
<div id="note-modal" class="modal">
    <div class="modal-content note-editor-modal">
        <div class="modal-header">
            <h3 id="note-modal-title">Create New Note</h3>
            <button class="close-modal" onclick="closeNoteModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="note-form">
            <div class="modal-body">
                <div class="form-group">
                    <label for="note-title">Note Title</label>
                    <input type="text" id="note-title" class="form-control" placeholder="Enter note title..." required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="note-subject">Subject</label>
                        <select id="note-subject" class="form-control" required>
                            <option value="">Select subject...</option>
                            <option value="Math">Math</option>
                            <option value="Science">Science</option>
                            <option value="English">English</option>
                            <option value="History">History</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="note-tags">Tags</label>
                        <input type="text" id="note-tags" class="form-control" placeholder="Add tags (comma separated)">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="note-content">Content</label>
                    <div class="editor-toolbar">
                        <button type="button" class="editor-btn" data-command="bold" title="Bold">
                            <i class="fas fa-bold"></i>
                        </button>
                        <button type="button" class="editor-btn" data-command="italic" title="Italic">
                            <i class="fas fa-italic"></i>
                        </button>
                        <button type="button" class="editor-btn" data-command="underline" title="Underline">
                            <i class="fas fa-underline"></i>
                        </button>
                        <div class="editor-separator"></div>
                        <button type="button" class="editor-btn" data-command="insertUnorderedList" title="Bullet List">
                            <i class="fas fa-list-ul"></i>
                        </button>
                        <button type="button" class="editor-btn" data-command="insertOrderedList" title="Numbered List">
                            <i class="fas fa-list-ol"></i>
                        </button>
                        <div class="editor-separator"></div>
                        <button type="button" class="editor-btn" onclick="insertLink()" title="Insert Link">
                            <i class="fas fa-link"></i>
                        </button>
                        <button type="button" class="editor-btn" onclick="insertImage()" title="Insert Image">
                            <i class="fas fa-image"></i>
                        </button>
                    </div>
                    <div id="note-content" class="note-editor" contenteditable="true" placeholder="Start typing your note..."></div>
                </div>
                
                <div class="form-group">
                    <label>Attachments</label>
                    <div class="file-upload-area">
                        <input type="file" id="note-attachments" multiple accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif">
                        <div class="upload-placeholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Drag files here or click to browse</p>
                            <small>Supported: PDF, DOC, TXT, JPG, PNG (Max 10MB each)</small>
                        </div>
                        <div id="attachment-preview" class="attachment-preview"></div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeNoteModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Note
                </button>
            </div>
        </form>
    </div>
</div>

<!-- File Upload Modal -->
<div id="upload-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Upload Files</h3>
            <button class="close-modal" onclick="closeUploadModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="upload-zone" id="upload-zone">
                <div class="upload-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <h4>Drag & Drop Files Here</h4>
                <p>or click to browse files</p>
                <input type="file" id="bulk-file-input" multiple accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.ppt,.pptx,.xls,.xlsx">
                <button class="btn btn-primary" onclick="document.getElementById('bulk-file-input').click()">
                    <i class="fas fa-folder-open"></i> Choose Files
                </button>
            </div>
            
            <div class="file-info">
                <h4>Supported File Types:</h4>
                <div class="file-types">
                    <span class="file-type">PDF</span>
                    <span class="file-type">DOC/DOCX</span>
                    <span class="file-type">PPT/PPTX</span>
                    <span class="file-type">XLS/XLSX</span>
                    <span class="file-type">TXT</span>
                    <span class="file-type">JPG/PNG</span>
                </div>
                <p><strong>Maximum file size:</strong> 10MB per file</p>
                <p><strong>Total storage:</strong> <span id="storage-used">0 MB</span> / 1 GB used</p>
            </div>
            
            <div id="upload-progress" class="upload-progress" style="display: none;">
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <div class="progress-text">
                    <span id="upload-percent">0%</span>
                    <span id="upload-status">Uploading...</span>
                </div>
            </div>
            
            <div id="upload-queue" class="upload-queue"></div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeUploadModal()">Close</button>
            <button type="button" class="btn btn-success" id="start-upload-btn" onclick="startUpload()" disabled>
                <i class="fas fa-upload"></i> Start Upload
            </button>
        </div>
    </div>
</div>

<!-- Note Viewer Modal -->
<div id="note-viewer-modal" class="modal">
    <div class="modal-content note-viewer">
        <div class="modal-header">
            <div class="note-viewer-title">
                <h3 id="viewer-note-title"></h3>
                <div class="note-meta">
                    <span id="viewer-note-subject" class="subject-tag"></span>
                    <span id="viewer-note-date" class="note-date"></span>
                </div>
            </div>
            <div class="viewer-actions">
                <button class="btn btn-sm btn-outline" onclick="editCurrentNote()">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-sm btn-outline" onclick="shareNote()">
                    <i class="fas fa-share"></i> Share
                </button>
                <button class="close-modal" onclick="closeNoteViewer()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="modal-body">
            <div id="viewer-note-content" class="note-content"></div>
            
            <div id="viewer-attachments" class="note-attachments">
                <h4>Attachments</h4>
                <div id="attachment-list" class="attachment-list"></div>
            </div>
            
            <div id="viewer-tags" class="note-tags">
                <h4>Tags</h4>
                <div id="tag-list" class="tag-list"></div>
            </div>
        </div>
    </div>
</div>

<!-- File Viewer Modal -->
<div id="file-viewer-modal" class="modal">
    <div class="modal-content file-viewer">
        <div class="modal-header">
            <h3 id="file-viewer-title"></h3>
            <div class="file-viewer-actions">
                <button class="btn btn-sm btn-outline" onclick="downloadCurrentFile()">
                    <i class="fas fa-download"></i> Download
                </button>
                <button class="btn btn-sm btn-outline" onclick="shareCurrentFile()">
                    <i class="fas fa-share"></i> Share
                </button>
                <button class="close-modal" onclick="closeFileViewer()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="modal-body">
            <div id="file-viewer-content" class="file-viewer-content">
                <!-- File content will be loaded here -->
            </div>
        </div>
    </div>
</div>