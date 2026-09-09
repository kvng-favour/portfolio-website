<?php
/**
 * Auth/session helpers. Requires config.php (session already started) and
 * config/database.php to be included first.
 */

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return is_string($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

function flash_set(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function flash_get(string $key): ?string
{
    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $message;
}

function find_role_id(PDO $pdo, string $roleName): int
{
    $stmt = $pdo->prepare('SELECT id FROM roles WHERE name = ?');
    $stmt->execute([$roleName]);
    $id = $stmt->fetchColumn();
    if ($id === false) {
        throw new RuntimeException("Unknown role: $roleName");
    }
    return (int) $id;
}

function find_user_by_email(PDO $pdo, string $email): ?array
{
    $stmt = $pdo->prepare(
        'SELECT u.*, r.name AS role_name FROM users u
         JOIN roles r ON r.id = u.role_id
         WHERE u.email = ? LIMIT 1'
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    return $user ?: null;
}

function login_user(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id'    => (int) $user['id'],
        'name'  => $user['full_name'],
        'email' => $user['email'],
        'role'  => $user['role_name'],
    ];
}

function logout_user(): void
{
    $_SESSION = [];
    session_regenerate_id(true);
}

function current_role(): ?string
{
    return current_user()['role'] ?? null;
}

function require_login(): void
{
    if (!is_logged_in()) {
        flash_set('error', 'Please log in to continue.');
        header('Location: ' . url('login.php'));
        exit;
    }
}

function require_role(string $role): void
{
    require_login();
    if (current_role() !== $role) {
        http_response_code(403);
        exit('You do not have access to this page.');
    }
}

/**
 * Very small password rule set: at least 8 characters. Kept intentionally
 * simple for Phase 1 — not a place to invent extra requirements.
 */
function password_is_valid(string $password): bool
{
    return strlen($password) >= 8;
}
