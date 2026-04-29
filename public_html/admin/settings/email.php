<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$page_title = 'Email Settings';
$errors = [];
$success = '';

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
    // Table may already exist or DB issue - continue gracefully
}

// Helper to get a site setting
function getEmailSetting(string $key, string $default = ''): string {
    try {
        $row = db()->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = ?", [$key]);
        return $row ? ($row['setting_value'] ?? $default) : $default;
    } catch (Throwable $e) {
        return $default;
    }
}

// Helper to save a site setting
function saveEmailSetting(string $key, string $value, string $desc = ''): void {
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

    if (!$errors) {
        $fields = [
            'email_notification_address' => ['value' => trim($_POST['notification_email'] ?? ''), 'desc' => 'Notification email address'],
            'email_from_name'            => ['value' => trim($_POST['from_name'] ?? ''),          'desc' => 'Email from name'],
            'email_from_address'         => ['value' => trim($_POST['from_address'] ?? ''),       'desc' => 'Email from address'],
            'email_on_adoption'          => ['value' => isset($_POST['email_on_adoption']) ? '1' : '0',   'desc' => 'Send email on new adoption application'],
            'email_on_donation'          => ['value' => isset($_POST['email_on_donation']) ? '1' : '0',   'desc' => 'Send email on new donation'],
            'email_on_volunteer'         => ['value' => isset($_POST['email_on_volunteer']) ? '1' : '0',  'desc' => 'Send email on new volunteer application'],
            'email_on_contact'           => ['value' => isset($_POST['email_on_contact']) ? '1' : '0',    'desc' => 'Send email on new contact message'],
        ];

        try {
            foreach ($fields as $key => $data) {
                saveEmailSetting($key, $data['value'], $data['desc']);
            }
            flash('success', 'Email settings saved successfully!');
            redirect('/admin/settings/email.php');
            exit;
        } catch (Throwable $e) {
            $errors[] = 'Failed to save settings: ' . $e->getMessage();
        }
    }
}

// Load current values
$notification_email = getEmailSetting('email_notification_address', '');
$from_name          = getEmailSetting('email_from_name', '');
$from_address       = getEmailSetting('email_from_address', '');
$email_on_adoption  = getEmailSetting('email_on_adoption', '1');
$email_on_donation  = getEmailSetting('email_on_donation', '1');
$email_on_volunteer = getEmailSetting('email_on_volunteer', '1');
$email_on_contact   = getEmailSetting('email_on_contact', '1');

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
        <h2 class="admin-card-title">✉️ Email Settings</h2>
        <a href="/admin/settings/index.php" class="btn btn-outline btn-sm">Back to Settings</a>
    </div>

    <form method="post" style="padding: 1.75rem;">
        <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">

        <div class="form-section">
            <div class="form-section-title">Email Configuration</div>

            <div class="form-group">
                <label>Notification Email Address</label>
                <input type="email" name="notification_email"
                       value="<?php echo htmlspecialchars($notification_email); ?>"
                       placeholder="admin@carefulcatrescue.org">
                <small class="form-hint">Where notification emails will be sent (e.g., new applications, donations).</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Email From Name</label>
                    <input type="text" name="from_name"
                           value="<?php echo htmlspecialchars($from_name); ?>"
                           placeholder="Careful Cat Rescue">
                    <small class="form-hint">The sender name shown in outgoing emails.</small>
                </div>

                <div class="form-group">
                    <label>Email From Address</label>
                    <input type="email" name="from_address"
                           value="<?php echo htmlspecialchars($from_address); ?>"
                           placeholder="noreply@carefulcatrescue.org">
                    <small class="form-hint">The sender email address for outgoing emails.</small>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">Email Notifications</div>
            <p class="form-hint" style="margin-bottom: 1.25rem;">Choose which events should trigger an email notification to the address above.</p>

            <div class="form-group">
                <label class="toggle-switch">
                    <input type="checkbox" name="email_on_adoption" value="1" <?php echo $email_on_adoption === '1' ? 'checked' : ''; ?>>
                    <span class="toggle-slider"></span>
                    <span class="toggle-label">Send email on new adoption application</span>
                </label>
            </div>

            <div class="form-group">
                <label class="toggle-switch">
                    <input type="checkbox" name="email_on_donation" value="1" <?php echo $email_on_donation === '1' ? 'checked' : ''; ?>>
                    <span class="toggle-slider"></span>
                    <span class="toggle-label">Send email on new donation</span>
                </label>
            </div>

            <div class="form-group">
                <label class="toggle-switch">
                    <input type="checkbox" name="email_on_volunteer" value="1" <?php echo $email_on_volunteer === '1' ? 'checked' : ''; ?>>
                    <span class="toggle-slider"></span>
                    <span class="toggle-label">Send email on new volunteer application</span>
                </label>
            </div>

            <div class="form-group">
                <label class="toggle-switch">
                    <input type="checkbox" name="email_on_contact" value="1" <?php echo $email_on_contact === '1' ? 'checked' : ''; ?>>
                    <span class="toggle-slider"></span>
                    <span class="toggle-label">Send email on new contact message</span>
                </label>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Email Settings</button>
            <a href="/admin/settings/index.php" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
