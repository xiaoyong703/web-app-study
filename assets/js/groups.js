// Study Groups Management System
class StudyGroupsManager {
    constructor() {
        this.currentGroup = null;
        this.userGroups = [];
        this.messages = [];
        this.isConnected = false;
        
        this.init();
    }
    
    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.bindEvents();
            this.loadUserGroups();
            this.initializeChat();
        });
    }
    
    bindEvents() {
        // Group navigation
        document.addEventListener('click', (e) => {
            if (e.target.matches('.join-group-btn')) {
                const groupId = e.target.dataset.groupId;
                this.joinGroup(groupId);
            }
            
            if (e.target.matches('.leave-group-btn')) {
                const groupId = e.target.dataset.groupId;
                this.leaveGroup(groupId);
            }
            
            if (e.target.matches('.view-group-btn')) {
                const groupId = e.target.dataset.groupId;
                this.viewGroup(groupId);
            }
            
            if (e.target.matches('.invite-member-btn')) {
                this.showInviteModal();
            }
        });
        
        // Group creation form
        const createGroupForm = document.getElementById('create-group-form');
        if (createGroupForm) {
            createGroupForm.addEventListener('submit', (e) => this.createGroup(e));
        }
        
        // Join with code form
        const joinCodeForm = document.getElementById('join-code-form');
        if (joinCodeForm) {
            joinCodeForm.addEventListener('submit', (e) => this.joinWithCode(e));
        }
        
        // Chat functionality
        const chatForm = document.getElementById('chat-form');
        if (chatForm) {
            chatForm.addEventListener('submit', (e) => this.sendMessage(e));
        }
        
        // Search functionality
        const searchInput = document.getElementById('search-groups');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => this.searchGroups(e.target.value));
        }
        
        // File upload
        const fileInput = document.getElementById('file-upload');
        if (fileInput) {
            fileInput.addEventListener('change', (e) => this.handleFileUpload(e));
        }
    }
    
    async loadUserGroups() {
        try {
            const response = await fetch('api/groups/get-user-groups.php');
            const data = await response.json();
            
            if (data.success) {
                this.userGroups = data.groups;
                this.renderMyGroups();
                
                // Load first group if available
                if (this.userGroups.length > 0 && !this.currentGroup) {
                    this.viewGroup(this.userGroups[0].id);
                }
            }
        } catch (error) {
            console.error('Error loading user groups:', error);
            this.showNotification('Failed to load groups', 'error');
        }
    }
    
    async loadAvailableGroups() {
        try {
            const response = await fetch('api/groups/get-available-groups.php');
            const data = await response.json();
            
            if (data.success) {
                this.renderAvailableGroups(data.groups);
            }
        } catch (error) {
            console.error('Error loading available groups:', error);
        }
    }
    
    renderMyGroups() {
        const myGroupsList = document.getElementById('my-groups-list');
        if (!myGroupsList) return;
        
        if (this.userGroups.length === 0) {
            myGroupsList.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>You haven't joined any groups yet</p>
                    <button class="btn btn-primary" onclick="openCreateGroupModal()">Create Your First Group</button>
                </div>
            `;
            return;
        }
        
        myGroupsList.innerHTML = this.userGroups.map(group => `
            <div class="group-card ${this.currentGroup?.id === group.id ? 'active' : ''}">
                <div class="group-avatar">
                    <img src="${group.avatar || 'assets/images/default-group.png'}" alt="${group.name}">
                    <div class="member-count">${group.member_count}</div>
                </div>
                <div class="group-info">
                    <h4>${group.name}</h4>
                    <p>${group.description}</p>
                    <div class="group-meta">
                        <span class="group-subject">${group.subject}</span>
                        <span class="group-activity">Active ${this.getRelativeTime(group.last_activity)}</span>
                    </div>
                </div>
                <div class="group-actions">
                    <button class="btn btn-sm btn-primary view-group-btn" data-group-id="${group.id}">
                        <i class="fas fa-eye"></i> View
                    </button>
                    ${group.is_admin ? `
                        <button class="btn btn-sm btn-outline manage-group-btn" data-group-id="${group.id}">
                            <i class="fas fa-cog"></i> Manage
                        </button>
                    ` : `
                        <button class="btn btn-sm btn-danger leave-group-btn" data-group-id="${group.id}">
                            <i class="fas fa-sign-out-alt"></i> Leave
                        </button>
                    `}
                </div>
            </div>
        `).join('');
    }
    
    renderAvailableGroups(groups) {
        const availableGroupsList = document.getElementById('available-groups-list');
        if (!availableGroupsList) return;
        
        availableGroupsList.innerHTML = groups.map(group => `
            <div class="group-card">
                <div class="group-avatar">
                    <img src="${group.avatar || 'assets/images/default-group.png'}" alt="${group.name}">
                    <div class="member-count">${group.member_count}</div>
                </div>
                <div class="group-info">
                    <h4>${group.name}</h4>
                    <p>${group.description}</p>
                    <div class="group-meta">
                        <span class="group-subject">${group.subject}</span>
                        <span class="group-privacy ${group.is_private ? 'private' : 'public'}">
                            <i class="fas fa-${group.is_private ? 'lock' : 'globe'}"></i>
                            ${group.is_private ? 'Private' : 'Public'}
                        </span>
                    </div>
                </div>
                <div class="group-actions">
                    <button class="btn btn-primary join-group-btn" data-group-id="${group.id}">
                        <i class="fas fa-plus"></i> Join Group
                    </button>
                </div>
            </div>
        `).join('');
    }
    
    async viewGroup(groupId) {
        try {
            const response = await fetch(`api/groups/get-group-details.php?id=${groupId}`);
            const data = await response.json();
            
            if (data.success) {
                this.currentGroup = data.group;
                this.showGroupInterface();
                this.loadGroupMessages();
                this.loadGroupFiles();
                this.loadGroupMembers();
            }
        } catch (error) {
            console.error('Error loading group details:', error);
            this.showNotification('Failed to load group', 'error');
        }
    }
    
    showGroupInterface() {
        const groupInterface = document.getElementById('group-interface');
        const groupsList = document.getElementById('groups-list');
        
        if (groupInterface && groupsList) {
            groupsList.style.display = 'none';
            groupInterface.style.display = 'block';
            
            // Update group header
            document.getElementById('current-group-name').textContent = this.currentGroup.name;
            document.getElementById('current-group-description').textContent = this.currentGroup.description;
            document.getElementById('current-group-members').textContent = `${this.currentGroup.member_count} members`;
        }
        
        // Update active group in sidebar
        this.renderMyGroups();
    }
    
    async loadGroupMessages() {
        try {
            const response = await fetch(`api/groups/get-messages.php?group_id=${this.currentGroup.id}`);
            const data = await response.json();
            
            if (data.success) {
                this.messages = data.messages;
                this.renderMessages();
            }
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }
    
    renderMessages() {
        const chatMessages = document.getElementById('chat-messages');
        if (!chatMessages) return;
        
        chatMessages.innerHTML = this.messages.map(message => `
            <div class="message ${message.is_own ? 'own' : ''}">
                <div class="message-avatar">
                    <img src="${message.user_avatar || 'assets/images/default-avatar.png'}" alt="${message.username}">
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <span class="message-author">${message.username}</span>
                        <span class="message-time">${this.formatTime(message.created_at)}</span>
                    </div>
                    <div class="message-text">${this.formatMessageContent(message.content)}</div>
                    ${message.attachment ? this.renderAttachment(message.attachment) : ''}
                </div>
            </div>
        `).join('');
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    formatMessageContent(content) {
        // Convert URLs to links and handle basic markdown
        return content
            .replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank">$1</a>')
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/`(.*?)`/g, '<code>$1</code>');
    }
    
    renderAttachment(attachment) {
        const fileType = attachment.type || 'file';
        const fileIcon = this.getFileIcon(fileType);
        
        return `
            <div class="message-attachment">
                <div class="attachment-info">
                    <i class="fas fa-${fileIcon}"></i>
                    <span class="attachment-name">${attachment.name}</span>
                    <span class="attachment-size">${this.formatFileSize(attachment.size)}</span>
                </div>
                <a href="${attachment.url}" class="btn btn-sm btn-outline" download>
                    <i class="fas fa-download"></i> Download
                </a>
            </div>
        `;
    }
    
    async sendMessage(e) {
        e.preventDefault();
        
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();
        
        if (!message) return;
        
        try {
            const response = await fetch('api/groups/send-message.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    group_id: this.currentGroup.id,
                    content: message
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                messageInput.value = '';
                this.loadGroupMessages(); // Refresh messages
            }
        } catch (error) {
            console.error('Error sending message:', error);
            this.showNotification('Failed to send message', 'error');
        }
    }
    
    async createGroup(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const groupData = {
            name: formData.get('name'),
            description: formData.get('description'),
            subject: formData.get('subject'),
            is_private: formData.get('privacy') === 'private',
            max_members: formData.get('max_members') || 50
        };
        
        try {
            const response = await fetch('api/groups/create-group.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(groupData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Group created successfully!', 'success');
                this.closeCreateModal();
                this.loadUserGroups();
            } else {
                this.showNotification(data.message || 'Failed to create group', 'error');
            }
        } catch (error) {
            console.error('Error creating group:', error);
            this.showNotification('Error creating group', 'error');
        }
    }
    
    async joinGroup(groupId) {
        try {
            const response = await fetch('api/groups/join-group.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ group_id: groupId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Successfully joined group!', 'success');
                this.loadUserGroups();
                this.loadAvailableGroups();
            } else {
                this.showNotification(data.message || 'Failed to join group', 'error');
            }
        } catch (error) {
            console.error('Error joining group:', error);
            this.showNotification('Error joining group', 'error');
        }
    }
    
    async joinWithCode(e) {
        e.preventDefault();
        
        const inviteCode = document.getElementById('invite-code').value.trim();
        if (!inviteCode) return;
        
        try {
            const response = await fetch('api/groups/join-with-code.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ invite_code: inviteCode })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Successfully joined group!', 'success');
                this.loadUserGroups();
                document.getElementById('invite-code').value = '';
            } else {
                this.showNotification(data.message || 'Invalid invite code', 'error');
            }
        } catch (error) {
            console.error('Error joining with code:', error);
            this.showNotification('Error joining group', 'error');
        }
    }
    
    async leaveGroup(groupId) {
        if (!confirm('Are you sure you want to leave this group?')) return;
        
        try {
            const response = await fetch('api/groups/leave-group.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ group_id: groupId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Left group successfully', 'success');
                this.loadUserGroups();
                
                if (this.currentGroup?.id === groupId) {
                    this.backToGroupsList();
                }
            } else {
                this.showNotification(data.message || 'Failed to leave group', 'error');
            }
        } catch (error) {
            console.error('Error leaving group:', error);
            this.showNotification('Error leaving group', 'error');
        }
    }
    
    async handleFileUpload(e) {
        const files = e.target.files;
        if (!files.length) return;
        
        const formData = new FormData();
        formData.append('group_id', this.currentGroup.id);
        
        Array.from(files).forEach(file => {
            formData.append('files[]', file);
        });
        
        try {
            const response = await fetch('api/groups/upload-files.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Files uploaded successfully!', 'success');
                this.loadGroupFiles();
                e.target.value = ''; // Clear input
            } else {
                this.showNotification(data.message || 'Failed to upload files', 'error');
            }
        } catch (error) {
            console.error('Error uploading files:', error);
            this.showNotification('Error uploading files', 'error');
        }
    }
    
    async loadGroupFiles() {
        try {
            const response = await fetch(`api/groups/get-files.php?group_id=${this.currentGroup.id}`);
            const data = await response.json();
            
            if (data.success) {
                this.renderGroupFiles(data.files);
            }
        } catch (error) {
            console.error('Error loading group files:', error);
        }
    }
    
    renderGroupFiles(files) {
        const filesList = document.getElementById('group-files-list');
        if (!filesList) return;
        
        if (files.length === 0) {
            filesList.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>No files shared yet</p>
                </div>
            `;
            return;
        }
        
        filesList.innerHTML = files.map(file => `
            <div class="file-item">
                <div class="file-icon">
                    <i class="fas fa-${this.getFileIcon(file.type)}"></i>
                </div>
                <div class="file-info">
                    <h5>${file.name}</h5>
                    <div class="file-meta">
                        <span>Uploaded by ${file.uploader}</span>
                        <span>${this.formatFileSize(file.size)}</span>
                        <span>${this.getRelativeTime(file.uploaded_at)}</span>
                    </div>
                </div>
                <div class="file-actions">
                    <a href="${file.url}" class="btn btn-sm btn-outline" download>
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
        `).join('');
    }
    
    async loadGroupMembers() {
        try {
            const response = await fetch(`api/groups/get-members.php?group_id=${this.currentGroup.id}`);
            const data = await response.json();
            
            if (data.success) {
                this.renderGroupMembers(data.members);
            }
        } catch (error) {
            console.error('Error loading group members:', error);
        }
    }
    
    renderGroupMembers(members) {
        const membersList = document.getElementById('group-members-list');
        if (!membersList) return;
        
        membersList.innerHTML = members.map(member => `
            <div class="member-item">
                <div class="member-avatar">
                    <img src="${member.avatar || 'assets/images/default-avatar.png'}" alt="${member.username}">
                    ${member.is_online ? '<div class="online-indicator"></div>' : ''}
                </div>
                <div class="member-info">
                    <h5>${member.username}</h5>
                    <div class="member-role ${member.role}">${member.role}</div>
                </div>
                <div class="member-stats">
                    <span class="study-time">${member.study_time}h</span>
                    <span class="member-since">Joined ${this.getRelativeTime(member.joined_at)}</span>
                </div>
            </div>
        `).join('');
    }
    
    backToGroupsList() {
        const groupInterface = document.getElementById('group-interface');
        const groupsList = document.getElementById('groups-list');
        
        if (groupInterface && groupsList) {
            groupInterface.style.display = 'none';
            groupsList.style.display = 'block';
            this.currentGroup = null;
        }
    }
    
    searchGroups(searchTerm) {
        // Implement search functionality
        this.loadAvailableGroups();
    }
    
    initializeChat() {
        // Initialize real-time chat if WebSocket is available
        // For now, we'll use polling
        setInterval(() => {
            if (this.currentGroup) {
                this.loadGroupMessages();
            }
        }, 10000); // Refresh every 10 seconds
    }
    
    // Utility functions
    getRelativeTime(timestamp) {
        const now = new Date();
        const time = new Date(timestamp);
        const diff = Math.floor((now - time) / 1000);
        
        if (diff < 60) return 'just now';
        if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
        if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
        return Math.floor(diff / 86400) + 'd ago';
    }
    
    formatTime(timestamp) {
        return new Date(timestamp).toLocaleTimeString();
    }
    
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    getFileIcon(fileType) {
        const iconMap = {
            'pdf': 'file-pdf',
            'doc': 'file-word',
            'docx': 'file-word',
            'xls': 'file-excel',
            'xlsx': 'file-excel',
            'ppt': 'file-powerpoint',
            'pptx': 'file-powerpoint',
            'jpg': 'file-image',
            'jpeg': 'file-image',
            'png': 'file-image',
            'gif': 'file-image',
            'mp4': 'file-video',
            'mp3': 'file-audio',
            'zip': 'file-archive',
            'rar': 'file-archive'
        };
        
        return iconMap[fileType] || 'file';
    }
    
    closeCreateModal() {
        const modal = document.getElementById('create-group-modal');
        if (modal) {
            modal.style.display = 'none';
        }
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
function openCreateGroupModal() {
    const modal = document.getElementById('create-group-modal');
    if (modal) {
        modal.style.display = 'block';
    }
}

function closeCreateGroupModal() {
    window.studyGroupsManager.closeCreateModal();
}

function backToGroupsList() {
    window.studyGroupsManager.backToGroupsList();
}

// Initialize study groups manager
document.addEventListener('DOMContentLoaded', () => {
    window.studyGroupsManager = new StudyGroupsManager();
});