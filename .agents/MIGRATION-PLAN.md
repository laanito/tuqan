# Tuqan PHP 8 + Docker + Testing Migration Plan

**Branch:** `php-migration-plan-docker-testing` (created 2026-05)  
**Status:** Stage 1 (Docker Foundation) completed on 2026-05-25. Ready for Stage 2.  
**Goal:** Migrate the legacy Tuqan/Qnova ISO 9001/14001 application to a maintainable, tested, PHP 8.3+ state with a 100% Docker-based development environment. Zero reliance on host PHP, nginx, or postgres.

## Executive Summary

This plan delivers:
- Fully Dockerized dev environment (php 8.3-fpm, nginx, postgres 16) — no local services ever used.
- Modern testing harness (PHPUnit 10+ inside Docker) with unit + integration tests that can validate changes safely.
- Staged migration path (8 phases) with clear gates, timeline (~10-14 weeks core), and rollback strategies.
- All changes documented here; future agents follow checklists exactly.

**Key Constraints**
- Business logic for ISO compliance (document control, permissions, risk matrices, questionnaires, indicators) must be preserved.
- All execution, builds, tests, and DB work via `docker compose` only.
- No code changes outside `.agents/` until this plan (or a specific stage) is approved.

## Current Project Status (Audit Findings — May 2026 Session)

### Inventory
- **Total PHP files:** ~310
- **Root-level .php (mostly legacy entry points):** 36
- **Classes/ (namespaced but mixed quality):** ~37
- **Legacy bloat (to be isolated/removed):** 
  - javascript/FCKeditor/: 3.2 MB (abandoned 2010-era editor, major security surface)
  - Image/ (PEAR-era graphing): 2.5 MB + tests
  - HTML/, Pager/, Date.php, PEAR.php (bundled old libs)
- **Modernized pieces (partial):** Phroute routing, Jasny/Auth + sessions, some Twig 1.x pages under `Tuqan\Pages\*`, namespacing on many Classes/, custom PDO-based query builder (Manejador_Base_Datos + generador_SQL).
- **Templates:** Only 3 Twig files, all referencing Bootstrap 3.3.7 CDNs + old tuqan.css (README claim of "Bootstrap 5" is inaccurate).
- **Dependencies (composer.json):** No PHP version constraint, Twig ~1.35, Monolog ~1.23, Former 4.1.7 (BS3), fpdf, phroute, jasny/auth, surface dev-master (risky), ext requirements only.
- **No PSR-4 autoload** configured in composer despite README and prior claims. All loading via manual `require_once` (duplicates exist, e.g. generador_SQL).
- **Database:** PostgreSQL (pgsql). Hardcoded creds and paths in two places (etc/qnova.conf.php + Classes/Config.php). Query builder relies on `addslashes()` + string concatenation (SQL injection risk, especially with mixed LATIN1/UNICODE).
- **Tests:** Zero outside old Image/ subdir. No PHPUnit, no CI harness.
- **CI:** Only ancient phpci.yml (ignores legacy dirs, allows failures).
- **Docker/Env:** None. No .github/workflows. Hardcoded paths assume `/var/www/html/qnova/`.
- **Functionality:** Application is explicitly **not functional** (per README). Partial migration left it in an intermediate broken state.

### Risk Matrix (High Level)
| Risk | Likelihood | Impact | Mitigation in This Plan |
|------|------------|--------|-------------------------|
| SQL injection in query builder | High | Critical | Stage 3: externalize config + introduce prepared statements wrapper; tests for injection vectors |
| Business rule drift during refactor | Medium | High | Preserve via tests exercising original paths; strangler-fig pattern |
| Docker env drift from prod | Low | Medium | Single compose file + documented prod parity checks |
| Large legacy removal breaks editor/docs | Medium | Medium | Stage 5: usage audit + archive/ before delete; FCK kept until modern editor added |
| Data loss on DB migrations | Low | High | Always use volume snapshots + test DB + full dump restore in Docker |
| Old deps fail on PHP 8.3 | High | High | Stage 2: incremental dep updates inside Docker; pin compat versions first |

Full detailed inventory lives in this and prior session analysis (grep results, file reads of Config, Manejador_Base_Datos, generador_SQL, index.php, templates, etc.).

## 5.1 Dev Environment — Docker Only (Definition of Done)

**Never use host PHP, nginx, or postgres for this project.**

### Target Stack
- **PHP:** 8.3 (fpm + cli variants). Minimum 8.2 allowed during transition if needed.
- **Web:** nginx:alpine (or stable) with custom site.conf proxying to php-fpm.
- **DB:** postgres:16-alpine (or 15 for closer to original).
- **Extras in compose:** 
  - Optional: mailhog or mailpit for email testing.
  - Optional: adminer or pgadmin for DB UI (dev only).
- **Volumes:** bind mount for live code editing, named volume for postgres data.
- **Networks:** internal only + exposed ports (nginx 8080, perhaps 8443 later).
- **User:** www-data inside containers (uid/gid aligned where possible on macOS).

### Required Files (to be added in Stage 1 after approval)
- `Dockerfile` (php 8.3-fpm base, extensions: pdo_pgsql, pgsql, gd, gettext, intl, zip, bcmath, opcache, xdebug for dev profile)
- `docker-compose.yml` (or docker-compose.dev.yml)
- `docker/nginx/tuqan.conf` (server block, fastcgi to php, client_max_body for uploads)
- `docker/php/php.ini` (memory, upload limits, error_reporting=E_ALL, display_errors=On for dev, opcache, xdebug config)
- `docker/postgres/init/00-init.sql` or use existing scripts/ for seed (anonymized)
- `.env.docker` (template) + `.env` (gitignored) for DB creds, base_path, etc.
- `docker-compose.override.yml` (optional for local tweaks)

**Exact docker-compose.yml skeleton** (future agent will write this to root after approval):

```yaml
version: '3.8'
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
      target: dev  # or prod
    volumes:
      - .:/var/www/html
      - ./docker/php/php.ini:/usr/local/etc/php/conf.d/tuqan.ini
    environment:
      - APP_ENV=dev
      - DB_HOST=db
      - DB_PORT=5432
      - DB_NAME=${DB_NAME:-qnova}
      - DB_USER=${DB_USER:-qnova}
      - DB_PASS=${DB_PASS:-secret}
    depends_on:
      - db
    networks:
      - tuqan-net

  web:
    image: nginx:1.27-alpine
    ports:
      - "8080:80"
    volumes:
      - .:/var/www/html
      - ./docker/nginx/tuqan.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    networks:
      - tuqan-net

  db:
    image: postgres:16-alpine
    environment:
      - POSTGRES_DB=${DB_NAME:-qnova}
      - POSTGRES_USER=${DB_USER:-qnova}
      - POSTGRES_PASSWORD=${DB_PASS:-secret}
    volumes:
      - tuqan_pgdata:/var/lib/postgresql/data
      - ./scripts:/docker-entrypoint-initdb.d:ro  # for initial dumps (use subset for speed)
    ports:
      - "5432:5432"  # only for host tools if needed; prefer docker exec
    networks:
      - tuqan-net

volumes:
  tuqan_pgdata:

networks:
  tuqan-net:
    driver: bridge
```

**Dockerfile skeleton** (multi-stage for prod later):

```dockerfile
# syntax=docker/dockerfile:1
FROM php:8.3-fpm AS base

# Install system deps + PHP extensions (only docker-php-ext)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libgd-dev \
    libzip-dev \
    gettext \
    locales \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql pgsql gd zip gettext intl bcmath opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Dev target with xdebug
FROM base AS dev
RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
# Dev user adjustments if needed

# Prod target (later stages)
FROM base AS prod
# opcache, no xdebug, etc.

# Default to dev for compose
FROM dev
```

**Usage (all agents must use exactly these patterns):**

```bash
# From host (only docker commands allowed)
docker compose --env-file .env.docker up -d --build

# Enter PHP container (NEVER use host php)
docker compose exec app php -v
docker compose exec app composer install --no-interaction
docker compose exec app composer validate

# DB shell (never host psql)
docker compose exec db psql -U qnova -d qnova -c "SELECT version();"

# Run tests (see TESTING section)
docker compose exec app vendor/bin/phpunit

# Logs
docker compose logs -f app web db

# Teardown (preserve volume or not)
docker compose down
```

**Config Externalization Goal (Stage 3):** Remove all hardcoded localhost/qnova/ZTBlMWI2... strings. Use `getenv()` or vlucas/phpdotenv. Docker will be the single source for local creds.

**Port & Path Notes:** Original app assumes /qnova subdir and specific DOCUMENT_ROOT. Plan: either (a) set nginx root + SCRIPT_NAME or (b) fix base_path in Config during migration. Prefer minimal change to logic.

## 5.2 Testing Harness (Definition of Done)

**Chosen Tools (PHP 8.3 native):**
- PHPUnit 10.x or 11.x (latest compatible)
- Optional: PestPHP 2+ (for nicer syntax on new tests)
- DB testing: transaction rollback + dedicated test database or schema
- Static analysis: PHPStan level 5+ (gradual) or Psalm
- (Future) Infection for mutation testing on core logic

**Why not full e2e browser first:** Legacy JS (FCK, dhtml calendar, tree menus) makes it brittle early. Start with backend + integration (HTTP via Symfony\Component\HttpKernel or simple curl against web container + DB assertions).

**Directory Layout (added in Stage 2):**
```
tests/
  Unit/
    Classes/
      ConfigTest.php
      GeneradorSQLTest.php   # test the builder in isolation
  Integration/
    Database/
      QueryBuilderTest.php
    Auth/
      LoginFlowTest.php
  bootstrap.php
  TestCase.php (extends PHPUnit\Framework\TestCase, sets up DB connection via docker env)
```

**Example bootstrap.php (inside Docker):**
```php
<?php
require __DIR__ . '/../vendor/autoload.php';
// Later: load minimal Config, mock sessions, etc.
```

**Running (mandatory pattern):**
```bash
docker compose exec app composer require --dev phpunit/phpunit phpstan/phpstan
docker compose exec app ./vendor/bin/phpunit --configuration phpunit.xml.dist --testsuite Unit
docker compose exec app ./vendor/bin/phpunit --testsuite Integration --coverage-html /tmp/coverage
```

**Validation Requirements per Stage:**
- Every migrated or touched file must have a corresponding test that fails before the change and passes after (TDD where feasible).
- For legacy paths that are hard to unit test: add at minimum an integration test that exercises the original entry point (e.g., simulate POST to /procesa_...) and asserts DB side effects or output contains expected strings.
- Gate: 0 failing tests + coverage report for touched modules.

**Future Expansion:**
- Factories for test data (users, documents, aspects)
- Snapshot testing for generated PDFs/excels (store expected in tests/fixtures)
- Contract tests if APIs emerge

## 5.3 Migration Path — Detailed Stages + Timeline

**Total Estimated Core Duration:** 10-14 weeks (agentic pace, careful review between stages). Assume 3-5 focused agent sessions per stage + human review.

**Overarching Gates (apply to every stage):**
1. All work in Docker only.
2. `docker compose exec app composer validate` passes.
3. Full test suite green (even if small initially).
4. Update this plan + STAGE-CHECKLISTS.md with evidence.
5. PR + user sign-off before proceeding to next stage.

### Stage 0 — Planning & Foundation (COMPLETE — this session)
- Branch created, .agents/ overhauled with this plan.
- Full audit via tools.
- **Gate:** This file + AGENTS.md + DOCKER.md + TESTING.md + STAGE-CHECKLISTS.md committed on the planning branch.

### Stage 1 — Docker Environment (1 week)
**Goal:** Developer can `docker compose up` and have a working PHP 8.3 + nginx + postgres stack with zero host services.

**Detailed Tasks (see STAGE-CHECKLISTS.md for checklist):**
- Create Dockerfile + compose files (use the skeletons above; tune extensions, user, permissions for macOS bind mounts).
- Add .dockerignore, update .gitignore for .env*, volumes, etc.
- Seed DB with a minimal viable dump (subset of qnovaintegraldumpvacio.sql or generated schema + essential rows for login).
- Verify inside containers: PHP 8.3, all required exts (pdo_pgsql, gd, gettext), composer 2.x, nginx serves static + php.
- Basic smoke: access http://localhost:8080 , see no fatal, or a simple phpinfo route temporarily.
- Document any path/encoding gotchas (original uses LATIN1 in places).

**Validation Commands (exact — run in Docker):**
```bash
docker compose --env-file .env.docker up -d --build
docker compose exec app php -v | grep -E 'PHP 8\.(2|3)'
docker compose exec app php -m | grep -E '(pdo_pgsql|gd|gettext)'
docker compose exec db psql -U qnova -d qnova -c '\l'
curl -I http://localhost:8080 || echo "nginx responding"
docker compose down -v  # optional cleanup
```

**Deliverables:** Working docker stack + updated docs. No app functionality required yet.

**Rollback:** `docker compose down -v` + rm of new files (git clean).

### Stage 2 — Testing Harness + PHP 8.3 Baseline (1.5-2 weeks)
**Goal:** First green tests run inside Docker; app code loads without fatal on PHP 8.3 (even if not fully functional).

**Tasks:**
- Add phpunit, phpstan to composer (dev).
- Create tests/ skeleton + bootstrap that can at least `require` key Classes/ without immediate fatal.
- Write 5-10 smoke tests: Config::initialize, generador_SQL basic, simple Manejador query builder (mock or real test DB).
- Run phpstan --level=0 on whole codebase; fix only the minimal to make it pass (do not refactor yet).
- Fix any PHP 8.3 incompat that surface (e.g. deprecated warnings as errors in dev, nulls, etc.).
- Update composer.json with `"php": "^8.2"` and lock compatible dep versions.
- Optional: first pass at Twig 2/3? (risky — defer if it touches too much).

**Validation:**
- `docker compose exec app ./vendor/bin/phpunit` → all green.
- `docker compose exec app ./vendor/bin/phpstan analyse --level=0` → 0 errors (or documented ignores).
- Evidence appended to docs.

### Stage 3 — Config, Secrets & Query Safety (1 week)
**Goal:** No hardcoded secrets/paths; safer DB access patterns introduced without breaking existing callers.

**Tasks:**
- Introduce vlucas/phpdotenv (or native getenv).
- Single source of truth for DB creds (Docker env + .env).
- Update both Config.php and etc/ usage (or deprecate etc/).
- Create DB wrapper or enhance Manejador to support prepared statements (new methods: `consultaPreparada`, keep old for compat).
- Refactor highest-risk call sites (Auth, login, user-facing forms) to use prepared where easy.
- Add tests that assert no raw string concat in critical paths (or at least cover them).

**Validation:** Integration tests exercise login + a permission check with test data; DB logs show prepared stmts where possible.

### Stage 4 — PSR-4 Autoloading & Class Loading Cleanup (2 weeks)
**Goal:** Composer autoloads the Tuqan\* namespace; manual requires drastically reduced; no duplicate loads.

**Tasks:**
- Add proper autoload + autoload-dev to composer.json.
- Fix filename → class mismatches (e.g. rename or use classmap for .class.php files).
- Audit and remove duplicate requires (index.php has two generador_SQL).
- Gradually delete old require blocks as autoload covers them.
- Validate no "Class not found" on routed pages and key legacy includes.
- Update any custom spl_autoload if present (none expected).

**Validation:** `docker compose exec app composer dump-autoload -o` ; full test suite + manual route exercise (e.g. login pages) still work.

### Stage 5 — Legacy Bloat Removal / Archival (2-3 weeks)
**Goal:** Attack surface and image size reduced; dead code gone or clearly isolated.

**Tasks (use heavy grep + usage analysis):**
- FCKeditor: usage audit → decide keep (in userfiles or archive/) until rich text replacement in UI phase. Do not delete yet if documents rely on it.
- Image/Graph + related: grep for actual usage in app code (not just self-tests). Likely safe to move to archive/legacy/.
- HTML/Template/IT, Pager, Date.php, old PEAR remnants: similar audit + archive or delete if truly unused.
- Root .php files: map which are still reached via router vs dead entry points.
- Update all ignores in any CI remnants.

**Validation:** App still "as functional as before" (i.e. same breakage points) + tests cover removed paths if any were exercised.

### Stage 6 — Modern CI + Docker in GitHub Actions (1 week)
**Goal:** Every PR runs the full Docker test suite automatically.

**Tasks:**
- Create .github/workflows/ci.yml that does `docker compose up --build`, composer install, phpunit, phpstan (all inside the workflow using docker).
- Add badge to README (after approval).
- Optional: matrix for PHP 8.2/8.3 if needed.

**Validation:** Push a docs-only change; watch Actions green.

### Stage 7 — Incremental Logic Modernization (ongoing, parallel with later stages)
Pick modules one-by-one (e.g. risk matrix calculator, document approval workflow, user permissions).

Pattern:
1. Write characterization tests against current behavior.
2. Extract pure functions / value objects.
3. Introduce DI where possible (start with new code).
4. Strangler: new route or service calls old, or vice-versa.
5. Deprecate old path only after full test coverage and user sign-off.

Never delete logic until tests prove equivalence.

### Stage 8 — UI/Dep Upgrade & Polish (later, after core stable)
- Twig 3 + modern template inheritance.
- Bootstrap 5 (or current) + remove old CSS/JS/images where possible.
- Replace FCK with CKEditor 5 or Trix or ProseMirror.
- Full PSR-12 / PHPStan level 8+ / Rector for automated cleanup.
- Production Docker image + deployment docs.

## Timeline (Indicative)

- Stage 0: Done (this session)
- Stage 1 (Docker): Week 1
- Stage 2 (Tests + PHP8 baseline): Weeks 2-3
- Stage 3 (Config/Safety): Week 4
- Stage 4 (Autoload): Weeks 5-6
- Stage 5 (Cleanup): Weeks 7-9
- Stage 6 (CI): Week 10
- Stage 7+: Ongoing (prioritized by business value / risk)

**Buffer:** 2-4 weeks for surprises (legacy surprises always appear).

**Milestones for Human Review:**
- End of Stage 1: "I can develop with only Docker"
- End of Stage 2: "Tests exist and PHP 8.3 loads the code"
- End of Stage 4: "Modern loading + no more manual require hell"

## How to Run the Plan (Future Agents)

See AGENTS.md for mandatory workflow.  
Detailed per-stage checklists + exact commands + expected outputs: STAGE-CHECKLISTS.md

After each stage gate, append a "Stage N — Completed Evidence" section to this file with:
- Date
- Branch
- docker compose outputs (key lines)
- Test count + pass rate
- Any deviations + rationale

## Success Metrics (Measurable)

- 100% of development happens in Docker (auditable via docs).
- >= 60% line coverage on all new/modernized code (PHPUnit).
- Zero critical/high vulnerabilities in `composer audit` inside Docker.
- Application can be brought to "login screen functional" state without host services.
- All business-critical paths have characterization tests before major refactors.

## Appendices

- A: Original README (snapshot at plan creation)
- B: Key file excerpts from audit (Config, DB builder, index.php, templates)
- C: Full previous .agents/ phase-0 content (archived in git history of this branch)

---

**Plan Owner:** This session's agent + future agents following AGENTS.md  
**Last Updated:** 2026-05 (plan creation)  
**Approval Status:** Pending user review of this document and STAGE-CHECKLISTS.md