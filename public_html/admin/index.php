<?php
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
/**
 * Admin Dashboard - FIXED VERSION
 * FIXES: Uses requireAdmin() which checks isAdminLoggedIn()
 */
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

// Require admin authentication
requireAdmin();


$page_title = 'Dashboard';

// Get statistics
$stats = [
    'total_cats' => db()->fetchOne("SELECT COUNT(*) as count FROM cats")['count'],
    'adoptable_cats' => db()->fetchOne("SELECT COUNT(*) as count FROM cats WHERE status = 'adoptable'")['count'],
    'pending_volunteers' => db()->fetchOne("SELECT COUNT(*) as count FROM volunteers WHERE status = 'pending'")['count'],
    'total_donations' => db()->fetchOne("SELECT SUM(amount) as total FROM donations WHERE status = 'completed'")['total'] ?? 0,
    'recent_contacts' => db()->fetchOne("SELECT COUNT(*) as count FROM contacts WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")['count'],
];

// Get recent activities
$recentDonations = db()->fetchAll(
    "SELECT * FROM donations ORDER BY created_at DESC LIMIT 5"
);

$recentContacts = db()->fetchAll(
    "SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5"
);

require_once __DIR__ . '/includes/admin-header.php';
?>

<div class="dashboard-grid">
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background-color: var(--primary-color);">🐱</div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['total_cats']); ?></h3>
                <p>Total Cats</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background-color: var(--secondary-color);">❤️</div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['adoptable_cats']); ?></h3>
                <p>Adoptable Cats</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #ff9800;">🤝</div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['pending_volunteers']); ?></h3>
                <p>Pending Volunteers</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #4caf50;">💰</div>
            <div class="stat-content">
                <h3><?php echo formatCurrency($stats['total_donations']); ?></h3>
                <p>Total Donations</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #2196f3;">✉️</div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['recent_contacts']); ?></h3>
                <p>Recent Messages (7 days)</p>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="dashboard-section">
        <h2>Recent Donations</h2>
        <?php if (empty($recentDonations)): ?>
            <p class="text-muted">No recent donations</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Donor</th>
                            <th>Email</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentDonations as $donation): ?>
                            <tr>
                                <td><?php echo formatDateTime($donation['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($donation['donor_name']); ?></td>
                                <td><?php echo htmlspecialchars($donation['donor_email']); ?></td>
                                <td><?php echo formatCurrency($donation['amount']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $donation['status'] === 'completed' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($donation['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center" style="margin-top: 20px;">
                <a href="/admin/donations/index.php" class="btn btn-outline">View All Donations</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="dashboard-section">
        <h2>Recent Messages</h2>
        <?php if (empty($recentContacts)): ?>
            <p class="text-muted">No recent messages</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentContacts as $contact): ?>
                            <tr>
                                <td><?php echo formatDateTime($contact['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($contact['name']); ?></td>
                                <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                <td><?php echo htmlspecialchars($contact['subject']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $contact['status'] === 'read' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($contact['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center" style="margin-top: 20px;">
                <a href="/admin/messages/index.php" class="btn btn-outline">View All Messages</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

