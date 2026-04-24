<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('customer');

$user = current_user();
$serviceId = (int) ($_GET['id'] ?? $_POST['service_id'] ?? 0);

$stmt = $mysqli->prepare("
    SELECT services.*, users.name AS provider_name
    FROM services
    INNER JOIN users ON services.provider_id = users.id
    WHERE services.id = ? AND services.status = 'active'
    LIMIT 1
");
$stmt->bind_param('i', $serviceId);
$stmt->execute();
$service = fetch_one_assoc($stmt);
$stmt->close();

if (!$service) {
    set_flash('error', 'Service not found or inactive.');
    redirect('/local-services-booking-platform/customer/services.php');
}

$errors = [];

if (is_post()) {
    $bookingDate = post_value('booking_date');
    $bookingTime = post_value('booking_time');
    $address = post_value('address');
    $message = post_value('message');

    if ($bookingDate === '' || $bookingTime === '' || $address === '') {
        $errors[] = 'Booking date, time, and address are required.';
    }

    if (!$errors) {
        $stmt = $mysqli->prepare('
            INSERT INTO bookings (customer_id, service_id, provider_id, booking_date, booking_time, address, message, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, "pending")
        ');
        $stmt->bind_param(
            'iiissss',
            $user['id'],
            $service['id'],
            $service['provider_id'],
            $bookingDate,
            $bookingTime,
            $address,
            $message
        );
        $stmt->execute();
        $stmt->close();

        set_flash('success', 'Service booked successfully. Please wait for provider confirmation.');
        redirect('/local-services-booking-platform/customer/my-bookings.php');
    }
}

$pageTitle = 'Book Service - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="dashboard-grid">
    <div class="form-card">
        <h2>Book Service</h2>
        <p class="muted">Submit your booking request for <?php echo h($service['title']); ?>.</p>
        <?php if ($errors): ?>
            <div class="alert alert-error"><?php echo h(implode(' ', $errors)); ?></div>
        <?php endif; ?>
        <form method="post" data-validate="true">
            <input type="hidden" name="service_id" value="<?php echo (int) $service['id']; ?>">
            <div class="form-row">
                <div>
                    <label for="booking_date">Booking Date</label>
                    <input type="date" id="booking_date" name="booking_date" required value="<?php echo old('booking_date'); ?>">
                </div>
                <div>
                    <label for="booking_time">Booking Time</label>
                    <input type="time" id="booking_time" name="booking_time" required value="<?php echo old('booking_time'); ?>">
                </div>
            </div>
            <div>
                <label for="address">Service Address</label>
                <textarea id="address" name="address" required><?php echo old('address', $user['name']); ?></textarea>
            </div>
            <div>
                <label for="message">Message</label>
                <textarea id="message" name="message"><?php echo old('message'); ?></textarea>
            </div>
            <button type="submit">Confirm Booking</button>
        </form>
    </div>
    <aside class="info-card">
        <h3>Booking Summary</h3>
        <p><strong>Service:</strong> <?php echo h($service['title']); ?></p>
        <p><strong>Provider:</strong> <?php echo h($service['provider_name']); ?></p>
        <p><strong>Price:</strong> Rs. <?php echo number_format((float) $service['price'], 2); ?></p>
        <p><strong>Location:</strong> <?php echo h($service['location']); ?></p>
    </aside>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
