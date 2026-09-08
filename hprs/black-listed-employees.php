<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
$page_title = 'Black Listed Employees | Hospitality Recruitment and Placement';
require __DIR__ . '/includes/header.php';

$coming_soon_title = 'Black Listed Employees';
$coming_soon_body  = 'This preserves the existing Black Listed Employees concept, redesigned with restricted access, evidence records, reviewer sign-off, a full audit trail, and a dispute/appeal process. No automatic or arbitrary blacklisting will be allowed — every entry requires human review.';
$coming_soon_phase = 'Phase 3 (verification & moderation workflows)';
require __DIR__ . '/includes/coming-soon.php';

require __DIR__ . '/includes/footer.php';
