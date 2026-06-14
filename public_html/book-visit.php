<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Check if page is visible
if (!isPageVisible('book_visit')) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>Page Not Found</h1>';
    exit;
}

$pageTitle = 'Visit Our Cat Sanctuary - Book an Appointment';
$metaDescription = 'Schedule a visit to meet our cats and see our sanctuary in Murfreesboro, TN. All visits are by appointment only. Come meet servals, savannahs, bengals, and more.';
$metaKeywords = 'visit cat sanctuary, book cat visit, Murfreesboro TN cat sanctuary tour, meet serval savannah bengal caracal';

$successMessage = '';
$errorMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = ''; // Phone removed per owner request
    $preferred_date = sanitize($_POST['preferred_date'] ?? '');
    $preferred_time = sanitize($_POST['preferred_time'] ?? '');
    $num_visitors = (int)($_POST['num_visitors'] ?? 1);
    $purpose = sanitize($_POST['purpose'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    if ($name && $email && $preferred_date) {
        try {
            db()->execute(
                "INSERT INTO bookings (name, email, phone, preferred_date, preferred_time, num_visitors, purpose, message, status, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())",
                [$name, $email, $phone, $preferred_date, $preferred_time, $num_visitors, $purpose, $message]
            );
            $successMessage = 'Thank you for your visit request! We\'ll contact you soon to confirm your appointment.';
        } catch (Exception $e) {
            $errorMessage = 'Sorry, there was an error submitting your request. Please try again.';
        }
    } else {
        $errorMessage = 'Please fill in all required fields.';
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1>Visit Our Cat Sanctuary</h1>
        <p>We welcome visitors to come meet our cats and see our sanctuary. All visits are by appointment only to ensure the best experience for you and our incredible felines.</p>
    </div>
</section>

<!-- Visit Information Section -->
<section class="section">
    <div class="container">
        <div class="grid grid-2" style="gap: 3rem;">
            <div>
                <h2 class="mb-3">What to Expect</h2>
                <div class="card mb-3">
                    <div class="card-content">
                        <h4>Visiting Hours</h4>
                        <p>Tuesday - Saturday: 10:00 AM - 4:00 PM<br>
                        Sunday: 12:00 PM - 4:00 PM<br>
                        Monday: Closed</p>
                    </div>
                </div>
                
                <div class="card mb-3">
                    <div class="card-content">
                        <h4>Location</h4>
                        <p>Careful Cat Rescue<br>
                        Murfreesboro, TN<br>
                        <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a></p>
                    </div>
                </div>
                
                <div class="card mb-3">
                    <div class="card-content">
                        <h4>Visit Guidelines</h4>
                        <ul style="margin-left: 1.5rem;">
                            <li>All visits must be scheduled in advance</li>
                            <li>Please arrive on time for your appointment</li>
                            <li>Hand sanitizer will be provided upon arrival</li>
                            <li>Children must be supervised at all times</li>
                            <li>Please be calm and respectful with our cats</li>
                            <li>No outside food or drinks in cat areas</li>
                            <li>Follow all handler instructions for safety</li>
                        </ul>
                    </div>
                </div>
                
                <div class="card" style="border: 2px solid var(--primary-color); background: #fff5f5;">
                    <div class="card-content text-center">
                        <h4 style="color: var(--primary-color);">Love What We Do?</h4>
                        <p>After your visit, consider supporting our cats with a donation.</p>
                        <a href="/donate.php" class="btn btn-primary">Donate Now</a>
                    </div>
                </div>
            </div>
            
            <div>
                <h2 class="mb-3">Schedule Your Visit</h2>
                <div class="card">
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
                            <div class="form-group">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            
                            <div class="grid grid-2">
                                <div class="form-group">
                                    <label class="form-label">Preferred Date *</label>
                                    <input type="date" name="preferred_date" class="form-control" 
                                           min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Preferred Time</label>
                                    <select name="preferred_time" class="form-control">
                                        <option value="">Select time...</option>
                                        <option value="10:00 AM">10:00 AM</option>
                                        <option value="11:00 AM">11:00 AM</option>
                                        <option value="12:00 PM">12:00 PM</option>
                                        <option value="1:00 PM">1:00 PM</option>
                                        <option value="2:00 PM">2:00 PM</option>
                                        <option value="3:00 PM">3:00 PM</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="grid grid-2">
                                <div class="form-group">
                                    <label class="form-label">Number of Visitors</label>
                                    <input type="number" name="num_visitors" class="form-control" min="1" max="10" value="1">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Purpose of Visit</label>
                                    <select name="purpose" class="form-control">
                                        <option value="">Select...</option>
                                        <option value="Adoption">Interested in Adoption</option>
                                        <option value="General Visit">General Visit / Meet the Cats</option>
                                        <option value="Volunteer Interest">Volunteer Interest</option>
                                        <option value="Donation Drop-off">Donation / Supply Drop-off</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Additional Information</label>
                                <textarea name="message" class="form-control" rows="4" 
                                          placeholder="Is there a specific cat you'd like to meet? Any special requirements?"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">Request Visit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Frequently Asked Questions</h2>
        </div>
        
        <div style="max-width: 800px; margin: 0 auto;">
            <div class="card mb-3">
                <div class="card-content">
                    <h4>Do I need an appointment?</h4>
                    <p>Yes, all visits require an appointment. This helps us ensure we can give you and our cats the attention you deserve, and keeps our animals calm and comfortable.</p>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-content">
                    <h4>Can I bring my children?</h4>
                    <p>Yes! We welcome families. Children must be supervised by adults at all times and taught to be calm and gentle with our cats.</p>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-content">
                    <h4>Can I adopt a cat during my visit?</h4>
                    <p>While you can meet cats and express interest during your visit, adoptions require an application process. We can help you start the application during your visit.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h4>What should I bring?</h4>
                    <p>Just yourself! We provide everything you need. If you're interested in adoption, you may want to bring questions about cat care and your living situation.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
