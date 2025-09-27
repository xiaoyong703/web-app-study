<div class="flashcards-page">
    <!-- Flashcard Sets Overview -->
    <div class="page-header">
        <h1><i class="fas fa-cards"></i> Flashcards</h1>
        <p>Create, study, and master your flashcard sets</p>
    </div>

    <!-- Study Mode Section -->
    <div class="study-section" id="study-section" style="display: none;">
        <div class="card study-card">
            <div class="card-header">
                <h2>Study Session</h2>
                <div class="study-progress">
                    <span id="study-counter">1 / 10</span>
                    <div class="progress-bar">
                        <div id="study-progress-fill" class="progress-fill"></div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="flashcard" id="current-flashcard">
                    <div class="flashcard-inner">
                        <div class="flashcard-front">
                            <div class="card-content">
                                <h3>Question</h3>
                                <p id="question-text">Loading...</p>
                            </div>
                            <button class="flip-btn" onclick="flipCard()">
                                <i class="fas fa-sync-alt"></i> Show Answer
                            </button>
                        </div>
                        <div class="flashcard-back">
                            <div class="card-content">
                                <h3>Answer</h3>
                                <p id="answer-text">Loading...</p>
                                <div class="hint" id="hint-text" style="display: none;">
                                    <small><strong>Hint:</strong> <span id="hint-content"></span></small>
                                </div>
                            </div>
                            <div class="difficulty-buttons">
                                <button class="diff-btn easy" onclick="markCard('easy')">
                                    <i class="fas fa-smile"></i> Easy
                                </button>
                                <button class="diff-btn medium" onclick="markCard('medium')">
                                    <i class="fas fa-meh"></i> Medium
                                </button>
                                <button class="diff-btn hard" onclick="markCard('hard')">
                                    <i class="fas fa-frown"></i> Hard
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="study-controls">
                    <button class="btn btn-outline" onclick="exitStudy()">
                        <i class="fas fa-times"></i> Exit Study
                    </button>
                    <button class="btn btn-primary" id="next-card-btn" onclick="nextCard()" disabled>
                        Next Card <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sets Overview Section -->
    <div class="sets-section" id="sets-section">
        <div class="sets-header">
            <h2>Your Flashcard Sets</h2>
            <button class="btn btn-primary" onclick="openCreateSetModal()">
                <i class="fas fa-plus"></i> Create Set
            </button>
        </div>

        <div class="sets-grid" id="sets-grid">
            <?php
            try {
                $stmt = $pdo->prepare("SELECT fs.*, COUNT(f.id) as card_count 
                                     FROM flashcard_sets fs 
                                     LEFT JOIN flashcards f ON fs.id = f.set_id 
                                     WHERE fs.user_id = ? OR fs.is_public = 1 
                                     GROUP BY fs.id 
                                     ORDER BY fs.created_at DESC");
                $stmt->execute([$_SESSION['user_id']]);
                $sets = $stmt->fetchAll();
                
                if ($sets):
                    foreach ($sets as $set):
            ?>
            <div class="set-card" data-set-id="<?php echo $set['id']; ?>">
                <div class="set-header">
                    <h3><?php echo htmlspecialchars($set['title']); ?></h3>
                    <div class="set-meta">
                        <span class="card-count"><?php echo $set['card_count']; ?> cards</span>
                        <span class="subject-tag"><?php echo htmlspecialchars($set['subject']); ?></span>
                    </div>
                </div>
                
                <div class="set-description">
                    <p><?php echo htmlspecialchars($set['description']); ?></p>
                </div>
                
                <div class="set-actions">
                    <button class="btn btn-primary btn-sm" onclick="startStudySession(<?php echo $set['id']; ?>)">
                        <i class="fas fa-play"></i> Study
                    </button>
                    
                    <?php if ($set['user_id'] === $_SESSION['user_id']): ?>
                    <button class="btn btn-outline btn-sm" onclick="editSet(<?php echo $set['id']; ?>)">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-outline btn-sm" onclick="deleteSet(<?php echo $set['id']; ?>)">
                        <i class="fas fa-trash"></i>
                    </button>
                    <?php endif; ?>
                </div>
                
                <?php if ($set['is_public']): ?>
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
                <i class="fas fa-cards"></i>
                <h3>No flashcard sets yet</h3>
                <p>Create your first flashcard set to start studying</p>
                <button class="btn btn-primary" onclick="openCreateSetModal()">
                    <i class="fas fa-plus"></i> Create Your First Set
                </button>
            </div>
            <?php endif; ?>
            
            <?php } catch(Exception $e) { ?>
                <div class="error-state">
                    <p>Unable to load flashcard sets. Please try again.</p>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Browse Public Sets -->
    <div class="public-sets-section">
        <h2>Browse Public Sets</h2>
        <div class="filter-controls">
            <select id="subject-filter" class="form-control">
                <option value="">All Subjects</option>
                <option value="Math">Math</option>
                <option value="Science">Science</option>
                <option value="English">English</option>
                <option value="History">History</option>
                <option value="Other">Other</option>
            </select>
            <input type="text" id="search-sets" class="form-control" placeholder="Search sets...">
        </div>
        
        <div class="public-sets-grid" id="public-sets-grid">
            <!-- Public sets will be loaded here via JavaScript -->
        </div>
    </div>
</div>

<!-- Create/Edit Set Modal -->
<div id="set-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3 id="set-modal-title">Create Flashcard Set</h3>
        <form id="set-form">
            <input type="hidden" id="set-id" value="">
            
            <div class="form-group">
                <label for="set-title">Set Title:</label>
                <input type="text" id="set-title" class="form-control" placeholder="e.g., Spanish Vocabulary" required>
            </div>
            
            <div class="form-group">
                <label for="set-description">Description:</label>
                <textarea id="set-description" class="form-control" placeholder="Describe what this set covers..." rows="3"></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="set-subject">Subject:</label>
                    <select id="set-subject" class="form-control">
                        <option value="Math">Math</option>
                        <option value="Science">Science</option>
                        <option value="English">English</option>
                        <option value="History">History</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="set-public"> Make this set public
                    </label>
                </div>
            </div>
            
            <div class="cards-section">
                <h4>Flashcards</h4>
                <div id="cards-container">
                    <!-- Cards will be added here -->
                </div>
                
                <button type="button" class="btn btn-outline" onclick="addCardForm()">
                    <i class="fas fa-plus"></i> Add Card
                </button>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeSetModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Set</button>
            </div>
        </form>
    </div>
</div>

<!-- Study Complete Modal -->
<div id="study-complete-modal" class="modal">
    <div class="modal-content">
        <h3>Study Session Complete!</h3>
        <div class="study-results">
            <div class="result-stat">
                <span class="result-number" id="cards-studied">0</span>
                <span class="result-label">Cards Studied</span>
            </div>
            <div class="result-stat">
                <span class="result-number" id="accuracy-rate">0%</span>
                <span class="result-label">Accuracy</span>
            </div>
            <div class="result-stat">
                <span class="result-number" id="time-spent">0m</span>
                <span class="result-label">Time Spent</span>
            </div>
        </div>
        
        <div class="achievements-earned" id="achievements-earned" style="display: none;">
            <h4>🎉 Achievements Earned!</h4>
            <div id="achievements-list"></div>
        </div>
        
        <div class="study-actions">
            <button class="btn btn-outline" onclick="closeStudyComplete()">
                <i class="fas fa-home"></i> Back to Sets
            </button>
            <button class="btn btn-primary" onclick="studyAgain()">
                <i class="fas fa-redo"></i> Study Again
            </button>
        </div>
    </div>
</div>