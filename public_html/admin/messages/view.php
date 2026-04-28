<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$page_title='View Message';
$id=$_GET['id'] ?? '';
$msg=db()->fetchOne("SELECT * FROM contacts WHERE id=?",[$id]);
if(!$msg){ redirect('/admin/messages/index.php'); }
// contacts table has no status column - skip status update
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header"><h2 class="admin-card-title">✉️ Message</h2></div>
  <table class="admin-table">
    <tbody>
      <tr><th>From</th><td><?php echo htmlspecialchars(trim(($msg['first_name'] ?? '') . ' ' . ($msg['last_name'] ?? '')).' <'.($msg['email'] ?? '').'>' ?? '');?></td></tr>
      <tr><th>Subject</th><td><?php echo htmlspecialchars($msg['subject'] ?? '');?></td></tr>
      <tr><th>Date</th><td><?php echo formatDateTime($msg['created_at'] ?? '');?></td></tr>
      <tr><th>Message</th><td><pre style="white-space:pre-wrap;"><?php echo htmlspecialchars($msg['message'] ?? '');?></pre></td></tr>
    </tbody>
  </table>
  <p><a class="btn btn-outline" href="/admin/messages/index.php">Back</a></p>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
