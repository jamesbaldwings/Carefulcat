<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$id = isset($_GET['id']) ? sanitize($_GET['id']) : '';

if (empty($id)) {
    flash('error', 'Adoption application not found.');
    redirect('/admin/adoptions/index.php');
}

// Get adoption application with cat details
$sql = "SELECT a.*, c.name as cat_name, c.hero_photo as cat_photo, c.age as cat_age, c.sex as cat_sex, c.species as cat_species
        FROM adoptions a
        LEFT JOIN cats c ON a.cat_id = c.id
        WHERE a.id = ?";
$adoption = db()->fetchOne($sql, [$id]);

if (!$adoption) {
    flash('error', 'Adoption application not found.');
    redirect('/admin/adoptions/index.php');
}

$pageTitle = 'View Adoption Application - ' . $adoption['adopter_name'];
require_once __DIR__ . '/../includes/admin-header.php';
?>

<div class="admin-header">
    <h1>📋 Adoption Application Details</h1>
    <div>
        <a href="/admin/adoptions/index.php" class="btn btn-outline">← Back to Applications</a>
    </div>
</div>

<?php if ($m = flash_out('success')): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($m); ?></div>
<?php endif; ?>

<?php if ($m = flash_out('error')): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($m); ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="admin-card-title">Application for <?php echo e($adoption['cat_name']); ?></h2>
                <p style="margin: 4px 0 0 0; color: #666;">
                    Submitted: <?php echo date('M d, Y g:i A', strtotime($adoption['applied_at'])); ?>
                </p>
            </div>
            <div>
                <?php if ($adoption['status'] === 'pending'): ?>
                    <span class="badge" style="background-color: #f59e0b; color: white; padding: 6px 16px; font-size: 14px;">Pending</span>
                <?php elseif ($adoption['status'] === 'approved'): ?>
                    <span class="badge" style="background-color: #10b981; color: white; padding: 6px 16px; font-size: 14px;">Approved</span>
                <?php elseif ($adoption['status'] === 'rejected'): ?>
                    <span class="badge" style="background-color: #ef4444; color: white; padding: 6px 16px; font-size: 14px;">Rejected</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="admin-card-body">
        <!-- Cat Information -->
        <div class="info-section">
            <h3 class="section-title">🐱 Cat Information</h3>
            <div class="card" style="background-color: #f0f9ff; border: 2px solid var(--primary-color);">
                <div class="card-content">
                    <div style="display: grid; grid-template-columns: 120px 1fr; gap: 1.5rem; align-items: center;">
                        <?php if ($adoption['cat_photo']): ?>
                        <img src="<?php echo e($adoption['cat_photo']); ?>" alt="<?php echo e($adoption['cat_name']); ?>" 
                             style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px;">
                        <?php endif; ?>
                        <div>
                            <h3 style="margin: 0 0 0.5rem 0; color: var(--primary-color); font-size: 1.3rem;">
                                <?php echo e($adoption['cat_name']); ?>
                            </h3>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <span class="badge"><?php echo e($adoption['cat_age']); ?></span>
                                <span class="badge"><?php echo e($adoption['cat_sex'] === 'M' ? 'Male' : 'Female'); ?></span>
                                <span class="badge"><?php echo e($adoption['cat_species']); ?></span>
                                <?php if ($adoption['adoption_fee']): ?>
                                <span class="badge" style="background-color: var(--primary-color); color: white;">
                                    Fee: $<?php echo e($adoption['adoption_fee']); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Section 1: Personal Information -->
        <div class="info-section">
            <h3 class="section-title">🧾 1. Personal Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Full Name:</span>
                    <span class="info-value"><?php echo e($adoption['adopter_name']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Age 18+:</span>
                    <span class="info-value">
                        <?php echo $adoption['is_18_or_older'] ? '✅ Yes' : '❌ No'; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Address:</span>
                    <span class="info-value">
                        <?php echo e($adoption['address_city']); ?>, <?php echo e($adoption['address_state']); ?> <?php echo e($adoption['address_zip']); ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">
                        <a href="mailto:<?php echo e($adoption['adopter_email']); ?>"><?php echo e($adoption['adopter_email']); ?></a>
                    </span>
                </div>
                <?php if ($adoption['phone_home']): ?>
                <div class="info-item">
                    <span class="info-label">Home Phone:</span>
                    <span class="info-value"><?php echo e($adoption['phone_home']); ?></span>
                </div>
                <?php endif; ?>
                <?php if ($adoption['phone_cell']): ?>
                <div class="info-item">
                    <span class="info-label">Cell Phone:</span>
                    <span class="info-value"><?php echo e($adoption['phone_cell']); ?></span>
                </div>
                <?php endif; ?>
                <?php if ($adoption['phone_work']): ?>
                <div class="info-item">
                    <span class="info-label">Work Phone:</span>
                    <span class="info-value"><?php echo e($adoption['phone_work']); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Section 2: Household Information -->
        <div class="info-section">
            <h3 class="section-title">🏠 2. Household Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Residence Type:</span>
                    <span class="info-value"><?php echo e(ucfirst(str_replace('_', ' ', $adoption['residence_type']))); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Own or Rent:</span>
                    <span class="info-value"><?php echo e(ucfirst($adoption['residence_ownership'])); ?></span>
                </div>
                <?php if ($adoption['residence_ownership'] === 'rent'): ?>
                <div class="info-item">
                    <span class="info-label">Landlord Verified:</span>
                    <span class="info-value">
                        <?php echo $adoption['landlord_verified'] ? '✅ Yes' : '❌ No'; ?>
                    </span>
                </div>
                <?php endif; ?>
                <div class="info-item">
                    <span class="info-label">Years at Address:</span>
                    <span class="info-value"><?php echo e($adoption['years_at_address']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Allergies in Household:</span>
                    <span class="info-value">
                        <?php echo $adoption['has_allergies'] ? '⚠️ Yes' : '✅ No'; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Number of Children:</span>
                    <span class="info-value"><?php echo e($adoption['num_children']); ?></span>
                </div>
                <?php if ($adoption['children_ages']): ?>
                <div class="info-item">
                    <span class="info-label">Children's Ages:</span>
                    <span class="info-value"><?php echo e($adoption['children_ages']); ?></span>
                </div>
                <?php endif; ?>
                <div class="info-item">
                    <span class="info-label">Home Activity Level:</span>
                    <span class="info-value"><?php echo e(ucfirst($adoption['home_activity_level'])); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Section 3: Current and Past Pets -->
        <div class="info-section">
            <h3 class="section-title">🐾 3. Current and Past Pets</h3>
            <div class="info-grid">
                <div class="info-item full-width">
                    <span class="info-label">Has Current Pets:</span>
                    <span class="info-value">
                        <?php echo $adoption['has_current_pets'] ? 'Yes' : 'No'; ?>
                    </span>
                </div>
                <?php if ($adoption['has_current_pets'] && $adoption['current_pets_details']): ?>
                <div class="info-item full-width">
                    <span class="info-label">Current Pets Details:</span>
                    <div class="info-text-block"><?php echo nl2br(e($adoption['current_pets_details'])); ?></div>
                </div>
                <?php endif; ?>
                <?php if ($adoption['past_pets_details']): ?>
                <div class="info-item full-width">
                    <span class="info-label">Past Pets:</span>
                    <div class="info-text-block"><?php echo nl2br(e($adoption['past_pets_details'])); ?></div>
                </div>
                <?php endif; ?>
                <div class="info-item full-width">
                    <span class="info-label">Surrendered Pet Before:</span>
                    <span class="info-value">
                        <?php echo $adoption['surrendered_pet_before'] ? '⚠️ Yes' : '✅ No'; ?>
                    </span>
                </div>
                <?php if ($adoption['surrendered_pet_before'] && $adoption['surrender_reason']): ?>
                <div class="info-item full-width">
                    <span class="info-label">Surrender Reason:</span>
                    <div class="info-text-block"><?php echo nl2br(e($adoption['surrender_reason'])); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Section 4: Pet Care and Lifestyle -->
        <div class="info-section">
            <h3 class="section-title">🧡 4. Pet Care and Lifestyle</h3>
            <div class="info-grid">
                <div class="info-item full-width">
                    <span class="info-label">Reason for Adoption:</span>
                    <div class="info-text-block"><?php echo nl2br(e($adoption['adoption_reason'])); ?></div>
                </div>
                <div class="info-item">
                    <span class="info-label">Cat Location:</span>
                    <span class="info-value"><?php echo e(ucfirst(str_replace('_', ' ', $adoption['cat_location']))); ?></span>
                </div>
                <div class="info-item full-width">
                    <span class="info-label">Scratching/Litter Box Plan:</span>
                    <div class="info-text-block"><?php echo nl2br(e($adoption['scratching_plan'])); ?></div>
                </div>
                <div class="info-item">
                    <span class="info-label">Prepared for Costs:</span>
                    <span class="info-value">
                        <?php echo $adoption['prepared_for_costs'] ? '✅ Yes' : '❌ No'; ?>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Section 5: Veterinary Information -->
        <div class="info-section">
            <h3 class="section-title">🩺 5. Veterinary Information</h3>
            <div class="info-grid">
                <?php if ($adoption['vet_name']): ?>
                <div class="info-item">
                    <span class="info-label">Veterinarian Name:</span>
                    <span class="info-value"><?php echo e($adoption['vet_name']); ?></span>
                </div>
                <?php endif; ?>
                <?php if ($adoption['vet_clinic']): ?>
                <div class="info-item">
                    <span class="info-label">Clinic Name:</span>
                    <span class="info-value"><?php echo e($adoption['vet_clinic']); ?></span>
                </div>
                <?php endif; ?>
                <?php if ($adoption['vet_phone']): ?>
                <div class="info-item">
                    <span class="info-label">Clinic Phone:</span>
                    <span class="info-value"><?php echo e($adoption['vet_phone']); ?></span>
                </div>
                <?php endif; ?>
                <div class="info-item">
                    <span class="info-label">Needs Vet Help:</span>
                    <span class="info-value">
                        <?php echo $adoption['needs_vet_help'] ? 'Yes' : 'No'; ?>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Section 6: Preferences & Agreement -->
        <div class="info-section">
            <h3 class="section-title">🐱 6. Preferences & Agreement</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Open to Bonded Pair:</span>
                    <span class="info-value">
                        <?php echo $adoption['open_to_bonded_pair'] ? '✅ Yes' : 'No'; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Open to Special Needs:</span>
                    <span class="info-value">
                        <?php echo $adoption['open_to_special_needs'] ? '✅ Yes' : 'No'; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Adopted Before:</span>
                    <span class="info-value">
                        <?php echo $adoption['adopted_before'] ? 'Yes' : 'No'; ?>
                    </span>
                </div>
            </div>
            
            <div class="card" style="background-color: #fef3c7; border: 1px solid #f59e0b; padding: 1.5rem; margin-top: 1.5rem;">
                <h4 style="margin: 0 0 1rem 0; color: #92400e;">Agreement Terms</h4>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Info is True:</span>
                        <span class="info-value">
                            <?php echo $adoption['agree_info_true'] ? '✅ Agreed' : '❌ Not Agreed'; ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Provide Vet Care:</span>
                        <span class="info-value">
                            <?php echo $adoption['agree_vet_care'] ? '✅ Agreed' : '❌ Not Agreed'; ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">No Declawing:</span>
                        <span class="info-value">
                            <?php echo $adoption['agree_no_declaw'] ? '✅ Agreed' : '❌ Not Agreed'; ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Return if Unable:</span>
                        <span class="info-value">
                            <?php echo $adoption['agree_return_if_unable'] ? '✅ Agreed' : '❌ Not Agreed'; ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Signature:</span>
                        <span class="info-value" style="font-style: italic;"><?php echo e($adoption['signature']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date:</span>
                        <span class="info-value"><?php echo date('M d, Y', strtotime($adoption['signature_date'])); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="form-divider"></div>
        
        <div class="form-actions">
            <?php if ($adoption['status'] === 'pending'): ?>
            <form method="POST" action="/admin/adoptions/approve.php" style="display: inline;" 
                  onsubmit="return confirm('Are you sure you want to approve this adoption application?');">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="id" value="<?php echo e($adoption['id']); ?>">
                <button type="submit" class="btn btn-success">
                    ✅ Approve Application
                </button>
            </form>
            
            <form method="POST" action="/admin/adoptions/reject.php" style="display: inline;" 
                  onsubmit="return confirm('Are you sure you want to reject this adoption application?');">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="id" value="<?php echo e($adoption['id']); ?>">
                <button type="submit" class="btn btn-danger">
                    ❌ Reject Application
                </button>
            </form>
            <?php endif; ?>
            
            <a href="/admin/adoptions/index.php" class="btn btn-outline">
                ← Back to Applications
            </a>
        </div>
    </div>
</div>

<style>
.info-section {
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e5e7eb;
}

.info-section:last-of-type {
    border-bottom: none;
}

.section-title {
    font-size: 1.2rem;
    color: var(--primary-color);
    margin: 0 0 1.5rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--primary-color);
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-label {
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.info-value {
    color: #1f2937;
    font-size: 15px;
}

.info-text-block {
    background-color: #f9fafb;
    padding: 1rem;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    color: #1f2937;
    line-height: 1.6;
    white-space: pre-wrap;
}

.badge {
    display: inline-block;
    padding: 4px 12px;
    background-color: #e5e7eb;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 500;
}

.form-divider {
    height: 1px;
    background-color: #e5e7eb;
    margin: 2rem 0;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
