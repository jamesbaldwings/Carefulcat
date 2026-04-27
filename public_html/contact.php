<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Contact Us - Careful Cat Rescue';
$metaDescription = 'Get in touch with Careful Cat Rescue. We\'re here to answer your questions about exotic cat adoption, volunteering, donations, and more in Murfreesboro, TN.';
$metaKeywords = 'contact exotic cat rescue, Careful Cat Rescue contact, exotic feline adoption inquiry, Murfreesboro TN rescue contact';

// Get cat ID if specified (for adoption inquiries)
$catId = isset($_GET['cat']) ? sanitize($_GET['cat']) : '';
$cat = null;

if ($catId) {
    $cat = db()->fetchOne("SELECT * FROM cats WHERE id = ?", [$catId]);
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1>Contact Us</h1>
        <p>Have questions about our exotic cats? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid grid-2" style="gap: 3rem;">
            <!-- Left Column - Contact Form -->
            <div>
                <div class="card">
                    <div class="card-content">
                        <?php if ($cat): ?>
                        <div class="alert alert-info">
                            <strong>Inquiry about: <?php echo e($cat['name']); ?></strong>
                        </div>
                        <?php endif; ?>
                        
                        <h2>Send us a Message</h2>
                        
                        <form id="contactForm">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                            <?php if ($cat): ?>
                            <input type="hidden" name="subject" value="Adoption Inquiry: <?php echo e($cat['name']); ?>">
                            <?php endif; ?>
                            
                            <div class="grid grid-2">
                                <div class="form-group">
                                    <label class="form-label">First Name *</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                    <div class="form-error"></div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Last Name *</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                    <div class="form-error"></div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" required>
                                <div class="form-error"></div>
                            </div>
                            
                            <?php if (!$cat): ?>
                            <div class="form-group">
                                <label class="form-label">Subject *</label>
                                <select name="subject" class="form-control" required>
                                    <option value="">Select a subject</option>
                                    <option value="Adoption Inquiry">Exotic Cat Adoption Inquiry</option>
                                    <option value="Volunteer Opportunity">Volunteer Opportunity</option>
                                    <option value="Donation Question">Donation Question</option>
                                    <option value="Foster Program">Foster Program</option>
                                    <option value="Surrender Inquiry">Surrender Inquiry</option>
                                    <option value="General Question">General Question</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="form-error"></div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label class="form-label">Message *</label>
                                <textarea name="message" class="form-control" rows="6" required><?php if ($cat): ?>I'm interested in adopting <?php echo e($cat['name']); ?>. <?php endif; ?></textarea>
                                <div class="form-error"></div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" style="width: 100%;" id="submitBtn">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Contact Information -->
            <div>
                <div class="card mb-3">
                    <div class="card-content">
                        <h3>Contact Information</h3>
                        
                        <div style="margin-top: 1.5rem;">
                            <div style="display: flex; align-items: start; gap: 1rem; margin-bottom: 1.5rem;">
                                <svg width="24" height="24" fill="currentColor" style="color: var(--primary-color); flex-shrink: 0;">
                                    <use href="#icon-location"/>
                                </svg>
                                <div>
                                    <strong>Location</strong>
                                    <p style="margin: 0.25rem 0 0 0; color: var(--text-light);">
                                        <?php echo e(getSetting('site_address', 'Murfreesboro, TN')); ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div style="display: flex; align-items: start; gap: 1rem; margin-bottom: 1.5rem;">
                                <svg width="24" height="24" fill="currentColor" style="color: var(--primary-color); flex-shrink: 0;">
                                    <use href="#icon-email"/>
                                </svg>
                                <div>
                                    <strong>Email</strong>
                                    <p style="margin: 0.25rem 0 0 0;">
                                        <a href="mailto:<?php echo e(SITE_EMAIL); ?>"><?php echo e(SITE_EMAIL); ?></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-3">
                    <div class="card-content">
                        <h3>Visit Our Exotic Cat Sanctuary</h3>
                        <p>We welcome visitors by appointment. Please contact us to schedule a visit to meet our exotic cats.</p>
                        <?php if (isPageVisible('book_visit')): ?>
                        <a href="/book-visit.php" class="btn btn-primary">Schedule a Visit</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card mb-3">
                    <div class="card-content">
                        <h3>Hours</h3>
                        <p><strong>By Appointment Only</strong></p>
                        <p style="margin: 0; color: var(--text-light);">
                            Monday - Friday: 10am - 6pm<br>
                            Saturday: 10am - 4pm<br>
                            Sunday: Closed
                        </p>
                    </div>
                </div>
                
                <div class="card" style="border: 2px solid var(--primary-color); background: #fff5f5;">
                    <div class="card-content text-center">
                        <h4 style="color: var(--primary-color);">Support Our Mission</h4>
                        <p>Can't adopt or volunteer right now? A donation makes a huge difference for our exotic cats.</p>
                        <a href="/donate.php" class="btn btn-primary" style="width: 100%;">Donate Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('contactForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!validateForm(this)) {
        return;
    }
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Sending...';
    submitBtn.disabled = true;
    
    try {
        const result = await submitForm(this, '/api/contact.php');
        
        if (result.success) {
            showAlert('success', 'Thank you for your message! We\'ll get back to you soon.');
            this.reset();
        } else {
            showAlert('error', result.message || 'Failed to send message. Please try again.');
        }
    } catch (error) {
        showAlert('error', 'An error occurred. Please try again.');
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
