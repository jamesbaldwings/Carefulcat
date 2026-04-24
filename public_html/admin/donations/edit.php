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
        <h2 class="admin-card-title">💰 Edit Donation</h2>
    </div>
    
    <?php if ($errors): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div>
    <?php endif; ?>
    
    <form method="post">
        <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
        
        <div class="form-group">
            <label>First Name <span style="color:red;">*</span></label>
            <input name="first_name" required value="<?php echo htmlspecialchars($donation['first_name']); ?>">
        </div>
        
        <div class="form-group">
            <label>Last Name <span style="color:red;">*</span></label>
            <input name="last_name" required value="<?php echo htmlspecialchars($donation['last_name']); ?>">
        </div>
        
        <div class="form-group">
            <label>Email <span style="color:red;">*</span></label>
            <input name="email" type="email" required value="<?php echo htmlspecialchars($donation['email']); ?>">
        </div>
        
        <div class="form-group">
            <label>Amount ($) <span style="color:red;">*</span></label>
            <input name="amount" type="number" step="0.01" min="0.01" required value="<?php echo htmlspecialchars($donation['amount']); ?>">
        </div>
        
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="pending" <?php echo $donation['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="completed" <?php echo $donation['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                <option value="failed" <?php echo $donation['status'] === 'failed' ? 'selected' : ''; ?>>Failed</option>
                <option value="refunded" <?php echo $donation['status'] === 'refunded' ? 'selected' : ''; ?>>Refunded</option>
            </select>
        </div>
        
        <button class="btn" type="submit">💾 Update Donation</button>
        <a class="btn btn-outline" href="/admin/donations/index.php">Cancel</a>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
