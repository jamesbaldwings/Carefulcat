<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$page_title = 'Donation Settings';
$errors = [];

// Ensure site_settings table exists
try {
    db()->query("CREATE TABLE IF NOT EXISTS site_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) NOT NULL UNIQUE,
        setting_value TEXT,
        description VARCHAR(255) DEFAULT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
} catch (Throwable $e) {
    // Table may already exist — continue gracefully
}

// Helper to get a site setting
function getDonationSetting(string $key, string $default = ''): string {
    try {
        $row = db()->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = ?", [$key]);
        return $row ? ($row['setting_value'] ?? $default) : $default;
    } catch (Throwable $e) {
        return $default;
    }
}

// Helper to save a site setting
function saveDonationSetting(string $key, string $value, string $desc = ''): void {
    $existing = db()->fetchOne("SELECT id FROM site_settings WHERE setting_key = ?", [$key]);
    if ($existing) {
        db()->query("UPDATE site_settings SET setting_value = ?, description = ? WHERE setting_key = ?", [$value, $desc, $key]);
    } else {
        db()->query("INSERT INTO site_settings (setting_key, setting_value, description) VALUES (?, ?, ?)", [$key, $value, $desc]);
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf'] ?? '')) {
        $errors[] = 'Invalid CSRF token.';
    }

    $paypal_email      = trim($_POST['paypal_email'] ?? '');
    $paypal_enabled    = isset($_POST['paypal_enabled']) ? '1' : '0';
    $suggested_amounts = trim($_POST['suggested_amounts'] ?? '');
    $donation_message  = trim($_POST['donation_message'] ?? '');
    $min_amount        = trim($_POST['min_amount'] ?? '');

    // Validate minimum amount if provided
    if ($min_amount !== '' && (!is_numeric($min_amount) || (float)$min_amount < 0)) {
        $errors[] = 'Minimum donation amount must be a positive number.';
    }

    // Validate PayPal email if provided
    if ($paypal_email !== '' && !filter_var($paypal_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid PayPal email address.';
    }

    if (!$errors) {
        try {
            $fields = [
                'donation_paypal_email'      => ['value' => $paypal_email,      'desc' => 'PayPal email address for donations'],
                'donation_paypal_enabled'    => ['value' => $paypal_enabled,    'desc' => 'Enable/disable PayPal donations'],
                'donation_suggested_amounts' => ['value' => $suggested_amounts, 'desc' => 'Suggested donation amounts (comma-separated)'],
                'donation_page_message'      => ['value' => $donation_message,  'desc' => 'Donation page message/description'],
                'donation_min_amount'        => ['value' => $min_amount,        'desc' => 'Minimum donation amount'],
            ];

            foreach ($fields as $key => $data) {
                saveDonationSetting($key, $data['value'], $data['desc']);
            }

            flash('success', 'Donation settings saved successfully!');
            redirect('/admin/settings/donations.php');
            exit;
        } catch (Throwable $e) {
            $errors[] = 'Failed to save settings: ' . $e->getMessage();
        }
    }
}

// Load current values
$paypal_email      = getDonationSetting('donation_paypal_email', '');
$paypal_enabled    = getDonationSetting('donation_paypal_enabled', '0');
$suggested_amounts = getDonationSetting('donation_suggested_amounts', '10,25,50,100');
$donation_message  = getDonationSetting('donation_page_message', '');
$min_amount        = getDonationSetting('donation_min_amount', '5');

require_once __DIR__ . '/../includes/admin-header.php';
?>

<?php if ($m = flash_out('success')): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($m ?? ''); ?></div>
<?php endif; ?>
<?php if ($errors): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">💰 Donation Settings</h2>
        <a href="/admin/settings/index.php" class="btn btn-outline btn-sm">Back to Settings</a>
    </div>

    <form method="post" style="padding: 1.75rem;">
        <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">

        <div class="form-section">
            <div class="form-section-title">PayPal Configuration</div>

            <div class="form-group">
                <label>PayPal Email Address</label>
                <input type="email" name="paypal_email"
                       value="<?php echo htmlspecialchars($paypal_email); ?>"
                       placeholder="donations@carefulcatrescue.org">
                <small class="form-hint">The PayPal account email where donation payments will be sent.</small>
            </div>

            <div class="form-group">
                <label class="toggle-switch">
                    <input type="checkbox" name="paypal_enabled" value="1" <?php echo $paypal_enabled === '1' ? 'checked' : ''; ?>>
                    <span class="toggle-slider"></span>
                    <span class="toggle-label">Enable PayPal Donations</span>
                </label>
                <small class="form-hint" style="margin-top: 0.5rem;">When disabled, the PayPal donation option will be hidden from the donation page.</small>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">Donation Options</div>

            <div class="form-row">
                <div class="form-group">
                    <label>Suggested Donation Amounts</label>
                    <input type="text" name="suggested_amounts"
                           value="<?php echo htmlspecialchars($suggested_amounts); ?>"
                           placeholder="10,25,50,100">
                    <small class="form-hint">Comma-separated dollar amounts shown as quick-select buttons (e.g., 10,25,50,100).</small>
                </div>

                <div class="form-group">
                    <label>Minimum Donation Amount ($)</label>
                    <input type="number" name="min_amount" step="0.01" min="0"
                           value="<?php echo htmlspecialchars($min_amount); ?>"
                           placeholder="5.00">
                    <small class="form-hint">The minimum amount a donor can contribute.</small>
                </div>
            </div>

            <div class="form-group">
                <label>Donation Page Message</label>
                <textarea name="donation_message" rows="5"
                          placeholder="Write a heartfelt message to encourage donations..."><?php echo htmlspecialchars($donation_message); ?></textarea>
                <small class="form-hint">This message is displayed at the top of the public donation page. Use it to explain how donations help the cats.</small>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Donation Settings</button>
            <a href="/admin/settings/index.php" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
