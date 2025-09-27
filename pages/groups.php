<div class="groups-page">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-users"></i> Study Groups</h1>
        <p>Join study groups, collaborate with peers, and achieve more together</p>
    </div>

    <!-- My Groups Section -->
    <div class="my-groups-section">
        <div class="section-header">
            <h2>My Study Groups</h2>
            <button class="btn btn-primary" onclick="openCreateGroupModal()">
                <i class="fas fa-plus"></i> Create Group
            </button>
        </div>

        <div class="groups-grid" id="my-groups-grid">
            <?php
            try {
                $stmt = $pdo->prepare("SELECT sg.*, gm.role, COUNT(gm2.id) as member_count 
                                     FROM study_groups sg 
                                     JOIN group_members gm ON sg.id = gm.group_id 
                                     LEFT JOIN group_members gm2 ON sg.id = gm2.group_id 
                                     WHERE gm.user_id = ? 
                                     GROUP BY sg.id 
                                     ORDER BY sg.created_at DESC");
                $stmt->execute([$_SESSION['user_id']]);
                $myGroups = $stmt->fetchAll();
                
                if ($myGroups):
                    foreach ($myGroups as $group):
            ?>
            <div class="group-card my-group" data-group-id="<?php echo $group['id']; ?>">
                <div class="group-header">
                    <h3><?php echo htmlspecialchars($group['name']); ?></h3>
                    <div class="group-meta">
                        <span class="member-count"><?php echo $group['member_count']; ?> members</span>
                        <span class="role-badge role-<?php echo $group['role']; ?>"><?php echo ucfirst($group['role']); ?></span>
                    </div>
                </div>
                
                <div class="group-description">
                    <p><?php echo htmlspecialchars($group['description']); ?></p>
                </div>
                
                <div class="group-stats">
                    <div class="stat-item">
                        <i class="fas fa-calendar"></i>
                        <span>Created <?php echo date('M j, Y', strtotime($group['created_at'])); ?></span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-code"></i>
                        <span>Code: <?php echo $group['invite_code']; ?></span>
                    </div>
                </div>
                
                <div class="group-actions">
                    <button class="btn btn-primary btn-sm" onclick="enterGroup(<?php echo $group['id']; ?>)">
                        <i class="fas fa-door-open"></i> Enter Group
                    </button>
                    
                    <?php if ($group['role'] === 'owner' || $group['role'] === 'admin'): ?>
                    <button class="btn btn-outline btn-sm" onclick="manageGroup(<?php echo $group['id']; ?>)">
                        <i class="fas fa-cog"></i> Manage
                    </button>
                    <?php endif; ?>
                    
                    <button class="btn btn-outline btn-sm" onclick="leaveGroup(<?php echo $group['id']; ?>)">
                        <i class="fas fa-sign-out-alt"></i> Leave
                    </button>
                </div>
                
                <?php if ($group['is_public']): ?>
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
                <i class="fas fa-users"></i>
                <h3>No study groups yet</h3>
                <p>Create or join your first study group to collaborate with others</p>
                <button class="btn btn-primary" onclick="openCreateGroupModal()">
                    <i class="fas fa-plus"></i> Create Your First Group
                </button>
            </div>
            <?php endif; ?>
            
            <?php } catch(Exception $e) { ?>
                <div class="error-state">
                    <p>Unable to load your study groups. Please try again.</p>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Join Group Section -->
    <div class="join-group-section">
        <div class="join-group-card">
            <h3><i class="fas fa-user-plus"></i> Join a Study Group</h3>
            <p>Have an invite code? Enter it below to join a study group.</p>
            
            <form id="join-group-form" class="join-form">
                <div class="form-group">
                    <input type="text" id="invite-code" class="form-control" placeholder="Enter invite code" required>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Join Group
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Public Groups Section -->
    <div class="public-groups-section">
        <h2>Browse Public Groups</h2>
        
        <div class="groups-filters">
            <input type="text" id="search-groups" class="form-control" placeholder="Search groups...">
            <select id="sort-groups" class="form-control">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="popular">Most Popular</option>
                <option value="name">Name A-Z</option>
            </select>
        </div>

        <div class="groups-grid" id="public-groups-grid">
            <?php
            try {
                $stmt = $pdo->prepare("SELECT sg.*, COUNT(gm.id) as member_count,
                                     EXISTS(SELECT 1 FROM group_members WHERE group_id = sg.id AND user_id = ?) as is_member
                                     FROM study_groups sg 
                                     LEFT JOIN group_members gm ON sg.id = gm.group_id 
                                     WHERE sg.is_public = 1 
                                     GROUP BY sg.id 
                                     ORDER BY member_count DESC, sg.created_at DESC 
                                     LIMIT 12");
                $stmt->execute([$_SESSION['user_id']]);
                $publicGroups = $stmt->fetchAll();
                
                foreach ($publicGroups as $group):
            ?>
            <div class="group-card public-group" data-group-id="<?php echo $group['id']; ?>">
                <div class="group-header">
                    <h3><?php echo htmlspecialchars($group['name']); ?></h3>
                    <div class="group-meta">
                        <span class="member-count"><?php echo $group['member_count']; ?> members</span>
                        <span class="capacity"><?php echo $group['max_members']; ?> max</span>
                    </div>
                </div>
                
                <div class="group-description">
                    <p><?php echo htmlspecialchars($group['description']); ?></p>
                </div>
                
                <div class="group-stats">
                    <div class="stat-item">
                        <i class="fas fa-calendar"></i>
                        <span>Created <?php echo date('M j, Y', strtotime($group['created_at'])); ?></span>
                    </div>
                </div>
                
                <div class="group-actions">
                    <?php if ($group['is_member']): ?>
                    <button class="btn btn-success btn-sm" disabled>
                        <i class="fas fa-check"></i> Already Member
                    </button>
                    <?php elseif ($group['member_count'] >= $group['max_members']): ?>
                    <button class="btn btn-outline btn-sm" disabled>
                        <i class="fas fa-users"></i> Group Full
                    </button>
                    <?php else: ?>
                    <button class="btn btn-primary btn-sm" onclick="joinPublicGroup(<?php echo $group['id']; ?>)">
                        <i class="fas fa-user-plus"></i> Join Group
                    </button>
                    <?php endif; ?>
                    
                    <button class="btn btn-outline btn-sm" onclick="viewGroupDetails(<?php echo $group['id']; ?>)">
                        <i class="fas fa-info-circle"></i> Details
                    </button>
                </div>
                
                <div class="public-badge">
                    <i class="fas fa-globe"></i> Public
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php } catch(Exception $e) { ?>
                <div class="error-state">
                    <p>Unable to load public groups. Please try again.</p>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Group Leaderboard -->
    <div class="leaderboard-section">
        <h2><i class="fas fa-trophy"></i> Group Leaderboards</h2>
        <div class="leaderboard-tabs">
            <button class="tab-btn active" onclick="showLeaderboard('weekly')">This Week</button>
            <button class="tab-btn" onclick="showLeaderboard('monthly')">This Month</button>
            <button class="tab-btn" onclick="showLeaderboard('alltime')">All Time</button>
        </div>
        
        <div class="leaderboard-content" id="leaderboard-content">
            <!-- Leaderboard will be loaded here -->
        </div>
    </div>
</div>

<!-- Group Details Modal -->
<div id="group-details-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="group-details-content">
            <!-- Group details will be loaded here -->
        </div>
    </div>
</div>

<!-- Create Group Modal -->
<div id="create-group-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Create Study Group</h3>
        <form id="create-group-form">
            <div class="form-group">
                <label for="group-name">Group Name:</label>
                <input type="text" id="group-name" class="form-control" placeholder="e.g., AP Biology Study Group" required>
            </div>
            
            <div class="form-group">
                <label for="group-description">Description:</label>
                <textarea id="group-description" class="form-control" placeholder="Describe your group's purpose and goals..." rows="4" required></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="max-members">Maximum Members:</label>
                    <select id="max-members" class="form-control">
                        <option value="10">10 members</option>
                        <option value="25">25 members</option>
                        <option value="50" selected>50 members</option>
                        <option value="100">100 members</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="group-public" checked> Make group public
                    </label>
                    <small>Public groups can be discovered and joined by anyone</small>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeCreateGroupModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Group</button>
            </div>
        </form>
    </div>
</div>

<!-- Group Management Modal -->
<div id="manage-group-modal" class="modal">
    <div class="modal-content large">
        <span class="close">&times;</span>
        <h3>Manage Group</h3>
        <div class="management-tabs">
            <button class="tab-btn active" onclick="showManagementTab('members')">Members</button>
            <button class="tab-btn" onclick="showManagementTab('settings')">Settings</button>
            <button class="tab-btn" onclick="showManagementTab('analytics')">Analytics</button>
        </div>
        
        <div class="management-content" id="management-content">
            <!-- Management content will be loaded here -->
        </div>
    </div>
</div>

<!-- Group Chat/Activity Feed -->
<div id="group-activity-modal" class="modal">
    <div class="modal-content large">
        <span class="close">&times;</span>
        <div class="group-activity-header">
            <h3 id="activity-group-name">Group Activity</h3>
            <div class="activity-tabs">
                <button class="tab-btn active" onclick="showActivityTab('feed')">Activity Feed</button>
                <button class="tab-btn" onclick="showActivityTab('members')">Members</button>
                <button class="tab-btn" onclick="showActivityTab('resources')">Resources</button>
            </div>
        </div>
        
        <div class="activity-content" id="activity-content">
            <!-- Activity content will be loaded here -->
        </div>
    </div>
</div>