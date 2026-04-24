<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$id = $_GET['id'] ?? '';
if (!$id) {
    flash('error', 'Invalid donation ID');
    redirect('/admin/donations/index.php');
    exit;
}

$donation = db()->fetchOne("SELECT * FROM donations WHERE id = ?", [$id]);
if (!$donation) {
    flash('error', 'Donation not found');
    redirect('/admin/donations/index.php');
    exit;
}

// Delete the donation
try {
    db()->query("DELETE FROM donations WHERE id = ?", [$id]);
    flash('success', 'Donation deleted successfully');
} catch (Exception $e) {
    flash('error', 'Failed to delete donation: ' . $e->getMessage());
}

redirect('/admin/donations/index.php');
exit;
