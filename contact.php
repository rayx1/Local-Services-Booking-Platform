<?php
require_once __DIR__ . '/includes/functions.php';

$errors = [];

if (is_post()) {
    $name = post_value('name');
    $email = post_value('email');
    $subject = post_value('subject');
    $message = post_value('message');

    if ($name === '' || $email === '' || $subject === '' || $message === '') {
        $errors[] = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (!$errors) {
        $stmt = $mysqli->prepare('INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $name, $email, $subject, $message);
        $stmt->execute();
        $stmt->close();
        set_flash('success', 'Your message has been sent successfully.');
        redirect('/local-services-booking-platform/contact.php');
    }
}

$pageTitle = 'Contact - Local Services Booking Platform';
require_once __DIR__ . '/includes/header.php';
?>

<section class="dashboard-grid">
    <div class="form-card">
        <h2>Contact Us</h2>
        <p class="muted">Send your query or feedback using the form below.</p>
        <?php if ($errors): ?>
            <div class="alert alert-error"><?php echo h(implode(' ', $errors)); ?></div>
        <?php endif; ?>
        <form method="post" data-validate="true">
            <div>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required value="<?php echo old('name'); ?>">
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?php echo old('email'); ?>">
            </div>
            <div>
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required value="<?php echo old('subject'); ?>">
            </div>
            <div>
                <label for="message">Message</label>
                <textarea id="message" name="message" required><?php echo old('message'); ?></textarea>
            </div>
            <button type="submit">Send Message</button>
        </form>
    </div>
    <aside class="info-card">
        <h3>Project Contact Info</h3>
        <p>Email: support@localservices.test</p>
        <p>Phone: +91 90000 00000</p>
        <p>Office Hours: Monday to Saturday, 9 AM to 6 PM</p>
    </aside>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
