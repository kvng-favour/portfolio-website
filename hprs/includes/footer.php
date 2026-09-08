</main>

<footer class="site-footer">
    <div class="container site-footer__inner">
        <div class="site-footer__brand">
            <span class="brand__mark brand__mark--footer">HR<span class="brand__mark-accent">&amp;</span>P</span>
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
