<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
$page_title = 'Settings';
require_once __DIR__ . '/../includes/admin-header.php';
?>
<?php if ($m = flash_out('success')): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($m ?? ''); ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">⚙️ Settings</h2>
    </div>
    <div style="padding: 1.75rem 1.75rem 0.25rem;">
        <p style="color: var(--admin-text-light); font-size: 0.9375rem; margin-bottom: 0;">
            Manage your site configuration, notifications, and preferences.
        </p>
    </div>
</div>

<div class="settings-grid">
    <!-- Site Information -->
    <div class="settings-card">
        <div style="font-size: 2rem; margin-bottom: 0.75rem;">🌐</div>
        <h3>Site Information</h3>
        <p>View site name, base URL, and other application constants.</p>
        <a href="/admin/settings/site.php" class="btn btn-primary">Manage Site Info</a>
    </div>

    <!-- Page Visibility -->
    <div class="settings-card">
        <div style="font-size: 2rem; margin-bottom: 0.75rem;">👁️</div>
        <h3>Page Visibility</h3>
        <p>Control which pages are visible on the public website.</p>
        <a href="/admin/settings/pages.php" class="btn btn-primary">Manage Visibility</a>
    </div>

    <!-- User Management -->
    <div class="settings-card">
        <div style="font-size: 2rem; margin-bottom: 0.75rem;">👥</div>
        <h3>User Management</h3>
        <p>View and manage admin and staff user accounts.</p>
        <a href="/admin/settings/users.php" class="btn btn-primary">Manage Users</a>
    </div>

    <!-- Email Settings -->
    <div class="settings-card">
        <div style="font-size: 2rem; margin-bottom: 0.75rem;">✉️</div>
        <h3>Email Settings</h3>
        <p>Configure notification email address, sender details, and event triggers.</p>
        <a href="/admin/settings/email.php" class="btn btn-primary">Manage Email</a>
    </div>

    <!-- Donation Settings -->
    <div class="settings-card">
        <div style="font-size: 2rem; margin-bottom: 0.75rem;">💰</div>
        <h3>Donation Settings</h3>
        <p>Configure PayPal, suggested amounts, and the donation page message.</p>
        <a href="/admin/settings/donations.php" class="btn btn-primary">Manage Donations</a>
    </div>

    <!-- Backup & Export -->
    <div class="settings-card">
        <div style="font-size: 2rem; margin-bottom: 0.75rem;">💾</div>
        <h3>Backup & Export</h3>
        <p>Export cats, donations, adoptions, and volunteer data as CSV files.</p>
        <a href="/admin/settings/backup.php" class="btn btn-primary">Backup & Export</a>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
