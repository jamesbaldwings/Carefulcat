<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$cat_id=$_GET['cat_id'] ?? '';
$cat=db()->fetchOne("SELECT * FROM cats WHERE id=?",[$cat_id]);
if(!$cat){ redirect('/admin/cats/index.php'); }
$page_title='Medical Record — '.($cat['name'] ?? '');

// latest adoption (no fee output)
$adopt=db()->fetchOne("SELECT status,applied_at,approved_at,denied_at,adopter_name,adopter_email,adopter_phone FROM adoptions WHERE cat_id=? ORDER BY applied_at DESC LIMIT 1",[$cat_id]);
// all treatments
$tx=db()->fetchAll("SELECT treatment_type,date_administered,administered_by,notes FROM cat_treatments WHERE cat_id=? ORDER BY date_administered ASC, id ASC",[$cat_id]);

require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-card">
  <div class="admin-card-header">
    <h2 class="admin-card-title">📄 Veterinary Medical Record — <?php echo htmlspecialchars($cat['name'] ?? '');?></h2>
    <button class="btn btn-outline" onclick="window.print()">🖨️ Print</button>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <tbody>
        <tr><th style="width:220px;">Shelter Tag</th><td><?php echo htmlspecialchars($cat['shelter_tag'] ?? '');?></td></tr>
        <tr><th>Sex</th><td><?php echo htmlspecialchars($cat['sex']??''); ?></td></tr>
        <tr><th>DOB</th><td><?php echo ($cat['dob'] ?? null)?htmlspecialchars($cat['dob'] ?? ''):'—';?></td></tr>
        <tr><th>Intake Date</th><td><?php echo ($cat['intake_date'] ?? null)?htmlspecialchars($cat['intake_date'] ?? ''):'—';?></td></tr>
        <tr><th>Notes</th><td><?php echo htmlspecialchars($cat['notes']??''); ?></td></tr>
      </tbody>
    </table>
  </div>

  <h3 style="margin-top:1rem;">Adoption (Summary)</h3>
  <?php if($adopt): ?>
  <div class="table-responsive">
    <table class="admin-table">
      <tbody>
        <tr><th>Status</th><td><?php echo ucfirst($adopt['status'] ?? '');?></td></tr>
        <tr><th>Applied</th><td><?php echo formatDateTime($adopt['applied_at'] ?? '');?></td></tr>
        <tr><th>Approved</th><td><?php echo ($adopt['approved_at'] ?? null)?formatDateTime($adopt['approved_at'] ?? ''):'—';?></td></tr>
        <tr><th>Denied</th><td><?php echo ($adopt['denied_at'] ?? null)?formatDateTime($adopt['denied_at'] ?? ''):'—';?></td></tr>
        <tr><th>Adopter</th><td><?php echo htmlspecialchars($adopt['adopter_name'] ?? '').' — '.htmlspecialchars($adopt['adopter_email'] ?? '');?></td></tr>
        <tr><th>Phone</th><td><?php echo htmlspecialchars($adopt['adopter_phone']??''); ?></td></tr>
      </tbody>
    </table>
  </div>
  <?php else: ?>
    <p class="text-muted">No adoption record.</p>
  <?php endif; ?>

  <h3 style="margin-top:1rem;">Treatments</h3>
  <?php if(empty($tx)): ?>
    <p class="text-muted">No recorded treatments.</p>
  <?php else: ?>
    <div class="table-responsive">
      <table class="admin-table">
        <thead><tr><th>Date</th><th>Treatment</th><th>Administered By</th><th>Notes</th></tr></thead>
        <tbody>
          <?php foreach($tx as $t): ?>
            <tr>
              <td><?php echo htmlspecialchars($t['date_administered'] ?? '');?></td>
              <td><?php echo ucwords(str_replace('_',' ',$t['treatment_type']));?></td>
              <td><?php echo htmlspecialchars($t['administered_by']??''); ?></td>
              <td><?php echo htmlspecialchars($t['notes']??''); ?></td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

  <p style="margin-top:1rem;">
    <a class="btn" href="/admin/cats/treatments.php?cat_id=<?php echo htmlspecialchars($cat_id ?? '');?>">➕ Add Treatment</a>
    <a class="btn btn-outline" href="/admin/cats/index.php">Back to Cats</a>
  </p>
</div>

<style>
@media print {
  .admin-sidebar, .admin-header, .admin-footer, .btn, .admin-header-actions { display:none !important; }
  .admin-content { padding:0; }
  body { background:#fff; }
}
</style>

<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
