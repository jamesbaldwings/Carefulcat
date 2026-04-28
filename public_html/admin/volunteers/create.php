<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$page_title = 'Add Volunteer';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf'] ?? '')) {
        $errors[] = 'Invalid CSRF token.';
    }
    $fn = trim($_POST['first_name'] ?? '');
    $ln = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $status = $_POST['status'] ?? 'pending';
    if ($fn === '' || $ln === '' || $email === '') {
        $errors[] = 'First name, last name, and email are required.';
    }
    if (empty($errors)) {
        db()->prepare("INSERT INTO volunteers (first_name, last_name, email, phone, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())")->execute([$fn, $ln, $email, $phone, $status]);
        flash('success', 'Volunteer added successfully.');
        redirect('/admin/volunteers/index.php');
    }
}
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
    <div class="admin-card-header">
        <h1 class="admin-card-title">🤝 Add New Volunteer</h1>
    </div>
    <div class="admin-card-body">
        <?php if (!empty($errors)) : ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error) : ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="create.php" method="post">
            <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">

            <div class="form-section">
                <h2 class="form-section-title">Personal Information</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name <span class="required">*</span></label>
                        <input type="text" id="first_name" name="first_name" placeholder="e.g., Jane" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name <span class="required">*</span></label>
                        <input type="text" id="last_name" name="last_name" placeholder="e.g., Doe" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2 class="form-section-title">Contact Details</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" placeholder="e.g., volunteer@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="e.g., (123) 456-7890" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2 class="form-section-title">Status & Options</h2>
                <div class="form-group">
                    <label for="status">Application Status</label>
                    <select id="status" name="status">
                        <?php
                        $statuses = ['pending', 'approved', 'denied'];
                        $current_status = $_POST['status'] ?? 'pending';
                        foreach ($statuses as $s) {
                            $selected = ($s === $current_status) ? ' selected' : '';
                            echo '<option value="'.htmlspecialchars($s).'"' . $selected . '>'.ucfirst($s).'</option>';
                        }
                        ?>
                    </select>
                    <small class="form-hint">Set the current status of this volunteer application.</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Volunteer</button>
                <a href="/admin/volunteers/index.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
