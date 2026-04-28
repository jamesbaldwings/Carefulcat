<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/messages/index.php');
    exit;
}

if (!csrf_verify($_POST['csrf'] ?? '')) {
    flash('error', 'Invalid CSRF token');
    redirect('/admin/messages/index.php');
    exit;
}

$id = $_POST['id'] ?? '';

if (empty($id)) {
    flash('error', 'Invalid message ID');
    redirect('/admin/messages/index.php');
    exit;
}

try {
    // Check if message exists
    $message = db()->fetchOne("SELECT * FROM contacts WHERE id = ?", [$id]);
    
    if (!$message) {
        flash('error', 'Message not found');
        redirect('/admin/messages/index.php');
        exit;
    }
    
    // Delete the message
    db()->delete('contacts', 'id = ?', [$id]);
    
    flash('success', 'Message deleted successfully');
    redirect('/admin/messages/index.php');
    
} catch (Exception $e) {
    flash('error', 'Failed to delete message: ' . $e->getMessage());
    redirect('/admin/messages/index.php');
}
