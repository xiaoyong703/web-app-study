// Study Timer functionality
class StudyTimer {
    constructor() {
        this.timeLeft = 25 * 60; // 25 minutes in seconds
        this.originalTime = 25 * 60;
        this.isRunning = false;
        this.isPaused = false;
        this.interval = null;
        this.currentSubject = 'Math';
        
        this.timerDisplay = document.getElementById('timer-time');
        this.timerLabel = document.getElementById('timer-label');
        this.startBtn = document.getElementById('start-btn');
        this.pauseBtn = document.getElementById('pause-btn');
        this.resetBtn = document.getElementById('reset-btn');
        this.subjectInput = document.getElementById('subject-input');
        this.presetButtons = document.querySelectorAll('.preset-btn');
        
        this.init();
    }
    
    init() {
        this.updateDisplay();
        this.bindEvents();
    }
    
    bindEvents() {
        if (this.startBtn) {
            this.startBtn.addEventListener('click', () => this.start());
        }
        
        if (this.pauseBtn) {
            this.pauseBtn.addEventListener('click', () => this.pause());
        }
        
        if (this.resetBtn) {
            this.resetBtn.addEventListener('click', () => this.reset());
        }
        
        if (this.subjectInput) {
            this.subjectInput.addEventListener('change', (e) => {
                this.currentSubject = e.target.value;
            });
        }
        
        this.presetButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const minutes = parseInt(e.target.dataset.minutes);
                this.setTime(minutes);
            });
        });
    }
    
    start() {
        if (!this.isRunning) {
            this.isRunning = true;
            this.isPaused = false;
            
            this.startBtn.disabled = true;
            this.pauseBtn.disabled = false;
            
            this.startBtn.innerHTML = '<i class="fas fa-play"></i> Running...';
            this.timerLabel.textContent = `Studying ${this.currentSubject}`;
            
            this.interval = setInterval(() => {
                this.timeLeft--;
                this.updateDisplay();
                
                if (this.timeLeft <= 0) {
                    this.complete();
                }
            }, 1000);
        }
    }
    
    pause() {
        if (this.isRunning && !this.isPaused) {
            this.isPaused = true;
            clearInterval(this.interval);
            
            this.startBtn.disabled = false;
            this.pauseBtn.disabled = true;
            
            this.startBtn.innerHTML = '<i class="fas fa-play"></i> Resume';
            this.timerLabel.textContent = 'Paused';
        }
    }
    
    reset() {
        this.isRunning = false;
        this.isPaused = false;
        clearInterval(this.interval);
        
        this.timeLeft = this.originalTime;
        this.updateDisplay();
        
        this.startBtn.disabled = false;
        this.pauseBtn.disabled = true;
        
        this.startBtn.innerHTML = '<i class="fas fa-play"></i> Start';
        this.timerLabel.textContent = 'Focus Time';
    }
    
    complete() {
        this.isRunning = false;
        clearInterval(this.interval);
        
        // Calculate session duration
        const sessionDuration = this.originalTime - this.timeLeft;
        
        // Save session to database
        this.saveSession(sessionDuration);
        
        // Show completion notification
        this.showNotification('Study session completed!', 'success');
        
        // Play notification sound (optional)
        this.playNotificationSound();
        
        // Reset timer
        this.reset();
        
        // Update statistics
        if (window.statsManager) {
            window.statsManager.refresh();
        }
    }
    
    setTime(minutes) {
        if (!this.isRunning) {
            this.timeLeft = minutes * 60;
            this.originalTime = minutes * 60;
            this.updateDisplay();
        }
    }
    
    updateDisplay() {
        if (this.timerDisplay) {
            this.timerDisplay.textContent = this.formatTime(this.timeLeft);
        }
        
        // Update document title with timer
        if (this.isRunning && !this.isPaused) {
            document.title = `${this.formatTime(this.timeLeft)} - YPT Study`;
        } else {
            document.title = 'YPT Study App - Focus & Excel';
        }
    }
    
    formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    }
    
    async saveSession(duration) {
        try {
            const response = await fetch('api/save-session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    subject: this.currentSubject,
                    duration: duration
                })
            });
            
            if (!response.ok) {
                throw new Error('Failed to save session');
            }
        } catch (error) {
            console.error('Error saving session:', error);
        }
    }
    
    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        // Style the notification
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--primary-color);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
    
    playNotificationSound() {
        // Create audio context for notification sound
        if (typeof AudioContext !== 'undefined' || typeof webkitAudioContext !== 'undefined') {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
            oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);
            oscillator.frequency.setValueAtTime(800, audioContext.currentTime + 0.2);
            
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.5);
        }
    }
}

// Global function for quick timer start
function startQuickTimer(minutes) {
    if (window.studyTimer) {
        window.studyTimer.setTime(minutes);
        window.studyTimer.start();
    }
}

// Initialize timer when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.studyTimer = new StudyTimer();
});