<?php
require_once __DIR__ . '/includes/functions.php';

$categories = get_categories();
$featuredServices = [];
$stmt = $mysqli->prepare("
    SELECT services.*, categories.name AS category_name, users.name AS provider_name
    FROM services
    INNER JOIN categories ON services.category_id = categories.id
    INNER JOIN users ON services.provider_id = users.id
    WHERE services.status = 'active'
    ORDER BY services.created_at DESC
    LIMIT 6
");
$stmt->execute();
$featuredServices = fetch_all_assoc($stmt);
$stmt->close();

$pageTitle = 'Home - Local Services Booking Platform';
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div>
        <h1>Find trusted local experts and book services in minutes.</h1>
        <p>Browse verified local providers for plumbing, electrical work, cleaning, tutoring, carpentry, and more through one easy platform.</p>
        <div class="hero-actions">
            <a class="btn" href="/local-services-booking-platform/customer/services.php">Browse Services</a>
            <a class="btn btn-secondary" href="/local-services-booking-platform/auth/register.php">Join Now</a>
        </div>
    </div>
    <div class="info-card">
        <h3>Why this platform?</h3>
        <ul class="feature-list">
            <li>Quick service discovery by category</li>
            <li>Simple booking workflow for customers</li>
            <li>Dedicated provider and admin dashboards</li>
            <li>Real-time status tracking for every booking</li>
        </ul>
    </div>
</section>

<section class="section-heading">
    <h2>Popular Categories</h2>
    <p>Explore service types available on the platform.</p>
</section>
<section class="card-grid">
    <?php foreach ($categories as $category): ?>
        <article class="card">
            <h3><?php echo h($category['name']); ?></h3>
            <p><?php echo h($category['description']); ?></p>
            <a href="/local-services-booking-platform/customer/services.php?category=<?php echo (int) $category['id']; ?>">View services</a>
        </article>
    <?php endforeach; ?>
</section>

<section class="section-heading">
    <h2>Featured Services</h2>
    <p>Start with some of the most recent active service listings.</p>
</section>
<section class="card-grid">
    <?php foreach ($featuredServices as $service): ?>
        <article class="card">
            <span class="badge badge-success"><?php echo h($service['category_name']); ?></span>
            <h3><?php echo h($service['title']); ?></h3>
            <p><?php echo h(substr($service['description'], 0, 120)); ?>...</p>
            <p class="price">Rs. <?php echo number_format((float) $service['price'], 2); ?></p>
            <p class="muted">Provider: <?php echo h($service['provider_name']); ?></p>
            <a href="/local-services-booking-platform/customer/service-details.php?id=<?php echo (int) $service['id']; ?>">View details</a>
        </article>
    <?php endforeach; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
