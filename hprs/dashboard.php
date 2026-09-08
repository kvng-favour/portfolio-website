<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

if (!is_logged_in()) {
    header('Location: ' . url('login.php'));
    exit;
}

$page_title = 'Dashboard | Hospitality Recruitment and Placement';
require __DIR__ . '/includes/header.php';

$coming_soon_title = 'Dashboard';
$coming_soon_body  = 'Candidate and recruiter dashboards (applications, pipeline, AI matches, analytics) are built once authentication and profiles are in place.';
$coming_soon_phase = 'Phase 1 (foundation)';
require __DIR__ . '/includes/coming-soon.php';

require __DIR__ . '/includes/footer.php';
