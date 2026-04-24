<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

function base_url(string $path = ''): string
{
    return $path;
}

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function get_flash(): ?array
{
    if (empty($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user']);
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function has_role(string $role): bool
{
    return is_logged_in() && ($_SESSION['user']['role'] ?? '') === $role;
}

function require_login(): void
{
    if (!is_logged_in()) {
        set_flash('error', 'Please log in to continue.');
        redirect('/local-services-booking-platform/auth/login.php');
    }
}

function require_role(string $role): void
{
    require_login();

    if (!has_role($role)) {
        set_flash('error', 'You are not allowed to access that page.');
        redirect('/local-services-booking-platform/index.php');
    }
}

function get_dashboard_path(string $role): string
{
    return match ($role) {
        'admin' => '/local-services-booking-platform/admin/dashboard.php',
        'provider' => '/local-services-booking-platform/provider/dashboard.php',
        default => '/local-services-booking-platform/customer/dashboard.php',
    };
}

function old(string $key, string $default = ''): string
{
    return h($_POST[$key] ?? $default);
}

function post_value(string $key): string
{
    return trim($_POST[$key] ?? '');
}

function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function fetch_all_assoc(mysqli_stmt $stmt): array
{
    $result = $stmt->get_result();
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function fetch_one_assoc(mysqli_stmt $stmt): ?array
{
    $result = $stmt->get_result();
    return $result ? ($result->fetch_assoc() ?: null) : null;
}

function get_categories(): array
{
    global $mysqli;

    $result = $mysqli->query('SELECT * FROM categories ORDER BY name ASC');
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function get_service_count(): int
{
    global $mysqli;

    $result = $mysqli->query("SELECT COUNT(*) AS total FROM services WHERE status = 'active'");
    $row = $result ? $result->fetch_assoc() : ['total' => 0];
    return (int) ($row['total'] ?? 0);
}

function get_user_count_by_role(string $role): int
{
    global $mysqli;

    $stmt = $mysqli->prepare('SELECT COUNT(*) AS total FROM users WHERE role = ?');
    $stmt->bind_param('s', $role);
    $stmt->execute();
    $row = fetch_one_assoc($stmt) ?? ['total' => 0];
    $stmt->close();
    return (int) $row['total'];
}

function get_total_count(string $table): int
{
    global $mysqli;

    $allowed = ['users', 'categories', 'services', 'bookings', 'contact_messages'];
    if (!in_array($table, $allowed, true)) {
        return 0;
    }

    $result = $mysqli->query("SELECT COUNT(*) AS total FROM {$table}");
    $row = $result ? $result->fetch_assoc() : ['total' => 0];
    return (int) ($row['total'] ?? 0);
}

function get_status_badge_class(string $status): string
{
    return match ($status) {
        'accepted', 'active', 'completed' => 'badge-success',
        'rejected', 'inactive' => 'badge-danger',
        default => 'badge-warning',
    };
}

function booking_status_options(): array
{
    return ['pending', 'accepted', 'rejected', 'completed'];
}
?>
