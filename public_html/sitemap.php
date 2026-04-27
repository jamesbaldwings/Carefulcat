<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Site Map - Careful Cat Rescue';
$metaDescription = 'Navigate the Careful Cat Rescue website. Find all pages about exotic cat adoption, rescue, volunteering, and donating in Murfreesboro, TN.';

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1>Site Map</h1>
        <p>Find everything on our website quickly and easily.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid grid-3">
            
            <!-- Main Pages -->
            <div class="card">
                <div class="card-content">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Main Pages</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                            <a href="/">Home</a>
                        </li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                            <a href="/about.php">About Us</a>
                        </li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                            <a href="/adoptions.php">Adoptions</a>
                        </li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                            <a href="/residents.php">Our Residents</a>
                        </li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                            <a href="/donate.php">Donate</a>
                        </li>
                        <li style="padding: 0.5rem 0;">
                            <a href="/contact.php">Contact Us</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Get Involved -->
            <div class="card">
                <div class="card-content">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Get Involved</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                            <a href="/volunteer.php">Volunteer</a>
                        </li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                            <a href="/book-visit.php">Visit Our Sanctuary</a>
                        </li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                            <a href="/sponsors.php">Our Sponsors</a>
                        </li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                            <a href="/shop.php">Shop Merchandise</a>
                        </li>
                        <li style="padding: 0.5rem 0;">
                            <a href="/blog.php">Blog & News</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Resources -->
            <div class="card">
                <div class="card-content">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Resources</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                            <a href="/faq.php">FAQ</a>
                        </li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                            <a href="/privacy.php">Privacy Policy</a>
                        </li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                            <a href="/terms.php">Terms of Service</a>
                        </li>
                        <li style="padding: 0.5rem 0;">
                            <a href="/sitemap.php">Site Map</a>
                        </li>
                    </ul>
                </div>
            </div>
            
        </div>
        
        <!-- Quick Actions -->
        <div class="mt-5">
            <div class="card">
                <div class="card-content">
                    <h3 style="color: var(--primary-color); margin-bottom: 1.5rem; text-align: center;">Quick Actions</h3>
                    <div class="grid grid-4">
                        <div class="text-center">
                            <a href="/adoptions.php" class="btn btn-primary" style="width: 100%;">Adopt an Exotic Cat</a>
                        </div>
                        <div class="text-center">
                            <a href="/donate.php" class="btn btn-primary" style="width: 100%;">Make a Donation</a>
                        </div>
                        <div class="text-center">
                            <a href="/volunteer.php" class="btn btn-primary" style="width: 100%;">Become a Volunteer</a>
                        </div>
                        <div class="text-center">
                            <a href="/book-visit.php" class="btn btn-primary" style="width: 100%;">Schedule a Visit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Info -->
        <div class="mt-4">
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Contact Information</h3>
                    <p>
                        <strong>Careful Cat Rescue</strong><br>
                        Murfreesboro, TN<br>
                        Email: <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a><br>
                        <a href="/contact.php" class="btn btn-outline mt-3">Send Us a Message</a>
                    </p>
                </div>
            </div>
        </div>
        
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

