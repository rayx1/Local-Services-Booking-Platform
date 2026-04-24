<?php
require_once __DIR__ . '/../includes/functions.php';

$categories = get_categories();
$selectedCategory = (int) ($_GET['category'] ?? 0);

$sql = "
    SELECT services.*, categories.name AS category_name, users.name AS provider_name
    FROM services
    INNER JOIN categories ON services.category_id = categories.id
    INNER JOIN users ON services.provider_id = users.id
    WHERE services.status = 'active'
";

if ($selectedCategory > 0) {
    $sql .= ' AND services.category_id = ?';
}

$sql .= ' ORDER BY services.created_at DESC';
$stmt = $mysqli->prepare($sql);

if ($selectedCategory > 0) {
    $stmt->bind_param('i', $selectedCategory);
}

$stmt->execute();
$services = fetch_all_assoc($stmt);
$stmt->close();

$pageTitle = 'Browse Services - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="section-heading">
    <h2>Browse Services</h2>
    <p>Find local professionals by category.</p>
</section>

<section class="form-card filter-bar">
    <form method="get">
        <div class="form-row">
            <div>
                <label for="category">Filter by Category</label>
                <select name="category" id="category">
                    <option value="">All categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo (int) $category['id']; ?>" <?php echo $selectedCategory === (int) $category['id'] ? 'selected' : ''; ?>>
                            <?php echo h($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="align-self: end;">
                <button type="submit">Apply Filter</button>
            </div>
        </div>
    </form>
</section>

<section class="card-grid">
    <?php if ($services): ?>
        <?php foreach ($services as $service): ?>
            <article class="card">
                <span class="badge badge-success"><?php echo h($service['category_name']); ?></span>
                <h3><?php echo h($service['title']); ?></h3>
                <p><?php echo h(substr($service['description'], 0, 110)); ?>...</p>
                <p class="price">Rs. <?php echo number_format((float) $service['price'], 2); ?></p>
                <p class="muted">Provider: <?php echo h($service['provider_name']); ?></p>
                <p class="muted">Location: <?php echo h($service['location']); ?></p>
                <a href="/local-services-booking-platform/customer/service-details.php?id=<?php echo (int) $service['id']; ?>">View details</a>
            </article>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="card">
            <h3>No services found</h3>
            <p>Try another category or check back later.</p>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
