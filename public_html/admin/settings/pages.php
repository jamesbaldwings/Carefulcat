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
  <div class="admin-card-header"><h2 class="admin-card-title">📄 Page Visibility</h2></div>
  <p class="text-muted" style="margin-bottom:1rem;">Read-only toggles. Add DB persistence later.</p>

  <div class="table-responsive">
    <table class="admin-table">
      <thead><tr><th>Page</th><th>Visible</th></tr></thead>
      <tbody>
        <?php foreach ($visibility as $page => $isVisible): ?>
          <tr>
            <td><?php echo htmlspecialchars(ucfirst($page)); ?></td>
            <td>
              <label class="switch">
                <input type="checkbox" disabled <?php echo $isVisible ? 'checked' : ''; ?>>
                <span class="slider"></span>
              </label>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
