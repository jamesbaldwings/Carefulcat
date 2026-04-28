<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$page_title='Add Sanctuary Resident';
$errors=[];

if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!csrf_verify($_POST['csrf'] ?? '')){ $errors[]='Invalid CSRF token.'; }
  
  $name=trim($_POST['name'] ?? '');
  $species=trim($_POST['species'] ?? '');
  $sex=$_POST['sex'] ?? '';
  $age=trim($_POST['age'] ?? '');
  $location=trim($_POST['location'] ?? 'Murfreesboro, TN');
  $bio=trim($_POST['bio'] ?? '');
  $shelter_tag=trim($_POST['shelter_tag'] ?? '');
  $hero_photo=trim($_POST['hero_photo'] ?? '');
  
  if($name===''){ $errors[]='Name is required.'; }
  if($sex===''){ $errors[]='Sex is required.'; }
  
  if(!$errors){
    // Always set status to 'sanctuary' for residents
    db()->query("INSERT INTO cats (name, species, status, sex, age, fee, location, bio, shelter_tag, hero_photo, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,NOW())",
      [$name, $species, 'sanctuary', $sex, $age, 0, $location, $bio, $shelter_tag, $hero_photo]);
    flash('success','Sanctuary resident added successfully!');
    redirect('/admin/residents/index.php'); exit;
  }
}
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header">
    <h1 class="admin-card-title">🏠 Add Sanctuary Resident</h1>
  </div>
  <div class="admin-card-body">
    <?php if($m=flash_out('success')): ?><div class="alert alert-success"><?php echo htmlspecialchars($m ?? ''); ?></div><?php endif; ?>
    <?php if($errors): ?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
      <input type="hidden" name="hero_photo" id="hero_photo_url" value="">

      <div class="form-section">
        <h2 class="form-section-title">Basic Information</h2>
        <div class="form-row">
          <div class="form-group">
            <label for="name">Name <span class="required">*</span></label>
            <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" placeholder="Enter cat name...">
          </div>
          <div class="form-group">
            <label for="species">Species</label>
            <input type="text" id="species" name="species" value="<?php echo htmlspecialchars($_POST['species'] ?? ''); ?>" placeholder="e.g., Domestic Shorthair">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="sex">Sex <span class="required">*</span></label>
            <select id="sex" name="sex" required>
              <option value="">Select...</option>
              <option value="M" <?php echo ($_POST['sex'] ?? '') === 'M' ? 'selected' : ''; ?>>Male</option>
              <option value="F" <?php echo ($_POST['sex'] ?? '') === 'F' ? 'selected' : ''; ?>>Female</option>
            </select>
          </div>
          <div class="form-group">
            <label for="age">Age</label>
            <input type="text" id="age" name="age" value="<?php echo htmlspecialchars($_POST['age'] ?? ''); ?>" placeholder="e.g., 2 years, 6 months">
          </div>
        </div>
      </div>

      <div class="form-section">
        <h2 class="form-section-title">Details</h2>
        <div class="form-group">
          <label for="location">Location</label>
          <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($_POST['location'] ?? 'Murfreesboro, TN'); ?>">
        </div>
        <div class="form-group">
          <label for="shelter_tag">Shelter Tag</label>
          <input type="text" id="shelter_tag" name="shelter_tag" value="<?php echo htmlspecialchars($_POST['shelter_tag'] ?? ''); ?>" placeholder="e.g., CAT-2025-001">
        </div>
        <div class="form-group">
          <label for="bio">Bio</label>
          <textarea id="bio" name="bio" rows="4" placeholder="Tell us about this resident..."><?php echo htmlspecialchars($_POST['bio'] ?? ''); ?></textarea>
          <small class="form-hint">Explain why this cat is a permanent sanctuary resident.</small>
        </div>
      </div>

      <div class="form-section">
        <h2 class="form-section-title">Media</h2>
        <div class="form-group">
          <label>Photo</label>
          <div class="file-upload-area">
            <label for="hero_photo_input" class="file-upload-label">
              <span class="file-upload-icon">📷</span>
              <span class="file-upload-text">Click to upload or drag and drop</span>
              <span class="file-upload-hint">JPG, PNG, GIF, WEBP (Max 5MB)</span>
            </label>
            <input type="file" id="hero_photo_input" accept="image/*" onchange="uploadImage(this)" class="file-upload-input">
          </div>
          <div id="image_preview" class="file-upload-preview"></div>
        </div>
      </div>

      <div class="form-actions">
        <button class="btn btn-primary" type="submit">💾 Save Resident</button>
        <a class="btn btn-outline" href="/admin/residents/index.php">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
function uploadImage(input) {
  if (input.files && input.files[0]) {
    const file = input.files[0];
    const formData = new FormData();
    formData.append('file', file);
    
    const preview = document.getElementById('image_preview');
    preview.innerHTML = '<p>Uploading...</p>';
    
    fetch('/admin/upload.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        document.getElementById('hero_photo_url').value = data.url;
        preview.innerHTML = 
          '<img src="' + data.url + '" alt="Image preview" style="max-width: 200px; border-radius: 8px;"><br>' +
          '<small style="color: green;">✓ Image uploaded successfully</small>';
      } else {
        preview.innerHTML = 
          '<p style="color: red;">Error: ' + data.error + '</p>';
      }
    })
    .catch(error => {
      preview.innerHTML = 
        '<p style="color: red;">Upload failed: ' + error + '</p>';
    });
  }
}
</script>

<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
