<?php
$pageTitle = 'Books';
require_once 'includes/functions.php';

$categories = getBookCategories();
$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : null;
$books = getBooks($selectedCategory);
?>
<?php include 'includes/header.php'; ?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container">
        <span class="page-label">Our Collection</span>
        <h1 class="page-title">Discover Your Next Read</h1>
        <p class="page-subtitle">Browse, borrow, or buy from our curated selection of over 2,000 titles</p>
    </div>
</section>

<!-- Books Content -->
<section class="books-content">
    <div class="container">
        <!-- Category Filter -->
        <div class="books-filter">
            <a href="books.php" class="filter-btn <?php echo !$selectedCategory ? 'active' : ''; ?>">All Books</a>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                <a href="books.php?category=<?php echo $category['id']; ?>" 
                   class="filter-btn <?php echo $selectedCategory == $category['id'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                    <span class="count"><?php echo $category['book_count']; ?></span>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <a href="books.php?category=1" class="filter-btn">Fiction <span class="count">1</span></a>
                <a href="books.php?category=2" class="filter-btn">Non-Fiction <span class="count">1</span></a>
                <a href="books.php?category=3" class="filter-btn">Poetry <span class="count">1</span></a>
                <a href="books.php?category=4" class="filter-btn">Mystery <span class="count">1</span></a>
                <a href="books.php?category=5" class="filter-btn">Classics <span class="count">1</span></a>
            <?php endif; ?>
        </div>
        
        <!-- Books Grid -->
        <div class="books-page-grid">
            <?php if (!empty($books)): ?>
                <?php foreach ($books as $book): ?>
                <div class="book-card-full">
                    <div class="book-cover">
                        <?php if ($book['cover_image']): ?>
                            <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                        <?php else: ?>
                            <div class="book-cover-placeholder">
                                <span class="book-icon">📚</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($book['featured']): ?>
                        <span class="book-badge">Staff Pick</span>
                        <?php endif; ?>
                    </div>
                    <div class="book-details">
                        <span class="book-category"><?php echo htmlspecialchars($book['category_name'] ?? 'General'); ?></span>
                        <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="book-author">by <?php echo htmlspecialchars($book['author']); ?></p>
                        <?php if ($book['description']): ?>
                        <p class="book-description"><?php echo htmlspecialchars($book['description']); ?></p>
                        <?php endif; ?>
                        <div class="book-footer">
                            <span class="book-price"><?php echo formatPrice($book['price']); ?></span>
                            <span class="book-status available">Available</span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback content -->
                <div class="book-card-full">
                    <div class="book-cover">
                        <div class="book-cover-placeholder"><span class="book-icon">📕</span></div>
                        <span class="book-badge">Staff Pick</span>
                    </div>
                    <div class="book-details">
                        <span class="book-category">Fiction</span>
                        <h3 class="book-title">The Midnight Library</h3>
                        <p class="book-author">by Matt Haig</p>
                        <p class="book-description">Between life and death there is a library filled with books that tell the stories of every life you could have lived.</p>
                        <div class="book-footer">
                            <span class="book-price">$16.99</span>
                            <span class="book-status available">Available</span>
                        </div>
                    </div>
                </div>
                <div class="book-card-full">
                    <div class="book-cover">
                        <div class="book-cover-placeholder"><span class="book-icon">📗</span></div>
                        <span class="book-badge">Staff Pick</span>
                    </div>
                    <div class="book-details">
                        <span class="book-category">Non-Fiction</span>
                        <h3 class="book-title">Atomic Habits</h3>
                        <p class="book-author">by James Clear</p>
                        <p class="book-description">An easy and proven way to build good habits and break bad ones.</p>
                        <div class="book-footer">
                            <span class="book-price">$18.99</span>
                            <span class="book-status available">Available</span>
                        </div>
                    </div>
                </div>
                <div class="book-card-full">
                    <div class="book-cover">
                        <div class="book-cover-placeholder"><span class="book-icon">📙</span></div>
                        <span class="book-badge">Staff Pick</span>
                    </div>
                    <div class="book-details">
                        <span class="book-category">Mystery</span>
                        <h3 class="book-title">The Silent Patient</h3>
                        <p class="book-author">by Alex Michaelides</p>
                        <p class="book-description">A woman who shot her husband and stopped speaking.</p>
                        <div class="book-footer">
                            <span class="book-price">$15.99</span>
                            <span class="book-status available">Available</span>
                        </div>
                    </div>
                </div>
                <div class="book-card-full">
                    <div class="book-cover">
                        <div class="book-cover-placeholder"><span class="book-icon">📘</span></div>
                        <span class="book-badge">Staff Pick</span>
                    </div>
                    <div class="book-details">
                        <span class="book-category">Classic Literature</span>
                        <h3 class="book-title">Pride and Prejudice</h3>
                        <p class="book-author">by Jane Austen</p>
                        <p class="book-description">A romantic novel of manners following Elizabeth Bennet.</p>
                        <div class="book-footer">
                            <span class="book-price">$14.99</span>
                            <span class="book-status available">Available</span>
                        </div>
                    </div>
                </div>
                <div class="book-card-full">
                    <div class="book-cover">
                        <div class="book-cover-placeholder"><span class="book-icon">📓</span></div>
                    </div>
                    <div class="book-details">
                        <span class="book-category">Poetry</span>
                        <h3 class="book-title">The Waste Land</h3>
                        <p class="book-author">by T.S. Eliot</p>
                        <p class="book-description">A masterpiece of modernist poetry.</p>
                        <div class="book-footer">
                            <span class="book-price">$12.99</span>
                            <span class="book-status available">Available</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Book Request -->
<section class="book-request">
    <div class="container">
        <div class="request-content">
            <h3>Can't Find What You're Looking For?</h3>
            <p>Let us know and we'll do our best to bring it to our shelves.</p>
            <a href="contact.php" class="btn btn-primary">Request a Book</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

