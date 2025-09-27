// Main application functionality
document.addEventListener('DOMContentLoaded', function() {
    // Focus mode functionality
    let focusModeActive = false;
    
    // Break modal functionality 
    const breakModal = document.getElementById('break-modal');
    const closeModal = breakModal?.querySelector('.close');
    const breakButtons = document.querySelectorAll('.break-btn');
    
    // Modal event listeners
    if (closeModal) {
        closeModal.addEventListener('click', closeBreakModal);
    }
    
    if (breakModal) {
        window.addEventListener('click', (e) => {
            if (e.target === breakModal) {
                closeBreakModal();
            }
        });
    }
    
    breakButtons.forEach(btn => {
        btn.addEventListener('click', handleBreakSelection);
    });
    
    // Add smooth scrolling for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add loading states to buttons
    document.addEventListener('click', function(e) {
        if (e.target.matches('.btn:not(.theme-btn):not(.profile-btn)')) {
            const button = e.target;
            const originalText = button.innerHTML;
            
            // Don't add loading to certain buttons
            if (button.id === 'start-btn' || button.id === 'pause-btn' || button.id === 'reset-btn') {
                return;
            }
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            button.disabled = true;
            
            // Reset after 2 seconds (adjust based on actual operation time)
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 2000);
        }
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + Space: Start/pause timer
        if ((e.ctrlKey || e.metaKey) && e.code === 'Space') {
            e.preventDefault();
            const startBtn = document.getElementById('start-btn');
            const pauseBtn = document.getElementById('pause-btn');
            
            if (startBtn && !startBtn.disabled) {
                startBtn.click();
            } else if (pauseBtn && !pauseBtn.disabled) {
                pauseBtn.click();
            }
        }
        
        // Ctrl/Cmd + R: Reset timer
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            const resetBtn = document.getElementById('reset-btn');
            if (resetBtn) {
                resetBtn.click();
            }
        }
        
        // Ctrl/Cmd + T: Toggle theme
        if ((e.ctrlKey || e.metaKey) && e.key === 't') {
            e.preventDefault();
            const themeBtn = document.getElementById('theme-toggle');
            if (themeBtn) {
                themeBtn.click();
            }
        }
        
        // Escape: Close modals
        if (e.key === 'Escape') {
            closeBreakModal();
        }
    });
    
    // Auto-save functionality for form inputs
    const autoSaveInputs = document.querySelectorAll('input[data-autosave], select[data-autosave], textarea[data-autosave]');
    autoSaveInputs.forEach(input => {
        input.addEventListener('change', function() {
            const key = `autosave_${this.name || this.id}`;
            localStorage.setItem(key, this.value);
        });
        
        // Restore saved values
        const key = `autosave_${input.name || input.id}`;
        const savedValue = localStorage.getItem(key);
        if (savedValue) {
            input.value = savedValue;
        }
    });
});

// Global functions
function toggleFocusMode() {
    const focusModeActive = document.body.classList.toggle('focus-mode');
    
    if (focusModeActive) {
        // Add focus mode styles
        const style = document.createElement('style');
        style.id = 'focus-mode-styles';
        style.textContent = `
            .focus-mode .navbar,
            .focus-mode .actions-card,
            .focus-mode .stats-card {
                opacity: 0.3;
                pointer-events: none;
            }
            .focus-mode .timer-card {
                transform: scale(1.05);
                box-shadow: var(--shadow-lg);
            }
        `;
        document.head.appendChild(style);
        
        showNotification('Focus mode activated! 🎯', 'info');
    } else {
        // Remove focus mode styles
        const style = document.getElementById('focus-mode-styles');
        if (style) {
            style.remove();
        }
        
        showNotification('Focus mode deactivated', 'info');
    }
}

function showBreakOptions() {
    const modal = document.getElementById('break-modal');
    if (modal) {
        modal.style.display = 'block';
    }
}

function closeBreakModal() {
    const modal = document.getElementById('break-modal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function handleBreakSelection(e) {
    const breakType = e.target.closest('.break-btn').dataset.type;
    let breakDuration;
    
    switch (breakType) {
        case 'nap':
            breakDuration = 20;
            break;
        case 'meal':
            breakDuration = 30;
            break;
        case 'walk':
            breakDuration = 15;
            break;
        case 'custom':
            const customTime = prompt('Enter break duration in minutes:');
            breakDuration = parseInt(customTime);
            if (isNaN(breakDuration) || breakDuration <= 0) {
                showNotification('Invalid duration entered', 'error');
                return;
            }
            break;
    }
    
    if (breakDuration) {
        startBreakTimer(breakDuration, breakType);
        closeBreakModal();
    }
}

function startBreakTimer(minutes, type) {
    // Create a simple break timer
    let timeLeft = minutes * 60;
    const breakNotification = showPersistentNotification(
        `Break time: ${formatBreakTime(timeLeft)} (${type})`,
        'info'
    );
    
    const interval = setInterval(() => {
        timeLeft--;
        breakNotification.textContent = `Break time: ${formatBreakTime(timeLeft)} (${type})`;
        
        if (timeLeft <= 0) {
            clearInterval(interval);
            breakNotification.remove();
            showNotification('Break time is over! Ready to study? 💪', 'success');
        }
    }, 1000);
    
    // Add click to end break early
    breakNotification.addEventListener('click', () => {
        clearInterval(interval);
        breakNotification.remove();
        showNotification('Break ended early', 'info');
    });
}

function formatBreakTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    let backgroundColor;
    switch (type) {
        case 'success':
            backgroundColor = 'var(--success-color)';
            break;
        case 'error':
            backgroundColor = 'var(--danger-color)';
            break;
        case 'warning':
            backgroundColor = 'var(--warning-color)';
            break;
        case 'info':
        default:
            backgroundColor = 'var(--primary-color)';
            break;
    }
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${backgroundColor};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: var(--shadow-lg);
        z-index: 1000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        cursor: pointer;
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }
    }, 3000);
    
    // Click to dismiss
    notification.addEventListener('click', () => {
        if (notification.parentNode) {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }
    });
}

function showPersistentNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type} persistent`;
    notification.textContent = message;
    
    let backgroundColor;
    switch (type) {
        case 'success':
            backgroundColor = 'var(--success-color)';
            break;
        case 'error':
            backgroundColor = 'var(--danger-color)';
            break;
        case 'warning':
            backgroundColor = 'var(--warning-color)';
            break;
        case 'info':
        default:
            backgroundColor = 'var(--primary-color)';
            break;
    }
    
    notification.style.cssText = `
        position: fixed;
        top: 70px;
        right: 20px;
        background: ${backgroundColor};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: var(--shadow-lg);
        z-index: 1000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        cursor: pointer;
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    return notification;
}