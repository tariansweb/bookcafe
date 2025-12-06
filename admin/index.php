<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/admin_functions.php';

$stats = getDashboardStats();
$recentActivities = getRecentActivities(15);
?>
<?php include 'includes/header.php'; ?>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📚</div>
        <div class="stat-content">
            <h3><?php echo number_format($stats['books'] ?? 0); ?></h3>
            <p>Books Available</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">☕</div>
        <div class="stat-content">
            <h3><?php echo number_format($stats['menu_items'] ?? 0); ?></h3>
            <p>Menu Items</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">🎭</div>
        <div class="stat-content">
            <h3><?php echo number_format($stats['events'] ?? 0); ?></h3>
            <p>Upcoming Events</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">✉️</div>
        <div class="stat-content">
            <h3><?php echo number_format($stats['unread_messages'] ?? 0); ?></h3>
            <p>Unread Messages</p>
        </div>
        <?php if ($stats['unread_messages'] > 0): ?>
            <a href="messages.php" class="stat-action">View</a>
        <?php endif; ?>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-content">
            <h3><?php echo number_format($stats['subscribers'] ?? 0); ?></h3>
            <p>Subscribers</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">📊</div>
        <div class="stat-content">
            <h3><?php echo number_format($stats['today_activities'] ?? 0); ?></h3>
            <p>Today's Activities</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <h2>Quick Actions</h2>
    <div class="action-buttons">
        <a href="books.php?action=add" class="btn btn-primary">
            <span>➕</span> Add New Book
        </a>
        <a href="menu.php?action=add" class="btn btn-primary">
            <span>➕</span> Add Menu Item
        </a>
        <a href="events.php?action=add" class="btn btn-primary">
            <span>➕</span> Create Event
        </a>
        <a href="messages.php" class="btn btn-secondary">
            <span>✉️</span> View Messages
        </a>
    </div>
</div>

<!-- Recent Activity -->
<div class="admin-card">
    <h2>Recent Activity</h2>
    
    <?php if (!empty($recentActivities)): ?>
        <div class="activity-list">
            <?php foreach ($recentActivities as $activity): ?>
                <div class="activity-item">
                    <div class="activity-icon">
                        <?php 
                        $icon = '📝';
                        if (strpos($activity['action'], 'create') !== false) $icon = '➕';
                        elseif (strpos($activity['action'], 'update') !== false) $icon = '✏️';
                        elseif (strpos($activity['action'], 'delete') !== false) $icon = '🗑️';
                        elseif (strpos($activity['action'], 'login') !== false) $icon = '🔓';
                        elseif (strpos($activity['action'], 'logout') !== false) $icon = '🔒';
                        echo $icon;
                        ?>
                    </div>
                    <div class="activity-content">
                        <p class="activity-text">
                            <strong><?php echo htmlspecialchars($activity['full_name'] ?? $activity['username']); ?></strong>
                            <?php echo htmlspecialchars($activity['action']); ?>
                            <?php if ($activity['entity_type']): ?>
                                <?php echo htmlspecialchars($activity['entity_type']); ?>
                            <?php endif; ?>
                        </p>
                        <?php if ($activity['details']): ?>
                            <p class="activity-details"><?php echo htmlspecialchars($activity['details']); ?></p>
                        <?php endif; ?>
                        <p class="activity-time"><?php echo formatDate($activity['created_at'], 'M j, Y g:i A'); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="no-data">No recent activity</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

