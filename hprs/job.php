<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$pdo = hprs_db();
$jobId = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare(
    'SELECT j.*, c.name AS company_name, c.location AS company_location
     FROM jobs j JOIN companies c ON c.id = j.company_id
     WHERE j.id = ? LIMIT 1'
);
$stmt->execute([$jobId]);
$job = $stmt->fetch();

if (!$job) {
    http_response_code(404);
    $page_title = 'Job Not Found | Hospitality Recruitment and Placement';
    require __DIR__ . '/includes/header.php';
    echo '<section class="container" style="padding:var(--space-10) 0;"><h1>Job not found</h1><p><a href="' . url('jobs.php') . '" class="btn btn--ghost">&larr; Back to job search</a></p></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$alreadyApplied = false;
$candidateProfileId = null;
if (current_role() === 'job_seeker') {
    $stmt = $pdo->prepare('SELECT id FROM candidate_profiles WHERE user_id = ? LIMIT 1');
    $stmt->execute([current_user()['id']]);
    $candidateProfileId = $stmt->fetchColumn() ?: null;

    if ($candidateProfileId) {
        $stmt = $pdo->prepare('SELECT 1 FROM applications WHERE job_id = ? AND candidate_id = ? LIMIT 1');
        $stmt->execute([$jobId, $candidateProfileId]);
        $alreadyApplied = (bool) $stmt->fetchColumn();
    }
}

$page_title = e($job['title']) . ' at ' . e($job['company_name']) . ' | Hospitality Recruitment and Placement';
$extra_stylesheets = ['css/home.css', 'css/auth.css'];
require __DIR__ . '/includes/header.php';

if ($message = flash_get('success')) {
    echo '<div class="container" style="margin-top:var(--space-6);"><div class="form-success">' . e($message) . '</div></div>';
}
if ($error = flash_get('error')) {
    echo '<div class="container" style="margin-top:var(--space-6);"><div class="form-error">' . e($error) . '</div></div>';
}
?>
<section>
    <div class="container" style="max-width:820px;">
        <p><a href="<?= url('jobs.php') ?>">&larr; Back to job search</a></p>

        <div class="card" style="margin-top:var(--space-4);">
            <div class="job-card__top">
                <div>
                    <h1 style="margin-bottom:4px;"><?= e($job['title']) ?></h1>
                    <p class="job-card__company"><?= e($job['company_name']) ?> &middot; <?= e($job['location']) ?></p>
                </div>
                <span class="badge badge--<?= $job['status'] === 'open' ? 'open' : 'soon' ?>"><?= e(ucfirst($job['status'])) ?></span>
            </div>

            <div class="job-card__meta" style="margin: var(--space-4) 0;">
                <?php if ($job['salary_min'] || $job['salary_max']): ?>
                    <span>&#8358;<?= number_format((int) $job['salary_min']) ?>&ndash;&#8358;<?= number_format((int) $job['salary_max']) ?>/month</span>
                <?php endif; ?>
                <?php if ($job['experience_required']): ?>
                    <span><?= (int) $job['experience_required'] ?>+ yrs experience</span>
                <?php endif; ?>
                <span><?= e(ucwords(str_replace('_', ' ', $job['employment_type']))) ?></span>
                <span><?= e($job['category']) ?></span>
                <span>Posted <?= e(date('M j, Y', strtotime($job['created_at']))) ?></span>
            </div>

            <h3>About this role</h3>
            <p style="white-space:pre-line;"><?= e($job['description']) ?></p>

            <?php if ($job['responsibilities']): ?>
                <h3>Key Responsibilities</h3>
                <ul style="margin-bottom:var(--space-4);">
                    <?php foreach (preg_split('/\r\n|\r|\n/', trim($job['responsibilities'])) as $line): ?>
                        <?php if (trim($line) !== ''): ?>
                            <li style="padding:4px 0;color:var(--color-text-muted);">&#8226; <?= e($line) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if ($job['requirements']): ?>
                <h3>Requirements</h3>
                <ul style="margin-bottom:var(--space-4);">
                    <?php foreach (preg_split('/\r\n|\r|\n/', trim($job['requirements'])) as $line): ?>
                        <?php if (trim($line) !== ''): ?>
                            <li style="padding:4px 0;color:var(--color-text-muted);">&#8226; <?= e($line) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div style="margin-top:var(--space-6);">
                <?php if (!is_logged_in()): ?>
                    <a href="<?= url('login.php') ?>" class="btn btn--gold btn--lg">Login to Apply</a>
                    <a href="<?= url('register-job-seeker.php') ?>" class="btn btn--ghost btn--lg">Create an Account</a>
                <?php elseif (current_role() !== 'job_seeker'): ?>
                    <p style="color:var(--color-text-muted);">Only job seeker accounts can apply to vacancies.</p>
                <?php elseif ($alreadyApplied): ?>
                    <span class="badge badge--match" style="padding:0.65rem 1.25rem;font-size:var(--fs-sm);">&#10003; You've already applied to this role</span>
                <?php elseif ($job['status'] !== 'open'): ?>
                    <p style="color:var(--color-text-muted);">This role is no longer accepting applications.</p>
                <?php else: ?>
                    <form method="post" action="<?= url('apply.php') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="job_id" value="<?= (int) $job['id'] ?>">
                        <button type="submit" class="btn btn--gold btn--lg">Apply Now</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
