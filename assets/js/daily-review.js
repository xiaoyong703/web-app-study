// Daily Review System
class DailyReviewManager {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 4;
        this.startTime = null;
        this.reviewData = {
            mood: null,
            reflection: {},
            goals: [],
            studyPlan: {},
            insights: {}
        };
        this.reviewTimer = null;
        this.timerMinutes = 10;
        
        this.init();
    }
    
    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.bindEvents();
            this.loadYesterdayData();
            this.startReviewTimer();
            this.loadDailyQuote();
            this.initializeWeekChart();
        });
    }
    
    bindEvents() {
        // Step navigation
        document.addEventListener('click', (e) => {
            if (e.target.matches('.next-step-btn')) {
                const nextStep = parseInt(e.target.dataset.next);
                this.goToStep(nextStep);
            }
            
            if (e.target.matches('.prev-step-btn')) {
                const prevStep = parseInt(e.target.dataset.prev);
                this.goToStep(prevStep);
            }
            
            if (e.target.matches('.complete-review-btn')) {
                this.completeReview();
            }
        });
        
        // Mood selection
        document.addEventListener('click', (e) => {
            if (e.target.closest('.mood-option')) {
                this.selectMood(e.target.closest('.mood-option'));
            }
        });
        
        // Auto-save inputs
        document.addEventListener('input', (e) => {
            if (e.target.matches('textarea, input[type="text"], select')) {
                this.saveInputData();
            }
        });
        
        // Add more goals
        document.addEventListener('click', (e) => {
            if (e.target.matches('.add-goal-btn')) {
                this.addGoalInput();
            }
        });
    }
    
    async loadYesterdayData() {
        try {
            const yesterday = new Date();
            yesterday.setDate(yesterday.getDate() - 1);
            const dateStr = yesterday.toISOString().split('T')[0];
            
            const response = await fetch(`api/daily-review/get-yesterday-stats.php?date=${dateStr}`);
            const data = await response.json();
            
            if (data.success) {
                this.displayYesterdayStats(data.stats);
                this.displayYesterdayActivities(data.activities);
            }
        } catch (error) {
            console.error('Error loading yesterday data:', error);
        }
    }
    
    displayYesterdayStats(stats) {
        document.getElementById('yesterday-study-time').textContent = this.formatTime(stats.study_time || 0);
        document.getElementById('yesterday-tasks-completed').textContent = stats.tasks_completed || 0;
        document.getElementById('yesterday-streak').textContent = stats.streak || 0;
        document.getElementById('yesterday-points').textContent = stats.points_earned || 0;
    }
    
    displayYesterdayActivities(activities) {
        const activitiesList = document.getElementById('yesterday-activities-list');
        
        if (!activities || activities.length === 0) {
            activitiesList.innerHTML = `
                <div class="no-activities">
                    <i class="fas fa-calendar-times"></i>
                    <p>No recorded activities yesterday</p>
                    <p class="text-muted">Start tracking your study sessions today!</p>
                </div>
            `;
            return;
        }
        
        activitiesList.innerHTML = activities.map(activity => `
            <div class="activity-item ${activity.type}">
                <div class="activity-time">${this.formatTime12Hour(activity.time)}</div>
                <div class="activity-content">
                    <div class="activity-title">${activity.title}</div>
                    <div class="activity-details">${activity.details}</div>
                </div>
                <div class="activity-points">+${activity.points} XP</div>
            </div>
        `).join('');
    }
    
    goToStep(stepNumber) {
        if (stepNumber < 1 || stepNumber > this.totalSteps) return;
        
        // Validate current step before proceeding
        if (stepNumber > this.currentStep && !this.validateCurrentStep()) {
            return;
        }
        
        // Hide current step
        document.querySelector(`#step-${this.currentStep}`).classList.remove('active');
        document.querySelector(`.step[data-step="${this.currentStep}"]`).classList.remove('active');
        
        // Show new step
        this.currentStep = stepNumber;
        document.querySelector(`#step-${this.currentStep}`).classList.add('active');
        document.querySelector(`.step[data-step="${this.currentStep}"]`).classList.add('active');
        
        // Update progress bar
        this.updateProgressBar();
        
        // Special handling for certain steps
        if (this.currentStep === 4) {
            this.loadWeekProgress();
        }
        
        // Auto-save progress
        this.saveReviewProgress();
    }
    
    validateCurrentStep() {
        switch (this.currentStep) {
            case 1:
                return true; // No validation needed for review step
                
            case 2:
                if (!this.reviewData.mood) {
                    this.showNotification('Please select your mood to continue', 'warning');
                    return false;
                }
                return true;
                
            case 3:
                const goals = this.collectGoals();
                if (goals.length === 0) {
                    this.showNotification('Please set at least one goal for tomorrow', 'warning');
                    return false;
                }
                return true;
                
            default:
                return true;
        }
    }
    
    updateProgressBar() {
        const progressPercent = (this.currentStep / this.totalSteps) * 100;
        document.querySelector('.progress-fill').style.width = progressPercent + '%';
    }
    
    selectMood(moodElement) {
        // Remove previous selection
        document.querySelectorAll('.mood-option').forEach(option => {
            option.classList.remove('selected');
        });
        
        // Select new mood
        moodElement.classList.add('selected');
        this.reviewData.mood = moodElement.dataset.mood;
        
        // Show mood-specific encouragement
        this.showMoodEncouragement(this.reviewData.mood);
    }
    
    showMoodEncouragement(mood) {
        const messages = {
            excellent: "Fantastic! You're in great spirits. Let's make the most of this positive energy!",
            good: "Great! You're feeling good and ready to tackle your goals.",
            okay: "That's perfectly fine. Sometimes okay days lead to great achievements.",
            tired: "It's okay to feel tired. Let's plan a gentle but productive day ahead.",
            stressed: "I understand you're feeling stressed. Let's focus on manageable goals and self-care."
        };
        
        this.showNotification(messages[mood] || "Thanks for sharing how you're feeling!", 'info');
    }
    
    saveInputData() {
        // Save reflection data
        this.reviewData.reflection = {
            biggestWin: document.getElementById('biggest-win')?.value || '',
            biggestChallenge: document.getElementById('biggest-challenge')?.value || '',
            lessonsLearned: document.getElementById('lessons-learned')?.value || ''
        };
        
        // Save study plan
        this.reviewData.studyPlan = {
            focusSubject: document.getElementById('focus-subject')?.value || '',
            targetHours: document.getElementById('target-hours')?.value || 0,
            targetMinutes: document.getElementById('target-minutes')?.value || 0,
            studyMethod: document.getElementById('study-method')?.value || ''
        };
        
        // Save insights
        this.reviewData.insights = {
            improvementArea: document.getElementById('improvement-area')?.value || '',
            motivationReason: document.getElementById('motivation-reason')?.value || '',
            successReward: document.getElementById('success-reward')?.value || ''
        };
    }
    
    collectGoals() {
        const goals = [];
        const goalInputs = document.querySelectorAll('.goal-input');
        const prioritySelects = document.querySelectorAll('.priority-select');
        
        goalInputs.forEach((input, index) => {
            const goalText = input.value.trim();
            if (goalText) {
                goals.push({
                    text: goalText,
                    priority: prioritySelects[index]?.value || 'medium'
                });
            }
        });
        
        this.reviewData.goals = goals;
        return goals;
    }
    
    startReviewTimer() {
        this.startTime = new Date();
        let timeRemaining = this.timerMinutes * 60; // Convert to seconds
        
        this.reviewTimer = setInterval(() => {
            timeRemaining--;
            
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            
            document.getElementById('review-timer').textContent = 
                `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            // Color coding for time awareness
            const timerElement = document.getElementById('review-timer');
            if (timeRemaining <= 120) { // 2 minutes left
                timerElement.classList.add('warning');
            } else if (timeRemaining <= 60) { // 1 minute left
                timerElement.classList.add('urgent');
            }
            
            if (timeRemaining <= 0) {
                this.timeUp();
            }
        }, 1000);
    }
    
    timeUp() {
        clearInterval(this.reviewTimer);
        this.showTimeUpNotification();
    }
    
    showTimeUpNotification() {
        const notification = document.createElement('div');
        notification.className = 'time-up-notification';
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-clock"></i>
                <h3>10 Minutes Up!</h3>
                <p>You can continue at your own pace or complete the review now.</p>
                <div class="notification-actions">
                    <button class="btn btn-primary" onclick="this.parentElement.parentElement.parentElement.remove()">
                        Continue Review
                    </button>
                    <button class="btn btn-outline" onclick="window.dailyReviewManager.quickComplete()">
                        Quick Complete
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
    }
    
    quickComplete() {
        // Fill in minimal data and complete
        if (!this.reviewData.mood) {
            this.reviewData.mood = 'okay';
        }
        
        if (this.reviewData.goals.length === 0) {
            this.reviewData.goals = [{ text: 'Continue studying', priority: 'medium' }];
        }
        
        this.completeReview();
    }
    
    async loadDailyQuote() {
        try {
            const response = await fetch('api/daily-review/get-daily-quote.php');
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('daily-quote').textContent = data.quote.text;
                document.getElementById('quote-author').textContent = `— ${data.quote.author}`;
            }
        } catch (error) {
            console.error('Error loading daily quote:', error);
            // Fallback quote
            document.getElementById('daily-quote').textContent = 
                "The expert in anything was once a beginner.";
            document.getElementById('quote-author').textContent = "— Helen Hayes";
        }
    }
    
    initializeWeekChart() {
        // Initialize chart placeholder - will be populated when step 4 is reached
        const canvas = document.getElementById('week-progress-chart');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            // Draw placeholder
            ctx.fillStyle = '#f3f4f6';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = '#6b7280';
            ctx.font = '16px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('Loading weekly progress...', canvas.width / 2, canvas.height / 2);
        }
    }
    
    async loadWeekProgress() {
        try {
            const response = await fetch('api/daily-review/get-week-progress.php');
            const data = await response.json();
            
            if (data.success) {
                this.renderWeekChart(data.weekData);
            }
        } catch (error) {
            console.error('Error loading week progress:', error);
        }
    }
    
    renderWeekChart(weekData) {
        const canvas = document.getElementById('week-progress-chart');
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        const width = canvas.width;
        const height = canvas.height;
        
        // Clear canvas
        ctx.clearRect(0, 0, width, height);
        
        // Chart setup
        const padding = 40;
        const chartWidth = width - 2 * padding;
        const chartHeight = height - 2 * padding;
        const barWidth = chartWidth / 7;
        
        // Find max value for scaling
        const maxMinutes = Math.max(...weekData.map(day => day.study_minutes));
        const scale = maxMinutes > 0 ? chartHeight / maxMinutes : 1;
        
        // Draw bars
        weekData.forEach((day, index) => {
            const barHeight = day.study_minutes * scale;
            const x = padding + index * barWidth + barWidth * 0.1;
            const y = height - padding - barHeight;
            const barWidthActual = barWidth * 0.8;
            
            // Bar color based on performance
            const color = day.study_minutes >= 60 ? '#10b981' : 
                         day.study_minutes >= 30 ? '#f59e0b' : '#ef4444';
            
            ctx.fillStyle = color;
            ctx.fillRect(x, y, barWidthActual, barHeight);
            
            // Day label
            ctx.fillStyle = '#374151';
            ctx.font = '12px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(day.day_name.substr(0, 3), x + barWidthActual / 2, height - 10);
            
            // Time label
            const hours = Math.floor(day.study_minutes / 60);
            const minutes = day.study_minutes % 60;
            const timeLabel = hours > 0 ? `${hours}h${minutes}m` : `${minutes}m`;
            ctx.fillText(timeLabel, x + barWidthActual / 2, y - 5);
        });
        
        // Chart title
        ctx.fillStyle = '#111827';
        ctx.font = 'bold 14px sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('This Week\'s Study Time', width / 2, 20);
    }
    
    async completeReview() {
        // Collect all data
        this.saveInputData();
        this.collectGoals();
        
        const reviewDuration = this.startTime ? 
            Math.floor((new Date() - this.startTime) / 60000) : 10;
        
        // Prepare review data for saving
        const completeReviewData = {
            ...this.reviewData,
            reviewDuration: reviewDuration,
            completedAt: new Date().toISOString()
        };
        
        try {
            const response = await fetch('api/daily-review/save-review.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(completeReviewData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showCompletionModal(reviewDuration);
                this.awardReviewPoints();
                this.scheduleReminderForTomorrow();
            } else {
                this.showNotification('Failed to save review', 'error');
            }
        } catch (error) {
            console.error('Error saving review:', error);
            this.showNotification('Error saving review', 'error');
        }
    }
    
    showCompletionModal(duration) {
        const modal = document.getElementById('review-summary-modal');
        modal.style.display = 'block';
        
        // Update duration
        document.getElementById('review-duration').textContent = duration;
        
        // Create plan summary
        const planSummary = document.getElementById('plan-summary');
        planSummary.innerHTML = `
            <div class="summary-goals">
                <h4>Tomorrow's Goals:</h4>
                <ul>
                    ${this.reviewData.goals.map(goal => 
                        `<li class="priority-${goal.priority}">${goal.text}</li>`
                    ).join('')}
                </ul>
            </div>
            <div class="summary-study-plan">
                <h4>Study Plan:</h4>
                <p><strong>Focus:</strong> ${this.reviewData.studyPlan.focusSubject || 'Not specified'}</p>
                <p><strong>Target Time:</strong> ${this.reviewData.studyPlan.targetHours}h ${this.reviewData.studyPlan.targetMinutes}m</p>
                <p><strong>Method:</strong> ${this.reviewData.studyPlan.studyMethod || 'Not specified'}</p>
            </div>
        `;
        
        // Personal motivation
        document.getElementById('personal-motivation').textContent = 
            this.reviewData.insights.motivationReason || 'Keep pushing towards your goals!';
    }
    
    awardReviewPoints() {
        // Award points for completing daily review
        if (window.gamificationManager) {
            window.gamificationManager.awardPoints(50, 'review', 'Completed daily review');
        }
        
        // Trigger custom event
        window.dispatchEvent(new CustomEvent('dailyReviewComplete', {
            detail: { points: 50, duration: this.reviewData.reviewDuration }
        }));
    }
    
    scheduleReminderForTomorrow() {
        // Set reminder for tomorrow's review
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(19, 0, 0, 0); // 7 PM reminder
        
        const timeUntilReminder = tomorrow - new Date();
        
        if (timeUntilReminder > 0 && 'Notification' in window) {
            setTimeout(() => {
                if (Notification.permission === 'granted') {
                    new Notification('Daily Review Reminder', {
                        body: 'Time for your daily review! Reflect on today and plan tomorrow.',
                        icon: 'assets/images/app-icon.png'
                    });
                }
            }, timeUntilReminder);
        }
    }
    
    async saveReviewProgress() {
        // Auto-save progress
        try {
            await fetch('api/daily-review/save-progress.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    currentStep: this.currentStep,
                    reviewData: this.reviewData
                })
            });
        } catch (error) {
            console.error('Error saving review progress:', error);
        }
    }
    
    // Utility functions
    formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        
        if (hours > 0) {
            return `${hours}h ${minutes}m`;
        }
        return `${minutes}m`;
    }
    
    formatTime12Hour(timeString) {
        const time = new Date(`2000-01-01 ${timeString}`);
        return time.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }
    
    showNotification(message, type = 'info') {
        if (typeof showNotification === 'function') {
            showNotification(message, type);
        } else {
            console.log(message);
        }
    }
}

// Initialize daily review manager
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.daily-review-page')) {
        window.dailyReviewManager = new DailyReviewManager();
    }
});