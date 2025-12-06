<?php
$pageTitle = 'Admin Users Management';
require_once __DIR__ . '/includes/admin_functions.php';

$action = $_GET['action'] ?? 'list';
$adminId = $_GET['id'] ?? null;

$success = '';
$error = '';

$db = getDBConnection();

// Only super_admin can manage admin users
$currentAdmin = getCurrentAdmin();
if ($currentAdmin['role'] !== 'super_admin') {
    die('Access denied. Only super administrators can manage admin users.');
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            // Check if username or email already exists
            $checkSql = "SELECT id FROM admin_users WHERE username = :username OR email = :email";
            $checkStmt = $db->prepare($checkSql);
            $checkStmt->execute([
                ':username' => sanitize($_POST['username']),
                ':email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)
            ]);
            
            if ($checkStmt->fetch()) {
                $error = 'Username or email already exists';
            } else {
                // Add new admin user
                $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                
                $sql = "INSERT INTO admin_users (username, email, password, full_name, role, is_active) 
                        VALUES (:username, :email, :password, :full_name, :role, :is_active)";
                
                $stmt = $db->prepare($sql);
                $result = $stmt->execute([
                    ':username' => sanitize($_POST['username']),
                    ':email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                    ':password' => $hashedPassword,
                    ':full_name' => sanitize($_POST['full_name']),
                    ':role' => $_POST['role'],
                    ':is_active' => isset($_POST['is_active']) ? 1 : 0
                ]);
                
                if ($result) {
                    logAdminActivity($_SESSION['admin_id'], 'create', 'admin_user', $db->lastInsertId(), 'Created admin user: ' . $_POST['username']);
                    $success = 'Admin user added successfully!';
                    $action = 'list';
                } else {
                    $error = 'Failed to add admin user';
                }
            }
            
        } elseif ($_POST['action'] === 'edit' && $adminId) {
            // Update admin user
            $sql = "UPDATE admin_users SET 
                    email = :email, full_name = :full_name, role = :role, is_active = :is_active";
            
            $params = [
                ':id' => $adminId,
                ':email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                ':full_name' => sanitize($_POST['full_name']),
                ':role' => $_POST['role'],
                ':is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            
            // Update password if provided
            if (!empty($_POST['password'])) {
                $sql .= ", password = :password";
                $params[':password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }
            
            $sql .= " WHERE id = :id";
            
            $stmt = $db->prepare($sql);
            $result = $stmt->execute($params);
            
            if ($result) {
                logAdminActivity($_SESSION['admin_id'], 'update', 'admin_user', $adminId, 'Updated admin user ID: ' . $adminId);
                $success = 'Admin user updated successfully!';
                $action = 'list';
            } else {
                $error = 'Failed to update admin user';
            }
        }
    }
}

// Handle delete
if ($action === 'delete' && $adminId) {
    // Prevent deleting yourself
    if ($adminId == $_SESSION['admin_id']) {
        $error = 'You cannot delete your own account';
        $action = 'list';
    } else {
        $stmt = $db->prepare("DELETE FROM admin_users WHERE id = :id");
        if ($stmt->execute([':id' => $adminId])) {
            logAdminActivity($_SESSION['admin_id'], 'delete', 'admin_user', $adminId, 'Deleted admin user ID: ' . $adminId);
            $success = 'Admin user deleted successfully!';
        } else {
            $error = 'Failed to delete admin user';
        }
        $action = 'list';
    }
}

// Get admin user data for editing
$admin = null;
if (($action === 'edit') && $adminId) {
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE id = :id");
    $stmt->execute([':id' => $adminId]);
    $admin = $stmt->fetch();
}

// Get all admin users
$admins = [];
if ($action === 'list') {
    $sql = "SELECT * FROM admin_users ORDER BY created_at DESC";
    $admins = $db->query($sql)->fetchAll();
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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">Admin Users</h2>
            <a href="?action=add" class="btn btn-primary">➕ Add New Admin</a>
        </div>
        
        <!-- Admins Table -->
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($admins)): ?>
                        <?php foreach ($admins as $adm): ?>
                            <tr>
                                <td><?php echo $adm['id']; ?></td>
                                <td><?php echo htmlspecialchars($adm['username']); ?></td>
                                <td><?php echo htmlspecialchars($adm['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($adm['email']); ?></td>
                                <td>
                                    <span class="badge badge-info" style="text-transform: capitalize;">
                                        <?php echo str_replace('_', ' ', $adm['role']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($adm['is_active']): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $adm['last_login'] ? formatDate($adm['last_login'], 'M j, Y g:i A') : 'Never'; ?></td>
                                <td class="table-actions">
                                    <a href="?action=edit&id=<?php echo $adm['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
                                    <?php if ($adm['id'] != $_SESSION['admin_id']): ?>
                                        <a href="?action=delete&id=<?php echo $adm['id']; ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirmDelete('Are you sure you want to delete this admin user?')">Delete</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="no-data">No admin users found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
<?php elseif ($action === 'add' || $action === 'edit'): ?>
    
    <!-- Add/Edit Form -->
    <div class="admin-card">
        <h2><?php echo $action === 'add' ? 'Add New Admin User' : 'Edit Admin User'; ?></h2>
        
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $action; ?>">
            
            <?php if ($action === 'add'): ?>
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
            <?php else: ?>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($admin['username']); ?>" disabled>
                    <small style="color: #7f8c8d;">Username cannot be changed</small>
                </div>
            <?php endif; ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" class="form-control" required
                           value="<?php echo htmlspecialchars($admin['email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control"
                           value="<?php echo htmlspecialchars($admin['full_name'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password <?php echo $action === 'edit' ? '(leave blank to keep current)' : '*'; ?></label>
                    <input type="password" id="password" name="password" class="form-control" 
                           <?php echo $action === 'add' ? 'required' : ''; ?>>
                    <?php if ($action === 'add'): ?>
                        <small style="color: #7f8c8d;">Minimum 6 characters recommended</small>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="role">Role *</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="admin" <?php echo ($admin['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="editor" <?php echo ($admin['role'] ?? '') === 'editor' ? 'selected' : ''; ?>>Editor</option>
                        <option value="super_admin" <?php echo ($admin['role'] ?? '') === 'super_admin' ? 'selected' : ''; ?>>Super Admin</option>
                    </select>
                    <small style="color: #7f8c8d;">
                        Super Admin: Full access | Admin: Most features | Editor: Content only
                    </small>
                </div>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" <?php echo ($admin['is_active'] ?? 1) ? 'checked' : ''; ?>>
                    Active Account
                </label>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">
                    <?php echo $action === 'add' ? 'Add Admin User' : 'Update Admin User'; ?>
                </button>
                <a href="admins.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
    
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

