<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('customer');

$user = current_user();

$stmt = $mysqli->prepare("
    SELECT COUNT(*) AS total,
           SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_count,
           SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed_count
    FROM bookings
    WHERE customer_id = ?
");
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$stats = fetch_one_assoc($stmt) ?? ['total' => 0, 'pending_count' => 0, 'completed_count' => 0];
$stmt->close();

$stmt = $mysqli->prepare("
    SELECT bookings.*, services.title
    FROM bookings
    INNER JOIN services ON bookings.service_id = services.id
    WHERE bookings.customer_id = ?
    ORDER BY bookings.created_at DESC
    LIMIT 5
");
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$recentBookings = fetch_all_assoc($stmt);
$stmt->close();

$pageTitle = 'Customer Dashboard - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="section-heading">
    <h2>Customer Dashboard</h2>
    <p>Welcome, <?php echo h($user['name']); ?>. Track your bookings and browse new services.</p>
</section>

<section class="stats-grid">
    <div class="stat-card">
        <h3>Total Bookings</h3>
        <p class="price"><?php echo (int) $stats['total']; ?></p>
    </div>
    <div class="stat-card">
        <h3>Pending</h3>
        <p class="price"><?php echo (int) $stats['pending_count']; ?></p>
    </div>
    <div class="stat-card">
        <h3>Completed</h3>
        <p class="price"><?php echo (int) $stats['completed_count']; ?></p>
    </div>
    <div class="stat-card">
        <h3>Active Services</h3>
        <p class="price"><?php echo get_service_count(); ?></p>
    </div>
</section>

<section class="dashboard-grid">
    <div class="table-card">
        <h2>Recent Bookings</h2>
        <table>
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentBookings as $booking): ?>
                    <tr>
                        <td><?php echo h($booking['title']); ?></td>
                        <td><?php echo h($booking['booking_date']); ?></td>
                        <td><?php echo h($booking['booking_time']); ?></td>
                        <td><span class="badge <?php echo get_status_badge_class($booking['status']); ?>"><?php echo h($booking['status']); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <aside class="info-card">
        <h3>Quick Actions</h3>
        <div class="inline-actions">
            <a class="btn" href="/local-services-booking-platform/customer/services.php">Browse Services</a>
            <a class="btn btn-outline" href="/local-services-booking-platform/customer/my-bookings.php">My Bookings</a>
        </div>
    </aside>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
