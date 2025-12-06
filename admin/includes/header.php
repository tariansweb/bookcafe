<?php
requireAdminLogin();
$currentAdmin = getCurrentAdmin();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin Panel'; ?> - BookCafe Admin</title>
    <link rel="icon" type="image/svg+xml" href="../assets/images/favicon.svg">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="admin-panel">
    
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <h2>📚 BookCafe</h2>
            <p>Admin Panel</p>
        </div>
        
        <nav class="sidebar-nav">
            <a href="index.php" class="nav-item <?php echo $currentPage === 'index' ? 'active' : ''; ?>">
                <span class="nav-icon">📊</span>
                Dashboard
            </a>
            <a href="books.php" class="nav-item <?php echo $currentPage === 'books' ? 'active' : ''; ?>">
                <span class="nav-icon">📚</span>
                Books
            </a>
            <a href="menu.php" class="nav-item <?php echo $currentPage === 'menu' ? 'active' : ''; ?>">
                <span class="nav-icon">☕</span>
                Menu Items
            </a>
            <a href="events.php" class="nav-item <?php echo $currentPage === 'events' ? 'active' : ''; ?>">
                <span class="nav-icon">🎭</span>
                Events
            </a>
            <a href="messages.php" class="nav-item <?php echo $currentPage === 'messages' ? 'active' : ''; ?>">
                <span class="nav-icon">✉️</span>
                Messages
            </a>
            <a href="subscribers.php" class="nav-item <?php echo $currentPage === 'subscribers' ? 'active' : ''; ?>">
                <span class="nav-icon">👥</span>
                Subscribers
            </a>
            <a href="admins.php" class="nav-item <?php echo $currentPage === 'admins' ? 'active' : ''; ?>">
                <span class="nav-icon">🔐</span>
                Admin Users
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <a href="../index.php" target="_blank" class="nav-item">
                <span class="nav-icon">🌐</span>
                View Website
            </a>
        </div>
    </aside>
    
    <!-- Main Content -->
    <div class="admin-main">
        
        <!-- Top Bar -->
        <header class="admin-header">
            <div class="header-left">
                <h1><?php echo $pageTitle ?? 'Dashboard'; ?></h1>
            </div>
            <div class="header-right">
                <span class="admin-user">
                    👤 <?php echo htmlspecialchars($currentAdmin['full_name'] ?? $currentAdmin['username']); ?>
                    <small>(<?php echo htmlspecialchars($currentAdmin['role']); ?>)</small>
                </span>
                <a href="logout.php" class="btn btn-outline btn-sm">Logout</a>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="admin-content">

