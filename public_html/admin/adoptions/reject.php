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
        'status' => 'denied',
        'denied_at' => date('Y-m-d H:i:s'),
        'approved_at' => null
    ], 'id = ?', [$id]);
    
    flash('success', 'Adoption application rejected');
    redirect('/admin/adoptions/index.php');
    
} catch (Exception $e) {
    flash('error', 'Failed to reject adoption: ' . $e->getMessage());
    redirect('/admin/adoptions/index.php');
}
