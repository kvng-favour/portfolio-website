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
$old = ['full_name' => '', 'email' => '', 'phone' => '', 'location' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Your session expired. Please try again.';
    } else {
        $old['full_name'] = trim($_POST['full_name'] ?? '');
        $old['email']     = trim($_POST['email'] ?? '');
        $old['phone']     = trim($_POST['phone'] ?? '');
        $old['location']  = trim($_POST['location'] ?? '');
        $password         = $_POST['password'] ?? '';
        $confirmPassword  = $_POST['confirm_password'] ?? '';

        if ($old['full_name'] === '') {
            $errors[] = 'Full name is required.';
        }
        if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Enter a valid email address.';
        }
        if ($old['phone'] === '') {
            $errors[] = 'Phone number is required.';
        }
        if ($old['location'] === '') {
            $errors[] = 'Location is required.';
        }
        if (!password_is_valid($password)) {
            $errors[] = 'Password must be at least 8 characters.';
        }
        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }

        if (!$errors) {
            $pdo = hprs_db();
            $existing = find_user_by_email($pdo, $old['email']);
            if ($existing) {
                $errors[] = 'An account with that email already exists. Try logging in instead.';
            } else {
                $pdo->beginTransaction();
                try {
                    $roleId = find_role_id($pdo, 'job_seeker');

                    $stmt = $pdo->prepare(
                        'INSERT INTO users (role_id, full_name, email, phone, password_hash, status, email_verified_at)
                         VALUES (?, ?, ?, ?, ?, ?, NULL)'
                    );
                    $stmt->execute([
                        $roleId,
                        $old['full_name'],
                        $old['email'],
                        $old['phone'],
                        password_hash($password, PASSWORD_DEFAULT),
                        'active', // email verification service isn't wired up yet (Phase 2)
                    ]);
                    $userId = (int) $pdo->lastInsertId();

                    $stmt = $pdo->prepare(
                        'INSERT INTO candidate_profiles (user_id, location, profile_completion)
                         VALUES (?, ?, ?)'
                    );
                    $stmt->execute([$userId, $old['location'], 15]);

                    $pdo->commit();

                    $user = find_user_by_email($pdo, $old['email']);
                    login_user($user);
                    flash_set('success', 'Welcome to Hospitality Recruitment and Placement! Your candidate profile has been created.');
                    header('Location: ' . url('dashboard.php'));
                    exit;
                } catch (Throwable $e) {
                    $pdo->rollBack();
                    $errors[] = 'Something went wrong creating your account. Please try again.';
                }
            }
        }
    }
}

$page_title = 'Register as a Job Seeker | Hospitality Recruitment and Placement';
$extra_stylesheets = ['css/auth.css'];
require __DIR__ . '/includes/header.php';
?>
<section class="auth-section">
    <div class="container">
        <div class="auth-card">
            <div class="auth-card__header">
                <h1>Find your perfect job now!</h1>
                <p>Create your free job seeker account to build a profile and start applying.</p>
            </div>

            <?php foreach ($errors as $error): ?>
                <div class="form-error"><?= e($error) ?></div>
            <?php endforeach; ?>

            <form method="post" action="<?= url('register-job-seeker.php') ?>" novalidate>
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="full_name">Full name</label>
                    <input class="form-control" type="text" id="full_name" name="full_name" value="<?= e($old['full_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input class="form-control" type="email" id="email" name="email" value="<?= e($old['email']) ?>" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone number</label>
                        <input class="form-control" type="tel" id="phone" name="phone" value="<?= e($old['phone']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input class="form-control" type="text" id="location" name="location" placeholder="e.g. Lagos, Nigeria" value="<?= e($old['location']) ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input class="form-control" type="password" id="password" name="password" required minlength="8">
                        <p class="field-hint">At least 8 characters.</p>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm password</label>
                        <input class="form-control" type="password" id="confirm_password" name="confirm_password" required minlength="8">
                    </div>
                </div>
                <button type="submit" class="btn btn--gold btn--block btn--lg">Create My Account</button>
            </form>

            <p class="auth-card__footer">
                Already have an account? <a href="<?= url('login.php') ?>">Login</a><br>
                Recruiting instead? <a href="<?= url('register-recruiter.php') ?>">Register as a Recruiter</a>
            </p>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
