<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

// Get JSON input
$input = getJsonInput();

// Verify CSRF token
if (!isset($input['csrf_token']) || !verifyCsrfToken($input['csrf_token'])) {
    jsonResponse(['success' => false, 'message' => 'Invalid CSRF token'], 403);
}

// Validate required fields
$required = ['amount', 'type', 'first_name', 'last_name', 'email'];
$errors = validateRequired($input, $required);

if (!empty($errors)) {
    jsonResponse(['success' => false, 'message' => implode(', ', $errors)], 400);
}

// Validate email
if (!isValidEmail($input['email'])) {
    jsonResponse(['success' => false, 'message' => 'Invalid email address'], 400);
}

// Validate amount
$amount = intval($input['amount']);
if ($amount < 500) {
    jsonResponse(['success' => false, 'message' => 'Minimum donation amount is $5'], 400);
}

// Validate type
if (!in_array($input['type'], ['one-time', 'monthly'])) {
    jsonResponse(['success' => false, 'message' => 'Invalid donation type'], 400);
}

// Sanitize inputs
$firstName = sanitize($input['first_name']);
$lastName = sanitize($input['last_name']);
$email = sanitize($input['email']);
$type = sanitize($input['type']);
$sponsoredCatId = isset($input['sponsored_cat_id']) && !empty($input['sponsored_cat_id']) ? sanitize($input['sponsored_cat_id']) : null;

try {
    // Check if Stripe is configured
    if (empty(STRIPE_SECRET_KEY)) {
        jsonResponse(['success' => false, 'message' => 'Payment processing is not configured'], 500);
    }
    
    // Initialize Stripe
    require_once __DIR__ . '/../vendor/autoload.php';
    \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
    
    // Create donation record
    $donationId = generateUuid();
    $inserted = db()->query(
        "INSERT INTO donations (id, first_name, last_name, email, amount, type, sponsored_cat_id, status, created_at) 
         VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())",
        [$donationId, $firstName, $lastName, $email, $amount, $type, $sponsoredCatId]
    );
    
    if (!$inserted) {
        throw new Exception('Failed to create donation record');
    }
    
    // Create Stripe PaymentIntent
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'usd',
        'description' => $sponsoredCatId ? "Sponsorship donation for cat" : "General donation",
        'metadata' => [
            'donation_id' => $donationId,
            'type' => $type,
            'sponsored_cat_id' => $sponsoredCatId ?: 'none'
        ],
        'receipt_email' => $email
    ]);
    
    // Update donation with payment intent ID
    db()->query(
        "UPDATE donations SET stripe_payment_intent_id = ? WHERE id = ?",
        [$paymentIntent->id, $donationId]
    );
    
    jsonResponse([
        'success' => true,
        'clientSecret' => $paymentIntent->client_secret,
        'donationId' => $donationId
    ]);
    
} catch (\Stripe\Exception\ApiErrorException $e) {
    error_log("Stripe API Error: " . $e->getMessage());
    jsonResponse(['success' => false, 'message' => 'Payment processing error: ' . $e->getMessage()], 500);
} catch (Exception $e) {
    error_log("Donation Error: " . $e->getMessage());
    jsonResponse(['success' => false, 'message' => 'An error occurred. Please try again.'], 500);
}

