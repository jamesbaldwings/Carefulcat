<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$id = $_GET['id'] ?? '';
if (!$id) {
    flash('error', 'Invalid resident ID');
    redirect('/admin/residents/index.php');
    exit;
}

$cat = db()->fetchOne("SELECT * FROM cats WHERE id = ? AND status = 'sanctuary'", [$id]);
if (!$cat) {
    flash('error', 'Resident not found');
    redirect('/admin/residents/index.php');
    exit;
}

// Delete the resident
try {
    db()->query("DELETE FROM cats WHERE id = ?", [$id]);
    flash('success', 'Resident removed successfully');
} catch (Exception $e) {
    flash('error', 'Failed to remove resident: ' . $e->getMessage());
}

redirect('/admin/residents/index.php');
exit;
