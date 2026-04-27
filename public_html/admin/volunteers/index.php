<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$page_title='Volunteers';

$rows = db()->fetchAll("SELECT id,first_name,last_name,email,phone,status,volunteer_id,created_at FROM volunteers ORDER BY created_at DESC LIMIT 200");
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="dashboard-section">
  <h2>🤝 Volunteers</h2>
  <p><a class="btn" href="/admin/volunteers/create.php">+ Add Volunteer</a></p>
  <?php if ($m=flash_out('success')): ?><div class="alert alert-success"><?php echo htmlspecialchars($m ?? ''); ?></div><?php endif; ?>
  <div class="table-responsive">
    <table class="admin-table">
      <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Volunteer ID</th><th>Applied</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="8" style="text-align: center; padding: 20px; color: #666;">No volunteers yet.</td></tr>
        <?php else: ?>
          <?php foreach($rows as $v): ?>
            <tr>
              <td><?php echo (int)($v['id'] ?? 0); ?></td>
              <td><?php echo htmlspecialchars($v['first_name'].' '.$v['last_name'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($v['email'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($v['phone'] ?? ''); ?></td>
              <td>
                <span class="badge badge-<?php echo $v['status']==='approved'?'success':($v['status']==='denied'?'danger':'warning'); ?>">
                  <?php echo ucfirst($v['status'] ?? ''); ?>
                </span>
              </td>
              <td><?php echo htmlspecialchars($v['volunteer_id'] ?? '-'); ?></td>
              <td><?php echo formatDateTime($v['created_at'] ?? ''); ?></td>
              <td style="white-space: nowrap;">
                <a class="btn btn-small" href="/admin/volunteers/view.php?id=<?php echo (int)($v['id'] ?? 0); ?>">View</a>
                <?php if ($v['status'] === 'pending'): ?>
                  <form method="post" action="/admin/volunteers/approve.php" style="display:inline">
                    <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="id" value="<?php echo (int)($v['id'] ?? 0); ?>">
                    <button class="btn btn-small" type="submit">✅ Approve</button>
                  </form>
                  <form method="post" action="/admin/volunteers/reject.php" style="display:inline">
                    <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="id" value="<?php echo (int)($v['id'] ?? 0); ?>">
                    <button class="btn btn-small btn-outline" type="submit">❌ Reject</button>
                  </form>
                <?php endif; ?>
                <a class="btn btn-small btn-outline" href="/admin/volunteers/edit.php?id=<?php echo (int)($v['id'] ?? 0); ?>">Edit</a>
                <form method="post" action="/admin/volunteers/delete.php" style="display:inline" onsubmit="return confirm('Delete this volunteer?');">
                  <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
                  <input type="hidden" name="id" value="<?php echo (int)($v['id'] ?? 0); ?>">
                  <button class="btn btn-small btn-danger" type="submit">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach;?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
