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
  $fee = $_POST['fee'] ?? '';
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
  <div class="admin-card-header">
    <h1 class="admin-card-title">🐱 Add New Cat</h1>
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
            <input id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" placeholder="Enter cat name...">
          </div>
          <div class="form-group">
            <label for="species">Species</label>
            <input id="species" name="species" value="<?php echo htmlspecialchars($_POST['species'] ?? 'Domestic Shorthair'); ?>" placeholder="e.g., Domestic Shorthair, Siamese">
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
            <input id="age" name="age" value="<?php echo htmlspecialchars($_POST['age'] ?? ''); ?>" placeholder="e.g., 2 years, 6 months">
          </div>
        </div>
      </div>

      <div class="form-section">
        <h2 class="form-section-title">Status &amp; Location</h2>
        <div class="form-row">
          <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
              <option value="intake" <?php echo ($_POST['status'] ?? 'intake') === 'intake' ? 'selected' : ''; ?>>Intake</option>
              <option value="adoptable" <?php echo ($_POST['status'] ?? '') === 'adoptable' ? 'selected' : ''; ?>>Adoptable</option>
              <option value="adopted" <?php echo ($_POST['status'] ?? '') === 'adopted' ? 'selected' : ''; ?>>Adopted</option>
              <option value="hold" <?php echo ($_POST['status'] ?? '') === 'hold' ? 'selected' : ''; ?>>Hold</option>
              <option value="sanctuary" <?php echo ($_POST['status'] ?? '') === 'sanctuary' ? 'selected' : ''; ?>>Sanctuary</option>
            </select>
          </div>
          <div class="form-group">
            <label for="location">Location</label>
            <input id="location" name="location" value="<?php echo htmlspecialchars($_POST['location'] ?? 'Murfreesboro, TN'); ?>">
          </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="fee">Adoption Fee ($)</label>
                <input id="fee" name="fee" type="number" min="0" value="<?php echo htmlspecialchars($_POST['fee'] ?? '75'); ?>">
            </div>
            <div class="form-group">
                <label for="shelter_tag">Shelter Tag</label>
                <input id="shelter_tag" name="shelter_tag" value="<?php echo htmlspecialchars($_POST['shelter_tag'] ?? ''); ?>" placeholder="e.g., CAT-2025-001">
            </div>
        </div>
      </div>

      <div class="form-section">
        <h2 class="form-section-title">Details &amp; Media</h2>
        <div class="form-group">
          <label for="bio">Bio</label>
          <textarea id="bio" name="bio" rows="5" placeholder="Tell us about this cat..."><?php echo htmlspecialchars($_POST['bio'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
          <label>Hero Photo</label>
          <div class="file-upload-area">
            <span class="file-upload-icon">📷</span>
            <p class="file-upload-text">Click or drag a photo to upload</p>
            <input type="file" id="hero_photo_input" accept="image/*" onchange="uploadImage(this)" class="file-upload-input">
            <div class="file-upload-hint">JPG, PNG, GIF, WEBP - Max 5MB</div>
          </div>
          <div id="image_preview" class="file-upload-preview"></div>
        </div>
      </div>

      <div class="form-actions">
        <button class="btn btn-primary" type="submit">💾 Save Cat</button>
        <a class="btn btn-outline" href="/admin/cats/index.php">Cancel</a>
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
    
    const previewContainer = document.getElementById('image_preview');
    previewContainer.innerHTML = '<p>Uploading...</p>';
    
    fetch('/admin/upload.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        document.getElementById('hero_photo_url').value = data.url;
        previewContainer.innerHTML = 
          '<img src="' + data.url + '" alt="Image preview" style="max-width: 200px; border-radius: 8px;"><br>' +
          '<small style="color: green;">✓ Image uploaded successfully</small>';
      } else {
        previewContainer.innerHTML = 
          '<p style="color: red;">Error: ' + data.error + '</p>';
      }
    })
    .catch(error => {
      previewContainer.innerHTML = 
        '<p style="color: red;">Upload failed: ' + error + '</p>';
    });
  }
}
</script>

<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
