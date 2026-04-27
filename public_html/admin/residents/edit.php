<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();

$id = $_GET['id'] ?? '';
if (!$id) {
    flash('error', 'Invalid resident ID');
    redirect('/admin/residents/index.php');
    exit;
}

$cat = db()->fetchOne("SELECT * FROM cats WHERE id = ? AND status = 'sanctuary'", [$id]);
if (!$cat) {
    flash('error', 'Resident not found');
    redirect('/admin/residents/index.php');
    exit;
}

$page_title = 'Edit Resident: ' . ($cat['name'] ?? '');
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf'] ?? '')) { $errors[] = 'Invalid CSRF token.'; }
    
    $name = trim($_POST['name'] ?? '');
    $species = trim($_POST['species'] ?? '');
    $sex = $_POST['sex'] ?? '';
    $age = trim($_POST['age'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $shelter_tag = trim($_POST['shelter_tag'] ?? '');
    $hero_photo = trim($_POST['hero_photo'] ?? $cat['hero_photo']);
    
    if ($name === '') { $errors[] = 'Name is required.'; }
    if ($sex === '') { $errors[] = 'Sex is required.'; }
    
    if (!$errors) {
        db()->query("UPDATE cats SET name=?, species=?, sex=?, age=?, location=?, bio=?, shelter_tag=?, hero_photo=? WHERE id=?",
            [$name, $species, $sex, $age, $location, $bio, $shelter_tag, $hero_photo, $id]);
        flash('success', 'Resident updated successfully!');
        redirect('/admin/residents/index.php');
        exit;
    }
}

require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
    <div class="admin-card-header"><h2 class="admin-card-title">🏠 Edit Resident</h2></div>
    <?php if ($errors): ?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="hero_photo" id="hero_photo_url" value="<?php echo htmlspecialchars($cat['hero_photo'] ?? ''); ?>">
        
        <div class="form-group">
            <label>Name <span style="color:red;">*</span></label>
            <input name="name" required value="<?php echo htmlspecialchars($cat['name'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label>Species</label>
            <input name="species" value="<?php echo htmlspecialchars($cat['species'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label>Sex <span style="color:red;">*</span></label>
            <select name="sex" required>
                <option value="">Select...</option>
                <option value="M" <?php echo $cat['sex'] === 'M' ? 'selected' : ''; ?>>Male</option>
                <option value="F" <?php echo $cat['sex'] === 'F' ? 'selected' : ''; ?>>Female</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Age</label>
            <input name="age" value="<?php echo htmlspecialchars($cat['age'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label>Location</label>
            <input name="location" value="<?php echo htmlspecialchars($cat['location'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label>Shelter Tag</label>
            <input name="shelter_tag" value="<?php echo htmlspecialchars($cat['shelter_tag'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label>Bio</label>
            <textarea name="bio" rows="4"><?php echo htmlspecialchars($cat['bio'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Photo</label>
            <?php if ($cat['hero_photo'] ?? null): ?>
                <div style="margin-bottom: 10px;">
                    <img src="<?php echo htmlspecialchars($cat['hero_photo'] ?? ''); ?>" style="max-width: 200px; border-radius: 8px;">
                    <p style="font-size: 12px; color: #666;">Current photo</p>
                </div>
            <?php endif; ?>
            <input type="file" id="hero_photo_input" accept="image/*" onchange="uploadImage(this)">
            <div id="image_preview" style="margin-top: 10px;"></div>
            <small style="color: #666;">Upload new photo to replace current one</small>
        </div>
        
        <button class="btn" type="submit">💾 Update Resident</button>
        <a class="btn btn-outline" href="/admin/residents/index.php">Cancel</a>
    </form>
</div>

<script>
function uploadImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const formData = new FormData();
        formData.append('file', file);
        
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
                    '<small style="color: green;">✓ New image uploaded</small>';
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
