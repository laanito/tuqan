# Tuqan

[![CodeFactor](https://www.codefactor.io/repository/github/laanito/tuqan/badge/master)](https://www.codefactor.io/repository/github/laanito/tuqan/overview/master) [![GitHub license](https://img.shields.io/github/license/laanito/tuqan.svg)](https://github.com/laanito/tuqan/blob/master/LICENSE.md) [![GitHub issues](https://img.shields.io/github/issues/laanito/tuqan.svg)](https://github.com/laanito/tuqan/issues)

# Tuqan

**Legacy ISO 9001 / ISO 14001 Management Application (2005 → 2026)**

Modernization project of a PHP 5.1 application into modern PHP standards.

---

## Current Status (April 27, 2026)

### ✅ Completed (Partial / Historical)
- Composer setup with modern dependencies
- Migration from PEAR::Auth to Jasny/Auth
- Routing implemented with Phroute
- Forms migrated from PEAR::QuickForm to Former (in some areas)
- Database layer migrated from PEAR::DB to PDO (see closed issue #21)
- Gettext-based internationalization (`es_ES`)

**Note:** Some items above (e.g. full PSR-4 autoloading and Bootstrap 5) were listed in earlier status but are incomplete or inaccurate as of 2026. A full audit and Docker-based modernization effort is now underway.

### 🔄 In Progress
- Complete removal of remaining PEAR dependencies
- Separation of mixed logic from legacy `.php` files
- General code cleanup and modernization

### 🚧 Important Notes
**The application is NOT functional and is NOT production ready.**

This is an ongoing modernization project of a very old codebase. It is currently in an intermediate state with significant legacy code, mixed concerns, and incomplete migrations.

---

## Development Environment (2026+)

**All development must be done using Docker.** No local PHP, nginx, or PostgreSQL should be used.

### Quick Start

```bash
# Start the full environment (PHP 8.3 + nginx + PostgreSQL)
docker compose --env-file .env.docker up -d --build

# Enter the PHP container
docker compose exec app bash

# Run composer
docker compose exec app composer install

# Check PHP version inside the container
docker compose exec app php -v
```

The environment includes:
- PHP 8.3.31 (FPM + CLI) with Xdebug
- Nginx
- PostgreSQL 16

See `.agents/DOCKER-ENV.md` and `.agents/STAGE-CHECKLISTS.md` for full details and validation commands.

**Note:** Stage 1 (Docker foundation) has been completed. Later stages will add a proper test harness and continue the modernization.

---

## Tech Stack

| Layer             | Old Technology          | Current Technology       | Status      |
|-------------------|-------------------------|--------------------------|-------------|
| PHP               | 5.1                     | 8.x                      | In progress |
| Database Access   | PEAR::DB                | **PDO**                  | ✅ Done     |
| Authentication    | PEAR::Auth              | Jasny/Auth               | ✅ Done     |
| Forms             | PEAR::QuickForm         | Former                   | ✅ Done     |
| Routing           | Custom                  | Phroute                  | ✅ Done     |
| Frontend          | Old HTML                | Bootstrap 3 (legacy)     | Partial     |
| Localization      | Manual                  | gettext                  | ✅ Done     |

---

## Project Goals

- Modernize the codebase while preserving original business logic
- Improve maintainability and security
- Follow a careful, step-by-step approach
- Document the full modernization process
- Do all the changes using agentic workflows

---

## How to Contribute / Work on the Project

**All work must be done inside the Docker environment.**

1. Clone the repository
2. Start the environment:
   ```bash
   docker compose --env-file .env.docker up -d --build
   ```
3. Install dependencies inside the container:
   ```bash
   docker compose exec app composer install
   ```
4. Make your changes (edit files locally — they are mounted into the container)
5. Test your changes using Docker commands only
6. Commit and open a Pull Request

**Rule of thumb**: Every significant change should maintain or improve existing functionality.

See `.agents/MIGRATION-PLAN.md` for the current modernization roadmap and `.agents/AGENTS.md` for contributor guidelines.

---

Last updated: May 2026  
Maintained by laanito + agentic workflow

> **Note**: A formal migration plan is being followed. See the documents in the `.agents/` directory for current status, Docker setup, and upcoming stages.
