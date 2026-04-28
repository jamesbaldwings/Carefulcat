<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$page_title='Sponsors';
$rows=db()->fetchAll("SELECT id,name,logo_url,website_url,is_active,display_order,created_at FROM sponsors ORDER BY created_at DESC LIMIT 200");
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="dashboard-section">
  <h2>🏆 Sponsors</h2>
  <p><a class="btn" href="/admin/sponsors/create.php">+ Add Sponsor</a></p>
  <?php if($m=flash_out('success')):?><div class="alert alert-success"><?php echo htmlspecialchars($m ?? '');?></div><?php endif;?>
  <div class="table-responsive">
    <table class="admin-table">
      <thead><tr><th>ID</th><th>Name</th><th>Website URL</th><th>Active</th><th>Created</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach($rows as $s):?>
        <tr>
          <td><?php echo htmlspecialchars($s['id'] ?? '');?></td>
          <td><?php echo htmlspecialchars($s['name'] ?? '');?></td>
          <td><?php echo htmlspecialchars($s['website_url'] ?? '');?></td>
          <td><span class="badge badge-<?php echo $s['is_active']?'success':'warning';?>"><?php echo ($s['is_active'] ? 'Active' : 'Inactive');?></span></td>
          <td><?php echo formatDateTime($s['created_at'] ?? '');?></td>
          <td>
            <a class="btn btn-small" href="/admin/sponsors/edit.php?id=<?php echo htmlspecialchars($s['id'] ?? '');?>">Edit</a>
            <form method="post" action="/admin/sponsors/delete.php" style="display:inline" onsubmit="return confirm('Delete this sponsor?');">
              <input type="hidden" name="csrf" value="<?php echo csrf_token();?>">
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($s['id'] ?? '');?>">
              <button class="btn btn-small btn-danger" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach;?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
