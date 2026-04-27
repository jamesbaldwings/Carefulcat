<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Check if page is visible
if (!isPageVisible('blog')) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>Page Not Found</h1>';
    exit;
}

$pageTitle = 'Blog & News - Exotic Cat Rescue Stories';
$metaDescription = 'Read the latest news, exotic cat rescue success stories, care tips, and updates from Careful Cat Rescue in Murfreesboro, TN.';
$metaKeywords = 'exotic cat rescue blog, small exotic cat news, serval rescue stories, savannah cat care tips, exotic feline rescue updates Tennessee';

// Get published blog posts
$sql = "SELECT * FROM posts WHERE status = 'published' ORDER BY published_at DESC LIMIT 12";
$posts = db()->fetchAll($sql);

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1>Blog &amp; News</h1>
        <p>Stay updated with the latest exotic cat rescue news, success stories, care tips, and updates from our sanctuary.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (count($posts) > 0): ?>
        <div class="grid grid-3">
            <?php foreach ($posts as $post): ?>
            <div class="card">
                <?php if ($post['featured_image']): ?>
                <img src="<?php echo e($post['featured_image']); ?>" alt="<?php echo e($post['title']); ?>" class="card-image">
                <?php endif; ?>
                <div class="card-content">
                    <div class="mb-2">
                        <span class="badge"><?php echo e($post['category']); ?></span>
                        <span style="color: var(--text-light); font-size: 0.875rem; margin-left: 0.5rem;">
                            <?php echo date('M d, Y', strtotime($post['published_at'])); ?>
                        </span>
                    </div>
                    <h3 class="card-title"><?php echo e($post['title']); ?></h3>
                    <p class="card-text"><?php echo e(truncate(strip_tags($post['content']), 150)); ?></p>
                </div>
                <div class="card-footer">
                    <a href="/blog-post.php?slug=<?php echo e($post['slug']); ?>" class="btn btn-outline" style="width: 100%;">Read More</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="card">
            <div class="card-content text-center" style="padding: 3rem;">
                <h3>No posts yet</h3>
                <p>We're working on some great content! Check back soon for updates and stories from our exotic cat rescue.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Donate CTA -->
<section class="section" style="background: linear-gradient(135deg, var(--primary-color), #c0392b); color: white; text-align: center;">
    <div class="container">
        <h2 style="color: white; margin-bottom: 0.5rem;">Every Story Starts With Your Support</h2>
        <p style="margin-bottom: 1.5rem; opacity: 0.95; font-size: 1.1rem;">Behind every rescue story is a generous donor who made it possible. Help us write more happy endings for exotic cats in need.</p>
        <a href="/donate.php" class="btn" style="background: white; color: var(--primary-color); font-weight: 700; padding: 12px 36px; border-radius: 30px;">Donate Now</a>
    </div>
</section>

<!-- Newsletter Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <div class="card" style="max-width: 600px; margin: 0 auto;">
            <div class="card-content text-center">
                <h2 class="mb-2">Subscribe to Our Newsletter</h2>
                <p class="mb-3">Get the latest exotic cat rescue updates, success stories, and adoption news delivered to your inbox.</p>
                <form method="POST" action="/api/newsletter-subscribe.php" class="form-inline">
                    <div class="form-group" style="flex: 1;">
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
