<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('provider');

$user = current_user();
$categories = get_categories();
$serviceId = (int) ($_GET['id'] ?? $_POST['service_id'] ?? 0);

$stmt = $mysqli->prepare('SELECT * FROM services WHERE id = ? AND provider_id = ? LIMIT 1');
$stmt->bind_param('ii', $serviceId, $user['id']);
$stmt->execute();
$service = fetch_one_assoc($stmt);
$stmt->close();

if (!$service) {
    set_flash('error', 'Service not found.');
    redirect('/local-services-booking-platform/provider/my-services.php');
}

$errors = [];

if (is_post()) {
    $title = post_value('title');
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $description = post_value('description');
    $price = post_value('price');
    $location = post_value('location');
    $status = post_value('status');

    if ($title === '' || $categoryId === 0 || $description === '' || $price === '' || $location === '' || $status === '') {
        $errors[] = 'All fields are required.';
    }

    if (!is_numeric($price) || (float) $price <= 0) {
        $errors[] = 'Price must be a positive number.';
    }

    if (!$errors) {
        $priceValue = (float) $price;
        $stmt = $mysqli->prepare('
            UPDATE services
            SET category_id = ?, title = ?, description = ?, price = ?, location = ?, status = ?
            WHERE id = ? AND provider_id = ?
        ');
        $stmt->bind_param('issdssii', $categoryId, $title, $description, $priceValue, $location, $status, $serviceId, $user['id']);
        $stmt->execute();
        $stmt->close();

        set_flash('success', 'Service updated successfully.');
        redirect('/local-services-booking-platform/provider/my-services.php');
    }
}

$pageTitle = 'Edit Service - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="form-card">
    <h2>Edit Service</h2>
    <?php if ($errors): ?>
        <div class="alert alert-error"><?php echo h(implode(' ', $errors)); ?></div>
    <?php endif; ?>
    <form method="post" data-validate="true">
        <input type="hidden" name="service_id" value="<?php echo (int) $serviceId; ?>">
        <div class="form-row">
            <div>
                <label for="title">Service Title</label>
                <input type="text" id="title" name="title" required value="<?php echo old('title', $service['title']); ?>">
            </div>
            <div>
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <?php $selectedCategoryId = (int) ($_POST['category_id'] ?? $service['category_id']); ?>
                        <option value="<?php echo (int) $category['id']; ?>" <?php echo $selectedCategoryId === (int) $category['id'] ? 'selected' : ''; ?>>
                            <?php echo h($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div>
                <label for="price">Price</label>
                <input type="number" step="0.01" id="price" name="price" required value="<?php echo old('price', (string) $service['price']); ?>">
            </div>
            <div>
                <label for="status">Status</label>
                <?php $selectedStatus = $_POST['status'] ?? $service['status']; ?>
                <select id="status" name="status" required>
                    <option value="active" <?php echo $selectedStatus === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $selectedStatus === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
        </div>
        <div>
            <label for="location">Location</label>
            <input type="text" id="location" name="location" required value="<?php echo old('location', $service['location']); ?>">
        </div>
        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description" required><?php echo old('description', $service['description']); ?></textarea>
        </div>
        <button type="submit">Update Service</button>
    </form>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
