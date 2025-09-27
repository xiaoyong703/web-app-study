// Quiz Management System
class QuizManager {
    constructor() {
        this.currentQuiz = null;
        this.currentQuestion = 0;
        this.userAnswers = [];
        this.timeRemaining = 0;
        this.timerInterval = null;
        this.quizzes = [];
        
        this.init();
    }
    
    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.bindEvents();
            this.loadQuizzes();
        });
    }
    
    bindEvents() {
        // Quiz navigation
        document.addEventListener('click', (e) => {
            if (e.target.matches('.start-quiz-btn')) {
                const quizId = e.target.dataset.quizId;
                this.startQuiz(quizId);
            }
            
            if (e.target.matches('.next-question-btn')) {
                this.nextQuestion();
            }
            
            if (e.target.matches('.prev-question-btn')) {
                this.previousQuestion();
            }
            
            if (e.target.matches('.submit-quiz-btn')) {
                this.submitQuiz();
            }
            
            if (e.target.matches('.retry-quiz-btn')) {
                this.retryQuiz();
            }
        });
        
        // Answer selection
        document.addEventListener('change', (e) => {
            if (e.target.matches('input[name="quiz-answer"]')) {
                this.saveAnswer(e.target.value);
            }
        });
        
        // Create quiz form
        const createQuizForm = document.getElementById('create-quiz-form');
        if (createQuizForm) {
            createQuizForm.addEventListener('submit', (e) => this.saveQuiz(e));
        }
        
        // Search and filter
        const searchInput = document.getElementById('search-quizzes');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => this.filterQuizzes(e.target.value));
        }
    }
    
    async loadQuizzes() {
        try {
            const response = await fetch('api/quizzes/get-quizzes.php');
            const data = await response.json();
            
            if (data.success) {
                this.quizzes = data.quizzes;
                this.renderQuizzes();
            }
        } catch (error) {
            console.error('Error loading quizzes:', error);
            this.showNotification('Failed to load quizzes', 'error');
        }
    }
    
    renderQuizzes() {
        const quizzesGrid = document.getElementById('quizzes-grid');
        if (!quizzesGrid) return;
        
        quizzesGrid.innerHTML = this.quizzes.map(quiz => `
            <div class="quiz-card">
                <div class="quiz-header">
                    <h4>${quiz.title}</h4>
                    <span class="quiz-difficulty ${quiz.difficulty}">${quiz.difficulty}</span>
                </div>
                <div class="quiz-info">
                    <p>${quiz.description}</p>
                    <div class="quiz-meta">
                        <span><i class="fas fa-question-circle"></i> ${quiz.question_count} questions</span>
                        <span><i class="fas fa-clock"></i> ${quiz.time_limit || 'No limit'}</span>
                        <span><i class="fas fa-star"></i> ${quiz.best_score || 0}%</span>
                    </div>
                </div>
                <div class="quiz-actions">
                    <button class="btn btn-primary start-quiz-btn" data-quiz-id="${quiz.id}">
                        <i class="fas fa-play"></i> Start Quiz
                    </button>
                    <button class="btn btn-outline view-results-btn" data-quiz-id="${quiz.id}">
                        <i class="fas fa-chart-bar"></i> Results
                    </button>
                </div>
            </div>
        `).join('');
    }
    
    async startQuiz(quizId) {
        try {
            const response = await fetch(`api/quizzes/get-quiz.php?id=${quizId}`);
            const data = await response.json();
            
            if (data.success) {
                this.currentQuiz = data.quiz;
                this.currentQuestion = 0;
                this.userAnswers = new Array(this.currentQuiz.questions.length).fill(null);
                
                this.showQuizInterface();
                this.loadQuestion();
                
                if (this.currentQuiz.time_limit) {
                    this.startTimer(this.currentQuiz.time_limit * 60);
                }
            }
        } catch (error) {
            console.error('Error starting quiz:', error);
            this.showNotification('Failed to start quiz', 'error');
        }
    }
    
    showQuizInterface() {
        const quizInterface = document.getElementById('quiz-interface');
        const quizList = document.getElementById('quiz-list');
        
        if (quizInterface && quizList) {
            quizList.style.display = 'none';
            quizInterface.style.display = 'block';
            
            // Update quiz title and progress
            document.getElementById('quiz-title').textContent = this.currentQuiz.title;
            this.updateProgress();
        }
    }
    
    loadQuestion() {
        const question = this.currentQuiz.questions[this.currentQuestion];
        const questionContainer = document.getElementById('question-container');
        
        if (!questionContainer || !question) return;
        
        questionContainer.innerHTML = `
            <div class="question-header">
                <h3>Question ${this.currentQuestion + 1}</h3>
                <div class="question-points">+${question.points || 1} points</div>
            </div>
            <div class="question-text">${question.question}</div>
            <div class="answer-options">
                ${question.options.map((option, index) => `
                    <label class="answer-option">
                        <input type="radio" name="quiz-answer" value="${index}" 
                               ${this.userAnswers[this.currentQuestion] == index ? 'checked' : ''}>
                        <span class="option-text">${option}</span>
                    </label>
                `).join('')}
            </div>
        `;
        
        this.updateNavigationButtons();
    }
    
    updateNavigationButtons() {
        const prevBtn = document.getElementById('prev-question-btn');
        const nextBtn = document.getElementById('next-question-btn');
        const submitBtn = document.getElementById('submit-quiz-btn');
        
        if (prevBtn) prevBtn.disabled = this.currentQuestion === 0;
        
        if (nextBtn && submitBtn) {
            const isLastQuestion = this.currentQuestion === this.currentQuiz.questions.length - 1;
            nextBtn.style.display = isLastQuestion ? 'none' : 'block';
            submitBtn.style.display = isLastQuestion ? 'block' : 'none';
        }
    }
    
    updateProgress() {
        const progressBar = document.querySelector('.quiz-progress .progress-fill');
        const progressText = document.querySelector('.quiz-progress-text');
        
        if (progressBar && progressText) {
            const progress = ((this.currentQuestion + 1) / this.currentQuiz.questions.length) * 100;
            progressBar.style.width = progress + '%';
            progressText.textContent = `${this.currentQuestion + 1} / ${this.currentQuiz.questions.length}`;
        }
    }
    
    saveAnswer(answerIndex) {
        this.userAnswers[this.currentQuestion] = parseInt(answerIndex);
    }
    
    nextQuestion() {
        if (this.currentQuestion < this.currentQuiz.questions.length - 1) {
            this.currentQuestion++;
            this.loadQuestion();
            this.updateProgress();
        }
    }
    
    previousQuestion() {
        if (this.currentQuestion > 0) {
            this.currentQuestion--;
            this.loadQuestion();
            this.updateProgress();
        }
    }
    
    async submitQuiz() {
        if (!this.validateQuizCompletion()) {
            return;
        }
        
        this.stopTimer();
        
        try {
            const response = await fetch('api/quizzes/submit-quiz.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    quiz_id: this.currentQuiz.id,
                    answers: this.userAnswers,
                    time_taken: this.currentQuiz.time_limit ? 
                        (this.currentQuiz.time_limit * 60 - this.timeRemaining) : null
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showResults(data.results);
                this.updateAchievements();
            }
        } catch (error) {
            console.error('Error submitting quiz:', error);
            this.showNotification('Failed to submit quiz', 'error');
        }
    }
    
    validateQuizCompletion() {
        const unanswered = this.userAnswers.filter(answer => answer === null).length;
        
        if (unanswered > 0) {
            const proceed = confirm(`You have ${unanswered} unanswered questions. Submit anyway?`);
            return proceed;
        }
        
        return true;
    }
    
    showResults(results) {
        const resultsContainer = document.getElementById('quiz-results');
        const quizInterface = document.getElementById('quiz-interface');
        
        if (resultsContainer && quizInterface) {
            quizInterface.style.display = 'none';
            resultsContainer.style.display = 'block';
            
            resultsContainer.innerHTML = `
                <div class="results-header">
                    <h2>Quiz Complete!</h2>
                    <div class="score-display">
                        <div class="score-circle">
                            <span class="score-percentage">${results.percentage}%</span>
                        </div>
                        <div class="score-details">
                            <p>${results.correct_answers} out of ${results.total_questions} correct</p>
                            <p class="score-grade ${results.grade.toLowerCase()}">${results.grade}</p>
                        </div>
                    </div>
                </div>
                
                <div class="results-breakdown">
                    <h3>Question Breakdown</h3>
                    ${this.renderQuestionBreakdown(results.question_results)}
                </div>
                
                <div class="results-actions">
                    <button class="btn btn-primary retry-quiz-btn">
                        <i class="fas fa-redo"></i> Retry Quiz
                    </button>
                    <button class="btn btn-outline" onclick="location.reload()">
                        <i class="fas fa-list"></i> Back to Quizzes
                    </button>
                </div>
            `;
        }
    }
    
    renderQuestionBreakdown(questionResults) {
        return questionResults.map((result, index) => `
            <div class="question-result ${result.correct ? 'correct' : 'incorrect'}">
                <div class="question-number">${index + 1}</div>
                <div class="question-content">
                    <p class="question">${result.question}</p>
                    <div class="answer-comparison">
                        <div class="user-answer">
                            <strong>Your answer:</strong> ${result.user_answer || 'Not answered'}
                            ${result.correct ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>'}
                        </div>
                        ${!result.correct ? `
                            <div class="correct-answer">
                                <strong>Correct answer:</strong> ${result.correct_answer}
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    startTimer(seconds) {
        this.timeRemaining = seconds;
        this.updateTimerDisplay();
        
        this.timerInterval = setInterval(() => {
            this.timeRemaining--;
            this.updateTimerDisplay();
            
            if (this.timeRemaining <= 0) {
                this.submitQuiz();
            }
        }, 1000);
    }
    
    stopTimer() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
            this.timerInterval = null;
        }
    }
    
    updateTimerDisplay() {
        const timerDisplay = document.getElementById('quiz-timer');
        if (timerDisplay) {
            const minutes = Math.floor(this.timeRemaining / 60);
            const seconds = this.timeRemaining % 60;
            timerDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (this.timeRemaining <= 60) {
                timerDisplay.classList.add('warning');
            }
        }
    }
    
    retryQuiz() {
        this.startQuiz(this.currentQuiz.id);
        document.getElementById('quiz-results').style.display = 'none';
    }
    
    async saveQuiz(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const quizData = {
            title: formData.get('title'),
            description: formData.get('description'),
            difficulty: formData.get('difficulty'),
            time_limit: formData.get('time_limit'),
            questions: this.collectQuestions()
        };
        
        try {
            const response = await fetch('api/quizzes/save-quiz.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(quizData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Quiz created successfully!', 'success');
                this.closeCreateModal();
                this.loadQuizzes();
            } else {
                this.showNotification('Failed to create quiz', 'error');
            }
        } catch (error) {
            console.error('Error saving quiz:', error);
            this.showNotification('Error creating quiz', 'error');
        }
    }
    
    collectQuestions() {
        const questions = [];
        const questionElements = document.querySelectorAll('.question-input');
        
        questionElements.forEach(element => {
            const question = element.querySelector('input[name="question"]').value;
            const options = Array.from(element.querySelectorAll('input[name="option"]')).map(input => input.value);
            const correctAnswer = parseInt(element.querySelector('select[name="correct"]').value);
            
            if (question && options.length >= 2) {
                questions.push({
                    question,
                    options,
                    correct_answer: correctAnswer
                });
            }
        });
        
        return questions;
    }
    
    filterQuizzes(searchTerm) {
        const filteredQuizzes = this.quizzes.filter(quiz => 
            quiz.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
            quiz.description.toLowerCase().includes(searchTerm.toLowerCase())
        );
        
        this.renderFilteredQuizzes(filteredQuizzes);
    }
    
    renderFilteredQuizzes(quizzes) {
        const quizzesGrid = document.getElementById('quizzes-grid');
        if (!quizzesGrid) return;
        
        quizzesGrid.innerHTML = quizzes.map(quiz => `
            <div class="quiz-card">
                <div class="quiz-header">
                    <h4>${quiz.title}</h4>
                    <span class="quiz-difficulty ${quiz.difficulty}">${quiz.difficulty}</span>
                </div>
                <div class="quiz-info">
                    <p>${quiz.description}</p>
                    <div class="quiz-meta">
                        <span><i class="fas fa-question-circle"></i> ${quiz.question_count} questions</span>
                        <span><i class="fas fa-clock"></i> ${quiz.time_limit || 'No limit'}</span>
                        <span><i class="fas fa-star"></i> ${quiz.best_score || 0}%</span>
                    </div>
                </div>
                <div class="quiz-actions">
                    <button class="btn btn-primary start-quiz-btn" data-quiz-id="${quiz.id}">
                        <i class="fas fa-play"></i> Start Quiz
                    </button>
                    <button class="btn btn-outline view-results-btn" data-quiz-id="${quiz.id}">
                        <i class="fas fa-chart-bar"></i> Results
                    </button>
                </div>
            </div>
        `).join('');
    }
    
    closeCreateModal() {
        const modal = document.getElementById('create-quiz-modal');
        if (modal) {
            modal.style.display = 'none';
        }
    }
    
    updateAchievements() {
        if (window.achievementsManager) {
            window.achievementsManager.checkAchievements();
        }
    }
    
    updateLanguage() {
        // Update UI text when language changes
        this.renderQuizzes();
    }
    
    showNotification(message, type = 'info') {
        if (typeof showNotification === 'function') {
            showNotification(message, type);
        } else {
            console.log(message);
        }
    }
}

// Global functions
function openCreateQuizModal() {
    const modal = document.getElementById('create-quiz-modal');
    if (modal) {
        modal.style.display = 'block';
    }
}

function closeCreateQuizModal() {
    window.quizManager.closeCreateModal();
}

// Initialize quiz manager
document.addEventListener('DOMContentLoaded', () => {
    window.quizManager = new QuizManager();
});