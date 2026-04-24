<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('provider');

$user = current_user();

$stmt = $mysqli->prepare("
    SELECT services.*, categories.name AS category_name
    FROM services
    INNER JOIN categories ON services.category_id = categories.id
    WHERE services.provider_id = ?
    ORDER BY services.created_at DESC
");
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$services = fetch_all_assoc($stmt);
$stmt->close();

$pageTitle = 'My Services - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="table-card">
    <h2>My Services</h2>
    <div class="inline-actions">
        <a class="btn" href="/local-services-booking-platform/provider/add-service.php">Add New Service</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Price</th>
                <th>Location</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($services): ?>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?php echo h($service['title']); ?></td>
                        <td><?php echo h($service['category_name']); ?></td>
                        <td>Rs. <?php echo number_format((float) $service['price'], 2); ?></td>
                        <td><?php echo h($service['location']); ?></td>
                        <td><span class="badge <?php echo get_status_badge_class($service['status']); ?>"><?php echo h($service['status']); ?></span></td>
                        <td><a href="/local-services-booking-platform/provider/edit-service.php?id=<?php echo (int) $service['id']; ?>">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">You have not added any services yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
