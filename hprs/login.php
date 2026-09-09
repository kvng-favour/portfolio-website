<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    header('Location: ' . url('dashboard.php'));
    exit;
}

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Your session expired. Please try again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $errors[] = 'Enter your email and password.';
        } else {
            $pdo = hprs_db();
            $user = find_user_by_email($pdo, $email);

            if (!$user || !password_verify($password, $user['password_hash'])) {
                $errors[] = 'Incorrect email or password.';
            } elseif ($user['status'] === 'suspended') {
                $errors[] = 'This account has been suspended. Contact support for help.';
            } else {
                login_user($user);
                header('Location: ' . url('dashboard.php'));
                exit;
            }
        }
    }
}

$page_title = 'Login | Hospitality Recruitment and Placement';
$extra_stylesheets = ['css/auth.css'];
require __DIR__ . '/includes/header.php';
?>
<section class="auth-section">
    <div class="container">
        <div class="auth-card">
            <div class="auth-card__header">
                <h1>Welcome back</h1>
                <p>Login to your Hospitality Recruitment and Placement account.</p>
            </div>

            <?php foreach ($errors as $error): ?>
                <div class="form-error"><?= e($error) ?></div>
            <?php endforeach; ?>

            <?php if ($message = flash_get('success')): ?>
                <div class="form-success"><?= e($message) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= url('login.php') ?>" novalidate>
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input class="form-control" type="email" id="email" name="email" value="<?= e($email) ?>" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input class="form-control" type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn--gold btn--block btn--lg">Login</button>
            </form>

            <p class="auth-card__footer">
                New here? <a href="<?= url('register-job-seeker.php') ?>">Find a job</a> or <a href="<?= url('register-recruiter.php') ?>">post a vacancy</a>
            </p>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
