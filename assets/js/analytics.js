// Analytics functionality with Chart.js
class AnalyticsManager {
    constructor() {
        this.charts = {};
        this.currentRange = 'week';
        this.currentSubject = '';
        
        this.init();
    }
    
    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.bindEvents();
            this.loadAnalyticsData();
            this.initializeCharts();
        });
    }
    
    bindEvents() {
        // Time range selector
        document.querySelectorAll('.range-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                document.querySelectorAll('.range-btn').forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
                this.currentRange = e.target.dataset.range;
                this.updateAnalytics();
            });
        });
        
        // Subject filter
        const subjectFilter = document.getElementById('subject-filter-analytics');
        if (subjectFilter) {
            subjectFilter.addEventListener('change', (e) => {
                this.currentSubject = e.target.value;
                this.updateAnalytics();
            });
        }
        
        // Chart type buttons
        document.querySelectorAll('.chart-type-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const chartContainer = e.target.closest('.chart-card');
                const chartType = e.target.dataset.chart;
                const chartId = chartContainer.querySelector('canvas').id;
                
                // Update active button
                chartContainer.querySelectorAll('.chart-type-btn').forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
                
                this.updateChartType(chartId, chartType);
            });
        });
    }
    
    async loadAnalyticsData() {
        try {
            const response = await fetch(`api/analytics/get-analytics.php?range=${this.currentRange}&subject=${this.currentSubject}`);
            const data = await response.json();
            
            if (data.success) {
                this.updateKPIs(data.kpis);
                this.updateCharts(data.charts);
                this.updateInsights(data.insights);
            }
        } catch (error) {
            console.error('Error loading analytics data:', error);
        }
    }
    
    updateKPIs(kpis) {
        // Total study time
        document.getElementById('total-study-time').textContent = this.formatTime(kpis.totalTime || 0);
        document.getElementById('time-change').textContent = `${kpis.timeChange > 0 ? '+' : ''}${kpis.timeChange}% vs last period`;
        document.getElementById('time-change').className = `kpi-change ${kpis.timeChange >= 0 ? 'positive' : 'negative'}`;
        
        // Current streak
        document.getElementById('current-streak').textContent = kpis.currentStreak || 0;
        document.getElementById('streak-status').textContent = kpis.streakStatus || 'Keep it up!';
        
        // Average session
        document.getElementById('avg-session').textContent = this.formatTime(kpis.avgSession || 0);
        document.getElementById('session-change').textContent = kpis.sessionChange || 'No change';
        
        // Total points
        document.getElementById('total-points').textContent = kpis.totalPoints || 0;
        document.getElementById('points-change').textContent = `+${kpis.pointsThisPeriod || 0} this period`;
    }
    
    initializeCharts() {
        // Study Time Trends Chart
        const studyTimeCtx = document.getElementById('study-time-chart');
        if (studyTimeCtx) {
            this.charts.studyTime = new Chart(studyTimeCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Study Time (minutes)',
                        data: [],
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => this.formatTime(value * 60)
                            }
                        }
                    }
                }
            });
        }
        
        // Subject Distribution Chart
        const subjectCtx = document.getElementById('subject-distribution-chart');
        if (subjectCtx) {
            this.charts.subjectDistribution = new Chart(subjectCtx, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: [
                            '#6366f1', '#10b981', '#f59e0b', 
                            '#ef4444', '#8b5cf6', '#06b6d4'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
        
        // Quiz Performance Chart
        const quizCtx = document.getElementById('quiz-performance-chart');
        if (quizCtx) {
            this.charts.quizPerformance = new Chart(quizCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Quiz Score %',
                        data: [],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: (value) => value + '%'
                            }
                        }
                    }
                }
            });
        }
        
        // Activity Heatmap
        this.createActivityHeatmap();
    }
    
    updateCharts(chartData) {
        // Update study time chart
        if (this.charts.studyTime && chartData.studyTime) {
            this.charts.studyTime.data.labels = chartData.studyTime.labels;
            this.charts.studyTime.data.datasets[0].data = chartData.studyTime.data;
            this.charts.studyTime.update();
        }
        
        // Update subject distribution chart
        if (this.charts.subjectDistribution && chartData.subjects) {
            this.charts.subjectDistribution.data.labels = chartData.subjects.labels;
            this.charts.subjectDistribution.data.datasets[0].data = chartData.subjects.data;
            this.charts.subjectDistribution.update();
        }
        
        // Update quiz performance chart
        if (this.charts.quizPerformance && chartData.quizzes) {
            this.charts.quizPerformance.data.labels = chartData.quizzes.labels;
            this.charts.quizPerformance.data.datasets[0].data = chartData.quizzes.data;
            this.charts.quizPerformance.update();
        }
    }
    
    updateChartType(chartId, newType) {
        const chartMap = {
            'study-time-chart': 'studyTime',
            'subject-distribution-chart': 'subjectDistribution',
            'quiz-performance-chart': 'quizPerformance'
        };
        
        const chartKey = chartMap[chartId];
        if (this.charts[chartKey]) {
            const chart = this.charts[chartKey];
            const currentData = chart.data;
            
            // Destroy current chart
            chart.destroy();
            
            // Create new chart with new type
            const ctx = document.getElementById(chartId);
            this.charts[chartKey] = new Chart(ctx, {
                type: newType,
                data: currentData,
                options: this.getChartOptions(newType, chartKey)
            });
        }
    }
    
    getChartOptions(type, chartKey) {
        const baseOptions = {
            responsive: true,
            maintainAspectRatio: false
        };
        
        if (type === 'doughnut' || type === 'pie') {
            return {
                ...baseOptions,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            };
        }
        
        return {
            ...baseOptions,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };
    }
    
    createActivityHeatmap() {
        const canvas = document.getElementById('activity-heatmap');
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        const cellSize = 12;
        const cellPadding = 2;
        const cols = 53; // weeks in a year
        const rows = 7;  // days in a week
        
        canvas.width = cols * (cellSize + cellPadding);
        canvas.height = rows * (cellSize + cellPadding);
        
        // Generate sample heatmap data
        const heatmapData = this.generateHeatmapData();
        
        // Draw heatmap
        for (let week = 0; week < cols; week++) {
            for (let day = 0; day < rows; day++) {
                const intensity = heatmapData[week * rows + day] || 0;
                const color = this.getHeatmapColor(intensity);
                
                const x = week * (cellSize + cellPadding);
                const y = day * (cellSize + cellPadding);
                
                ctx.fillStyle = color;
                ctx.fillRect(x, y, cellSize, cellSize);
            }
        }
    }
    
    generateHeatmapData() {
        // Generate sample data (replace with real data from API)
        const data = [];
        for (let i = 0; i < 371; i++) {
            data.push(Math.random() * 5);
        }
        return data;
    }
    
    getHeatmapColor(intensity) {
        const colors = [
            '#f3f4f6', // level 0 - no activity
            '#d1fae5', // level 1 - low activity
            '#a7f3d0', // level 2 - moderate activity
            '#6ee7b7', // level 3 - high activity
            '#10b981'  // level 4 - very high activity
        ];
        
        const level = Math.min(4, Math.floor(intensity));
        return colors[level];
    }
    
    updateAnalytics() {
        this.loadAnalyticsData();
    }
    
    updateInsights(insights) {
        const insightsList = document.getElementById('insights-list');
        if (!insightsList || !insights) return;
        
        insightsList.innerHTML = insights.map(insight => `
            <div class="insight-item ${insight.type}">
                <div class="insight-icon">
                    <i class="${insight.icon}"></i>
                </div>
                <div class="insight-content">
                    <h4>${insight.title}</h4>
                    <p>${insight.message}</p>
                    ${insight.action ? `<button class="btn btn-sm btn-primary" onclick="${insight.action}">${insight.actionText}</button>` : ''}
                </div>
            </div>
        `).join('');
    }
    
    async generateComparison() {
        const period1 = document.getElementById('compare-period-1').value;
        const period2 = document.getElementById('compare-period-2').value;
        
        try {
            const response = await fetch('api/analytics/compare.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ period1, period2 })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.createComparisonChart(data.comparison);
            }
        } catch (error) {
            console.error('Error generating comparison:', error);
        }
    }
    
    createComparisonChart(comparisonData) {
        const ctx = document.getElementById('comparison-chart');
        if (!ctx) return;
        
        // Destroy existing chart if it exists
        if (this.charts.comparison) {
            this.charts.comparison.destroy();
        }
        
        this.charts.comparison = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: comparisonData.labels,
                datasets: [
                    {
                        label: comparisonData.period1.name,
                        data: comparisonData.period1.data,
                        backgroundColor: 'rgba(99, 102, 241, 0.8)',
                        borderColor: '#6366f1',
                        borderWidth: 1
                    },
                    {
                        label: comparisonData.period2.name,
                        data: comparisonData.period2.data,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: '#10b981',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    async exportAnalytics() {
        try {
            const response = await fetch(`api/analytics/export.php?range=${this.currentRange}&subject=${this.currentSubject}`);
            const blob = await response.blob();
            
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `analytics-${this.currentRange}-${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            this.showNotification('Analytics exported successfully!', 'success');
        } catch (error) {
            console.error('Error exporting analytics:', error);
            this.showNotification('Failed to export analytics', 'error');
        }
    }
    
    formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        
        if (hours > 0) {
            return `${hours}h ${minutes}m`;
        }
        return `${minutes}m`;
    }
    
    showNotification(message, type = 'info') {
        if (typeof showNotification === 'function') {
            showNotification(message, type);
        } else {
            console.log(message);
        }
    }
}

// Goals Management
class GoalsManager {
    constructor() {
        this.goals = [];
        this.init();
    }
    
    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.bindGoalsEvents();
            this.loadGoals();
        });
    }
    
    bindGoalsEvents() {
        const goalsForm = document.getElementById('goals-form');
        if (goalsForm) {
            goalsForm.addEventListener('submit', (e) => this.saveGoal(e));
        }
    }
    
    async loadGoals() {
        try {
            const response = await fetch('api/analytics/get-goals.php');
            const data = await response.json();
            
            if (data.success) {
                this.goals = data.goals;
                this.renderGoals();
            }
        } catch (error) {
            console.error('Error loading goals:', error);
        }
    }
    
    renderGoals() {
        const goalsGrid = document.getElementById('goals-grid');
        if (!goalsGrid) return;
        
        goalsGrid.innerHTML = this.goals.map(goal => `
            <div class="goal-card ${goal.completed ? 'completed' : ''}">
                <div class="goal-header">
                    <h4>${goal.title}</h4>
                    <div class="goal-type">${goal.type}</div>
                </div>
                <div class="goal-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: ${goal.progress}%"></div>
                    </div>
                    <span class="progress-text">${goal.current}/${goal.target} ${goal.unit}</span>
                </div>
                <div class="goal-meta">
                    <span class="goal-period">${goal.period}</span>
                    <span class="goal-status ${goal.completed ? 'completed' : 'active'}">${goal.completed ? 'Completed' : 'Active'}</span>
                </div>
            </div>
        `).join('');
    }
    
    async saveGoal(e) {
        e.preventDefault();
        
        const goalData = {
            title: document.getElementById('goal-title').value,
            type: document.getElementById('goal-type').value,
            target: parseInt(document.getElementById('goal-target').value),
            period: document.getElementById('goal-period').value
        };
        
        try {
            const response = await fetch('api/analytics/save-goal.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(goalData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.closeGoalsModal();
                this.loadGoals();
                this.showNotification('Goal created successfully!', 'success');
            } else {
                this.showNotification('Failed to create goal', 'error');
            }
        } catch (error) {
            console.error('Error saving goal:', error);
            this.showNotification('Error creating goal', 'error');
        }
    }
    
    openGoalsModal() {
        document.getElementById('goals-modal').style.display = 'block';
    }
    
    closeGoalsModal() {
        document.getElementById('goals-modal').style.display = 'none';
        document.getElementById('goals-form').reset();
    }
    
    showNotification(message, type) {
        if (typeof showNotification === 'function') {
            showNotification(message, type);
        }
    }
}

// Global functions
function openGoalsModal() {
    window.goalsManager.openGoalsModal();
}

function closeGoalsModal() {
    window.goalsManager.closeGoalsModal();
}

function exportAnalytics() {
    window.analyticsManager.exportAnalytics();
}

function generateComparison() {
    window.analyticsManager.generateComparison();
}

// Initialize managers
document.addEventListener('DOMContentLoaded', () => {
    window.analyticsManager = new AnalyticsManager();
    window.goalsManager = new GoalsManager();
});