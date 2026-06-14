<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Check if page is visible
if (!isPageVisible('residents')) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>Page Not Found</h1>';
    exit;
}

$pageTitle = 'Our Cat Residents - Sanctuary Felines';
$metaDescription = 'Meet all the wonderful cats currently living at Careful Cat Rescue sanctuary in Murfreesboro, TN. Some are adoptable, others are permanent sanctuary residents.';
$metaKeywords = 'cat sanctuary residents, cats Murfreesboro TN, serval sanctuary, savannah cat rescue resident, bengal cat rescue, cat sanctuary';

// Get all cats (not just adoptable)
$sql = "SELECT * FROM cats ORDER BY intake_date DESC";
$cats = db()->fetchAll($sql);

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1>Our Cat Residents</h1>
        <p>Meet all the wonderful cats currently living at our sanctuary. Some are looking for forever homes, while others are permanent residents receiving specialized lifelong care.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Results Count -->
        <p class="mb-3" style="color: var(--text-light);">
            Currently caring for <?php echo count($cats); ?> cat<?php echo count($cats) !== 1 ? 's' : ''; ?>
        </p>
        
        <!-- Cats Grid -->
        <?php if (count($cats) > 0): ?>
        <div class="grid grid-3">
            <?php foreach ($cats as $cat): ?>
            <div class="card cat-card">
                <span class="cat-status <?php echo e($cat['status']); ?>">
                    <?php echo ucfirst(e($cat['status'])); ?>
                </span>
                <img src="<?php echo e(cat_photo($cat)); ?>" alt="<?php echo e($cat['name']); ?>" class="card-image">
                <div class="card-content">
                    <h3 class="card-title"><?php echo e($cat['name']); ?></h3>
                    <p class="card-text"><?php echo e(truncate($cat['bio'], 100)); ?></p>
                    <div class="cat-badges">
                        <span class="badge"><?php echo e($cat['age']); ?></span>
                        <span class="badge"><?php echo e($cat['sex'] === 'M' ? 'Male' : 'Female'); ?></span>
                        <span class="badge"><?php echo e($cat['species']); ?></span>
                    </div>
                    
                    <?php 
                    $badges = json_decode($cat['badges'] ?? '[]', true);
                    if ($badges && count($badges) > 0): 
                    ?>
                    <div class="cat-badges mt-2">
                        <?php foreach (array_slice($badges, 0, 3) as $badge): ?>
                        <span class="badge" style="background-color: var(--accent-color);"><?php echo e($badge); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <a href="/cat-detail.php?id=<?php echo e($cat['id']); ?>" class="btn btn-primary" style="width: 100%;">Meet <?php echo e($cat['name']); ?></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="card">
            <div class="card-content text-center" style="padding: 3rem;">
                <h3>No residents yet</h3>
                <p>We're just getting started! Check back soon to meet our cat friends.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Support Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container text-center">
        <div class="section-header">
            <h2 class="section-title">Support Our Cat Residents</h2>
            <p class="section-subtitle">Your donation helps us provide specialized food, expert medical care, and love to all our cats. These animals depend on your generosity.</p>
        </div>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="/donate.php" class="btn btn-primary btn-lg">Donate Now</a>
            <a href="/adoptions.php" class="btn btn-outline btn-lg">Adopt a Cat</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
