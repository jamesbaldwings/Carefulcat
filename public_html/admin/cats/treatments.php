<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$cat_id=(int)($_GET['cat_id']??0);
$cat=db()->fetchOne("SELECT id,name,shelter_tag FROM cats WHERE id=?",[$cat_id]);
if(!$cat){ redirect('/admin/cats/index.php'); }
$page_title='Add Treatment for '.$cat['name'];
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!csrf_verify($_POST['csrf']??'')){ $errors[]='Invalid CSRF token.'; }
  $type=$_POST['treatment_type']??'vaccine1';
  $date=$_POST['date_administered']??'';
  $notes=trim($_POST['notes']??'');
  $by=trim($_POST['administered_by']??'');
  if($date===''){ $errors[]='Date is required.'; }
  if(!$errors){
    db()->query("INSERT INTO cat_treatments(cat_id,treatment_type,date_administered,notes,administered_by,created_at) VALUES(?,?,?,?,?,NOW())",
      [$cat_id,$type,$date,$notes,$by]);
    flash('success','Treatment added.');
    redirect('/admin/cats/medical.php?cat_id='.$cat_id);
  }
}
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header"><h2 class="admin-card-title">➕ Add Treatment — <?php echo htmlspecialchars($cat['name']);?></h2></div>
  <?php if($errors):?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ',$errors));?></div><?php endif;?>
  <form method="post">
    <input type="hidden" name="csrf" value="<?php echo csrf_token();?>">
    <div class="form-group"><label>Treatment Type</label>
      <select name="treatment_type">
        <?php foreach(['spay_neuter','vaccine1','vaccine2','vaccine3','vaccine4','rabies','other'] as $t):?>
          <option value="<?php echo $t;?>"><?php echo ucwords(str_replace('_',' ',$t));?></option>
        <?php endforeach;?>
      </select>
    </div>
    <div class="form-group"><label>Date Administered</label><input type="date" name="date_administered" required></div>
    <div class="form-group"><label>Administered By</label><input name="administered_by" placeholder="Dr. Smith, Vet Tech ..."></div>
    <div class="form-group"><label>Notes</label><textarea name="notes" rows="4"></textarea></div>
    <button class="btn" type="submit">Save</button>
    <a class="btn btn-outline" href="/admin/cats/medical.php?cat_id=<?php echo (int)$cat_id;?>">Cancel</a>
  </form>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
