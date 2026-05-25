# Testing Harness Specification — Tuqan

**Rule:** All test execution happens inside the `app` container via `docker compose exec app vendor/bin/phpunit ...`. No host PHPUnit.

## Tool Choices & Rationale

- **PHPUnit 10/11** — industry standard, excellent Docker + coverage support, works with legacy code.
- **Pest (optional layer on top of PHPUnit)** — for new greenfield tests only; do not force on legacy tests.
- **PHPStan** (level 0 → 5 gradual) — catches type and dead code issues without runtime.
- **Test DB Strategy:** Separate test database (qnova_test) or schema + transaction rollback. Never run tests against dev DB.
- **No heavy browser e2e initially** — legacy JS tree + FCKeditor makes it brittle. Add Symfony Panther or Playwright only after UI stabilization (Stage 8+).

## Setup Steps (Stage 2 — Exact Commands)

From host:

```bash
# 1. Ensure docker stack is running
docker compose --env-file .env.docker up -d

# 2. Install dev tools (inside container)
docker compose exec app composer require --dev \
    phpunit/phpunit:^10.5 \
    phpstan/phpstan:^1.11 \
    --with-all-dependencies

# 3. Create phpunit.xml.dist (see below)
# 4. Create tests/ structure
docker compose exec app mkdir -p tests/Unit/Classes tests/Integration/Database tests/Integration/Auth tests/Fixtures

# 5. Add test script to composer.json (for convenience)
# "scripts": { "test": "phpunit --configuration phpunit.xml.dist" }
```

## phpunit.xml.dist (Root)

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/10.5/phpunit.xsd"
         bootstrap="tests/bootstrap.php"
         cacheResult="false"
         colors="true"
         stderr="true">
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Integration">
            <directory>tests/Integration</directory>
        </testsuite>
    </testsuites>

    <coverage>
        <report>
            <html outputDirectory="build/coverage"/>
            <text outputFile="php://stdout"/>
        </report>
    </coverage>

    <php>
        <env name="APP_ENV" value="test"/>
        <env name="DB_NAME" value="qnova_test"/>
        <env name="DB_USER" value="qnova"/>
        <env name="DB_PASS" value="secret"/>
        <!-- Tell Config class or bootstrap to use test DB -->
    </php>
</phpunit>
```

## tests/bootstrap.php (Minimal at Start)

```php
<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// TODO Stage 3: load .env, call Config::initialize with test overrides
// For now, just enough to not fatal on require of namespaced classes
session_start(); // many legacy paths assume it
```

## Example Tests (Create These in Stage 2)

### 1. Unit test for Config (simple, no DB)

`tests/Unit/Classes/ConfigTest.php`

```php
<?php
declare(strict_types=1);

namespace Tuqan\Tests\Unit\Classes;

use PHPUnit\Framework\TestCase;
use Tuqan\Classes\Config;

final class ConfigTest extends TestCase
{
    public function testInitializeSetsDefaults(): void
    {
        Config::initialize();
        $this->assertNotEmpty(Config::$sDbEtc);
        $this->assertSame(5432, Config::$iPuertoEtc);
        $this->assertIsArray(Config::$aCharset);
    }
}
```

### 2. Integration test skeleton (uses real test DB connection)

`tests/Integration/Database/QueryBuilderTest.php`

```php
<?php
declare(strict_types=1);

namespace Tuqan\Tests\Integration\Database;

use PHPUnit\Framework\TestCase;
use Tuqan\Classes\Manejador_Base_Datos;

final class QueryBuilderTest extends TestCase
{
    private Manejador_Base_Datos $db;

    protected function setUp(): void
    {
        // In real impl: use docker env vars or test container
        $this->db = new Manejador_Base_Datos(
            getenv('DB_USER') ?: 'qnova',
            getenv('DB_PASS') ?: 'secret',
            getenv('DB_NAME') ?: 'qnova_test',
            getenv('DB_HOST') ?: 'db'
        );
    }

    public function testCanConnectAndRunBasicSelect(): void
    {
        // Use existing table from seed (e.g. 'idiomas' or 'usuarios')
        $this->db->iniciar_Consulta('SELECT');
        $this->db->construir_Campos(['id', 'login']);
        $this->db->construir_Tablas(['usuarios']);
        $this->db->consulta();

        $row = $this->db->coger_Fila();
        $this->assertIsArray($row);
    }
}
```

**Important:** These tests will initially fail or need adjustment because the query builder expects specific session state and DB schema. That is expected — the act of making them pass is part of the migration.

## Running Tests (Canonical Commands)

```bash
# All
docker compose exec app ./vendor/bin/phpunit --configuration phpunit.xml.dist

# Specific suite
docker compose exec app ./vendor/bin/phpunit --testsuite=Unit

# With coverage
docker compose exec app ./vendor/bin/phpunit --coverage-text

# PHPStan (progressive)
docker compose exec app ./vendor/bin/phpstan analyse Classes/ Pages/ --level=0
docker compose exec app ./vendor/bin/phpstan analyse --configuration phpstan.neon --level=2
```

## Test Database Strategy

Option A (recommended early): Use a second service in docker-compose for testing or just a different DB name on the same postgres container.

```yaml
# In docker-compose.yml (test profile or separate)
  db_test:
    image: postgres:16-alpine
    environment:
      - POSTGRES_DB=qnova_test
      ...
    volumes:
      - tuqan_test_pgdata:/var/lib/postgresql/data
```

Before integration tests: `docker compose exec db_test psql ... < /docker-entrypoint-initdb.d/minimal_seed.sql`

Use transactions + rollback in TestCase base class for speed.

## Characterization / Regression Tests for Legacy Code

For high-risk areas (permissions, risk formula, document lifecycle):

1. Identify a pure-ish function or a complete "happy path" call chain.
2. Write a test that calls the old entry point (e.g. include the old procesa_ file or call the method) with known inputs from fixtures.
3. Assert exact output or DB state changes.
4. Only then refactor the inside.

Example target: the environmental matrix formula in etc/qnova.conf.php and wherever it is evaluated.

## Coverage & Quality Gates (Enforced in Later Stages)

- New code: 70%+ line coverage required before merge.
- Refactored legacy: at least one characterization test that would have caught the old behavior.
- `composer audit` inside Docker must show 0 critical vulns.
- PHPStan level increases only when current level is clean.

## Fixtures & Data

Store under `tests/Fixtures/`:
- minimal_schema.sql (stripped version of the dump)
- usuarios.json, documentos_sample.json (anonymized)
- expected_pdf_output/ (binary snapshots for later)

Load via helper in bootstrap or per-test `loadFixture('usuarios')`.

## Continuous Validation

In Stage 6 (CI) the GitHub Action will run exactly:

```bash
docker compose --env-file .env.docker up -d db
docker compose exec -T db ... wait for ready ...
docker compose exec app composer install --no-interaction
docker compose exec app ./vendor/bin/phpunit --testsuite=Unit,Integration --fail-on-warning
docker compose exec app ./vendor/bin/phpstan analyse --level=2 --no-progress
```

## Anti-Patterns to Avoid

- Do not test private methods by default (test behavior).
- Do not share mutable state between tests (use setUp/tearDown + transactions).
- Never rely on real production data in tests.
- Do not skip the "make test fail first" step when changing logic.

This harness is designed to give future agents confidence to change 18-year-old code without fear.