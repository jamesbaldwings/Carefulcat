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
  <div class="admin-card-header"><h2 class="admin-card-title">🏆 Edit Sponsor</h2></div>
  <?php if($errors):?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ',$errors));?></div><?php endif;?>
  <form method="post">
    <input type="hidden" name="csrf" value="<?php echo csrf_token();?>">
    <div class="form-group"><label>Name</label><input name="name" value="<?php echo htmlspecialchars($s['name']);?>" required></div>
    <div class="form-group"><label>Level</label><input name="level" value="<?php echo htmlspecialchars($s['level']);?>"></div>
    <div class="form-group"><label>Website</label><input name="website" value="<?php echo htmlspecialchars($s['website']);?>"></div>
    <div class="form-group"><label>Status</label>
      <select name="status"><?php foreach(['active','inactive'] as $st):?><option value="<?php echo $st;?>" <?php echo $s['status']===$st?'selected':'';?>><?php echo ucfirst($st);?></option><?php endforeach;?></select>
    </div>
    <button class="btn" type="submit">Save</button>
    <a class="btn btn-outline" href="/admin/sponsors/index.php">Cancel</a>
  </form>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
