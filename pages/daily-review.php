<?php
// Daily Review System
?>
<div class="daily-review-page">
    <div class="review-header">
        <div class="review-title">
            <h1><i class="fas fa-calendar-check"></i> Daily Review</h1>
            <p>Take 10 minutes to reflect on your progress and plan ahead</p>
        </div>
        <div class="review-date">
            <span><?php echo date('l, F j, Y'); ?></span>
        </div>
    </div>

    <!-- Review Progress -->
    <div class="review-progress">
        <div class="progress-steps">
            <div class="step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-title">Yesterday's Review</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-title">Today's Reflection</div>
            </div>
            <div class="step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-title">Tomorrow's Planning</div>
            </div>
            <div class="step" data-step="4">
                <div class="step-number">4</div>
                <div class="step-title">Goals & Insights</div>
            </div>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: 25%"></div>
        </div>
    </div>

    <!-- Review Content -->
    <div class="review-content">
        <!-- Step 1: Yesterday's Review -->
        <div class="review-step active" id="step-1">
            <div class="step-header">
                <h2><i class="fas fa-history"></i> Yesterday's Accomplishments</h2>
                <p>Let's review what you achieved yesterday</p>
            </div>
            
            <div class="yesterday-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="yesterday-study-time">0h 0m</h3>
                        <p>Study Time</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="yesterday-tasks-completed">0</h3>
                        <p>Tasks Completed</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-fire"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="yesterday-streak">0</h3>
                        <p>Study Streak</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="yesterday-points">0</h3>
                        <p>Points Earned</p>
                    </div>
                </div>
            </div>
            
            <div class="yesterday-activities">
                <h3>Yesterday's Activities</h3>
                <div id="yesterday-activities-list" class="activities-list">
                    <!-- Populated by JavaScript -->
                </div>
            </div>
            
            <div class="step-actions">
                <button class="btn btn-primary next-step-btn" data-next="2">
                    <i class="fas fa-arrow-right"></i> Continue
                </button>
            </div>
        </div>

        <!-- Step 2: Today's Reflection -->
        <div class="review-step" id="step-2">
            <div class="step-header">
                <h2><i class="fas fa-heart"></i> How are you feeling today?</h2>
                <p>Reflect on your current state and motivation</p>
            </div>
            
            <div class="mood-selector">
                <h3>Select your mood:</h3>
                <div class="mood-options">
                    <div class="mood-option" data-mood="excellent">
                        <div class="mood-emoji">😄</div>
                        <span>Excellent</span>
                    </div>
                    <div class="mood-option" data-mood="good">
                        <div class="mood-emoji">😊</div>
                        <span>Good</span>
                    </div>
                    <div class="mood-option" data-mood="okay">
                        <div class="mood-emoji">😐</div>
                        <span>Okay</span>
                    </div>
                    <div class="mood-option" data-mood="tired">
                        <div class="mood-emoji">😴</div>
                        <span>Tired</span>
                    </div>
                    <div class="mood-option" data-mood="stressed">
                        <div class="mood-emoji">😰</div>
                        <span>Stressed</span>
                    </div>
                </div>
            </div>
            
            <div class="reflection-questions">
                <div class="question-group">
                    <label for="biggest-win">What was your biggest win yesterday?</label>
                    <textarea id="biggest-win" class="form-control" rows="3" placeholder="Describe your biggest accomplishment..."></textarea>
                </div>
                
                <div class="question-group">
                    <label for="biggest-challenge">What was your biggest challenge?</label>
                    <textarea id="biggest-challenge" class="form-control" rows="3" placeholder="What did you struggle with?"></textarea>
                </div>
                
                <div class="question-group">
                    <label for="lessons-learned">What did you learn?</label>
                    <textarea id="lessons-learned" class="form-control" rows="3" placeholder="Any insights or lessons learned?"></textarea>
                </div>
            </div>
            
            <div class="step-actions">
                <button class="btn btn-outline prev-step-btn" data-prev="1">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <button class="btn btn-primary next-step-btn" data-next="3">
                    <i class="fas fa-arrow-right"></i> Continue
                </button>
            </div>
        </div>

        <!-- Step 3: Tomorrow's Planning -->
        <div class="review-step" id="step-3">
            <div class="step-header">
                <h2><i class="fas fa-calendar-plus"></i> Planning Tomorrow</h2>
                <p>Set your intentions and goals for tomorrow</p>
            </div>
            
            <div class="tomorrow-goals">
                <div class="goal-section">
                    <h3><i class="fas fa-bullseye"></i> Tomorrow's Main Goals</h3>
                    <div class="goals-input">
                        <div class="goal-input-group">
                            <input type="text" class="form-control goal-input" placeholder="Goal 1: What's your top priority?">
                            <select class="form-control priority-select">
                                <option value="high">High</option>
                                <option value="medium" selected>Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        <div class="goal-input-group">
                            <input type="text" class="form-control goal-input" placeholder="Goal 2: What else do you want to achieve?">
                            <select class="form-control priority-select">
                                <option value="high">High</option>
                                <option value="medium" selected>Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        <div class="goal-input-group">
                            <input type="text" class="form-control goal-input" placeholder="Goal 3: Any additional goals?">
                            <select class="form-control priority-select">
                                <option value="high">High</option>
                                <option value="medium" selected>Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="study-plan">
                    <h3><i class="fas fa-book"></i> Study Plan</h3>
                    <div class="study-blocks">
                        <div class="study-block">
                            <label>Subject to focus on:</label>
                            <select class="form-control" id="focus-subject">
                                <option value="">Select subject...</option>
                                <option value="Math">Math</option>
                                <option value="Science">Science</option>
                                <option value="English">English</option>
                                <option value="History">History</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="study-block">
                            <label>Target study time:</label>
                            <div class="time-input-group">
                                <input type="number" class="form-control" id="target-hours" min="0" max="12" placeholder="0">
                                <span>hours</span>
                                <input type="number" class="form-control" id="target-minutes" min="0" max="59" placeholder="0">
                                <span>minutes</span>
                            </div>
                        </div>
                        
                        <div class="study-block">
                            <label>Study method:</label>
                            <select class="form-control" id="study-method">
                                <option value="">Select method...</option>
                                <option value="pomodoro">Pomodoro Technique</option>
                                <option value="focused">Focused Sessions</option>
                                <option value="flashcards">Flashcard Review</option>
                                <option value="quiz">Practice Quizzes</option>
                                <option value="mixed">Mixed Approach</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="step-actions">
                <button class="btn btn-outline prev-step-btn" data-prev="2">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <button class="btn btn-primary next-step-btn" data-next="4">
                    <i class="fas fa-arrow-right"></i> Continue
                </button>
            </div>
        </div>

        <!-- Step 4: Goals & Insights -->
        <div class="review-step" id="step-4">
            <div class="step-header">
                <h2><i class="fas fa-lightbulb"></i> Insights & Motivation</h2>
                <p>Final thoughts and motivation for tomorrow</p>
            </div>
            
            <div class="insights-section">
                <div class="motivation-quote">
                    <div class="quote-content">
                        <i class="fas fa-quote-left"></i>
                        <p id="daily-quote">Loading inspirational quote...</p>
                        <cite id="quote-author"></cite>
                    </div>
                </div>
                
                <div class="personal-insights">
                    <h3>Personal Insights</h3>
                    <div class="insight-questions">
                        <div class="question-group">
                            <label for="improvement-area">What's one area you want to improve tomorrow?</label>
                            <textarea id="improvement-area" class="form-control" rows="2" placeholder="Focus, time management, specific subject..."></textarea>
                        </div>
                        
                        <div class="question-group">
                            <label for="motivation-reason">Why is achieving tomorrow's goals important to you?</label>
                            <textarea id="motivation-reason" class="form-control" rows="2" placeholder="What's driving you to succeed?"></textarea>
                        </div>
                        
                        <div class="question-group">
                            <label for="success-reward">How will you reward yourself for completing tomorrow's goals?</label>
                            <textarea id="success-reward" class="form-control" rows="2" placeholder="Small celebration, treat, break activity..."></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="weekly-progress">
                    <h3>This Week's Progress</h3>
                    <div class="week-overview">
                        <canvas id="week-progress-chart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="step-actions">
                <button class="btn btn-outline prev-step-btn" data-prev="3">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <button class="btn btn-success complete-review-btn">
                    <i class="fas fa-check"></i> Complete Review
                </button>
            </div>
        </div>
    </div>

    <!-- Review Summary Modal -->
    <div id="review-summary-modal" class="modal">
        <div class="modal-content">
            <div class="summary-header">
                <h2><i class="fas fa-check-circle"></i> Review Complete!</h2>
                <p>Great job taking time to reflect and plan ahead</p>
            </div>
            
            <div class="summary-content">
                <div class="summary-stats">
                    <div class="summary-stat">
                        <i class="fas fa-clock"></i>
                        <span>Review completed in <strong id="review-duration">0</strong> minutes</span>
                    </div>
                    <div class="summary-stat">
                        <i class="fas fa-star"></i>
                        <span>Earned <strong>50 XP</strong> for daily review</span>
                    </div>
                </div>
                
                <div class="tomorrow-preview">
                    <h3>Tomorrow's Plan Summary:</h3>
                    <div id="plan-summary">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>
                
                <div class="motivational-message">
                    <p><strong>Remember:</strong> <span id="personal-motivation"></span></p>
                </div>
            </div>
            
            <div class="summary-actions">
                <button class="btn btn-primary" onclick="location.href='?page=dashboard'">
                    <i class="fas fa-home"></i> Go to Dashboard
                </button>
                <button class="btn btn-outline" onclick="location.href='?page=analytics'">
                    <i class="fas fa-chart-line"></i> View Analytics
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Timer for 10-minute review -->
<div class="review-timer">
    <div class="timer-display">
        <i class="fas fa-stopwatch"></i>
        <span id="review-timer">10:00</span>
    </div>
    <div class="timer-message">
        <span>Take your time, but aim for 10 minutes</span>
    </div>
</div>