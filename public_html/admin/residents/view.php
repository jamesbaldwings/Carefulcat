<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$id = $_GET['id'] ?? '';
if (!$id) {
    flash('error', 'Invalid resident ID');
    redirect('/admin/residents/index.php');
    exit;
}

$cat = db()->fetchOne("SELECT * FROM cats WHERE id = ? AND status = 'sanctuary'", [$id]);
if (!$cat) {
    flash('error', 'Resident not found');
    redirect('/admin/residents/index.php');
    exit;
}

$page_title = 'View Resident: ' . $cat['name'];
require_once __DIR__ . '/../includes/admin-header.php';
?>

<div class="admin-header-actions" style="margin-bottom: 20px;">
    <a href="/admin/residents/index.php" class="btn btn-outline">← Back to Residents</a>
    <a href="/admin/residents/edit.php?id=<?php echo urlencode($cat['id']); ?>" class="btn">Edit Resident</a>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">🏠 <?php echo htmlspecialchars($cat['name']); ?></h2>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
        <!-- Photo -->
        <div>
            <?php if ($cat['hero_photo']): ?>
                <img src="<?php echo htmlspecialchars($cat['hero_photo']); ?>" 
                     alt="<?php echo htmlspecialchars($cat['name']); ?>"
                     style="width: 100%; border-radius: 8px;">
            <?php else: ?>
                <div style="width: 100%; aspect-ratio: 1; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 48px;">
                    🐱
                </div>
            <?php endif; ?>
        </div>

        <!-- Details -->
        <div>
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 0; font-weight: bold; width: 150px;">Species:</td>
                    <td style="padding: 12px 0;"><?php echo htmlspecialchars($cat['species'] ?? 'N/A'); ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 0; font-weight: bold;">Sex:</td>
                    <td style="padding: 12px 0;"><?php echo $cat['sex'] === 'M' ? 'Male' : ($cat['sex'] === 'F' ? 'Female' : 'N/A'); ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 0; font-weight: bold;">Age:</td>
                    <td style="padding: 12px 0;"><?php echo htmlspecialchars($cat['age'] ?? 'N/A'); ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 0; font-weight: bold;">Location:</td>
                    <td style="padding: 12px 0;"><?php echo htmlspecialchars($cat['location'] ?? 'N/A'); ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 0; font-weight: bold;">Shelter Tag:</td>
                    <td style="padding: 12px 0;"><?php echo htmlspecialchars($cat['shelter_tag'] ?? 'N/A'); ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 0; font-weight: bold;">Status:</td>
                    <td style="padding: 12px 0;">
                        <span style="background: #9b59b6; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                            Sanctuary Resident
                        </span>
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 0; font-weight: bold;">Intake Date:</td>
                    <td style="padding: 12px 0;"><?php echo date('M d, Y', strtotime($cat['intake_date'] ?? $cat['created_at'])); ?></td>
                </tr>
            </table>

            <?php if ($cat['bio']): ?>
                <div style="margin-top: 20px;">
                    <h3 style="margin-bottom: 10px;">About <?php echo htmlspecialchars($cat['name']); ?></h3>
                    <p style="line-height: 1.6; color: #555;">
                        <?php echo nl2br(htmlspecialchars($cat['bio'])); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
