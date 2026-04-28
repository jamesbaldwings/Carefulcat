<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

$id = (int)($_GET['id'] ?? 0);
$message = db()->fetchOne("SELECT * FROM contacts WHERE id = ?", [$id]);

if (!$message) {
    flash('error', 'Message not found');
    redirect('/admin/messages/index.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf'] ?? '')) {
        flash('error', 'Invalid CSRF token');
        redirect('/admin/messages/reply.php?id=' . $id);
        exit;
    }
    
    $reply_subject = trim($_POST['subject'] ?? '');
    $reply_message = trim($_POST['message'] ?? '');
    
    if (empty($reply_subject) || empty($reply_message)) {
        flash('error', 'Subject and message are required');
        redirect('/admin/messages/reply.php?id=' . $id);
        exit;
    }
    
    try {
        // Send email using PHP mail() function
        $to = $message['email'] ?? '';
        $subject = $reply_subject;
        $body = $reply_message;
        $headers = [
            'From: ' . (getSetting('site_email', 'noreply@carefulcatrescue.org')),
            'Reply-To: ' . (getSetting('site_email', 'noreply@carefulcatrescue.org')),
            'X-Mailer: PHP/' . phpversion(),
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8'
        ];
        
        $success = mail($to, $subject, $body, implode("\r\n", $headers));
        
        if ($success) {
            // Update message status to replied (if status column exists)
            // Check if status column exists before updating
            try {
                db()->update('contacts', [
                    'status' => 'replied',
                    'replied_at' => date('Y-m-d H:i:s')
                ], 'id = ?', [$id]);
            } catch (Exception $e) {
                // If status column doesn't exist, just continue
                // The email was still sent successfully
            }
            
            flash('success', 'Reply sent successfully!');
            redirect('/admin/messages/view.php?id=' . $id);
        } else {
            flash('error', 'Failed to send email. Please check your mail server configuration.');
            redirect('/admin/messages/reply.php?id=' . $id);
        }
        
    } catch (Exception $e) {
        flash('error', 'Failed to send reply: ' . $e->getMessage());
        redirect('/admin/messages/reply.php?id=' . $id);
    }
    exit;
}

// Combine first_name and last_name for display
$sender_name = trim(($message['first_name'] ?? '') . ' ' . ($message['last_name'] ?? ''));

$page_title = 'Reply to Message';
require_once __DIR__ . '/../includes/admin-header.php';
?>

<div class="admin-card">
    <div class="admin-card-header">
        <h1 class="admin-card-title">✉️ Reply to Message</h1>
    </div>
    <div class="admin-card-body">
        <?php if ($m = flash_out('error')): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($m ?? ''); ?></div>
        <?php endif; ?>

        <div class="form-section">
            <h2 class="form-section-title">Original Message</h2>
            <table class="admin-table">
                <tbody>
                    <tr>
                        <th style="width: 150px;">From</th>
                        <td><?php echo htmlspecialchars($sender_name . ' <' . ($message['email'] ?? '') . '>' ?? ''); ?></td>
                    </tr>
                    <?php if (!empty($message['phone'])) : ?>
                        <tr>
                            <th>Phone</th>
                            <td><?php echo htmlspecialchars($message['phone'] ?? ''); ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Subject</th>
                        <td><?php echo htmlspecialchars($message['subject'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td><?php echo formatDateTime($message['created_at'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td><pre class="message-body"><?php echo htmlspecialchars($message['message'] ?? ''); ?></pre></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <form method="post" action="/admin/messages/reply.php?id=<?php echo $id; ?>">
            <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
            <div class="form-section">
                <h2 class="form-section-title">Your Reply</h2>
                <div class="form-group">
                    <label for="subject">Subject <span class="required">*</span></label>
                    <input type="text" id="subject" name="subject" value="<?php echo htmlspecialchars('Re: ' . ($message['subject'] ?? '')); ?>" required>
                </div>
                <div class="form-group">
                    <label for="message">Message <span class="required">*</span></label>
                    <textarea id="message" name="message" rows="10" required placeholder="Type your reply here..."></textarea>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Send Reply</button>
                <a href="/admin/messages/view.php?id=<?php echo $id; ?>" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
