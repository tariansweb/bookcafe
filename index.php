<?php
$pageTitle = 'Home';
require_once 'includes/functions.php';

// Get featured content
$featuredBooks = getFeaturedBooks(4);
$featuredMenu = getFeaturedMenuItems(6);
$upcomingEvents = getUpcomingEvents(3);
?>
<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-text">
            <span class="hero-badge">Est. 2024</span>
            <h1 class="hero-title">
                Where <em>Stories</em><br>
                Meet <em>Coffee</em>
            </h1>
            <p class="hero-description">
                Step into a world where every cup tells a story and every page transports you. 
                Our sanctuary blends artisanal coffee with curated literature for the perfect escape.
            </p>
            <div class="hero-actions">
                <a href="menu.php" class="btn btn-primary">Explore Menu</a>
                <a href="books.php" class="btn btn-outline">Browse Books</a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-card hero-card-1">
                <div class="card-icon">☕</div>
                <span>Artisan Coffee</span>
            </div>
            <div class="hero-card hero-card-2">
                <div class="card-icon">📖</div>
                <span>Curated Books</span>
            </div>
            <div class="hero-card hero-card-3">
                <div class="card-icon">🎭</div>
                <span>Live Events</span>
            </div>
        </div>
    </div>
    <div class="hero-scroll">
        <span>Scroll to discover</span>
        <div class="scroll-indicator"></div>
    </div>
</section>

<!-- About Section -->
<section class="about-section" id="about">
    <div class="container">
        <div class="about-grid">
            <div class="about-content">
                <span class="section-label">Our Story</span>
                <h2 class="section-title">A Haven for Dreamers & Thinkers</h2>
                <p class="about-text">
                    Born from a love of literature and the perfect brew, BookCafe is more than a destination—it's 
                    a feeling. Nestled in the heart of the city, we've created a space where time slows down, 
                    conversations deepen, and imagination takes flight.
                </p>
                <p class="about-text">
                    Our shelves house over 2,000 carefully selected titles, from contemporary bestsellers to 
                    forgotten gems waiting to be rediscovered. Paired with coffee sourced from ethical farms 
                    and roasted to perfection, every visit becomes a journey.
                </p>
                <div class="about-stats">
                    <div class="stat">
                        <span class="stat-number">2000+</span>
                        <span class="stat-label">Books</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">15</span>
                        <span class="stat-label">Coffee Origins</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">Events Yearly</span>
                    </div>
                </div>
            </div>
            <div class="about-image">
                <div class="image-frame">
                    <div class="image-placeholder">
                        <span class="placeholder-icon">📚☕</span>
                        <span class="placeholder-text">Our Cozy Space</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Menu Section -->
<section class="featured-menu">
    <div class="container">
        <div class="section-header">
            <span class="section-label">From Our Kitchen</span>
            <h2 class="section-title">Today's Favorites</h2>
            <p class="section-subtitle">Hand-crafted with love and the finest ingredients</p>
        </div>
        
        <div class="menu-grid">
            <?php if (!empty($featuredMenu)): ?>
                <?php foreach ($featuredMenu as $item): ?>
                <div class="menu-card">
                    <span class="menu-category"><?php echo htmlspecialchars($item['icon'] . ' ' . $item['category_name']); ?></span>
                    <h3 class="menu-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p class="menu-description"><?php echo htmlspecialchars($item['description']); ?></p>
                    <span class="menu-price"><?php echo formatPrice($item['price']); ?></span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback content when database is not connected -->
                <div class="menu-card">
                    <span class="menu-category">☕ Coffee</span>
                    <h3 class="menu-name">House Blend</h3>
                    <p class="menu-description">Our signature medium roast with notes of chocolate and caramel</p>
                    <span class="menu-price">$4.50</span>
                </div>
                <div class="menu-card">
                    <span class="menu-category">☕ Coffee</span>
                    <h3 class="menu-name">Cappuccino</h3>
                    <p class="menu-description">Espresso with steamed milk and velvety foam</p>
                    <span class="menu-price">$5.00</span>
                </div>
                <div class="menu-card">
                    <span class="menu-category">🍵 Tea</span>
                    <h3 class="menu-name">Earl Grey</h3>
                    <p class="menu-description">Classic bergamot-infused black tea</p>
                    <span class="menu-price">$4.00</span>
                </div>
                <div class="menu-card">
                    <span class="menu-category">🍵 Tea</span>
                    <h3 class="menu-name">Matcha Latte</h3>
                    <p class="menu-description">Premium Japanese green tea with steamed milk</p>
                    <span class="menu-price">$5.50</span>
                </div>
                <div class="menu-card">
                    <span class="menu-category">🥐 Pastries</span>
                    <h3 class="menu-name">Croissant</h3>
                    <p class="menu-description">Buttery, flaky French pastry</p>
                    <span class="menu-price">$4.50</span>
                </div>
                <div class="menu-card">
                    <span class="menu-category">🥪 Light Bites</span>
                    <h3 class="menu-name">Avocado Toast</h3>
                    <p class="menu-description">Sourdough with smashed avocado and microgreens</p>
                    <span class="menu-price">$8.50</span>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="section-cta">
            <a href="menu.php" class="btn btn-secondary">View Full Menu</a>
        </div>
    </div>
</section>

<!-- Featured Books Section -->
<section class="featured-books">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Staff Picks</span>
            <h2 class="section-title">Books We Love</h2>
            <p class="section-subtitle">Handpicked favorites from our collection</p>
        </div>
        
        <div class="books-grid">
            <?php if (!empty($featuredBooks)): ?>
                <?php foreach ($featuredBooks as $book): ?>
                <div class="book-card">
                    <div class="book-cover">
                        <?php if ($book['cover_image']): ?>
                            <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                        <?php else: ?>
                            <div class="book-cover-placeholder">
                                <span class="book-icon">📕</span>
                            </div>
                        <?php endif; ?>
                        <span class="book-badge">Featured</span>
                    </div>
                    <div class="book-info">
                        <span class="book-category"><?php echo htmlspecialchars($book['category_name'] ?? 'General'); ?></span>
                        <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="book-author">by <?php echo htmlspecialchars($book['author']); ?></p>
                        <span class="book-price"><?php echo formatPrice($book['price']); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback content -->
                <div class="book-card">
                    <div class="book-cover">
                        <div class="book-cover-placeholder"><span class="book-icon">📕</span></div>
                        <span class="book-badge">Featured</span>
                    </div>
                    <div class="book-info">
                        <span class="book-category">Fiction</span>
                        <h3 class="book-title">The Midnight Library</h3>
                        <p class="book-author">by Matt Haig</p>
                        <span class="book-price">$16.99</span>
                    </div>
                </div>
                <div class="book-card">
                    <div class="book-cover">
                        <div class="book-cover-placeholder"><span class="book-icon">📗</span></div>
                        <span class="book-badge">Featured</span>
                    </div>
                    <div class="book-info">
                        <span class="book-category">Non-Fiction</span>
                        <h3 class="book-title">Atomic Habits</h3>
                        <p class="book-author">by James Clear</p>
                        <span class="book-price">$18.99</span>
                    </div>
                </div>
                <div class="book-card">
                    <div class="book-cover">
                        <div class="book-cover-placeholder"><span class="book-icon">📙</span></div>
                        <span class="book-badge">Featured</span>
                    </div>
                    <div class="book-info">
                        <span class="book-category">Mystery</span>
                        <h3 class="book-title">The Silent Patient</h3>
                        <p class="book-author">by Alex Michaelides</p>
                        <span class="book-price">$15.99</span>
                    </div>
                </div>
                <div class="book-card">
                    <div class="book-cover">
                        <div class="book-cover-placeholder"><span class="book-icon">📘</span></div>
                        <span class="book-badge">Featured</span>
                    </div>
                    <div class="book-info">
                        <span class="book-category">Classic</span>
                        <h3 class="book-title">Pride and Prejudice</h3>
                        <p class="book-author">by Jane Austen</p>
                        <span class="book-price">$14.99</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="section-cta">
            <a href="books.php" class="btn btn-secondary">Explore Collection</a>
        </div>
    </div>
</section>

<!-- Events Section -->
<section class="events-section">
    <div class="container">
        <div class="events-header">
            <div class="section-header">
                <span class="section-label">Happening Soon</span>
                <h2 class="section-title">Upcoming Events</h2>
            </div>
            <a href="events.php" class="btn btn-outline">All Events</a>
        </div>
        
        <div class="events-list">
            <?php if (!empty($upcomingEvents)): ?>
                <?php foreach ($upcomingEvents as $event): ?>
                <div class="event-card">
                    <div class="event-date">
                        <span class="event-day"><?php echo formatDate($event['event_date'], 'd'); ?></span>
                        <span class="event-month"><?php echo formatDate($event['event_date'], 'M'); ?></span>
                    </div>
                    <div class="event-details">
                        <h3 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="event-description"><?php echo htmlspecialchars($event['description']); ?></p>
                        <div class="event-meta">
                            <?php if ($event['event_time']): ?>
                            <span class="event-time">🕐 <?php echo formatTime($event['event_time']); ?></span>
                            <?php endif; ?>
                            <?php if ($event['location']): ?>
                            <span class="event-location">📍 <?php echo htmlspecialchars($event['location']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback content -->
                <div class="event-card">
                    <div class="event-date">
                        <span class="event-day">15</span>
                        <span class="event-month">Dec</span>
                    </div>
                    <div class="event-details">
                        <h3 class="event-title">Poetry Night</h3>
                        <p class="event-description">Open mic poetry reading with guest performers</p>
                        <div class="event-meta">
                            <span class="event-time">🕐 7:00 PM</span>
                            <span class="event-location">📍 Main Reading Room</span>
                        </div>
                    </div>
                </div>
                <div class="event-card">
                    <div class="event-date">
                        <span class="event-day">22</span>
                        <span class="event-month">Dec</span>
                    </div>
                    <div class="event-details">
                        <h3 class="event-title">Book Club: Fiction Favorites</h3>
                        <p class="event-description">Monthly discussion of contemporary fiction</p>
                        <div class="event-meta">
                            <span class="event-time">🕐 6:30 PM</span>
                            <span class="event-location">📍 Garden Terrace</span>
                        </div>
                    </div>
                </div>
                <div class="event-card">
                    <div class="event-date">
                        <span class="event-day">29</span>
                        <span class="event-month">Dec</span>
                    </div>
                    <div class="event-details">
                        <h3 class="event-title">Author Meet & Greet</h3>
                        <p class="event-description">Meet local authors and get your books signed</p>
                        <div class="event-meta">
                            <span class="event-time">🕐 2:00 PM</span>
                            <span class="event-location">📍 Gallery Space</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">Ready to Escape?</h2>
            <p class="cta-text">
                Come find your next favorite read, savor a perfectly crafted cup, 
                and lose yourself in the magic of BookCafe.
            </p>
            <a href="contact.php" class="btn btn-primary btn-large">Visit Us Today</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

