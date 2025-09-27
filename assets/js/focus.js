// Focus Mode Management System
class FocusManager {
    constructor() {
        this.isActive = false;
        this.startTime = null;
        this.focusTimer = null;
        this.breakTimer = null;
        this.currentSession = null;
        this.blockedSites = [];
        this.allowedSites = [];
        this.distractionCount = 0;
        this.pomodoroSettings = {
            workTime: 25 * 60, // 25 minutes
            shortBreak: 5 * 60, // 5 minutes
            longBreak: 15 * 60, // 15 minutes
            sessionsUntilLongBreak: 4
        };
        this.currentPomodoroSession = 0;
        
        this.init();
    }
    
    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.bindEvents();
            this.loadFocusSettings();
            this.loadBlockedSites();
            this.initializeUI();
        });
    }
    
    bindEvents() {
        // Focus session controls
        document.addEventListener('click', (e) => {
            if (e.target.matches('#start-focus-btn')) {
                this.startFocusSession();
            }
            
            if (e.target.matches('#pause-focus-btn')) {
                this.pauseFocusSession();
            }
            
            if (e.target.matches('#stop-focus-btn')) {
                this.stopFocusSession();
            }
            
            if (e.target.matches('.focus-preset-btn')) {
                const minutes = parseInt(e.target.dataset.minutes);
                this.setFocusTime(minutes);
            }
            
            if (e.target.matches('#start-pomodoro-btn')) {
                this.startPomodoroSession();
            }
        });
        
        // Website blocking controls
        document.addEventListener('click', (e) => {
            if (e.target.matches('#add-blocked-site-btn')) {
                this.addBlockedSite();
            }
            
            if (e.target.matches('.remove-blocked-site')) {
                const site = e.target.dataset.site;
                this.removeBlockedSite(site);
            }
            
            if (e.target.matches('#add-allowed-site-btn')) {
                this.addAllowedSite();
            }
            
            if (e.target.matches('.remove-allowed-site')) {
                const site = e.target.dataset.site;
                this.removeAllowedSite(site);
            }
        });
        
        // Settings form
        const focusSettingsForm = document.getElementById('focus-settings-form');
        if (focusSettingsForm) {
            focusSettingsForm.addEventListener('submit', (e) => this.saveFocusSettings(e));
        }
        
        // Pomodoro settings
        const pomodoroSettingsForm = document.getElementById('pomodoro-settings-form');
        if (pomodoroSettingsForm) {
            pomodoroSettingsForm.addEventListener('submit', (e) => this.savePomodoroSettings(e));
        }
        
        // Environment controls
        document.addEventListener('click', (e) => {
            if (e.target.matches('#toggle-notifications-btn')) {
                this.toggleNotifications();
            }
            
            if (e.target.matches('#toggle-fullscreen-btn')) {
                this.toggleFullscreen();
            }
            
            if (e.target.matches('#play-ambient-sound-btn')) {
                this.toggleAmbientSound();
            }
        });
        
        // Break controls
        document.addEventListener('click', (e) => {
            if (e.target.matches('#take-break-btn')) {
                this.takeBreak();
            }
            
            if (e.target.matches('#skip-break-btn')) {
                this.skipBreak();
            }
        });
    }
    
    initializeUI() {
        this.updateFocusDisplay();
        this.renderBlockedSites();
        this.renderAllowedSites();
        this.updatePomodoroDisplay();
    }
    
    async loadFocusSettings() {
        try {
            const response = await fetch('api/focus/get-settings.php');
            const data = await response.json();
            
            if (data.success) {
                this.applySettings(data.settings);
            }
        } catch (error) {
            console.error('Error loading focus settings:', error);
        }
    }
    
    async loadBlockedSites() {
        try {
            const response = await fetch('api/focus/get-blocked-sites.php');
            const data = await response.json();
            
            if (data.success) {
                this.blockedSites = data.blocked_sites;
                this.allowedSites = data.allowed_sites;
                this.renderBlockedSites();
                this.renderAllowedSites();
            }
        } catch (error) {
            console.error('Error loading blocked sites:', error);
        }
    }
    
    startFocusSession() {
        if (this.isActive) return;
        
        const duration = this.getFocusDuration();
        if (!duration || duration <= 0) {
            this.showNotification('Please set a valid focus duration', 'warning');
            return;
        }
        
        this.isActive = true;
        this.startTime = new Date();
        this.distractionCount = 0;
        
        // Start timer
        this.focusTimer = setTimeout(() => {
            this.completeFocusSession();
        }, duration * 1000);
        
        // Update UI
        this.updateFocusControls();
        this.startFocusDisplay(duration);
        
        // Enable website blocking
        this.enableWebsiteBlocking();
        
        // Start session tracking
        this.trackFocusSession();
        
        // Show focus overlay
        this.showFocusOverlay();
        
        this.showNotification('Focus session started! Stay focused!', 'success');
        
        // Play focus sound
        this.playFocusSound('start');
    }
    
    pauseFocusSession() {
        if (!this.isActive) return;
        
        clearTimeout(this.focusTimer);
        this.isActive = false;
        
        this.updateFocusControls();
        this.pauseFocusDisplay();
        this.hideFocusOverlay();
        
        this.showNotification('Focus session paused', 'info');
    }
    
    stopFocusSession() {
        if (!this.isActive && !this.focusTimer) return;
        
        clearTimeout(this.focusTimer);
        this.isActive = false;
        this.startTime = null;
        
        this.updateFocusControls();
        this.resetFocusDisplay();
        this.hideFocusOverlay();
        this.disableWebsiteBlocking();
        
        // Save session data
        this.saveFocusSessionData();
        
        this.showNotification('Focus session ended', 'info');
        this.playFocusSound('stop');
    }
    
    completeFocusSession() {
        this.isActive = false;
        this.updateFocusControls();
        this.hideFocusOverlay();
        
        // Save successful session
        this.saveFocusSessionData(true);
        
        // Show completion modal
        this.showCompletionModal();
        
        // Update achievements
        this.updateAchievements();
        
        this.playFocusSound('complete');
    }
    
    startPomodoroSession() {
        this.currentPomodoroSession++;
        const isBreakTime = this.currentPomodoroSession % 2 === 0;
        
        if (isBreakTime) {
            const isLongBreak = (this.currentPomodoroSession / 2) % this.pomodoroSettings.sessionsUntilLongBreak === 0;
            const breakDuration = isLongBreak ? this.pomodoroSettings.longBreak : this.pomodoroSettings.shortBreak;
            
            this.startBreakTimer(breakDuration, isLongBreak ? 'long' : 'short');
        } else {
            this.setFocusTime(this.pomodoroSettings.workTime / 60);
            this.startFocusSession();
        }
        
        this.updatePomodoroDisplay();
    }
    
    startBreakTimer(duration, type) {
        this.showBreakModal(duration, type);
        
        this.breakTimer = setTimeout(() => {
            this.completeBreak();
        }, duration * 1000);
        
        this.updateBreakDisplay(duration);
    }
    
    completeBreak() {
        this.hideBreakModal();
        clearTimeout(this.breakTimer);
        
        this.showNotification('Break time is over! Ready for another focus session?', 'info');
        this.playFocusSound('break-end');
        
        // Auto-start next pomodoro session
        setTimeout(() => {
            this.startPomodoroSession();
        }, 2000);
    }
    
    takeBreak() {
        if (!this.isActive) return;
        
        this.pauseFocusSession();
        this.startBreakTimer(5 * 60, 'custom'); // 5-minute break
    }
    
    skipBreak() {
        clearTimeout(this.breakTimer);
        this.hideBreakModal();
        this.showNotification('Break skipped', 'info');
    }
    
    enableWebsiteBlocking() {
        // In a real implementation, this would communicate with a browser extension
        // or system-level blocking mechanism
        this.monitorWebsiteAccess();
    }
    
    disableWebsiteBlocking() {
        // Disable website monitoring
        if (this.websiteMonitor) {
            clearInterval(this.websiteMonitor);
        }
    }
    
    monitorWebsiteAccess() {
        // Monitor for attempts to access blocked sites
        this.websiteMonitor = setInterval(() => {
            // This would check current tab/window against blocked sites
            // For demo purposes, we'll simulate occasional distractions
            if (Math.random() < 0.01) { // 1% chance per check
                this.handleDistraction();
            }
        }, 1000);
    }
    
    handleDistraction() {
        this.distractionCount++;
        this.showDistractionWarning();
        this.updateDistractionCounter();
        
        // Penalize or adjust focus session
        if (this.distractionCount >= 3) {
            this.showMajorDistractionWarning();
        }
    }
    
    showDistractionWarning() {
        const warning = document.createElement('div');
        warning.className = 'distraction-warning';
        warning.innerHTML = `
            <div class="warning-content">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Stay Focused!</h3>
                <p>You attempted to access a blocked site. Keep your focus on your studies!</p>
                <button class="btn btn-primary" onclick="this.parentElement.parentElement.remove()">
                    Got it!
                </button>
            </div>
        `;
        
        document.body.appendChild(warning);
        
        setTimeout(() => {
            if (warning.parentNode) {
                warning.remove();
            }
        }, 5000);
        
        this.playFocusSound('distraction');
    }
    
    addBlockedSite() {
        const input = document.getElementById('blocked-site-input');
        const site = input.value.trim();
        
        if (!site) return;
        
        if (!this.blockedSites.includes(site)) {
            this.blockedSites.push(site);
            this.saveBlockedSites();
            this.renderBlockedSites();
            input.value = '';
            
            this.showNotification(`${site} added to blocked sites`, 'success');
        } else {
            this.showNotification('Site already blocked', 'warning');
        }
    }
    
    removeBlockedSite(site) {
        this.blockedSites = this.blockedSites.filter(s => s !== site);
        this.saveBlockedSites();
        this.renderBlockedSites();
        
        this.showNotification(`${site} removed from blocked sites`, 'success');
    }
    
    addAllowedSite() {
        const input = document.getElementById('allowed-site-input');
        const site = input.value.trim();
        
        if (!site) return;
        
        if (!this.allowedSites.includes(site)) {
            this.allowedSites.push(site);
            this.saveBlockedSites();
            this.renderAllowedSites();
            input.value = '';
            
            this.showNotification(`${site} added to allowed sites`, 'success');
        } else {
            this.showNotification('Site already allowed', 'warning');
        }
    }
    
    removeAllowedSite(site) {
        this.allowedSites = this.allowedSites.filter(s => s !== site);
        this.saveBlockedSites();
        this.renderAllowedSites();
        
        this.showNotification(`${site} removed from allowed sites`, 'success');
    }
    
    renderBlockedSites() {
        const blockedSitesList = document.getElementById('blocked-sites-list');
        if (!blockedSitesList) return;
        
        if (this.blockedSites.length === 0) {
            blockedSitesList.innerHTML = '<p class="empty-state">No blocked sites yet</p>';
            return;
        }
        
        blockedSitesList.innerHTML = this.blockedSites.map(site => `
            <div class="site-item blocked">
                <span class="site-url">${site}</span>
                <button class="btn btn-sm btn-danger remove-blocked-site" data-site="${site}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `).join('');
    }
    
    renderAllowedSites() {
        const allowedSitesList = document.getElementById('allowed-sites-list');
        if (!allowedSitesList) return;
        
        if (this.allowedSites.length === 0) {
            allowedSitesList.innerHTML = '<p class="empty-state">No allowed sites yet</p>';
            return;
        }
        
        allowedSitesList.innerHTML = this.allowedSites.map(site => `
            <div class="site-item allowed">
                <span class="site-url">${site}</span>
                <button class="btn btn-sm btn-danger remove-allowed-site" data-site="${site}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `).join('');
    }
    
    showFocusOverlay() {
        const overlay = document.getElementById('focus-overlay');
        if (overlay) {
            overlay.style.display = 'flex';
        }
    }
    
    hideFocusOverlay() {
        const overlay = document.getElementById('focus-overlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    }
    
    showCompletionModal() {
        const modal = document.createElement('div');
        modal.className = 'focus-completion-modal';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="completion-animation">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Focus Session Complete!</h2>
                <div class="session-stats">
                    <div class="stat">
                        <span class="stat-value">${this.formatDuration(this.getFocusDuration())}</span>
                        <span class="stat-label">Focused Time</span>
                    </div>
                    <div class="stat">
                        <span class="stat-value">${this.distractionCount}</span>
                        <span class="stat-label">Distractions</span>
                    </div>
                </div>
                <div class="completion-actions">
                    <button class="btn btn-primary" onclick="this.parentElement.parentElement.parentElement.remove()">
                        <i class="fas fa-trophy"></i> Great Job!
                    </button>
                    <button class="btn btn-outline" onclick="window.focusManager.startFocusSession(); this.parentElement.parentElement.parentElement.remove()">
                        <i class="fas fa-redo"></i> Start Another
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        setTimeout(() => {
            if (modal.parentNode) {
                modal.remove();
            }
        }, 10000);
    }
    
    showBreakModal(duration, type) {
        const modal = document.getElementById('break-reminder-modal');
        if (modal) {
            modal.style.display = 'block';
            
            const title = type === 'long' ? 'Long Break Time!' : 
                         type === 'short' ? 'Short Break Time!' : 'Break Time!';
            
            modal.querySelector('h3').textContent = title;
        }
    }
    
    hideBreakModal() {
        const modal = document.getElementById('break-reminder-modal');
        if (modal) {
            modal.style.display = 'none';
        }
    }
    
    updateFocusControls() {
        const startBtn = document.getElementById('start-focus-btn');
        const pauseBtn = document.getElementById('pause-focus-btn');
        const stopBtn = document.getElementById('stop-focus-btn');
        
        if (startBtn && pauseBtn && stopBtn) {
            if (this.isActive) {
                startBtn.style.display = 'none';
                pauseBtn.style.display = 'inline-block';
                stopBtn.style.display = 'inline-block';
            } else {
                startBtn.style.display = 'inline-block';
                pauseBtn.style.display = 'none';
                stopBtn.style.display = this.focusTimer ? 'inline-block' : 'none';
            }
        }
    }
    
    updateFocusDisplay() {
        const display = document.getElementById('focus-time-display');
        if (display) {
            const duration = this.getFocusDuration();
            display.textContent = this.formatDuration(duration);
        }
    }
    
    updatePomodoroDisplay() {
        const display = document.getElementById('pomodoro-session-display');
        if (display) {
            const sessionNumber = Math.ceil(this.currentPomodoroSession / 2);
            display.textContent = `Session ${sessionNumber}`;
        }
    }
    
    updateDistractionCounter() {
        const counter = document.getElementById('distraction-counter');
        if (counter) {
            counter.textContent = this.distractionCount;
        }
    }
    
    setFocusTime(minutes) {
        document.getElementById('focus-duration').value = minutes;
        this.updateFocusDisplay();
    }
    
    getFocusDuration() {
        const durationInput = document.getElementById('focus-duration');
        return durationInput ? parseInt(durationInput.value) * 60 : 1500; // Default 25 minutes
    }
    
    async saveFocusSettings(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const settings = {
            default_duration: formData.get('default_duration'),
            break_interval: formData.get('break_interval'),
            notifications_enabled: formData.get('notifications_enabled') === 'on',
            ambient_sounds: formData.get('ambient_sounds'),
            theme: formData.get('theme')
        };
        
        try {
            const response = await fetch('api/focus/save-settings.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(settings)
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Focus settings saved!', 'success');
            }
        } catch (error) {
            console.error('Error saving focus settings:', error);
            this.showNotification('Failed to save settings', 'error');
        }
    }
    
    async saveBlockedSites() {
        try {
            const response = await fetch('api/focus/save-blocked-sites.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    blocked_sites: this.blockedSites,
                    allowed_sites: this.allowedSites
                })
            });
            
            const data = await response.json();
            
            if (!data.success) {
                console.error('Failed to save blocked sites');
            }
        } catch (error) {
            console.error('Error saving blocked sites:', error);
        }
    }
    
    async trackFocusSession() {
        // Track session start
        try {
            const response = await fetch('api/focus/track-session.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'start',
                    duration: this.getFocusDuration()
                })
            });
        } catch (error) {
            console.error('Error tracking focus session:', error);
        }
    }
    
    async saveFocusSessionData(completed = false) {
        const duration = this.startTime ? Math.floor((new Date() - this.startTime) / 1000) : 0;
        
        try {
            const response = await fetch('api/focus/save-session.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    duration: duration,
                    completed: completed,
                    distractions: this.distractionCount,
                    session_type: 'focus'
                })
            });
        } catch (error) {
            console.error('Error saving focus session data:', error);
        }
    }
    
    playFocusSound(type) {
        try {
            const audio = new Audio(`assets/sounds/focus-${type}.mp3`);
            audio.volume = 0.3;
            audio.play().catch(() => {
                // Silently fail if sound can't play
            });
        } catch (error) {
            // Sound not available
        }
    }
    
    toggleNotifications() {
        // Toggle system notifications
        if ('Notification' in window) {
            if (Notification.permission === 'granted') {
                this.showNotification('Notifications are enabled', 'info');
            } else {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        this.showNotification('Notifications enabled!', 'success');
                    }
                });
            }
        }
    }
    
    toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(() => {
                this.showNotification('Fullscreen not supported', 'warning');
            });
        } else {
            document.exitFullscreen();
        }
    }
    
    toggleAmbientSound() {
        // Toggle ambient background sounds
        const audio = document.getElementById('ambient-audio');
        if (audio) {
            if (audio.paused) {
                audio.play();
                this.showNotification('Ambient sounds enabled', 'info');
            } else {
                audio.pause();
                this.showNotification('Ambient sounds disabled', 'info');
            }
        }
    }
    
    formatDuration(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        
        if (hours > 0) {
            return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
        return `${minutes}:${secs.toString().padStart(2, '0')}`;
    }
    
    updateAchievements() {
        if (window.achievementsManager) {
            window.achievementsManager.checkAchievements();
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
function startFocusSession() {
    window.focusManager.startFocusSession();
}

function pauseFocusSession() {
    window.focusManager.pauseFocusSession();
}

function stopFocusSession() {
    window.focusManager.stopFocusSession();
}

function takeBreak() {
    window.focusManager.takeBreak();
}

function skipBreak() {
    window.focusManager.skipBreak();
}

// Initialize focus manager
document.addEventListener('DOMContentLoaded', () => {
    window.focusManager = new FocusManager();
});