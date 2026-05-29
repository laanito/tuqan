# Tuqan

[![CodeFactor](https://www.codefactor.io/repository/github/laanito/tuqan/badge/master)](https://www.codefactor.io/repository/github/laanito/tuqan/overview/master) [![GitHub license](https://img.shields.io/github/license/laanito/tuqan.svg)](https://github.com/laanito/tuqan/blob/master/LICENSE.md) [![GitHub issues](https://img.shields.io/github/issues/laanito/tuqan.svg)](https://github.com/laanito/tuqan/issues)

# Tuqan

**Legacy ISO 9001 / ISO 14001 Management Application (2005 → 2026)**

Modernization project of a PHP 5.1 application into modern PHP standards.

---

## Current Status (June 2026)

**Major progress on the "Core Functionality Modernization" stepping stone (Stage 8).**

### ✅ What is Working Now
- Fully Dockerized environment (PHP 8.3 + nginx + PostgreSQL 16) — host PHP is never used.
- PSR-4 autoloading via Composer.
- Real database-backed login flow (company selection → context switch → user login) against a minimal curated schema/seed.
- Working placeholder landing page at `/main/` + real logout.
- Significant dependency modernization:
  - Twig upgraded to 3.x
  - Monolog upgraded to 2.x
  - Phroute upgraded + legacy patches removed
  - Former upgraded to 5.x
  - jasny/auth upgraded to v2
- Extensive cleanup of PHP 8.1+ deprecation noise (`#[ReturnTypeWillChange]` patches across Illuminate and other legacy code).
- PHPUnit 10 test suite (characterization + regression tests for the modernized paths).
- Full Test + Fix Loop discipline with Xdebug enabled and zero-tolerance for warnings in the critical flows.

### 🔄 Current Focus
- Completing the composer dependency modernization phase before investing in more business functionality.
- Reducing residual deprecation noise from the remaining old Illuminate baggage (mostly pulled in via Former).

### 🚧 Important Notes
- The application has a **functional minimum viable login flow** for testing purposes, but it is **not** a complete replacement for the original legacy Tuqan/Qnova application.
- Many legacy modules (document control, risk matrices, questionnaires, etc.) are still untouched or only lightly modernized.
- **Not production ready.**

See `.agents/MIGRATION-PLAN.md` and `.agents/STAGE-CHECKLISTS.md` for the detailed roadmap and current evidence.

---

## Development Environment (2026+)

**All development must be done using Docker.** No local PHP, nginx, or PostgreSQL is ever used.

### Quick Start (Current Recommended Flow)

```bash
# 1. Start everything
docker compose --env-file .env.docker up -d --build

# 2. Initialize the minimal database schema + seed (required for login)
docker compose exec app ./scripts/init-db.sh

# 3. (Optional but recommended during active work) Run tests
docker compose exec app ./vendor/bin/phpunit
```

You can then open http://localhost:8080/ and use the demo credentials:
- Company: `demo` / `admin`
- User: `admin` / `admin`

After successful login you should reach a functional landing page at `/main/`.

### Environment Details
- PHP 8.3 (FPM + CLI) with Xdebug enabled
- Nginx
- PostgreSQL 16
- Full strict Test + Fix Loop discipline expected (Xdebug on, zero tolerance for new warnings in critical paths)

See `.agents/DOCKER-ENV.md`, `.agents/STAGE-CHECKLISTS.md`, and `.agents/AGENTS.md` for detailed commands, validation gates, and working rules.

---

## Tech Stack (Current State)

| Layer                  | Original (2005-era)     | Current (2026)                          | Notes |
|------------------------|-------------------------|-----------------------------------------|-------|
| PHP                    | 5.1                     | 8.3                                     | Full Docker (fpm + cli) |
| Database Access        | PEAR::DB                | PDO + custom query builder              | Working, still legacy patterns |
| Authentication         | PEAR::Auth              | Jasny/Auth v2 + custom two-step flow    | Real DB-backed (company → user) |
| Forms                  | PEAR::QuickForm         | Former 5.x (still pulls old Illuminate) | Major upgrade done |
| Routing                | Custom / old            | Phroute 2.2                             | Patches removed |
| Templating             | Old PHP / PEAR          | Twig 3.x                                | Upgrade complete |
| Logging                | ?                       | Monolog 2.x                             | Upgrade complete |
| Frontend               | Old HTML + JS           | Bootstrap 3 (CDN) + legacy CSS          | Still legacy |
| Localization           | Manual                  | gettext                                 | Working |
| Testing                | None                    | PHPUnit 10                              | Growing test suite |
| Environment            | Direct host PHP         | 100% Docker (PHP 8.3 + nginx + PG 16)   | Enforced |

---

## Project Goals

- Modernize the codebase while preserving original business logic
- Improve maintainability and security
- Follow a careful, step-by-step approach
- Document the full modernization process
- Do all the changes using agentic workflows

---

## How to Contribute / Work on the Project

**All work must be done inside the Docker environment.** This is non-negotiable.

### Typical Workflow
1. `docker compose --env-file .env.docker up -d`
2. `docker compose exec app ./scripts/init-db.sh` (when needed)
3. Make changes locally (files are bind-mounted)
4. Verify using the project's strict standards:
   - Run relevant tests inside the container
   - Test critical flows (especially login → main → logout) with Xdebug enabled
   - Ensure no new deprecation/warning noise is introduced in the response bodies
5. Update documentation in `.agents/` when appropriate (doc-first for significant work)
6. Commit and open a Pull Request

**Strong expectations**:
- Follow the Test + Fix Loop (root cause fixes, no hiding with error suppression).
- Every meaningful change should be verifiable with clean Xdebug output on the main flows.
- Large dependency or architectural changes are documented in the `.agents/` folder.

See `.agents/MIGRATION-PLAN.md`, `.agents/STAGE-CHECKLISTS.md`, and `.agents/AGENTS.md` for the current plan and rules.

---

Last updated: June 2026 (during Stage 8 composer dependency modernization)  
Maintained by laanito + agentic workflow

---

**Note for contributors/agents**: The authoritative source of truth for the current state and roadmap is always in the `.agents/` directory, not this README.

