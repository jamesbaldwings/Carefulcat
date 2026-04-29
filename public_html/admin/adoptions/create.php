<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$page_title='New Adoption Application';
$errors=[];
$cats=db()->fetchAll("SELECT id,name,shelter_tag FROM cats ORDER BY name ASC");

if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!csrf_verify($_POST['csrf']??'')){ $errors[]='Invalid CSRF token.'; }
  $cat_id=$_POST['cat_id'] ?? '';
  $adopter_name=trim($_POST['adopter_name']??'');
  $adopter_email=trim($_POST['adopter_email']??'');
  $adopter_phone=trim($_POST['adopter_phone']??'');
  $adoption_fee=($_POST['adoption_fee'] ?? '')!=='' ? (float)$_POST['adoption_fee'] : null;
  if(empty($cat_id)||$adopter_name===''||$adopter_email===''){ $errors[]='Cat, adopter name and email are required.'; }
  if(!$errors){
    db()->query("INSERT INTO adoptions(cat_id,adopter_name,adopter_email,adopter_phone,adoption_fee,applied_at,created_at) VALUES(?,?,?,?,?,NOW(),NOW())",
      [$cat_id,$adopter_name,$adopter_email,$adopter_phone,$adoption_fee]);
    ensure_shelter_tag($cat_id);
    flash('success','Application created.'); redirect('/admin/adoptions/index.php');
  }
}
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header">
      <h1 class="admin-card-title">📝 New Adoption Application</h1>
  </div>
  <div class="admin-card-body">
    <?php if($errors):?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ',$errors));?></div><?php endif;?>
    <form method="post">
        <input type="hidden" name="csrf" value="<?php echo csrf_token();?>">

        <div class="form-section">
            <h2 class="form-section-title">Adoption Details</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="cat_id">Cat <span class="required">*</span></label>
                    <select name="cat_id" id="cat_id" required>
                        <option value="">-- Select a cat --</option>
                        <?php foreach($cats as $c):?>
                          <option value="<?php echo htmlspecialchars($c['id'] ?? '');?>"><?php echo htmlspecialchars((($c['shelter_tag'] ?? '') ? ($c['shelter_tag'] . ' - ') : '') . ($c['name'] ?? ''));?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="adoption_fee">Adoption Fee</label>
                    <input type="number" step="0.01" id="adoption_fee" name="adoption_fee" placeholder="e.g., 150.00">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2 class="form-section-title">Adopter Information</h2>
            <div class="form-group">
                <label for="adopter_name">Full Name <span class="required">*</span></label>
                <input type="text" id="adopter_name" name="adopter_name" required placeholder="e.g., John Doe">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="adopter_email">Email Address <span class="required">*</span></label>
                    <input type="email" id="adopter_email" name="adopter_email" required placeholder="e.g., john.doe@example.com">
                </div>
                <div class="form-group">
                    <label for="adopter_phone">Phone Number</label>
                    <input type="tel" id="adopter_phone" name="adopter_phone" placeholder="e.g., (555) 123-4567">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create Application</button>
            <a href="/admin/adoptions/index.php" class="btn btn-outline">Cancel</a>
        </div>
    </form>
  </div>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
