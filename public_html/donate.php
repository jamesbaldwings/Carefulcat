<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Donate';
$metaDescription = 'Support Careful Cat Rescue with a donation. Your contribution helps us rescue, rehabilitate, and rehome cats in need.';

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
        <h1>Make a Difference</h1>
        <p>Your donation helps us provide food, medical care, shelter, and love to cats in need. Every contribution makes a difference!</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid grid-2" style="gap: 3rem; align-items: start;">
            <!-- Left Column - Donation Form -->
            <div>
                <div class="card">
                    <div class="card-content">
                        <h2>Make a Donation</h2>
                        
                        <form id="donationForm">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                            
                            <div class="form-group">
                                <label class="form-label">Donation Type</label>
                                <div style="display: flex; gap: 1rem;">
                                    <label style="flex: 1; cursor: pointer;">
                                        <input type="radio" name="type" value="one-time" checked style="margin-right: 0.5rem;">
                                        One-Time
                                    </label>
                                    <label style="flex: 1; cursor: pointer;">
                                        <input type="radio" name="type" value="monthly" style="margin-right: 0.5rem;">
                                        Monthly
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Donation Amount</label>
                                <div class="grid grid-3" style="gap: 0.5rem; margin-bottom: 1rem;">
                                    <button type="button" class="btn btn-outline amount-btn" data-amount="2500">$25</button>
                                    <button type="button" class="btn btn-outline amount-btn" data-amount="5000">$50</button>
                                    <button type="button" class="btn btn-outline amount-btn" data-amount="10000">$100</button>
                                </div>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-light);">$</span>
                                    <input type="number" name="amount" id="customAmount" class="form-control" placeholder="Custom amount" min="5" style="padding-left: 30px;" required>
                                </div>
                                <small style="color: var(--text-light);">Minimum donation: $5</small>
                            </div>
                            
                            <?php if (count($cats) > 0): ?>
                            <div class="form-group">
                                <label class="form-label">Sponsor a Specific Cat (Optional)</label>
                                <select name="sponsored_cat_id" class="form-control">
                                    <option value="">General Donation</option>
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
                            
                            <button type="submit" class="btn btn-primary" style="width: 100%;" id="submitBtn">
                                Donate Now
                            </button>
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
                        <a href="/cat-detail.php?id=<?php echo e($sponsorCat['id']); ?>" class="btn btn-outline">Learn More</a>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="card mb-3">
                    <div class="card-content">
                        <h3>Your Impact</h3>
                        <p>Every donation helps us provide essential care for cats in need. Here's how your contribution makes a difference:</p>
                        
                        <div style="margin-top: 1.5rem;">
                            <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                                <strong style="color: var(--primary-color);">$25</strong>
                                <p style="margin: 0.5rem 0 0 0; color: var(--text-light);">Provides food for one cat for a month</p>
                            </div>
                            
                            <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                                <strong style="color: var(--primary-color);">$50</strong>
                                <p style="margin: 0.5rem 0 0 0; color: var(--text-light);">Covers basic vaccinations for one cat</p>
                            </div>
                            
                            <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                                <strong style="color: var(--primary-color);">$100</strong>
                                <p style="margin: 0.5rem 0 0 0; color: var(--text-light);">Funds spay/neuter surgery</p>
                            </div>
                            
                            <div>
                                <strong style="color: var(--primary-color);">$250+</strong>
                                <p style="margin: 0.5rem 0 0 0; color: var(--text-light);">Provides comprehensive medical care including emergency treatment</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-content">
                        <h3>Tax Deductible</h3>
                        <p>Careful Cat Rescue is a 501(c)(3) nonprofit organization. Your donation is tax-deductible to the extent allowed by law.</p>
                        <p style="font-size: 0.9rem; color: var(--text-light); margin-bottom: 0;">You will receive a receipt via email for your records.</p>
                    </div>
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
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Processing...';
    submitBtn.disabled = true;
    
    const formData = new FormData(this);
    const amount = parseFloat(formData.get('amount')) * 100; // Convert to cents
    
    if (amount < 500) {
        showAlert('error', 'Minimum donation amount is $5');
        submitBtn.textContent = originalText;
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
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

