<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('admin');

$errors = [];

if (isset($_POST['add_category'])) {
    $name = post_value('name');
    $description = post_value('description');

    if ($name === '' || $description === '') {
        $errors[] = 'Category name and description are required.';
    } else {
        $stmt = $mysqli->prepare('INSERT INTO categories (name, description) VALUES (?, ?)');
        $stmt->bind_param('ss', $name, $description);
        $stmt->execute();
        $stmt->close();
        set_flash('success', 'Category added successfully.');
        redirect('/local-services-booking-platform/admin/categories.php');
    }
}

if (isset($_POST['delete_category'])) {
    $categoryId = (int) ($_POST['category_id'] ?? 0);

    if ($categoryId > 0) {
        $stmt = $mysqli->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();
        $stmt->close();
        set_flash('success', 'Category deleted successfully.');
        redirect('/local-services-booking-platform/admin/categories.php');
    }
}

$categories = get_categories();

$pageTitle = 'Manage Categories - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="dashboard-grid">
    <div class="table-card">
        <h2>Categories</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo h($category['name']); ?></td>
                        <td><?php echo h($category['description']); ?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('Delete this category?');">
                                <input type="hidden" name="category_id" value="<?php echo (int) $category['id']; ?>">
                                <button type="submit" name="delete_category">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <aside class="form-card">
        <h2>Add Category</h2>
        <?php if ($errors): ?>
            <div class="alert alert-error"><?php echo h(implode(' ', $errors)); ?></div>
        <?php endif; ?>
        <form method="post" data-validate="true">
            <div>
                <label for="name">Category Name</label>
                <input type="text" id="name" name="name" required value="<?php echo old('name'); ?>">
            </div>
            <div>
                <label for="description">Description</label>
                <textarea id="description" name="description" required><?php echo old('description'); ?></textarea>
            </div>
            <button type="submit" name="add_category">Add Category</button>
        </form>
    </aside>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
