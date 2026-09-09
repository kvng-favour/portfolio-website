<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

require_role('job_seeker');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('jobs.php'));
    exit;
}

$jobId = (int) ($_POST['job_id'] ?? 0);

if (!verify_csrf() || !$jobId) {
    flash_set('error', 'Something went wrong. Please try again.');
    header('Location: ' . url('jobs.php'));
    exit;
}

$pdo = hprs_db();
$user = current_user();

$stmt = $pdo->prepare('SELECT id FROM candidate_profiles WHERE user_id = ? LIMIT 1');
$stmt->execute([$user['id']]);
$candidateProfileId = $stmt->fetchColumn();

if (!$candidateProfileId) {
    flash_set('error', 'We could not find your candidate profile.');
    header('Location: ' . url('jobs.php'));
    exit;
}

$stmt = $pdo->prepare('SELECT id, status FROM jobs WHERE id = ? LIMIT 1');
$stmt->execute([$jobId]);
$job = $stmt->fetch();

if (!$job || $job['status'] !== 'open') {
    flash_set('error', 'This job is no longer accepting applications.');
    header('Location: ' . url('jobs.php'));
    exit;
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO applications (job_id, candidate_id, status) VALUES (?, ?, "applied")'
    );
    $stmt->execute([$jobId, $candidateProfileId]);

    $pdo->prepare('UPDATE jobs SET applications_count = applications_count + 1 WHERE id = ?')
        ->execute([$jobId]);

    flash_set('success', 'Application submitted! You can track its status from your dashboard.');
} catch (PDOException $e) {
    // Unique key on (job_id, candidate_id) — duplicate applies land here.
    flash_set('error', 'You have already applied to this job.');
}

header('Location: ' . url('job.php?id=' . $jobId));
exit;
