<?php
/**
 * Database Configuration for AWS RDS MySQL
 * 
 * Update these credentials with your AWS RDS instance details
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'bookcafe_db');
define('DB_USER', 'localuser');
define('DB_PASS', 'BBCn3ws9o#');
define('DB_PORT', '3306');
define('DB_CHARSET', 'utf8mb4');

/**
 * Create database connection using PDO
 * 
 * @return PDO|null Database connection or null on failure
 */
function getDBConnection(): ?PDO {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            // PDO::MYSQL_ATTR_SSL_CA       => null, // Add SSL cert path for production
            // PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
        ];
        
        return new PDO($dsn, DB_USER, DB_PASS, $options);
        
    } catch (PDOException $e) {
        die("Database Connection Error: " . $e->getMessage());
        return null;
    }
}

/**
 * Test database connection
 * 
 * @return bool True if connection successful
 */
function testConnection(): bool {
    $conn = getDBConnection();
    return $conn !== null;
}

