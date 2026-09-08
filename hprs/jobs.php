<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
$page_title = 'Search Jobs | Hospitality Recruitment and Placement';
require __DIR__ . '/includes/header.php';

$coming_soon_title = 'Search Hospitality Jobs';
$coming_soon_body  = 'Full search with filters (location, category, experience, salary, employment type, date posted) and AI-recommended jobs will land here once the Job Listing & Search step of Phase 1 is built.';
$coming_soon_phase = 'Phase 1 (foundation) — next step';
require __DIR__ . '/includes/coming-soon.php';

require __DIR__ . '/includes/footer.php';
