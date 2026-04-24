<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'About - Local Services Booking Platform';
require_once __DIR__ . '/includes/header.php';
?>

<section class="card">
    <h1>About the Project</h1>
    <p>This platform connects customers with local service providers through a simple digital booking system built with Core PHP, MySQL, HTML, CSS, and JavaScript.</p>
    <div class="card-grid">
        <div class="info-card">
            <h3>Problem</h3>
            <p>Customers often struggle to find reliable local workers quickly, while providers lack an affordable digital presence.</p>
        </div>
        <div class="info-card">
            <h3>Solution</h3>
            <p>Customers can browse and book services, providers can manage listings and bookings, and admin can supervise the platform.</p>
        </div>
        <div class="info-card">
            <h3>Goal</h3>
            <p>Create a beginner-friendly college project that demonstrates full-stack CRUD, authentication, booking workflows, and role-based access control.</p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
