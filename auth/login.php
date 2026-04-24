<?php
require_once __DIR__ . '/../includes/functions.php';

if (is_logged_in()) {
    redirect(get_dashboard_path(current_user()['role']));
}

$errors = [];

if (is_post()) {
    $email = post_value('email');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Email and password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (!$errors) {
        $stmt = $mysqli->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = fetch_one_assoc($stmt);
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ];

            set_flash('success', 'Welcome back, ' . $user['name'] . '!');
            redirect(get_dashboard_path($user['role']));
        }

        $errors[] = 'Invalid login credentials.';
    }
}

$pageTitle = 'Login - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="form-card" style="max-width: 540px; margin: 0 auto;">
    <h2>Login</h2>
    <p class="muted">Sign in to manage services or bookings.</p>
    <?php if ($errors): ?>
        <div class="alert alert-error"><?php echo h(implode(' ', $errors)); ?></div>
    <?php endif; ?>
    <form method="post" data-validate="true">
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="<?php echo old('email'); ?>">
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
