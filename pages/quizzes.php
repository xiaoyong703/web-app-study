<div class="quizzes-page">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-question-circle"></i> Quizzes</h1>
        <p>Test your knowledge and track your progress</p>
    </div>

    <!-- Quiz Taking Section -->
    <div class="quiz-section" id="quiz-section" style="display: none;">
        <div class="card quiz-card">
            <div class="card-header">
                <div class="quiz-info">
                    <h2 id="quiz-title">Quiz Title</h2>
                    <div class="quiz-meta">
                        <span id="quiz-subject">Subject</span>
                        <span id="quiz-timer">⏰ 15:00</span>
                    </div>
                </div>
                <div class="quiz-progress">
                    <span id="question-counter">1 / 10</span>
                    <div class="progress-bar">
                        <div id="quiz-progress-fill" class="progress-fill"></div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="question-container">
                    <div class="question-number">Question <span id="current-question-num">1</span></div>
                    <div class="question-text" id="question-text">Loading question...</div>
                    
                    <div class="answers-container" id="answers-container">
                        <!-- Answer options will be inserted here -->
                    </div>
                    
                    <div class="quiz-controls">
                        <button class="btn btn-outline" onclick="exitQuiz()">
                            <i class="fas fa-times"></i> Exit Quiz
                        </button>
                        <div class="nav-buttons">
                            <button class="btn btn-secondary" id="prev-btn" onclick="previousQuestion()" disabled>
                                <i class="fas fa-arrow-left"></i> Previous
                            </button>
                            <button class="btn btn-primary" id="next-btn" onclick="nextQuestion()" disabled>
                                Next <i class="fas fa-arrow-right"></i>
                            </button>
                            <button class="btn btn-success" id="submit-btn" onclick="submitQuiz()" style="display: none;">
                                <i class="fas fa-check"></i> Submit Quiz
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz Overview Section -->
    <div class="quizzes-overview" id="quizzes-overview">
        <div class="quizzes-header">
            <h2>Available Quizzes</h2>
            <button class="btn btn-primary" onclick="openCreateQuizModal()">
                <i class="fas fa-plus"></i> Create Quiz
            </button>
        </div>

        <!-- Quiz Filters -->
        <div class="quiz-filters">
            <select id="subject-filter" class="form-control">
                <option value="">All Subjects</option>
                <option value="Math">Math</option>
                <option value="Science">Science</option>
                <option value="English">English</option>
                <option value="History">History</option>
                <option value="Other">Other</option>
            </select>
            <select id="difficulty-filter" class="form-control">
                <option value="">All Difficulties</option>
                <option value="easy">Easy</option>
                <option value="medium">Medium</option>
                <option value="hard">Hard</option>
            </select>
            <input type="text" id="search-quizzes" class="form-control" placeholder="Search quizzes...">
        </div>

        <div class="quizzes-grid" id="quizzes-grid">
            <?php
            try {
                $stmt = $pdo->prepare("SELECT q.*, COUNT(qq.id) as question_count,
                                     AVG(qa.percentage) as avg_score,
                                     COUNT(DISTINCT qa.user_id) as attempts_count
                                     FROM quizzes q 
                                     LEFT JOIN quiz_questions qq ON q.id = qq.quiz_id 
                                     LEFT JOIN quiz_attempts qa ON q.id = qa.quiz_id
                                     WHERE q.user_id = ? OR q.is_public = 1 
                                     GROUP BY q.id 
                                     ORDER BY q.created_at DESC");
                $stmt->execute([$_SESSION['user_id']]);
                $quizzes = $stmt->fetchAll();
                
                if ($quizzes):
                    foreach ($quizzes as $quiz):
                        // Get user's best attempt
                        $stmt2 = $pdo->prepare("SELECT MAX(percentage) as best_score FROM quiz_attempts WHERE quiz_id = ? AND user_id = ?");
                        $stmt2->execute([$quiz['id'], $_SESSION['user_id']]);
                        $userBest = $stmt2->fetch();
            ?>
            <div class="quiz-card" data-quiz-id="<?php echo $quiz['id']; ?>">
                <div class="quiz-header">
                    <h3><?php echo htmlspecialchars($quiz['title']); ?></h3>
                    <div class="quiz-meta">
                        <span class="question-count"><?php echo $quiz['question_count']; ?> questions</span>
                        <span class="subject-tag"><?php echo htmlspecialchars($quiz['subject']); ?></span>
                        <?php if ($quiz['time_limit']): ?>
                        <span class="time-limit">⏰ <?php echo $quiz['time_limit']; ?>m</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="quiz-description">
                    <p><?php echo htmlspecialchars($quiz['description']); ?></p>
                </div>
                
                <div class="quiz-stats">
                    <?php if ($userBest && $userBest['best_score']): ?>
                    <div class="user-best">
                        <strong>Your Best:</strong> <?php echo round($userBest['best_score'], 1); ?>%
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($quiz['avg_score']): ?>
                    <div class="avg-score">
                        <strong>Average:</strong> <?php echo round($quiz['avg_score'], 1); ?>%
                    </div>
                    <?php endif; ?>
                    
                    <div class="attempts">
                        <strong>Attempts:</strong> <?php echo $quiz['attempts_count']; ?>
                    </div>
                </div>
                
                <div class="quiz-actions">
                    <?php if ($quiz['question_count'] > 0): ?>
                    <button class="btn btn-primary btn-sm" onclick="startQuiz(<?php echo $quiz['id']; ?>)">
                        <i class="fas fa-play"></i> Take Quiz
                    </button>
                    <?php else: ?>
                    <span class="no-questions">No questions yet</span>
                    <?php endif; ?>
                    
                    <?php if ($quiz['user_id'] === $_SESSION['user_id']): ?>
                    <button class="btn btn-outline btn-sm" onclick="editQuiz(<?php echo $quiz['id']; ?>)">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-outline btn-sm" onclick="deleteQuiz(<?php echo $quiz['id']; ?>)">
                        <i class="fas fa-trash"></i>
                    </button>
                    <?php endif; ?>
                </div>
                
                <?php if ($quiz['is_public']): ?>
                <div class="public-badge">
                    <i class="fas fa-globe"></i> Public
                </div>
                <?php endif; ?>
            </div>
            <?php 
                    endforeach;
                else:
            ?>
            <div class="empty-state">
                <i class="fas fa-question-circle"></i>
                <h3>No quizzes available</h3>
                <p>Create your first quiz or browse public quizzes</p>
                <button class="btn btn-primary" onclick="openCreateQuizModal()">
                    <i class="fas fa-plus"></i> Create Your First Quiz
                </button>
            </div>
            <?php endif; ?>
            
            <?php } catch(Exception $e) { ?>
                <div class="error-state">
                    <p>Unable to load quizzes. Please try again.</p>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Recent Quiz Results -->
    <div class="recent-results">
        <h2>Your Recent Results</h2>
        <div class="results-list" id="results-list">
            <?php
            try {
                $stmt = $pdo->prepare("SELECT qa.*, q.title, q.subject 
                                     FROM quiz_attempts qa 
                                     JOIN quizzes q ON qa.quiz_id = q.id 
                                     WHERE qa.user_id = ? 
                                     ORDER BY qa.completed_at DESC 
                                     LIMIT 5");
                $stmt->execute([$_SESSION['user_id']]);
                $results = $stmt->fetchAll();
                
                foreach ($results as $result):
            ?>
            <div class="result-item">
                <div class="result-info">
                    <h4><?php echo htmlspecialchars($result['title']); ?></h4>
                    <span class="subject"><?php echo htmlspecialchars($result['subject']); ?></span>
                </div>
                <div class="result-score">
                    <span class="percentage <?php echo $result['percentage'] >= 70 ? 'good' : ($result['percentage'] >= 50 ? 'ok' : 'poor'); ?>">
                        <?php echo round($result['percentage'], 1); ?>%
                    </span>
                    <small><?php echo $result['score']; ?>/<?php echo $result['total_points']; ?> points</small>
                </div>
                <div class="result-date">
                    <?php echo date('M j, Y', strtotime($result['completed_at'])); ?>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($results)): ?>
            <div class="no-results">
                <p>No quiz attempts yet. Take your first quiz to see results here!</p>
            </div>
            <?php endif; ?>
            
            <?php } catch(Exception $e) { ?>
                <div class="error-loading">
                    <p>Unable to load recent results.</p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Create/Edit Quiz Modal -->
<div id="quiz-modal" class="modal">
    <div class="modal-content large">
        <span class="close">&times;</span>
        <h3 id="quiz-modal-title">Create Quiz</h3>
        <form id="quiz-form">
            <input type="hidden" id="quiz-id" value="">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="quiz-title-input">Quiz Title:</label>
                    <input type="text" id="quiz-title-input" class="form-control" placeholder="e.g., World War II History" required>
                </div>
                
                <div class="form-group">
                    <label for="quiz-subject-input">Subject:</label>
                    <select id="quiz-subject-input" class="form-control">
                        <option value="Math">Math</option>
                        <option value="Science">Science</option>
                        <option value="English">English</option>
                        <option value="History">History</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="quiz-description-input">Description:</label>
                <textarea id="quiz-description-input" class="form-control" placeholder="Describe what this quiz covers..." rows="3"></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="quiz-time-limit">Time Limit (minutes):</label>
                    <input type="number" id="quiz-time-limit" class="form-control" placeholder="Leave empty for no limit" min="1">
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="quiz-public"> Make this quiz public
                    </label>
                </div>
            </div>
            
            <div class="questions-section">
                <h4>Questions</h4>
                <div id="questions-container">
                    <!-- Questions will be added here -->
                </div>
                
                <button type="button" class="btn btn-outline" onclick="addQuestionForm()">
                    <i class="fas fa-plus"></i> Add Question
                </button>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeQuizModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Quiz</button>
            </div>
        </form>
    </div>
</div>

<!-- Quiz Results Modal -->
<div id="quiz-results-modal" class="modal">
    <div class="modal-content">
        <h3>Quiz Complete!</h3>
        <div class="quiz-results">
            <div class="result-summary">
                <div class="final-score">
                    <span class="score-percentage" id="final-percentage">0%</span>
                    <span class="score-fraction" id="final-fraction">0/0</span>
                </div>
                <div class="result-message" id="result-message">Great job!</div>
            </div>
            
            <div class="result-details">
                <div class="detail-item">
                    <span class="detail-label">Correct Answers:</span>
                    <span class="detail-value" id="correct-count">0</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Time Taken:</span>
                    <span class="detail-value" id="time-taken">0m 0s</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Points Earned:</span>
                    <span class="detail-value" id="points-earned">0</span>
                </div>
            </div>
            
            <div class="achievements-earned" id="quiz-achievements-earned" style="display: none;">
                <h4>🎉 Achievements Earned!</h4>
                <div id="quiz-achievements-list"></div>
            </div>
        </div>
        
        <div class="results-actions">
            <button class="btn btn-outline" onclick="closeQuizResults()">
                <i class="fas fa-home"></i> Back to Quizzes
            </button>
            <button class="btn btn-primary" onclick="retakeQuiz()">
                <i class="fas fa-redo"></i> Retake Quiz
            </button>
        </div>
    </div>
</div>