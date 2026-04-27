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
  <div class="admin-card-header"><h2 class="admin-card-title">🌐 Site Information</h2></div>

  <div class="table-responsive">
    <table class="admin-table">
      <tbody>
        <tr><th style="width:240px;">Site Name (constant)</th><td><?php echo htmlspecialchars(SITE_NAME ?? ''); ?></td></tr>
        <tr><th>Base URL (constant)</th><td><?php echo htmlspecialchars(BASE_URL ?? ''); ?></td></tr>
        <tr><th>Assets URL (constant)</th><td><?php echo htmlspecialchars(ASSETS_URL ?? ''); ?></td></tr>
      </tbody>
    </table>
  </div>

  <?php if (!empty($stored)): ?>
    <h3 style="margin-top:1.25rem;">Stored Settings</h3>
    <div class="table-responsive">
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
  <?php else: ?>
    <p class="text-muted" style="margin-top:1rem;">No database-backed settings found. Create a <code>settings</code> table with <code>key,value</code> to persist changes.</p>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
