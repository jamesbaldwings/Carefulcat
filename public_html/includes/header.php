<?php
if (!defined('DB_HOST')) {
    require_once __DIR__ . '/config.php';
    require_once __DIR__ . '/db.php';
    require_once __DIR__ . '/functions.php';
}

$pageVisibility = getPageVisibility();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

// SEO defaults
$siteName = SITE_NAME;
$siteUrl = SITE_URL;
$currentUrl = $siteUrl . parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$fullTitle = isset($pageTitle) ? e($pageTitle) . ' | ' . $siteName : $siteName . ' - Small Exotic Cat Rescue in Murfreesboro, TN';
$metaDesc = isset($metaDescription) ? e($metaDescription) : 'Careful Cat Rescue is a nonprofit dedicated to rescuing, rehabilitating, and rehoming small exotic felines in Murfreesboro, TN. Adopt, volunteer, or donate today.';
$metaKw = isset($metaKeywords) ? e($metaKeywords) : 'exotic cat rescue, small exotic cat adoption, exotic feline sanctuary, Murfreesboro TN, serval rescue, savannah cat rescue, bengal cat rescue, caracal rescue, donate exotic cat rescue';
$ogImg = isset($ogImage) ? e($ogImage) : ASSETS_URL . '/images/careful_cat_logo_1757800576415-Cgbj6qkL.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title><?php echo $fullTitle; ?></title>
    <meta name="description" content="<?php echo $metaDesc; ?>">
    <meta name="keywords" content="<?php echo $metaKw; ?>">
    <meta name="author" content="Careful Cat Rescue">
    <meta name="robots" content="index, follow">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo e($currentUrl); ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e($currentUrl); ?>">
    <meta property="og:title" content="<?php echo $fullTitle; ?>">
    <meta property="og:description" content="<?php echo $metaDesc; ?>">
    <meta property="og:image" content="<?php echo $ogImg; ?>">
    <meta property="og:site_name" content="<?php echo e($siteName); ?>">
    <meta property="og:locale" content="en_US">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $fullTitle; ?>">
    <meta name="twitter:description" content="<?php echo $metaDesc; ?>">
    <meta name="twitter:image" content="<?php echo $ogImg; ?>">
    
    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": ["NonprofitOrganization", "LocalBusiness"],
        "name": "Careful Cat Rescue",
        "description": "Careful Cat Rescue is a nonprofit organization dedicated to rescuing abandoned, neglected, and homeless small exotic felines. We provide medical care, rehabilitation, and a safe environment while they await their forever homes.",
        "url": "<?php echo e($siteUrl); ?>",
        "logo": "<?php echo ASSETS_URL; ?>/images/careful_cat_logo_1757800576415-Cgbj6qkL.png",
        "image": "<?php echo $ogImg; ?>",
        "email": "<?php echo e(SITE_EMAIL); ?>",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Murfreesboro",
            "addressRegion": "TN",
            "addressCountry": "US"
        },
        "openingHoursSpecification": [
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": ["Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                "opens": "10:00",
                "closes": "16:00"
            },
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": "Sunday",
                "opens": "12:00",
                "closes": "16:00"
            }
        ],
        "nonprofitStatus": "501(c)(3)",
        "areaServed": {
            "@type": "State",
            "name": "Tennessee"
        },
        "potentialAction": {
            "@type": "DonateAction",
            "target": {
                "@type": "EntryPoint",
                "urlTemplate": "<?php echo e($siteUrl); ?>/donate.php"
            },
            "description": "Donate to support small exotic cat rescue and rehabilitation"
        }
    }
    </script>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">
    
    <?php if (isset($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?php echo e($css); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (isset($additionalJS)): ?>
        <?php foreach ($additionalJS as $js): ?>
            <script src="<?php echo e($js); ?>"></script>
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
                        <li><a href="/about.php" class="<?php echo $currentPage === 'about' ? 'active' : ''; ?>">About</a></li>
                        
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
                        <li><a href="/donate.php" class="btn btn-primary nav-donate-btn">Donate Now</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="main-content">
