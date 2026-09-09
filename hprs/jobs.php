<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$pdo = hprs_db();

$keyword  = trim($_GET['q'] ?? '');
$location = trim($_GET['location'] ?? '');
$category = trim($_GET['category'] ?? '');

$where = ['j.status = "open"'];
$params = [];

if ($keyword !== '') {
    $where[] = '(j.title LIKE ? OR j.description LIKE ?)';
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
}
if ($location !== '') {
    $where[] = 'j.location LIKE ?';
    $params[] = "%$location%";
}
if ($category !== '') {
    $where[] = 'j.category = ?';
    $params[] = $category;
}

$sql = 'SELECT j.*, c.name AS company_name
        FROM jobs j JOIN companies c ON c.id = j.company_id
        WHERE ' . implode(' AND ', $where) . '
        ORDER BY j.created_at DESC
        LIMIT 50';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jobs = $stmt->fetchAll();

$stmt = $pdo->query('SELECT DISTINCT category FROM jobs WHERE category IS NOT NULL ORDER BY category');
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

$page_title = 'Search Jobs | Hospitality Recruitment and Placement';
$extra_stylesheets = ['css/home.css'];
require __DIR__ . '/includes/header.php';
?>
<section>
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow">Search</span>
            <h1>Find Hospitality Jobs</h1>
        </div>

        <form method="get" action="<?= url('jobs.php') ?>" class="card" style="margin-bottom:var(--space-8);">
            <div class="form-row" style="grid-template-columns: 2fr 1fr 1fr auto; align-items:end;">
                <div class="form-group" style="margin-bottom:0;">
                    <label for="q">Keyword</label>
                    <input class="form-control" type="text" id="q" name="q" placeholder="Job title or keyword" value="<?= e($keyword) ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="location">Location</label>
                    <input class="form-control" type="text" id="location" name="location" placeholder="City" value="<?= e($location) ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="category">Category</label>
                    <select class="form-control" id="category" name="category">
                        <option value="">All categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= e($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn--navy">Search</button>
            </div>
        </form>

        <?php if (!$jobs): ?>
            <div class="notice">
                No open jobs match your search yet.
                <?php if (!current_role() || current_role() !== 'recruiter'): ?>
                    Check back soon, or <a href="<?= url('register-recruiter.php') ?>">post one as a recruiter</a>.
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p style="color:var(--color-text-muted);margin-bottom:var(--space-5);"><?= count($jobs) ?> open role<?= count($jobs) === 1 ? '' : 's' ?> found.</p>
            <div class="grid-3">
                <?php foreach ($jobs as $job): ?>
                    <div class="card job-card">
                        <div class="job-card__top">
                            <div>
                                <h3 class="job-card__title"><?= e($job['title']) ?></h3>
                                <p class="job-card__company"><?= e($job['company_name']) ?> &middot; <?= e($job['location']) ?></p>
                            </div>
                            <span class="badge badge--open">Open</span>
                        </div>
                        <div class="job-card__meta">
                            <?php if ($job['salary_min'] || $job['salary_max']): ?>
                                <span>&#8358;<?= number_format((int) $job['salary_min']) ?>&ndash;&#8358;<?= number_format((int) $job['salary_max']) ?></span>
                            <?php endif; ?>
                            <?php if ($job['experience_required']): ?>
                                <span><?= (int) $job['experience_required'] ?>+ yrs experience</span>
                            <?php endif; ?>
                            <span><?= e(ucwords(str_replace('_', ' ', $job['employment_type']))) ?></span>
                        </div>
                        <div class="job-card__footer">
                            <span class="badge badge--soon"><?= e($job['category']) ?></span>
                            <a href="<?= url('job.php?id=' . $job['id']) ?>" class="btn btn--ghost">View &amp; Apply</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
