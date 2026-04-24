<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Check if page is visible
if (!isPageVisible('shop')) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>Page Not Found</h1>';
    exit;
}

$pageTitle = 'Shop';
$metaDescription = 'Shop our merchandise and support Careful Cat Rescue. All proceeds go directly to caring for our cats.';

// Get active products
$sql = "SELECT * FROM merch_products WHERE is_active = 1 ORDER BY display_order ASC, name ASC";
$products = db()->fetchAll($sql);

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1>Shop Our Merchandise</h1>
        <p>Support our rescue by purchasing merchandise. All proceeds go directly to caring for our cats!</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (count($products) > 0): ?>
        <div class="grid grid-3">
            <?php foreach ($products as $product): ?>
            <div class="card">
                <?php if ($product['image_url']): ?>
                <img src="<?php echo e($product['image_url']); ?>" alt="<?php echo e($product['name']); ?>" class="card-image">
                <?php endif; ?>
                <div class="card-content">
                    <h3 class="card-title"><?php echo e($product['name']); ?></h3>
                    <p class="card-text"><?php echo e($product['description']); ?></p>
                    
                    <?php if ($product['sizes']): ?>
                    <div class="mb-2">
                        <small style="color: var(--text-light);">
                            Available sizes: <?php echo e($product['sizes']); ?>
                        </small>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mt-2">
                        <strong style="font-size: 1.25rem; color: var(--primary-color);">
                            $<?php echo number_format($product['price'], 2); ?>
                        </strong>
                        <?php if ($product['stock_quantity'] !== null && $product['stock_quantity'] < 10): ?>
                        <span class="badge" style="background-color: var(--warning-color); margin-left: 0.5rem;">
                            Only <?php echo $product['stock_quantity']; ?> left!
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer">
                    <?php if ($product['stock_quantity'] === null || $product['stock_quantity'] > 0): ?>
                    <a href="/contact.php?subject=Order: <?php echo urlencode($product['name']); ?>" 
                       class="btn btn-primary" style="width: 100%;">Order Now</a>
                    <?php else: ?>
                    <button class="btn btn-outline" style="width: 100%;" disabled>Out of Stock</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="card">
            <div class="card-content text-center" style="padding: 3rem;">
                <h3>Coming Soon!</h3>
                <p>We're working on our merchandise shop. Check back soon for t-shirts, mugs, and more!</p>
                <p class="mt-3">In the meantime, you can support us through a <a href="/donate.php">donation</a>.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- How It Helps Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">How Your Purchase Helps</h2>
            <p class="section-subtitle">100% of proceeds from merchandise sales go directly to our rescue operations.</p>
        </div>
        
        <div class="grid grid-4">
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color); font-size: 2rem;">🍽️</h3>
                    <h4>Food & Supplies</h4>
                    <p>Nutritious food and daily care supplies for our cats</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color); font-size: 2rem;">🏥</h3>
                    <h4>Medical Care</h4>
                    <p>Veterinary visits, vaccinations, and treatments</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color); font-size: 2rem;">🏠</h3>
                    <h4>Shelter Operations</h4>
                    <p>Maintaining a safe and comfortable facility</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color); font-size: 2rem;">🐱</h3>
                    <h4>Rescue Efforts</h4>
                    <p>Saving more cats in need of help</p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <p><strong>Can't find what you're looking for?</strong></p>
            <a href="/contact.php" class="btn btn-outline">Contact Us</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

