# Tuqan PHP 8 + Docker + Testing Migration Plan

**Branch:** `php-migration-plan-docker-testing` (created 2026-05)  
**Status:** Stages 1-6 completed. **Stage 8 (Core Functionality Modernization) largely completed** — Twig 3, full composer modernization, real DB login (no hardcodes), incremental menu data import, working collapsible top menu from real legacy data, and first Phroute action mapping (colon-to-slash + smart legacy fallback). See STAGE-CHECKLISTS.md for evidence. Next major focus: menu-driven population of real module content.  
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
  - As of May 2026 (during bare-minimum + login work): A `Twig\Node\Node::count()` return type deprecation surfaced under PHP 8.3.
  - Decision: Applied minimal `#[ReturnTypeWillChange]` patch (consistent with prior vendor handling for Illuminate etc.).
  - Full Twig upgrade remains deferred as a dedicated later stage. See STAGE-CHECKLISTS.md for details.

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

### Stage 7 — Minimum Viable Working App + Incremental Logic (delivered in this phase)
Concrete foundation delivered:
- Reliable minimal DB schema + seed (only verified tables/rows for login + main nav, applied via single app-side script, no legacy dump conflicts).
- Clean home page at / and /main/ (full Phroute dispatch + MainPage render) with **zero deprecation warnings** even with Xdebug enabled.
- Lesson recorded: a temporary bypass/short-circuit was introduced mid-work under "get the home page demo" pressure; user feedback ("why you shortcircuit instead of fixing?") drove removal + proper root-cause patches in Phroute + additional legacy signature/trim/guard fixes.

**Strategic note (end of this phase):** Repeated vendor deprecation patching across libraries during this work led to the decision that a dedicated "Core Functionality Modernization" stepping stone (see revised Stage 8) should be treated as a prerequisite before significant new feature development.

Pick modules one-by-one after this base (e.g. risk matrix calculator, document approval workflow, user permissions).

Pattern:
1. Write characterization tests against current behavior.
2. Extract pure functions / value objects.
3. Introduce DI where possible (start with new code).
4. Strangler: new route or service calls old, or vice-versa.
5. Deprecate old path only after full test coverage and user sign-off.

Never delete logic until tests prove equivalence.

### Stage 8 — Core Functionality Modernization (Stepping Stone — Recommended before deep feature work)

**Rationale (recorded end of May 2026 login/landing work):**  
During the Minimum Viable Working App phase, pragmatic `#[ReturnTypeWillChange]` and similar minimal patches were applied to several EOL vendor libraries (Twig 1.x, older Illuminate components, etc.) to keep forward momentum with clean Xdebug output. While effective short-term, this pattern is expected to repeat for every new slice of functionality.

**Execution started (May 2026):** First concrete slice is the proper upgrade of Twig from 1.44.8 (`~1.35`) to 3.8+ on branch `feat/stage-8-twig-upgrade`. This removes the last vendor shim we applied for the template layer, modernizes the 4 render call sites, and proves the stepping-stone approach with the same strict Test + Fix Loop + full Xdebug verification discipline used in PR #56. 

**Slice 8.1 completed:** Twig 3.27 now running cleanly. Full login → landing → logout flows verified with **zero** deprecation or warning strings even under Xdebug. The only friction surfaced was the old `anahkiasen/former` + illuminate 5 tree blocking broad composer resolution (handled with `--ignore-platform-reqs` for this narrow slice; documented as input for the next stepping-stone slice).

**Stage 8.2 completed (June 2026):** Comprehensive modernization of the remaining runtime composer dependencies on branch `feat/stage-8-composer-deps-modernization`. Upgrades: monolog 1→2, phroute 2.1→2.2 (old trim(null) shims removed), jasny/auth 1→2.2, anahkiasen/former 4→5.2, setasign/fpdf, plus explicit root `illuminate/support: ^8.0` floor. All prior vendor `#[ReturnTypeWillChange]` and trim patches removed where upstream now supports PHP 8.3+. Full login → /main/ → logout flows verified with zero deprecation/warning strings under Xdebug. See STAGE-CHECKLISTS.md for full evidence.

**Stage 8.3 + extended work (May–June 2026):** On branch `feat/stage-8.3-gettext-login-menu-data`.
- Login flow made 100% database-driven.
- Gettext fixed + English scaffolding.
- **Full real legacy menu** imported via incremental `data-patches/` (0004 + follow-up cleanups 0005–0009).
- Complete migration from horizontal navbar to **collapsible left sidebar** navigation.
- First real module work started: User Management (Usuarios listing + create/edit forms scaffolding). POST + validation explicitly deferred to next leg.

See STAGE-CHECKLISTS.md (Stage 8.4 section) for full evidence and the decision to pause POST work due to context size.

**Planning for Modules (ongoing):**
Menu remains the driver. Real vertical slices (starting with Usuarios) will be delivered one at a time with modern pages + the new sidebar chrome.

**Decision:** Before moving into larger feature modernization or architectural changes, insert a dedicated "Core Functionality Modernization" stepping stone phase. The focus is proper, sustainable updates (not endless patches) to the foundational layers the modernized code now depends on.

**Scope ideas for this phase:**
- Proper upgrade path for Twig (2 or 3) across the small number of active templates.
- Modernization or replacement of the form library (Former) and related frontend dependencies.
- Cleanup / modernization of the DB access layer (Manejador_Base_Datos + generador_SQL) or migration to more standard patterns.
- Removal of remaining legacy class patterns, dynamic property usage, and old require-based loading in the actively used code paths.
- Raising baseline quality (PHPStan, Rector passes, consistent DI, etc.) on the modernized core.

This phase is explicitly positioned as **pre-requisite infrastructure** before Stage 9+ (deeper business logic modernization).

### Stage 9 — UI/Dep Upgrade & Polish (later, after core stable)
- Twig 3 + modern template inheritance (now as part of or after the stepping stone).
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
- Stage 7 (Minimum Viable + Incremental Logic): Completed (PR #56)
- Stage 8.1 (Twig 3 upgrade): Completed
- Stage 8.2 (Full composer dependency modernization): Completed
- Stage 8.3 completed.
- Stage 8.4 (Full menu structure reference + first real module "Administración → Usuarios"): Completed (PR #60)
- Stage 8.5 (Perfiles + Empresas + Menus/Idiomas/Permisos under Aplicacion + menu UX + Docker locale handling): Completed on `feat/stage-8.5-profiles-empresas-personalizacion`.
- Stage 8.6 (started): POST + validation for Perfiles + Sedes (Empresas renamed to Sedes per user nitpick "sedes (branches)"), first additional Personalizacion module (Clientes fully implemented with GET+POST), new data patches 0012+0013. Branch `feat/stage-8.6-post-handling-sedes-modules`.
- Stage 9+: Continue POST/validation + more Personalizacion modules + deeper logic (Menus editing, Permisos matrix, etc.).

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
**Last Updated:** 2026-06 (post #64 merge — Stage 8.6 delivered POST for Perfiles/Sedes/Usuarios/Clientes/Criterios, Sedes rename, basic Permisos matrix, Menus editing, centralized flashes, plus article notes file).

**Testing note (added after the big 8.6 merge):** See the new top section in .agents/STAGE-CHECKLISTS.md titled "Testing Strategy for Modernized Functional Modules". In short: for these vertical slices we rely on (1) rigorous, copy-pasteable Docker + psql verification commands + DB state assertions, (2) the human requester doing a full browser pass, and (3) whatever stable automated tests exist. Pure unit/integration coverage for the new `Procesar` methods and UIs is still catching up because of the current shape of the page classes. The checklists are the contract for "how do we know this actually works".
**Approval Status:** Stage 8.3 plan reviewed and approved by user (menu "as-is" first; Former deferred until form pages are reached)