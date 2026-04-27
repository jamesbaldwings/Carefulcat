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

<div class="dashboard-section">
    <h2>✉️ Reply to Message</h2>
    
    <?php if ($m = flash_out('error')): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($m ?? ''); ?></div>
    <?php endif; ?>
    
    <div class="admin-card" style="margin-bottom: 20px;">
        <h3>Original Message</h3>
        <table class="admin-table">
            <tbody>
                <tr>
                    <th style="width: 150px;">From</th>
                    <td><?php echo htmlspecialchars($sender_name . ' <' . ($message['email'] ?? '') . '>' ?? ''); ?></td>
                </tr>
                <?php if (!empty($message['phone'])): ?>
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
                    <td><pre style="white-space: pre-wrap; background: #f5f5f5; padding: 10px; border-radius: 4px;"><?php echo htmlspecialchars($message['message'] ?? ''); ?></pre></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="admin-card">
        <h3>Your Reply</h3>
        <form method="post" action="/admin/messages/reply.php?id=<?php echo $id; ?>">
            <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
            
            <div style="margin-bottom: 15px;">
                <label for="subject" style="display: block; margin-bottom: 5px; font-weight: bold;">Subject:</label>
                <input 
                    type="text" 
                    id="subject" 
                    name="subject" 
                    value="<?php echo htmlspecialchars('Re: ' . ($message['subject'] ?? '')); ?>" 
                    required 
                    style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"
                >
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="message" style="display: block; margin-bottom: 5px; font-weight: bold;">Message:</label>
                <textarea 
                    id="message" 
                    name="message" 
                    rows="10" 
                    required 
                    style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit;"
                    placeholder="Type your reply here..."
                ></textarea>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn">Send Reply</button>
                <a href="/admin/messages/view.php?id=<?php echo $id; ?>" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
