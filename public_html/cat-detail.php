<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? sanitize($_GET['id']) : '';

if (empty($id)) {
    redirect('/adoptions.php');
}

$cat = db()->fetchOne("SELECT * FROM cats WHERE id = ?", [$id]);

if (!$cat) {
    flash('error', 'Cat not found.');
    redirect('/adoptions.php');
}

$pageTitle = $cat['name'];
$metaDescription = $cat['bio'] ? truncate($cat['bio'], 150) : 'Meet ' . $cat['name'] . ', available for adoption.';

require_once __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container" style="max-width: 1000px;">
        <a href="/adoptions.php" style="display: inline-block; margin-bottom: 1rem; color: var(--text-light);">
            ← Back to Adoptable Cats
        </a>
        
        <div class="cat-detail-card">
            <div class="cat-detail-grid">
                <!-- Cat Image -->
                <div class="cat-detail-image">
                    <img src="<?php echo e($cat['hero_photo']); ?>" alt="<?php echo e($cat['name']); ?>">
                    <?php if ($cat['status'] === 'adoptable'): ?>
                    <span class="status-badge adoptable">Adoptable</span>
                    <?php elseif ($cat['status'] === 'adopted'): ?>
                    <span class="status-badge adopted">Adopted</span>
                    <?php elseif ($cat['status'] === 'pending'): ?>
                    <span class="status-badge pending">Adoption Pending</span>
                    <?php endif; ?>
                </div>
                
                <!-- Cat Info -->
                <div class="cat-detail-info">
                    <h1 class="cat-name"><?php echo e($cat['name']); ?></h1>
                    
                    <div class="cat-badges">
                        <span class="badge"><?php echo e($cat['age']); ?></span>
                        <span class="badge"><?php echo e($cat['sex'] === 'M' ? 'Male' : 'Female'); ?></span>
                        <span class="badge"><?php echo e($cat['species']); ?></span>
                        <?php if ($cat['fee']): ?>
                        <span class="badge fee">Adoption Fee: $<?php echo e($cat['fee']); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="cat-section">
                        <h2>About <?php echo e($cat['name']); ?></h2>
                        <p><?php echo nl2br(e($cat['bio'])); ?></p>
                    </div>
                    
                    <?php if ($cat['location']): ?>
                    <div class="cat-section">
                        <h3>📍 Location</h3>
                        <p><?php echo e($cat['location']); ?></p>
                        <?php if ($cat['intake_date']): ?>
                        <p style="font-size: 14px; color: #666;">
                            Intake Date: <?php echo date('M d, Y', strtotime($cat['intake_date'])); ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Action Buttons -->
                    <?php if ($cat['status'] === 'adoptable'): ?>
                    <div class="cat-actions">
                        <a href="/adopt-application.php?cat=<?php echo e($cat['id']); ?>" class="btn btn-primary btn-lg">
                            Apply to Adopt
                        </a>
                        <a href="/sponsor.php?cat=<?php echo e($cat['id']); ?>" class="btn btn-secondary btn-lg">
                            Sponsor <?php echo e($cat['name']); ?>
                        </a>
                    </div>
                    <?php elseif ($cat['status'] === 'adopted'): ?>
                    <div class="cat-actions">
                        <p style="color: #10b981; font-weight: 600; font-size: 18px;">
                            ✅ <?php echo e($cat['name']); ?> has been adopted!
                        </p>
                        <a href="/adoptions.php" class="btn btn-primary">
                            View Other Adoptable Cats
                        </a>
                    </div>
                    <?php elseif ($cat['status'] === 'pending'): ?>
                    <div class="cat-actions">
                        <p style="color: #f59e0b; font-weight: 600; font-size: 18px;">
                            ⏳ <?php echo e($cat['name']); ?>'s adoption is pending
                        </p>
                        <a href="/adoptions.php" class="btn btn-primary">
                            View Other Adoptable Cats
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.cat-detail-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.cat-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
}

.cat-detail-image {
    position: relative;
}

.cat-detail-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    min-height: 500px;
}

.status-badge {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.adoptable {
    background-color: var(--primary-color);
    color: white;
}

.status-badge.adopted {
    background-color: #10b981;
    color: white;
}

.status-badge.pending {
    background-color: #f59e0b;
    color: white;
}

.cat-detail-info {
    padding: 2.5rem;
}

.cat-name {
    font-size: 2.5rem;
    color: var(--primary-color);
    margin: 0 0 1rem 0;
}

.cat-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-bottom: 2rem;
}

.badge {
    display: inline-block;
    padding: 6px 16px;
    background-color: #e5e7eb;
    border-radius: 16px;
    font-size: 14px;
    font-weight: 500;
}

.badge.fee {
    background-color: var(--primary-color);
    color: white;
}

.cat-section {
    margin-bottom: 2rem;
}

.cat-section h2 {
    font-size: 1.5rem;
    color: var(--heading-color);
    margin: 0 0 1rem 0;
}

.cat-section h3 {
    font-size: 1.2rem;
    color: var(--heading-color);
    margin: 0 0 0.75rem 0;
}

.cat-section p {
    line-height: 1.8;
    color: var(--text-color);
}

.cat-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 2rem;
}

.btn-lg {
    padding: 14px 28px;
    font-size: 1.1rem;
    text-align: center;
}

@media (max-width: 768px) {
    .cat-detail-grid {
        grid-template-columns: 1fr;
    }
    
    .cat-detail-image img {
        min-height: 300px;
    }
    
    .cat-detail-info {
        padding: 1.5rem;
    }
    
    .cat-name {
        font-size: 2rem;
    }
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
