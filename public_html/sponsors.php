<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Our Sponsors';
$metaDescription = 'Thank you to our generous sponsors who support Careful Cat Rescue and help us save lives.';

// Get all active sponsors
$sponsors = db()->fetchAll(
    "SELECT * FROM sponsors WHERE is_active = 1 ORDER BY display_order ASC, name ASC"
);

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1>Our Generous Sponsors</h1>
        <p>We are grateful to our sponsors whose support makes our rescue work possible. Thank you for believing in our mission!</p>
    </div>
</section>

<?php if (count($sponsors) > 0): ?>
<section class="section">
    <div class="container">
        <div class="grid grid-4">
            <?php foreach ($sponsors as $sponsor): ?>
            <div class="card">
                <div class="card-content text-center">
                    <?php if ($sponsor['logo_url']): ?>
                    <a href="<?php echo e($sponsor['website_url']); ?>" target="_blank" rel="noopener noreferrer">
                        <img src="<?php echo e($sponsor['logo_url']); ?>" 
                             alt="<?php echo e($sponsor['name']); ?>" 
                             style="max-height: 100px; width: auto; margin: 0 auto 1rem; display: block;">
                    </a>
                    <?php endif; ?>
                    
                    <h4><?php echo e($sponsor['name']); ?></h4>
                    
                    <?php if ($sponsor['description']): ?>
                    <p style="font-size: 0.875rem; color: var(--text-light); margin-top: 0.5rem;">
                        <?php echo e($sponsor['description']); ?>
                    </p>
                    <?php endif; ?>
                    
                    <?php if ($sponsor['website_url']): ?>
                    <a href="<?php echo e($sponsor['website_url']); ?>" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="btn btn-outline btn-sm mt-2">
                        Visit Website
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php else: ?>
<section class="section">
    <div class="container">
        <div class="card">
            <div class="card-content text-center" style="padding: 3rem;">
                <h3>No Sponsors Yet</h3>
                <p>We're currently building our sponsor network. Interested in becoming a sponsor?</p>
                <a href="/contact.php" class="btn btn-primary mt-3">Contact Us</a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Become a Sponsor Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Become a Sponsor</h2>
            <p class="section-subtitle">Partner with us to make a lasting impact on the lives of cats in need.</p>
        </div>
        
        <div class="grid grid-3">
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color); font-size: 2rem; margin-bottom: 1rem;">🌟</h3>
                    <h4>Bronze Sponsor</h4>
                    <p style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color); margin: 1rem 0;">$500/year</p>
                    <ul style="text-align: left; margin-left: 1.5rem;">
                        <li>Logo on website</li>
                        <li>Social media recognition</li>
                        <li>Quarterly newsletter feature</li>
                    </ul>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color); font-size: 2rem; margin-bottom: 1rem;">⭐</h3>
                    <h4>Silver Sponsor</h4>
                    <p style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color); margin: 1rem 0;">$1,000/year</p>
                    <ul style="text-align: left; margin-left: 1.5rem;">
                        <li>All Bronze benefits</li>
                        <li>Logo on promotional materials</li>
                        <li>Monthly social media shoutouts</li>
                        <li>Event recognition</li>
                    </ul>
                </div>
            </div>
            
            <div class="card" style="border: 2px solid var(--primary-color);">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color); font-size: 2rem; margin-bottom: 1rem;">💎</h3>
                    <h4>Gold Sponsor</h4>
                    <p style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color); margin: 1rem 0;">$2,500/year</p>
                    <ul style="text-align: left; margin-left: 1.5rem;">
                        <li>All Silver benefits</li>
                        <li>Premium logo placement</li>
                        <li>Dedicated blog post</li>
                        <li>VIP event access</li>
                        <li>Custom sponsorship package</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <p><strong>Interested in becoming a sponsor?</strong></p>
            <a href="/contact.php?subject=Sponsorship Inquiry" class="btn btn-primary">Contact Us About Sponsorship</a>
        </div>
    </div>
</section>

<!-- Sponsor Benefits Section -->
<section class="section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Why Sponsor Us?</h2>
        </div>
        
        <div class="grid grid-2" style="gap: 2rem; max-width: 900px; margin: 0 auto;">
            <div class="card">
                <div class="card-content">
                    <h4>🤝 Community Impact</h4>
                    <p>Show your commitment to animal welfare and community support.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h4>📣 Brand Visibility</h4>
                    <p>Reach thousands of animal lovers through our website and social media.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h4>💚 Feel-Good Factor</h4>
                    <p>Know that your support directly saves lives and helps cats find homes.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h4>🎯 Tax Benefits</h4>
                    <p>Sponsorships are tax-deductible as we are a registered 501(c)(3) nonprofit.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

