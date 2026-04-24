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
  $adoption_fee=$_POST['adoption_fee']!=='' ? (float)$_POST['adoption_fee'] : null;
  $status=$_POST['status']??$a['status'];
  $approved_at = $status==='approved' ? ( $a['approved_at'] ?: date('Y-m-d H:i:s') ) : null;
  $denied_at   = $status==='denied' ? ( $a['denied_at'] ?: date('Y-m-d H:i:s') ) : null;
  if($cat_id<=0||$adopter_name===''||$adopter_email===''){ $errors[]='Cat, adopter name and email are required.'; }
  if(!$errors){
    db()->query("UPDATE adoptions SET cat_id=?, adopter_name=?, adopter_email=?, adopter_phone=?, adoption_fee=?, status=?, approved_at=?, denied_at=? WHERE id=?",
      [$cat_id,$adopter_name,$adopter_email,$adopter_phone,$adoption_fee,$status,$approved_at,$denied_at,$id]);
    ensure_shelter_tag($cat_id);
    flash('success','Adoption updated.'); redirect('/admin/adoptions/index.php');
  }
}
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header"><h2 class="admin-card-title">📝 Edit Adoption</h2></div>
  <?php if($errors):?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ',$errors));?></div><?php endif;?>
  <form method="post">
    <input type="hidden" name="csrf" value="<?php echo csrf_token();?>">
    <div class="form-group"><label>Cat</label>
      <select name="cat_id" required>
        <?php foreach($cats as $c):?>
          <option value="<?php echo (int)$c['id'];?>" <?php echo $a['cat_id']==$c['id']?'selected':''; ?>>
            <?php echo htmlspecialchars(($c['shelter_tag']?$c['shelter_tag'].' — ':'').$c['name']);?>
          </option>
        <?php endforeach;?>
      </select>
    </div>
    <div class="form-group"><label>Adopter Name</label><input name="adopter_name" value="<?php echo htmlspecialchars($a['adopter_name']);?>" required></div>
    <div class="form-group"><label>Adopter Email</label><input type="email" name="adopter_email" value="<?php echo htmlspecialchars($a['adopter_email']);?>" required></div>
    <div class="form-group"><label>Adopter Phone</label><input name="adopter_phone" value="<?php echo htmlspecialchars($a['adopter_phone']);?>"></div>
    <div class="form-group"><label>Adoption Fee</label><input type="number" step="0.01" name="adoption_fee" value="<?php echo htmlspecialchars($a['adoption_fee']??'');?>"></div>
    <div class="form-group"><label>Status</label>
      <select name="status">
        <?php foreach(['pending','approved','denied'] as $s):?>
          <option value="<?php echo $s;?>" <?php echo $a['status']===$s?'selected':''; ?>><?php echo ucfirst($s);?></option>
        <?php endforeach;?>
      </select>
    </div>
    <button class="btn" type="submit">Save</button>
    <a class="btn btn-outline" href="/admin/adoptions/index.php">Cancel</a>
  </form>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
