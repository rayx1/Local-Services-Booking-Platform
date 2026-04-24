<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('provider');

$categories = get_categories();
$errors = [];
$user = current_user();

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

    if (!in_array($status, ['active', 'inactive'], true)) {
        $errors[] = 'Please choose a valid status.';
    }

    if (!$errors) {
        $stmt = $mysqli->prepare('
            INSERT INTO services (provider_id, category_id, title, description, price, location, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $priceValue = (float) $price;
        $stmt->bind_param('iisdsss', $user['id'], $categoryId, $title, $description, $priceValue, $location, $status);
        $stmt->execute();
        $stmt->close();

        set_flash('success', 'Service added successfully.');
        redirect('/local-services-booking-platform/provider/my-services.php');
    }
}

$pageTitle = 'Add Service - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="form-card">
    <h2>Add New Service</h2>
    <?php if ($errors): ?>
        <div class="alert alert-error"><?php echo h(implode(' ', $errors)); ?></div>
    <?php endif; ?>
    <form method="post" data-validate="true">
        <div class="form-row">
            <div>
                <label for="title">Service Title</label>
                <input type="text" id="title" name="title" required value="<?php echo old('title'); ?>">
            </div>
            <div>
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo (int) $category['id']; ?>" <?php echo (int) old('category_id', '0') === (int) $category['id'] ? 'selected' : ''; ?>>
                            <?php echo h($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div>
                <label for="price">Price</label>
                <input type="number" step="0.01" id="price" name="price" required value="<?php echo old('price'); ?>">
            </div>
            <div>
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="active" <?php echo old('status', 'active') === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo old('status') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
        </div>
        <div>
            <label for="location">Location</label>
            <input type="text" id="location" name="location" required value="<?php echo old('location'); ?>">
        </div>
        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description" required><?php echo old('description'); ?></textarea>
        </div>
        <button type="submit">Save Service</button>
    </form>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
