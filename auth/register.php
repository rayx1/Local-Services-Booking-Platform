<?php
require_once __DIR__ . '/../includes/functions.php';

if (is_logged_in()) {
    redirect(get_dashboard_path(current_user()['role']));
}

$errors = [];

if (is_post()) {
    $name = post_value('name');
    $email = post_value('email');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $phone = post_value('phone');
    $address = post_value('address');
    $role = post_value('role');

    if ($name === '' || $email === '' || $password === '' || $confirmPassword === '' || $phone === '' || $address === '' || $role === '') {
        $errors[] = 'All fields are required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (!in_array($role, ['customer', 'provider'], true)) {
        $errors[] = 'Please choose a valid account type.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Password and confirm password do not match.';
    }

    $checkStmt = $mysqli->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $checkStmt->bind_param('s', $email);
    $checkStmt->execute();
    $existingUser = fetch_one_assoc($checkStmt);
    $checkStmt->close();

    if ($existingUser) {
        $errors[] = 'An account with this email already exists.';
    }

    if (!$errors) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare('INSERT INTO users (name, email, password, phone, address, role) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssss', $name, $email, $hashedPassword, $phone, $address, $role);
        $stmt->execute();
        $stmt->close();

        set_flash('success', 'Registration completed successfully. Please log in.');
        redirect('/local-services-booking-platform/auth/login.php');
    }
}

$pageTitle = 'Register - Local Services Booking Platform';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="form-card" style="max-width: 700px; margin: 0 auto;">
    <h2>Create Account</h2>
    <p class="muted">Register as a customer or service provider.</p>
    <?php if ($errors): ?>
        <div class="alert alert-error"><?php echo h(implode(' ', $errors)); ?></div>
    <?php endif; ?>
    <form method="post" data-validate="true">
        <div class="form-row">
            <div>
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required value="<?php echo old('name'); ?>">
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?php echo old('email'); ?>">
            </div>
        </div>
        <div class="form-row">
            <div>
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" required value="<?php echo old('phone'); ?>">
            </div>
            <div>
                <label for="role">Register As</label>
                <select id="role" name="role" required>
                    <option value="">Select role</option>
                    <option value="customer" <?php echo old('role') === 'customer' ? 'selected' : ''; ?>>Customer</option>
                    <option value="provider" <?php echo old('role') === 'provider' ? 'selected' : ''; ?>>Provider</option>
                </select>
            </div>
        </div>
        <div>
            <label for="address">Address</label>
            <textarea id="address" name="address" required><?php echo old('address'); ?></textarea>
        </div>
        <div class="form-row">
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
        </div>
        <button type="submit">Register</button>
    </form>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
