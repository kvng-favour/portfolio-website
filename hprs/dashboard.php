<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

require_login();
$user = current_user();
$pdo = hprs_db();

$isRecruiter = current_role() === 'recruiter';

if ($isRecruiter) {
    $stmt = $pdo->prepare(
        'SELECT c.id AS company_id, c.name AS company_name, c.verification_status,
                sp.name AS plan_name, s.status AS subscription_status
         FROM companies c
         LEFT JOIN subscriptions s ON s.company_id = c.id AND s.status = "active"
         LEFT JOIN subscription_plans sp ON sp.id = s.plan_id
         WHERE c.owner_user_id = ? LIMIT 1'
    );
    $stmt->execute([$user['id']]);
    $company = $stmt->fetch();

    $activeJobs = 0;
    $totalApplications = 0;
    if ($company) {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM jobs WHERE company_id = ? AND status = "open"');
        $stmt->execute([$company['company_id']]);
        $activeJobs = (int) $stmt->fetchColumn();

        $stmt = $pdo->prepare(
            'SELECT COUNT(*) FROM applications a JOIN jobs j ON j.id = a.job_id WHERE j.company_id = ?'
        );
        $stmt->execute([$company['company_id']]);
        $totalApplications = (int) $stmt->fetchColumn();
    }

    $navItems = [
        ['label' => 'Dashboard', 'href' => url('dashboard.php'), 'active' => true],
        ['label' => 'Jobs', 'href' => url('jobs.php'), 'active' => false],
        ['label' => 'Candidates', 'soon' => true],
        ['label' => 'AI Matching', 'soon' => true],
        ['label' => 'Applications', 'soon' => true],
        ['label' => 'Interviews', 'soon' => true],
        ['label' => 'Messages', 'soon' => true],
        ['label' => 'Talent Pool', 'soon' => true],
        ['label' => 'Company Profile', 'soon' => true],
        ['label' => 'Subscription', 'href' => url('plans-pricing.php'), 'active' => false],
        ['label' => 'Settings', 'soon' => true],
    ];
} else {
    $stmt = $pdo->prepare(
        'SELECT id, profile_completion, professional_title FROM candidate_profiles WHERE user_id = ? LIMIT 1'
    );
    $stmt->execute([$user['id']]);
    $profile = $stmt->fetch();

    $counts = ['applied' => 0, 'screening' => 0, 'shortlisted' => 0, 'interview' => 0, 'offer' => 0, 'hired' => 0];
    if ($profile) {
        $stmt = $pdo->prepare('SELECT status, COUNT(*) AS c FROM applications WHERE candidate_id = ? GROUP BY status');
        $stmt->execute([$profile['id']]);
        foreach ($stmt->fetchAll() as $row) {
            $counts[$row['status']] = (int) $row['c'];
        }
    }

    $navItems = [
        ['label' => 'Dashboard', 'href' => url('dashboard.php'), 'active' => true],
        ['label' => 'My Profile', 'soon' => true],
        ['label' => 'Recommended Jobs', 'soon' => true],
        ['label' => 'My Applications', 'soon' => true],
        ['label' => 'Saved Jobs', 'soon' => true],
        ['label' => 'Messages', 'soon' => true],
        ['label' => 'Interview Schedule', 'soon' => true],
        ['label' => 'Career Resources', 'soon' => true],
        ['label' => 'Settings', 'soon' => true],
    ];
}

$page_title = 'Dashboard | Hospitality Recruitment and Placement';
$extra_stylesheets = ['css/dashboard.css'];
require __DIR__ . '/includes/header.php';
?>
<section class="dash">
    <div class="container dash__inner">
        <aside class="dash-sidebar">
            <div class="dash-sidebar__user">
                <div class="dash-sidebar__avatar"><?= e(strtoupper(substr($user['name'], 0, 1))) ?></div>
                <div>
                    <strong><?= e($isRecruiter ? ($company['company_name'] ?? $user['name']) : $user['name']) ?></strong>
                    <span><?= $isRecruiter ? 'Recruiter' : 'Job Seeker' ?></span>
                </div>
            </div>
            <nav class="dash-nav">
                <?php foreach ($navItems as $item): ?>
                    <?php if (!empty($item['soon'])): ?>
                        <span class="dash-nav__item dash-nav__item--soon"><?= e($item['label']) ?> <em>Soon</em></span>
                    <?php else: ?>
                        <a class="dash-nav__item <?= !empty($item['active']) ? 'is-active' : '' ?>" href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
                <a class="dash-nav__item dash-nav__item--logout" href="<?= url('logout.php') ?>">Log Out</a>
            </nav>
        </aside>

        <main class="dash-main">
            <?php if ($isRecruiter): ?>
                <h1>Welcome back, <?= e($company['company_name'] ?? $user['name']) ?> &#128075;</h1>
                <p>Here's what's happening with your recruitment today.</p>

                <?php if (!$company): ?>
                    <div class="notice">Your company profile could not be found. Please contact support.</div>
                <?php else: ?>
                    <div class="grid-4 dash-stats">
                        <div class="card stat-card"><div class="stat-card__value"><?= $activeJobs ?></div><div class="stat-card__label">Active Jobs</div></div>
                        <div class="card stat-card"><div class="stat-card__value"><?= $totalApplications ?></div><div class="stat-card__label">Applications</div></div>
                        <div class="card stat-card"><div class="stat-card__value">&mdash;</div><div class="stat-card__label">AI Matched Candidates<br><span class="badge badge--soon">Phase 2</span></div></div>
                        <div class="card stat-card"><div class="stat-card__value">&mdash;</div><div class="stat-card__label">Interviews<br><span class="badge badge--soon">Phase 3</span></div></div>
                    </div>

                    <div class="card" style="margin-top:var(--space-6);">
                        <h3>Subscription</h3>
                        <p>Plan: <strong><?= e($company['plan_name'] ?? 'Free') ?></strong> &middot; Status: <span class="badge badge--open"><?= e($company['subscription_status'] ?? 'active') ?></span></p>
                        <a href="<?= url('plans-pricing.php') ?>" class="btn btn--ghost">Manage Plan</a>
                    </div>

                    <div class="dash-quick-actions">
                        <a href="<?= url('jobs.php') ?>" class="btn btn--navy">Post a Job</a>
                        <span class="btn btn--ghost" aria-disabled="true">Search Candidates <span class="badge badge--soon">Soon</span></span>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <h1>Good day, <?= e($user['name']) ?> &#128075;</h1>
                <p>Here's your job search progress.</p>

                <div class="card" style="margin-bottom:var(--space-6);">
                    <h3>Profile Strength</h3>
                    <div class="progress-bar"><div class="progress-bar__fill" style="width: <?= (int) ($profile['profile_completion'] ?? 0) ?>%;"></div></div>
                    <p><?= (int) ($profile['profile_completion'] ?? 0) ?>% complete &mdash; the full profile builder (experience, skills, CV upload) is the next stage to build.</p>
                </div>

                <div class="grid-4 dash-stats">
                    <div class="card stat-card"><div class="stat-card__value"><?= $counts['applied'] ?></div><div class="stat-card__label">Applied</div></div>
                    <div class="card stat-card"><div class="stat-card__value"><?= $counts['interview'] ?></div><div class="stat-card__label">Interviews</div></div>
                    <div class="card stat-card"><div class="stat-card__value"><?= $counts['offer'] ?></div><div class="stat-card__label">Offers</div></div>
                    <div class="card stat-card"><div class="stat-card__value"><?= $counts['hired'] ?></div><div class="stat-card__label">Hired</div></div>
                </div>

                <div class="notice" style="margin-top:var(--space-6);">AI-recommended jobs will appear here once the AI matching engine (Phase 2) is connected. Nothing fake is shown in the meantime.</div>

                <div class="dash-quick-actions">
                    <a href="<?= url('jobs.php') ?>" class="btn btn--gold">Search Jobs</a>
                    <span class="btn btn--ghost" aria-disabled="true">Complete Profile <span class="badge badge--soon">Soon</span></span>
                </div>
            <?php endif; ?>
        </main>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
