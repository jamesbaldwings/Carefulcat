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

$cat = db()->fetchOne("SELECT * FROM cats WHERE id = ? AND status = \'sanctuary\'", [$id]);
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
        db()->query("UPDATE cats SET name=?, species=?, sex=?, age=?, location=?, bio=?, shelter_tag=?, hero_photo=? WHERE id=? AND status = \'sanctuary\'",
            [$name, $species, $sex, $age, $location, $bio, $shelter_tag, $hero_photo, $id]);
        flash('success', 'Resident updated successfully!');
        redirect('/admin/residents/index.php');
        exit;
    }
}

require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
    <div class="admin-card-header">
        <h1 class="admin-card-title">Edit Resident: <?php echo htmlspecialchars($cat['name'] ?? ''); ?></h1>
    </div>
    <div class="admin-card-body">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="hero_photo" id="hero_photo_url" value="<?php echo htmlspecialchars($cat['hero_photo'] ?? ''); ?>">

            <div class="form-section">
                <h2 class="form-section-title">Basic Information</h2>
                <div class="form-group">
                    <label for="name">Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($cat['name'] ?? ''); ?>" placeholder="Enter cat name..." required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="species">Species</label>
                        <input type="text" id="species" name="species" value="<?php echo htmlspecialchars($cat['species'] ?? 'Felis catus'); ?>" placeholder="e.g., Felis catus">
                    </div>
                    <div class="form-group">
                        <label for="sex">Sex <span class="required">*</span></label>
                        <select id="sex" name="sex" required>
                            <option value="">Select Sex...</option>
                            <option value="M" <?php echo ($cat['sex'] ?? '') === 'M' ? 'selected' : ''; ?>>Male</option>
                            <option value="F" <?php echo ($cat['sex'] ?? '') === 'F' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2 class="form-section-title">Details</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="text" id="age" name="age" value="<?php echo htmlspecialchars($cat['age'] ?? ''); ?>" placeholder="e.g., 2 years">
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($cat['location'] ?? ''); ?>" placeholder="e.g., Main Cattery">
                    </div>
                </div>
                <div class="form-group">
                    <label for="shelter_tag">Shelter Tag</label>
                    <input type="text" id="shelter_tag" name="shelter_tag" value="<?php echo htmlspecialchars($cat['shelter_tag'] ?? ''); ?>" placeholder="Internal tracking ID">
                </div>
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="5" placeholder="A short story about the resident..."><?php echo htmlspecialchars($cat['bio'] ?? ''); ?></textarea>
                </div>
            </div>

            <div class="form-section">
                <h2 class="form-section-title">Media</h2>
                <div class="form-group">
                    <label>Hero Photo</label>
                    <div class="file-upload-area">
                        <span class="file-upload-icon">📷</span>
                        <input type="file" id="hero_photo_input" class="file-upload-input" accept="image/*" onchange="uploadImage(this)">
                        <p class="file-upload-text">Click or drag a new photo to upload</p>
                        <span class="file-upload-hint">Replaces the current photo. Max 2MB.</span>
                    </div>
                    <div id="image_preview" class="file-upload-preview" style="margin-top: 15px;">
                        <?php if (!empty($cat['hero_photo'])): ?>
                            <img src="<?php echo htmlspecialchars($cat['hero_photo']); ?>" alt="Current Photo" style="max-width: 200px; border-radius: 8px;">
                            <small>Current photo</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Save Changes</button>
                <a href="/admin/residents/index.php" class="btn btn-outline">Cancel</a>
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
                    '<img src="' + data.url + '" style="max-width: 200px; border-radius: 8px;"><br>' +
                    '<small style="color: green;">✓ New image uploaded successfully.</small>';
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
