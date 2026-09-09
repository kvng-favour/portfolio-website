</main>

<footer class="site-footer">
    <div class="container site-footer__inner">
        <div class="site-footer__brand">
            <a class="brand brand--footer" href="<?= url() ?>">
                <span class="brand__icon brand__icon--footer" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2 3 7v2h18V7l-9-5Z" fill="currentColor"/>
                        <path d="M4 10v9h4v-6h8v6h4v-9H4Z" fill="currentColor"/>
                    </svg>
                </span>
                <span class="brand__mark brand__mark--footer">HPRS</span>
            </a>
            <p>Hospitality Recruitment and Placement connects verified hospitality talent with hotels, restaurants, resorts, catering and event companies.</p>
        </div>

        <div class="site-footer__col">
            <h4>Job Seekers</h4>
            <ul>
                <li><a href="<?= url('jobs.php') ?>">Search Jobs</a></li>
                <li><a href="<?= url('register-job-seeker.php') ?>">Create a Profile</a></li>
                <li><a href="<?= url('login.php') ?>">Login</a></li>
            </ul>
        </div>

        <div class="site-footer__col">
            <h4>Recruiters</h4>
            <ul>
                <li><a href="<?= url('register-recruiter.php') ?>">Post a Job</a></li>
                <li><a href="<?= url('plans-pricing.php') ?>">Plans &amp; Pricing</a></li>
                <li><a href="<?= url('black-listed-employees.php') ?>">Black Listed Employees</a></li>
            </ul>
        </div>

        <div class="site-footer__col">
            <h4>Company</h4>
            <ul>
                <li><a href="<?= url('about.php') ?>">About Us</a></li>
            </ul>
        </div>
    </div>
    <div class="site-footer__bottom container">
        <p>Copyright &copy; <?= date('Y') ?> Hospitalityrecruitmentandplacement.com. All rights reserved.</p>
    </div>
</footer>

<script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
