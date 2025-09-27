<div class="achievements-page">
    <!-- Page Header -->
    <div class="page-header">
        <h1 data-i18n="achievements.title"><i class="fas fa-trophy"></i> Achievements</h1>
        <p data-i18n="achievements.subtitle">Track your progress and unlock rewards as you study</p>
    </div>

    <!-- Achievement Stats -->
    <div class="achievement-stats">
        <div class="stats-overview">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-medal"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number" id="earned-achievements">0</div>
                    <div class="stat-label" data-i18n="achievements.earned">Earned</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number" id="total-points">0</div>
                    <div class="stat-label" data-i18n="achievements.totalPoints">Total Points</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number" id="completion-percentage">0%</div>
                    <div class="stat-label" data-i18n="achievements.completion">Completion</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-fire"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number" id="current-streak">0</div>
                    <div class="stat-label" data-i18n="achievements.streak">Day Streak</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Achievements -->
    <div class="recent-achievements-section">
        <div class="card recent-card">
            <div class="card-header">
                <h2 data-i18n="achievements.recentlyEarned">Recently Earned</h2>
            </div>
            <div class="card-body">
                <div class="recent-achievements-list" id="recent-achievements">
                    <!-- Recent achievements will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Achievement Categories -->
    <div class="achievement-categories">
        <div class="category-tabs">
            <button class="tab-btn active" data-category="all" data-i18n="achievements.all">All</button>
            <button class="tab-btn" data-category="study" data-i18n="achievements.study">Study</button>
            <button class="tab-btn" data-category="streak" data-i18n="achievements.streaks">Streaks</button>
            <button class="tab-btn" data-category="social" data-i18n="achievements.social">Social</button>
            <button class="tab-btn" data-category="mastery" data-i18n="achievements.mastery">Mastery</button>
            <button class="tab-btn" data-category="special" data-i18n="achievements.special">Special</button>
        </div>

        <!-- Achievement Filter -->
        <div class="achievement-filters">
            <select id="achievement-filter" class="form-control">
                <option value="all" data-i18n="achievements.showAll">Show All</option>
                <option value="earned" data-i18n="achievements.earned">Earned</option>
                <option value="locked" data-i18n="achievements.locked">Locked</option>
                <option value="in-progress" data-i18n="achievements.inProgress">In Progress</option>
            </select>
            
            <div class="sort-options">
                <select id="achievement-sort" class="form-control">
                    <option value="recent" data-i18n="achievements.recent">Most Recent</option>
                    <option value="points" data-i18n="achievements.points">Highest Points</option>
                    <option value="rarity" data-i18n="achievements.rarity">Rarity</option>
                    <option value="alphabetical" data-i18n="achievements.alphabetical">Alphabetical</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Achievements Grid -->
    <div class="achievements-grid" id="achievements-grid">
        <!-- Study Achievements -->
        <div class="achievement-category" data-category="study">
            <h3 class="category-title" data-i18n="achievements.studyAchievements">Study Achievements</h3>
            <div class="achievements-row">
                <div class="achievement-item earned" data-achievement="first-session">
                    <div class="achievement-badge">
                        <i class="fas fa-baby"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.firstSteps">First Steps</h4>
                        <p data-i18n="achievements.firstStepsDesc">Complete your first study session</p>
                        <div class="achievement-meta">
                            <span class="points">+10 pts</span>
                            <span class="rarity common" data-i18n="achievements.common">Common</span>
                            <span class="earned-date">Earned 2 days ago</span>
                        </div>
                    </div>
                </div>

                <div class="achievement-item in-progress" data-achievement="study-warrior">
                    <div class="achievement-badge">
                        <i class="fas fa-fire"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.studyWarrior">Study Warrior</h4>
                        <p data-i18n="achievements.studyWarriorDesc">Study for 25+ hours total</p>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 68%;"></div>
                        </div>
                        <div class="progress-text">17/25 hours</div>
                        <div class="achievement-meta">
                            <span class="points">+50 pts</span>
                            <span class="rarity uncommon" data-i18n="achievements.uncommon">Uncommon</span>
                        </div>
                    </div>
                </div>

                <div class="achievement-item locked" data-achievement="marathon-scholar">
                    <div class="achievement-badge">
                        <i class="fas fa-running"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.marathonScholar">Marathon Scholar</h4>
                        <p data-i18n="achievements.marathonScholarDesc">Complete a 4-hour study session</p>
                        <div class="achievement-meta">
                            <span class="points">+100 pts</span>
                            <span class="rarity rare" data-i18n="achievements.rare">Rare</span>
                        </div>
                    </div>
                </div>

                <div class="achievement-item locked" data-achievement="century-club">
                    <div class="achievement-badge">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.centuryClub">Century Club</h4>
                        <p data-i18n="achievements.centuryClubDesc">Study for 100+ hours total</p>
                        <div class="achievement-meta">
                            <span class="points">+200 pts</span>
                            <span class="rarity epic" data-i18n="achievements.epic">Epic</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Streak Achievements -->
        <div class="achievement-category" data-category="streak">
            <h3 class="category-title" data-i18n="achievements.streakAchievements">Streak Achievements</h3>
            <div class="achievements-row">
                <div class="achievement-item earned" data-achievement="week-streak">
                    <div class="achievement-badge">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.weekStreak">Weekly Warrior</h4>
                        <p data-i18n="achievements.weekStreakDesc">Study 7 days in a row</p>
                        <div class="achievement-meta">
                            <span class="points">+30 pts</span>
                            <span class="rarity common" data-i18n="achievements.common">Common</span>
                            <span class="earned-date">Earned 1 week ago</span>
                        </div>
                    </div>
                </div>

                <div class="achievement-item in-progress" data-achievement="month-streak">
                    <div class="achievement-badge">
                        <i class="fas fa-gem"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.monthStreak">Monthly Master</h4>
                        <p data-i18n="achievements.monthStreakDesc">Study 30 days in a row</p>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 43%;"></div>
                        </div>
                        <div class="progress-text">13/30 days</div>
                        <div class="achievement-meta">
                            <span class="points">+150 pts</span>
                            <span class="rarity rare" data-i18n="achievements.rare">Rare</span>
                        </div>
                    </div>
                </div>

                <div class="achievement-item locked" data-achievement="year-streak">
                    <div class="achievement-badge">
                        <i class="fas fa-infinity"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.yearStreak">Legendary Learner</h4>
                        <p data-i18n="achievements.yearStreakDesc">Study 365 days in a row</p>
                        <div class="achievement-meta">
                            <span class="points">+1000 pts</span>
                            <span class="rarity legendary" data-i18n="achievements.legendary">Legendary</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mastery Achievements -->
        <div class="achievement-category" data-category="mastery">
            <h3 class="category-title" data-i18n="achievements.masteryAchievements">Mastery Achievements</h3>
            <div class="achievements-row">
                <div class="achievement-item earned" data-achievement="flashcard-novice">
                    <div class="achievement-badge">
                        <i class="fas fa-cards"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.flashcardNovice">Card Novice</h4>
                        <p data-i18n="achievements.flashcardNoviceDesc">Review 50 flashcards</p>
                        <div class="achievement-meta">
                            <span class="points">+25 pts</span>
                            <span class="rarity common" data-i18n="achievements.common">Common</span>
                            <span class="earned-date">Earned 3 days ago</span>
                        </div>
                    </div>
                </div>

                <div class="achievement-item in-progress" data-achievement="quiz-master">
                    <div class="achievement-badge">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.quizMaster">Quiz Master</h4>
                        <p data-i18n="achievements.quizMasterDesc">Score 90%+ on 5 different quizzes</p>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 60%;"></div>
                        </div>
                        <div class="progress-text">3/5 quizzes</div>
                        <div class="achievement-meta">
                            <span class="points">+75 pts</span>
                            <span class="rarity uncommon" data-i18n="achievements.uncommon">Uncommon</span>
                        </div>
                    </div>
                </div>

                <div class="achievement-item locked" data-achievement="perfect-score">
                    <div class="achievement-badge">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.perfectScore">Perfectionist</h4>
                        <p data-i18n="achievements.perfectScoreDesc">Get 100% on any quiz</p>
                        <div class="achievement-meta">
                            <span class="points">+50 pts</span>
                            <span class="rarity uncommon" data-i18n="achievements.uncommon">Uncommon</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Achievements -->
        <div class="achievement-category" data-category="social">
            <h3 class="category-title" data-i18n="achievements.socialAchievements">Social Achievements</h3>
            <div class="achievements-row">
                <div class="achievement-item earned" data-achievement="group-joiner">
                    <div class="achievement-badge">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.groupJoiner">Team Player</h4>
                        <p data-i18n="achievements.groupJoinerDesc">Join your first study group</p>
                        <div class="achievement-meta">
                            <span class="points">+20 pts</span>
                            <span class="rarity common" data-i18n="achievements.common">Common</span>
                            <span class="earned-date">Earned 5 days ago</span>
                        </div>
                    </div>
                </div>

                <div class="achievement-item locked" data-achievement="group-leader">
                    <div class="achievement-badge">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.groupLeader">Group Leader</h4>
                        <p data-i18n="achievements.groupLeaderDesc">Create and manage a study group</p>
                        <div class="achievement-meta">
                            <span class="points">+40 pts</span>
                            <span class="rarity uncommon" data-i18n="achievements.uncommon">Uncommon</span>
                        </div>
                    </div>
                </div>

                <div class="achievement-item locked" data-achievement="mentor">
                    <div class="achievement-badge">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.mentor">Mentor</h4>
                        <p data-i18n="achievements.mentorDesc">Help 10 students in study groups</p>
                        <div class="achievement-meta">
                            <span class="points">+100 pts</span>
                            <span class="rarity rare" data-i18n="achievements.rare">Rare</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Special Achievements -->
        <div class="achievement-category" data-category="special">
            <h3 class="category-title" data-i18n="achievements.specialAchievements">Special Achievements</h3>
            <div class="achievements-row">
                <div class="achievement-item earned" data-achievement="early-bird">
                    <div class="achievement-badge">
                        <i class="fas fa-sun"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.earlyBird">Early Bird</h4>
                        <p data-i18n="achievements.earlyBirdDesc">Study before 7 AM</p>
                        <div class="achievement-meta">
                            <span class="points">+15 pts</span>
                            <span class="rarity common" data-i18n="achievements.common">Common</span>
                            <span class="earned-date">Earned 1 day ago</span>
                        </div>
                    </div>
                </div>

                <div class="achievement-item locked" data-achievement="night-owl">
                    <div class="achievement-badge">
                        <i class="fas fa-moon"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.nightOwl">Night Owl</h4>
                        <p data-i18n="achievements.nightOwlDesc">Study after 11 PM</p>
                        <div class="achievement-meta">
                            <span class="points">+15 pts</span>
                            <span class="rarity common" data-i18n="achievements.common">Common</span>
                        </div>
                    </div>
                </div>

                <div class="achievement-item locked" data-achievement="holiday-scholar">
                    <div class="achievement-badge">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.holidayScholar">Holiday Scholar</h4>
                        <p data-i18n="achievements.holidayScholarDesc">Study on a holiday</p>
                        <div class="achievement-meta">
                            <span class="points">+25 pts</span>
                            <span class="rarity uncommon" data-i18n="achievements.uncommon">Uncommon</span>
                        </div>
                    </div>
                </div>

                <div class="achievement-item locked" data-achievement="time-traveler">
                    <div class="achievement-badge">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="achievement-content">
                        <h4 data-i18n="achievements.timeTraveler">Time Traveler</h4>
                        <p data-i18n="achievements.timeTravelerDesc">Study across different time zones</p>
                        <div class="achievement-meta">
                            <span class="points">+50 pts</span>
                            <span class="rarity rare" data-i18n="achievements.rare">Rare</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Achievement Leaderboard -->
    <div class="leaderboard-section">
        <div class="card leaderboard-card">
            <div class="card-header">
                <h3 data-i18n="achievements.leaderboard">Achievement Leaderboard</h3>
                <div class="leaderboard-filters">
                    <select id="leaderboard-period" class="form-control">
                        <option value="week" data-i18n="achievements.thisWeek">This Week</option>
                        <option value="month" data-i18n="achievements.thisMonth">This Month</option>
                        <option value="all" data-i18n="achievements.allTime">All Time</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="leaderboard-list" id="achievement-leaderboard">
                    <!-- Leaderboard entries will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Achievement Details Modal -->
<div id="achievement-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="achievement-details" id="achievement-details">
            <!-- Achievement details will be loaded here -->
        </div>
    </div>
</div>

<!-- Achievement Unlock Animation -->
<div id="achievement-unlock" class="achievement-unlock-overlay" style="display: none;">
    <div class="unlock-animation">
        <div class="unlock-badge">
            <i class="fas fa-trophy"></i>
        </div>
        <div class="unlock-content">
            <h2 data-i18n="achievements.unlocked">Achievement Unlocked!</h2>
            <h3 id="unlock-title">Achievement Title</h3>
            <p id="unlock-description">Achievement description</p>
            <div class="unlock-points">
                <span data-i18n="achievements.earned">Earned</span>
                <span id="unlock-points-value">+50</span>
                <span data-i18n="achievements.points">points</span>
            </div>
        </div>
        <button class="unlock-close" onclick="closeAchievementUnlock()">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>