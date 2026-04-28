<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();
// Check if admin is logged in
// Authentication handled by requireAdmin()

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $tier = $_POST['tier'] ?? '';
    $website_url = trim($_POST['website_url'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $featured = isset($_POST['featured_on_homepage']) ? 1 : 0;
    $display_order = intval($_POST['display_order'] ?? 0);
    $active = isset($_POST['active']) ? 1 : 0;
    
    // Validation
    if (empty($name)) $errors[] = 'Name is required';
    if (empty($tier)) $errors[] = 'Tier is required';
    
    // Handle logo upload
    $logo_path = '';
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../public/uploads/sponsors/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
        
        if (in_array($file_ext, $allowed_exts)) {
            $filename = uniqid('sponsor_') . '.' . $file_ext;
            $logo_path = '/uploads/sponsors/' . $filename;
            move_uploaded_file($_FILES['logo']['tmp_name'], $upload_dir . $filename);
        } else {
            $errors[] = 'Invalid file type. Allowed: JPG, PNG, GIF, SVG';
        }
    } else {
        $errors[] = 'Logo is required';
    }
    
    if (empty($errors)) {
        db()->execute(
            "INSERT INTO sponsors (name, logo_url, website_url, description, is_active, display_order) 
             VALUES (?, ?, ?, ?, ?, ?)",
            [$name, $logo_path, $website_url, $description, $active, $display_order]
        );
        
        header('Location: index.php?success=added');
        exit;
    }
}
?>

<div class="admin-content">
    <div class="admin-header">
        <h1>Add New Sponsor</h1>
        <a href="index.php" class="btn btn-outline">Back to Sponsors</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <strong>Please fix the following errors:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error ?? '') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Sponsor Details</h2>
        </div>
        <div class="admin-card-body">
            <form method="POST" enctype="multipart/form-data" class="admin-form">

                <div class="form-section">
                    <h3 class="form-section-title">Basic Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Sponsor Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name" required placeholder="Enter sponsor name..."
                                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="tier">Sponsor Tier <span class="required">*</span></label>
                            <select id="tier" name="tier" required>
                                <option value="">Select Tier</option>
                                <option value="bronze" <?= ($_POST['tier'] ?? '') === 'bronze' ? 'selected' : '' ?>>Bronze</option>
                                <option value="silver" <?= ($_POST['tier'] ?? '') === 'silver' ? 'selected' : '' ?>>Silver</option>
                                <option value="gold" <?= ($_POST['tier'] ?? '') === 'gold' ? 'selected' : '' ?>>Gold</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="website_url">Website URL</label>
                        <input type="url" id="website_url" name="website_url" 
                               placeholder="https://example.com"
                               value="<?= htmlspecialchars($_POST['website_url'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" placeholder="Briefly describe the sponsor..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">Media</h3>
                    <div class="form-group">
                        <label for="logo">Logo Image <span class="required">*</span></label>
                        <div class="file-upload-area">
                            <span class="file-upload-icon">🖼️</span>
                            <span class="file-upload-text">Click or drag to upload a logo</span>
                            <input type="file" id="logo" name="logo" accept="image/*" required class="file-upload-input" onchange="previewImage(this, 'logo-preview')">
                            <span class="file-upload-hint">Recommended: 300x300px. Allowed: JPG, PNG, GIF, SVG.</span>
                        </div>
                        <div class="file-upload-preview" id="logo-preview"></div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">Status & Options</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="display_order">Display Order</label>
                            <input type="number" id="display_order" name="display_order" min="0" 
                                   value="<?= htmlspecialchars($_POST['display_order'] ?? '0') ?>">
                            <small class="form-hint">Lower numbers appear first on the sponsors page.</small>
                        </div>
                        <div class="form-group">
                            <label>Settings</label>
                            <div class="checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="featured_on_homepage" value="1" 
                                           <?= isset($_POST['featured_on_homepage']) ? 'checked' : '' ?>>
                                    Featured on Homepage
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="active" value="1" checked>
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Sponsor</button>
                    <a href="index.php" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Image preview"/>';
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.innerHTML = '';
        preview.style.display = 'none';
    }
}
</script>

<?php require_once '../includes/admin-footer.php'; ?>
