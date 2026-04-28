<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
$page_title = 'Site Information';
// Try to read optional settings table (if present)
$stored = [];
try {
    $stored = db()->fetchAll("SELECT `key`, `value` FROM settings");
} catch (Throwable $e) {}
require_once __DIR__ . '/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header">
    <h2 class="admin-card-title">🌐 Site Information</h2>
    <a href="/admin/settings/index.php" class="btn btn-outline btn-sm">Back to Settings</a>
  </div>

  <div style="padding: 1.75rem;">
    <div class="form-section">
      <div class="form-section-title">Application Constants</div>
      <div class="admin-table-container">
        <table class="admin-table">
          <thead>
            <tr><th style="width:240px;">Setting</th><th>Value</th></tr>
          </thead>
          <tbody>
            <tr><td><strong>Site Name</strong></td><td><?php echo htmlspecialchars(SITE_NAME ?? ''); ?></td></tr>
            <tr><td><strong>Base URL</strong></td><td><?php echo htmlspecialchars(BASE_URL ?? ''); ?></td></tr>
            <tr><td><strong>Assets URL</strong></td><td><?php echo htmlspecialchars(ASSETS_URL ?? ''); ?></td></tr>
          </tbody>
        </table>
      </div>
      <small class="form-hint" style="margin-top: 0.75rem;">These values are defined in config.php and cannot be changed from the admin panel.</small>
    </div>

    <?php if (!empty($stored)): ?>
    <div class="form-section">
      <div class="form-section-title">Database Settings</div>
      <div class="admin-table-container">
        <table class="admin-table">
          <thead><tr><th>Key</th><th>Value</th></tr></thead>
          <tbody>
            <?php foreach ($stored as $row): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['key'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['value'] ?? ''); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php else: ?>
    <div class="form-section">
      <div class="form-section-title">Database Settings</div>
      <div class="admin-empty-state">
        <div class="admin-empty-icon">📋</div>
        <h3>No Stored Settings</h3>
        <p>No database-backed settings found. Create a <code>settings</code> table with <code>key, value</code> columns to persist changes.</p>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
