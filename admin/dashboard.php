<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('admin');

$pageTitle = 'Admin Dashboard - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="section-heading">
    <h2>Admin Dashboard</h2>
    <p>Monitor platform growth and manage users, categories, services, and bookings.</p>
</section>

<section class="stats-grid">
    <div class="stat-card">
        <h3>Total Users</h3>
        <p class="price"><?php echo get_total_count('users'); ?></p>
    </div>
    <div class="stat-card">
        <h3>Total Providers</h3>
        <p class="price"><?php echo get_user_count_by_role('provider'); ?></p>
    </div>
    <div class="stat-card">
        <h3>Total Customers</h3>
        <p class="price"><?php echo get_user_count_by_role('customer'); ?></p>
    </div>
    <div class="stat-card">
        <h3>Total Services</h3>
        <p class="price"><?php echo get_total_count('services'); ?></p>
    </div>
    <div class="stat-card">
        <h3>Total Bookings</h3>
        <p class="price"><?php echo get_total_count('bookings'); ?></p>
    </div>
</section>

<section class="card-grid">
    <div class="card">
        <h3>Users</h3>
        <p>Review customers, providers, and admin accounts.</p>
        <a href="/local-services-booking-platform/admin/users.php">Manage Users</a>
    </div>
    <div class="card">
        <h3>Categories</h3>
        <p>Add and maintain service categories.</p>
        <a href="/local-services-booking-platform/admin/categories.php">Manage Categories</a>
    </div>
    <div class="card">
        <h3>Services</h3>
        <p>View all service listings and control status.</p>
        <a href="/local-services-booking-platform/admin/services.php">Manage Services</a>
    </div>
    <div class="card">
        <h3>Bookings</h3>
        <p>Inspect all booking activity across the platform.</p>
        <a href="/local-services-booking-platform/admin/bookings.php">View Bookings</a>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
