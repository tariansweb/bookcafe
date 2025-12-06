<?php
$pageTitle = 'Contact Messages';
require_once __DIR__ . '/includes/admin_functions.php';

$page = $_GET['page'] ?? 1;
$messageId = $_GET['id'] ?? null;
$action = $_GET['action'] ?? 'list';

$success = '';
$error = '';

$db = getDBConnection();

// Handle mark as read
if ($action === 'mark_read' && $messageId) {
    if (markMessageAsRead($messageId)) {
        logAdminActivity($_SESSION['admin_id'], 'update', 'contact_message', $messageId, 'Marked message as read');
        $success = 'Message marked as read';
    }
    $action = 'list';
}

// Handle delete
if ($action === 'delete' && $messageId) {
    $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = :id");
    if ($stmt->execute([':id' => $messageId])) {
        logAdminActivity($_SESSION['admin_id'], 'delete', 'contact_message', $messageId, 'Deleted message ID: ' . $messageId);
        $success = 'Message deleted successfully';
    } else {
        $error = 'Failed to delete message';
    }
    $action = 'list';
}

// Get message for viewing
$message = null;
if ($action === 'view' && $messageId) {
    $stmt = $db->prepare("SELECT * FROM contact_messages WHERE id = :id");
    $stmt->execute([':id' => $messageId]);
    $message = $stmt->fetch();
    
    // Mark as read when viewing
    if ($message && !$message['is_read']) {
        markMessageAsRead($messageId);
        $message['is_read'] = 1;
    }
}

// Get all messages
$messagesData = [];
if ($action === 'list') {
    $messagesData = getAllContactMessages($page, 20);
}
?>
<?php include 'includes/header.php'; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if ($action === 'list'): ?>
    
    <div class="admin-card">
        <h2>Contact Messages</h2>
        
        <!-- Messages Table -->
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($messagesData['data'])): ?>
                        <?php foreach ($messagesData['data'] as $msg): ?>
                            <tr style="<?php echo !$msg['is_read'] ? 'font-weight: bold;' : ''; ?>">
                                <td><?php echo $msg['id']; ?></td>
                                <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                                <td><?php echo formatDate($msg['created_at'], 'M j, Y'); ?></td>
                                <td>
                                    <?php if ($msg['is_read']): ?>
                                        <span class="badge badge-info">Read</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Unread</span>
                                    <?php endif; ?>
                                </td>
                                <td class="table-actions">
                                    <a href="?action=view&id=<?php echo $msg['id']; ?>" class="btn btn-secondary btn-sm">View</a>
                                    <?php if (!$msg['is_read']): ?>
                                        <a href="?action=mark_read&id=<?php echo $msg['id']; ?>" class="btn btn-success btn-sm">Mark Read</a>
                                    <?php endif; ?>
                                    <a href="?action=delete&id=<?php echo $msg['id']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirmDelete('Are you sure you want to delete this message?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="no-data">No messages found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($messagesData['pages'] > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $messagesData['pages']; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo $i === (int)$page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
    
<?php elseif ($action === 'view' && $message): ?>
    
    <div class="admin-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>View Message</h2>
            <a href="messages.php" class="btn btn-outline">← Back to Messages</a>
        </div>
        
        <div style="background: var(--admin-bg); padding: 20px; border-radius: 8px;">
            <div style="margin-bottom: 15px;">
                <strong>From:</strong> <?php echo htmlspecialchars($message['name']); ?>
            </div>
            <div style="margin-bottom: 15px;">
                <strong>Email:</strong> 
                <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>">
                    <?php echo htmlspecialchars($message['email']); ?>
                </a>
            </div>
            <div style="margin-bottom: 15px;">
                <strong>Subject:</strong> <?php echo htmlspecialchars($message['subject']); ?>
            </div>
            <div style="margin-bottom: 15px;">
                <strong>Date:</strong> <?php echo formatDate($message['created_at'], 'F j, Y g:i A'); ?>
            </div>
            <div style="margin-bottom: 15px;">
                <strong>Status:</strong>
                <?php if ($message['is_read']): ?>
                    <span class="badge badge-info">Read</span>
                <?php else: ?>
                    <span class="badge badge-warning">Unread</span>
                <?php endif; ?>
            </div>
            <hr>
            <div style="margin-top: 20px;">
                <strong>Message:</strong>
                <div style="margin-top: 10px; white-space: pre-wrap;">
                    <?php echo htmlspecialchars($message['message']); ?>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>?subject=Re: <?php echo urlencode($message['subject']); ?>" 
               class="btn btn-primary">
                Reply via Email
            </a>
            <a href="?action=delete&id=<?php echo $message['id']; ?>" 
               class="btn btn-danger"
               onclick="return confirmDelete('Are you sure you want to delete this message?')">
                Delete Message
            </a>
        </div>
    </div>
    
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

