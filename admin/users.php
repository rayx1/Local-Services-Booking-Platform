<?php
require_once __DIR__ . '/../includes/functions.php';
require_role('admin');

$currentUser = current_user();

if (is_post()) {
    $userId = (int) ($_POST['user_id'] ?? 0);

    if ($userId > 0 && $userId !== (int) $currentUser['id']) {
        $stmt = $mysqli->prepare('DELETE FROM users WHERE id = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->close();
        set_flash('success', 'User removed successfully.');
    } else {
        set_flash('error', 'Invalid user delete request.');
    }

    redirect('/local-services-booking-platform/admin/users.php');
}

$result = $mysqli->query('SELECT * FROM users ORDER BY created_at DESC');
$users = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

$pageTitle = 'Manage Users - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="table-card">
    <h2>Manage Users</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo h($user['name']); ?></td>
                    <td><?php echo h($user['email']); ?></td>
                    <td><?php echo h($user['phone']); ?></td>
                    <td><span class="badge badge-warning"><?php echo h($user['role']); ?></span></td>
                    <td><?php echo h($user['created_at']); ?></td>
                    <td>
                        <?php if ((int) $user['id'] !== (int) $currentUser['id']): ?>
                            <form method="post" onsubmit="return confirm('Delete this user?');">
                                <input type="hidden" name="user_id" value="<?php echo (int) $user['id']; ?>">
                                <button type="submit">Delete</button>
                            </form>
                        <?php else: ?>
                            Current Admin
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
