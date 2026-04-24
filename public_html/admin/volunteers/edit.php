<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$id=(int)($_GET['id']??0);
$v=db()->fetchOne("SELECT * FROM volunteers WHERE id=?",[$id]);
if(!$v){ redirect('/admin/volunteers/index.php'); }
$page_title='Edit Volunteer';
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!csrf_verify($_POST['csrf'] ?? '')){ $errors[]='Invalid CSRF token.'; }
  $fn=trim($_POST['first_name']??''); $ln=trim($_POST['last_name']??''); $email=trim($_POST['email']??'');
  $phone=trim($_POST['phone']??''); $status=$_POST['status']??'pending';
  if($fn===''||$ln===''||$email===''){ $errors[]='First, last name and email are required.'; }
  if(!$errors){
    db()->query("UPDATE volunteers SET first_name=?,last_name=?,email=?,phone=?,status=? WHERE id=?",[$fn,$ln,$email,$phone,$status,$id]);
    flash('success','Volunteer updated.'); redirect('/admin/volunteers/index.php');
  }
}
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header"><h2 class="admin-card-title">🤝 Edit Volunteer</h2></div>
  <?php if($errors): ?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div><?php endif;?>
  <form method="post">
    <input type="hidden" name="csrf" value="<?php echo csrf_token();?>">
    <div class="form-group"><label>First Name</label><input name="first_name" value="<?php echo htmlspecialchars($v['first_name']);?>" required></div>
    <div class="form-group"><label>Last Name</label><input name="last_name" value="<?php echo htmlspecialchars($v['last_name']);?>" required></div>
    <div class="form-group"><label>Email</label><input type="email" name="email" value="<?php echo htmlspecialchars($v['email']);?>" required></div>
    <div class="form-group"><label>Phone</label><input name="phone" value="<?php echo htmlspecialchars($v['phone']);?>"></div>
    <div class="form-group"><label>Status</label>
      <select name="status">
        <?php foreach(['pending','approved','denied'] as $s):?>
          <option value="<?php echo $s; ?>" <?php echo $v['status']===$s?'selected':''; ?>><?php echo ucfirst($s);?></option>
        <?php endforeach;?>
      </select>
    </div>
    <button class="btn" type="submit">Save</button>
    <a class="btn btn-outline" href="/admin/volunteers/index.php">Cancel</a>
  </form>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
