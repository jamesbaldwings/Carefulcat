<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Get cat ID from URL
$catId = isset($_GET['cat']) ? sanitize($_GET['cat']) : '';

// Get cat details if cat ID provided
$cat = null;
if ($catId) {
    $cat = db()->fetchOne("SELECT * FROM cats WHERE id = ? AND status = 'adoptable'", [$catId]);
    if (!$cat) {
        flash('error', 'Cat not found or not available for adoption.');
        redirect('/adoptions.php');
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate CSRF token
        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            throw new Exception('Invalid security token. Please try again.');
        }
        
        // Get and validate form data
        $selectedCatId = sanitize($_POST['cat_id'] ?? '');
        
        // Section 1: Personal Information
        $fullName = sanitize($_POST['full_name'] ?? '');
        $is18OrOlder = isset($_POST['is_18_or_older']) ? 1 : 0;
        $city = sanitize($_POST['city'] ?? '');
        $state = sanitize($_POST['state'] ?? '');
        $zip = sanitize($_POST['zip'] ?? '');
        $phoneHome = sanitize($_POST['phone_home'] ?? '');
        $phoneCell = sanitize($_POST['phone_cell'] ?? '');
        $phoneWork = sanitize($_POST['phone_work'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        
        // Section 2: Household Information
        $residenceType = sanitize($_POST['residence_type'] ?? '');
        $residenceOwnership = sanitize($_POST['residence_ownership'] ?? '');
        $landlordVerified = isset($_POST['landlord_verified']) ? 1 : 0;
        $yearsAtAddress = sanitize($_POST['years_at_address'] ?? '');
        $hasAllergies = isset($_POST['has_allergies']) ? 1 : 0;
        $numChildren = (int)($_POST['num_children'] ?? 0);
        $childrenAges = sanitize($_POST['children_ages'] ?? '');
        $homeActivityLevel = sanitize($_POST['home_activity_level'] ?? '');
        
        // Section 3: Current and Past Pets
        $hasCurrentPets = isset($_POST['has_current_pets']) ? 1 : 0;
        $currentPetsDetails = sanitize($_POST['current_pets_details'] ?? '');
        $pastPetsDetails = sanitize($_POST['past_pets_details'] ?? '');
        $surrenderedPetBefore = isset($_POST['surrendered_pet_before']) ? 1 : 0;
        $surrenderReason = sanitize($_POST['surrender_reason'] ?? '');
        
        // Section 4: Pet Care and Lifestyle
        $adoptionReason = sanitize($_POST['adoption_reason'] ?? '');
        $catLocation = sanitize($_POST['cat_location'] ?? '');
        $scratchingPlan = sanitize($_POST['scratching_plan'] ?? '');
        $preparedForCosts = isset($_POST['prepared_for_costs']) ? 1 : 0;
        
        // Section 5: Veterinary Information
        $vetName = sanitize($_POST['vet_name'] ?? '');
        $vetClinic = sanitize($_POST['vet_clinic'] ?? '');
        $vetPhone = sanitize($_POST['vet_phone'] ?? '');
        $needsVetHelp = isset($_POST['needs_vet_help']) ? 1 : 0;
        
        // Section 6: Adoption Preferences & Agreement
        $openToBondedPair = isset($_POST['open_to_bonded_pair']) ? 1 : 0;
        $openToSpecialNeeds = isset($_POST['open_to_special_needs']) ? 1 : 0;
        $adoptedBefore = isset($_POST['adopted_before']) ? 1 : 0;
        $signature = sanitize($_POST['signature'] ?? '');
        $signatureDate = sanitize($_POST['signature_date'] ?? '');
        $agreeInfoTrue = isset($_POST['agree_info_true']) ? 1 : 0;
        $agreeVetCare = isset($_POST['agree_vet_care']) ? 1 : 0;
        $agreeNoDeclaw = isset($_POST['agree_no_declaw']) ? 1 : 0;
        $agreeReturnIfUnable = isset($_POST['agree_return_if_unable']) ? 1 : 0;
        
        // Validation
        if (empty($selectedCatId)) {
            throw new Exception('Please select a cat to adopt.');
        }
        
        if (empty($fullName)) {
            throw new Exception('Please enter your full name.');
        }
        
        if (!$is18OrOlder) {
            throw new Exception('You must be 18 years or older to adopt.');
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please enter a valid email address.');
        }
        
        if (empty($phoneCell) && empty($phoneHome)) {
            throw new Exception('Please provide at least one phone number.');
        }
        
        if (empty($city) || empty($state) || empty($zip)) {
            throw new Exception('Please provide your complete address (city, state, ZIP).');
        }
        
        if ($residenceOwnership === 'rent' && !$landlordVerified) {
            throw new Exception('If you rent, you must verify with your landlord that you can own a cat.');
        }
        
        if (!$agreeInfoTrue || !$agreeVetCare || !$agreeNoDeclaw || !$agreeReturnIfUnable) {
            throw new Exception('You must agree to all terms and conditions.');
        }
        
        if (empty($signature)) {
            throw new Exception('Please provide your signature.');
        }
        
        // Verify cat is still available
        $selectedCat = db()->fetchOne("SELECT * FROM cats WHERE id = ? AND status = 'adoptable'", [$selectedCatId]);
        if (!$selectedCat) {
            throw new Exception('Sorry, this cat is no longer available for adoption.');
        }
        
        // Generate unique ID for adoption application
        $adoptionId = uniqid('', true);
        
        // Insert adoption application
        $sql = "INSERT INTO adoptions (
            id, cat_id, adopter_name, adopter_email, adopter_phone, status, adoption_fee,
            is_18_or_older, address_city, address_state, address_zip,
            phone_home, phone_cell, phone_work,
            residence_type, residence_ownership, landlord_verified, years_at_address,
            has_allergies, num_children, children_ages, home_activity_level,
            has_current_pets, current_pets_details, past_pets_details,
            surrendered_pet_before, surrender_reason,
            adoption_reason, cat_location, scratching_plan, prepared_for_costs,
            vet_name, vet_clinic, vet_phone, needs_vet_help,
            open_to_bonded_pair, open_to_special_needs, adopted_before,
            signature, signature_date,
            agree_info_true, agree_vet_care, agree_no_declaw, agree_return_if_unable,
            applied_at
        ) VALUES (
            ?, ?, ?, ?, ?, 'pending', ?,
            ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?,
            ?, ?, ?, ?,
            NOW()
        )";
        
        db()->execute($sql, [
            $adoptionId, $selectedCatId, $fullName, $email, $phoneCell ?: $phoneHome, $selectedCat['fee'],
            $is18OrOlder, $city, $state, $zip,
            $phoneHome, $phoneCell, $phoneWork,
            $residenceType, $residenceOwnership, $landlordVerified, $yearsAtAddress,
            $hasAllergies, $numChildren, $childrenAges, $homeActivityLevel,
            $hasCurrentPets, $currentPetsDetails, $pastPetsDetails,
            $surrenderedPetBefore, $surrenderReason,
            $adoptionReason, $catLocation, $scratchingPlan, $preparedForCosts,
            $vetName, $vetClinic, $vetPhone, $needsVetHelp,
            $openToBondedPair, $openToSpecialNeeds, $adoptedBefore,
            $signature, $signatureDate,
            $agreeInfoTrue, $agreeVetCare, $agreeNoDeclaw, $agreeReturnIfUnable
        ]);
        
        // Success message
        flash('success', 'Your adoption application for ' . htmlspecialchars($selectedCat['name']) . ' has been submitted! We will review your application and contact you within 2-3 business days.');
        redirect('/adoptions.php');
        
    } catch (Exception $e) {
        flash('error', $e->getMessage());
    }
}

$pageTitle = $cat ? 'Adopt ' . e($cat['name']) . ' - Cat Adoption Application' : 'Cat Adoption Application';
$metaDescription = 'Submit a comprehensive adoption application for a cat at Careful Cat Rescue in Murfreesboro, TN. Servals, savannahs, bengals, caracals, and more.';
$metaKeywords = 'cat adoption application, adopt cat, serval adoption form, savannah cat adoption, bengal cat adoption Murfreesboro TN';

require_once __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="/admin/css/admin-forms.css">

<section class="section">
    <div class="container" style="max-width: 900px;">
        <a href="<?php echo $cat ? '/cat-detail.php?id=' . e($cat['id']) : '/adoptions.php'; ?>" style="display: inline-block; margin-bottom: 1rem; color: var(--text-light);">
            &larr; Back to <?php echo $cat ? e($cat['name']) : 'Adoptable Cats'; ?>
        </a>
        
        <?php if ($m = flash_out('success')): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($m); ?></div>
        <?php endif; ?>
        
        <?php if ($m = flash_out('error')): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($m); ?></div>
        <?php endif; ?>
        
        <div class="admin-card">
            <div class="admin-card-header">
                <h1 class="admin-card-title">Cat Adoption Application</h1>
                <p style="margin: 8px 0 0 0; color: #666; font-size: 14px;">
                    <?php if ($cat): ?>
                        Apply to adopt <?php echo e($cat['name']); ?>
                    <?php else: ?>
                        Please complete all sections to help us find the perfect match
                    <?php endif; ?>
                </p>
            </div>
            
            <div class="admin-card-body">
                <?php if ($cat): ?>
                <!-- Selected Cat Info -->
                <div class="card mb-4" style="background-color: #f0f9ff; border: 2px solid var(--primary-color);">
                    <div class="card-content">
                        <div style="display: grid; grid-template-columns: 120px 1fr; gap: 1.5rem; align-items: center;">
                            <img src="<?php echo e(cat_photo($cat)); ?>" alt="<?php echo e($cat['name']); ?>" style="width: 120px; height: 120px; object-fit: cover; border-radius: var(--border-radius);">
                            <div>
                                <h3 style="margin: 0 0 0.5rem 0; color: var(--primary-color);"><?php echo e($cat['name']); ?></h3>
                                <div class="cat-badges">
                                    <span class="badge"><?php echo e($cat['age']); ?></span>
                                    <span class="badge"><?php echo e($cat['sex'] === 'M' ? 'Male' : 'Female'); ?></span>
                                    <span class="badge"><?php echo e($cat['species']); ?></span>
                                    <?php if ($cat['fee']): ?>
                                    <span class="badge" style="background-color: var(--primary-color); color: white;">Adoption Fee: $<?php echo e($cat['fee']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <p style="margin: 0.5rem 0 0 0; color: #666; font-size: 14px;">
                                    <?php echo e(truncate($cat['bio'], 150)); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="" class="admin-form" id="adoptionForm">
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="cat_id" value="<?php echo $cat ? e($cat['id']) : ''; ?>">
                    
                    <?php if (!$cat): ?>
                    <!-- Cat Selection (if no cat pre-selected) -->
                    <div class="form-group">
                        <label class="form-label required">Select Cat to Adopt</label>
                        <select name="cat_id" class="form-control" required>
                            <option value="">-- Choose a cat --</option>
                            <?php
                            $availableCats = db()->fetchAll("SELECT id, name, age, sex, species, fee FROM cats WHERE status = 'adoptable' ORDER BY name");
                            foreach ($availableCats as $availableCat):
                            ?>
                            <option value="<?php echo e($availableCat['id']); ?>">
                                <?php echo e($availableCat['name']); ?> - 
                                <?php echo e($availableCat['age']); ?>, 
                                <?php echo e($availableCat['sex'] === 'M' ? 'Male' : 'Female'); ?> 
                                <?php echo e($availableCat['species']); ?>
                                <?php if ($availableCat['fee']): ?> - $<?php echo e($availableCat['fee']); ?><?php endif; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    
                    <!-- SECTION 1: Personal Information -->
                    <div class="form-section">
                        <h2 class="form-section-title">🧾 1. Personal Information</h2>
                        
                        <div class="form-group">
                            <label class="form-label required">Full Name</label>
                            <input type="text" name="full_name" class="form-control" 
                                   placeholder="First and Last Name" required
                                   value="<?php echo isset($_POST['full_name']) ? e($_POST['full_name']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="is_18_or_older" value="1" required
                                       <?php echo isset($_POST['is_18_or_older']) ? 'checked' : ''; ?>>
                                <span>I am 18 years of age or older</span>
                            </label>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">City</label>
                                <input type="text" name="city" class="form-control" required
                                       value="<?php echo isset($_POST['city']) ? e($_POST['city']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label required">State</label>
                                <input type="text" name="state" class="form-control" required
                                       value="<?php echo isset($_POST['state']) ? e($_POST['state']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label required">ZIP Code</label>
                                <input type="text" name="zip" class="form-control" required
                                       value="<?php echo isset($_POST['zip']) ? e($_POST['zip']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Home Phone</label>
                                <input type="tel" name="phone_home" class="form-control" 
                                       placeholder="(555) 123-4567"
                                       value="<?php echo isset($_POST['phone_home']) ? e($_POST['phone_home']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label required">Cell Phone</label>
                                <input type="tel" name="phone_cell" class="form-control" 
                                       placeholder="(555) 123-4567" required
                                       value="<?php echo isset($_POST['phone_cell']) ? e($_POST['phone_cell']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Work Phone</label>
                                <input type="tel" name="phone_work" class="form-control" 
                                       placeholder="(555) 123-4567"
                                       value="<?php echo isset($_POST['phone_work']) ? e($_POST['phone_work']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Email Address</label>
                            <input type="email" name="email" class="form-control" 
                                   placeholder="your.email@example.com" required
                                   value="<?php echo isset($_POST['email']) ? e($_POST['email']) : ''; ?>">
                        </div>
                    </div>
                    
                    <!-- SECTION 2: Household Information -->
                    <div class="form-section">
                        <h2 class="form-section-title">🏠 2. Household Information</h2>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Type of Residence</label>
                                <select name="residence_type" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    <option value="house">House</option>
                                    <option value="apartment">Apartment</option>
                                    <option value="condo">Condo</option>
                                    <option value="townhouse">Townhouse</option>
                                    <option value="mobile_home">Mobile Home</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label required">Do you own or rent?</label>
                                <select name="residence_ownership" class="form-control" required id="residenceOwnership">
                                    <option value="">-- Select --</option>
                                    <option value="own">Own</option>
                                    <option value="rent">Rent</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group" id="landlordVerificationGroup" style="display: none;">
                            <label class="checkbox-label">
                                <input type="checkbox" name="landlord_verified" value="1" id="landlordVerified">
                                <span>I have verified with my landlord that I am permitted to own a cat</span>
                            </label>
                            <small class="form-help" style="color: #dc2626; margin-top: 0.5rem; display: block;">
                                If you rent, you must confirm landlord approval before proceeding
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">How long have you lived at your current address?</label>
                            <input type="text" name="years_at_address" class="form-control" 
                                   placeholder="e.g., 2 years, 6 months" required
                                   value="<?php echo isset($_POST['years_at_address']) ? e($_POST['years_at_address']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="has_allergies" value="1"
                                       <?php echo isset($_POST['has_allergies']) ? 'checked' : ''; ?>>
                                <span>Is anyone in your household allergic to cats?</span>
                            </label>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Number of Children</label>
                                <input type="number" name="num_children" class="form-control" min="0" value="0"
                                       value="<?php echo isset($_POST['num_children']) ? e($_POST['num_children']) : '0'; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Children's Ages</label>
                                <input type="text" name="children_ages" class="form-control" 
                                       placeholder="e.g., 5, 8, 12"
                                       value="<?php echo isset($_POST['children_ages']) ? e($_POST['children_ages']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Typical noise/activity level of your home</label>
                            <select name="home_activity_level" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="quiet">Quiet</option>
                                <option value="moderate">Moderate</option>
                                <option value="active">Active</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- SECTION 3: Current and Past Pets -->
                    <div class="form-section">
                        <h2 class="form-section-title">🐾 3. Current and Past Pets</h2>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="has_current_pets" value="1" id="hasCurrentPets"
                                       <?php echo isset($_POST['has_current_pets']) ? 'checked' : ''; ?>>
                                <span>Do you currently have any pets?</span>
                            </label>
                        </div>
                        
                        <div class="form-group" id="currentPetsGroup" style="display: none;">
                            <label class="form-label">Current Pets Details</label>
                            <textarea name="current_pets_details" class="form-control" rows="4"
                                      placeholder="For each pet, please provide: Species, breed, age, sex, spayed/neutered status, vaccination status, how long owned"><?php echo isset($_POST['current_pets_details']) ? e($_POST['current_pets_details']) : ''; ?></textarea>
                            <small class="form-help">Example: Dog, Golden Retriever, 5 years, Male, Neutered, Up-to-date on vaccines, Owned 3 years</small>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Past Pets</label>
                            <textarea name="past_pets_details" class="form-control" rows="3"
                                      placeholder="Please describe any pets you've had in the past and what happened to them (deceased, rehomed, etc.)"><?php echo isset($_POST['past_pets_details']) ? e($_POST['past_pets_details']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="surrendered_pet_before" value="1" id="surrenderedPet"
                                       <?php echo isset($_POST['surrendered_pet_before']) ? 'checked' : ''; ?>>
                                <span>Have you ever surrendered a pet to a shelter or rescue?</span>
                            </label>
                        </div>
                        
                        <div class="form-group" id="surrenderReasonGroup" style="display: none;">
                            <label class="form-label">If yes, please explain why</label>
                            <textarea name="surrender_reason" class="form-control" rows="3"
                                      placeholder="Please provide details about the circumstances"><?php echo isset($_POST['surrender_reason']) ? e($_POST['surrender_reason']) : ''; ?></textarea>
                        </div>
                    </div>
                    
                    <!-- SECTION 4: Pet Care and Lifestyle -->
                    <div class="form-section">
                        <h2 class="form-section-title">🧡 4. Pet Care and Lifestyle</h2>
                        
                        <div class="form-group">
                            <label class="form-label required">Why do you want to adopt a cat?</label>
                            <textarea name="adoption_reason" class="form-control" rows="4" required
                                      placeholder="Tell us about your motivation for adopting and what you're looking for in a feline companion"><?php echo isset($_POST['adoption_reason']) ? e($_POST['adoption_reason']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Where will the cat stay during the day and at night?</label>
                            <select name="cat_location" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="indoors_only">Indoors only</option>
                                <option value="outdoors_only">Outdoors only</option>
                                <option value="indoor_outdoor">Both indoors and outdoors</option>
                            </select>
                            <small class="form-help">We strongly recommend keeping cats in secure, enriched environments for their safety</small>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">How will you handle scratching or litter box issues?</label>
                            <textarea name="scratching_plan" class="form-control" rows="3" required
                                      placeholder="Describe your plan for providing scratching posts, litter boxes, and addressing any behavioral issues"><?php echo isset($_POST['scratching_plan']) ? e($_POST['scratching_plan']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="prepared_for_costs" value="1" required>
                                <span>I am prepared for the financial responsibility of pet ownership, including food, litter, routine veterinary care, and emergency medical expenses</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- SECTION 5: Veterinary Information -->
                    <div class="form-section">
                        <h2 class="form-section-title">🩺 5. Veterinary Information</h2>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Current or Previous Veterinarian Name</label>
                                <input type="text" name="vet_name" class="form-control" 
                                       placeholder="Dr. Smith"
                                       value="<?php echo isset($_POST['vet_name']) ? e($_POST['vet_name']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Clinic Name</label>
                                <input type="text" name="vet_clinic" class="form-control" 
                                       placeholder="Happy Paws Veterinary Clinic"
                                       value="<?php echo isset($_POST['vet_clinic']) ? e($_POST['vet_clinic']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Clinic Phone Number</label>
                            <input type="tel" name="vet_phone" class="form-control" 
                                   placeholder="(555) 123-4567"
                                   value="<?php echo isset($_POST['vet_phone']) ? e($_POST['vet_phone']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="needs_vet_help" value="1"
                                       <?php echo isset($_POST['needs_vet_help']) ? 'checked' : ''; ?>>
                                <span>I would like help finding a veterinarian in my area</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- SECTION 6: Adoption Preferences & Agreement -->
                    <div class="form-section">
                        <h2 class="form-section-title">🐱 6. Adoption Preferences & Agreement</h2>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="open_to_bonded_pair" value="1"
                                       <?php echo isset($_POST['open_to_bonded_pair']) ? 'checked' : ''; ?>>
                                <span>I am open to adopting a bonded pair (two cats that must stay together)</span>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="open_to_special_needs" value="1"
                                       <?php echo isset($_POST['open_to_special_needs']) ? 'checked' : ''; ?>>
                                <span>I am open to adopting a special-needs cat (medical conditions, disabilities, etc.)</span>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="adopted_before" value="1"
                                       <?php echo isset($_POST['adopted_before']) ? 'checked' : ''; ?>>
                                <span>I have adopted from Careful Cat Rescue before</span>
                            </label>
                        </div>
                        
                        <div class="form-divider"></div>
                        
                        <h3 style="margin: 1.5rem 0 1rem 0; font-size: 1.1rem; color: var(--primary-color);">Agreement & Signature</h3>
                        
                        <div class="card" style="background-color: #fef3c7; border: 1px solid #f59e0b; padding: 1.5rem; margin-bottom: 1.5rem;">
                            <p style="margin: 0 0 1rem 0; font-weight: 600; color: #92400e;">
                                Please read and agree to the following terms:
                            </p>
                            
                            <div class="form-group" style="margin-bottom: 0.75rem;">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="agree_info_true" value="1" required>
                                    <span>I confirm that all information provided in this application is true and complete</span>
                                </label>
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 0.75rem;">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="agree_vet_care" value="1" required>
                                    <span>I agree to provide proper veterinary care, including annual checkups and necessary medical treatment</span>
                                </label>
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 0.75rem;">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="agree_no_declaw" value="1" required>
                                    <span>I will NOT declaw the cat under any circumstances</span>
                                </label>
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="agree_return_if_unable" value="1" required>
                                    <span>I agree to return the cat to Careful Cat Rescue if I am unable to keep it for any reason</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Signature (Type your full name)</label>
                                <input type="text" name="signature" class="form-control" required
                                       placeholder="Your Full Name"
                                       value="<?php echo isset($_POST['signature']) ? e($_POST['signature']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label required">Date</label>
                                <input type="date" name="signature_date" class="form-control" required
                                       value="<?php echo isset($_POST['signature_date']) ? e($_POST['signature_date']) : date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-divider"></div>
                    
                    <!-- Next Steps Info -->
                    <div class="card" style="background-color: #f0f9ff; border: 1px solid #3b82f6; padding: 1.5rem; margin-bottom: 1.5rem;">
                        <h3 style="margin: 0 0 0.5rem 0; color: #1e40af;">📋 What Happens Next?</h3>
                        <ol style="margin: 0.5rem 0 0 1.5rem; line-height: 1.8; color: #1e3a8a;">
                            <li><strong>Application Review:</strong> Our team will carefully review your application (2-3 business days)</li>
                            <li><strong>Contact:</strong> We'll reach out to schedule a meet-and-greet with <?php echo $cat ? e($cat['name']) : 'your selected cat'; ?></li>
                            <li><strong>Home Visit:</strong> We may conduct a brief home visit or virtual tour</li>
                            <li><strong>Final Approval:</strong> Once approved, we'll complete the adoption paperwork</li>
                            <li><strong>Welcome Home:</strong> Take your new family member home!</li>
                        </ol>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <span style="font-size: 1.2rem;">📝</span>
                            Submit Application
                        </button>
                        <a href="<?php echo $cat ? '/cat-detail.php?id=' . e($cat['id']) : '/adoptions.php'; ?>" class="btn btn-outline">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.form-section {
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid #e5e7eb;
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section-title {
    font-size: 1.3rem;
    color: var(--primary-color);
    margin: 0 0 1.5rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--primary-color);
}

.form-divider {
    height: 1px;
    background-color: #e5e7eb;
    margin: 2rem 0;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    cursor: pointer;
    font-size: 15px;
    line-height: 1.6;
}

.checkbox-label input[type="checkbox"] {
    margin-top: 0.25rem;
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.checkbox-label span {
    flex: 1;
}

.btn-lg {
    padding: 14px 32px;
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

.cat-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.badge {
    display: inline-block;
    padding: 4px 12px;
    background-color: #e5e7eb;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 500;
}
</style>

<script>
// Show/hide landlord verification based on residence ownership
document.getElementById('residenceOwnership').addEventListener('change', function() {
    const landlordGroup = document.getElementById('landlordVerificationGroup');
    const landlordCheckbox = document.getElementById('landlordVerified');
    
    if (this.value === 'rent') {
        landlordGroup.style.display = 'block';
        landlordCheckbox.required = true;
    } else {
        landlordGroup.style.display = 'none';
        landlordCheckbox.required = false;
        landlordCheckbox.checked = false;
    }
});

// Show/hide current pets details
document.getElementById('hasCurrentPets').addEventListener('change', function() {
    const currentPetsGroup = document.getElementById('currentPetsGroup');
    currentPetsGroup.style.display = this.checked ? 'block' : 'none';
});

// Show/hide surrender reason
document.getElementById('surrenderedPet').addEventListener('change', function() {
    const surrenderGroup = document.getElementById('surrenderReasonGroup');
    surrenderGroup.style.display = this.checked ? 'block' : 'none';
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check if renting
    if (document.getElementById('residenceOwnership').value === 'rent') {
        document.getElementById('landlordVerificationGroup').style.display = 'block';
    }
    
    // Check if has current pets
    if (document.getElementById('hasCurrentPets').checked) {
        document.getElementById('currentPetsGroup').style.display = 'block';
    }
    
    // Check if surrendered pet
    if (document.getElementById('surrenderedPet').checked) {
        document.getElementById('surrenderReasonGroup').style.display = 'block';
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
