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
$old = ['full_name' => '', 'email' => '', 'phone' => '', 'company_name' => '', 'industry' => '', 'location' => ''];

$industries = ['Hotel', 'Restaurant', 'Resort', 'Catering', 'Events', 'Hospitality Group', 'Other'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Your session expired. Please try again.';
    } else {
        $old['full_name']    = trim($_POST['full_name'] ?? '');
        $old['email']        = trim($_POST['email'] ?? '');
        $old['phone']        = trim($_POST['phone'] ?? '');
        $old['company_name'] = trim($_POST['company_name'] ?? '');
        $old['industry']     = trim($_POST['industry'] ?? '');
        $old['location']     = trim($_POST['location'] ?? '');
        $password            = $_POST['password'] ?? '';
        $confirmPassword     = $_POST['confirm_password'] ?? '';

        if ($old['full_name'] === '') {
            $errors[] = 'Your full name is required.';
        }
        if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Enter a valid email address.';
        }
        if ($old['phone'] === '') {
            $errors[] = 'Phone number is required.';
        }
        if ($old['company_name'] === '') {
            $errors[] = 'Company name is required.';
        }
        if (!in_array($old['industry'], $industries, true)) {
            $errors[] = 'Select a valid industry.';
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
                    $roleId = find_role_id($pdo, 'recruiter');

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
                        'INSERT INTO companies (owner_user_id, name, industry, location, verification_status)
                         VALUES (?, ?, ?, ?, ?)'
                    );
                    $stmt->execute([$userId, $old['company_name'], $old['industry'], $old['location'], 'unverified']);
                    $companyId = (int) $pdo->lastInsertId();

                    $stmt = $pdo->prepare(
                        'INSERT INTO recruiter_profiles (user_id, company_id) VALUES (?, ?)'
                    );
                    $stmt->execute([$userId, $companyId]);

                    // Every new company starts on the Free plan until they upgrade.
                    $stmt = $pdo->prepare(
                        'INSERT INTO subscriptions (company_id, plan_id, status, started_at, expires_at)
                         VALUES (?, (SELECT id FROM subscription_plans WHERE name = "Free" LIMIT 1), "active", NOW(), NULL)'
                    );
                    $stmt->execute([$companyId]);

                    $pdo->commit();

                    $user = find_user_by_email($pdo, $old['email']);
                    login_user($user);
                    flash_set('success', 'Welcome to Hospitality Recruitment and Placement! Your company profile has been created on the Free plan.');
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

$page_title = 'Register as a Recruiter | Hospitality Recruitment and Placement';
$extra_stylesheets = ['css/auth.css'];
require __DIR__ . '/includes/header.php';
?>
<section class="auth-section">
    <div class="container">
        <div class="auth-card auth-card--wide">
            <div class="auth-card__header">
                <h1>Find the perfect candidate now!</h1>
                <p>Create your recruiter account and start posting hospitality vacancies.</p>
            </div>

            <?php foreach ($errors as $error): ?>
                <div class="form-error"><?= e($error) ?></div>
            <?php endforeach; ?>

            <form method="post" action="<?= url('register-recruiter.php') ?>" novalidate>
                <?= csrf_field() ?>
                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name">Your full name</label>
                        <input class="form-control" type="text" id="full_name" name="full_name" value="<?= e($old['full_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Work email</label>
                        <input class="form-control" type="email" id="email" name="email" value="<?= e($old['email']) ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone number</label>
                        <input class="form-control" type="tel" id="phone" name="phone" value="<?= e($old['phone']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Company location</label>
                        <input class="form-control" type="text" id="location" name="location" placeholder="e.g. Lagos, Nigeria" value="<?= e($old['location']) ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="company_name">Company name</label>
                        <input class="form-control" type="text" id="company_name" name="company_name" value="<?= e($old['company_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="industry">Industry</label>
                        <select class="form-control" id="industry" name="industry" required>
                            <option value="">Select industry</option>
                            <?php foreach ($industries as $industry): ?>
                                <option value="<?= e($industry) ?>" <?= $old['industry'] === $industry ? 'selected' : '' ?>><?= e($industry) ?></option>
                            <?php endforeach; ?>
                        </select>
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
                <button type="submit" class="btn btn--navy btn--block btn--lg">Create Recruiter Account</button>
            </form>

            <p class="auth-card__footer">
                Already have an account? <a href="<?= url('login.php') ?>">Login</a><br>
                Looking for a job instead? <a href="<?= url('register-job-seeker.php') ?>">Register as a Job Seeker</a>
            </p>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
