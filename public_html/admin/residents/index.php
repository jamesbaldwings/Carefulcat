<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$page_title = 'Sanctuary Residents';

// Get all sanctuary cats
$residents = db()->fetchAll("SELECT * FROM cats WHERE status = 'sanctuary' ORDER BY created_at DESC");

require_once __DIR__ . '/../includes/admin-header.php';
?>

<div class="admin-header-actions" style="margin-bottom: 20px;">
    <a href="/admin/residents/create.php" class="btn">+ New Resident</a>
</div>

<?php if ($m = flash_out('success')): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($m ?? ''); ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">🏠 Sanctuary Residents</h2>
        <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">
            Cats living permanently at the sanctuary
        </p>
    </div>

    <?php if (count($residents) > 0): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Species</th>
                    <th>Sex</th>
                    <th>Age</th>
                    <th>Shelter Tag</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($residents as $cat): ?>
                    <tr>
                        <td>
                            <?php if ($cat['hero_photo'] ?? null): ?>
                                <img src="<?php echo htmlspecialchars($cat['hero_photo'] ?? ''); ?>" 
                                     alt="<?php echo htmlspecialchars($cat['name'] ?? ''); ?>" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            <?php else: ?>
                                <div style="width: 50px; height: 50px; background: #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                    🐱
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?php echo htmlspecialchars($cat['name'] ?? ''); ?></strong></td>
                        <td><?php echo htmlspecialchars($cat['species'] ?? 'N/A'); ?></td>
                        <td><?php echo $cat['sex'] === 'M' ? 'Male' : ($cat['sex'] === 'F' ? 'Female' : 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($cat['age'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($cat['shelter_tag'] ?? 'N/A'); ?></td>
                        <td class="admin-table-actions">
                            <a href="/admin/residents/view.php?id=<?php echo urlencode($cat['id']); ?>" class="btn btn-sm">View</a>
                            <a href="/admin/residents/edit.php?id=<?php echo urlencode($cat['id']); ?>" class="btn btn-sm btn-outline">Edit</a>
                            <a href="/admin/residents/delete.php?id=<?php echo urlencode($cat['id']); ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Are you sure you want to remove this resident?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="admin-empty-state">
            <div class="admin-empty-icon">🏠</div>
            <h3>No Sanctuary Residents</h3>
            <p>There are currently no cats marked as sanctuary residents.</p>
            <a href="/admin/residents/create.php" class="btn">Add First Resident</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
