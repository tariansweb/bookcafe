<?php
$pageTitle = 'Newsletter Subscribers';
require_once __DIR__ . '/includes/admin_functions.php';

$page = $_GET['page'] ?? 1;
$action = $_GET['action'] ?? 'list';
$subscriberId = $_GET['id'] ?? null;

$success = '';
$error = '';

$db = getDBConnection();

// Handle deactivate
if ($action === 'deactivate' && $subscriberId) {
    $stmt = $db->prepare("UPDATE subscribers SET is_active = 0 WHERE id = :id");
    if ($stmt->execute([':id' => $subscriberId])) {
        logAdminActivity($_SESSION['admin_id'], 'update', 'subscriber', $subscriberId, 'Deactivated subscriber');
        $success = 'Subscriber deactivated successfully';
    } else {
        $error = 'Failed to deactivate subscriber';
    }
    $action = 'list';
}

// Handle reactivate
if ($action === 'activate' && $subscriberId) {
    $stmt = $db->prepare("UPDATE subscribers SET is_active = 1 WHERE id = :id");
    if ($stmt->execute([':id' => $subscriberId])) {
        logAdminActivity($_SESSION['admin_id'], 'update', 'subscriber', $subscriberId, 'Activated subscriber');
        $success = 'Subscriber activated successfully';
    } else {
        $error = 'Failed to activate subscriber';
    }
    $action = 'list';
}

// Handle delete
if ($action === 'delete' && $subscriberId) {
    $stmt = $db->prepare("DELETE FROM subscribers WHERE id = :id");
    if ($stmt->execute([':id' => $subscriberId])) {
        logAdminActivity($_SESSION['admin_id'], 'delete', 'subscriber', $subscriberId, 'Deleted subscriber ID: ' . $subscriberId);
        $success = 'Subscriber deleted successfully';
    } else {
        $error = 'Failed to delete subscriber';
    }
    $action = 'list';
}

// Get all subscribers
$subscribersData = [];
if ($action === 'list') {
    $subscribersData = getAllSubscribers($page, 50);
}
?>
<?php include 'includes/header.php'; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="admin-card">
    <h2>Newsletter Subscribers (<?php echo number_format($subscribersData['total'] ?? 0); ?>)</h2>
    
    <?php if (!empty($subscribersData['data'])): ?>
        <div style="margin-bottom: 20px;">
            <button class="btn btn-primary" onclick="exportEmails()">📧 Export Email List</button>
        </div>
    <?php endif; ?>
    
    <!-- Subscribers Table -->
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Subscribed Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($subscribersData['data'])): ?>
                    <?php foreach ($subscribersData['data'] as $sub): ?>
                        <tr>
                            <td><?php echo $sub['id']; ?></td>
                            <td><?php echo htmlspecialchars($sub['email']); ?></td>
                            <td><?php echo formatDate($sub['created_at'], 'M j, Y'); ?></td>
                            <td>
                                <?php if ($sub['is_active']): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="table-actions">
                                <?php if ($sub['is_active']): ?>
                                    <a href="?action=deactivate&id=<?php echo $sub['id']; ?>" class="btn btn-warning btn-sm">Deactivate</a>
                                <?php else: ?>
                                    <a href="?action=activate&id=<?php echo $sub['id']; ?>" class="btn btn-success btn-sm">Activate</a>
                                <?php endif; ?>
                                <a href="?action=delete&id=<?php echo $sub['id']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirmDelete('Are you sure you want to delete this subscriber?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-data">No subscribers found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($subscribersData['pages'] > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $subscribersData['pages']; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo $i === (int)$page ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function exportEmails() {
    // Get all email addresses
    const emails = [];
    document.querySelectorAll('.admin-table tbody tr').forEach(row => {
        const emailCell = row.cells[1];
        if (emailCell) {
            emails.push(emailCell.textContent.trim());
        }
    });
    
    if (emails.length > 0) {
        // Create a text file with emails
        const text = emails.join('\n');
        const blob = new Blob([text], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        
        // Create download link
        const a = document.createElement('a');
        a.href = url;
        a.download = 'subscribers_' + new Date().toISOString().split('T')[0] + '.txt';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
}
</script>

<?php include 'includes/footer.php'; ?>

