<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$page_title = 'Settings';
require_once __DIR__ . '/../includes/admin-header.php';
?>

<link rel="stylesheet" href="/admin/css/admin-forms.css">

<?php if ($m = flash_out('success')): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($m ?? ''); ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">⚙️ Settings</h2>
        <p style="margin: 8px 0 0 0; color: #666; font-size: 14px;">
            Manage your site configuration and preferences
        </p>
    </div>
</div>

<div class="settings-grid">
    <!-- Site Information -->
    <div class="settings-card">
        <div style="font-size: 32px; margin-bottom: 12px;">🌐</div>
        <h3>Site Information</h3>
        <p>Update site name, contact info, and social media links</p>
        <a href="/admin/settings/site-info.php" class="btn">
            Manage Site Info
        </a>
    </div>
    
    <!-- Page Visibility -->
    <div class="settings-card">
        <div style="font-size: 32px; margin-bottom: 12px;">👁️</div>
        <h3>Page Visibility</h3>
        <p>Control which pages are visible on the website</p>
        <a href="/admin/settings/page-visibility.php" class="btn">
            Manage Visibility
        </a>
    </div>
    
    <!-- User Management -->
    <div class="settings-card">
        <div style="font-size: 32px; margin-bottom: 12px;">👥</div>
        <h3>User Management</h3>
        <p>Manage admin and staff user accounts</p>
        <a href="/admin/settings/users.php" class="btn">
            Manage Users
        </a>
    </div>
    
    <!-- Email Settings -->
    <div class="settings-card">
        <div style="font-size: 32px; margin-bottom: 12px;">✉️</div>
        <h3>Email Settings</h3>
        <p>Configure email notifications and templates</p>
        <a href="/admin/settings/email.php" class="btn btn-outline">
            Coming Soon
        </a>
    </div>
    
    <!-- Donation Settings -->
    <div class="settings-card">
        <div style="font-size: 32px; margin-bottom: 12px;">💰</div>
        <h3>Donation Settings</h3>
        <p>Configure payment gateways and donation options</p>
        <a href="/admin/settings/donations.php" class="btn btn-outline">
            Coming Soon
        </a>
    </div>
    
    <!-- Backup & Export -->
    <div class="settings-card">
        <div style="font-size: 32px; margin-bottom: 12px;">💾</div>
        <h3>Backup & Export</h3>
        <p>Export data and create database backups</p>
        <a href="/admin/settings/backup.php" class="btn btn-outline">
            Coming Soon
        </a>
    </div>
</div>

<style>
.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
    margin-top: 24px;
}

.settings-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 24px;
    transition: all 0.2s;
}

.settings-card:hover {
    border-color: #3b82f6;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    transform: translateY(-2px);
}

.settings-card h3 {
    margin: 0 0 8px 0;
    font-size: 18px;
    color: #1f2937;
    font-weight: 600;
}

.settings-card p {
    margin: 0 0 16px 0;
    color: #6b7280;
    font-size: 14px;
    line-height: 1.5;
}

.settings-card .btn {
    width: 100%;
    justify-content: center;
}
</style>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
