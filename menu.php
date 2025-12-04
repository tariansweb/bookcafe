<?php
$pageTitle = 'Menu';
require_once 'includes/functions.php';

$menuItems = getMenuItems();
?>
<?php include 'includes/header.php'; ?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container">
        <span class="page-label">Our Menu</span>
        <h1 class="page-title">Crafted with Care</h1>
        <p class="page-subtitle">From bean to cup, from oven to plate — every item is made with passion</p>
    </div>
</section>

<!-- Menu Content -->
<section class="menu-content">
    <div class="container">
        <?php if (!empty($menuItems)): ?>
            <?php foreach ($menuItems as $categoryName => $category): ?>
            <div class="menu-category-section" id="<?php echo $category['slug']; ?>">
                <div class="category-header">
                    <span class="category-icon"><?php echo $category['icon']; ?></span>
                    <h2 class="category-title"><?php echo htmlspecialchars($categoryName); ?></h2>
                </div>
                
                <div class="menu-items-grid">
                    <?php foreach ($category['items'] as $item): ?>
                    <div class="menu-item-card <?php echo $item['featured'] ? 'featured' : ''; ?>">
                        <?php if ($item['featured']): ?>
                        <span class="featured-badge">★ Popular</span>
                        <?php endif; ?>
                        <div class="item-header">
                            <h3 class="item-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <span class="item-price"><?php echo formatPrice($item['price']); ?></span>
                        </div>
                        <p class="item-description"><?php echo htmlspecialchars($item['description']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Fallback static menu -->
            <div class="menu-category-section" id="coffee">
                <div class="category-header">
                    <span class="category-icon">☕</span>
                    <h2 class="category-title">Coffee</h2>
                </div>
                <div class="menu-items-grid">
                    <div class="menu-item-card featured">
                        <span class="featured-badge">★ Popular</span>
                        <div class="item-header">
                            <h3 class="item-name">House Blend</h3>
                            <span class="item-price">$4.50</span>
                        </div>
                        <p class="item-description">Our signature medium roast with notes of chocolate and caramel</p>
                    </div>
                    <div class="menu-item-card">
                        <div class="item-header">
                            <h3 class="item-name">Espresso</h3>
                            <span class="item-price">$3.50</span>
                        </div>
                        <p class="item-description">Bold and intense single or double shot</p>
                    </div>
                    <div class="menu-item-card featured">
                        <span class="featured-badge">★ Popular</span>
                        <div class="item-header">
                            <h3 class="item-name">Cappuccino</h3>
                            <span class="item-price">$5.00</span>
                        </div>
                        <p class="item-description">Espresso with steamed milk and velvety foam</p>
                    </div>
                    <div class="menu-item-card">
                        <div class="item-header">
                            <h3 class="item-name">Cold Brew</h3>
                            <span class="item-price">$5.50</span>
                        </div>
                        <p class="item-description">Smooth, slow-steeped coffee served over ice</p>
                    </div>
                </div>
            </div>
            
            <div class="menu-category-section" id="tea">
                <div class="category-header">
                    <span class="category-icon">🍵</span>
                    <h2 class="category-title">Tea</h2>
                </div>
                <div class="menu-items-grid">
                    <div class="menu-item-card featured">
                        <span class="featured-badge">★ Popular</span>
                        <div class="item-header">
                            <h3 class="item-name">Earl Grey</h3>
                            <span class="item-price">$4.00</span>
                        </div>
                        <p class="item-description">Classic bergamot-infused black tea</p>
                    </div>
                    <div class="menu-item-card">
                        <div class="item-header">
                            <h3 class="item-name">Chamomile</h3>
                            <span class="item-price">$4.00</span>
                        </div>
                        <p class="item-description">Calming herbal blend for reading sessions</p>
                    </div>
                    <div class="menu-item-card featured">
                        <span class="featured-badge">★ Popular</span>
                        <div class="item-header">
                            <h3 class="item-name">Matcha Latte</h3>
                            <span class="item-price">$5.50</span>
                        </div>
                        <p class="item-description">Premium Japanese green tea with steamed milk</p>
                    </div>
                </div>
            </div>
            
            <div class="menu-category-section" id="pastries">
                <div class="category-header">
                    <span class="category-icon">🥐</span>
                    <h2 class="category-title">Pastries</h2>
                </div>
                <div class="menu-items-grid">
                    <div class="menu-item-card featured">
                        <span class="featured-badge">★ Popular</span>
                        <div class="item-header">
                            <h3 class="item-name">Croissant</h3>
                            <span class="item-price">$4.50</span>
                        </div>
                        <p class="item-description">Buttery, flaky French pastry</p>
                    </div>
                    <div class="menu-item-card">
                        <div class="item-header">
                            <h3 class="item-name">Blueberry Muffin</h3>
                            <span class="item-price">$4.00</span>
                        </div>
                        <p class="item-description">Fresh-baked with wild blueberries</p>
                    </div>
                    <div class="menu-item-card featured">
                        <span class="featured-badge">★ Popular</span>
                        <div class="item-header">
                            <h3 class="item-name">Chocolate Éclair</h3>
                            <span class="item-price">$5.00</span>
                        </div>
                        <p class="item-description">Choux pastry with cream and chocolate glaze</p>
                    </div>
                </div>
            </div>
            
            <div class="menu-category-section" id="light-bites">
                <div class="category-header">
                    <span class="category-icon">🥪</span>
                    <h2 class="category-title">Light Bites</h2>
                </div>
                <div class="menu-items-grid">
                    <div class="menu-item-card featured">
                        <span class="featured-badge">★ Popular</span>
                        <div class="item-header">
                            <h3 class="item-name">Avocado Toast</h3>
                            <span class="item-price">$8.50</span>
                        </div>
                        <p class="item-description">Sourdough with smashed avocado and microgreens</p>
                    </div>
                    <div class="menu-item-card">
                        <div class="item-header">
                            <h3 class="item-name">Caprese Sandwich</h3>
                            <span class="item-price">$9.00</span>
                        </div>
                        <p class="item-description">Fresh mozzarella, tomato, and basil on ciabatta</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Menu Note -->
<section class="menu-note">
    <div class="container">
        <div class="note-content">
            <p>🌿 We use locally sourced ingredients whenever possible. Please inform us of any allergies.</p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

