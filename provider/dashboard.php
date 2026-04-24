<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('provider');

$user = current_user();

$stmt = $mysqli->prepare("
    SELECT COUNT(*) AS total,
           SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) AS active_services
    FROM services
    WHERE provider_id = ?
");
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$serviceStats = fetch_one_assoc($stmt) ?? ['total' => 0, 'active_services' => 0];
$stmt->close();

$stmt = $mysqli->prepare("
    SELECT COUNT(*) AS total,
           SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_count,
           SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed_count
    FROM bookings
    WHERE provider_id = ?
");
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$bookingStats = fetch_one_assoc($stmt) ?? ['total' => 0, 'pending_count' => 0, 'completed_count' => 0];
$stmt->close();

$stmt = $mysqli->prepare("
    SELECT bookings.*, services.title, users.name AS customer_name
    FROM bookings
    INNER JOIN services ON bookings.service_id = services.id
    INNER JOIN users ON bookings.customer_id = users.id
    WHERE bookings.provider_id = ?
    ORDER BY bookings.created_at DESC
    LIMIT 5
");
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$recentRequests = fetch_all_assoc($stmt);
$stmt->close();

$pageTitle = 'Provider Dashboard - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="section-heading">
    <h2>Provider Dashboard</h2>
    <p>Welcome, <?php echo h($user['name']); ?>. Manage your listings and incoming bookings.</p>
</section>

<section class="stats-grid">
    <div class="stat-card">
        <h3>Total Services</h3>
        <p class="price"><?php echo (int) $serviceStats['total']; ?></p>
    </div>
    <div class="stat-card">
        <h3>Active Services</h3>
        <p class="price"><?php echo (int) $serviceStats['active_services']; ?></p>
    </div>
    <div class="stat-card">
        <h3>Pending Requests</h3>
        <p class="price"><?php echo (int) $bookingStats['pending_count']; ?></p>
    </div>
    <div class="stat-card">
        <h3>Completed Jobs</h3>
        <p class="price"><?php echo (int) $bookingStats['completed_count']; ?></p>
    </div>
</section>

<section class="dashboard-grid">
    <div class="table-card">
        <h2>Recent Booking Requests</h2>
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentRequests as $request): ?>
                    <tr>
                        <td><?php echo h($request['customer_name']); ?></td>
                        <td><?php echo h($request['title']); ?></td>
                        <td><?php echo h($request['booking_date']); ?> <?php echo h($request['booking_time']); ?></td>
                        <td><span class="badge <?php echo get_status_badge_class($request['status']); ?>"><?php echo h($request['status']); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <aside class="info-card">
        <h3>Quick Actions</h3>
        <div class="inline-actions">
            <a class="btn" href="/local-services-booking-platform/provider/add-service.php">Add Service</a>
            <a class="btn btn-outline" href="/local-services-booking-platform/provider/my-services.php">My Services</a>
            <a class="btn btn-secondary" href="/local-services-booking-platform/provider/booking-requests.php">Booking Requests</a>
        </div>
    </aside>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
