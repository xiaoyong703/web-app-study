// Statistics management
class StatsManager {
    constructor() {
        this.statsGrid = document.querySelector('.stats-grid');
        this.progressFill = document.querySelector('.progress-fill');
        this.progressText = document.querySelector('.progress-text');
        
        this.init();
    }
    
    init() {
        this.refresh();
        // Refresh stats every 30 seconds
        setInterval(() => this.refresh(), 30000);
    }
    
    async refresh() {
        try {
            const response = await fetch('api/get-stats.php');
            const stats = await response.json();
            
            if (stats.success) {
                this.updateStatsDisplay(stats.data);
            }
        } catch (error) {
            console.error('Error fetching stats:', error);
        }
    }
    
    updateStatsDisplay(data) {
        // Update stat values
        const statItems = this.statsGrid.querySelectorAll('.stat-item');
        
        if (statItems[0]) {
            const todayTimeValue = statItems[0].querySelector('.stat-value');
            todayTimeValue.textContent = this.formatTime(data.today.total_time || 0);
        }
        
        if (statItems[1]) {
            const todaySessionsValue = statItems[1].querySelector('.stat-value');
            todaySessionsValue.textContent = data.today.sessions_count || 0;
        }
        
        if (statItems[2]) {
            const weekTimeValue = statItems[2].querySelector('.stat-value');
            weekTimeValue.textContent = this.formatTime(data.week.total_time || 0);
        }
        
        if (statItems[3]) {
            const weekSessionsValue = statItems[3].querySelector('.stat-value');
            weekSessionsValue.textContent = data.week.sessions_count || 0;
        }
        
        // Update progress bar
        const weekTime = data.week.total_time || 0;
        const weekGoal = 10 * 3600; // 10 hours in seconds
        const progressPercentage = Math.min(100, (weekTime / weekGoal) * 100);
        
        if (this.progressFill) {
            this.progressFill.style.width = `${progressPercentage}%`;
        }
        
        if (this.progressText) {
            this.progressText.textContent = `${this.formatTime(weekTime)} / 10:00:00 hours this week`;
        }
    }
    
    formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const remainingSeconds = seconds % 60;
        
        if (hours > 0) {
            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        }
        return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    }
    
    async getDetailedStats(period = 'week') {
        try {
            const response = await fetch(`api/get-detailed-stats.php?period=${period}`);
            const result = await response.json();
            
            if (result.success) {
                return result.data;
            }
            return null;
        } catch (error) {
            console.error('Error fetching detailed stats:', error);
            return null;
        }
    }
}

// Global function to open statistics modal/page
function openStatistics() {
    // For now, just show an alert with basic stats
    if (window.statsManager) {
        window.statsManager.getDetailedStats().then(stats => {
            if (stats) {
                alert(`Detailed Statistics:\n\nTotal Study Time: ${window.statsManager.formatTime(stats.total_time)}\nTotal Sessions: ${stats.total_sessions}\nAverage Session: ${window.statsManager.formatTime(stats.avg_session)}\nMost Studied Subject: ${stats.top_subject || 'N/A'}`);
            }
        });
    }
}

// Initialize stats manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.statsManager = new StatsManager();
});