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
    
    // Update volunteer status to denied
    db()->update('volunteers', [
        'status' => 'denied',
        'updated_at' => date('Y-m-d H:i:s')
    ], 'id = ?', [$id]);
    
    flash('success', 'Volunteer application rejected');
    redirect('/admin/volunteers/index.php');
    
} catch (Exception $e) {
    flash('error', 'Failed to reject volunteer: ' . $e->getMessage());
    redirect('/admin/volunteers/index.php');
}
