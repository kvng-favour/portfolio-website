<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

require_role('recruiter');
$user = current_user();
$pdo = hprs_db();

$stmt = $pdo->prepare('SELECT id, name FROM companies WHERE owner_user_id = ? LIMIT 1');
$stmt->execute([$user['id']]);
$company = $stmt->fetch();

if (!$company) {
    http_response_code(500);
    exit('No company profile found for this account.');
}

// Free plan is capped at 1 active job — enforce it before showing the form.
$stmt = $pdo->prepare(
    'SELECT sp.max_active_jobs FROM subscriptions s
     JOIN subscription_plans sp ON sp.id = s.plan_id
     WHERE s.company_id = ? AND s.status = "active" LIMIT 1'
);
$stmt->execute([$company['id']]);
$maxActiveJobs = $stmt->fetchColumn();
$maxActiveJobs = $maxActiveJobs === false ? null : $maxActiveJobs;

$stmt = $pdo->prepare('SELECT COUNT(*) FROM jobs WHERE company_id = ? AND status = "open"');
$stmt->execute([$company['id']]);
$activeJobCount = (int) $stmt->fetchColumn();

$planLimitReached = $maxActiveJobs !== null && $activeJobCount >= (int) $maxActiveJobs;

$categories = ['Front Office', 'Food & Beverage', 'Culinary', 'Housekeeping', 'Guest Relations', 'Management', 'Events', 'Other'];
$employmentTypes = ['full_time' => 'Full-time', 'part_time' => 'Part-time', 'contract' => 'Contract', 'temporary' => 'Temporary', 'internship' => 'Internship'];

$errors = [];
$old = [
    'title' => '', 'category' => '', 'description' => '', 'responsibilities' => '',
    'requirements' => '', 'location' => '', 'employment_type' => 'full_time',
    'salary_min' => '', 'salary_max' => '', 'experience_required' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$planLimitReached) {
    if (!verify_csrf()) {
        $errors[] = 'Your session expired. Please try again.';
    } else {
        foreach ($old as $key => $_) {
            $old[$key] = trim($_POST[$key] ?? '');
        }

        if ($old['title'] === '') {
            $errors[] = 'Job title is required.';
        }
        if (!in_array($old['category'], $categories, true)) {
            $errors[] = 'Select a valid category.';
        }
        if ($old['description'] === '') {
            $errors[] = 'Job description is required.';
        }
        if ($old['location'] === '') {
            $errors[] = 'Location is required.';
        }
        if (!array_key_exists($old['employment_type'], $employmentTypes)) {
            $errors[] = 'Select a valid employment type.';
        }
        if ($old['salary_min'] !== '' && !ctype_digit($old['salary_min'])) {
            $errors[] = 'Minimum salary must be a whole number.';
        }
        if ($old['salary_max'] !== '' && !ctype_digit($old['salary_max'])) {
            $errors[] = 'Maximum salary must be a whole number.';
        }
        if ($old['experience_required'] !== '' && !ctype_digit($old['experience_required'])) {
            $errors[] = 'Years of experience must be a whole number.';
        }

        if (!$errors) {
            $stmt = $pdo->prepare(
                'INSERT INTO jobs (company_id, posted_by_user_id, title, category, description, responsibilities,
                                   requirements, location, employment_type, salary_min, salary_max,
                                   experience_required, status)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "open")'
            );
            $stmt->execute([
                $company['id'],
                $user['id'],
                $old['title'],
                $old['category'],
                $old['description'],
                $old['responsibilities'] ?: null,
                $old['requirements'] ?: null,
                $old['location'],
                $old['employment_type'],
                $old['salary_min'] !== '' ? (int) $old['salary_min'] : null,
                $old['salary_max'] !== '' ? (int) $old['salary_max'] : null,
                $old['experience_required'] !== '' ? (int) $old['experience_required'] : null,
            ]);
            $jobId = (int) $pdo->lastInsertId();

            flash_set('success', 'Your job has been posted and is now visible to candidates.');
            header('Location: ' . url('job.php?id=' . $jobId));
            exit;
        }
    }
}

$page_title = 'Post a Job | Hospitality Recruitment and Placement';
$extra_stylesheets = ['css/auth.css', 'css/dashboard.css'];
require __DIR__ . '/includes/header.php';
?>
<section class="dash">
    <div class="container" style="max-width:760px;">
        <h1>Post a Job</h1>
        <p>Posting as <strong><?= e($company['name']) ?></strong>.</p>

        <?php if ($planLimitReached): ?>
            <div class="notice">
                You've reached your Free plan limit of <?= (int) $maxActiveJobs ?> active job post<?= $maxActiveJobs == 1 ? '' : 's' ?>.
                <a href="<?= url('plans-pricing.php') ?>">Upgrade your plan</a> to post more.
            </div>
        <?php else: ?>
            <?php foreach ($errors as $error): ?>
                <div class="form-error"><?= e($error) ?></div>
            <?php endforeach; ?>

            <div class="card" style="margin-top:var(--space-5);">
                <form method="post" action="<?= url('post-job.php') ?>" novalidate>
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="title">Job title</label>
                        <input class="form-control" type="text" id="title" name="title" placeholder="e.g. Front Office Manager" value="<?= e($old['title']) ?>" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="">Select category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= e($category) ?>" <?= $old['category'] === $category ? 'selected' : '' ?>><?= e($category) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="employment_type">Employment type</label>
                            <select class="form-control" id="employment_type" name="employment_type" required>
                                <?php foreach ($employmentTypes as $value => $label): ?>
                                    <option value="<?= e($value) ?>" <?= $old['employment_type'] === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input class="form-control" type="text" id="location" name="location" placeholder="e.g. Lagos, Nigeria" value="<?= e($old['location']) ?>" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="salary_min">Salary min (&#8358;/month, optional)</label>
                            <input class="form-control" type="number" min="0" id="salary_min" name="salary_min" value="<?= e($old['salary_min']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="salary_max">Salary max (&#8358;/month, optional)</label>
                            <input class="form-control" type="number" min="0" id="salary_max" name="salary_max" value="<?= e($old['salary_max']) ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="experience_required">Years of experience required (optional)</label>
                        <input class="form-control" type="number" min="0" id="experience_required" name="experience_required" value="<?= e($old['experience_required']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Job description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required><?= e($old['description']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="responsibilities">Key responsibilities (optional, one per line)</label>
                        <textarea class="form-control" id="responsibilities" name="responsibilities" rows="4"><?= e($old['responsibilities']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="requirements">Requirements (optional, one per line)</label>
                        <textarea class="form-control" id="requirements" name="requirements" rows="4"><?= e($old['requirements']) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn--navy btn--lg btn--block">Post Job</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
