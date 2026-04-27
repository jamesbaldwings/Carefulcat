<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

// Get filter parameters
$status = $_GET['status'] ?? 'all';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

// Build query
$query = "SELECT * FROM donations WHERE 1=1";
$params = [];

if ($status !== 'all') {
    $query .= " AND status = ?";
    $params[] = $status;
}

if (!empty($start_date)) {
    $query .= " AND DATE(created_at) >= ?";
    $params[] = $start_date;
}

if (!empty($end_date)) {
    $query .= " AND DATE(created_at) <= ?";
    $params[] = $end_date;
}

$query .= " ORDER BY created_at DESC";

try {
    $donations = db()->fetchAll($query, $params);
    
    // Set headers for CSV download
    $filename = 'donations_export_' . date('Y-m-d_His') . '.csv';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Open output stream
    $output = fopen('php://output', 'w');
    
    // Add BOM for Excel UTF-8 support
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Write CSV header
    fputcsv($output, [
        'ID',
        'First Name',
        'Last Name',
        'Email',
        'Phone',
        'Address',
        'Amount',
        'Status',
        'Type',
        'Sponsored Cat ID',
        'Payment Method',
        'Transaction ID',
        'Stripe Payment Intent',
        'Donation Type',
        'Is Recurring',
        'Recurring Frequency',
        'Is Anonymous',
        'Message',
        'Dedication',
        'Created At',
        'Updated At'
    ]);
    
    // Write data rows
    foreach ($donations as $donation) {
        fputcsv($output, [
            $donation['id'] ?? '',
            $donation['first_name'] ?? '',
            $donation['last_name'] ?? '',
            $donation['email'] ?? '',
            $donation['donor_phone'] ?? '',
            $donation['donor_address'] ?? '',
            number_format(($donation['amount'] ?? 0) / 100, 2),
            $donation['status'] ?? '',
            $donation['type'] ?? '',
            $donation['sponsored_cat_id'] ?? '',
            $donation['payment_method'] ?? '',
            $donation['transaction_id'] ?? '',
            $donation['stripe_payment_intent_id'] ?? '',
            $donation['donation_type'] ?? '',
            isset($donation['is_recurring']) && ($donation['is_recurring'] ?? null) ? 'Yes' : 'No',
            $donation['recurring_frequency'] ?? '',
            isset($donation['is_anonymous']) && ($donation['is_anonymous'] ?? null) ? 'Yes' : 'No',
            $donation['message'] ?? '',
            $donation['dedication'] ?? '',
            $donation['created_at'] ?? '',
            $donation['updated_at'] ?? ''
        ]);
    }
    
    fclose($output);
    exit;
    
} catch (Exception $e) {
    flash('error', 'Failed to export donations: ' . $e->getMessage());
    redirect('/admin/donations/index.php');
    exit;
}
