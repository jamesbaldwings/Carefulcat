<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/volunteers/index.php');
    exit;
}

if (!csrf_verify($_POST['csrf'] ?? '')) {
    flash('error', 'Invalid CSRF token');
    redirect('/admin/volunteers/index.php');
    exit;
}

$id = $_POST['id'] ?? '';

if (empty($id)) {
    flash('error', 'Invalid volunteer ID');
    redirect('/admin/volunteers/index.php');
    exit;
}

try {
    // Check if volunteer exists
    $volunteer = db()->fetchOne("SELECT * FROM volunteers WHERE id = ?", [$id]);
    
    if (!$volunteer) {
        flash('error', 'Volunteer not found');
        redirect('/admin/volunteers/index.php');
        exit;
    }
    
    // Generate volunteer ID if not exists
    $volunteer_id = $volunteer['volunteer_id'] ?? '';
    if (empty($volunteer_id)) {
        // Format: VOL-YYYY-XXXX (e.g., VOL-2025-0001)
        $year = date('Y');
        $last = db()->fetchOne("
            SELECT volunteer_id 
            FROM volunteers 
            WHERE volunteer_id LIKE ? 
            ORDER BY volunteer_id DESC 
            LIMIT 1
        ", ["VOL-{$year}-%"]);
        
        if ($last && preg_match('/VOL-\d{4}-(\d{4})/', $last['volunteer_id'], $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }
        
        $volunteer_id = sprintf('VOL-%s-%04d', $year, $number);
    }
    
    // Update volunteer status
    db()->update('volunteers', [
        'status' => 'approved',
        'volunteer_id' => $volunteer_id,
        'updated_at' => date('Y-m-d H:i:s')
    ], 'id = ?', [$id]);
    
    flash('success', 'Volunteer approved successfully! Volunteer ID: ' . $volunteer_id);
    redirect('/admin/volunteers/index.php');
    
} catch (Exception $e) {
    flash('error', 'Failed to approve volunteer: ' . $e->getMessage());
    redirect('/admin/volunteers/index.php');
}
