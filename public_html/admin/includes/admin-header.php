<?php
/**
 * Admin Header - FULL VERSION (with emojis + fixed includes + RESIDENTS LINK)
 * Correct paths, authentication, and active nav highlighting.
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin(); // Authentication check

// Detect current page/section for menu highlighting
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin Panel'; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/admin.css">
    <link rel="icon" href="<?php echo ASSETS_URL; ?>/images/favicon.png">
</head>
<body class="admin-body">
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <img src="<?php echo ASSETS_URL; ?>/images/careful_cat_logo_1757800576415-Cgbj6qkL.png"
                     alt="<?php echo SITE_NAME; ?>" style="width: 40px; height: 40px;">
                <span>Admin Panel</span>
            </div>

            <nav class="admin-nav">
                <a href="/admin/index.php" class="admin-nav-item <?php echo $current_page === 'index' ? 'active' : ''; ?>">
                    📊 Dashboard
                </a>
                <a href="/admin/cats/index.php" class="admin-nav-item <?php echo strpos($_SERVER['PHP_SELF'], '/cats/') !== false ? 'active' : ''; ?>">
                    🐱 Cats
                </a>
                <a href="/admin/residents/index.php" class="admin-nav-item <?php echo strpos($_SERVER['PHP_SELF'], '/residents/') !== false ? 'active' : ''; ?>">
                    🏠 Residents
                </a>
                <a href="/admin/adoptions/index.php" class="admin-nav-item <?php echo strpos($_SERVER['PHP_SELF'], '/adoptions/') !== false ? 'active' : ''; ?>">
                    📝 Adoptions
                </a>
                <a href="/admin/donations/index.php" class="admin-nav-item <?php echo strpos($_SERVER['PHP_SELF'], '/donations/') !== false ? 'active' : ''; ?>">
                    💰 Donations
                </a>
                <a href="/admin/volunteers/index.php" class="admin-nav-item <?php echo strpos($_SERVER['PHP_SELF'], '/volunteers/') !== false ? 'active' : ''; ?>">
                    🤝 Volunteers
                </a>
                <a href="/admin/blog/index.php" class="admin-nav-item <?php echo strpos($_SERVER['PHP_SELF'], '/blog/') !== false ? 'active' : ''; ?>">
                    📰 Blog Posts
                </a>
                <a href="/admin/messages/index.php" class="admin-nav-item <?php echo strpos($_SERVER['PHP_SELF'], '/messages/') !== false ? 'active' : ''; ?>">
                    ✉️ Messages
                </a>
                <a href="/admin/sponsors/index.php" class="admin-nav-item <?php echo strpos($_SERVER['PHP_SELF'], '/sponsors/') !== false ? 'active' : ''; ?>">
                    🏆 Sponsors
                </a>
                <a href="/admin/settings/index.php" class="admin-nav-item <?php echo strpos($_SERVER['PHP_SELF'], '/settings/') !== false ? 'active' : ''; ?>">
                    ⚙️ Settings
                </a>
            </nav>

            <div class="admin-user">
                <div class="admin-user-info">
                    <strong><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin User'); ?></strong>
                    <small><?php echo htmlspecialchars($_SESSION['admin_email'] ?? 'admin@carefulcatrescue.org'); ?></small>
                </div>
                <a href="/admin/logout.php" class="admin-logout">🚪 Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <h1><?php echo $page_title ?? 'Dashboard'; ?></h1>
                <div class="admin-header-actions">
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-outline" target="_blank">🌐 View Site</a>
                </div>
            </header>

            <div class="admin-content">
