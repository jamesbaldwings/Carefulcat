<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$id=(int)($_GET['id']??0);
$s=db()->fetchOne("SELECT * FROM sponsors WHERE id=?",[$id]);
if(!$s){ redirect('/admin/sponsors/index.php'); }
$page_title='Edit Sponsor';
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!csrf_verify($_POST['csrf'] ?? '')){ $errors[]='Invalid CSRF token.'; }
  $name=trim($_POST['name']??''); $level=trim($_POST['level']??''); $website=trim($_POST['website']??''); $status=$_POST['status']??'active';
  if($name===''){ $errors[]='Name is required.'; }
  if(!$errors){
    db()->query("UPDATE sponsors SET name=?,level=?,website=?,status=? WHERE id=?",[$name,$level,$website,$status,$id]);
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
            <label for="level">Level</label>
            <input type="text" id="level" name="level" value="<?php echo htmlspecialchars($s['level'] ?? '');?>" placeholder="e.g., Gold">
          </div>
        </div>
        <div class="form-group">
          <label for="website">Website</label>
          <input type="url" id="website" name="website" value="<?php echo htmlspecialchars($s['website'] ?? '');?>" placeholder="https://example.com">
        </div>
      </div>

      <div class="form-section">
        <h2 class="form-section-title">Status</h2>
        <div class="form-group">
          <label for="status">Status</label>
          <select id="status" name="status">
            <?php foreach(['active','inactive'] as $st):?>
              <option value="<?php echo $st;?>" <?php echo ($s['status'] ?? 'active') === $st ? 'selected' : '';?>><?php echo ucfirst($st);?></option>
            <?php endforeach;?>
          </select>
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
