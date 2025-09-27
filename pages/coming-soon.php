<div class="coming-soon">
    <div class="coming-soon-content">
        <i class="fas fa-tools coming-soon-icon"></i>
        <h2>Coming Soon!</h2>
        <p>This feature is under development and will be available in a future update.</p>
        
        <div class="feature-preview">
            <?php
            $currentPage = $_GET['page'] ?? '';
            switch($currentPage) {
                case 'ai-tutor':
                    echo '<h3><i class="fas fa-robot"></i> AI Tutor</h3>';
                    echo '<p>Get personalized study assistance, question answering, and learning recommendations powered by AI.</p>';
                    echo '<ul class="feature-list">';
                    echo '<li>Smart Q&A system</li>';
                    echo '<li>Personalized study plans</li>';
                    echo '<li>Progress analysis</li>';
                    echo '<li>Subject-specific help</li>';
                    echo '</ul>';
                    break;
                    
                case 'notes':
                    echo '<h3><i class="fas fa-sticky-note"></i> Notes Hub</h3>';
                    echo '<p>Organize, share, and collaborate on study notes with advanced features.</p>';
                    echo '<ul class="feature-list">';
                    echo '<li>Rich text editor</li>';
                    echo '<li>File uploads & attachments</li>';
                    echo '<li>Note sharing & collaboration</li>';
                    echo '<li>Smart organization</li>';
                    echo '</ul>';
                    break;
                    
                case 'marketplace':
                    echo '<h3><i class="fas fa-store"></i> Marketplace</h3>';
                    echo '<p>Buy and sell study materials, courses, and educational resources.</p>';
                    echo '<ul class="feature-list">';
                    echo '<li>Study material exchange</li>';
                    echo '<li>Course marketplace</li>';
                    echo '<li>Tutor services</li>';
                    echo '<li>Resource library</li>';
                    echo '</ul>';
                    break;
            }
            ?>
        </div>
        
        <div class="roadmap-info">
            <h4>Development Roadmap</h4>
            <div class="phases">
                <div class="phase current">
                    <strong>Phase 1 (Current)</strong>
                    <p>Study timer, to-do list, statistics, theme toggle</p>
                </div>
                <div class="phase">
                    <strong>Phase 2</strong>
                    <p>Focus mode, break tracking, D-Day countdown, user accounts</p>
                </div>
                <div class="phase">
                    <strong>Phase 3</strong>
                    <p>Study groups, gamification, flashcards, notes upload</p>
                </div>
                <div class="phase">
                    <strong>Phase 4</strong>
                    <p>AI tutor, marketplace, advanced analytics</p>
                </div>
            </div>
        </div>
        
        <a href="?page=dashboard" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Back to Study App
        </a>
    </div>
</div>