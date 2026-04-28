<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$id=(int)($_GET['id']??0);
$a=db()->fetchOne("SELECT * FROM adoptions WHERE id=?",[$id]);
if(!$a){ redirect('/admin/adoptions/index.php'); }
$page_title='Edit Adoption';
$cats=db()->fetchAll("SELECT id,name,shelter_tag FROM cats ORDER BY name ASC");
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!csrf_verify($_POST['csrf']??'')){ $errors[]='Invalid CSRF token.'; }
  $cat_id=(int)($_POST['cat_id']??0);
  $adopter_name=trim($_POST['adopter_name']??'');
  $adopter_email=trim($_POST['adopter_email']??'');
  $adopter_phone=trim($_POST['adopter_phone']??'');
  $adoption_fee=($_POST['adoption_fee'] ?? '')!=='' ? (float)$_POST['adoption_fee'] : null;
  $status=$_POST['status']??$a['status'];
  $approved_at = $status==='approved' ? ( ($a['approved_at'] ?? null) ?: date('Y-m-d H:i:s') ) : null;
  $denied_at   = $status==='denied' ? ( ($a['denied_at'] ?? null) ?: date('Y-m-d H:i:s') ) : null;
  if($cat_id<=0||$adopter_name===''||$adopter_email===''){ $errors[]='Cat, adopter name and email are required.'; }
  if(!$errors){
    db()->query("UPDATE adoptions SET cat_id=?, adopter_name=?, adopter_email=?, adopter_phone=?, adoption_fee=?, status=?, approved_at=?, denied_at=? WHERE id=?.",
      [$cat_id,$adopter_name,$adopter_email,$adopter_phone,$adoption_fee,$status,$approved_at,$denied_at,$id]);
    ensure_shelter_tag($cat_id);
    flash('success','Adoption updated.'); redirect('/admin/adoptions/index.php');
  }
}
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header">
    <h1 class="admin-card-title">📝 Edit Adoption</h1>
  </div>
  <div class="admin-card-body">
    <?php if($errors):?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ',$errors));?></div><?php endif;?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?php echo csrf_token();?>">

      <div class="form-section">
        <h2 class="form-section-title">Basic Information</h2>
        <div class="form-group">
          <label for="cat_id">Cat <span class="required">*</span></label>
          <select name="cat_id" id="cat_id" required>
            <?php foreach($cats as $c):?>
              <option value="<?php echo (int)($c['id'] ?? 0);?>" <?php echo $a['cat_id']==$c['id']?'selected':''; ?>>
                <?php echo htmlspecialchars((($c['shelter_tag'] ?? '') ? ($c['shelter_tag'] . ' — ') : '') . ($c['name'] ?? ''));?>
              </option>
            <?php endforeach;?>
          </select>
        </div>
      </div>

      <div class="form-section">
        <h2 class="form-section-title">Adopter Details</h2>
        <div class="form-row">
          <div class="form-group">
            <label for="adopter_name">Adopter Name <span class="required">*</span></label>
            <input type="text" id="adopter_name" name="adopter_name" value="<?php echo htmlspecialchars($a['adopter_name'] ?? '');?>" placeholder="e.g., Jane Doe" required>
          </div>
          <div class="form-group">
            <label for="adopter_email">Adopter Email <span class="required">*</span></label>
            <input type="email" id="adopter_email" name="adopter_email" value="<?php echo htmlspecialchars($a['adopter_email'] ?? '');?>" placeholder="e.g., jane.doe@example.com" required>
          </div>
        </div>
        <div class="form-group">
          <label for="adopter_phone">Adopter Phone</label>
          <input type="tel" id="adopter_phone" name="adopter_phone" value="<?php echo htmlspecialchars($a['adopter_phone'] ?? '');?>" placeholder="e.g., (123) 456-7890">
        </div>
      </div>

      <div class="form-section">
        <h2 class="form-section-title">Status &amp; Fee</h2>
        <div class="form-row">
          <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status">
              <?php foreach(['pending','approved','denied'] as $s):?>
                <option value="<?php echo $s;?>" <?php echo $a['status']===$s?'selected':''; ?>><?php echo ucfirst($s);?></option>
              <?php endforeach;?>
            </select>
          </div>
          <div class="form-group">
            <label for="adoption_fee">Adoption Fee</label>
            <input type="number" id="adoption_fee" step="0.01" name="adoption_fee" value="<?php echo htmlspecialchars($a['adoption_fee']??'');?>" placeholder="e.g., 150.00">
          </div>
        </div>
      </div>

      <div class="form-actions">
        <button class="btn btn-primary" type="submit">Save Changes</button>
        <a class="btn btn-outline" href="/admin/adoptions/index.php">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
