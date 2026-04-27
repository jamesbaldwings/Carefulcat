<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Small Exotic Cat Rescue in Murfreesboro, TN';
$metaDescription = 'Careful Cat Rescue is a nonprofit dedicated to rescuing, rehabilitating, and rehoming small exotic felines in Murfreesboro, TN. Adopt a serval, savannah, bengal, caracal, or other exotic cat today.';
$metaKeywords = 'exotic cat rescue, small exotic cat adoption, serval rescue, savannah cat rescue, bengal cat adoption, caracal rescue, exotic feline sanctuary, Murfreesboro TN, donate exotic cat rescue';

// Get featured cats (adoptable cats, limit 3)
$featuredCats = db()->fetchAll(
    "SELECT * FROM cats WHERE status = 'adoptable' ORDER BY intake_date DESC LIMIT 3"
);

// Get recent blog posts (limit 3)
$recentPosts = db()->fetchAll(
    "SELECT * FROM posts WHERE status = 'published' AND category = 'blog' ORDER BY published_at DESC LIMIT 3"
);

// Get active sponsors (limit to 8 for homepage)
$sponsors = db()->fetchAll(
    "SELECT * FROM sponsors WHERE is_active = 1 ORDER BY display_order ASC, name ASC LIMIT 8"
);

// Check if there are more sponsors
$totalSponsors = db()->fetchOne("SELECT COUNT(*) as count FROM sponsors WHERE is_active = 1")['count'] ?? 0;

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Saving Exotic Felines, One Paw at a Time</h1>
        <p>Welcome to Careful Cat Rescue, where every small exotic feline deserves a loving home. We rescue, rehabilitate, and rehome small exotic cats in need throughout Tennessee.</p>
        <div class="hero-actions">
            <?php if ($pageVisibility['adoptions']): ?>
            <a href="/adoptions.php" class="btn btn-primary">Adopt an Exotic Cat</a>
            <?php endif; ?>
            <a href="/donate.php" class="btn btn-outline" style="background: rgba(255,255,255,0.15); border: 2px solid white; color: white; font-weight: 700;">Donate Now</a>
            <?php if ($pageVisibility['volunteer']): ?>
            <a href="/volunteer.php" class="btn btn-secondary">Volunteer</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">About Our Mission</h2>
            <p class="section-subtitle">
                Careful Cat Rescue is a nonprofit organization dedicated to rescuing abandoned, neglected, and homeless small exotic felines. 
                We provide medical care, rehabilitation, and a safe environment while they await their forever homes.
            </p>
        </div>
        
        <div class="grid grid-3">
            <div class="card">
                <div class="card-content text-center">
                    <h3>🏠 Rescue</h3>
                    <p>We rescue small exotic cats from difficult situations and provide them with immediate care and safety.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h3>💊 Rehabilitate</h3>
                    <p>Every exotic feline receives medical attention, vaccinations, spay/neuter services, and behavioral support.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h3>❤️ Rehome</h3>
                    <p>We carefully match these exotic cats with loving families to ensure they find their perfect forever homes.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if ($pageVisibility['adoptions'] && count($featuredCats) > 0): ?>
<!-- Featured Cats Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Meet Our Adoptable Exotic Cats</h2>
            <p class="section-subtitle">These wonderful exotic felines are looking for their forever homes. Could one of them be your new companion?</p>
        </div>
        
        <div class="grid grid-3">
            <?php foreach ($featuredCats as $cat): ?>
            <div class="card cat-card">
                <span class="cat-status <?php echo e($cat['status']); ?>">
                    <?php echo ucfirst(e($cat['status'])); ?>
                </span>
                <img src="<?php echo e($cat['hero_photo']); ?>" alt="<?php echo e($cat['name']); ?>" class="card-image">
                <div class="card-content">
                    <h3 class="card-title"><?php echo e($cat['name']); ?></h3>
                    <p class="card-text"><?php echo e(truncate($cat['bio'], 100)); ?></p>
                    <div class="cat-badges">
                        <span class="badge"><?php echo e($cat['age']); ?></span>
                        <span class="badge"><?php echo e($cat['sex'] === 'M' ? 'Male' : 'Female'); ?></span>
                        <?php if ($cat['fee']): ?>
                        <span class="badge">$<?php echo e($cat['fee']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="/cat-detail.php?id=<?php echo e($cat['id']); ?>" class="btn btn-primary" style="width: 100%;">Learn More</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="/adoptions.php" class="btn btn-primary">View All Adoptable Exotic Cats</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- How You Can Help Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">How You Can Help</h2>
            <p class="section-subtitle">There are many ways to support our mission and make a difference in the lives of small exotic cats in need.</p>
        </div>
        
        <div class="grid grid-4">
            <?php if ($pageVisibility['adoptions']): ?>
            <div class="card">
                <div class="card-content text-center">
                    <h3>Adopt</h3>
                    <p>Give a small exotic cat a loving forever home and gain a loyal companion.</p>
                    <a href="/adoptions.php" class="btn btn-outline mt-2">Adopt Now</a>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="card" style="border: 2px solid var(--primary-color);">
                <div class="card-content text-center">
                    <h3>Donate</h3>
                    <p>Your donation helps us provide specialized food, medical care, and shelter for exotic felines.</p>
                    <a href="/donate.php" class="btn btn-primary mt-2">Donate Now</a>
                </div>
            </div>
            
            <?php if ($pageVisibility['volunteer']): ?>
            <div class="card">
                <div class="card-content text-center">
                    <h3>Volunteer</h3>
                    <p>Join our team and help care for exotic felines while they await adoption.</p>
                    <a href="/volunteer.php" class="btn btn-outline mt-2">Volunteer</a>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-content text-center">
                    <h3>Sponsor</h3>
                    <p>Sponsor an exotic cat's care and follow their journey to a new home.</p>
                    <a href="/donate.php" class="btn btn-outline mt-2">Sponsor</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Urgent Need / Impact Section -->
<section class="section" style="background: linear-gradient(135deg, #fff5f5, #ffe0e0); padding: 3rem 0;">
    <div class="container text-center">
        <h2 class="section-title" style="color: var(--primary-color);">Your Donation Makes a Real Difference</h2>
        <p style="font-size: 1.15rem; max-width: 700px; margin: 0 auto 2rem;">Exotic cats require specialized diets, larger enclosures, and expert veterinary care. Your generosity directly funds the rescue and rehabilitation of these incredible animals.</p>
        <div class="grid grid-3" style="max-width: 800px; margin: 0 auto 2rem;">
            <div class="text-center">
                <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary-color); margin: 0;">$25</p>
                <p style="color: var(--text-light);">Feeds one exotic cat for a week</p>
            </div>
            <div class="text-center">
                <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary-color); margin: 0;">$50</p>
                <p style="color: var(--text-light);">Covers a veterinary checkup</p>
            </div>
            <div class="text-center">
                <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary-color); margin: 0;">$100</p>
                <p style="color: var(--text-light);">Provides emergency medical care</p>
            </div>
        </div>
        <a href="/donate.php" class="btn btn-primary" style="padding: 14px 40px; font-size: 1.15rem;">Make a Donation Today</a>
    </div>
</section>

<?php if ($pageVisibility['blog'] && count($recentPosts) > 0): ?>
<!-- Recent Blog Posts Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Latest News & Stories</h2>
            <p class="section-subtitle">Stay updated with our rescue stories, adoption success stories, and exotic cat care tips.</p>
        </div>
        
        <div class="grid grid-3">
            <?php foreach ($recentPosts as $post): ?>
            <div class="card">
                <?php if ($post['cover_image_url']): ?>
                <img src="<?php echo e($post['cover_image_url']); ?>" alt="<?php echo e($post['title']); ?>" class="card-image">
                <?php endif; ?>
                <div class="card-content">
                    <h3 class="card-title"><?php echo e($post['title']); ?></h3>
                    <p class="card-text"><?php echo e(truncate($post['excerpt'] ?: strip_tags($post['content']), 120)); ?></p>
                    <p style="font-size: 0.875rem; color: var(--text-light);">
                        <?php echo formatDate($post['published_at']); ?>
                    </p>
                </div>
                <div class="card-footer">
                    <a href="/post.php?slug=<?php echo e($post['slug']); ?>" class="btn btn-outline" style="width: 100%;">Read More</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="/blog.php" class="btn btn-primary">View All Posts</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action Section -->
<section class="section">
    <div class="container text-center">
        <h2 class="section-title">Ready to Make a Difference?</h2>
        <p class="section-subtitle">
            Every small exotic feline deserves a second chance. Whether you adopt, donate, or volunteer, you're helping save lives.
        </p>
        <br>
        <div class="hero-actions">
            <?php if ($pageVisibility['adoptions']): ?>
            <a href="/adoptions.php" class="btn btn-primary">Find Your New Companion</a>
            <?php endif; ?>
            <a href="/donate.php" class="btn btn-primary" style="background-color: #27ae60;">Donate Now</a>
            <a href="/contact.php" class="btn btn-secondary">Get in Touch</a>
        </div>
    </div>
</section>

<?php if (count($sponsors) > 0): ?>
<!-- Sponsors Section -->
<section class="section" style="background-color: var(--bg-light); padding: 40px 0;">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Our Generous Sponsors</h2>
            <p class="section-subtitle">Thank you to our amazing sponsors who make our work possible!</p>
        </div>
        <div class="grid grid-4">
            <?php foreach ($sponsors as $sponsor): ?>
            <div class="card">
                <div class="card-content text-center">
                    <?php if ($sponsor['logo_url']): ?>
                    <a href="<?php echo e($sponsor['website_url']); ?>" target="_blank" rel="noopener noreferrer">
                        <img src="<?php echo e($sponsor['logo_url']); ?>" alt="<?php echo e($sponsor['name']); ?>" style="max-height: 80px; width: auto; margin: 0 auto; display: block;">
                    </a>
                    <?php else: ?>
                    <h4><?php echo e($sponsor['name']); ?></h4>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if ($totalSponsors > 8): ?>
        <div class="text-center mt-4">
            <a href="/sponsors.php" class="btn btn-primary">View All Sponsors</a>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
