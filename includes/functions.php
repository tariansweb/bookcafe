<?php
/**
 * Helper Functions for BookCafe
 */

require_once __DIR__ . '/../config/config.php';

/**
 * Sanitize user input
 * 
 * @param string $data Input to sanitize
 * @return string Sanitized string
 */
function sanitize(string $data): string {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to a URL
 * 
 * @param string $url URL to redirect to
 */
function redirect(string $url): void {
    header("Location: $url");
    exit;
}

/**
 * Get all menu items grouped by category
 * 
 * @return array Menu items grouped by category
 */
function getMenuItems(): array {
    $db = getDBConnection();
    if (!$db) return [];
    
    $sql = "SELECT mi.*, mc.name as category_name, mc.slug as category_slug, mc.icon 
            FROM menu_items mi 
            JOIN menu_categories mc ON mi.category_id = mc.id 
            WHERE mi.available = 1 
            ORDER BY mc.display_order, mi.name";
    
    $stmt = $db->query($sql);
    $items = $stmt->fetchAll();
    
    $grouped = [];
    foreach ($items as $item) {
        $category = $item['category_name'];
        if (!isset($grouped[$category])) {
            $grouped[$category] = [
                'icon' => $item['icon'],
                'slug' => $item['category_slug'],
                'items' => []
            ];
        }
        $grouped[$category]['items'][] = $item;
    }
    
    return $grouped;
}

/**
 * Get featured menu items
 * 
 * @param int $limit Number of items to return
 * @return array Featured menu items
 */
function getFeaturedMenuItems(int $limit = 6): array {
    $db = getDBConnection();
    if (!$db) return [];
    
    $sql = "SELECT mi.*, mc.name as category_name, mc.icon 
            FROM menu_items mi 
            JOIN menu_categories mc ON mi.category_id = mc.id 
            WHERE mi.featured = 1 AND mi.available = 1 
            ORDER BY RAND() 
            LIMIT :limit";
    
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Get all books with optional filtering
 * 
 * @param int|null $categoryId Filter by category
 * @param int $limit Number of books to return
 * @return array Books
 */
function getBooks(?int $categoryId = null, int $limit = 20): array {
    $db = getDBConnection();
    if (!$db) return [];
    
    $sql = "SELECT b.*, c.name as category_name, c.slug as category_slug 
            FROM books b 
            LEFT JOIN categories c ON b.category_id = c.id 
            WHERE b.available = 1";
    
    if ($categoryId) {
        $sql .= " AND b.category_id = :category_id";
    }
    
    $sql .= " ORDER BY b.featured DESC, b.created_at DESC LIMIT :limit";
    
    $stmt = $db->prepare($sql);
    if ($categoryId) {
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Get book categories
 * 
 * @return array Categories
 */
function getBookCategories(): array {
    $db = getDBConnection();
    if (!$db) return [];
    
    $sql = "SELECT c.*, COUNT(b.id) as book_count 
            FROM categories c 
            LEFT JOIN books b ON c.id = b.category_id AND b.available = 1 
            GROUP BY c.id 
            ORDER BY c.name";
    
    return $db->query($sql)->fetchAll();
}

/**
 * Get featured books
 * 
 * @param int $limit Number of books to return
 * @return array Featured books
 */
function getFeaturedBooks(int $limit = 4): array {
    $db = getDBConnection();
    if (!$db) return [];
    
    $sql = "SELECT b.*, c.name as category_name 
            FROM books b 
            LEFT JOIN categories c ON b.category_id = c.id 
            WHERE b.featured = 1 AND b.available = 1 
            ORDER BY RAND() 
            LIMIT :limit";
    
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Get upcoming events
 * 
 * @param int $limit Number of events to return
 * @return array Events
 */
function getUpcomingEvents(int $limit = 5): array {
    $db = getDBConnection();
    if (!$db) return [];
    
    $sql = "SELECT * FROM events 
            WHERE event_date >= CURDATE() 
            ORDER BY event_date ASC, event_time ASC 
            LIMIT :limit";
    
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Submit a contact message
 * 
 * @param string $name Sender name
 * @param string $email Sender email
 * @param string $subject Message subject
 * @param string $message Message content
 * @return bool Success status
 */
function submitContactMessage(string $name, string $email, string $subject, string $message): bool {
    $db = getDBConnection();
    if (!$db) return false;
    
    $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (:name, :email, :subject, :message)";
    
    $stmt = $db->prepare($sql);
    return $stmt->execute([
        ':name' => sanitize($name),
        ':email' => filter_var($email, FILTER_SANITIZE_EMAIL),
        ':subject' => sanitize($subject),
        ':message' => sanitize($message)
    ]);
}

/**
 * Subscribe to newsletter
 * 
 * @param string $email Email address
 * @return array Result with success status and message
 */
function subscribeNewsletter(string $email): array {
    $db = getDBConnection();
    if (!$db) return ['success' => false, 'message' => 'Database connection failed'];
    
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Invalid email address'];
    }
    
    try {
        $sql = "INSERT INTO subscribers (email) VALUES (:email)";
        $stmt = $db->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        return ['success' => true, 'message' => 'Successfully subscribed!'];
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            return ['success' => false, 'message' => 'Email already subscribed'];
        }
        return ['success' => false, 'message' => 'Subscription failed'];
    }
}

/**
 * Format price for display
 * 
 * @param float $price Price value
 * @return string Formatted price
 */
function formatPrice(float $price): string {
    return '$' . number_format($price, 2);
}

/**
 * Format date for display
 * 
 * @param string $date Date string
 * @param string $format Output format
 * @return string Formatted date
 */
function formatDate(string $date, string $format = 'F j, Y'): string {
    return date($format, strtotime($date));
}

/**
 * Format time for display
 * 
 * @param string $time Time string
 * @return string Formatted time
 */
function formatTime(string $time): string {
    return date('g:i A', strtotime($time));
}

