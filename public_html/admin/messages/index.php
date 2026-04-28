<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$page_title='Messages';

// Fixed: Use first_name and last_name instead of name (matches your schema)
$rows=db()->fetchAll("SELECT id,first_name,last_name,email,subject,message,created_at FROM contacts ORDER BY created_at DESC LIMIT 200");

require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="dashboard-section">
  <h2>✉️ Messages</h2>
  <?php if($m=flash_out('success')):?><div class="alert alert-success"><?php echo htmlspecialchars($m ?? '');?></div><?php endif;?>
  
  <?php if (empty($rows)): ?>
    <p class="text-muted">No messages yet.</p>
  <?php else: ?>
    <div class="table-responsive">
      <table class="admin-table">
        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
          <?php foreach($rows as $r):?>
            <tr>
              <td><?php echo htmlspecialchars($r['id'] ?? '');?></td>
              <td><?php echo htmlspecialchars(($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? '')); ?></td>
              <td><?php echo htmlspecialchars($r['email'] ?? '');?></td>
              <td><?php echo htmlspecialchars($r['subject'] ?? '');?></td>
              <td><?php echo formatDateTime($r['created_at'] ?? '');?></td>
              <td style="white-space: nowrap;">
                <a class="btn btn-small" href="/admin/messages/view.php?id=<?php echo htmlspecialchars($r['id'] ?? '');?>">View</a>
                <a class="btn btn-small" href="/admin/messages/reply.php?id=<?php echo htmlspecialchars($r['id'] ?? '');?>">✉️ Reply</a>
                <form method="post" action="/admin/messages/delete.php" style="display:inline" onsubmit="return confirm('Delete this message?');">
                  <input type="hidden" name="csrf" value="<?php echo csrf_token();?>">
                  <input type="hidden" name="id" value="<?php echo htmlspecialchars($r['id'] ?? '');?>">
                  <button class="btn btn-small btn-danger" type="submit">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
