<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$id=$_GET['id'] ?? '';
$cat = db()->fetchOne("SELECT * FROM cats WHERE id=?",[$id]);
if(!$cat){ redirect('/admin/cats/index.php'); exit; }
$page_title='Edit Cat #'.$id;
$errors=[];

if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!csrf_verify($_POST['csrf'] ?? '')){ $errors[]='Invalid CSRF token.'; }
  $name=trim($_POST['name'] ?? '');
  $species=trim($_POST['species'] ?? '');
  $status=$_POST['status'] ?? 'intake';
  $sex=$_POST['sex'] ?? ($cat['sex'] ?? '');
  $age=trim($_POST['age'] ?? '');
  $fee=$_POST['fee'] ?? ($cat['fee'] ?? 0);
  $location=trim($_POST['location'] ?? ($cat['location'] ?? 'Murfreesboro, TN'));
  $bio=trim($_POST['bio'] ?? '');
  // New uploads populate the hidden field; otherwise keep the existing photo.
  $hero_photo=trim($_POST['hero_photo'] ?? '');
  if($hero_photo===''){ $hero_photo = $cat['hero_photo'] ?? ''; }

  if($name===''){ $errors[]='Name is required.'; }
  if(!$errors){
    db()->query(
      "UPDATE cats SET name=?, species=?, status=?, sex=?, age=?, fee=?, location=?, bio=?, hero_photo=? WHERE id=?",
      [$name,$species,$status,$sex,$age,$fee,$location,$bio,$hero_photo,$id]
    );
    flash('success','Cat updated.');
    redirect('/admin/cats/index.php'); exit;
  }
  $cat = array_merge($cat, [
    'name'=>$name,'species'=>$species,'status'=>$status,'sex'=>$sex,
    'age'=>$age,'fee'=>$fee,'location'=>$location,'bio'=>$bio,'hero_photo'=>$hero_photo
  ]);
}
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header">
    <h1 class="admin-card-title">🐱 Edit Cat</h1>
  </div>
  <div class="admin-card-body">
    <?php if($m=flash_out('success')): ?><div class="alert alert-success"><?php echo htmlspecialchars($m ?? ''); ?></div><?php endif; ?>
    <?php if($errors): ?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="hero_photo" id="hero_photo_url" value="<?php echo htmlspecialchars($cat['hero_photo'] ?? ''); ?>">

        <div class="form-section">
            <h2 class="form-section-title">Basic Information</h2>
            <div class="form-row">
              <div class="form-group">
                  <label for="name">Name <span class="required">*</span></label>
                  <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($cat['name'] ?? ''); ?>" placeholder="Enter cat name..." required>
              </div>
              <div class="form-group">
                  <label for="species">Species</label>
                  <input type="text" id="species" name="species" value="<?php echo htmlspecialchars($cat['species'] ?? ''); ?>" placeholder="e.g., Domestic Shorthair">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="sex">Sex</label>
                <select id="sex" name="sex">
                  <option value="">Select...</option>
                  <option value="M" <?php echo ($cat['sex'] ?? '')==='M'?'selected':''; ?>>Male</option>
                  <option value="F" <?php echo ($cat['sex'] ?? '')==='F'?'selected':''; ?>>Female</option>
                </select>
              </div>
              <div class="form-group">
                <label for="age">Age</label>
                <input id="age" name="age" value="<?php echo htmlspecialchars($cat['age'] ?? ''); ?>" placeholder="e.g., 2 years">
              </div>
            </div>
        </div>

        <div class="form-section">
            <h2 class="form-section-title">Status &amp; Location</h2>
            <div class="form-row">
              <div class="form-group">
                  <label for="status">Status</label>
                  <select id="status" name="status">
                      <?php foreach (['intake', 'adoptable', 'adopted', 'hold', 'sanctuary'] as $st): ?>
                          <option value="<?php echo $st; ?>" <?php echo (($cat['status'] ?? '') === $st ? 'selected' : ''); ?>><?php echo ucfirst($st); ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>
              <div class="form-group">
                  <label for="fee">Adoption Fee ($)</label>
                  <input id="fee" name="fee" type="number" min="0" value="<?php echo htmlspecialchars($cat['fee'] ?? '0'); ?>">
              </div>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input id="location" name="location" value="<?php echo htmlspecialchars($cat['location'] ?? 'Murfreesboro, TN'); ?>">
            </div>
        </div>

        <div class="form-section">
            <h2 class="form-section-title">Details &amp; Media</h2>
            <div class="form-group">
              <label for="bio">Bio</label>
              <textarea id="bio" name="bio" rows="5" placeholder="Tell us about this cat..."><?php echo htmlspecialchars($cat['bio'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
              <label>Hero Photo</label>
              <div class="file-upload-area">
                <span class="file-upload-icon">📷</span>
                <p class="file-upload-text">Click or drag a photo to upload</p>
                <input type="file" id="hero_photo_input" accept="image/*" onchange="uploadImage(this)" class="file-upload-input">
                <div class="file-upload-hint">JPG, PNG, GIF, WEBP - Max 5MB</div>
              </div>
              <div id="image_preview" class="file-upload-preview">
                <?php $cur = cat_photo($cat); ?>
                <img src="<?php echo htmlspecialchars($cur); ?>" alt="Current photo" style="max-width:200px;border-radius:8px;">
                <br><small style="color:#666;">Current photo (upload a new one to replace it)</small>
              </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Save Changes</button>
            <a href="/admin/cats/index.php" class="btn btn-outline">Cancel</a>
        </div>
    </form>
  </div>
</div>

<script>
function uploadImage(input) {
  if (input.files && input.files[0]) {
    const formData = new FormData();
    formData.append('file', input.files[0]);
    const preview = document.getElementById('image_preview');
    preview.innerHTML = '<p>Uploading...</p>';
    fetch('/admin/upload.php', { method: 'POST', body: formData })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          document.getElementById('hero_photo_url').value = data.url;
          preview.innerHTML =
            '<img src="' + data.url + '" alt="Image preview" style="max-width:200px;border-radius:8px;"><br>' +
            '<small style="color:green;">✓ New image uploaded — click Save Changes to apply</small>';
        } else {
          preview.innerHTML = '<p style="color:red;">Error: ' + data.error + '</p>';
        }
      })
      .catch(err => { preview.innerHTML = '<p style="color:red;">Upload failed: ' + err + '</p>'; });
  }
}
</script>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
