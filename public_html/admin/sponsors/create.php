<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$page_title='Add Sponsor';
$errors=[];

if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!csrf_verify($_POST['csrf'] ?? '')){ $errors[]='Invalid CSRF token.'; }
  
  $name=trim($_POST['name']??'');
  $logo_url=trim($_POST['logo_url']??'');
  $website_url=trim($_POST['website_url']??'');
  $description=trim($_POST['description']??'');
  $is_active=isset($_POST['is_active']) ? 1 : 0;
  $display_order = $_POST['display_order'] ?? '';
  
  if($name===''){ $errors[]='Name is required.'; }
  
  if(!$errors){
    db()->query("INSERT INTO sponsors(name, logo_url, website_url, description, is_active, display_order, created_at) VALUES(?,?,?,?,?,?,NOW())",
      [$name, $logo_url, $website_url, $description, $is_active, $display_order]);
    flash('success','Sponsor added successfully!');
    redirect('/admin/sponsors/index.php');
  }
}
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header">
    <h1 class="admin-card-title">🏆 Add New Sponsor</h1>
  </div>
  <div class="admin-card-body">
    <?php if($errors):?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ',$errors));?></div><?php endif;?>
    
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?php echo csrf_token();?>">
      <input type="hidden" name="logo_url" id="logo_url" value="<?php echo htmlspecialchars($_POST['logo_url'] ?? ''); ?>">

      <div class="form-section">
        <h2 class="form-section-title">Sponsor Details</h2>
        <div class="form-group">
          <label for="name">Sponsor Name <span class="required">*</span></label>
          <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" placeholder="Enter sponsor name...">
        </div>
        <div class="form-group">
          <label for="website_url">Website URL</label>
          <input type="url" id="website_url" name="website_url" value="<?php echo htmlspecialchars($_POST['website_url'] ?? ''); ?>" placeholder="https://example.com">
        </div>
        <div class="form-group">
          <label for="description">Description</label>
          <textarea id="description" name="description" rows="3" placeholder="Brief description of the sponsor..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
        </div>
      </div>

      <div class="form-section">
        <h2 class="form-section-title">Media</h2>
        <div class="form-group">
          <label>Logo</label>
          <div class="file-upload-area">
            <span class="file-upload-icon">📷</span>
            <input type="file" id="logo_input" accept="image/*" onchange="uploadLogo(this)" class="file-upload-input" style="opacity: 0; position: absolute; top: 0; left: 0; width: 100%; height: 100%; cursor: pointer;">
            <p class="file-upload-text">Click or drag to upload logo</p>
            <small class="file-upload-hint">PNG, JPG, GIF, WEBP up to 5MB</small>
          </div>
          <div id="logo_preview" class="file-upload-preview" style="margin-top: 10px;">
            <?php if (!empty($_POST['logo_url'])): ?>
              <img src="<?php echo htmlspecialchars($_POST['logo_url']); ?>" style="max-width: 200px; max-height: 100px; border-radius: 8px;">
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="form-section">
        <h2 class="form-section-title">Settings</h2>
        <div class="form-row">
          <div class="form-group">
            <label for="display_order">Display Order</label>
            <input type="number" id="display_order" name="display_order" min="0" value="<?php echo htmlspecialchars($_POST['display_order'] ?? '0'); ?>">
            <small class="form-hint">Lower numbers appear first.</small>
          </div>
          <div class="form-group">
            <label class="toggle-switch">
              <input type="checkbox" name="is_active" value="1" <?php echo isset($_POST['is_active']) || !isset($_POST['name']) ? 'checked' : ''; ?>>
              <span class="toggle-slider"></span>
              <span class="toggle-label">Active</span>
            </label>
          </div>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">💾 Save Sponsor</button>
        <a href="/admin/sponsors/index.php" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
function uploadLogo(input) {
  if (input.files && input.files[0]) {
    const file = input.files[0];
    const formData = new FormData();
    formData.append('file', file);
    
    const previewContainer = document.getElementById('logo_preview');
    previewContainer.innerHTML = '<p>Uploading...</p>';
    
    fetch('/admin/upload.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        document.getElementById('logo_url').value = data.url;
        previewContainer.innerHTML = 
          '<img src="' + data.url + '" style="max-width: 200px; max-height: 100px; border-radius: 8px;"><br>' +
          '<small style="color: green;">✓ Logo uploaded successfully</small>';
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
