<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();
$page_title = 'Cats';

$cats = db()->fetchAll("SELECT id, name, breed, status, created_at FROM cats ORDER BY created_at DESC LIMIT 100");

require_once __DIR__ . '/../includes/admin-header.php';
?>
<div class="dashboard-section">
  <h2>🐱 Cats</h2>
  <p><a class="btn" href="/admin/cats/create.php">+ Add Cat</a></p>

  <?php if (empty($cats)): ?>
    <p class="text-muted">No cats yet.</p>
  <?php else: ?>
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr><th>ID</th><th>Name</th><th>Breed</th><th>Status</th><th>Created</th><th style="width:160px;">Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach ($cats as $c): ?>
            <tr>
              <td><?php echo (int)$c['id']; ?></td>
              <td><?php echo htmlspecialchars($c['name'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($c['breed'] ?? ''); ?></td>
              <td>
                <span class="badge badge-<?php echo ($c['status'] ?? '') === 'adoptable' ? 'success' : 'warning'; ?>">
                  <?php echo htmlspecialchars(ucfirst($c['status'] ?? '')); ?>
                </span>
              </td>
              <td><?php echo formatDateTime($c['created_at'] ?? ''); ?></td>
              <td>
                <a class="btn btn-small" href="/admin/cats/edit.php?id=<?php echo (int)$c['id']; ?>">Edit</a>
                <form method="post" action="/admin/cats/delete.php" style="display:inline" onsubmit="return confirm('Delete this cat?');">
                  <input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>">
                  <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
                  <button class="btn btn-small btn-danger" type="submit">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
