<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$id=$_GET['id'] ?? '';
$s=db()->fetchOne("SELECT * FROM sponsors WHERE id=?",[$id]);
if(!$s){ redirect('/admin/sponsors/index.php'); }
$page_title='Edit Sponsor';
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!csrf_verify($_POST['csrf'] ?? '')){ $errors[]='Invalid CSRF token.'; }
  $name=trim($_POST['name']??''); $logo_url=trim($_POST['logo_url']??''); $website_url=trim($_POST['website_url']??''); $description=trim($_POST['description']??''); $is_active=isset($_POST['is_active']) ? 1 : 0; $display_order=(int)($_POST['display_order'] ?? 0);
  if($name===''){ $errors[]='Name is required.'; }
  if(!$errors){
    db()->query("UPDATE sponsors SET name=?,logo_url=?,website_url=?,description=?,is_active=?,display_order=? WHERE id=?",[$name,$logo_url,$website_url,$description,$is_active,$display_order,$id]);
    flash('success','Sponsor updated.'); redirect('/admin/sponsors/index.php');
  }
}
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header">
    <h1 class="admin-card-title">🏆 Edit Sponsor</h1>
  </div>
  <div class="admin-card-body">
    <?php if($errors):?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ',$errors));?></div><?php endif;?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?php echo csrf_token();?>">

      <div class="form-section">
        <h2 class="form-section-title">Sponsor Details</h2>
        <div class="form-row">
          <div class="form-group">
            <label for="name">Name <span class="required">*</span></label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($s['name'] ?? '');?>" placeholder="e.g., Acme Corporation" required>
          </div>
          <div class="form-group">
            <label for="logo_url">Logo URL</label>
            <input type="text" id="logo_url" name="logo_url" value="<?php echo htmlspecialchars($s['logo_url'] ?? '');?>" placeholder="e.g., /uploads/sponsors/logo.png">
          </div>
        </div>
        <div class="form-group">
          <label for="website_url">Website URL</label>
          <input type="url" id="website_url" name="website_url" value="<?php echo htmlspecialchars($s['website_url'] ?? '');?>" placeholder="https://example.com">
        </div>
      </div>

      <div class="form-section">
        <h2 class="form-section-title">Status</h2>
        <div class="form-group">
          <label for="description">Description</label>
          <textarea id="description" name="description" rows="3" placeholder="Brief description..."><?php echo htmlspecialchars($s['description'] ?? '');?></textarea>
        </div>
        <div class="form-group">
          <label for="display_order">Display Order</label>
          <input type="number" id="display_order" name="display_order" min="0" value="<?php echo htmlspecialchars($s['display_order'] ?? '0');?>">
        </div>
        <div class="form-group">
          <label><input type="checkbox" name="is_active" value="1" <?php echo ($s['is_active'] ?? 1) ? 'checked' : '';?>> Active</label>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="/admin/sponsors/index.php" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
