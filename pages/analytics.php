<div class="analytics-page">
    <!-- Page Header -->
    <div class="page-header">
        <h1 data-i18n="analytics.title"><i class="fas fa-chart-line"></i> Analytics Dashboard</h1>
        <p data-i18n="analytics.subtitle">Visualize your study patterns and track your progress over time</p>
    </div>

    <!-- Analytics Controls -->
    <div class="analytics-controls">
        <div class="time-range-selector">
            <button class="range-btn active" data-range="week" data-i18n="analytics.thisWeek">This Week</button>
            <button class="range-btn" data-range="month" data-i18n="analytics.thisMonth">This Month</button>
            <button class="range-btn" data-range="year" data-i18n="analytics.thisYear">This Year</button>
            <button class="range-btn" data-range="all" data-i18n="analytics.allTime">All Time</button>
        </div>
        
        <div class="analytics-filters">
            <select id="subject-filter-analytics" class="form-control">
                <option value="" data-i18n="analytics.allSubjects">All Subjects</option>
                <option value="Math">Math</option>
                <option value="Science">Science</option>
                <option value="English">English</option>
                <option value="History">History</option>
                <option value="Other">Other</option>
            </select>
            
            <button class="btn btn-outline" onclick="exportAnalytics()">
                <i class="fas fa-download"></i> <span data-i18n="analytics.export">Export</span>
            </button>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="kpi-section">
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="kpi-content">
                    <div class="kpi-value" id="total-study-time">0h 0m</div>
                    <div class="kpi-label" data-i18n="analytics.totalStudyTime">Total Study Time</div>
                    <div class="kpi-change positive" id="time-change">+12% vs last period</div>
                </div>
            </div>
            
            <div class="kpi-card">
                <div class="kpi-icon">
                    <i class="fas fa-fire"></i>
                </div>
                <div class="kpi-content">
                    <div class="kpi-value" id="current-streak">0</div>
                    <div class="kpi-label" data-i18n="analytics.currentStreak">Current Streak</div>
                    <div class="kpi-change" id="streak-status">Keep it up!</div>
                </div>
            </div>
            
            <div class="kpi-card">
                <div class="kpi-icon">
                    <i class="fas fa-target"></i>
                </div>
                <div class="kpi-content">
                    <div class="kpi-value" id="avg-session">0m</div>
                    <div class="kpi-label" data-i18n="analytics.avgSession">Avg Session</div>
                    <div class="kpi-change" id="session-change">+5 min vs last period</div>
                </div>
            </div>
            
            <div class="kpi-card">
                <div class="kpi-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="kpi-content">
                    <div class="kpi-value" id="total-points">0</div>
                    <div class="kpi-label" data-i18n="analytics.totalPoints">Total Points</div>
                    <div class="kpi-change positive" id="points-change">+150 this week</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <!-- Study Time Trends -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 data-i18n="analytics.studyTimeTrends">Study Time Trends</h3>
                <div class="chart-controls">
                    <button class="chart-type-btn active" data-chart="line" data-i18n="analytics.line">Line</button>
                    <button class="chart-type-btn" data-chart="bar" data-i18n="analytics.bar">Bar</button>
                    <button class="chart-type-btn" data-chart="area" data-i18n="analytics.area">Area</button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="study-time-chart"></canvas>
            </div>
        </div>

        <!-- Subject Distribution -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 data-i18n="analytics.subjectDistribution">Study Time by Subject</h3>
                <div class="chart-controls">
                    <button class="chart-type-btn active" data-chart="doughnut" data-i18n="analytics.doughnut">Doughnut</button>
                    <button class="chart-type-btn" data-chart="pie" data-i18n="analytics.pie">Pie</button>
                    <button class="chart-type-btn" data-chart="polarArea" data-i18n="analytics.polar">Polar</button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="subject-distribution-chart"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics -->
    <div class="detailed-analytics">
        <!-- Session Analysis -->
        <div class="analysis-card">
            <div class="card-header">
                <h3 data-i18n="analytics.sessionAnalysis">Session Analysis</h3>
            </div>
            <div class="card-body">
                <div class="analysis-grid">
                    <div class="analysis-item">
                        <div class="analysis-label" data-i18n="analytics.totalSessions">Total Sessions</div>
                        <div class="analysis-value" id="total-sessions">0</div>
                        <div class="analysis-trend" id="sessions-trend">
                            <i class="fas fa-arrow-up"></i> +8% vs last period
                        </div>
                    </div>
                    
                    <div class="analysis-item">
                        <div class="analysis-label" data-i18n="analytics.completionRate">Completion Rate</div>
                        <div class="analysis-value" id="completion-rate">0%</div>
                        <div class="analysis-trend" id="completion-trend">
                            <i class="fas fa-arrow-up"></i> +2% vs last period
                        </div>
                    </div>
                    
                    <div class="analysis-item">
                        <div class="analysis-label" data-i18n="analytics.mostProductiveTime">Most Productive Time</div>
                        <div class="analysis-value" id="productive-time">2:00 PM</div>
                        <div class="analysis-description" data-i18n="analytics.basedOnData">Based on session data</div>
                    </div>
                    
                    <div class="analysis-item">
                        <div class="analysis-label" data-i18n="analytics.longestSession">Longest Session</div>
                        <div class="analysis-value" id="longest-session">2h 30m</div>
                        <div class="analysis-description" id="longest-date">Yesterday</div>
                    </div>
                </div>
                
                <!-- Session Heatmap -->
                <div class="heatmap-section">
                    <h4 data-i18n="analytics.activityHeatmap">Activity Heatmap</h4>
                    <div class="heatmap-container">
                        <canvas id="activity-heatmap"></canvas>
                    </div>
                    <div class="heatmap-legend">
                        <span data-i18n="analytics.less">Less</span>
                        <div class="legend-scale">
                            <div class="legend-item level-0"></div>
                            <div class="legend-item level-1"></div>
                            <div class="legend-item level-2"></div>
                            <div class="legend-item level-3"></div>
                            <div class="legend-item level-4"></div>
                        </div>
                        <span data-i18n="analytics.more">More</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="analysis-card">
            <div class="card-header">
                <h3 data-i18n="analytics.performanceMetrics">Performance Metrics</h3>
            </div>
            <div class="card-body">
                <!-- Quiz Performance -->
                <div class="metric-section">
                    <h4 data-i18n="analytics.quizPerformance">Quiz Performance</h4>
                    <div class="performance-chart">
                        <canvas id="quiz-performance-chart"></canvas>
                    </div>
                </div>
                
                <!-- Flashcard Mastery -->
                <div class="metric-section">
                    <h4 data-i18n="analytics.flashcardMastery">Flashcard Mastery</h4>
                    <div class="mastery-breakdown">
                        <div class="mastery-item">
                            <div class="mastery-level easy">
                                <div class="mastery-color"></div>
                                <span data-i18n="analytics.easy">Easy</span>
                                <span class="mastery-count" id="easy-cards">0</span>
                            </div>
                        </div>
                        <div class="mastery-item">
                            <div class="mastery-level medium">
                                <div class="mastery-color"></div>
                                <span data-i18n="analytics.medium">Medium</span>
                                <span class="mastery-count" id="medium-cards">0</span>
                            </div>
                        </div>
                        <div class="mastery-item">
                            <div class="mastery-level hard">
                                <div class="mastery-color"></div>
                                <span data-i18n="analytics.hard">Hard</span>
                                <span class="mastery-count" id="hard-cards">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Goals and Targets -->
    <div class="goals-section">
        <div class="card goals-card">
            <div class="card-header">
                <h3 data-i18n="analytics.goalsTargets">Goals & Targets</h3>
                <button class="btn btn-outline btn-sm" onclick="openGoalsModal()">
                    <i class="fas fa-plus"></i> <span data-i18n="analytics.addGoal">Add Goal</span>
                </button>
            </div>
            <div class="card-body">
                <div class="goals-grid" id="goals-grid">
                    <!-- Goals will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Comparison Section -->
    <div class="comparison-section">
        <div class="card comparison-card">
            <div class="card-header">
                <h3 data-i18n="analytics.comparison">Compare Periods</h3>
            </div>
            <div class="card-body">
                <div class="comparison-controls">
                    <select id="compare-period-1" class="form-control">
                        <option value="current-week" data-i18n="analytics.currentWeek">Current Week</option>
                        <option value="last-week" data-i18n="analytics.lastWeek">Last Week</option>
                        <option value="current-month" data-i18n="analytics.currentMonth">Current Month</option>
                        <option value="last-month" data-i18n="analytics.lastMonth">Last Month</option>
                    </select>
                    <span data-i18n="analytics.vs">vs</span>
                    <select id="compare-period-2" class="form-control">
                        <option value="last-week" data-i18n="analytics.lastWeek">Last Week</option>
                        <option value="current-week" data-i18n="analytics.currentWeek">Current Week</option>
                        <option value="last-month" data-i18n="analytics.lastMonth">Last Month</option>
                        <option value="current-month" data-i18n="analytics.currentMonth">Current Month</option>
                    </select>
                    <button class="btn btn-primary" onclick="generateComparison()">
                        <i class="fas fa-chart-bar"></i> <span data-i18n="analytics.compare">Compare</span>
                    </button>
                </div>
                
                <div class="comparison-results" id="comparison-results">
                    <canvas id="comparison-chart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Insights and Recommendations -->
    <div class="insights-section">
        <div class="card insights-card">
            <div class="card-header">
                <h3 data-i18n="analytics.insights">Insights & Recommendations</h3>
            </div>
            <div class="card-body">
                <div class="insights-list" id="insights-list">
                    <!-- AI-generated insights will appear here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Goals Modal -->
<div id="goals-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3 data-i18n="analytics.addNewGoal">Add New Goal</h3>
        <form id="goals-form">
            <div class="form-group">
                <label for="goal-title" data-i18n="analytics.goalTitle">Goal Title:</label>
                <input type="text" id="goal-title" class="form-control" placeholder="e.g., Study 10 hours this week" required>
            </div>
            
            <div class="form-group">
                <label for="goal-type" data-i18n="analytics.goalType">Goal Type:</label>
                <select id="goal-type" class="form-control">
                    <option value="study-time" data-i18n="analytics.studyTime">Study Time</option>
                    <option value="sessions" data-i18n="analytics.sessions">Sessions</option>
                    <option value="streak" data-i18n="analytics.streak">Streak</option>
                    <option value="points" data-i18n="analytics.points">Points</option>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="goal-target" data-i18n="analytics.target">Target:</label>
                    <input type="number" id="goal-target" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="goal-period" data-i18n="analytics.period">Period:</label>
                    <select id="goal-period" class="form-control">
                        <option value="daily" data-i18n="analytics.daily">Daily</option>
                        <option value="weekly" data-i18n="analytics.weekly">Weekly</option>
                        <option value="monthly" data-i18n="analytics.monthly">Monthly</option>
                    </select>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeGoalsModal()" data-i18n="common.cancel">Cancel</button>
                <button type="submit" class="btn btn-primary" data-i18n="analytics.createGoal">Create Goal</button>
            </div>
        </form>
    </div>
</div>