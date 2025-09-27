// Flashcards functionality
class FlashcardsManager {
    constructor() {
        this.currentSet = null;
        this.currentCards = [];
        this.currentCardIndex = 0;
        this.studyResults = {
            studied: 0,
            correct: 0,
            startTime: null
        };
        this.isFlipped = false;
        
        this.init();
    }
    
    init() {
        // Bind event listeners
        document.addEventListener('DOMContentLoaded', () => {
            this.bindEvents();
            this.loadPublicSets();
        });
    }
    
    bindEvents() {
        // Set form submission
        const setForm = document.getElementById('set-form');
        if (setForm) {
            setForm.addEventListener('submit', (e) => this.saveSet(e));
        }
        
        // Search and filter
        const searchInput = document.getElementById('search-sets');
        if (searchInput) {
            searchInput.addEventListener('input', () => this.filterSets());
        }
        
        const subjectFilter = document.getElementById('subject-filter');
        if (subjectFilter) {
            subjectFilter.addEventListener('change', () => this.filterSets());
        }
        
        // Keyboard shortcuts for study mode
        document.addEventListener('keydown', (e) => {
            if (document.getElementById('study-section').style.display !== 'none') {
                switch(e.key) {
                    case ' ':
                        e.preventDefault();
                        this.flipCard();
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        this.markCard('hard');
                        break;
                    case 'ArrowDown':
                        e.preventDefault();
                        this.markCard('medium');
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        this.markCard('easy');
                        break;
                    case 'Escape':
                        e.preventDefault();
                        this.exitStudy();
                        break;
                }
            }
        });
    }
    
    async startStudySession(setId) {
        try {
            const response = await fetch(`api/flashcards/get-cards.php?set_id=${setId}`);
            const result = await response.json();
            
            if (result.success && result.cards.length > 0) {
                this.currentSet = result.set;
                this.currentCards = this.shuffleArray(result.cards);
                this.currentCardIndex = 0;
                this.studyResults = {
                    studied: 0,
                    correct: 0,
                    startTime: new Date()
                };
                
                this.showStudyMode();
                this.displayCurrentCard();
            } else {
                this.showNotification('No cards found in this set', 'error');
            }
        } catch (error) {
            console.error('Error starting study session:', error);
            this.showNotification('Failed to start study session', 'error');
        }
    }
    
    showStudyMode() {
        document.getElementById('sets-section').style.display = 'none';
        document.getElementById('study-section').style.display = 'block';
        
        // Update study header
        const studyCounter = document.getElementById('study-counter');
        studyCounter.textContent = `${this.currentCardIndex + 1} / ${this.currentCards.length}`;
        
        this.updateStudyProgress();
    }
    
    displayCurrentCard() {
        if (this.currentCardIndex >= this.currentCards.length) {
            this.completeStudySession();
            return;
        }
        
        const card = this.currentCards[this.currentCardIndex];
        const flashcard = document.getElementById('current-flashcard');
        
        // Reset card flip state
        this.isFlipped = false;
        flashcard.classList.remove('flipped');
        
        // Update card content
        document.getElementById('question-text').textContent = card.question;
        document.getElementById('answer-text').textContent = card.answer;
        
        const hintElement = document.getElementById('hint-text');
        const hintContent = document.getElementById('hint-content');
        
        if (card.hint) {
            hintContent.textContent = card.hint;
            hintElement.style.display = 'block';
        } else {
            hintElement.style.display = 'none';
        }
        
        // Update progress
        const studyCounter = document.getElementById('study-counter');
        studyCounter.textContent = `${this.currentCardIndex + 1} / ${this.currentCards.length}`;
        
        this.updateStudyProgress();
        
        // Disable next button until card is marked
        document.getElementById('next-card-btn').disabled = true;
    }
    
    flipCard() {
        const flashcard = document.getElementById('current-flashcard');
        this.isFlipped = !this.isFlipped;
        
        if (this.isFlipped) {
            flashcard.classList.add('flipped');
        } else {
            flashcard.classList.remove('flipped');
        }
    }
    
    markCard(difficulty) {
        if (!this.isFlipped) {
            this.flipCard();
            return;
        }
        
        const card = this.currentCards[this.currentCardIndex];
        
        // Record the response
        this.studyResults.studied++;
        if (difficulty === 'easy' || difficulty === 'medium') {
            this.studyResults.correct++;
        }
        
        // Save card review
        this.saveCardReview(card.id, difficulty);
        
        // Enable next button
        document.getElementById('next-card-btn').disabled = false;
        
        // Visual feedback
        const buttons = document.querySelectorAll('.diff-btn');
        buttons.forEach(btn => btn.classList.remove('selected'));
        document.querySelector(`.diff-btn.${difficulty}`).classList.add('selected');
    }
    
    async saveCardReview(cardId, difficulty) {
        try {
            await fetch('api/flashcards/review-card.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    card_id: cardId,
                    difficulty: difficulty
                })
            });
        } catch (error) {
            console.error('Error saving card review:', error);
        }
    }
    
    nextCard() {
        this.currentCardIndex++;
        
        if (this.currentCardIndex >= this.currentCards.length) {
            this.completeStudySession();
        } else {
            this.displayCurrentCard();
        }
    }
    
    updateStudyProgress() {
        const progressFill = document.getElementById('study-progress-fill');
        const progress = ((this.currentCardIndex) / this.currentCards.length) * 100;
        progressFill.style.width = `${progress}%`;
    }
    
    completeStudySession() {
        const endTime = new Date();
        const timeSpent = Math.round((endTime - this.studyResults.startTime) / 1000 / 60); // minutes
        const accuracy = this.studyResults.studied > 0 
            ? Math.round((this.studyResults.correct / this.studyResults.studied) * 100) 
            : 0;
        
        // Update completion modal
        document.getElementById('cards-studied').textContent = this.studyResults.studied;
        document.getElementById('accuracy-rate').textContent = `${accuracy}%`;
        document.getElementById('time-spent').textContent = `${timeSpent}m`;
        
        // Save study session
        this.saveStudySession(timeSpent, accuracy);
        
        // Show completion modal
        document.getElementById('study-complete-modal').style.display = 'block';
    }
    
    async saveStudySession(timeSpent, accuracy) {
        try {
            await fetch('api/flashcards/save-study-session.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    set_id: this.currentSet.id,
                    cards_studied: this.studyResults.studied,
                    time_spent: timeSpent,
                    accuracy: accuracy
                })
            });
        } catch (error) {
            console.error('Error saving study session:', error);
        }
    }
    
    exitStudy() {
        if (confirm('Are you sure you want to exit? Your progress will be lost.')) {
            this.showSetsView();
        }
    }
    
    showSetsView() {
        document.getElementById('study-section').style.display = 'none';
        document.getElementById('sets-section').style.display = 'block';
    }
    
    closeStudyComplete() {
        document.getElementById('study-complete-modal').style.display = 'none';
        this.showSetsView();
    }
    
    studyAgain() {
        document.getElementById('study-complete-modal').style.display = 'none';
        this.startStudySession(this.currentSet.id);
    }
    
    // Set management functions
    openCreateSetModal() {
        document.getElementById('set-modal-title').textContent = 'Create Flashcard Set';
        document.getElementById('set-id').value = '';
        document.getElementById('set-form').reset();
        document.getElementById('cards-container').innerHTML = '';
        this.addCardForm();
        document.getElementById('set-modal').style.display = 'block';
    }
    
    closeSetModal() {
        document.getElementById('set-modal').style.display = 'none';
    }
    
    addCardForm() {
        const container = document.getElementById('cards-container');
        const cardCount = container.children.length;
        
        const cardForm = document.createElement('div');
        cardForm.className = 'card-form';
        cardForm.innerHTML = `
            <div class="card-form-header">
                <h5>Card ${cardCount + 1}</h5>
                <button type="button" class="btn-remove" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Question:</label>
                    <textarea class="form-control card-question" placeholder="Enter the question..." rows="2" required></textarea>
                </div>
                <div class="form-group">
                    <label>Answer:</label>
                    <textarea class="form-control card-answer" placeholder="Enter the answer..." rows="2" required></textarea>
                </div>
            </div>
            <div class="form-group">
                <label>Hint (optional):</label>
                <input type="text" class="form-control card-hint" placeholder="Enter a helpful hint...">
            </div>
            <div class="form-group">
                <label>Difficulty:</label>
                <select class="form-control card-difficulty">
                    <option value="easy">Easy</option>
                    <option value="medium" selected>Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>
        `;
        
        container.appendChild(cardForm);
    }
    
    async saveSet(e) {
        e.preventDefault();
        
        const setData = {
            id: document.getElementById('set-id').value,
            title: document.getElementById('set-title').value,
            description: document.getElementById('set-description').value,
            subject: document.getElementById('set-subject').value,
            is_public: document.getElementById('set-public').checked
        };
        
        // Collect cards
        const cardForms = document.querySelectorAll('.card-form');
        const cards = [];
        
        cardForms.forEach((form, index) => {
            const question = form.querySelector('.card-question').value.trim();
            const answer = form.querySelector('.card-answer').value.trim();
            
            if (question && answer) {
                cards.push({
                    question: question,
                    answer: answer,
                    hint: form.querySelector('.card-hint').value.trim(),
                    difficulty: form.querySelector('.card-difficulty').value
                });
            }
        });
        
        if (cards.length === 0) {
            this.showNotification('Please add at least one card', 'error');
            return;
        }
        
        try {
            const response = await fetch('api/flashcards/save-set.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ set: setData, cards: cards })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showNotification('Flashcard set saved successfully!', 'success');
                this.closeSetModal();
                location.reload(); // Refresh to show new set
            } else {
                this.showNotification(result.message || 'Failed to save set', 'error');
            }
        } catch (error) {
            console.error('Error saving set:', error);
            this.showNotification('Error saving set', 'error');
        }
    }
    
    async deleteSet(setId) {
        if (!confirm('Are you sure you want to delete this flashcard set? This action cannot be undone.')) {
            return;
        }
        
        try {
            const response = await fetch('api/flashcards/delete-set.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ set_id: setId })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showNotification('Set deleted successfully', 'success');
                location.reload();
            } else {
                this.showNotification('Failed to delete set', 'error');
            }
        } catch (error) {
            console.error('Error deleting set:', error);
            this.showNotification('Error deleting set', 'error');
        }
    }
    
    async loadPublicSets() {
        try {
            const response = await fetch('api/flashcards/get-public-sets.php');
            const result = await response.json();
            
            if (result.success) {
                this.renderPublicSets(result.sets);
            }
        } catch (error) {
            console.error('Error loading public sets:', error);
        }
    }
    
    renderPublicSets(sets) {
        const container = document.getElementById('public-sets-grid');
        
        container.innerHTML = sets.map(set => `
            <div class="set-card public-set" data-set-id="${set.id}">
                <div class="set-header">
                    <h3>${this.escapeHtml(set.title)}</h3>
                    <div class="set-meta">
                        <span class="card-count">${set.card_count} cards</span>
                        <span class="subject-tag">${this.escapeHtml(set.subject)}</span>
                    </div>
                </div>
                <div class="set-description">
                    <p>${this.escapeHtml(set.description)}</p>
                </div>
                <div class="set-actions">
                    <button class="btn btn-primary btn-sm" onclick="flashcardsManager.startStudySession(${set.id})">
                        <i class="fas fa-play"></i> Study
                    </button>
                </div>
                <div class="public-badge">
                    <i class="fas fa-globe"></i> Public
                </div>
            </div>
        `).join('');
    }
    
    filterSets() {
        const search = document.getElementById('search-sets').value.toLowerCase();
        const subject = document.getElementById('subject-filter').value;
        
        const setCards = document.querySelectorAll('.set-card');
        
        setCards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const cardSubject = card.querySelector('.subject-tag').textContent;
            
            const matchesSearch = !search || title.includes(search);
            const matchesSubject = !subject || cardSubject === subject;
            
            card.style.display = matchesSearch && matchesSubject ? 'block' : 'none';
        });
    }
    
    shuffleArray(array) {
        const shuffled = [...array];
        for (let i = shuffled.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
        }
        return shuffled;
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    showNotification(message, type = 'info') {
        // Use the global notification function
        if (typeof showNotification === 'function') {
            showNotification(message, type);
        } else {
            alert(message);
        }
    }
}

// Global functions
function startStudySession(setId) {
    window.flashcardsManager.startStudySession(setId);
}

function editSet(setId) {
    // Implementation for editing sets
    console.log('Edit set:', setId);
}

function deleteSet(setId) {
    window.flashcardsManager.deleteSet(setId);
}

function flipCard() {
    window.flashcardsManager.flipCard();
}

function markCard(difficulty) {
    window.flashcardsManager.markCard(difficulty);
}

function nextCard() {
    window.flashcardsManager.nextCard();
}

function exitStudy() {
    window.flashcardsManager.exitStudy();
}

function openCreateSetModal() {
    window.flashcardsManager.openCreateSetModal();
}

function closeSetModal() {
    window.flashcardsManager.closeSetModal();
}

function addCardForm() {
    window.flashcardsManager.addCardForm();
}

function closeStudyComplete() {
    window.flashcardsManager.closeStudyComplete();
}

function studyAgain() {
    window.flashcardsManager.studyAgain();
}

// Initialize flashcards manager
document.addEventListener('DOMContentLoaded', () => {
    window.flashcardsManager = new FlashcardsManager();
});