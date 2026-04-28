<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$page_title = 'Backup & Export';

// Handle CSV export requests
if (isset($_GET['export']) && csrf_verify($_GET['token'] ?? '')) {
    $export = $_GET['export'];
    $allowed = ['cats', 'donations', 'adoptions', 'volunteers'];

    if (in_array($export, $allowed, true)) {
        try {
            $rows = db()->fetchAll("SELECT * FROM {$export}");

            if (empty($rows)) {
                flash('error', 'No data found in the ' . $export . ' table to export.');
                redirect('/admin/settings/backup.php');
                exit;
            }

            // Generate CSV
            $filename = $export . '_export_' . date('Y-m-d_His') . '.csv';

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Pragma: no-cache');
            header('Expires: 0');

            $output = fopen('php://output', 'w');

            // Write header row
            fputcsv($output, array_keys($rows[0]));

            // Write data rows
            foreach ($rows as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
            exit;

        } catch (Throwable $e) {
            flash('error', 'Export failed: ' . $e->getMessage());
            redirect('/admin/settings/backup.php');
            exit;
        }
    }
}

// Get record counts for display
$counts = [];
$tables = ['cats', 'donations', 'adoptions', 'volunteers'];
foreach ($tables as $table) {
    try {
        $result = db()->fetchOne("SELECT COUNT(*) as cnt FROM {$table}");
        $counts[$table] = (int)($result['cnt'] ?? 0);
    } catch (Throwable $e) {
        $counts[$table] = 0;
    }
}

$csrf = csrf_token();

require_once __DIR__ . '/../includes/admin-header.php';
?>

<?php if ($m = flash_out('success')): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($m ?? ''); ?></div>
<?php endif; ?>
<?php if ($m = flash_out('error')): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($m ?? ''); ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">💾 Backup & Export</h2>
        <a href="/admin/settings/index.php" class="btn btn-outline btn-sm">Back to Settings</a>
    </div>

    <div style="padding: 1.75rem;">
        <div class="form-section">
            <div class="form-section-title">Export Data as CSV</div>
            <p class="form-hint" style="margin-bottom: 1.5rem;">Download complete data exports from each table. Files are generated in CSV format and include all columns.</p>

            <div class="export-grid">
                <div class="export-card">
                    <div class="export-icon">🐱</div>
                    <h3>Cats Data</h3>
                    <p><?php echo number_format($counts['cats']); ?> records</p>
                    <?php if ($counts['cats'] > 0): ?>
                        <a href="/admin/settings/backup.php?export=cats&token=<?php echo urlencode($csrf); ?>" class="btn btn-primary btn-block">
                            Download CSV
                        </a>
                    <?php else: ?>
                        <span class="btn btn-outline btn-block" style="opacity: 0.5; cursor: not-allowed;">No Data</span>
                    <?php endif; ?>
                </div>

                <div class="export-card">
                    <div class="export-icon">💰</div>
                    <h3>Donations Data</h3>
                    <p><?php echo number_format($counts['donations']); ?> records</p>
                    <?php if ($counts['donations'] > 0): ?>
                        <a href="/admin/settings/backup.php?export=donations&token=<?php echo urlencode($csrf); ?>" class="btn btn-primary btn-block">
                            Download CSV
                        </a>
                    <?php else: ?>
                        <span class="btn btn-outline btn-block" style="opacity: 0.5; cursor: not-allowed;">No Data</span>
                    <?php endif; ?>
                </div>

                <div class="export-card">
                    <div class="export-icon">📝</div>
                    <h3>Adoption Applications</h3>
                    <p><?php echo number_format($counts['adoptions']); ?> records</p>
                    <?php if ($counts['adoptions'] > 0): ?>
                        <a href="/admin/settings/backup.php?export=adoptions&token=<?php echo urlencode($csrf); ?>" class="btn btn-primary btn-block">
                            Download CSV
                        </a>
                    <?php else: ?>
                        <span class="btn btn-outline btn-block" style="opacity: 0.5; cursor: not-allowed;">No Data</span>
                    <?php endif; ?>
                </div>

                <div class="export-card">
                    <div class="export-icon">🤝</div>
                    <h3>Volunteer Applications</h3>
                    <p><?php echo number_format($counts['volunteers']); ?> records</p>
                    <?php if ($counts['volunteers'] > 0): ?>
                        <a href="/admin/settings/backup.php?export=volunteers&token=<?php echo urlencode($csrf); ?>" class="btn btn-primary btn-block">
                            Download CSV
                        </a>
                    <?php else: ?>
                        <span class="btn btn-outline btn-block" style="opacity: 0.5; cursor: not-allowed;">No Data</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">Export Information</div>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr><th>Table</th><th>Records</th><th>Format</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($counts as $table => $count): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars(ucfirst($table)); ?></strong></td>
                            <td><?php echo number_format($count); ?></td>
                            <td><span class="badge badge-success">CSV</span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <small class="form-hint" style="margin-top: 0.75rem;">All exports include every column from the database table. CSV files can be opened in Excel, Google Sheets, or any spreadsheet application.</small>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
