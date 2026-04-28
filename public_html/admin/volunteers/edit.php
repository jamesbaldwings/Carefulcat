<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$id=$_GET['id'] ?? '';
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
    db()->query("UPDATE volunteers SET first_name=?,last_name=?,email=?,phone=?,status=? WHERE id=? ",[$fn,$ln,$email,$phone,$status,$id]);
    flash('success','Volunteer updated.'); redirect('/admin/volunteers/index.php');
  }
}
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header">
    <h1 class="admin-card-title">🤝 Edit Volunteer</h1>
  </div>
  <div class="admin-card-body">
    <?php if($errors): ?>
      <div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div>
    <?php endif; ?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?php echo csrf_token();?>">

      <div class="form-section">
        <h2 class="form-section-title">Basic Information</h2>
        <div class="form-row">
          <div class="form-group">
            <label for="first_name">First Name <span class="required">*</span></label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($v['first_name'] ?? '');?>" placeholder="Enter first name..." required>
          </div>
          <div class="form-group">
            <label for="last_name">Last Name <span class="required">*</span></label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($v['last_name'] ?? '');?>" placeholder="Enter last name..." required>
          </div>
        </div>
      </div>

      <div class="form-section">
        <h2 class="form-section-title">Contact Details</h2>
        <div class="form-row">
          <div class="form-group">
            <label for="email">Email <span class="required">*</span></label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($v['email'] ?? '');?>" placeholder="john@example.com" required>
          </div>
          <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($v['phone'] ?? '');?>" placeholder="Enter phone number...">
          </div>
        </div>
      </div>

      <div class="form-section">
        <h2 class="form-section-title">Status & Options</h2>
        <div class="form-group">
          <label for="status">Status</label>
          <select id="status" name="status">
            <?php foreach(['pending','approved','denied'] as $s):?>
              <option value="<?php echo $s; ?>" <?php echo ($v['status'] ?? '') === $s ? 'selected' : ''; ?>><?php echo ucfirst($s);?></option>
            <?php endforeach;?>
          </select>
        </div>
      </div>

      <div class="form-actions">
        <button class="btn btn-primary" type="submit">Save Changes</button>
        <a class="btn btn-outline" href="/admin/volunteers/index.php">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
