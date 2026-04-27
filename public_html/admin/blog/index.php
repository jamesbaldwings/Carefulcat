<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$page_title='Blog Posts';
$rows=db()->fetchAll("SELECT id,title,slug,status,created_at,published_at FROM blog_posts ORDER BY created_at DESC LIMIT 200");
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="dashboard-section">
  <h2>📰 Blog Posts</h2>
  <p><a class="btn" href="/admin/blog/create.php">+ New Post</a></p>
  <?php if($m=flash_out('success')):?><div class="alert alert-success"><?php echo htmlspecialchars($m ?? '');?></div><?php endif;?>
  <div class="table-responsive">
    <table class="admin-table">
      <thead><tr><th>ID</th><th>Title</th><th>Slug</th><th>Status</th><th>Created</th><th>Published</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach($rows as $p):?>
          <tr>
            <td><?php echo (int)($p['id'] ?? 0);?></td>
            <td><?php echo htmlspecialchars($p['title'] ?? '');?></td>
            <td><?php echo htmlspecialchars($p['slug'] ?? '');?></td>
            <td><span class="badge badge-<?php echo $p['status']==='published'?'success':'warning';?>"><?php echo ucfirst($p['status'] ?? '');?></span></td>
            <td><?php echo formatDateTime($p['created_at'] ?? '');?></td>
            <td><?php echo ($p['published_at'] ?? null)?formatDateTime($p['published_at'] ?? ''):'—';?></td>
            <td>
              <a class="btn btn-small" href="/admin/blog/edit.php?id=<?php echo (int)($p['id'] ?? 0);?>">Edit</a>
              <form method="post" action="/admin/blog/delete.php" style="display:inline" onsubmit="return confirm('Delete this post?');">
                <input type="hidden" name="csrf" value="<?php echo csrf_token();?>">
                <input type="hidden" name="id" value="<?php echo (int)($p['id'] ?? 0);?>">
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
