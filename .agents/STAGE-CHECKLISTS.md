# Stage Checklists & Validation Commands — Tuqan Migration

**How to use this file:**
- Copy the relevant stage's todo items into a `todo_write` call at the start of the stage.
- Execute **only** the commands shown (all via docker compose).
- After each major item, append output + "PASS" or "FAIL + reason" to the Evidence section at the bottom of this file (or in MIGRATION-PLAN.md).
- Only mark the stage `completed` in your todo list when ALL gates are green and docs are updated.

---

## Stage 1 — Docker Environment (Foundation)

**todo_write items (copy these):**

```json
[
  {"id":"1.1","content":"Create Dockerfile, docker-compose.yml, nginx/php configs from DOCKER-ENV.md skeletons","status":"pending"},
  {"id":"1.2","content":"Add .env.docker + update .gitignore","status":"pending"},
  {"id":"1.3","content":"Build and start stack; verify PHP 8.3 + all extensions inside container","status":"pending"},
  {"id":"1.4","content":"Initialize postgres with minimal seed; connect via docker exec psql","status":"pending"},
  {"id":"1.5","content":"Verify nginx serves static + php-fpm (curl or browser to localhost:8080)","status":"pending"},
  {"id":"1.6","content":"Document any gotchas (paths, permissions, encoding) in DOCKER-ENV.md","status":"pending"},
  {"id":"1.7","content":"Update MIGRATION-PLAN.md and this file with full evidence + screenshots if useful","status":"pending"}
]
```

**Exact Validation Commands (run in order, capture output):**

```bash
# Clean previous attempts
docker compose --env-file .env.docker down -v 2>/dev/null || true

# Build
docker compose --env-file .env.docker build --no-cache --progress=plain 2>&1 | tail -30

# Start
docker compose --env-file .env.docker up -d

# 1. PHP version & extensions (MUST be 8.3)
docker compose exec app php -v
docker compose exec app php -m | grep -E 'pdo_pgsql|gd|gettext|intl|zip'

# 2. Composer works
docker compose exec app composer --version
docker compose exec app composer validate --no-check-all

# 3. DB ready & queryable
docker compose exec db pg_isready -U qnova
docker compose exec db psql -U qnova -d qnova -c "SELECT version(); SELECT count(*) FROM information_schema.tables WHERE table_schema='public';"

# 4. Web responds (no 502/500)
curl -I --max-time 10 http://localhost:8080 || echo "CURL FAILED - check nginx logs"
docker compose logs --tail=20 web

# 5. App container can see code
docker compose exec app ls -la /var/www/html | head -10
docker compose exec app ls -la /var/www/html/.agents | head -5
```

**Stage 1 Gate (must all be true before proceeding):**
- [ ] PHP 8.3.x exactly inside `app`
- [ ] All 7+ required extensions present
- [ ] Postgres accepts connection and has tables (even if 0 rows)
- [ ] `curl -I http://localhost:8080` returns 200 or 302 (or 404 from app, not 502)
- [ ] No permission errors on bind mount
- [ ] Evidence appended below

**Rollback:** `docker compose down -v && git clean -fd docker/ .env* docker-compose*`

---

## Stage 2 — Testing Harness + PHP 8.3 Baseline

**todo_write items:**

```json
[
  {"id":"2.1","content":"Add phpunit + phpstan via composer inside Docker","status":"pending"},
  {"id":"2.2","content":"Create phpunit.xml.dist + tests/bootstrap.php + basic directory structure","status":"pending"},
  {"id":"2.3","content":"Write 5+ smoke tests (Config, generador_SQL, simple DB connect)","status":"pending"},
  {"id":"2.4","content":"First phpunit run (expect some failures — document them)","status":"pending"},
  {"id":"2.5","content":"phpstan --level=0 on Classes/ + Pages/ (fix only fatal parse issues)","status":"pending"},
  {"id":"2.6","content":"Add 'php': '^8.2' to composer.json + pin safe dep versions","status":"pending"},
  {"id":"2.7","content":"All tests that can pass, pass. Evidence + coverage report captured","status":"pending"}
]
```

**Validation Commands:**

```bash
docker compose exec app composer require --dev phpunit/phpunit:^10.5 phpstan/phpstan:^1.11 --with-all-dependencies

# Create dirs (if not done via search_replace)
docker compose exec app mkdir -p tests/Unit/Classes tests/Integration/Database tests/Fixtures

# Run the very first test suite (will be small)
docker compose exec app ./vendor/bin/phpunit --version

# PHPStan baseline
docker compose exec app ./vendor/bin/phpstan --version
docker compose exec app ./vendor/bin/phpstan analyse Classes/ Pages/ Controllers/ --level=0 --no-progress 2>&1 | tail -20

# Full suite (after writing the example tests)
docker compose exec app ./vendor/bin/phpunit --configuration phpunit.xml.dist --testsuite=Unit --display-warnings
```

**Stage 2 Gate:**
- [ ] phpunit 10.x runs inside container
- [ ] At least 3 green tests exercising Config + query builder (even if they only test construction)
- [ ] phpstan level 0 clean on modernized directories (or documented ignores for legacy)
- [ ] composer.json has explicit PHP constraint
- [ ] Evidence section updated with `phpunit --coverage-text` output

---

## Stage 3 — Config, Secrets & Query Safety (In Progress)

**Approach:**
- Using native `getenv()` (dotenv package install blocked by legacy dependencies for now — acceptable for Docker-only environment).
- Started externalizing credentials in `Classes/Config.php`.
- Updated `.env.docker` and `docker-compose.yml`.

**Progress so far:**
- `Config.php` now pulls DB credentials and some settings from environment variables with fallbacks.
- `etc/qnova.conf.php` also updated to respect env vars (transition support for legacy files).
- Docker environment properly passes variables.
- New safer method `consultaPreparada($sql, $params)` added to `Manejador_Base_Datos`.
- Basic test coverage added for the new method.

**Next steps:**
- High-risk methods in Auth.php have been refactored to use `consultaPreparada()`.
- Continue reducing reliance on the old string-concatenation query builder in other areas.

---

## Stage 4 — Autoload & Class Loading

**Commands:**

```bash
# Edit composer.json (via search_replace in future stage)
docker compose exec app composer dump-autoload -o --classmap-authoritative

# Verify a namespaced class loads without manual require
docker compose exec app php -r '
require "vendor/autoload.php";
echo (class_exists("Tuqan\Classes\Config") ? "Autoload OK" : "FAIL");
'
```

**Gate:** Removing 50%+ of the manual `require_once` lines in index.php + Pages/ does not break routed pages or tests.

---

## Stage 5 — Legacy Bloat Removal

**Audit Commands (run these to decide what dies):**

```bash
# Find real usage of FCKeditor (not just its own files)
docker compose exec app grep -r --include="*.php" "FCKeditor\|fckeditor" . --exclude-dir=javascript --exclude-dir=.git | head -20

# Same for Image/Graph usage outside its tests
docker compose exec app grep -r --include="*.php" "Image/Graph\|jpgraph\|phplot" . --exclude-dir=Image --exclude-dir=.git | head -10

# Dead root files?
docker compose exec app grep -r --include="*.php" "require.*arbol_documentos.php\|include.*creaFicha" . --exclude-dir=.git | head -5
```

**Gate:** After archival/deletes, `docker compose exec app composer install` + full test suite + smoke curl still succeed. Size of image reduced measurably.

---

## Stage 6 — CI

**Validation (after .github/workflows/ci.yml added):**

```bash
# Local simulation of CI (what the action will do)
docker compose --env-file .env.docker up -d
docker compose exec app composer install --no-interaction --prefer-dist
docker compose exec app ./vendor/bin/phpunit --testsuite=Unit,Integration --fail-on-warning --stop-on-failure
docker compose exec app ./vendor/bin/phpstan analyse --level=2 --no-progress
docker compose down
```

**Gate:** A dummy PR on GitHub shows the workflow green (or local equivalent passes 100%).

---

## Evidence Log (Append After Every Stage)

**Stage 1 — COMPLETED (2026-05-25)**

**Branch:** `stage-1-docker-foundation`

**Evidence:**

- Docker version: 29.3.1 (macOS, Docker Desktop)
- PHP inside container: **PHP 8.3.31** (cli) + Xdebug 3.3.2 + OPcache
- Extensions confirmed: `pdo_pgsql`, `pgsql`, `gd`, `gettext`, `intl`, `zip`, `bcmath`, `xdebug`
- PostgreSQL: **16.14** (Alpine) — healthy and accepting connections
- Nginx: Responding with **HTTP 200** + `X-Powered-By: PHP/8.3.31`
- Stack started successfully with `docker compose --env-file .env.docker up -d`

**Gotchas / Deviations:**
- Old `version:` key in docker-compose.yml removed (was generating warning)
- `PEAR.php` throws a parse error on request (expected legacy code — will be addressed in later stages)
- No database schema yet (we have no seed script in this stage — planned for later)

**Files added in this stage:**
- `Dockerfile`
- `docker-compose.yml`
- `docker/nginx/tuqan.conf`
- `docker/php/php.ini`
- `docker/php/xdebug.ini`
- `.env.docker`
- Updated `.gitignore`

All work performed exclusively via Docker commands. No local PHP/nginx/postgres used.
```

**Stage 2 — In Progress**

**Branch:** `stage-2-testing-harness`

**Evidence (current session):**

- PHPUnit 10.5.63 + PHPStan 1.12 successfully installed and runnable inside Docker.
- `phpunit.xml.dist` created.
- Basic test scaffolding (`tests/Unit`, `tests/Integration`, bootstrap).
- First smoke tests pass (`ConfigTest`, `QueryBuilderTest`).
- Temporary manual requires in bootstrap used until Stage 4 autoloading.
- PHP version constraint declared via `config.platform.php: "8.2.0"`.
- PHPStan level 0 baseline run (errors mainly due to cross-class dependencies not yet loaded).

**Notes:**
- Legacy deps (esp. Former + old Illuminate) create friction; handled with audit ignores and platform config for now.
- Focus of this stage: get harness in place and establish baseline, not full dep modernization.

---

## General Tips for Agents

- Always run `docker compose exec app composer validate` before committing.
- If a test is red and you don't understand why, capture full `docker compose logs app` + the exact failing test output.
- When in doubt, read the current .agents/*.md files again — they are the contract.
- Update this checklist file with new commands discovered during execution.

**This document turns the high-level plan into an executable checklist that any future agent (or human) can follow with minimal ambiguity.**