<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('customer');

$user = current_user();

$stmt = $mysqli->prepare("
    SELECT bookings.*, services.title, services.price, users.name AS provider_name
    FROM bookings
    INNER JOIN services ON bookings.service_id = services.id
    INNER JOIN users ON bookings.provider_id = users.id
    WHERE bookings.customer_id = ?
    ORDER BY bookings.created_at DESC
");
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$bookings = fetch_all_assoc($stmt);
$stmt->close();

$pageTitle = 'My Bookings - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="table-card">
    <h2>My Bookings</h2>
    <table>
        <thead>
            <tr>
                <th>Service</th>
                <th>Provider</th>
                <th>Date</th>
                <th>Time</th>
                <th>Price</th>
                <th>Status</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($bookings): ?>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo h($booking['title']); ?></td>
                        <td><?php echo h($booking['provider_name']); ?></td>
                        <td><?php echo h($booking['booking_date']); ?></td>
                        <td><?php echo h($booking['booking_time']); ?></td>
                        <td>Rs. <?php echo number_format((float) $booking['price'], 2); ?></td>
                        <td><span class="badge <?php echo get_status_badge_class($booking['status']); ?>"><?php echo h($booking['status']); ?></span></td>
                        <td><?php echo h($booking['message']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">You have not created any bookings yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
