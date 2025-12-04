<?php
require_once __DIR__ . '/../config/config.php';

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo SITE_NAME; ?> - <?php echo SITE_TAGLINE; ?>. A cozy haven for book lovers and coffee enthusiasts.">
    
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' . SITE_NAME : SITE_NAME . ' - ' . SITE_TAGLINE; ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">
</head>
<body>
    <!-- Decorative grain overlay -->
    <div class="grain-overlay"></div>
    
    <!-- Navigation -->
    <header class="site-header">
        <nav class="nav-container">
            <a href="index.php" class="logo">
                <span class="logo-icon">📚</span>
                <span class="logo-text">
                    <span class="logo-name"><?php echo SITE_NAME; ?></span>
                    <span class="logo-tagline"><?php echo SITE_TAGLINE; ?></span>
                </span>
            </a>
            
            <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
                <span class="hamburger"></span>
            </button>
            
            <ul class="nav-menu">
                <li><a href="index.php" class="<?php echo $currentPage === 'index' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="menu.php" class="<?php echo $currentPage === 'menu' ? 'active' : ''; ?>">Menu</a></li>
                <li><a href="books.php" class="<?php echo $currentPage === 'books' ? 'active' : ''; ?>">Books</a></li>
                <li><a href="events.php" class="<?php echo $currentPage === 'events' ? 'active' : ''; ?>">Events</a></li>
                <li><a href="contact.php" class="<?php echo $currentPage === 'contact' ? 'active' : ''; ?>">Contact</a></li>
            </ul>
        </nav>
    </header>
    
    <main class="main-content">

