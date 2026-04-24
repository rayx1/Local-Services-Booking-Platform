<?php
$pageTitle = $pageTitle ?? 'Local Services Booking Platform';
$flash = get_flash();
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo h($pageTitle); ?></title>
    <link rel="stylesheet" href="/local-services-booking-platform/assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container nav-wrapper">
            <a class="brand" href="/local-services-booking-platform/index.php">Local Services</a>
            <button class="menu-toggle" id="menuToggle" type="button">Menu</button>
            <nav class="site-nav" id="siteNav">
                <a href="/local-services-booking-platform/index.php">Home</a>
                <a href="/local-services-booking-platform/about.php">About</a>
                <a href="/local-services-booking-platform/contact.php">Contact</a>
                <a href="/local-services-booking-platform/customer/services.php">Services</a>
                <?php if ($user): ?>
                    <a href="<?php echo h(get_dashboard_path($user['role'])); ?>">Dashboard</a>
                    <a href="/local-services-booking-platform/auth/logout.php">Logout</a>
                <?php else: ?>
                    <a href="/local-services-booking-platform/auth/login.php">Login</a>
                    <a href="/local-services-booking-platform/auth/register.php">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="page-content">
        <div class="container">
            <?php if ($flash): ?>
                <div class="alert <?php echo $flash['type'] === 'success' ? 'alert-success' : 'alert-error'; ?>">
                    <?php echo h($flash['message']); ?>
                </div>
            <?php endif; ?>
