<?php
$pageTitle = 'Books Management';
require_once __DIR__ . '/includes/admin_functions.php';

$action = $_GET['action'] ?? 'list';
$bookId = $_GET['id'] ?? null;
$page = $_GET['page'] ?? 1;
$search = $_GET['search'] ?? '';

$success = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getDBConnection();
    
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            // Add new book
            $sql = "INSERT INTO books (title, author, isbn, category_id, price, description, cover_image, stock, featured, available) 
                    VALUES (:title, :author, :isbn, :category_id, :price, :description, :cover_image, :stock, :featured, :available)";
            
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                ':title' => sanitize($_POST['title']),
                ':author' => sanitize($_POST['author']),
                ':isbn' => sanitize($_POST['isbn']),
                ':category_id' => $_POST['category_id'] ?: null,
                ':price' => $_POST['price'],
                ':description' => sanitize($_POST['description']),
                ':cover_image' => sanitize($_POST['cover_image']),
                ':stock' => $_POST['stock'],
                ':featured' => isset($_POST['featured']) ? 1 : 0,
                ':available' => isset($_POST['available']) ? 1 : 0
            ]);
            
            if ($result) {
                logAdminActivity($_SESSION['admin_id'], 'create', 'book', $db->lastInsertId(), 'Created book: ' . $_POST['title']);
                $success = 'Book added successfully!';
                $action = 'list';
            } else {
                $error = 'Failed to add book';
            }
            
        } elseif ($_POST['action'] === 'edit' && $bookId) {
            // Update book
            $sql = "UPDATE books SET 
                    title = :title, author = :author, isbn = :isbn, category_id = :category_id, 
                    price = :price, description = :description, cover_image = :cover_image, 
                    stock = :stock, featured = :featured, available = :available 
                    WHERE id = :id";
            
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                ':id' => $bookId,
                ':title' => sanitize($_POST['title']),
                ':author' => sanitize($_POST['author']),
                ':isbn' => sanitize($_POST['isbn']),
                ':category_id' => $_POST['category_id'] ?: null,
                ':price' => $_POST['price'],
                ':description' => sanitize($_POST['description']),
                ':cover_image' => sanitize($_POST['cover_image']),
                ':stock' => $_POST['stock'],
                ':featured' => isset($_POST['featured']) ? 1 : 0,
                ':available' => isset($_POST['available']) ? 1 : 0
            ]);
            
            if ($result) {
                logAdminActivity($_SESSION['admin_id'], 'update', 'book', $bookId, 'Updated book: ' . $_POST['title']);
                $success = 'Book updated successfully!';
                $action = 'list';
            } else {
                $error = 'Failed to update book';
            }
        }
    }
}

// Handle delete
if ($action === 'delete' && $bookId) {
    $db = getDBConnection();
    $stmt = $db->prepare("DELETE FROM books WHERE id = :id");
    if ($stmt->execute([':id' => $bookId])) {
        logAdminActivity($_SESSION['admin_id'], 'delete', 'book', $bookId, 'Deleted book ID: ' . $bookId);
        $success = 'Book deleted successfully!';
    } else {
        $error = 'Failed to delete book';
    }
    $action = 'list';
}

// Get categories for dropdown
$categories = getBookCategories();

// Get book data for editing
$book = null;
if (($action === 'edit' || $action === 'view') && $bookId) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT * FROM books WHERE id = :id");
    $stmt->execute([':id' => $bookId]);
    $book = $stmt->fetch();
}

// Get books list
if ($action === 'list') {
    $booksData = getAllBooksAdmin($page, 20, $search);
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
    
    <!-- Search and Add Button -->
    <div class="admin-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <form method="GET" class="search-bar" style="flex: 1; margin: 0;">
                <input type="text" name="search" placeholder="Search books by title, author, or ISBN..." 
                       value="<?php echo htmlspecialchars($search); ?>" class="form-control">
                <button type="submit" class="btn btn-secondary">Search</button>
            </form>
            <a href="?action=add" class="btn btn-primary">➕ Add New Book</a>
        </div>
        
        <!-- Books Table -->
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Featured</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($booksData['data'])): ?>
                        <?php foreach ($booksData['data'] as $book): ?>
                            <tr>
                                <td><?php echo $book['id']; ?></td>
                                <td><?php echo htmlspecialchars($book['title']); ?></td>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                <td><?php echo htmlspecialchars($book['category_name'] ?? 'N/A'); ?></td>
                                <td><?php echo formatPrice($book['price']); ?></td>
                                <td><?php echo $book['stock']; ?></td>
                                <td><?php echo $book['featured'] ? '⭐' : ''; ?></td>
                                <td>
                                    <?php if ($book['available']): ?>
                                        <span class="badge badge-success">Yes</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">No</span>
                                    <?php endif; ?>
                                </td>
                                <td class="table-actions">
                                    <a href="?action=edit&id=<?php echo $book['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
                                    <a href="?action=delete&id=<?php echo $book['id']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirmDelete('Are you sure you want to delete this book?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="no-data">No books found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($booksData['pages'] > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $booksData['pages']; $i++): ?>
                    <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="<?php echo $i === (int)$page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
    
<?php elseif ($action === 'add' || $action === 'edit'): ?>
    
    <!-- Add/Edit Form -->
    <div class="admin-card">
        <h2><?php echo $action === 'add' ? 'Add New Book' : 'Edit Book'; ?></h2>
        
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $action; ?>">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" class="form-control" required
                           value="<?php echo htmlspecialchars($book['title'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="author">Author *</label>
                    <input type="text" id="author" name="author" class="form-control" required
                           value="<?php echo htmlspecialchars($book['author'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="isbn">ISBN</label>
                    <input type="text" id="isbn" name="isbn" class="form-control"
                           value="<?php echo htmlspecialchars($book['isbn'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" class="form-control">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"
                                    <?php echo ($book['category_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="price">Price *</label>
                    <input type="number" step="0.01" id="price" name="price" class="form-control" required
                           value="<?php echo $book['price'] ?? ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" class="form-control"
                           value="<?php echo $book['stock'] ?? 0; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="cover_image">Cover Image URL</label>
                <input type="url" id="cover_image" name="cover_image" class="form-control"
                       value="<?php echo htmlspecialchars($book['cover_image'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control"><?php echo htmlspecialchars($book['description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="featured" <?php echo ($book['featured'] ?? 0) ? 'checked' : ''; ?>>
                        Featured Book
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="available" <?php echo ($book['available'] ?? 1) ? 'checked' : ''; ?>>
                        Available for Sale
                    </label>
                </div>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">
                    <?php echo $action === 'add' ? 'Add Book' : 'Update Book'; ?>
                </button>
                <a href="books.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
    
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

