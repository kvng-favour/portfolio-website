<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

$page_title = 'Hospitality Recruitment and Placement | Hospitality Jobs & Talent';
$page_description = 'Hospitality Recruitment and Placement connects hospitality job seekers with hotels, restaurants, resorts and hospitality employers across Nigeria.';
$extra_stylesheets = ['css/home.css'];

require __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container hero__inner">
        <div>
            <h1>Are you seeking the perfect job or candidate in the hospitality industry?</h1>
            <p class="hero__lead">Join our community today. We connect verified hospitality talent with hotels, restaurants, resorts, catering and event companies.</p>
            <div class="hero__ctas">
                <a href="<?= url('register-job-seeker.php') ?>" class="btn btn--primary btn--lg">Find a Job</a>
                <a href="<?= url('register-recruiter.php') ?>" class="btn btn--outline-light btn--lg">Find Talent</a>
            </div>
        </div>
        <div class="hero__paths">
            <div class="hero__path">
                <h3>Job Seekers</h3>
                <p>Find your perfect job now! Build a profile once and get matched to hospitality roles that fit you.</p>
                <a href="<?= url('register-job-seeker.php') ?>" class="btn btn--gold">Get Started!</a>
            </div>
            <div class="hero__path">
                <h3>Recruiters</h3>
                <p>Find the perfect candidate now! Post a vacancy and reach our pool of background-checked hospitality professionals.</p>
                <a href="<?= url('register-recruiter.php') ?>" class="btn btn--primary">Get Started!</a>
            </div>
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

        <div class="notice" style="max-width:640px;margin:0 auto var(--space-8);">
            <strong>Coming soon —</strong>&nbsp;live job listings go live once the Job Posting &amp; Search pages are built in the next foundation step. The cards below show the layout using sample data only.
        </div>

        <div class="grid-3">
            <div class="card job-card">
                <div class="job-card__top">
                    <div>
                        <h3 class="job-card__title">Front Office Manager</h3>
                        <p class="job-card__company">Sample Hotel Group &middot; Lagos</p>
                    </div>
                    <span class="badge badge--open">Open</span>
                </div>
                <div class="job-card__meta">
                    <span>&#8358;250,000&ndash;&#8358;350,000</span>
                    <span>4+ yrs experience</span>
                    <span>Full-time</span>
                </div>
                <div class="job-card__footer">
                    <span class="badge badge--soon">Sample data</span>
                    <a href="<?= url('register-job-seeker.php') ?>" class="btn btn--ghost">Apply</a>
                </div>
            </div>
            <div class="card job-card">
                <div class="job-card__top">
                    <div>
                        <h3 class="job-card__title">Executive Chef</h3>
                        <p class="job-card__company">Sample Resort &middot; Abuja</p>
                    </div>
                    <span class="badge badge--open">Open</span>
                </div>
                <div class="job-card__meta">
                    <span>&#8358;300,000&ndash;&#8358;450,000</span>
                    <span>6+ yrs experience</span>
                    <span>Full-time</span>
                </div>
                <div class="job-card__footer">
                    <span class="badge badge--soon">Sample data</span>
                    <a href="<?= url('register-job-seeker.php') ?>" class="btn btn--ghost">Apply</a>
                </div>
            </div>
            <div class="card job-card">
                <div class="job-card__top">
                    <div>
                        <h3 class="job-card__title">Housekeeping Supervisor</h3>
                        <p class="job-card__company">Sample Hospitality Group &middot; Port Harcourt</p>
                    </div>
                    <span class="badge badge--open">Open</span>
                </div>
                <div class="job-card__meta">
                    <span>&#8358;150,000&ndash;&#8358;220,000</span>
                    <span>2+ yrs experience</span>
                    <span>Full-time</span>
                </div>
                <div class="job-card__footer">
                    <span class="badge badge--soon">Sample data</span>
                    <a href="<?= url('register-job-seeker.php') ?>" class="btn btn--ghost">Apply</a>
                </div>
            </div>
        </div>
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
