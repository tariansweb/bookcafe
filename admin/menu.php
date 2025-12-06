<?php
$pageTitle = 'Menu Items Management';
require_once __DIR__ . '/includes/admin_functions.php';

$action = $_GET['action'] ?? 'list';
$itemId = $_GET['id'] ?? null;

$success = '';
$error = '';

$db = getDBConnection();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            // Add new menu item
            $sql = "INSERT INTO menu_items (category_id, name, description, price, featured, available) 
                    VALUES (:category_id, :name, :description, :price, :featured, :available)";
            
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                ':category_id' => $_POST['category_id'],
                ':name' => sanitize($_POST['name']),
                ':description' => sanitize($_POST['description']),
                ':price' => $_POST['price'],
                ':featured' => isset($_POST['featured']) ? 1 : 0,
                ':available' => isset($_POST['available']) ? 1 : 0
            ]);
            
            if ($result) {
                logAdminActivity($_SESSION['admin_id'], 'create', 'menu_item', $db->lastInsertId(), 'Created menu item: ' . $_POST['name']);
                $success = 'Menu item added successfully!';
                $action = 'list';
            } else {
                $error = 'Failed to add menu item';
            }
            
        } elseif ($_POST['action'] === 'edit' && $itemId) {
            // Update menu item
            $sql = "UPDATE menu_items SET 
                    category_id = :category_id, name = :name, description = :description, 
                    price = :price, featured = :featured, available = :available 
                    WHERE id = :id";
            
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                ':id' => $itemId,
                ':category_id' => $_POST['category_id'],
                ':name' => sanitize($_POST['name']),
                ':description' => sanitize($_POST['description']),
                ':price' => $_POST['price'],
                ':featured' => isset($_POST['featured']) ? 1 : 0,
                ':available' => isset($_POST['available']) ? 1 : 0
            ]);
            
            if ($result) {
                logAdminActivity($_SESSION['admin_id'], 'update', 'menu_item', $itemId, 'Updated menu item: ' . $_POST['name']);
                $success = 'Menu item updated successfully!';
                $action = 'list';
            } else {
                $error = 'Failed to update menu item';
            }
        }
    }
}

// Handle delete
if ($action === 'delete' && $itemId) {
    $stmt = $db->prepare("DELETE FROM menu_items WHERE id = :id");
    if ($stmt->execute([':id' => $itemId])) {
        logAdminActivity($_SESSION['admin_id'], 'delete', 'menu_item', $itemId, 'Deleted menu item ID: ' . $itemId);
        $success = 'Menu item deleted successfully!';
    } else {
        $error = 'Failed to delete menu item';
    }
    $action = 'list';
}

// Get menu categories
$menuCategories = $db->query("SELECT * FROM menu_categories ORDER BY display_order")->fetchAll();

// Get menu item data for editing
$item = null;
if (($action === 'edit') && $itemId) {
    $stmt = $db->prepare("SELECT * FROM menu_items WHERE id = :id");
    $stmt->execute([':id' => $itemId]);
    $item = $stmt->fetch();
}

// Get menu items grouped by category
$menuItems = [];
if ($action === 'list') {
    $sql = "SELECT mi.*, mc.name as category_name, mc.icon 
            FROM menu_items mi 
            JOIN menu_categories mc ON mi.category_id = mc.id 
            ORDER BY mc.display_order, mi.name";
    $menuItems = $db->query($sql)->fetchAll();
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
            <h2 style="margin: 0;">Menu Items</h2>
            <a href="?action=add" class="btn btn-primary">➕ Add New Item</a>
        </div>
        
        <!-- Menu Items Table -->
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Featured</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($menuItems)): ?>
                        <?php foreach ($menuItems as $item): ?>
                            <tr>
                                <td><?php echo $item['id']; ?></td>
                                <td><?php echo htmlspecialchars($item['icon'] . ' ' . $item['category_name']); ?></td>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars(substr($item['description'], 0, 50)) . '...'; ?></td>
                                <td><?php echo formatPrice($item['price']); ?></td>
                                <td><?php echo $item['featured'] ? '⭐' : ''; ?></td>
                                <td>
                                    <?php if ($item['available']): ?>
                                        <span class="badge badge-success">Yes</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">No</span>
                                    <?php endif; ?>
                                </td>
                                <td class="table-actions">
                                    <a href="?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
                                    <a href="?action=delete&id=<?php echo $item['id']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirmDelete('Are you sure you want to delete this menu item?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="no-data">No menu items found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
<?php elseif ($action === 'add' || $action === 'edit'): ?>
    
    <!-- Add/Edit Form -->
    <div class="admin-card">
        <h2><?php echo $action === 'add' ? 'Add New Menu Item' : 'Edit Menu Item'; ?></h2>
        
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $action; ?>">
            
            <div class="form-group">
                <label for="category_id">Category *</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php foreach ($menuCategories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"
                                <?php echo ($item['category_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['icon'] . ' ' . $cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="name">Item Name *</label>
                <input type="text" id="name" name="name" class="form-control" required
                       value="<?php echo htmlspecialchars($item['name'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control"><?php echo htmlspecialchars($item['description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="price">Price *</label>
                <input type="number" step="0.01" id="price" name="price" class="form-control" required
                       value="<?php echo $item['price'] ?? ''; ?>">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="featured" <?php echo ($item['featured'] ?? 0) ? 'checked' : ''; ?>>
                        Featured Item
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="available" <?php echo ($item['available'] ?? 1) ? 'checked' : ''; ?>>
                        Available
                    </label>
                </div>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">
                    <?php echo $action === 'add' ? 'Add Item' : 'Update Item'; ?>
                </button>
                <a href="menu.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
    
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

