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
$required = ['first_name', 'last_name', 'email', 'subject', 'message'];
$errors = validateRequired($input, $required);

if (!empty($errors)) {
    jsonResponse(['success' => false, 'message' => implode(', ', $errors)], 400);
}

// Validate email
if (!isValidEmail($input['email'])) {
    jsonResponse(['success' => false, 'message' => 'Invalid email address'], 400);
}

// Sanitize inputs
$firstName = sanitize($input['first_name']);
$lastName = sanitize($input['last_name']);
$email = sanitize($input['email']);
$phone = isset($input['phone']) ? sanitize($input['phone']) : null;
$subject = sanitize($input['subject']);
$message = sanitize($input['message']);

try {
    // Insert contact record
    $contactId = generateUuid();
    $inserted = db()->query(
        "INSERT INTO contacts (id, first_name, last_name, email, phone, subject, message, created_at) 
         VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
        [$contactId, $firstName, $lastName, $email, $phone, $subject, $message]
    );
    
    if (!$inserted) {
        throw new Exception('Failed to save contact form');
    }
    
    // TODO: Send email notification to admin
    // You can implement email sending here using PHPMailer or similar
    
    jsonResponse([
        'success' => true,
        'message' => 'Thank you for your message! We will get back to you soon.',
        'id' => $contactId
    ]);
    
} catch (Exception $e) {
    error_log("Contact Form Error: " . $e->getMessage());
    jsonResponse(['success' => false, 'message' => 'An error occurred. Please try again.'], 500);
}

