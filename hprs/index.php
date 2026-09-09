<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$stmt = hprs_db()->query(
    'SELECT j.*, c.name AS company_name FROM jobs j
     JOIN companies c ON c.id = j.company_id
     WHERE j.status = "open" ORDER BY j.created_at DESC LIMIT 3'
);
$featuredJobs = $stmt->fetchAll();

$page_title = 'Hospitality Recruitment and Placement | Hospitality Jobs & Talent';
$page_description = 'Hospitality Recruitment and Placement connects hospitality job seekers with hotels, restaurants, resorts and hospitality employers across Nigeria.';
$extra_stylesheets = ['css/home.css'];

require __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container hero__inner">
        <div>
            <h1>Connecting Great Talent<br><span class="accent">with Great Opportunities</span></h1>
            <p class="hero__lead">The leading hospitality recruitment platform connecting qualified professionals with top employers worldwide.</p>

            <div class="hero__paths">
                <div class="path-card">
                    <div class="path-card__icon" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/><path d="M4 20c0-4 3.6-6 8-6s8 2 8 6" stroke="currentColor" stroke-width="2"/></svg>
                    </div>
                    <h3>I'm a Job Seeker</h3>
                    <p>Find your next career opportunity in the hospitality industry.</p>
                    <a href="<?= url('register-job-seeker.php') ?>" class="btn btn--navy">Find a Job &rarr;</a>
                </div>
                <div class="path-card">
                    <div class="path-card__icon" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="8" width="18" height="12" rx="1" stroke="currentColor" stroke-width="2"/><path d="M8 8V6a4 4 0 0 1 8 0v2" stroke="currentColor" stroke-width="2"/></svg>
                    </div>
                    <h3>I'm an Employer</h3>
                    <p>Hire the best talent for your team and grow your business.</p>
                    <a href="<?= url('register-recruiter.php') ?>" class="btn btn--gold">Post a Job &rarr;</a>
                </div>
            </div>
        </div>

        <div class="hero__visual">
            <div class="hero__photo">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" stroke="currentColor" stroke-width="1.5"/></svg>
                <span class="hero__photo-label">Photography placeholder — swap in licensed hospitality photography</span>
            </div>

            <div class="hero__stats">
                <div class="hero__stat">
                    <span class="hero__stat-icon" aria-hidden="true">&#128101;</span>
                    <div><strong>25,000+</strong><span>Qualified Candidates</span></div>
                </div>
                <div class="hero__stat">
                    <span class="hero__stat-icon" aria-hidden="true">&#127976;</span>
                    <div><strong>3,500+</strong><span>Hospitality Employers</span></div>
                </div>
                <div class="hero__stat">
                    <span class="hero__stat-icon" aria-hidden="true">&#9989;</span>
                    <div><strong>8,200+</strong><span>Successful Placements</span></div>
                </div>
                <div class="hero__stat">
                    <span class="hero__stat-icon" aria-hidden="true">&#127760;</span>
                    <div><strong>25+</strong><span>Countries Served</span></div>
                </div>
            </div>

            <div class="hero__promo">
                <h4>Build Your Dream Team</h4>
                <p>Find the best hospitality talent, quickly and easily.</p>
                <a href="<?= url('register-recruiter.php') ?>" class="btn btn--navy">Post a Job Now &rarr;</a>
            </div>
        </div>
    </div>

    <div class="hero-features container">
        <div class="hero-feature">
            <div class="hero-feature__icon" aria-hidden="true">&#129504;</div>
            <div><h4>AI-Powered Matching</h4><p>Smarter matches, faster.</p></div>
        </div>
        <div class="hero-feature">
            <div class="hero-feature__icon" aria-hidden="true">&#128737;</div>
            <div><h4>Verified &amp; Background Checked</h4><p>Trusted, safer hires.</p></div>
        </div>
        <div class="hero-feature">
            <div class="hero-feature__icon" aria-hidden="true">&#9889;</div>
            <div><h4>Faster Hiring</h4><p>Reduce time to hire.</p></div>
        </div>
        <div class="hero-feature">
            <div class="hero-feature__icon" aria-hidden="true">&#128172;</div>
            <div><h4>Dedicated Support</h4><p>We're with you every step.</p></div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow">How It Works</span>
            <h2>We find you the BEST!</h2>
        </div>
        <div class="steps">
            <div class="step">
                <div class="step__num">1</div>
                <h3>Sign Up</h3>
                <p>Create your job seeker or recruiter account in minutes.</p>
            </div>
            <div class="step">
                <div class="step__num">2</div>
                <h3>Request</h3>
                <p>Recruiters post vacancies and request candidates for hospitality roles.</p>
            </div>
            <div class="step">
                <div class="step__num">3</div>
                <h3>Apply</h3>
                <p>Job seekers browse and apply to hospitality positions that match their profile.</p>
            </div>
            <div class="step">
                <div class="step__num">4</div>
                <h3>Response</h3>
                <p>Our team and employers deliver timely, transparent feedback.</p>
            </div>
        </div>
    </div>
</section>

<section class="section--tint">
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow">Featured Roles</span>
            <h2>Featured Hospitality Jobs</h2>
        </div>

        <?php if (!$featuredJobs): ?>
            <div class="notice" style="max-width:640px;margin:0 auto;">
                No open jobs yet &mdash; be the first to <a href="<?= url('register-recruiter.php') ?>">post one as a recruiter</a>.
            </div>
        <?php else: ?>
            <div class="grid-3">
                <?php foreach ($featuredJobs as $job): ?>
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
            <p style="text-align:center;margin-top:var(--space-6);"><a href="<?= url('jobs.php') ?>" class="btn btn--navy">Browse All Jobs</a></p>
        <?php endif; ?>
    </div>
</section>

<section>
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow">Why Choose Us</span>
            <h2>Everything you need to hire — and get hired — in hospitality</h2>
        </div>
        <div class="grid-3">
            <div class="card feature-card">
                <div class="feature-card__icon">&#10003;</div>
                <h3>Background Checked Candidates</h3>
                <p>Every profile goes through a verification workflow before employers see it.</p>
            </div>
            <div class="card feature-card">
                <div class="feature-card__icon">&#128101;</div>
                <h3>Access to a Pool of Qualified Candidates</h3>
                <p>Search a growing database of hospitality professionals across every role.</p>
            </div>
            <div class="card feature-card">
                <div class="feature-card__icon">&#9888;</div>
                <h3>Access to Database of Blacklisted Employees</h3>
                <p>Review flagged candidates with full evidence, audit history and a fair dispute process.</p>
            </div>
            <div class="card feature-card">
                <div class="feature-card__icon">&#9201;</div>
                <h3>Time Efficiency</h3>
                <p>Cut down time-to-hire with a streamlined, structured recruitment pipeline.</p>
            </div>
            <div class="card feature-card">
                <div class="feature-card__icon">&#129504;</div>
                <h3>AI-Powered Candidate Matching</h3>
                <p><span class="badge badge--soon">Coming soon</span> Explainable AI match scores once the matching engine is connected in Phase 2.</p>
            </div>
            <div class="card feature-card">
                <div class="feature-card__icon">&#128203;</div>
                <h3>Streamlined Recruitment Process</h3>
                <p>Track every application from Applied to Hired in one visual pipeline.</p>
            </div>
        </div>
    </div>
</section>

<section class="section--tint">
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow">Trusted Nationwide</span>
            <h2>The biggest hospitality employers trust us</h2>
            <p>From independent restaurants to national hotel groups, employers of every size use our platform to find verified hospitality talent.</p>
        </div>
        <div class="grid-4">
            <div class="card stat-card">
                <div class="stat-card__value">2,500+</div>
                <div class="stat-card__label">Registered candidates</div>
            </div>
            <div class="card stat-card">
                <div class="stat-card__value">300+</div>
                <div class="stat-card__label">Hospitality employers</div>
            </div>
            <div class="card stat-card">
                <div class="stat-card__value">1,100+</div>
                <div class="stat-card__label">Successful placements</div>
            </div>
            <div class="card stat-card">
                <div class="stat-card__value">48 hrs</div>
                <div class="stat-card__label">Average response time</div>
            </div>
        </div>
        <p style="text-align:center;margin-top:var(--space-4);font-size:var(--fs-xs);">
            <span class="badge badge--soon">Sample figures</span> — replaced with live figures from the admin analytics dashboard once built.
        </p>
    </div>
</section>

<section>
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow">Reviews from Jobseekers</span>
            <h2>What our candidates say</h2>
        </div>
        <div class="grid-3">
            <div class="card testimonial">
                <p>"I got shortlisted for a Front Office role within a week of completing my profile."</p>
                <p class="testimonial__name">— Sample review, Lagos</p>
            </div>
            <div class="card testimonial">
                <p>"The application tracker made it easy to see exactly where I stood with each employer."</p>
                <p class="testimonial__name">— Sample review, Abuja</p>
            </div>
            <div class="card testimonial">
                <p>"As a recruiter, the candidate pool saved us weeks of sourcing time."</p>
                <p class="testimonial__name">— Sample review, Port Harcourt</p>
            </div>
        </div>
        <p style="text-align:center;font-size:var(--fs-xs);"><span class="badge badge--soon">Sample content</span> — real testimonials to be migrated from the current site.</p>
    </div>
</section>

<section>
    <div class="container">
        <div class="cta-banner">
            <h2>Ready to find your perfect match?</h2>
            <p style="color:rgba(255,255,255,0.8);max-width:520px;margin:0 auto;">Join Hospitality Recruitment and Placement today — whether you're building a career or building a team.</p>
            <div class="btn-row">
                <a href="<?= url('register-job-seeker.php') ?>" class="btn btn--gold btn--lg">Find a Job</a>
                <a href="<?= url('register-recruiter.php') ?>" class="btn btn--outline-light btn--lg">Post a Job</a>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
