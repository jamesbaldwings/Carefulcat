<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Check if page is visible
if (!isPageVisible('adoptions')) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>Page Not Found</h1>';
    exit;
}

$pageTitle = 'Adoptable Exotic Cats - Find Your Perfect Exotic Feline';
$metaDescription = 'Browse our adorable small exotic cats looking for forever homes. Find your perfect exotic feline companion - servals, savannahs, bengals, caracals, and more.';
$metaKeywords = 'adopt exotic cat, exotic cat adoption, serval adoption, savannah cat adoption, bengal cat adoption, caracal adoption, exotic feline rescue, Murfreesboro TN';

// Get filter parameters
$status = isset($_GET['status']) ? sanitize($_GET['status']) : 'adoptable';
$age = isset($_GET['age']) ? sanitize($_GET['age']) : '';
$sex = isset($_GET['sex']) ? sanitize($_GET['sex']) : '';

// Build query
$sql = "SELECT * FROM cats WHERE status = ?";
$params = [$status];

if ($age) {
    $sql .= " AND age = ?";
    $params[] = $age;
}

if ($sex) {
    $sql .= " AND sex = ?";
    $params[] = $sex;
}

$sql .= " ORDER BY intake_date DESC";

$cats = db()->fetchAll($sql, $params);

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1>Adoptable Exotic Cats</h1>
        <p>Meet our wonderful small exotic cats who are looking for their forever homes. Each one has a unique personality and is ready to bring joy to your life.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-content">
                <form method="GET" action="" id="filterForm">
                    <div class="grid grid-3">
                        <div class="form-group">
                            <label class="form-label">Age</label>
                            <select name="age" class="form-control" onchange="document.getElementById('filterForm').submit()">
                                <option value="">All Ages</option>
                                <option value="Kitten" <?php echo $age === 'Kitten' ? 'selected' : ''; ?>>Kitten</option>
                                <option value="Young Adult" <?php echo $age === 'Young Adult' ? 'selected' : ''; ?>>Young Adult</option>
                                <option value="Adult" <?php echo $age === 'Adult' ? 'selected' : ''; ?>>Adult</option>
                                <option value="Senior" <?php echo $age === 'Senior' ? 'selected' : ''; ?>>Senior</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Sex</label>
                            <select name="sex" class="form-control" onchange="document.getElementById('filterForm').submit()">
                                <option value="">All</option>
                                <option value="M" <?php echo $sex === 'M' ? 'selected' : ''; ?>>Male</option>
                                <option value="F" <?php echo $sex === 'F' ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">&nbsp;</label>
                            <?php if ($age || $sex): ?>
                            <a href="/adoptions.php" class="btn btn-outline" style="width: 100%;">Clear Filters</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Results Count -->
        <p class="mb-3" style="color: var(--text-light);">
            Showing <?php echo count($cats); ?> exotic cat<?php echo count($cats) !== 1 ? 's' : ''; ?>
        </p>
        
        <!-- Cats Grid -->
        <?php if (count($cats) > 0): ?>
        <div class="grid grid-3">
            <?php foreach ($cats as $cat): ?>
            <div class="card cat-card">
                <span class="cat-status <?php echo e($cat['status']); ?>">
                    <?php echo ucfirst(e($cat['status'])); ?>
                </span>
                <img src="<?php echo e(cat_photo($cat)); ?>" alt="<?php echo e($cat['name']); ?>" class="card-image">
                <div class="card-content">
                    <h3 class="card-title"><?php echo e($cat['name']); ?></h3>
                    <p class="card-text"><?php echo e(truncate($cat['bio'], 100)); ?></p>
                    <div class="cat-badges">
                        <span class="badge"><?php echo e($cat['age']); ?></span>
                        <span class="badge"><?php echo e($cat['sex'] === 'M' ? 'Male' : 'Female'); ?></span>
                        <span class="badge"><?php echo e($cat['species']); ?></span>
                        <?php if ($cat['fee']): ?>
                        <span class="badge">$<?php echo e($cat['fee']); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <?php 
                    $badges = json_decode($cat['badges'] ?? '[]', true);
                    if ($badges && count($badges) > 0): 
                    ?>
                    <div class="cat-badges mt-2">
                        <?php foreach (array_slice($badges, 0, 3) as $badge): ?>
                        <span class="badge" style="background-color: var(--accent-color);"><?php echo e($badge); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <a href="/cat-detail.php?id=<?php echo e($cat['id']); ?>" class="btn btn-primary" style="width: 100%;">Meet <?php echo e($cat['name']); ?></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="card">
            <div class="card-content text-center" style="padding: 3rem;">
                <h3>No exotic cats found</h3>
                <p>There are currently no exotic cats matching your criteria. Please check back soon or adjust your filters.</p>
                <a href="/adoptions.php" class="btn btn-primary mt-2">View All Exotic Cats</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Adoption Process Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Our Adoption Process</h2>
            <p class="section-subtitle">We want to ensure every exotic feline finds the perfect forever home. Here's how our adoption process works.</p>
        </div>
        
        <div class="grid grid-4">
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color);">1</h3>
                    <h4>Browse</h4>
                    <p>Look through our adoptable exotic cats and find one that captures your heart.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color);">2</h3>
                    <h4>Apply</h4>
                    <p>Fill out an adoption application and tell us about your home, experience, and lifestyle.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color);">3</h3>
                    <h4>Meet & Greet</h4>
                    <p>Visit our facility to meet your potential new exotic companion in person.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color);">4</h3>
                    <h4>Take Home</h4>
                    <p>Complete the adoption paperwork and welcome your new exotic family member home!</p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="/contact.php" class="btn btn-primary">Start Your Adoption Journey</a>
            <span style="margin: 0 1rem; color: var(--text-light);">or</span>
            <a href="/donate.php" class="btn btn-outline">Support Our Rescue with a Donation</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
