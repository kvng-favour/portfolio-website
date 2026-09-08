-- ============================================================================
-- HOSPITALITY RECRUITMENT & PLACEMENT (HPRS) PLATFORM
-- Phase 1 Foundation Schema — MySQL 8+
-- ============================================================================
-- This covers only what Phase 1 (foundation) needs: auth, profiles, companies,
-- jobs, applications, and basic subscription plans. Interviews, messages,
-- notifications, AI matches, verification and blacklist records, and audit
-- logs are added in later phases so we don't build ahead of what's wired up.
-- ============================================================================

CREATE DATABASE IF NOT EXISTS hprs_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hprs_db;

-- ----------------------------------------------------------------------------
-- roles
-- ----------------------------------------------------------------------------
CREATE TABLE roles (
    id          TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(30) NOT NULL UNIQUE   -- job_seeker | recruiter | admin
) ENGINE=InnoDB;

INSERT INTO roles (name) VALUES ('job_seeker'), ('recruiter'), ('admin');

-- ----------------------------------------------------------------------------
-- users — one account table for all roles; role-specific data lives in
-- candidate_profiles / recruiter_profiles so we don't cram everything here.
-- ----------------------------------------------------------------------------
CREATE TABLE users (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id             TINYINT UNSIGNED NOT NULL,
    full_name           VARCHAR(150) NOT NULL,
    email               VARCHAR(190) NOT NULL UNIQUE,
    phone               VARCHAR(30)  NULL,
    password_hash       VARCHAR(255) NOT NULL,
    status              ENUM('pending','active','suspended') NOT NULL DEFAULT 'pending',
    email_verified_at   DATETIME NULL,
    remember_token      VARCHAR(100) NULL,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id),
    INDEX idx_users_role (role_id),
    INDEX idx_users_status (status)
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- companies — the employer entity a recruiter account posts jobs under
-- ----------------------------------------------------------------------------
CREATE TABLE companies (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    owner_user_id        BIGINT UNSIGNED NOT NULL,
    name                VARCHAR(180) NOT NULL,
    industry            VARCHAR(100) NULL,        -- Hotel, Restaurant, Resort, Catering, Events...
    description         TEXT NULL,
    logo_path           VARCHAR(255) NULL,
    website             VARCHAR(255) NULL,
    location             VARCHAR(150) NULL,
    verification_status ENUM('unverified','pending','verified') NOT NULL DEFAULT 'unverified',
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_companies_owner FOREIGN KEY (owner_user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_companies_owner (owner_user_id)
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- recruiter_profiles
-- ----------------------------------------------------------------------------
CREATE TABLE recruiter_profiles (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     BIGINT UNSIGNED NOT NULL UNIQUE,
    company_id  BIGINT UNSIGNED NULL,
    job_title   VARCHAR(120) NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_recruiter_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_recruiter_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- candidate_profiles
-- ----------------------------------------------------------------------------
CREATE TABLE candidate_profiles (
    id                    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id               BIGINT UNSIGNED NOT NULL UNIQUE,
    photo_path            VARCHAR(255) NULL,
    professional_title    VARCHAR(150) NULL,       -- e.g. Front Office Manager
    location              VARCHAR(150) NULL,
    preferred_locations   VARCHAR(255) NULL,        -- comma-separated for Phase 1
    desired_roles         VARCHAR(255) NULL,
    years_experience      TINYINT UNSIGNED NULL,
    salary_expectation_min INT UNSIGNED NULL,
    salary_expectation_max INT UNSIGNED NULL,
    availability          ENUM('immediate','2_weeks','1_month','not_available') NOT NULL DEFAULT 'immediate',
    work_authorization    VARCHAR(150) NULL,
    bio                   TEXT NULL,
    cv_path               VARCHAR(255) NULL,
    profile_completion    TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at             DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at             DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_candidate_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_candidate_location (location)
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- subscription_plans — admin-configurable; prices/features editable later
-- via an admin panel, not hard-coded into application logic.
-- ----------------------------------------------------------------------------
CREATE TABLE subscription_plans (
    id              TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(60) NOT NULL,          -- Free, Professional, Business, Enterprise
    price           DECIMAL(10,2) NOT NULL DEFAULT 0,
    billing_cycle   ENUM('monthly','yearly') NOT NULL DEFAULT 'monthly',
    max_active_jobs SMALLINT UNSIGNED NULL,         -- NULL = unlimited
    features_json   JSON NULL,
    is_active       TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

INSERT INTO subscription_plans (name, price, billing_cycle, max_active_jobs, features_json) VALUES
    ('Free', 0.00, 'monthly', 1, JSON_ARRAY('1 active job post', 'Basic candidate search')),
    ('Professional', 25000.00, 'monthly', 10, JSON_ARRAY('10 active job posts', 'AI candidate matching', 'Talent pool access')),
    ('Business', 65000.00, 'monthly', 50, JSON_ARRAY('50 active job posts', 'AI candidate matching', 'Priority support')),
    ('Enterprise', 0.00, 'monthly', NULL, JSON_ARRAY('Unlimited job posts', 'Dedicated account manager', 'Custom integrations'));

-- ----------------------------------------------------------------------------
-- subscriptions — a recruiter/company's subscription to a plan
-- ----------------------------------------------------------------------------
CREATE TABLE subscriptions (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id  BIGINT UNSIGNED NOT NULL,
    plan_id     TINYINT UNSIGNED NOT NULL,
    status      ENUM('active','expired','cancelled','grace_period') NOT NULL DEFAULT 'active',
    started_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at  DATETIME NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_subscription_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    CONSTRAINT fk_subscription_plan FOREIGN KEY (plan_id) REFERENCES subscription_plans(id),
    INDEX idx_subscriptions_status (status)
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- jobs
-- ----------------------------------------------------------------------------
CREATE TABLE jobs (
    id                    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id            BIGINT UNSIGNED NOT NULL,
    posted_by_user_id      BIGINT UNSIGNED NOT NULL,
    title                 VARCHAR(180) NOT NULL,
    category              VARCHAR(100) NULL,        -- Front Office, Culinary, Housekeeping...
    description           TEXT NOT NULL,
    responsibilities      TEXT NULL,
    requirements          TEXT NULL,
    location              VARCHAR(150) NULL,
    employment_type       ENUM('full_time','part_time','contract','temporary','internship') NOT NULL DEFAULT 'full_time',
    salary_min            INT UNSIGNED NULL,
    salary_max            INT UNSIGNED NULL,
    salary_currency       VARCHAR(10) NOT NULL DEFAULT 'NGN',
    experience_required   TINYINT UNSIGNED NULL,
    certifications_required VARCHAR(255) NULL,
    status                ENUM('draft','open','closed','filled') NOT NULL DEFAULT 'draft',
    applications_count    INT UNSIGNED NOT NULL DEFAULT 0,
    created_at            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_jobs_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    CONSTRAINT fk_jobs_poster FOREIGN KEY (posted_by_user_id) REFERENCES users(id),
    INDEX idx_jobs_status (status),
    INDEX idx_jobs_location (location),
    INDEX idx_jobs_category (category),
    FULLTEXT INDEX ft_jobs_title_desc (title, description)
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- applications
-- ----------------------------------------------------------------------------
CREATE TABLE applications (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_id        BIGINT UNSIGNED NOT NULL,
    candidate_id  BIGINT UNSIGNED NOT NULL,          -- references candidate_profiles.id
    status        ENUM('applied','screening','shortlisted','interview','offer','hired','rejected') NOT NULL DEFAULT 'applied',
    cover_note    TEXT NULL,
    applied_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_applications_job FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    CONSTRAINT fk_applications_candidate FOREIGN KEY (candidate_id) REFERENCES candidate_profiles(id) ON DELETE CASCADE,
    UNIQUE KEY uq_job_candidate (job_id, candidate_id),
    INDEX idx_applications_status (status)
) ENGINE=InnoDB;
