<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

try {
  $donations = db()->fetchAll("
    SELECT id, first_name, last_name, email, amount, status, created_at
    FROM donations
    ORDER BY created_at DESC
    LIMIT 100
  ");
} catch (Throwable $e) {
  $donations = [];
}

$page_title = 'Donations';
require_once __DIR__ . '/../includes/admin-header.php';
?>

<div class="admin-header-actions" style="margin-bottom: 20px;">
  <a class="btn" href="/admin/donations/export.php">📊 Export to CSV</a>
</div>

<?php if ($m=flash_out('success')): ?>
  <div class="alert alert-success"><?php echo htmlspecialchars($m ?? ''); ?></div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h2 class="admin-card-title">💰 Donations</h2>
  </div>
  
  <?php if (empty($donations)): ?>
    <div class="admin-empty-state">
      <div class="admin-empty-icon">💰</div>
      <h3>No Donations</h3>
      <p>There are currently no donations in the system.</p>
    </div>
  <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Donor</th>
          <th>Email</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($donations as $d): ?>
          <tr>
            <td><?php echo (int)($d['id'] ?? 0); ?></td>
            <td><strong><?php echo htmlspecialchars(trim(($d['first_name'] ?? '') . ' ' . ($d['last_name'] ?? ''))); ?></strong></td>
            <td><?php echo htmlspecialchars($d['email'] ?? ''); ?></td>
            <td><strong><?php echo formatCurrency($d['amount'] ?? 0); ?></strong></td>
            <td>
              <span class="badge badge-<?php echo ($d['status'] ?? '') === 'completed' ? 'success' : 'warning'; ?>">
                <?php echo htmlspecialchars(ucfirst($d['status'] ?? '')); ?>
              </span>
            </td>
            <td><?php echo formatDateTime($d['created_at'] ?? ''); ?></td>
            <td class="admin-table-actions">
              <a class="btn btn-sm" href="/admin/donations/view.php?id=<?php echo (int)($d['id'] ?? 0); ?>">View</a>
              <a class="btn btn-sm btn-outline" href="/admin/donations/edit.php?id=<?php echo (int)($d['id'] ?? 0); ?>">Edit</a>
              <a class="btn btn-sm btn-danger" 
                 href="/admin/donations/delete.php?id=<?php echo (int)($d['id'] ?? 0); ?>"
                 onclick="return confirm('Are you sure you want to delete this donation?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
