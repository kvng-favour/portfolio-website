<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
$page_title = 'Plans & Pricing | Hospitality Recruitment and Placement';
require __DIR__ . '/includes/header.php';
?>
<section>
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow">Plans &amp; Pricing</span>
            <h1>Choose the plan that fits your hiring needs</h1>
            <p>Pricing shown below comes from <code>subscription_plans</code> and will be manageable from the admin panel — nothing here is hard-coded into checkout logic yet.</p>
        </div>

        <div class="notice" style="max-width:640px;margin:0 auto var(--space-8);">
            <strong>Coming soon —</strong>&nbsp;checkout, payment and automatic activation/renewal are not connected yet. This page currently only displays the plan structure.
        </div>

        <div class="grid-4">
            <?php
            $plans = [
                ['name' => 'Free', 'price' => '₦0', 'cycle' => '/mo', 'features' => ['1 active job post', 'Basic candidate search']],
                ['name' => 'Professional', 'price' => '₦25,000', 'cycle' => '/mo', 'features' => ['10 active job posts', 'AI candidate matching', 'Talent pool access']],
                ['name' => 'Business', 'price' => '₦65,000', 'cycle' => '/mo', 'features' => ['50 active job posts', 'AI candidate matching', 'Priority support']],
                ['name' => 'Enterprise', 'price' => 'Custom', 'cycle' => '', 'features' => ['Unlimited job posts', 'Dedicated account manager', 'Custom integrations']],
            ];
            foreach ($plans as $plan): ?>
            <div class="card">
                <h3><?= e($plan['name']) ?></h3>
                <p style="font-size:var(--fs-2xl);color:var(--color-navy);font-weight:800;margin-bottom:var(--space-3);">
                    <?= e($plan['price']) ?><span style="font-size:var(--fs-sm);color:var(--color-text-muted);font-weight:500;"><?= e($plan['cycle']) ?></span>
                </p>
                <ul style="margin-bottom:var(--space-4);">
                    <?php foreach ($plan['features'] as $f): ?>
                        <li style="padding:var(--space-1) 0;color:var(--color-text-muted);font-size:var(--fs-sm);">&#10003; <?= e($f) ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?= url('register-recruiter.php') ?>" class="btn btn--ghost btn--block">Get Started</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
