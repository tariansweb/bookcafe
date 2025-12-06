<?php
/**
 * Admin Functions for BookCafe
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in as admin
 * 
 * @return bool
 */
function isAdminLoggedIn(): bool {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

/**
 * Require admin login - redirect to login if not authenticated
 */
function requireAdminLogin(): void {
    if (!isAdminLoggedIn()) {
        redirect('/admin/login.php');
    }
}

/**
 * Get current admin user data
 * 
 * @return array|null
 */
function getCurrentAdmin(): ?array {
    if (!isAdminLoggedIn()) {
        return null;
    }
    
    $db = getDBConnection();
    if (!$db) return null;
    
    $sql = "SELECT id, username, email, full_name, role, avatar, last_login 
            FROM admin_users 
            WHERE id = :id AND is_active = 1";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $_SESSION['admin_id']]);
    
    return $stmt->fetch();
}

/**
 * Authenticate admin user
 * 
 * @param string $username
 * @param string $password
 * @return array Result with success status and message
 */
function authenticateAdmin(string $username, string $password): array {
    $db = getDBConnection();
    if (!$db) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }

    
    $sql = "SELECT * FROM admin_users WHERE username = :username AND is_active = 1";
    $stmt = $db->prepare($sql);
    $stmt->execute([':username' => $username]);
    
    $admin = $stmt->fetch();
    
    if (!$admin || !password_verify($password, $admin['password'])) {
        return ['success' => false, 'message' => 'Invalid username or password'];
    }
    
    // Set session variables
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_role'] = $admin['role'];
    $_SESSION['admin_full_name'] = $admin['full_name'];
    
    // Update last login
    $updateSql = "UPDATE admin_users SET last_login = NOW() WHERE id = :id";
    $updateStmt = $db->prepare($updateSql);
    $updateStmt->execute([':id' => $admin['id']]);
    
    // Log activity
    logAdminActivity($admin['id'], 'login', null, null, 'User logged in');
    
    return ['success' => true, 'message' => 'Login successful'];
}

/**
 * Logout admin user
 */
function logoutAdmin(): void {
    if (isAdminLoggedIn()) {
        logAdminActivity($_SESSION['admin_id'], 'logout', null, null, 'User logged out');
    }
    
    session_unset();
    session_destroy();
}

/**
 * Log admin activity
 * 
 * @param int $adminId
 * @param string $action
 * @param string|null $entityType
 * @param int|null $entityId
 * @param string|null $details
 */
function logAdminActivity(int $adminId, string $action, ?string $entityType = null, ?int $entityId = null, ?string $details = null): void {
    $db = getDBConnection();
    if (!$db) return;
    
    $sql = "INSERT INTO admin_activity_log (admin_id, action, entity_type, entity_id, details, ip_address) 
            VALUES (:admin_id, :action, :entity_type, :entity_id, :details, :ip_address)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':admin_id' => $adminId,
        ':action' => $action,
        ':entity_type' => $entityType,
        ':entity_id' => $entityId,
        ':details' => $details,
        ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
    ]);
}

/**
 * Get dashboard statistics
 * 
 * @return array
 */
function getDashboardStats(): array {
    $db = getDBConnection();
    if (!$db) return [];
    
    $stats = [];
    
    // Total books
    $stmt = $db->query("SELECT COUNT(*) as count FROM books WHERE available = 1");
    $stats['books'] = $stmt->fetch()['count'];
    
    // Total menu items
    $stmt = $db->query("SELECT COUNT(*) as count FROM menu_items WHERE available = 1");
    $stats['menu_items'] = $stmt->fetch()['count'];
    
    // Upcoming events
    $stmt = $db->query("SELECT COUNT(*) as count FROM events WHERE event_date >= CURDATE()");
    $stats['events'] = $stmt->fetch()['count'];
    
    // Unread messages
    $stmt = $db->query("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0");
    $stats['unread_messages'] = $stmt->fetch()['count'];
    
    // Active subscribers
    $stmt = $db->query("SELECT COUNT(*) as count FROM subscribers WHERE is_active = 1");
    $stats['subscribers'] = $stmt->fetch()['count'];
    
    // Recent activity
    $stmt = $db->query("SELECT COUNT(*) as count FROM admin_activity_log WHERE DATE(created_at) = CURDATE()");
    $stats['today_activities'] = $stmt->fetch()['count'];
    
    return $stats;
}

/**
 * Get recent admin activities
 * 
 * @param int $limit
 * @return array
 */
function getRecentActivities(int $limit = 10): array {
    $db = getDBConnection();
    if (!$db) return [];
    
    $sql = "SELECT aal.*, au.username, au.full_name 
            FROM admin_activity_log aal 
            JOIN admin_users au ON aal.admin_id = au.id 
            ORDER BY aal.created_at DESC 
            LIMIT :limit";
    
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Get all books with pagination
 * 
 * @param int $page
 * @param int $perPage
 * @param string|null $search
 * @return array
 */
function getAllBooksAdmin(int $page = 1, int $perPage = 20, ?string $search = null): array {
    $db = getDBConnection();
    if (!$db) return ['data' => [], 'total' => 0, 'pages' => 0];
    
    $offset = ($page - 1) * $perPage;
    
    $where = "1=1";
    $params = [];
    
    if ($search) {
        $where .= " AND (b.title LIKE :search OR b.author LIKE :search OR b.isbn LIKE :search)";
        $params[':search'] = "%$search%";
    }
    
    // Get total count
    $countSql = "SELECT COUNT(*) as total FROM books b WHERE $where";
    $stmt = $db->prepare($countSql);
    $stmt->execute($params);
    $total = $stmt->fetch()['total'];
    
    // Get data
    $sql = "SELECT b.*, c.name as category_name 
            FROM books b 
            LEFT JOIN categories c ON b.category_id = c.id 
            WHERE $where 
            ORDER BY b.created_at DESC 
            LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    return [
        'data' => $stmt->fetchAll(),
        'total' => $total,
        'pages' => ceil($total / $perPage),
        'current_page' => $page
    ];
}

/**
 * Get all contact messages
 * 
 * @param int $page
 * @param int $perPage
 * @return array
 */
function getAllContactMessages(int $page = 1, int $perPage = 20): array {
    $db = getDBConnection();
    if (!$db) return ['data' => [], 'total' => 0, 'pages' => 0];
    
    $offset = ($page - 1) * $perPage;
    
    // Get total count
    $countSql = "SELECT COUNT(*) as total FROM contact_messages";
    $total = $db->query($countSql)->fetch()['total'];
    
    // Get data
    $sql = "SELECT * FROM contact_messages 
            ORDER BY is_read ASC, created_at DESC 
            LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    return [
        'data' => $stmt->fetchAll(),
        'total' => $total,
        'pages' => ceil($total / $perPage),
        'current_page' => $page
    ];
}

/**
 * Mark message as read
 * 
 * @param int $messageId
 * @return bool
 */
function markMessageAsRead(int $messageId): bool {
    $db = getDBConnection();
    if (!$db) return false;
    
    $sql = "UPDATE contact_messages SET is_read = 1 WHERE id = :id";
    $stmt = $db->prepare($sql);
    
    return $stmt->execute([':id' => $messageId]);
}

/**
 * Get all subscribers
 * 
 * @param int $page
 * @param int $perPage
 * @return array
 */
function getAllSubscribers(int $page = 1, int $perPage = 50): array {
    $db = getDBConnection();
    if (!$db) return ['data' => [], 'total' => 0, 'pages' => 0];
    
    $offset = ($page - 1) * $perPage;
    
    // Get total count
    $countSql = "SELECT COUNT(*) as total FROM subscribers";
    $total = $db->query($countSql)->fetch()['total'];
    
    // Get data
    $sql = "SELECT * FROM subscribers 
            ORDER BY created_at DESC 
            LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    return [
        'data' => $stmt->fetchAll(),
        'total' => $total,
        'pages' => ceil($total / $perPage),
        'current_page' => $page
    ];
}

