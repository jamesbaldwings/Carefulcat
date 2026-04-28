<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
$page_title = 'User Management';

$users = []; $error = '';
try {
    $users = db()->fetchAll("
      SELECT id, first_name, last_name, email, role, is_active, last_login, created_at
      FROM admin_users
      ORDER BY created_at DESC
      LIMIT 200
    ");
} catch (Throwable $e) {
    $error = 'Could not load users. Adjust query if your schema differs.';
    if (defined('DEBUG_MODE') && DEBUG_MODE) { $error .= ' ('.$e->getMessage().')'; }
}

require_once __DIR__ . '/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header">
    <h2 class="admin-card-title">👥 User Management</h2>
  </div>
  <div class="admin-card-body">
    <?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error ?? ''); ?></div><?php endif; ?>

    <?php if (empty($users)): ?>
      <p class="text-muted">No admin users found.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Last Login</th><th>Created</th></tr>
          </thead>
          <tbody>
            <?php foreach ($users as $u): ?>
              <tr>
                <td><?php echo (int)($u['id'] ?? 0); ?></td>
                <td><?php echo htmlspecialchars(trim(($u['first_name'] ?? '').' '.($u['last_name'] ?? ''))); ?></td>
                <td><?php echo htmlspecialchars($u['email'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($u['role'] ?? 'admin'); ?></td>
                <td>
                  <span class="badge badge-<?php echo ((int)($u['is_active'] ?? 0) === 1) ? 'success' : 'warning'; ?>">
                    <?php echo ((int)($u['is_active'] ?? 0) === 1) ? 'Active' : 'Inactive'; ?>
                  </span>
                </td>
                <td><?php echo ($u['last_login'] ?? null) ? formatDateTime($u['last_login'] ?? '') : '—'; ?></td>
                <td><?php echo ($u['created_at'] ?? null) ? formatDateTime($u['created_at'] ?? '') : '—'; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
