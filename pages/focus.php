<div class="focus-page">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-eye-slash"></i> Focus Mode</h1>
        <p>Block distractions and maintain deep focus during study sessions</p>
    </div>

    <!-- Focus Session Active -->
    <div class="focus-active-section" id="focus-active-section" style="display: none;">
        <div class="card focus-active-card">
            <div class="card-header">
                <h2><i class="fas fa-shield-alt"></i> Focus Mode Active</h2>
                <div class="focus-timer">
                    <span id="focus-time-left">25:00</span>
                    <span class="focus-label">remaining</span>
                </div>
            </div>
            <div class="card-body">
                <div class="focus-status">
                    <div class="status-indicator active">
                        <div class="pulse"></div>
                        <span>Deep Focus Activated</span>
                    </div>
                    
                    <div class="blocked-attempts" id="blocked-attempts">
                        <span class="attempts-count">0</span>
                        <span class="attempts-label">distractions blocked</span>
                    </div>
                </div>
                
                <div class="focus-controls">
                    <button class="btn btn-outline" onclick="pauseFocusSession()">
                        <i class="fas fa-pause"></i> Pause Session
                    </button>
                    <button class="btn btn-danger" onclick="endFocusSession()">
                        <i class="fas fa-stop"></i> End Focus Mode
                    </button>
                </div>
                
                <div class="motivational-quote" id="motivational-quote">
                    <p>"Success is the sum of small efforts repeated day in and day out."</p>
                    <small>- Robert Collier</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Focus Setup Section -->
    <div class="focus-setup-section" id="focus-setup-section">
        <div class="focus-options">
            <!-- Quick Start Focus -->
            <div class="card quick-focus-card">
                <div class="card-header">
                    <h2><i class="fas fa-bolt"></i> Quick Focus</h2>
                </div>
                <div class="card-body">
                    <p>Start a focus session with default settings</p>
                    
                    <div class="quick-presets">
                        <button class="preset-btn" onclick="quickFocus(25)">
                            <span class="preset-time">25m</span>
                            <span class="preset-label">Pomodoro</span>
                        </button>
                        <button class="preset-btn" onclick="quickFocus(45)">
                            <span class="preset-time">45m</span>
                            <span class="preset-label">Deep Work</span>
                        </button>
                        <button class="preset-btn" onclick="quickFocus(90)">
                            <span class="preset-time">90m</span>
                            <span class="preset-label">Flow State</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Custom Focus Session -->
            <div class="card custom-focus-card">
                <div class="card-header">
                    <h2><i class="fas fa-cogs"></i> Custom Focus Session</h2>
                </div>
                <div class="card-body">
                    <form id="focus-form">
                        <div class="form-group">
                            <label for="focus-duration">Duration (minutes):</label>
                            <input type="number" id="focus-duration" class="form-control" value="25" min="5" max="240">
                        </div>
                        
                        <div class="form-group">
                            <label for="focus-subject">Subject:</label>
                            <select id="focus-subject" class="form-control">
                                <option value="Math">Math</option>
                                <option value="Science">Science</option>
                                <option value="English">English</option>
                                <option value="History">History</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Block Level:</label>
                            <div class="block-level-options">
                                <label class="radio-label">
                                    <input type="radio" name="block-level" value="minimal" checked>
                                    <span>Minimal - Just timer and motivation</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="block-level" value="moderate">
                                    <span>Moderate - Block social media</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="block-level" value="strict">
                                    <span>Strict - Block all non-educational sites</span>
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-play"></i> Start Focus Session
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Focus Statistics -->
        <div class="focus-stats-section">
            <div class="card focus-stats-card">
                <div class="card-header">
                    <h2><i class="fas fa-chart-line"></i> Focus Statistics</h2>
                </div>
                <div class="card-body">
                    <?php
                    try {
                        // Get focus session statistics
                        $stmt = $pdo->prepare("SELECT 
                            COUNT(*) as total_sessions,
                            SUM(duration) as total_time,
                            AVG(duration) as avg_duration,
                            COUNT(CASE WHEN completed = 1 THEN 1 END) as completed_sessions
                            FROM focus_sessions 
                            WHERE user_id = ?");
                        $stmt->execute([$_SESSION['user_id']]);
                        $focusStats = $stmt->fetch();
                        
                        $completionRate = $focusStats['total_sessions'] > 0 
                            ? ($focusStats['completed_sessions'] / $focusStats['total_sessions']) * 100 
                            : 0;
                    ?>
                    
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value"><?php echo $focusStats['total_sessions'] ?? 0; ?></div>
                            <div class="stat-label">Total Sessions</div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-value"><?php echo formatTime($focusStats['total_time'] ?? 0); ?></div>
                            <div class="stat-label">Focus Time</div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-value"><?php echo formatTime($focusStats['avg_duration'] ?? 0); ?></div>
                            <div class="stat-label">Average Session</div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-value"><?php echo round($completionRate, 1); ?>%</div>
                            <div class="stat-label">Completion Rate</div>
                        </div>
                    </div>
                    
                    <?php
                    // Get recent focus sessions
                    $stmt = $pdo->prepare("SELECT * FROM focus_sessions 
                                         WHERE user_id = ? 
                                         ORDER BY created_at DESC 
                                         LIMIT 5");
                    $stmt->execute([$_SESSION['user_id']]);
                    $recentSessions = $stmt->fetchAll();
                    ?>
                    
                    <div class="recent-sessions">
                        <h3>Recent Sessions</h3>
                        <?php if ($recentSessions): ?>
                        <div class="sessions-list">
                            <?php foreach ($recentSessions as $session): ?>
                            <div class="session-item">
                                <div class="session-info">
                                    <span class="session-duration"><?php echo formatTime($session['duration']); ?></span>
                                    <span class="session-date"><?php echo date('M j, g:i A', strtotime($session['created_at'])); ?></span>
                                </div>
                                <div class="session-status">
                                    <?php if ($session['completed']): ?>
                                    <span class="status-badge completed">
                                        <i class="fas fa-check"></i> Completed
                                    </span>
                                    <?php else: ?>
                                    <span class="status-badge incomplete">
                                        <i class="fas fa-times"></i> Interrupted
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p class="no-sessions">No focus sessions yet. Start your first session above!</p>
                        <?php endif; ?>
                    </div>
                    
                    <?php } catch(Exception $e) { ?>
                        <p>Unable to load focus statistics.</p>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Focus Tips -->
        <div class="focus-tips-section">
            <div class="card focus-tips-card">
                <div class="card-header">
                    <h2><i class="fas fa-lightbulb"></i> Focus Tips</h2>
                </div>
                <div class="card-body">
                    <div class="tips-list">
                        <div class="tip-item">
                            <i class="fas fa-clock"></i>
                            <div class="tip-content">
                                <h4>Use the Pomodoro Technique</h4>
                                <p>Work for 25 minutes, then take a 5-minute break. Repeat for optimal productivity.</p>
                            </div>
                        </div>
                        
                        <div class="tip-item">
                            <i class="fas fa-mobile-alt"></i>
                            <div class="tip-content">
                                <h4>Remove Physical Distractions</h4>
                                <p>Put your phone in another room or use airplane mode during focus sessions.</p>
                            </div>
                        </div>
                        
                        <div class="tip-item">
                            <i class="fas fa-brain"></i>
                            <div class="tip-content">
                                <h4>Single-Task Focus</h4>
                                <p>Focus on one subject or task at a time for maximum effectiveness.</p>
                            </div>
                        </div>
                        
                        <div class="tip-item">
                            <i class="fas fa-leaf"></i>
                            <div class="tip-content">
                                <h4>Take Proper Breaks</h4>
                                <p>Step away from your workspace, stretch, or get some fresh air during breaks.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Focus Achievements -->
    <div class="focus-achievements">
        <div class="card achievements-card">
            <div class="card-header">
                <h2><i class="fas fa-trophy"></i> Focus Achievements</h2>
            </div>
            <div class="card-body">
                <div class="achievements-grid">
                    <div class="achievement-item earned">
                        <i class="fas fa-baby"></i>
                        <h4>First Focus</h4>
                        <p>Complete your first focus session</p>
                        <small>Earned 2 days ago</small>
                    </div>
                    
                    <div class="achievement-item">
                        <i class="fas fa-fire"></i>
                        <h4>Focus Streak</h4>
                        <p>Complete focus sessions for 7 days in a row</p>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 60%;"></div>
                        </div>
                        <small>4/7 days</small>
                    </div>
                    
                    <div class="achievement-item">
                        <i class="fas fa-stopwatch"></i>
                        <h4>Marathon Focus</h4>
                        <p>Complete a 2-hour focus session</p>
                        <small>Not earned</small>
                    </div>
                    
                    <div class="achievement-item">
                        <i class="fas fa-shield"></i>
                        <h4>Distraction Defender</h4>
                        <p>Block 100 distracting websites</p>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 23%;"></div>
                        </div>
                        <small>23/100 blocked</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Break Reminder Modal -->
<div id="break-reminder-modal" class="modal">
    <div class="modal-content">
        <h3><i class="fas fa-coffee"></i> Time for a Break!</h3>
        <p>You've been focusing for a while. Take a short break to recharge.</p>
        
        <div class="break-options">
            <button class="btn btn-outline" onclick="skipBreak()">
                <i class="fas fa-forward"></i> Skip Break
            </button>
            <button class="btn btn-primary" onclick="takeBreak(5)">
                <i class="fas fa-coffee"></i> 5 Minute Break
            </button>
        </div>
    </div>
</div>

<!-- Focus Complete Modal -->
<div id="focus-complete-modal" class="modal">
    <div class="modal-content">
        <h3>🎉 Focus Session Complete!</h3>
        <div class="completion-stats">
            <div class="completion-stat">
                <span class="stat-number" id="session-duration">25</span>
                <span class="stat-label">Minutes Focused</span>
            </div>
            <div class="completion-stat">
                <span class="stat-number" id="distractions-blocked">0</span>
                <span class="stat-label">Distractions Blocked</span>
            </div>
            <div class="completion-stat">
                <span class="stat-number" id="points-earned">+25</span>
                <span class="stat-label">Points Earned</span>
            </div>
        </div>
        
        <div class="motivational-message">
            <p>Great job staying focused! Consistency is key to achieving your goals.</p>
        </div>
        
        <div class="completion-actions">
            <button class="btn btn-outline" onclick="closeFocusComplete()">
                <i class="fas fa-home"></i> Back to Dashboard
            </button>
            <button class="btn btn-primary" onclick="startAnotherSession()">
                <i class="fas fa-redo"></i> Another Session
            </button>
        </div>
    </div>
</div>