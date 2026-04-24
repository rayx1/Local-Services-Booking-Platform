<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('provider');

$user = current_user();

if (is_post()) {
    $bookingId = (int) ($_POST['booking_id'] ?? 0);
    $status = post_value('status');

    if ($bookingId > 0 && in_array($status, booking_status_options(), true)) {
        $stmt = $mysqli->prepare('UPDATE bookings SET status = ? WHERE id = ? AND provider_id = ?');
        $stmt->bind_param('sii', $status, $bookingId, $user['id']);
        $stmt->execute();
        $stmt->close();
        set_flash('success', 'Booking status updated successfully.');
    } else {
        set_flash('error', 'Invalid booking update request.');
    }

    redirect('/local-services-booking-platform/provider/booking-requests.php');
}

$stmt = $mysqli->prepare("
    SELECT bookings.*, services.title, users.name AS customer_name, users.phone AS customer_phone
    FROM bookings
    INNER JOIN services ON bookings.service_id = services.id
    INNER JOIN users ON bookings.customer_id = users.id
    WHERE bookings.provider_id = ?
    ORDER BY bookings.created_at DESC
");
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$bookings = fetch_all_assoc($stmt);
$stmt->close();

$pageTitle = 'Booking Requests - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="table-card">
    <h2>Booking Requests</h2>
    <table>
        <thead>
            <tr>
                <th>Customer</th>
                <th>Service</th>
                <th>Schedule</th>
                <th>Address</th>
                <th>Message</th>
                <th>Status</th>
                <th>Update</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($bookings): ?>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td>
                            <?php echo h($booking['customer_name']); ?><br>
                            <span class="muted"><?php echo h($booking['customer_phone']); ?></span>
                        </td>
                        <td><?php echo h($booking['title']); ?></td>
                        <td><?php echo h($booking['booking_date']); ?><br><?php echo h($booking['booking_time']); ?></td>
                        <td><?php echo h($booking['address']); ?></td>
                        <td><?php echo h($booking['message']); ?></td>
                        <td><span class="badge <?php echo get_status_badge_class($booking['status']); ?>"><?php echo h($booking['status']); ?></span></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="booking_id" value="<?php echo (int) $booking['id']; ?>">
                                <select name="status">
                                    <?php foreach (booking_status_options() as $statusOption): ?>
                                        <option value="<?php echo h($statusOption); ?>" <?php echo $booking['status'] === $statusOption ? 'selected' : ''; ?>>
                                            <?php echo ucfirst($statusOption); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No booking requests found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
