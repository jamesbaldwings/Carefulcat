<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Donate - Help Rescue Small Exotic Cats';
$metaDescription = 'Support Careful Cat Rescue with a tax-deductible donation. Your contribution helps us rescue, rehabilitate, and rehome small exotic cats including servals, savannahs, bengals, and caracals.';
$metaKeywords = 'donate exotic cat rescue, support exotic feline sanctuary, exotic cat charity, tax deductible donation, sponsor exotic cat, Murfreesboro TN nonprofit';

// Get sponsor cat if specified
$sponsorCatId = isset($_GET['sponsor']) ? sanitize($_GET['sponsor']) : '';
$sponsorCat = null;

if ($sponsorCatId) {
    $sponsorCat = db()->fetchOne("SELECT * FROM cats WHERE id = ?", [$sponsorCatId]);
}

// Get all cats for sponsorship dropdown
$cats = db()->fetchAll("SELECT id, name, status FROM cats WHERE status IN ('adoptable', 'resident') ORDER BY name ASC");

$additionalJS = ['https://js.stripe.com/v3/'];

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1>Make a Difference for Exotic Felines</h1>
        <p>Your donation helps us provide specialized food, expert medical care, safe enclosures, and love to small exotic cats in need. Every contribution saves lives!</p>
    </div>
</section>

<!-- Urgency / Emotional Appeal -->
<section style="background: linear-gradient(135deg, #fff5f5, #ffe8e8); padding: 2rem 0;">
    <div class="container text-center">
        <p style="font-size: 1.2rem; font-weight: 600; color: var(--primary-color); margin: 0;">
            Right now, exotic cats are waiting for rescue. Your donation today means one more life saved.
        </p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid grid-2" style="gap: 3rem; align-items: start;">
            <!-- Left Column - Donation Form -->
            <div>
                <div class="card" style="border: 2px solid var(--primary-color);">
                    <div class="card-content">
                        <h2 style="color: var(--primary-color);">Make a Donation</h2>
                        <p style="margin-bottom: 1.5rem; color: var(--text-light);">100% of your donation goes directly to exotic cat care. It takes less than 2 minutes.</p>
                        
                        <form id="donationForm">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                            
                            <div class="form-group">
                                <label class="form-label">Donation Type</label>
                                <div style="display: flex; gap: 1rem;">
                                    <label style="flex: 1; cursor: pointer; padding: 12px; border: 2px solid var(--border-color); border-radius: var(--border-radius); text-align: center; transition: all 0.3s;">
                                        <input type="radio" name="type" value="one-time" checked style="margin-right: 0.5rem;">
                                        One-Time
                                    </label>
                                    <label style="flex: 1; cursor: pointer; padding: 12px; border: 2px solid var(--border-color); border-radius: var(--border-radius); text-align: center; transition: all 0.3s;">
                                        <input type="radio" name="type" value="monthly" style="margin-right: 0.5rem;">
                                        Monthly <small style="display: block; color: var(--primary-color); font-weight: 600;">Most Impact</small>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Choose Your Impact</label>
                                <div class="grid grid-3" style="gap: 0.5rem; margin-bottom: 1rem;">
                                    <button type="button" class="btn btn-outline amount-btn" data-amount="2500" style="position: relative;">
                                        $25
                                        <small style="display: block; font-size: 0.7rem; color: var(--text-light);">Feed 1 cat/week</small>
                                    </button>
                                    <button type="button" class="btn btn-primary amount-btn" data-amount="5000" style="position: relative;">
                                        $50
                                        <small style="display: block; font-size: 0.7rem;">Vet checkup</small>
                                    </button>
                                    <button type="button" class="btn btn-outline amount-btn" data-amount="10000" style="position: relative;">
                                        $100
                                        <small style="display: block; font-size: 0.7rem; color: var(--text-light);">Emergency care</small>
                                    </button>
                                </div>
                                <div class="grid grid-3" style="gap: 0.5rem; margin-bottom: 1rem;">
                                    <button type="button" class="btn btn-outline amount-btn" data-amount="25000">$250</button>
                                    <button type="button" class="btn btn-outline amount-btn" data-amount="50000">$500</button>
                                    <button type="button" class="btn btn-outline amount-btn" data-amount="100000">$1,000</button>
                                </div>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-light);">$</span>
                                    <input type="number" name="amount" id="customAmount" class="form-control" placeholder="Custom amount" min="5" value="50" style="padding-left: 30px;" required>
                                </div>
                                <small style="color: var(--text-light);">Minimum donation: $5</small>
                            </div>
                            
                            <?php if (count($cats) > 0): ?>
                            <div class="form-group">
                                <label class="form-label">Sponsor a Specific Exotic Cat (Optional)</label>
                                <select name="sponsored_cat_id" class="form-control">
                                    <option value="">General Donation - Where Needed Most</option>
                                    <?php foreach ($cats as $cat): ?>
                                    <option value="<?php echo e($cat['id']); ?>" <?php echo $sponsorCat && $sponsorCat['id'] === $cat['id'] ? 'selected' : ''; ?>>
                                        <?php echo e($cat['name']); ?> (<?php echo ucfirst(e($cat['status'])); ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                            
                            <div class="grid grid-2">
                                <div class="form-group">
                                    <label class="form-label">First Name *</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Last Name *</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Card Information *</label>
                                <div id="card-element" style="padding: 12px; border: 1px solid var(--border-color); border-radius: var(--border-radius);"></div>
                                <div id="card-errors" class="form-error" style="margin-top: 0.5rem;"></div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 1.15rem; font-weight: 700;" id="submitBtn">
                                Donate Now &rarr;
                            </button>
                            <p style="text-align: center; margin-top: 0.75rem; font-size: 0.85rem; color: var(--text-light);">
                                🔒 Secure payment powered by Stripe. Your data is encrypted and safe.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Impact Information -->
            <div>
                <?php if ($sponsorCat): ?>
                <div class="card mb-3">
                    <img src="<?php echo e($sponsorCat['hero_photo']); ?>" alt="<?php echo e($sponsorCat['name']); ?>" class="card-image">
                    <div class="card-content">
                        <h3>Sponsor <?php echo e($sponsorCat['name']); ?></h3>
                        <p><?php echo e(truncate($sponsorCat['bio'], 150)); ?></p>
                        <a href="/cat-detail.php?id=<?php echo e($sponsorCat['id']); ?>" class="btn btn-outline">Learn More About <?php echo e($sponsorCat['name']); ?></a>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="card mb-3" style="border-left: 4px solid var(--primary-color);">
                    <div class="card-content">
                        <h3>Your Impact on Exotic Cat Lives</h3>
                        <p>Every donation helps us provide essential specialized care for small exotic cats in need. Here's exactly how your contribution makes a difference:</p>
                        
                        <div style="margin-top: 1.5rem;">
                            <div style="margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--border-color);">
                                <strong style="color: var(--primary-color); font-size: 1.25rem;">$25</strong>
                                <p style="margin: 0.5rem 0 0 0;">Provides high-protein food for one exotic cat for a week - servals, savannahs, and other exotic felines require specialized diets.</p>
                            </div>
                            
                            <div style="margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--border-color);">
                                <strong style="color: var(--primary-color); font-size: 1.25rem;">$50</strong>
                                <p style="margin: 0.5rem 0 0 0;">Covers a veterinary checkup with an exotic animal specialist - routine care that keeps our residents healthy.</p>
                            </div>
                            
                            <div style="margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--border-color);">
                                <strong style="color: var(--primary-color); font-size: 1.25rem;">$100</strong>
                                <p style="margin: 0.5rem 0 0 0;">Funds emergency medical treatment - many exotic cats arrive malnourished or injured and need immediate care.</p>
                            </div>
                            
                            <div style="margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--border-color);">
                                <strong style="color: var(--primary-color); font-size: 1.25rem;">$250</strong>
                                <p style="margin: 0.5rem 0 0 0;">Covers spay/neuter surgery and comprehensive vaccinations for one exotic feline.</p>
                            </div>
                            
                            <div>
                                <strong style="color: var(--primary-color); font-size: 1.25rem;">$500+</strong>
                                <p style="margin: 0.5rem 0 0 0;">Funds a full rescue operation - from transport to intake, medical evaluation, and initial rehabilitation of a newly rescued exotic cat.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-3" style="background-color: #f0fdf4;">
                    <div class="card-content">
                        <h3>Why Exotic Cats Need Your Help</h3>
                        <p>Small exotic cats like servals, caracals, savannahs, and bengals are often acquired as pets by owners who underestimate the specialized care they require. When these owners can no longer care for them, these beautiful animals need a safe place to go.</p>
                        <p style="font-weight: 600; margin-bottom: 0;">Your donation ensures they receive the expert care, proper nutrition, and safe environment they deserve while we find them the right forever home.</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-content">
                        <h3>Tax Deductible</h3>
                        <p>Careful Cat Rescue is a registered 501(c)(3) nonprofit organization (EIN: 39-4358631). Your donation is tax-deductible to the full extent allowed by law.</p>
                        <p style="font-size: 0.9rem; color: var(--text-light); margin-bottom: 0;">You will receive a receipt via email for your tax records.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Other Ways to Help -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Other Ways to Support Our Exotic Cats</h2>
        </div>
        <div class="grid grid-3">
            <div class="card">
                <div class="card-content text-center">
                    <h4>Volunteer Your Time</h4>
                    <p>Help care for our exotic residents, assist at events, or support our operations.</p>
                    <a href="/volunteer.php" class="btn btn-outline mt-2">Learn More</a>
                </div>
            </div>
            <div class="card">
                <div class="card-content text-center">
                    <h4>Donate Supplies</h4>
                    <p>We always need high-protein food, enrichment toys, bedding, and medical supplies.</p>
                    <a href="/contact.php" class="btn btn-outline mt-2">View Wish List</a>
                </div>
            </div>
            <div class="card">
                <div class="card-content text-center">
                    <h4>Become a Sponsor</h4>
                    <p>Business sponsorships help fund our mission while giving your brand visibility.</p>
                    <a href="/sponsors.php" class="btn btn-outline mt-2">Sponsorship Info</a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Stripe Integration
const stripe = Stripe('<?php echo STRIPE_PUBLIC_KEY; ?>');
const elements = stripe.elements();
const cardElement = elements.create('card', {
    style: {
        base: {
            fontSize: '16px',
            color: '#2d3436',
            fontFamily: '"Open Sans", sans-serif',
            '::placeholder': {
                color: '#636e72'
            }
        }
    }
});

cardElement.mount('#card-element');

cardElement.on('change', function(event) {
    const displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

// Amount buttons
document.querySelectorAll('.amount-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const amount = parseInt(this.dataset.amount) / 100;
        document.getElementById('customAmount').value = amount;
        
        document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('btn-primary'));
        document.querySelectorAll('.amount-btn').forEach(b => b.classList.add('btn-outline'));
        this.classList.remove('btn-outline');
        this.classList.add('btn-primary');
    });
});

// Form submission
document.getElementById('donationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = 'Processing Your Donation...';
    submitBtn.disabled = true;
    
    const formData = new FormData(this);
    const amount = parseFloat(formData.get('amount')) * 100; // Convert to cents
    
    if (amount < 500) {
        showAlert('error', 'Minimum donation amount is $5');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        return;
    }
    
    try {
        // Create payment intent
        const response = await fetch('/api/donate.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                amount: Math.round(amount),
                type: formData.get('type'),
                first_name: formData.get('first_name'),
                last_name: formData.get('last_name'),
                email: formData.get('email'),
                sponsored_cat_id: formData.get('sponsored_cat_id') || null,
                csrf_token: formData.get('csrf_token')
            })
        });
        
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'Payment failed');
        }
        
        // Confirm payment with Stripe
        const result = await stripe.confirmCardPayment(data.clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: formData.get('first_name') + ' ' + formData.get('last_name'),
                    email: formData.get('email')
                }
            }
        });
        
        if (result.error) {
            throw new Error(result.error.message);
        }
        
        // Success!
        window.location.href = '/thank-you.php?donation=' + data.donationId;
        
    } catch (error) {
        showAlert('error', error.message);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
