# Hospitality Recruitment & Placement (HPRS) Platform

Redesign/rebuild of the existing [Hospitality Recruitment and Placement](https://www.hospitalityrecruitmentandplacement.com/)
site into a modern, AI-assisted hospitality recruitment platform, built with
**HTML, CSS, vanilla JavaScript, PHP and MySQL**. Lives in this repo under
`/hprs` so it doesn't disturb the portfolio site at the repo root.

## Status: Phase 1 — Foundation (in progress)

Built so far — all wired to a real MySQL database, not mocked:

- Project structure (`config/`, `database/`, `includes/`, `assets/`)
- MySQL schema for auth, profiles, companies, jobs, applications, subscriptions
  (`database/schema.sql`)
- Design system: brand tokens (navy `#000046`, gold `#fcc800`, cyan
  `#00c3f2`), base styles, and reusable components (buttons, nav, cards,
  job cards, stat cards, badges, forms, dashboard sidebar/nav, notices)
- Homepage matching the app-style reference layout: hero with job
  seeker/employer path cards, floating stats card, promo card, feature
  row, how-it-works, differentiators, stats, testimonials, CTA
- **Working registration** — Job Seeker (`register-job-seeker.php`) and
  Recruiter (`register-recruiter.php`), both insert real rows
  (`users`, `candidate_profiles` / `companies` + `recruiter_profiles` +
  a Free `subscriptions` row), hash passwords with `password_hash()`,
  validate input server-side, and log the user straight in
- **Working login/logout** (`login.php` / `logout.php`) — session-based,
  CSRF-protected, checks `password_verify()`, rejects suspended accounts
- **Role-aware dashboard** (`dashboard.php`) — recruiter view (active
  jobs/applications pulled live from the DB, subscription plan/status)
  and job seeker view (profile completion, application counts by
  status), styled as a sidebar app shell matching the reference. Nothing
  not yet built (AI matching, interviews, messaging) shows fake numbers —
  those nav items are visibly marked "Soon"
- Coming-soon pages for everything not yet built (About, Plans & Pricing,
  Black Listed Employees, Jobs search) so nothing 404s

Not built yet (see Roadmap): candidate profile builder (experience,
skills, CV upload), job posting & search, application pipeline, AI
features, payments, notifications, admin panel, mobile app.

## Running this on XAMPP

1. **Install XAMPP** (if you haven't) from apachefriends.org and start
   **Apache** and **MySQL** from the XAMPP Control Panel.

2. **Copy the project in.** Copy the whole `hprs` folder into your XAMPP
   web root:
   - Windows: `C:\xampp\htdocs\hprs`
   - macOS: `/Applications/XAMPP/htdocs/hprs`
   - Linux: `/opt/lampp/htdocs/hprs`

3. **Create the database.** Open **phpMyAdmin**
   (`http://localhost/phpmyadmin`) → **Import** tab → choose
   `hprs/database/schema.sql` → **Go**. This creates the `hprs_db`
   database and all tables (it also seeds the four subscription plans).
   Alternatively, from a terminal:
   ```bash
   # Windows: run from C:\xampp\mysql\bin
   mysql -u root -p < hprs/database/schema.sql
   ```
   (XAMPP's default MySQL root user has no password — just press Enter.)

4. **Configure the database connection.** Inside the `hprs` folder, copy
   `config/.env.example` to `hprs/.env` and edit it:
   ```
   APP_ENV=development
   APP_URL=http://localhost/hprs

   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_NAME=hprs_db
   DB_USER=root
   DB_PASS=
   ```
   (Leave `DB_PASS` empty for a stock XAMPP install.)

5. **Visit the site**: `http://localhost/hprs/`

   Try it end-to-end: click **Sign Up → Register as a Job Seeker** (or
   **For Employers**), fill the form, and you'll be logged straight into
   the dashboard — a real row now exists in `hprs_db.users` (check it in
   phpMyAdmin). Log out and back in from `http://localhost/hprs/login.php`
   to confirm the password check works.

**Common XAMPP gotchas:**
- If Apache won't start, something else (often Skype or IIS) is holding
  port 80 — change Apache's port in XAMPP's `httpd.conf`, or free the port.
- If you get a database connection error, double-check `hprs/.env` has
  the right `DB_USER`/`DB_PASS` for your MySQL install, and that the
  MySQL service is actually running (green in the Control Panel).
- `mysqli`/`pdo_mysql` ships enabled in XAMPP by default — no extra PHP
  extension setup needed.

## Roadmap (per the platform spec)

**Phase 1 — Foundation** (current): design system ✅, homepage ✅, auth ✅,
job seeker registration ✅, recruiter registration ✅, dashboards ✅,
basic subscription architecture ✅ — still to build: candidate profile
builder, recruiter/company profile editing, job creation, job search,
applications.

**Phase 2 — First AI automation**: AI CV parser, AI job matching,
job recommendations, candidate ranking, automated application pipeline,
email notifications.

**Phase 3 — Advanced automation**: recruiter/candidate AI assistants,
interview scheduling, WhatsApp integration, verification workflows
(including Black Listed Employees), automated reminders.

**Phase 4 — Scale**: mobile app, enterprise accounts, advanced analytics,
multiple payment providers, monitoring, advanced security.

## Brand rules

The full company name **Hospitality Recruitment & Placement** is always
shown in full. Colors and content are sourced from the live site, not
invented. AI features are recommend/rank/explain only — humans keep final
say on hiring, rejection, verification and blacklisting decisions.
