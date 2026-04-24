<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('admin');

$result = $mysqli->query("
    SELECT bookings.*, c.name AS customer_name, p.name AS provider_name, services.title
    FROM bookings
    INNER JOIN users c ON bookings.customer_id = c.id
    INNER JOIN users p ON bookings.provider_id = p.id
    INNER JOIN services ON bookings.service_id = services.id
    ORDER BY bookings.created_at DESC
");
$bookings = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

$pageTitle = 'All Bookings - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="table-card">
    <h2>All Bookings</h2>
    <table>
        <thead>
            <tr>
                <th>Customer</th>
                <th>Provider</th>
                <th>Service</th>
                <th>Schedule</th>
                <th>Status</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?php echo h($booking['customer_name']); ?></td>
                    <td><?php echo h($booking['provider_name']); ?></td>
                    <td><?php echo h($booking['title']); ?></td>
                    <td><?php echo h($booking['booking_date']); ?><br><?php echo h($booking['booking_time']); ?></td>
                    <td><span class="badge <?php echo get_status_badge_class($booking['status']); ?>"><?php echo h($booking['status']); ?></span></td>
                    <td><?php echo h($booking['address']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
