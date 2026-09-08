<?php
/**
 * Shared site header/nav. Expects config.php + functions.php already
 * included by the calling page. $page_title / $page_description may be
 * set before including this file.
 */
$page_title = $page_title ?? APP_NAME;
$page_description = $page_description ?? 'Hospitality Recruitment and Placement connects hospitality job seekers with hotels, restaurants, resorts and hospitality employers.';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= e($page_title) ?></title>
    <meta name="description" content="<?= e($page_description) ?>" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?= asset('css/design-tokens.css') ?>" />
    <link rel="stylesheet" href="<?= asset('css/base.css') ?>" />
    <link rel="stylesheet" href="<?= asset('css/components.css') ?>" />
    <?php if (!empty($extra_stylesheets)): foreach ($extra_stylesheets as $sheet): ?>
    <link rel="stylesheet" href="<?= asset($sheet) ?>" />
    <?php endforeach; endif; ?>
</head>
<body>
<a class="skip-link" href="#main-content">Skip to content</a>

<header class="site-header">
    <div class="site-header__inner container">
        <a class="brand" href="<?= url() ?>">
            <span class="brand__mark">HR<span class="brand__mark-accent">&amp;</span>P</span>
            <span class="brand__name">Hospitality<br>Recruitment &amp; Placement</span>
        </a>

        <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="siteNav" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
        </button>

        <nav class="site-nav" id="siteNav">
            <ul>
                <li><a href="<?= url() ?>">Home</a></li>
                <li><a href="<?= url('about.php') ?>">About Us</a></li>
                <li><a href="<?= url('plans-pricing.php') ?>">Plans &amp; Pricing</a></li>
                <li><a href="<?= url('black-listed-employees.php') ?>">Black Listed Employees</a></li>
            </ul>
            <div class="site-nav__actions">
                <?php if (is_logged_in()): ?>
                    <a class="btn btn--ghost" href="<?= url('dashboard.php') ?>">Dashboard</a>
                <?php else: ?>
                    <a class="btn btn--ghost" href="<?= url('login.php') ?>">Login</a>
                    <div class="dropdown">
                        <button class="btn btn--primary dropdown__toggle" type="button">Register</button>
                        <div class="dropdown__menu">
                            <a href="<?= url('register-job-seeker.php') ?>">Register as a Job Seeker</a>
                            <a href="<?= url('register-recruiter.php') ?>">Register as a Recruiter</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>

<main id="main-content">
