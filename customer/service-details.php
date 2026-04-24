<?php
require_once __DIR__ . '/../includes/functions.php';

$serviceId = (int) ($_GET['id'] ?? 0);

$stmt = $mysqli->prepare("
    SELECT services.*, categories.name AS category_name, users.name AS provider_name, users.phone AS provider_phone, users.email AS provider_email
    FROM services
    INNER JOIN categories ON services.category_id = categories.id
    INNER JOIN users ON services.provider_id = users.id
    WHERE services.id = ? AND services.status = 'active'
    LIMIT 1
");
$stmt->bind_param('i', $serviceId);
$stmt->execute();
$service = fetch_one_assoc($stmt);
$stmt->close();

if (!$service) {
    set_flash('error', 'Service not found.');
    redirect('/local-services-booking-platform/customer/services.php');
}

$pageTitle = 'Service Details - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="details-grid">
    <article class="card">
        <span class="badge badge-success"><?php echo h($service['category_name']); ?></span>
        <h1><?php echo h($service['title']); ?></h1>
        <p><?php echo nl2br(h($service['description'])); ?></p>
    </article>
    <aside class="info-card service-meta">
        <div>
            <strong>Price:</strong>
            <div class="price">Rs. <?php echo number_format((float) $service['price'], 2); ?></div>
        </div>
        <div><strong>Provider:</strong> <?php echo h($service['provider_name']); ?></div>
        <div><strong>Location:</strong> <?php echo h($service['location']); ?></div>
        <div><strong>Phone:</strong> <?php echo h($service['provider_phone']); ?></div>
        <div><strong>Email:</strong> <?php echo h($service['provider_email']); ?></div>
        <?php if (has_role('customer')): ?>
            <a class="btn" href="/local-services-booking-platform/customer/book-service.php?id=<?php echo (int) $service['id']; ?>">Book This Service</a>
        <?php else: ?>
            <a class="btn" href="/local-services-booking-platform/auth/login.php">Login to Book</a>
        <?php endif; ?>
    </aside>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
