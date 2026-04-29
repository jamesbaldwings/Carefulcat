<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Check if page is visible
if (!isPageVisible('volunteer')) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>Page Not Found</h1>';
    exit;
}

$pageTitle = 'Volunteer With Us - Help Rescue Exotic Cats';
$metaDescription = 'Join our team of dedicated volunteers and help make a difference in the lives of small exotic cats in need. Volunteer at Careful Cat Rescue in Murfreesboro, TN.';
$metaKeywords = 'volunteer exotic cat rescue, exotic feline sanctuary volunteer, Murfreesboro TN animal volunteer, small exotic cat care volunteer';

$successMessage = '';
$errorMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = ''; // Phone removed per owner request
    $availability = sanitize($_POST['availability'] ?? '');
    $interests = isset($_POST['interests']) ? json_encode($_POST['interests']) : '[]';
    $experience = sanitize($_POST['experience'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    if ($name && $email) {
        try {
            db()->execute(
                "INSERT INTO volunteers (name, email, phone, availability, interests, experience, message, status, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())",
                [$name, $email, $phone, $availability, $interests, $experience, $message]
            );
            $successMessage = 'Thank you for your interest in volunteering with our exotic cats! We\'ll be in touch soon.';
        } catch (Exception $e) {
            $errorMessage = 'Sorry, there was an error submitting your application. Please try again.';
        }
    } else {
        $errorMessage = 'Please fill in all required fields.';
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1>Volunteer With Us</h1>
        <p>Join our team of dedicated volunteers and help make a difference in the lives of small exotic cats in need.</p>
    </div>
</section>

<!-- Why Volunteer Section -->
<section class="section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Why Volunteer?</h2>
            <p class="section-subtitle">Volunteering at Careful Cat Rescue is a uniquely rewarding experience that makes a real difference for exotic felines.</p>
        </div>
        
        <div class="grid grid-3">
            <div class="card">
                <div class="card-content text-center">
                    <h3>Make a Difference</h3>
                    <p>Your time and effort directly improves the lives of small exotic cats in our care, helping them heal and find forever homes.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h3>Learn & Grow</h3>
                    <p>Gain valuable experience in exotic animal care and rescue operations - skills you won't find anywhere else.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h3>Join Our Community</h3>
                    <p>Connect with fellow exotic cat lovers and make lasting friendships with people who share your passion.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Volunteer Opportunities Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Volunteer Opportunities</h2>
            <p class="section-subtitle">There are many ways to help our exotic cats - find the role that fits you best.</p>
        </div>
        
        <div class="grid grid-2">
            <div class="card">
                <div class="card-content">
                    <h3>Exotic Cat Care</h3>
                    <p>Feed, enrich, and socialize our exotic cats. Help keep their specialized enclosures clean and comfortable. This is hands-on work with our incredible animals.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h3>Adoption Events</h3>
                    <p>Help at adoption events, meet potential adopters, and showcase our wonderful exotic cats to find them loving forever homes.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h3>Foster Care</h3>
                    <p>Provide temporary homes for exotic cats in need of extra care or socialization. Experienced exotic cat owners preferred.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h3>Administrative Support</h3>
                    <p>Assist with paperwork, social media, fundraising, and other behind-the-scenes tasks that keep our rescue running.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h3>Transportation</h3>
                    <p>Help transport exotic cats to vet appointments, rescue pickups, or adoption events throughout Tennessee.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h3>Fundraising</h3>
                    <p>Organize or assist with fundraising events to support our mission of rescuing and rehoming small exotic felines.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Application Form Section -->
<section class="section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Apply to Volunteer</h2>
            <p class="section-subtitle">Fill out the form below and we'll get back to you soon!</p>
        </div>
        
        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <div class="card-content">
                <?php if ($successMessage): ?>
                <div class="alert alert-success mb-3">
                    <?php echo e($successMessage); ?>
                </div>
                <?php endif; ?>
                
                <?php if ($errorMessage): ?>
                <div class="alert alert-danger mb-3">
                    <?php echo e($errorMessage); ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="grid grid-2">
                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Availability</label>
                        <select name="availability" class="form-control">
                            <option value="">Select...</option>
                            <option value="Weekdays">Weekdays</option>
                            <option value="Weekends">Weekends</option>
                            <option value="Both">Both Weekdays & Weekends</option>
                            <option value="Flexible">Flexible</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Areas of Interest (select all that apply)</label>
                        <div class="grid grid-2">
                            <label class="checkbox-label">
                                <input type="checkbox" name="interests[]" value="Exotic Cat Care"> Exotic Cat Care
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="interests[]" value="Adoption Events"> Adoption Events
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="interests[]" value="Foster Care"> Foster Care
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="interests[]" value="Administrative"> Administrative Support
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="interests[]" value="Transportation"> Transportation
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="interests[]" value="Fundraising"> Fundraising
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Previous Experience with Exotic Animals</label>
                        <textarea name="experience" class="form-control" rows="3" placeholder="Tell us about any previous experience you have with exotic cats, servals, savannahs, bengals, or other animals..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Additional Information</label>
                        <textarea name="message" class="form-control" rows="4" placeholder="Is there anything else you'd like us to know?"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">Submit Volunteer Application</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Can't Volunteer CTA -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container text-center">
        <h2 class="section-title">Can't Volunteer Right Now?</h2>
        <p class="section-subtitle">Even a small donation makes a huge difference for our exotic cats. Every dollar goes directly to their care.</p>
        <a href="/donate.php" class="btn btn-primary" style="padding: 12px 36px; font-size: 1.1rem;">Donate to Support Our Exotic Cats</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
