<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
$page_title = 'Page Visibility';
$visibility = [
  'home'=>true,'about'=>true,'adopt'=>true,'donate'=>true,'blog'=>true,'sponsors'=>true,'contact'=>true,
];
try {
    $rows = db()->fetchAll("SELECT page, visible FROM page_visibility");
    if ($rows) foreach ($rows as $r) { $visibility[$r['page']] = (bool)$r['visible']; }
} catch (Throwable $e) {}
require_once __DIR__ . '/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header">
    <h2 class="admin-card-title">📄 Page Visibility</h2>
    <a href="/admin/settings/index.php" class="btn btn-outline btn-sm">Back to Settings</a>
  </div>

  <div style="padding: 1.75rem;">
    <div class="form-section">
      <div class="form-section-title">Public Page Toggles</div>
      <p class="form-hint" style="margin-bottom: 1.25rem;">Control which pages are visible on the public website. These toggles are currently read-only.</p>
      <div class="admin-table-container">
        <table class="admin-table">
          <thead><tr><th>Page</th><th>Status</th><th>Visibility</th></tr></thead>
          <tbody>
            <?php foreach ($visibility as $page => $isVisible): ?>
              <tr>
                <td><strong><?php echo htmlspecialchars(ucfirst($page)); ?></strong></td>
                <td>
                  <span class="badge <?php echo $isVisible ? 'badge-success' : 'badge-warning'; ?>">
                    <?php echo $isVisible ? 'Visible' : 'Hidden'; ?>
                  </span>
                </td>
                <td>
                  <label class="toggle-switch">
                    <input type="checkbox" disabled <?php echo $isVisible ? 'checked' : ''; ?>>
                    <span class="toggle-slider"></span>
                  </label>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
