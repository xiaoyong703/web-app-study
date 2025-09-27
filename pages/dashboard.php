<div class="dashboard">
    <!-- Study Timer Section -->
    <div class="card timer-card">
        <div class="card-header">
            <h2><i class="fas fa-clock"></i> Study Timer</h2>
        </div>
        <div class="card-body">
            <div class="timer-display">
                <div id="timer-time">25:00</div>
                <div class="timer-label" id="timer-label">Focus Time</div>
            </div>
            
            <div class="timer-controls">
                <button id="start-btn" class="btn btn-primary">
                    <i class="fas fa-play"></i> Start
                </button>
                <button id="pause-btn" class="btn btn-secondary" disabled>
                    <i class="fas fa-pause"></i> Pause
                </button>
                <button id="reset-btn" class="btn btn-outline">
                    <i class="fas fa-refresh"></i> Reset
                </button>
            </div>
            
            <div class="timer-settings">
                <div class="input-group">
                    <label for="subject-input">Subject:</label>
                    <select id="subject-input" class="form-control">
                        <option value="Math">Math</option>
                        <option value="Science">Science</option>
                        <option value="English">English</option>
                        <option value="History">History</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="timer-presets">
                    <button class="preset-btn" data-minutes="25">25m</button>
                    <button class="preset-btn" data-minutes="45">45m</button>
                    <button class="preset-btn" data-minutes="60">60m</button>
                    <button class="preset-btn" data-minutes="90">90m</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Todo List Section -->
    <div class="card todo-card">
        <div class="card-header">
            <h2><i class="fas fa-tasks"></i> Today's Goals</h2>
        </div>
        <div class="card-body">
            <form id="todo-form" class="todo-form">
                <div class="input-group">
                    <input type="text" id="todo-input" class="form-control" placeholder="Add a new task..." required>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
            </form>
            
            <ul id="todo-list" class="todo-list">
                <?php
                $todos = getTodoItems($_SESSION['user_id'], $pdo);
                foreach ($todos as $todo):
                ?>
                <li class="todo-item <?php echo $todo['completed'] ? 'completed' : ''; ?>" data-id="<?php echo $todo['id']; ?>">
                    <input type="checkbox" class="todo-checkbox" <?php echo $todo['completed'] ? 'checked' : ''; ?>>
                    <span class="todo-text"><?php echo htmlspecialchars($todo['task']); ?></span>
                    <button class="delete-btn" title="Delete task">
                        <i class="fas fa-trash"></i>
                    </button>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="card stats-card">
        <div class="card-header">
            <h2><i class="fas fa-chart-bar"></i> Study Statistics</h2>
        </div>
        <div class="card-body">
            <?php
            $stats = getStudyStats($_SESSION['user_id'], $pdo);
            $todayTime = $stats['today']['total_time'] ?? 0;
            $todaySessions = $stats['today']['sessions_count'] ?? 0;
            $weekTime = $stats['week']['total_time'] ?? 0;
            $weekSessions = $stats['week']['sessions_count'] ?? 0;
            ?>
            
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value"><?php echo formatTime($todayTime); ?></div>
                    <div class="stat-label">Today's Study Time</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-value"><?php echo $todaySessions; ?></div>
                    <div class="stat-label">Sessions Today</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-value"><?php echo formatTime($weekTime); ?></div>
                    <div class="stat-label">This Week</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-value"><?php echo $weekSessions; ?></div>
                    <div class="stat-label">Weekly Sessions</div>
                </div>
            </div>
            
            <div class="progress-section">
                <h3>Weekly Goal Progress</h3>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo min(100, ($weekTime / 3600) * 10); ?>%"></div>
                </div>
                <p class="progress-text">
                    <?php echo formatTime($weekTime); ?> / 10:00:00 hours this week
                </p>
            </div>
        </div>
    </div>

    <!-- D-Day Countdown -->
    <div class="card dday-card">
        <div class="card-header">
            <h2><i class="fas fa-calendar-alt"></i> D-Day Countdown</h2>
        </div>
        <div class="card-body">
            <?php
            try {
                $stmt = $pdo->prepare("SELECT * FROM d_day_events WHERE user_id = ? AND is_completed = 0 ORDER BY target_date ASC LIMIT 3");
                $stmt->execute([$_SESSION['user_id']]);
                $events = $stmt->fetchAll();
                
                if ($events): 
                    foreach ($events as $event):
                        $daysLeft = (strtotime($event['target_date']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);
                ?>
                <div class="dday-item" style="border-left: 4px solid <?php echo $event['color']; ?>">
                    <div class="dday-title"><?php echo htmlspecialchars($event['title']); ?></div>
                    <div class="dday-countdown">
                        <?php if ($daysLeft > 0): ?>
                            <span class="days-left"><?php echo ceil($daysLeft); ?> days left</span>
                        <?php elseif ($daysLeft == 0): ?>
                            <span class="days-left today">Today!</span>
                        <?php else: ?>
                            <span class="days-left overdue"><?php echo abs(floor($daysLeft)); ?> days overdue</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php 
                    endforeach;
                else: ?>
                <div class="no-events">
                    <p>No upcoming events. <a href="?page=dashboard&action=add-dday">Add your first D-Day event</a></p>
                </div>
                <?php endif;
            } catch(Exception $e) {
                echo '<p>Unable to load events.</p>';
            }
            ?>
            
            <button class="btn btn-outline btn-sm" onclick="openDdayModal()">
                <i class="fas fa-plus"></i> Add Event
            </button>
        </div>
    </div>

    <!-- Gamification Panel -->
    <div class="card gamification-card">
        <div class="card-header">
            <h2><i class="fas fa-trophy"></i> Your Progress</h2>
        </div>
        <div class="card-body">
            <?php
            try {
                $stmt = $pdo->prepare("SELECT * FROM user_stats WHERE user_id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $userStats = $stmt->fetch();
                
                if (!$userStats) {
                    // Create initial user stats
                    $stmt = $pdo->prepare("INSERT INTO user_stats (user_id) VALUES (?)");
                    $stmt->execute([$_SESSION['user_id']]);
                    $userStats = ['total_points' => 0, 'level' => 1, 'experience_points' => 0, 'current_streak' => 0, 'longest_streak' => 0];
                }
                
                $nextLevelXP = $userStats['level'] * 100; // 100 XP per level
                $currentXP = $userStats['experience_points'] % 100;
            ?>
            
            <div class="user-level">
                <div class="level-info">
                    <span class="level-badge">Level <?php echo $userStats['level']; ?></span>
                    <span class="total-points"><?php echo $userStats['total_points']; ?> points</span>
                </div>
                <div class="xp-bar">
                    <div class="xp-fill" style="width: <?php echo ($currentXP / 100) * 100; ?>%"></div>
                </div>
                <small><?php echo $currentXP; ?>/100 XP to next level</small>
            </div>
            
            <div class="stats-mini">
                <div class="stat-mini">
                    <i class="fas fa-fire"></i>
                    <span><?php echo $userStats['current_streak']; ?> day streak</span>
                </div>
                <div class="stat-mini">
                    <i class="fas fa-crown"></i>
                    <span>Best: <?php echo $userStats['longest_streak']; ?> days</span>
                </div>
            </div>
            
            <div class="recent-achievements">
                <?php
                $stmt = $pdo->prepare("SELECT * FROM user_achievements WHERE user_id = ? ORDER BY earned_at DESC LIMIT 2");
                $stmt->execute([$_SESSION['user_id']]);
                $achievements = $stmt->fetchAll();
                
                foreach ($achievements as $achievement):
                ?>
                <div class="achievement-mini">
                    <i class="<?php echo $achievement['badge_icon']; ?>"></i>
                    <span><?php echo $achievement['achievement_name']; ?></span>
                    <small>+<?php echo $achievement['points']; ?> pts</small>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php } catch(Exception $e) { ?>
                <p>Loading your progress...</p>
            <?php } ?>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card actions-card">
        <div class="card-header">
            <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
        </div>
        <div class="card-body">
            <div class="action-buttons">
                <button class="action-btn" onclick="startQuickTimer(25)">
                    <i class="fas fa-play-circle"></i>
                    <span>Quick 25m</span>
                </button>
                
                <button class="action-btn" onclick="window.location.href='?page=focus'">
                    <i class="fas fa-eye-slash"></i>
                    <span>Focus Mode</span>
                </button>
                
                <button class="action-btn" onclick="showBreakOptions()">
                    <i class="fas fa-coffee"></i>
                    <span>Take Break</span>
                </button>
                
                <button class="action-btn" onclick="window.location.href='?page=flashcards'">
                    <i class="fas fa-cards"></i>
                    <span>Flashcards</span>
                </button>
                
                <button class="action-btn" onclick="window.location.href='?page=quizzes'">
                    <i class="fas fa-question-circle"></i>
                    <span>Take Quiz</span>
                </button>
                
                <button class="action-btn" onclick="window.location.href='?page=groups'">
                    <i class="fas fa-users"></i>
                    <span>Study Groups</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Break Options Modal -->
<div id="break-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Take a Break</h3>
        <div class="break-options">
            <button class="break-btn" data-type="nap">
                <i class="fas fa-bed"></i> Nap (20m)
            </button>
            <button class="break-btn" data-type="meal">
                <i class="fas fa-utensils"></i> Meal (30m)
            </button>
            <button class="break-btn" data-type="walk">
                <i class="fas fa-walking"></i> Walk (15m)
            </button>
            <button class="break-btn" data-type="custom">
                <i class="fas fa-clock"></i> Custom
            </button>
        </div>
    </div>
</div>

<!-- D-Day Event Modal -->
<div id="dday-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Add D-Day Event</h3>
        <form id="dday-form">
            <div class="form-group">
                <label for="dday-title">Event Title:</label>
                <input type="text" id="dday-title" class="form-control" placeholder="e.g., Math Final Exam" required>
            </div>
            
            <div class="form-group">
                <label for="dday-description">Description:</label>
                <textarea id="dday-description" class="form-control" placeholder="Additional details..." rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label for="dday-date">Target Date:</label>
                <input type="date" id="dday-date" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="dday-color">Color:</label>
                <div class="color-picker">
                    <input type="color" id="dday-color" value="#6366f1">
                    <div class="color-presets">
                        <div class="color-preset" data-color="#3b82f6" style="background: #3b82f6;"></div>
                        <div class="color-preset" data-color="#10b981" style="background: #10b981;"></div>
                        <div class="color-preset" data-color="#f59e0b" style="background: #f59e0b;"></div>
                        <div class="color-preset" data-color="#ef4444" style="background: #ef4444;"></div>
                        <div class="color-preset" data-color="#8b5cf6" style="background: #8b5cf6;"></div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeDdayModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Event</button>
            </div>
        </form>
    </div>
</div>