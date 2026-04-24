<?php
if (!defined('DB_HOST')) {
    require_once __DIR__ . '/config.php';
    require_once __DIR__ . '/db.php';
    require_once __DIR__ . '/functions.php';
}

$pageVisibility = getPageVisibility();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo isset($metaDescription) ? e($metaDescription) : 'Careful Cat Rescue - Saving lives one paw at a time in Murfreesboro, TN'; ?>">
    <meta name="keywords" content="cat rescue, cat adoption, animal shelter, Murfreesboro TN, cat sanctuary">
    <meta property="og:title" content="<?php echo isset($pageTitle) ? e($pageTitle) . ' - ' . SITE_NAME : SITE_NAME; ?>">
    <meta property="og:description" content="<?php echo isset($metaDescription) ? e($metaDescription) : 'Careful Cat Rescue - Saving lives one paw at a time'; ?>">
    <meta property="og:image" content="<?php echo isset($ogImage) ? e($ogImage) : ASSETS_URL . '/images/careful_cat_logo_1757800576415-Cgbj6qkL.png'; ?>">
    <meta property="og:url" content="<?php echo e(currentUrl()); ?>">
    <meta property="og:type" content="website">
    <title><?php echo isset($pageTitle) ? e($pageTitle) . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <?php if (isset($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?php echo e($css); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="<?php echo isset($bodyClass) ? e($bodyClass) : ''; ?>">
    <header class="site-header">
        <nav class="navbar">
            <div class="container">
                <div class="nav-wrapper">
                    <a href="/" class="logo">
                        <img src="<?php echo ASSETS_URL; ?>/images/careful_cat_logo_1757800576415-Cgbj6qkL.png" alt="<?php echo e(SITE_NAME); ?>" class="logo-img">
                        <span class="logo-text"><?php echo e(SITE_NAME); ?></span>
                    </a>
                    
                    <button class="mobile-menu-toggle" aria-label="Toggle menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    
                    <ul class="nav-menu">
                        <li><a href="/" class="<?php echo $currentPage === 'index' ? 'active' : ''; ?>">Home</a></li>
                        
                        <?php if ($pageVisibility['adoptions']): ?>
                        <li><a href="/adoptions.php" class="<?php echo $currentPage === 'adoptions' ? 'active' : ''; ?>">Adopt</a></li>
                        <?php endif; ?>
                        
                        <?php if ($pageVisibility['residents']): ?>
                        <li><a href="/residents.php" class="<?php echo $currentPage === 'residents' ? 'active' : ''; ?>">Residents</a></li>
                        <?php endif; ?>
                        
                        <?php if ($pageVisibility['blog']): ?>
                        <li><a href="/blog.php" class="<?php echo $currentPage === 'blog' ? 'active' : ''; ?>">Blog</a></li>
                        <?php endif; ?>
                        
                        <?php if ($pageVisibility['volunteer']): ?>
                        <li><a href="/volunteer.php" class="<?php echo $currentPage === 'volunteer' ? 'active' : ''; ?>">Volunteer</a></li>
                        <?php endif; ?>
                        
                        <?php if ($pageVisibility['book_visit']): ?>
                        <li><a href="/book-visit.php" class="<?php echo $currentPage === 'book-visit' ? 'active' : ''; ?>">Visit</a></li>
                        <?php endif; ?>
                        
                        <?php if ($pageVisibility['shop']): ?>
                        <li><a href="/shop.php" class="<?php echo $currentPage === 'shop' ? 'active' : ''; ?>">Shop</a></li>
                        <?php endif; ?>
                        
                        <li><a href="/contact.php" class="<?php echo $currentPage === 'contact' ? 'active' : ''; ?>">Contact</a></li>
                        <li><a href="/donate.php" class="btn btn-primary">Donate</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="main-content">

