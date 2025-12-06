<?php
$pageTitle = 'Events Management';
require_once __DIR__ . '/includes/admin_functions.php';

$action = $_GET['action'] ?? 'list';
$eventId = $_GET['id'] ?? null;

$success = '';
$error = '';

$db = getDBConnection();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            // Add new event
            $sql = "INSERT INTO events (title, description, event_date, event_time, location) 
                    VALUES (:title, :description, :event_date, :event_time, :location)";
            
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                ':title' => sanitize($_POST['title']),
                ':description' => sanitize($_POST['description']),
                ':event_date' => $_POST['event_date'],
                ':event_time' => $_POST['event_time'] ?: null,
                ':location' => sanitize($_POST['location'])
            ]);
            
            if ($result) {
                logAdminActivity($_SESSION['admin_id'], 'create', 'event', $db->lastInsertId(), 'Created event: ' . $_POST['title']);
                $success = 'Event added successfully!';
                $action = 'list';
            } else {
                $error = 'Failed to add event';
            }
            
        } elseif ($_POST['action'] === 'edit' && $eventId) {
            // Update event
            $sql = "UPDATE events SET 
                    title = :title, description = :description, event_date = :event_date, 
                    event_time = :event_time, location = :location 
                    WHERE id = :id";
            
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                ':id' => $eventId,
                ':title' => sanitize($_POST['title']),
                ':description' => sanitize($_POST['description']),
                ':event_date' => $_POST['event_date'],
                ':event_time' => $_POST['event_time'] ?: null,
                ':location' => sanitize($_POST['location'])
            ]);
            
            if ($result) {
                logAdminActivity($_SESSION['admin_id'], 'update', 'event', $eventId, 'Updated event: ' . $_POST['title']);
                $success = 'Event updated successfully!';
                $action = 'list';
            } else {
                $error = 'Failed to update event';
            }
        }
    }
}

// Handle delete
if ($action === 'delete' && $eventId) {
    $stmt = $db->prepare("DELETE FROM events WHERE id = :id");
    if ($stmt->execute([':id' => $eventId])) {
        logAdminActivity($_SESSION['admin_id'], 'delete', 'event', $eventId, 'Deleted event ID: ' . $eventId);
        $success = 'Event deleted successfully!';
    } else {
        $error = 'Failed to delete event';
    }
    $action = 'list';
}

// Get event data for editing
$event = null;
if (($action === 'edit') && $eventId) {
    $stmt = $db->prepare("SELECT * FROM events WHERE id = :id");
    $stmt->execute([':id' => $eventId]);
    $event = $stmt->fetch();
}

// Get all events
$events = [];
if ($action === 'list') {
    $sql = "SELECT * FROM events ORDER BY event_date DESC, event_time DESC";
    $events = $db->query($sql)->fetchAll();
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
    
    <!-- Add Button -->
    <div class="admin-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">Events</h2>
            <a href="?action=add" class="btn btn-primary">➕ Add New Event</a>
        </div>
        
        <!-- Events Table -->
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($events)): ?>
                        <?php foreach ($events as $event): ?>
                            <tr>
                                <td><?php echo $event['id']; ?></td>
                                <td><?php echo htmlspecialchars($event['title']); ?></td>
                                <td><?php echo htmlspecialchars(substr($event['description'], 0, 50)) . '...'; ?></td>
                                <td><?php echo formatDate($event['event_date'], 'M j, Y'); ?></td>
                                <td><?php echo $event['event_time'] ? formatTime($event['event_time']) : 'N/A'; ?></td>
                                <td><?php echo htmlspecialchars($event['location']); ?></td>
                                <td class="table-actions">
                                    <a href="?action=edit&id=<?php echo $event['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
                                    <a href="?action=delete&id=<?php echo $event['id']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirmDelete('Are you sure you want to delete this event?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="no-data">No events found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
<?php elseif ($action === 'add' || $action === 'edit'): ?>
    
    <!-- Add/Edit Form -->
    <div class="admin-card">
        <h2><?php echo $action === 'add' ? 'Add New Event' : 'Edit Event'; ?></h2>
        
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $action; ?>">
            
            <div class="form-group">
                <label for="title">Event Title *</label>
                <input type="text" id="title" name="title" class="form-control" required
                       value="<?php echo htmlspecialchars($event['title'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" class="form-control" required><?php echo htmlspecialchars($event['description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="event_date">Event Date *</label>
                    <input type="date" id="event_date" name="event_date" class="form-control" required
                           value="<?php echo $event['event_date'] ?? ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="event_time">Event Time</label>
                    <input type="time" id="event_time" name="event_time" class="form-control"
                           value="<?php echo $event['event_time'] ?? ''; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" class="form-control"
                       value="<?php echo htmlspecialchars($event['location'] ?? ''); ?>">
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">
                    <?php echo $action === 'add' ? 'Add Event' : 'Update Event'; ?>
                </button>
                <a href="events.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
    
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

