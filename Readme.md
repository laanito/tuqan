# Tuqan

[![CodeFactor](https://www.codefactor.io/repository/github/laanito/tuqan/badge/master)](https://www.codefactor.io/repository/github/laanito/tuqan/overview/master) [![GitHub license](https://img.shields.io/github/license/laanito/tuqan.svg)](https://github.com/laanito/tuqan/blob/master/LICENSE.md) [![GitHub issues](https://img.shields.io/github/issues/laanito/tuqan.svg)](https://github.com/laanito/tuqan/issues)

# Tuqan

**Legacy ISO 9001 / ISO 14001 Management Application (2005 → 2026)**

Modernization project of a PHP 5.1 application into modern PHP standards.

---

## Current Status (April 27, 2026)

### ✅ Completed
- Composer setup with modern dependencies
- Migration from PEAR::Auth to Jasny/Auth
- PSR-4 autoloading structure (`Classes/`)
- Routing implemented with Phroute
- Forms migrated from PEAR::QuickForm to Former
- Frontend updated to Bootstrap 5
- Database layer fully migrated from PEAR::DB to PDO (see closed issue #21)
- Gettext-based internationalization (`es_ES`)
- Partial cleanup of old PEAR remnants

### 🔄 In Progress
- Complete removal of remaining PEAR dependencies
- Separation of mixed logic from legacy `.php` files
- General code cleanup and modernization

### 🚧 Important Notes
The application is **not functional**.  
It is in an intermediate modernization state. Many legacy patterns still exist.

---

## Tech Stack

| Layer             | Old Technology          | Current Technology       | Status      |
|-------------------|-------------------------|--------------------------|-------------|
| PHP               | 5.1                     | 8.x                      | In progress |
| Database Access   | PEAR::DB                | **PDO**                  | ✅ Done     |
| Authentication    | PEAR::Auth              | Jasny/Auth               | ✅ Done     |
| Forms             | PEAR::QuickForm         | Former                   | ✅ Done     |
| Routing           | Custom                  | Phroute                  | ✅ Done     |
| Frontend          | Old HTML                | Bootstrap 5              | ✅ Done     |
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

1. Clone the repository
2. Run `composer install`
3. Configure the database connection
4. Test changes thoroughly before pushing

**Rule of thumb**: Every significant change should maintain or improve existing functionality.

---

Last updated: April 27, 2026  
Maintained by laanito + agentic workflow
