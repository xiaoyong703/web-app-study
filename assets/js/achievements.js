// Achievements and Gamification System
class AchievementsManager {
    constructor() {
        this.achievements = [];
        this.userAchievements = [];
        this.currentLevel = 1;
        this.currentXP = 0;
        this.xpToNextLevel = 100;
        
        this.init();
    }
    
    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.bindEvents();
            this.loadAchievements();
            this.loadUserProgress();
        });
    }
    
    bindEvents() {
        // Category filter buttons
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
                this.filterAchievements(e.target.dataset.category);
            });
        });
        
        // Achievement detail modal
        document.addEventListener('click', (e) => {
            if (e.target.closest('.achievement-card')) {
                const achievementId = e.target.closest('.achievement-card').dataset.id;
                this.showAchievementDetail(achievementId);
            }
        });
        
        // Close modal
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay') || e.target.classList.contains('close-modal')) {
                this.closeModal();
            }
        });
        
        // Escape key to close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal();
            }
        });
    }
    
    async loadAchievements() {
        try {
            const response = await fetch('api/achievements/get-achievements.php');
            const data = await response.json();
            
            if (data.success) {
                this.achievements = data.achievements;
                this.renderAchievements();
            }
        } catch (error) {
            console.error('Error loading achievements:', error);
        }
    }
    
    async loadUserProgress() {
        try {
            const response = await fetch('api/achievements/get-user-progress.php');
            const data = await response.json();
            
            if (data.success) {
                this.userAchievements = data.userAchievements;
                this.currentLevel = data.currentLevel;
                this.currentXP = data.currentXP;
                this.xpToNextLevel = data.xpToNextLevel;
                
                this.updateLevelDisplay();
                this.updateAchievementCards();
            }
        } catch (error) {
            console.error('Error loading user progress:', error);
        }
    }
    
    renderAchievements() {
        const achievementCategories = document.querySelectorAll('.achievement-category');
        
        achievementCategories.forEach(category => {
            const categoryType = category.dataset.category;
            const grid = category.querySelector('.achievements-grid');
            
            const categoryAchievements = this.achievements.filter(a => 
                a.category === categoryType || categoryType === 'all'
            );
            
            grid.innerHTML = categoryAchievements.map(achievement => 
                this.createAchievementCard(achievement)
            ).join('');
        });
    }
    
    createAchievementCard(achievement) {
        const userAchievement = this.userAchievements.find(ua => ua.achievement_id == achievement.id);
        const isUnlocked = userAchievement && userAchievement.unlocked;
        const progress = userAchievement ? userAchievement.progress : 0;
        const progressPercent = Math.min(100, (progress / achievement.target) * 100);
        
        return `
            <div class="achievement-card ${isUnlocked ? 'unlocked' : ''}" data-id="${achievement.id}">
                <div class="achievement-icon">
                    <i class="${achievement.icon}"></i>
                    ${isUnlocked ? '<div class="unlock-badge">✓</div>' : ''}
                </div>
                <div class="achievement-info">
                    <h4>${achievement.title}</h4>
                    <p>${achievement.description}</p>
                    <div class="achievement-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: ${progressPercent}%"></div>
                        </div>
                        <span class="progress-text">${progress}/${achievement.target}</span>
                    </div>
                    <div class="achievement-meta">
                        <span class="achievement-points">+${achievement.points} XP</span>
                        <span class="achievement-rarity ${achievement.rarity}">${achievement.rarity}</span>
                    </div>
                </div>
            </div>
        `;
    }
    
    updateAchievementCards() {
        document.querySelectorAll('.achievement-card').forEach(card => {
            const achievementId = card.dataset.id;
            const achievement = this.achievements.find(a => a.id == achievementId);
            const userAchievement = this.userAchievements.find(ua => ua.achievement_id == achievementId);
            
            if (userAchievement && userAchievement.unlocked) {
                card.classList.add('unlocked');
                
                const icon = card.querySelector('.achievement-icon');
                if (!icon.querySelector('.unlock-badge')) {
                    icon.innerHTML += '<div class="unlock-badge">✓</div>';
                }
            }
            
            // Update progress
            const progress = userAchievement ? userAchievement.progress : 0;
            const progressPercent = Math.min(100, (progress / achievement.target) * 100);
            
            const progressFill = card.querySelector('.progress-fill');
            const progressText = card.querySelector('.progress-text');
            
            if (progressFill) progressFill.style.width = progressPercent + '%';
            if (progressText) progressText.textContent = `${progress}/${achievement.target}`;
        });
    }
    
    updateLevelDisplay() {
        // Update level number
        document.getElementById('current-level').textContent = this.currentLevel;
        
        // Update XP progress
        document.getElementById('current-xp').textContent = this.currentXP;
        document.getElementById('next-level-xp').textContent = this.xpToNextLevel;
        
        // Update progress bar
        const progressPercent = (this.currentXP / this.xpToNextLevel) * 100;
        document.querySelector('.level-progress .progress-fill').style.width = progressPercent + '%';
    }
    
    filterAchievements(category) {
        document.querySelectorAll('.achievement-category').forEach(cat => {
            cat.style.display = cat.dataset.category === category || category === 'all' ? 'block' : 'none';
        });
    }
    
    showAchievementDetail(achievementId) {
        const achievement = this.achievements.find(a => a.id == achievementId);
        const userAchievement = this.userAchievements.find(ua => ua.achievement_id == achievementId);
        
        if (!achievement) return;
        
        const isUnlocked = userAchievement && userAchievement.unlocked;
        const progress = userAchievement ? userAchievement.progress : 0;
        const progressPercent = Math.min(100, (progress / achievement.target) * 100);
        
        const modalContent = `
            <div class="achievement-detail-modal">
                <div class="modal-header">
                    <div class="achievement-icon-large ${isUnlocked ? 'unlocked' : ''}">
                        <i class="${achievement.icon}"></i>
                        ${isUnlocked ? '<div class="unlock-badge">✓</div>' : ''}
                    </div>
                    <h2>${achievement.title}</h2>
                    <span class="achievement-rarity ${achievement.rarity}">${achievement.rarity}</span>
                </div>
                
                <div class="modal-body">
                    <p class="achievement-description">${achievement.description}</p>
                    
                    <div class="achievement-progress-detail">
                        <div class="progress-section">
                            <h4>Progress</h4>
                            <div class="progress-bar-large">
                                <div class="progress-fill" style="width: ${progressPercent}%"></div>
                            </div>
                            <div class="progress-stats">
                                <span>${progress} / ${achievement.target}</span>
                                <span>${Math.round(progressPercent)}% Complete</span>
                            </div>
                        </div>
                        
                        <div class="achievement-rewards">
                            <h4>Rewards</h4>
                            <div class="reward-item">
                                <i class="fas fa-star"></i>
                                <span>+${achievement.points} XP</span>
                            </div>
                            ${achievement.badge ? `
                                <div class="reward-item">
                                    <i class="fas fa-medal"></i>
                                    <span>${achievement.badge} Badge</span>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    <div class="achievement-tips">
                        <h4>Tips</h4>
                        <p>${achievement.tips || 'Keep working towards your goal!'}</p>
                    </div>
                    
                    ${isUnlocked ? `
                        <div class="achievement-unlock-date">
                            <i class="fas fa-calendar"></i>
                            <span>Unlocked on ${new Date(userAchievement.unlocked_at).toLocaleDateString()}</span>
                        </div>
                    ` : ''}
                </div>
                
                <button class="close-modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        this.showModal(modalContent);
    }
    
    showModal(content) {
        const existingModal = document.querySelector('.modal-overlay');
        if (existingModal) {
            existingModal.remove();
        }
        
        const modal = document.createElement('div');
        modal.className = 'modal-overlay achievements-modal';
        modal.innerHTML = content;
        
        document.body.appendChild(modal);
        
        // Animate in
        setTimeout(() => modal.classList.add('show'), 10);
    }
    
    closeModal() {
        const modal = document.querySelector('.modal-overlay');
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => modal.remove(), 300);
        }
    }
    
    // Achievement unlock animation
    async unlockAchievement(achievementId) {
        const achievement = this.achievements.find(a => a.id == achievementId);
        if (!achievement) return;
        
        // Create unlock notification
        const notification = document.createElement('div');
        notification.className = 'achievement-unlock-notification';
        notification.innerHTML = `
            <div class="unlock-animation">
                <div class="unlock-icon">
                    <i class="${achievement.icon}"></i>
                </div>
                <div class="unlock-content">
                    <h3>Achievement Unlocked!</h3>
                    <h4>${achievement.title}</h4>
                    <p>+${achievement.points} XP</p>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => notification.classList.add('show'), 100);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 500);
        }, 5000);
        
        // Play achievement sound (if available)
        this.playAchievementSound();
        
        // Update local data
        this.loadUserProgress();
    }
    
    playAchievementSound() {
        try {
            const audio = new Audio('assets/sounds/achievement.mp3');
            audio.volume = 0.3;
            audio.play().catch(() => {
                // Silently fail if sound can't play
            });
        } catch (error) {
            // Sound not available
        }
    }
    
    // Check for new achievements
    async checkAchievements() {
        try {
            const response = await fetch('api/achievements/check-achievements.php', {
                method: 'POST'
            });
            const data = await response.json();
            
            if (data.success && data.newAchievements) {
                // Show unlock animations for new achievements
                data.newAchievements.forEach(achievement => {
                    setTimeout(() => {
                        this.unlockAchievement(achievement.id);
                    }, 1000);
                });
            }
        } catch (error) {
            console.error('Error checking achievements:', error);
        }
    }
    
    // Get achievement stats
    getAchievementStats() {
        const totalAchievements = this.achievements.length;
        const unlockedAchievements = this.userAchievements.filter(ua => ua.unlocked).length;
        const completionRate = Math.round((unlockedAchievements / totalAchievements) * 100);
        
        return {
            total: totalAchievements,
            unlocked: unlockedAchievements,
            completionRate: completionRate
        };
    }
    
    // Update achievement stats in UI
    updateStatsDisplay() {
        const stats = this.getAchievementStats();
        
        const statsElements = {
            total: document.getElementById('total-achievements'),
            unlocked: document.getElementById('unlocked-achievements'),
            completion: document.getElementById('completion-rate')
        };
        
        if (statsElements.total) statsElements.total.textContent = stats.total;
        if (statsElements.unlocked) statsElements.unlocked.textContent = stats.unlocked;
        if (statsElements.completion) statsElements.completion.textContent = stats.completionRate + '%';
    }
}

// Leaderboard functionality
class LeaderboardManager {
    constructor() {
        this.leaderboardData = [];
        this.currentPeriod = 'week';
        this.init();
    }
    
    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.bindLeaderboardEvents();
            this.loadLeaderboard();
        });
    }
    
    bindLeaderboardEvents() {
        // Period selector
        document.querySelectorAll('.leaderboard-period-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                document.querySelectorAll('.leaderboard-period-btn').forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
                this.currentPeriod = e.target.dataset.period;
                this.loadLeaderboard();
            });
        });
    }
    
    async loadLeaderboard() {
        try {
            const response = await fetch(`api/achievements/get-leaderboard.php?period=${this.currentPeriod}`);
            const data = await response.json();
            
            if (data.success) {
                this.leaderboardData = data.leaderboard;
                this.renderLeaderboard();
            }
        } catch (error) {
            console.error('Error loading leaderboard:', error);
        }
    }
    
    renderLeaderboard() {
        const leaderboardList = document.getElementById('leaderboard-list');
        if (!leaderboardList) return;
        
        leaderboardList.innerHTML = this.leaderboardData.map((user, index) => `
            <div class="leaderboard-item ${index < 3 ? 'top-three' : ''} ${user.is_current_user ? 'current-user' : ''}">
                <div class="rank">
                    ${index < 3 ? this.getRankIcon(index) : `#${index + 1}`}
                </div>
                <div class="user-info">
                    <div class="user-avatar">
                        <img src="${user.avatar || 'assets/images/default-avatar.png'}" alt="${user.username}">
                    </div>
                    <div class="user-details">
                        <span class="username">${user.username}</span>
                        <span class="user-level">Level ${user.level}</span>
                    </div>
                </div>
                <div class="user-stats">
                    <span class="xp">${user.total_xp} XP</span>
                    <span class="achievements">${user.achievement_count} achievements</span>
                </div>
            </div>
        `).join('');
    }
    
    getRankIcon(index) {
        const icons = ['🥇', '🥈', '🥉'];
        return icons[index] || `#${index + 1}`;
    }
}

// Global functions
function showAllAchievements() {
    window.achievementsManager.filterAchievements('all');
}

// Initialize managers when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.achievementsManager = new AchievementsManager();
    window.leaderboardManager = new LeaderboardManager();
    
    // Check for achievements every 30 seconds
    setInterval(() => {
        if (window.achievementsManager) {
            window.achievementsManager.checkAchievements();
        }
    }, 30000);
});