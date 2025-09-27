// Gamification System
class GamificationManager {
    constructor() {
        this.userLevel = 1;
        this.currentXP = 0;
        this.xpToNextLevel = 100;
        this.totalPoints = 0;
        this.currentStreak = 0;
        this.longestStreak = 0;
        this.badges = [];
        this.recentActivities = [];
        
        this.init();
    }
    
    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.loadUserData();
            this.bindEvents();
            this.startStreakTracking();
        });
    }
    
    bindEvents() {
        // Listen for study activities to award points
        window.addEventListener('studySessionComplete', (e) => {
            this.awardStudyPoints(e.detail);
        });
        
        window.addEventListener('todoCompleted', (e) => {
            this.awardTaskPoints(e.detail);
        });
        
        window.addEventListener('quizCompleted', (e) => {
            this.awardQuizPoints(e.detail);
        });
        
        window.addEventListener('flashcardReviewed', (e) => {
            this.awardFlashcardPoints(e.detail);
        });
        
        // Profile and stats buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('.view-profile-btn')) {
                this.showUserProfile();
            }
            
            if (e.target.matches('.view-leaderboard-btn')) {
                this.showLeaderboard();
            }
            
            if (e.target.matches('.view-badges-btn')) {
                this.showBadgeCollection();
            }
        });
    }
    
    async loadUserData() {
        try {
            const response = await fetch('api/gamification/get-user-stats.php');
            const data = await response.json();
            
            if (data.success) {
                this.userLevel = data.stats.level;
                this.currentXP = data.stats.current_xp;
                this.xpToNextLevel = data.stats.xp_to_next_level;
                this.totalPoints = data.stats.total_points;
                this.currentStreak = data.stats.current_streak;
                this.longestStreak = data.stats.longest_streak;
                this.badges = data.stats.badges;
                this.recentActivities = data.stats.recent_activities;
                
                this.updateUI();
            }
        } catch (error) {
            console.error('Error loading user gamification data:', error);
        }
    }
    
    updateUI() {
        this.updateLevelDisplay();
        this.updatePointsDisplay();
        this.updateStreakDisplay();
        this.updateProgressBar();
        this.renderRecentActivities();
        this.renderBadges();
    }
    
    updateLevelDisplay() {
        const levelElements = document.querySelectorAll('.user-level');
        levelElements.forEach(element => {
            element.textContent = this.userLevel;
        });
    }
    
    updatePointsDisplay() {
        const pointsElements = document.querySelectorAll('.user-points');
        pointsElements.forEach(element => {
            element.textContent = this.formatNumber(this.totalPoints);
        });
        
        const xpElements = document.querySelectorAll('.current-xp');
        xpElements.forEach(element => {
            element.textContent = this.currentXP;
        });
        
        const nextLevelXpElements = document.querySelectorAll('.next-level-xp');
        nextLevelXpElements.forEach(element => {
            element.textContent = this.xpToNextLevel;
        });
    }
    
    updateStreakDisplay() {
        const streakElements = document.querySelectorAll('.current-streak');
        streakElements.forEach(element => {
            element.textContent = this.currentStreak;
        });
        
        const longestStreakElements = document.querySelectorAll('.longest-streak');
        longestStreakElements.forEach(element => {
            element.textContent = this.longestStreak;
        });
    }
    
    updateProgressBar() {
        const progressBars = document.querySelectorAll('.xp-progress-bar');
        progressBars.forEach(bar => {
            const progressFill = bar.querySelector('.progress-fill');
            if (progressFill) {
                const progressPercent = (this.currentXP / this.xpToNextLevel) * 100;
                progressFill.style.width = progressPercent + '%';
            }
        });
    }
    
    renderRecentActivities() {
        const activitiesList = document.getElementById('recent-activities-list');
        if (!activitiesList) return;
        
        if (this.recentActivities.length === 0) {
            activitiesList.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-history"></i>
                    <p>No recent activities</p>
                    <p class="text-muted">Start studying to see your progress here!</p>
                </div>
            `;
            return;
        }
        
        activitiesList.innerHTML = this.recentActivities.map(activity => `
            <div class="activity-item ${activity.type}">
                <div class="activity-icon">
                    <i class="fas fa-${this.getActivityIcon(activity.type)}"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">${activity.title}</div>
                    <div class="activity-description">${activity.description}</div>
                    <div class="activity-meta">
                        <span class="activity-points">+${activity.points} XP</span>
                        <span class="activity-time">${this.getRelativeTime(activity.created_at)}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    renderBadges() {
        const badgesList = document.getElementById('user-badges-list');
        if (!badgesList) return;
        
        if (this.badges.length === 0) {
            badgesList.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-medal"></i>
                    <p>No badges earned yet</p>
                    <p class="text-muted">Complete achievements to earn badges!</p>
                </div>
            `;
            return;
        }
        
        badgesList.innerHTML = this.badges.map(badge => `
            <div class="badge-item ${badge.rarity}">
                <div class="badge-icon">
                    <i class="fas fa-${badge.icon}"></i>
                </div>
                <div class="badge-info">
                    <h5>${badge.name}</h5>
                    <p>${badge.description}</p>
                    <span class="badge-date">Earned ${this.formatDate(badge.earned_at)}</span>
                </div>
            </div>
        `).join('');
    }
    
    async awardStudyPoints(sessionData) {
        const basePoints = 10;
        const timeBonus = Math.floor(sessionData.duration / 60) * 2; // 2 points per minute
        const totalPoints = basePoints + timeBonus;
        
        await this.awardPoints(totalPoints, 'study', `Studied ${sessionData.subject} for ${this.formatDuration(sessionData.duration)}`);
    }
    
    async awardTaskPoints(taskData) {
        const points = this.getTaskPoints(taskData.priority);
        await this.awardPoints(points, 'task', `Completed task: ${taskData.task}`);
    }
    
    async awardQuizPoints(quizData) {
        const basePoints = 20;
        const accuracyBonus = Math.floor(quizData.accuracy * 0.3); // Up to 30 bonus points for 100% accuracy
        const totalPoints = basePoints + accuracyBonus;
        
        await this.awardPoints(totalPoints, 'quiz', `Completed quiz with ${quizData.accuracy}% accuracy`);
    }
    
    async awardFlashcardPoints(flashcardData) {
        const basePoints = 5;
        const difficultyBonus = this.getDifficultyBonus(flashcardData.difficulty);
        const totalPoints = basePoints + difficultyBonus;
        
        await this.awardPoints(totalPoints, 'flashcard', `Reviewed ${flashcardData.count} flashcards`);
    }
    
    async awardPoints(points, type, description) {
        try {
            const response = await fetch('api/gamification/award-points.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    points: points,
                    type: type,
                    description: description
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.currentXP += points;
                this.totalPoints += points;
                
                // Check for level up
                if (this.currentXP >= this.xpToNextLevel) {
                    this.levelUp();
                }
                
                // Show points notification
                this.showPointsNotification(points, description);
                
                // Update UI
                this.updateUI();
                
                // Add to recent activities
                this.recentActivities.unshift({
                    title: this.getActivityTitle(type),
                    description: description,
                    points: points,
                    type: type,
                    created_at: new Date().toISOString()
                });
                
                // Keep only last 10 activities
                this.recentActivities = this.recentActivities.slice(0, 10);
                
                // Update achievements
                this.checkNewAchievements();
            }
        } catch (error) {
            console.error('Error awarding points:', error);
        }
    }
    
    levelUp() {
        const oldLevel = this.userLevel;
        
        // Calculate new level
        this.userLevel++;
        this.currentXP -= this.xpToNextLevel;
        this.xpToNextLevel = this.calculateXPForLevel(this.userLevel + 1);
        
        // Show level up animation
        this.showLevelUpNotification(oldLevel, this.userLevel);
        
        // Award level up bonus
        const levelUpBonus = this.userLevel * 50;
        this.totalPoints += levelUpBonus;
        
        // Play level up sound
        this.playSound('levelup');
        
        // Save level up to database
        this.saveLevelUp();
        
        // Check for level-based achievements
        this.checkLevelAchievements();
    }
    
    showPointsNotification(points, description) {
        const notification = document.createElement('div');
        notification.className = 'points-notification';
        notification.innerHTML = `
            <div class="points-animation">
                <div class="points-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="points-amount">+${points} XP</div>
                <div class="points-description">${description}</div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => notification.classList.add('show'), 100);
        
        // Auto-hide after 3 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 500);
        }, 3000);
    }
    
    showLevelUpNotification(oldLevel, newLevel) {
        const notification = document.createElement('div');
        notification.className = 'levelup-notification';
        notification.innerHTML = `
            <div class="levelup-animation">
                <div class="levelup-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h2>Level Up!</h2>
                <div class="level-change">
                    <span class="old-level">Level ${oldLevel}</span>
                    <i class="fas fa-arrow-right"></i>
                    <span class="new-level">Level ${newLevel}</span>
                </div>
                <p>You've reached a new level! Keep up the great work!</p>
                <button class="btn btn-primary" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-trophy"></i> Awesome!
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => notification.classList.add('show'), 100);
        
        // Auto-hide after 8 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 8000);
    }
    
    startStreakTracking() {
        // Check streak daily
        const now = new Date();
        const lastStudyDate = localStorage.getItem('lastStudyDate');
        
        if (lastStudyDate) {
            const lastDate = new Date(lastStudyDate);
            const daysDiff = Math.floor((now - lastDate) / (1000 * 60 * 60 * 24));
            
            if (daysDiff === 1) {
                // Continued streak
                this.currentStreak++;
                this.updateStreak();
            } else if (daysDiff > 1) {
                // Streak broken
                this.currentStreak = 0;
                this.updateStreak();
            }
        }
        
        // Listen for study activities to update streak
        window.addEventListener('studySessionComplete', () => {
            this.updateDailyStreak();
        });
    }
    
    updateDailyStreak() {
        const today = new Date().toDateString();
        const lastStudyDate = localStorage.getItem('lastStudyDate');
        
        if (lastStudyDate !== today) {
            localStorage.setItem('lastStudyDate', today);
            
            if (!lastStudyDate || this.isConsecutiveDay(lastStudyDate, today)) {
                this.currentStreak++;
                
                if (this.currentStreak > this.longestStreak) {
                    this.longestStreak = this.currentStreak;
                }
                
                this.updateStreak();
                this.checkStreakAchievements();
            }
        }
    }
    
    isConsecutiveDay(lastDate, currentDate) {
        const last = new Date(lastDate);
        const current = new Date(currentDate);
        const diffTime = current - last;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays === 1;
    }
    
    async updateStreak() {
        try {
            await fetch('api/gamification/update-streak.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    current_streak: this.currentStreak,
                    longest_streak: this.longestStreak
                })
            });
            
            this.updateStreakDisplay();
        } catch (error) {
            console.error('Error updating streak:', error);
        }
    }
    
    async showUserProfile() {
        const modal = document.createElement('div');
        modal.className = 'user-profile-modal modal-overlay';
        modal.innerHTML = `
            <div class="modal-content profile-content">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <img src="assets/images/default-avatar.png" alt="User Avatar">
                        <div class="level-badge">
                            <span>Level ${this.userLevel}</span>
                        </div>
                    </div>
                    <div class="profile-info">
                        <h2>Study Warrior</h2>
                        <div class="profile-stats">
                            <div class="stat">
                                <span class="stat-value">${this.formatNumber(this.totalPoints)}</span>
                                <span class="stat-label">Total XP</span>
                            </div>
                            <div class="stat">
                                <span class="stat-value">${this.currentStreak}</span>
                                <span class="stat-label">Current Streak</span>
                            </div>
                            <div class="stat">
                                <span class="stat-value">${this.badges.length}</span>
                                <span class="stat-label">Badges Earned</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="profile-progress">
                    <h3>Progress to Next Level</h3>
                    <div class="xp-progress-bar">
                        <div class="progress-fill" style="width: ${(this.currentXP / this.xpToNextLevel) * 100}%"></div>
                    </div>
                    <div class="progress-text">${this.currentXP} / ${this.xpToNextLevel} XP</div>
                </div>
                
                <div class="profile-badges">
                    <h3>Recent Badges</h3>
                    <div class="badges-showcase">
                        ${this.badges.slice(0, 6).map(badge => `
                            <div class="badge-showcase-item ${badge.rarity}">
                                <i class="fas fa-${badge.icon}"></i>
                                <span>${badge.name}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <button class="close-modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(modal);
        setTimeout(() => modal.classList.add('show'), 10);
        
        // Close modal events
        modal.addEventListener('click', (e) => {
            if (e.target === modal || e.target.classList.contains('close-modal')) {
                modal.classList.remove('show');
                setTimeout(() => modal.remove(), 300);
            }
        });
    }
    
    // Utility functions
    getTaskPoints(priority) {
        const pointsMap = {
            'high': 15,
            'medium': 10,
            'low': 5
        };
        return pointsMap[priority] || 5;
    }
    
    getDifficultyBonus(difficulty) {
        const bonusMap = {
            'hard': 5,
            'medium': 3,
            'easy': 1
        };
        return bonusMap[difficulty] || 1;
    }
    
    calculateXPForLevel(level) {
        return Math.floor(100 * Math.pow(1.2, level - 1));
    }
    
    getActivityIcon(type) {
        const iconMap = {
            'study': 'clock',
            'task': 'check',
            'quiz': 'question-circle',
            'flashcard': 'layer-group',
            'achievement': 'trophy',
            'levelup': 'star'
        };
        return iconMap[type] || 'star';
    }
    
    getActivityTitle(type) {
        const titleMap = {
            'study': 'Study Session',
            'task': 'Task Completed',
            'quiz': 'Quiz Completed',
            'flashcard': 'Flashcards Reviewed',
            'achievement': 'Achievement Unlocked',
            'levelup': 'Level Up'
        };
        return titleMap[type] || 'Activity';
    }
    
    formatNumber(num) {
        if (num >= 1000000) {
            return (num / 1000000).toFixed(1) + 'M';
        } else if (num >= 1000) {
            return (num / 1000).toFixed(1) + 'K';
        }
        return num.toString();
    }
    
    formatDuration(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        
        if (hours > 0) {
            return `${hours}h ${minutes}m`;
        }
        return `${minutes}m`;
    }
    
    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString();
    }
    
    getRelativeTime(timestamp) {
        const now = new Date();
        const time = new Date(timestamp);
        const diff = Math.floor((now - time) / 1000);
        
        if (diff < 60) return 'just now';
        if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
        if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
        return Math.floor(diff / 86400) + 'd ago';
    }
    
    playSound(type) {
        try {
            const audio = new Audio(`assets/sounds/${type}.mp3`);
            audio.volume = 0.3;
            audio.play().catch(() => {
                // Silently fail if sound can't play
            });
        } catch (error) {
            // Sound not available
        }
    }
    
    async checkNewAchievements() {
        if (window.achievementsManager) {
            window.achievementsManager.checkAchievements();
        }
    }
    
    async checkStreakAchievements() {
        // Check streak milestones
        const streakMilestones = [7, 14, 30, 60, 100];
        
        if (streakMilestones.includes(this.currentStreak)) {
            // Award streak achievement
            await this.awardPoints(this.currentStreak * 10, 'streak', `${this.currentStreak} day study streak!`);
        }
    }
    
    async checkLevelAchievements() {
        // Check level milestones
        const levelMilestones = [5, 10, 25, 50, 100];
        
        if (levelMilestones.includes(this.userLevel)) {
            // Award level achievement
            await this.awardPoints(this.userLevel * 20, 'milestone', `Reached level ${this.userLevel}!`);
        }
    }
    
    async saveLevelUp() {
        try {
            await fetch('api/gamification/level-up.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    new_level: this.userLevel,
                    current_xp: this.currentXP,
                    xp_to_next_level: this.xpToNextLevel
                })
            });
        } catch (error) {
            console.error('Error saving level up:', error);
        }
    }
}

// Global functions
function showUserProfile() {
    if (window.gamificationManager) {
        window.gamificationManager.showUserProfile();
    }
}

function triggerStudyComplete(sessionData) {
    window.dispatchEvent(new CustomEvent('studySessionComplete', { 
        detail: sessionData 
    }));
}

function triggerTodoComplete(taskData) {
    window.dispatchEvent(new CustomEvent('todoCompleted', { 
        detail: taskData 
    }));
}

function triggerQuizComplete(quizData) {
    window.dispatchEvent(new CustomEvent('quizCompleted', { 
        detail: quizData 
    }));
}

function triggerFlashcardReview(flashcardData) {
    window.dispatchEvent(new CustomEvent('flashcardReviewed', { 
        detail: flashcardData 
    }));
}

// Initialize gamification manager
document.addEventListener('DOMContentLoaded', () => {
    window.gamificationManager = new GamificationManager();
});