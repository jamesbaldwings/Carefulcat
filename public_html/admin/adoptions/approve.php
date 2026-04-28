<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/adoptions/index.php');
    exit;
}

if (!csrf_verify($_POST['csrf'] ?? '')) {
    flash('error', 'Invalid CSRF token');
    redirect('/admin/adoptions/index.php');
    exit;
}

$id = $_POST['id'] ?? '';

if (empty($id)) {
    flash('error', 'Invalid adoption ID');
    redirect('/admin/adoptions/index.php');
    exit;
}

try {
    // Check if adoption exists
    $adoption = db()->fetchOne("SELECT * FROM adoptions WHERE id = ?", [$id]);
    
    if (!$adoption) {
        flash('error', 'Adoption application not found');
        redirect('/admin/adoptions/index.php');
        exit;
    }
    
    // Update adoption status
    db()->update('adoptions', [
        'status' => 'approved',
        'approved_at' => date('Y-m-d H:i:s'),
        'denied_at' => null
    ], 'id = ?', [$id]);
    
    // Optionally update cat status to adopted
    if (!empty($adoption['cat_id'])) {
        db()->update('cats', [
            'status' => 'adopted'
        ], 'id = ?', [$adoption['cat_id']]);
    }
    
    flash('success', 'Adoption application approved successfully!');
    redirect('/admin/adoptions/index.php');
    
} catch (Exception $e) {
    flash('error', 'Failed to approve adoption: ' . $e->getMessage());
    redirect('/admin/adoptions/index.php');
}
