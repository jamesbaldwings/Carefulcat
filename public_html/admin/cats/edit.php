<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$id=(int)($_GET['id'] ?? 0);
$cat = db()->fetchOne("SELECT * FROM cats WHERE id=?",[$id]);
if(!$cat){ redirect('/admin/cats/index.php'); exit; }
$page_title='Edit Cat #'.$id;
$errors=[];

if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!csrf_verify($_POST['csrf'] ?? '')){ $errors[]='Invalid CSRF token.'; }
  $name=trim($_POST['name'] ?? '');
  $breed=trim($_POST['breed'] ?? '');
  $status=$_POST['status'] ?? 'intake';
  if($name===''){ $errors[]='Name is required.'; }
  if(!$errors){
    db()->query("UPDATE cats SET name=?, breed=?, status=? WHERE id=?",[$name,$breed,$status,$id]);
    flash('success','Cat updated.');
    redirect('/admin/cats/index.php'); exit;
  }
}
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header"><h2 class="admin-card-title">🐱 Edit Cat</h2></div>
  <?php if($m=flash_out('success')): ?><div class="alert alert-success"><?php echo htmlspecialchars($m); ?></div><?php endif; ?>
  <?php if($errors): ?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div><?php endif; ?>

  <form method="post">
    <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
    <div class="form-group"><label>Name</label><input name="name" value="<?php echo htmlspecialchars($cat['name']??''); ?>" required></div>
    <div class="form-group"><label>Breed</label><input name="breed" value="<?php echo htmlspecialchars($cat['breed']??''); ?>"></div>
    <div class="form-group">
      <label>Status</label>
      <select name="status">
        <?php foreach (['intake','adoptable','adopted','hold'] as $st): ?>
          <option value="<?php echo $st; ?>" <?php echo ($cat['status']===$st?'selected':''); ?>><?php echo ucfirst($st); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="btn" type="submit">Save</button>
    <a class="btn btn-outline" href="/admin/cats/index.php">Cancel</a>
  </form>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
