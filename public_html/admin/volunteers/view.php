<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

$id = (int)($_GET['id'] ?? 0);
$volunteer = db()->fetchOne("SELECT * FROM volunteers WHERE id = ?", [$id]);

if (!$volunteer) {
    flash('error', 'Volunteer not found');
    redirect('/admin/volunteers/index.php');
    exit;
}

$page_title = 'View Volunteer';
require_once __DIR__ . '/../includes/admin-header.php';
?>

<div class="dashboard-section">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>🤝 Volunteer Details</h2>
        <div>
            <a href="/admin/volunteers/edit.php?id=<?php echo $id; ?>" class="btn">Edit</a>
            <a href="/admin/volunteers/index.php" class="btn btn-outline">Back to List</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <tbody>
                <tr>
                    <th style="width: 200px;">ID</th>
                    <td><?php echo (int)($volunteer['id'] ?? 0); ?></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?php echo htmlspecialchars($volunteer['first_name'] . ' ' . $volunteer['last_name'] ?? ''); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><a href="mailto:<?php echo htmlspecialchars($volunteer['email'] ?? ''); ?>"><?php echo htmlspecialchars($volunteer['email'] ?? ''); ?></a></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><a href="tel:<?php echo htmlspecialchars($volunteer['phone'] ?? ''); ?>"><?php echo htmlspecialchars($volunteer['phone'] ?? ''); ?></a></td>
                </tr>
                <?php if (!empty($volunteer['address'])): ?>
                <tr>
                    <th>Address</th>
                    <td><?php echo nl2br(htmlspecialchars($volunteer['address'] ?? '')); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($volunteer['experience'])): ?>
                <tr>
                    <th>Experience</th>
                    <td><?php echo nl2br(htmlspecialchars($volunteer['experience'] ?? '')); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($volunteer['availability'])): ?>
                <tr>
                    <th>Availability</th>
                    <td><?php echo nl2br(htmlspecialchars($volunteer['availability'] ?? '')); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($volunteer['interests'])): ?>
                <tr>
                    <th>Interests</th>
                    <td><?php echo nl2br(htmlspecialchars($volunteer['interests'] ?? '')); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($volunteer['emergency_contact'])): ?>
                <tr>
                    <th>Emergency Contact</th>
                    <td><?php echo nl2br(htmlspecialchars($volunteer['emergency_contact'] ?? '')); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge badge-<?php 
                            echo $volunteer['status'] === 'approved' ? 'success' : 
                                ($volunteer['status'] === 'denied' ? 'danger' : 'warning'); 
                        ?>">
                            <?php echo ucfirst($volunteer['status'] ?? ''); ?>
                        </span>
                    </td>
                </tr>
                <?php if (!empty($volunteer['volunteer_id'])): ?>
                <tr>
                    <th>Volunteer ID</th>
                    <td><?php echo htmlspecialchars($volunteer['volunteer_id'] ?? ''); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (isset($volunteer['background_check'])): ?>
                <tr>
                    <th>Background Check</th>
                    <td><?php echo ($volunteer['background_check'] ?? null) ? '✅ Completed' : '❌ Not Completed'; ?></td>
                </tr>
                <?php endif; ?>
                <?php if (isset($volunteer['orientation_completed'])): ?>
                <tr>
                    <th>Orientation</th>
                    <td><?php echo ($volunteer['orientation_completed'] ?? null) ? '✅ Completed' : '❌ Not Completed'; ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th>Applied On</th>
                    <td><?php echo formatDateTime($volunteer['created_at'] ?? ''); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php if ($volunteer['status'] === 'pending'): ?>
    <div style="margin-top: 20px; display: flex; gap: 10px;">
        <form method="post" action="/admin/volunteers/approve.php" style="display: inline;">
            <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit" class="btn" onclick="return confirm('Approve this volunteer application?')">
                ✅ Approve
            </button>
        </form>
        
        <form method="post" action="/admin/volunteers/reject.php" style="display: inline;">
            <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit" class="btn btn-danger" onclick="return confirm('Reject this volunteer application?')">
                ❌ Reject
            </button>
        </form>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
