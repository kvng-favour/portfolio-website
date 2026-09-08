# Hospitality Recruitment & Placement (HPRS) Platform

Redesign/rebuild of the existing [Hospitality Recruitment and Placement](https://www.hospitalityrecruitmentandplacement.com/)
site into a modern, AI-assisted hospitality recruitment platform, built with
**HTML, CSS, vanilla JavaScript, PHP and MySQL**. Lives in this repo under
`/hprs` so it doesn't disturb the portfolio site at the repo root.

## Status: Phase 1 — Foundation (in progress)

Built so far:

- Project structure (`config/`, `database/`, `includes/`, `assets/`)
- MySQL schema for auth, profiles, companies, jobs, applications, subscriptions
  (`database/schema.sql`)
- Design system: brand tokens pulled from the live site (navy `#000046`,
  cyan `#00c3f2`, gold `#fcc800`), base styles, and reusable components
  (buttons, nav, cards, job cards, stat cards, badges, forms, notices)
- Homepage rebuilt with the live site's real copy and structure (hero,
  job seeker/recruiter paths, how it works, differentiators, stats,
  testimonials, CTA)
- Placeholder "Coming Soon" pages for every nav link so nothing 404s:
  About, Plans & Pricing, Black Listed Employees, Jobs, Login, Register
  (Job Seeker / Recruiter), Dashboard — each states plainly what's real
  vs. not yet connected, per the "don't fake backend functionality" rule.

Not built yet (see Roadmap): authentication, registration forms, candidate/
recruiter profile builders, job posting & search, application pipeline, AI
features, payments, notifications, admin panel, mobile app.

## Local setup

```bash
# 1. Create the database
mysql -u root -p < database/schema.sql

# 2. Configure environment
cp config/.env.example .env
# edit .env with your local DB credentials

# 3. Serve with PHP's built-in server from the repo root
php -S localhost:8000

# 4. Visit
http://localhost:8000/hprs/
```

## Roadmap (per the platform spec)

**Phase 1 — Foundation** (current): design system ✅, homepage ✅, auth,
job seeker registration, recruiter registration, candidate profiles,
recruiter/company profiles, job creation, job search, applications,
dashboards, basic subscription architecture.

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
