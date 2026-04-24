<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('admin');

if (is_post()) {
    $serviceId = (int) ($_POST['service_id'] ?? 0);
    $status = post_value('status');

    if ($serviceId > 0 && in_array($status, ['active', 'inactive'], true)) {
        $stmt = $mysqli->prepare('UPDATE services SET status = ? WHERE id = ?');
        $stmt->bind_param('si', $status, $serviceId);
        $stmt->execute();
        $stmt->close();
        set_flash('success', 'Service status updated successfully.');
    } else {
        set_flash('error', 'Invalid service update request.');
    }

    redirect('/local-services-booking-platform/admin/services.php');
}

$result = $mysqli->query("
    SELECT services.*, categories.name AS category_name, users.name AS provider_name
    FROM services
    INNER JOIN categories ON services.category_id = categories.id
    INNER JOIN users ON services.provider_id = users.id
    ORDER BY services.created_at DESC
");
$services = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

$pageTitle = 'Manage Services - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="table-card">
    <h2>All Services</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Provider</th>
                <th>Category</th>
                <th>Price</th>
                <th>Location</th>
                <th>Status</th>
                <th>Update</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?php echo h($service['title']); ?></td>
                    <td><?php echo h($service['provider_name']); ?></td>
                    <td><?php echo h($service['category_name']); ?></td>
                    <td>Rs. <?php echo number_format((float) $service['price'], 2); ?></td>
                    <td><?php echo h($service['location']); ?></td>
                    <td><span class="badge <?php echo get_status_badge_class($service['status']); ?>"><?php echo h($service['status']); ?></span></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="service_id" value="<?php echo (int) $service['id']; ?>">
                            <select name="status">
                                <option value="active" <?php echo $service['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $service['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                            <button type="submit">Save</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
