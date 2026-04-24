<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$page_title='Add Cat';
$errors=[];

if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!csrf_verify($_POST['csrf'] ?? '')){ $errors[]='Invalid CSRF token.'; }
  
  $name=trim($_POST['name'] ?? '');
  $species=trim($_POST['species'] ?? '');
  $status=$_POST['status'] ?? 'intake';
  $sex=$_POST['sex'] ?? '';
  $age=trim($_POST['age'] ?? '');
  $fee=(int)($_POST['fee'] ?? 0);
  $location=trim($_POST['location'] ?? 'Murfreesboro, TN');
  $bio=trim($_POST['bio'] ?? '');
  $shelter_tag=trim($_POST['shelter_tag'] ?? '');
  $hero_photo=trim($_POST['hero_photo'] ?? '');
  
  if($name===''){ $errors[]='Name is required.'; }
  if($sex===''){ $errors[]='Sex is required.'; }
  
  if(!$errors){
    db()->query("INSERT INTO cats (name, species, status, sex, age, fee, location, bio, shelter_tag, hero_photo, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,NOW())",
      [$name, $species, $status, $sex, $age, $fee, $location, $bio, $shelter_tag, $hero_photo]);
    flash('success','Cat added successfully!');
    redirect('/admin/cats/index.php'); exit;
  }
}
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header"><h2 class="admin-card-title">🐱 Add Cat</h2></div>
  <?php if($m=flash_out('success')): ?><div class="alert alert-success"><?php echo htmlspecialchars($m); ?></div><?php endif; ?>
  <?php if($errors): ?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div><?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="hero_photo" id="hero_photo_url" value="">
    
    <div class="form-group">
      <label>Name <span style="color:red;">*</span></label>
      <input name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
    </div>
    
    <div class="form-group">
      <label>Species</label>
      <input name="species" value="<?php echo htmlspecialchars($_POST['species'] ?? ''); ?>" placeholder="e.g., Domestic Shorthair, Siamese">
    </div>
    
    <div class="form-group">
      <label>Sex <span style="color:red;">*</span></label>
      <select name="sex" required>
        <option value="">Select...</option>
        <option value="M" <?php echo ($_POST['sex'] ?? '') === 'M' ? 'selected' : ''; ?>>Male</option>
        <option value="F" <?php echo ($_POST['sex'] ?? '') === 'F' ? 'selected' : ''; ?>>Female</option>
      </select>
    </div>
    
    <div class="form-group">
      <label>Age</label>
      <input name="age" value="<?php echo htmlspecialchars($_POST['age'] ?? ''); ?>" placeholder="e.g., 2 years, 6 months">
    </div>
    
    <div class="form-group">
      <label>Adoption Fee ($)</label>
      <input name="fee" type="number" min="0" value="<?php echo htmlspecialchars($_POST['fee'] ?? '75'); ?>">
    </div>
    
    <div class="form-group">
      <label>Location</label>
      <input name="location" value="<?php echo htmlspecialchars($_POST['location'] ?? 'Murfreesboro, TN'); ?>">
    </div>
    
    <div class="form-group">
      <label>Shelter Tag</label>
      <input name="shelter_tag" value="<?php echo htmlspecialchars($_POST['shelter_tag'] ?? ''); ?>" placeholder="e.g., CAT-2025-001">
    </div>
    
    <div class="form-group">
      <label>Status</label>
      <select name="status">
        <option value="intake" <?php echo ($_POST['status'] ?? 'intake') === 'intake' ? 'selected' : ''; ?>>Intake</option>
        <option value="adoptable" <?php echo ($_POST['status'] ?? '') === 'adoptable' ? 'selected' : ''; ?>>Adoptable</option>
        <option value="adopted" <?php echo ($_POST['status'] ?? '') === 'adopted' ? 'selected' : ''; ?>>Adopted</option>
        <option value="hold" <?php echo ($_POST['status'] ?? '') === 'hold' ? 'selected' : ''; ?>>Hold</option>
        <option value="sanctuary" <?php echo ($_POST['status'] ?? '') === 'sanctuary' ? 'selected' : ''; ?>>Sanctuary</option>
      </select>
    </div>
    
    <div class="form-group">
      <label>Bio</label>
      <textarea name="bio" rows="4" placeholder="Tell us about this cat..."><?php echo htmlspecialchars($_POST['bio'] ?? ''); ?></textarea>
    </div>
    
    <div class="form-group">
      <label>Hero Photo</label>
      <input type="file" id="hero_photo_input" accept="image/*" onchange="uploadImage(this)">
      <div id="image_preview" style="margin-top: 10px;"></div>
      <small style="color: #666;">Upload a photo (JPG, PNG, GIF, WEBP - Max 5MB)</small>
    </div>
    
    <button class="btn" type="submit">💾 Save Cat</button>
    <a class="btn btn-outline" href="/admin/cats/index.php">Cancel</a>
  </form>
</div>

<script>
function uploadImage(input) {
  if (input.files && input.files[0]) {
    const file = input.files[0];
    const formData = new FormData();
    formData.append('file', file);
    
    // Show loading
    document.getElementById('image_preview').innerHTML = '<p>Uploading...</p>';
    
    fetch('/admin/upload.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        document.getElementById('hero_photo_url').value = data.url;
        document.getElementById('image_preview').innerHTML = 
          '<img src="' + data.url + '" style="max-width: 200px; border-radius: 8px;"><br>' +
          '<small style="color: green;">✓ Image uploaded successfully</small>';
      } else {
        document.getElementById('image_preview').innerHTML = 
          '<p style="color: red;">Error: ' + data.error + '</p>';
      }
    })
    .catch(error => {
      document.getElementById('image_preview').innerHTML = 
        '<p style="color: red;">Upload failed: ' + error + '</p>';
    });
  }
}
</script>

<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
