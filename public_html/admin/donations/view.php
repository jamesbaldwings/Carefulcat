<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

$id = $_GET['id'] ?? '';
$donation = db()->fetchOne("SELECT * FROM donations WHERE id = ?", [$id]);

if (!$donation) {
    flash('error', 'Donation not found');
    redirect('/admin/donations/index.php');
    exit;
}

$page_title = 'View Donation';
require_once __DIR__ . '/../includes/admin-header.php';
?>

<div class="dashboard-section">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>💰 Donation Details</h2>
        <div>
            <a href="/admin/donations/index.php" class="btn btn-outline">Back to List</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <tbody>
                <tr>
                    <th style="width: 200px;">Donation ID</th>
                    <td><?php echo htmlspecialchars($donation['id'] ?? ''); ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge badge-<?php 
                            echo $donation['status'] === 'completed' ? 'success' : 
                                ($donation['status'] === 'failed' ? 'danger' : 'warning'); 
                        ?>">
                            <?php echo ucfirst($donation['status'] ?? ''); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th colspan="2" style="background: #f5f5f5; font-weight: bold;">Donor Information</th>
                </tr>
                <tr>
                    <th>Donor Name</th>
                    <td><?php 
                        $donor_name = trim(($donation['first_name'] ?? '') . ' ' . ($donation['last_name'] ?? ''));
                        echo htmlspecialchars($donor_name ?: 'Anonymous'); 
                    ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><a href="mailto:<?php echo htmlspecialchars($donation['email'] ?? ''); ?>"><?php echo htmlspecialchars($donation['email'] ?? ''); ?></a></td>
                </tr>
                <?php if (!empty($donation['donor_phone'])): ?>
                <tr>
                    <th>Phone</th>
                    <td><a href="tel:<?php echo htmlspecialchars($donation['donor_phone'] ?? ''); ?>"><?php echo htmlspecialchars($donation['donor_phone'] ?? ''); ?></a></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($donation['donor_address'])): ?>
                <tr>
                    <th>Address</th>
                    <td><?php echo nl2br(htmlspecialchars($donation['donor_address'] ?? '')); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th colspan="2" style="background: #f5f5f5; font-weight: bold;">Donation Details</th>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td style="font-size: 1.2em; font-weight: bold; color: #28a745;">
                        <?php echo formatCurrency($donation['amount']); ?>
                    </td>
                </tr>
                <?php if (!empty($donation['type'])): ?>
                <tr>
                    <th>Type</th>
                    <td><?php echo htmlspecialchars(ucfirst($donation['type'] ?? '')); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($donation['donation_type'])): ?>
                <tr>
                    <th>Donation Type</th>
                    <td><?php echo htmlspecialchars(ucfirst($donation['donation_type'] ?? '')); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($donation['sponsored_cat_id'])): ?>
                <tr>
                    <th>Sponsored Cat</th>
                    <td><?php echo htmlspecialchars($donation['sponsored_cat_id'] ?? ''); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($donation['payment_method'])): ?>
                <tr>
                    <th>Payment Method</th>
                    <td><?php echo htmlspecialchars(ucfirst($donation['payment_method'] ?? '')); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($donation['transaction_id'])): ?>
                <tr>
                    <th>Transaction ID</th>
                    <td><code><?php echo htmlspecialchars($donation['transaction_id'] ?? ''); ?></code></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($donation['stripe_payment_intent_id'])): ?>
                <tr>
                    <th>Stripe Payment Intent</th>
                    <td><code><?php echo htmlspecialchars($donation['stripe_payment_intent_id'] ?? ''); ?></code></td>
                </tr>
                <?php endif; ?>
                <?php if (isset($donation['is_recurring']) && $donation['is_recurring']): ?>
                <tr>
                    <th>Recurring</th>
                    <td>✅ Yes</td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($donation['recurring_frequency'])): ?>
                <tr>
                    <th>Frequency</th>
                    <td><?php echo htmlspecialchars(ucfirst($donation['recurring_frequency'] ?? '')); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($donation['message'])): ?>
                <tr>
                    <th>Message</th>
                    <td><?php echo nl2br(htmlspecialchars($donation['message'] ?? '')); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($donation['dedication'])): ?>
                <tr>
                    <th>Dedication</th>
                    <td><?php echo nl2br(htmlspecialchars($donation['dedication'] ?? '')); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (isset($donation['is_anonymous']) && $donation['is_anonymous']): ?>
                <tr>
                    <th>Anonymous</th>
                    <td>✅ Yes</td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th colspan="2" style="background: #f5f5f5; font-weight: bold;">Timeline</th>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td><?php echo formatDateTime($donation['created_at'] ?? ''); ?></td>
                </tr>
                <?php if (!empty($donation['updated_at'])): ?>
                <tr>
                    <th>Updated At</th>
                    <td><?php echo formatDateTime($donation['updated_at'] ?? ''); ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
