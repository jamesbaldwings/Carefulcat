<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
requireAdmin();
$page_title='Adoptions';

$rows=db()->fetchAll("
  SELECT a.id,a.cat_id,a.adopter_name,a.adopter_email,a.status,a.adoption_fee,a.applied_at,a.approved_at,a.denied_at,
         c.name AS cat_name,c.shelter_tag
  FROM adoptions a
  JOIN cats c ON c.id=a.cat_id
  ORDER BY a.applied_at DESC
  LIMIT 200
");
require_once __DIR__.'/../includes/admin-header.php';
?>
<div class="admin-header-actions" style="margin-bottom: 20px;">
  <a class="btn" href="/admin/adoptions/create.php">+ New Application</a>
</div>

<?php if($m=flash_out('success')):?><div class="alert alert-success"><?php echo htmlspecialchars($m ?? '');?></div><?php endif;?>

<div class="admin-card">
  <div class="admin-card-header">
    <h2 class="admin-card-title">📝 Adoptions</h2>
  </div>
  
  <?php if (empty($rows)): ?>
    <div class="admin-empty-state">
      <div class="admin-empty-icon">📝</div>
      <h3>No Adoption Applications</h3>
      <p>There are currently no adoption applications.</p>
      <a href="/admin/adoptions/create.php" class="btn">Add First Application</a>
    </div>
  <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Cat</th>
          <th>Shelter Tag</th>
          <th>Adopter</th>
          <th>Email</th>
          <th>Status</th>
          <th>Applied</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($rows as $r):?>
          <tr>
            <td><?php echo (int)($r['id'] ?? 0);?></td>
            <td><strong><?php echo htmlspecialchars($r['cat_name'] ?? '');?></strong></td>
            <td><?php echo htmlspecialchars($r['shelter_tag'] ?? '');?></td>
            <td><?php echo htmlspecialchars($r['adopter_name'] ?? '');?></td>
            <td><?php echo htmlspecialchars($r['adopter_email'] ?? '');?></td>
            <td>
              <span class="badge badge-<?php echo $r['status']==='approved'?'success':($r['status']==='denied'?'danger':'warning');?>">
                <?php echo ucfirst($r['status'] ?? '');?>
              </span>
            </td>
            <td><?php echo formatDateTime($r['applied_at'] ?? '');?></td>
            <td class="admin-table-actions">
              <a class="btn btn-sm" href="/admin/adoptions/view.php?id=<?php echo (int)($r['id'] ?? 0);?>">View</a>
              
              <?php if($r['status'] === 'pending'):?>
                <a class="btn btn-sm btn-success" 
                   href="/admin/adoptions/approve.php?id=<?php echo (int)($r['id'] ?? 0);?>"
                   onclick="return confirm('Approve this adoption application?')">
                  ✅ Approve
                </a>
                <a class="btn btn-sm btn-danger" 
                   href="/admin/adoptions/reject.php?id=<?php echo (int)($r['id'] ?? 0);?>"
                   onclick="return confirm('Reject this adoption application?')">
                  ❌ Reject
                </a>
              <?php endif;?>
              
              <a class="btn btn-sm btn-outline" href="/admin/adoptions/edit.php?id=<?php echo (int)($r['id'] ?? 0);?>">Edit</a>
              <a class="btn btn-sm btn-outline" href="/admin/cats/medical.php?cat_id=<?php echo (int)($r['cat_id'] ?? 0);?>">Medical</a>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php require_once __DIR__.'/../includes/admin-footer.php'; ?>
