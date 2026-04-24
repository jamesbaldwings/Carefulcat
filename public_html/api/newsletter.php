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

// Validate required fields
if (!isset($input['email']) || empty($input['email'])) {
    jsonResponse(['success' => false, 'message' => 'Email is required'], 400);
}

// Validate email
$email = sanitize($input['email']);
if (!isValidEmail($email)) {
    jsonResponse(['success' => false, 'message' => 'Invalid email address'], 400);
}

// Optional fields
$firstName = isset($input['first_name']) ? sanitize($input['first_name']) : null;
$lastName = isset($input['last_name']) ? sanitize($input['last_name']) : null;
$interests = isset($input['interests']) ? sanitize($input['interests']) : null;

try {
    // Check if email already exists
    $existing = db()->fetchOne(
        "SELECT id, status FROM newsletter_subscriptions WHERE email = ?",
        [$email]
    );
    
    if ($existing) {
        if ($existing['status'] === 'active') {
            jsonResponse(['success' => false, 'message' => 'This email is already subscribed'], 400);
        } else {
            // Reactivate subscription
            db()->query(
                "UPDATE newsletter_subscriptions SET status = 'active', subscribe_date = NOW(), unsubscribe_date = NULL WHERE email = ?",
                [$email]
            );
            
            jsonResponse([
                'success' => true,
                'message' => 'Welcome back! Your subscription has been reactivated.'
            ]);
        }
    }
    
    // Insert new subscription
    $subscriptionId = generateUuid();
    $inserted = db()->query(
        "INSERT INTO newsletter_subscriptions (id, email, first_name, last_name, interests, status, subscribe_date) 
         VALUES (?, ?, ?, ?, ?, 'active', NOW())",
        [$subscriptionId, $email, $firstName, $lastName, $interests]
    );
    
    if (!$inserted) {
        throw new Exception('Failed to save subscription');
    }
    
    jsonResponse([
        'success' => true,
        'message' => 'Thank you for subscribing to our newsletter!',
        'id' => $subscriptionId
    ]);
    
} catch (Exception $e) {
    error_log("Newsletter Subscription Error: " . $e->getMessage());
    jsonResponse(['success' => false, 'message' => 'An error occurred. Please try again.'], 500);
}

