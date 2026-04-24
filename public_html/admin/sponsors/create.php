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
  $display_order=(int)($_POST['display_order'] ?? 0);
  
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
  <div class="admin-card-header"><h2 class="admin-card-title">🏆 Add Sponsor</h2></div>
  <?php if($errors):?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ',$errors));?></div><?php endif;?>
  
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?php echo csrf_token();?>">
    <input type="hidden" name="logo_url" id="logo_url" value="">
    
    <div class="form-group">
      <label>Name <span style="color:red;">*</span></label>
      <input name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
    </div>
    
    <div class="form-group">
      <label>Logo</label>
      <input type="file" id="logo_input" accept="image/*" onchange="uploadLogo(this)">
      <div id="logo_preview" style="margin-top: 10px;"></div>
      <small style="color: #666;">Upload sponsor logo (JPG, PNG, GIF, WEBP - Max 5MB)</small>
    </div>
    
    <div class="form-group">
      <label>Website URL</label>
      <input name="website_url" type="url" value="<?php echo htmlspecialchars($_POST['website_url'] ?? ''); ?>" placeholder="https://example.com">
    </div>
    
    <div class="form-group">
      <label>Description</label>
      <textarea name="description" rows="3" placeholder="Brief description of the sponsor..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
    </div>
    
    <div class="form-group">
      <label>Display Order</label>
      <input name="display_order" type="number" min="0" value="<?php echo htmlspecialchars($_POST['display_order'] ?? '0'); ?>">
      <small style="color: #666;">Lower numbers appear first</small>
    </div>
    
    <div class="form-group">
      <label>
        <input type="checkbox" name="is_active" value="1" <?php echo isset($_POST['is_active']) || !isset($_POST['name']) ? 'checked' : ''; ?>>
        Active (show on website)
      </label>
    </div>
    
    <button class="btn" type="submit">💾 Save Sponsor</button>
    <a class="btn btn-outline" href="/admin/sponsors/index.php">Cancel</a>
  </form>
</div>

<script>
function uploadLogo(input) {
  if (input.files && input.files[0]) {
    const file = input.files[0];
    const formData = new FormData();
    formData.append('file', file);
    
    // Show loading
    document.getElementById('logo_preview').innerHTML = '<p>Uploading...</p>';
    
    fetch('/admin/upload.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        document.getElementById('logo_url').value = data.url;
        document.getElementById('logo_preview').innerHTML = 
          '<img src="' + data.url + '" style="max-width: 200px; max-height: 100px; border-radius: 8px;"><br>' +
          '<small style="color: green;">✓ Logo uploaded successfully</small>';
      } else {
        document.getElementById('logo_preview').innerHTML = 
          '<p style="color: red;">Error: ' + data.error + '</p>';
      }
    })
    .catch(error => {
      document.getElementById('logo_preview').innerHTML = 
        '<p style="color: red;">Upload failed: ' + error + '</p>';
    });
  }
}
</script>

<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
