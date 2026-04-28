<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$id = $_GET['id'] ?? '';
if (!$id) {
    flash('error', 'Invalid donation ID');
    redirect('/admin/donations/index.php');
    exit;
}

$donation = db()->fetchOne("SELECT * FROM donations WHERE id = ?", [$id]);
if (!$donation) {
    flash('error', 'Donation not found');
    redirect('/admin/donations/index.php');
    exit;
}

$page_title = 'Edit Donation';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf'] ?? '')) { $errors[] = 'Invalid CSRF token.'; }
    
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $amount = (float)($_POST['amount'] ?? 0);
    $status = $_POST['status'] ?? 'pending';
    
    if ($first_name === '') { $errors[] = 'First name is required.'; }
    if ($last_name === '') { $errors[] = 'Last name is required.'; }
    if ($email === '') { $errors[] = 'Email is required.'; }
    if ($amount <= 0) { $errors[] = 'Amount must be greater than zero.'; }
    
    if (!$errors) {
        db()->query("UPDATE donations SET first_name=?, last_name=?, email=?, amount=?, status=? WHERE id=?",
            [$first_name, $last_name, $email, $amount, $status, $id]);
        flash('success', 'Donation updated successfully!');
        redirect('/admin/donations/index.php');
        exit;
    }
}

require_once __DIR__ . '/../includes/admin-header.php';
?>

<div class="admin-card">
    <div class="admin-card-header">
        <h1 class="admin-card-title">Edit Donation</h1>
    </div>
    <div class="admin-card-body">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">

            <div class="form-section">
                <h2 class="form-section-title">Donor Information</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name <span class="required">*</span></label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($donation['first_name'] ?? ''); ?>" placeholder="Enter first name..." required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name <span class="required">*</span></label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($donation['last_name'] ?? ''); ?>" placeholder="Enter last name..." required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($donation['email'] ?? ''); ?>" placeholder="donor@example.com" required>
                </div>
            </div>

            <div class="form-section">
                <h2 class="form-section-title">Donation Details</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="amount">Amount ($) <span class="required">*</span></label>
                        <input type="number" id="amount" name="amount" step="0.01" min="0.01" value="<?php echo htmlspecialchars($donation['amount'] ?? ''); ?>" placeholder="e.g., 50.00" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="pending" <?php echo ($donation['status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="completed" <?php echo ($donation['status'] ?? '') === 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="failed" <?php echo ($donation['status'] ?? '') === 'failed' ? 'selected' : ''; ?>>Failed</option>
                            <option value="refunded" <?php echo ($donation['status'] ?? '') === 'refunded' ? 'selected' : ''; ?>>Refunded</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Donation</button>
                <a href="/admin/donations/index.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
