<?php
/**
 * Renders a consistent "coming soon" body for pages not yet built.
 * Set $coming_soon_title / $coming_soon_body / $coming_soon_phase before
 * including this file (after header.php, before footer.php).
 */
?>
<section>
    <div class="container" style="max-width:720px;">
        <div class="section-heading" style="margin-bottom:var(--space-5);">
            <span class="eyebrow">Coming Soon</span>
            <h1><?= e($coming_soon_title) ?></h1>
        </div>
        <div class="notice">
            <div>
                <p style="margin-bottom:var(--space-2);color:#7a5b00;"><?= e($coming_soon_body) ?></p>
                <p style="margin:0;color:#7a5b00;font-weight:600;">Status: Prototype layout only — no live data or backend logic is connected yet. Scheduled for <?= e($coming_soon_phase) ?>.</p>
            </div>
        </div>
        <p style="margin-top:var(--space-6);"><a href="<?= url() ?>" class="btn btn--ghost">&larr; Back to Home</a></p>
    </div>
</section>
