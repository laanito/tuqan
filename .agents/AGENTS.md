# AGENTS.md - Instructions for Agents Working on Tuqan PHP Migration

**Project:** Tuqan (legacy ISO 9001/14001 management app)  
**Current Branch (as of plan creation):** `php-migration-plan-docker-testing`  
**Date:** 2026-05 (this session)  
**Core Rule:** ZERO source code, config, or infrastructure changes outside `.agents/` until this migration plan is reviewed, approved, and explicitly authorized by the user for a specific stage.

## Mandatory Rules for All Future Agents

1. **Docker Only for PHP/Nginx/Postgres**
   - NEVER run `php`, `composer`, `nginx`, `psql`, or any project command directly on the host macOS shell.
   - ALL execution, testing, building, and validation MUST use `docker compose` + `docker compose exec`.
   - Example: `docker compose exec app php -v` (never bare `php -v`).

2. **Documentation First, Then Changes**
   - Update the relevant `.agents/` doc (PLAN, STAGE-CHECKLISTS, etc.) BEFORE making any change in a stage.
   - Use `todo_write` tool for any task with 3+ steps.
   - Every stage gate requires green tests (in Docker) + updated docs.

3. **Branch & PR Discipline**
   - Create a feature branch from `php-migration-plan-docker-testing` (or latest main after merge) for each stage or sub-task.
   - Example: `git checkout -b stage-1-docker-foundation`
   - Never commit directly to `master` or the planning branch without review.
   - All changes via PR. Use MCP github tools only when explicitly pushing a PR.

4. **Verification Before Claiming Done**
   - Run the exact validation commands listed in STAGE-CHECKLISTS.md inside Docker.
   - Capture output and append to the stage doc as "Evidence".
   - Only mark a todo `completed` when validation passes and docs are updated.

5. **Test + Fix Loop — Root Cause Over Symptom Hiding (Primary Operational Mode)**
   - The default and mandatory way of working is **Test / Verify → Observe the real symptom → Fix the Root Cause in source code → Re-verify**, repeated until the symptom is gone at the source.
   - **Never hide symptoms** with short-circuits, bypasses, early returns before the problematic code, `error_reporting` suppression, `XDEBUG_MODE=off`, comments that silence issues, or any other workaround whose only purpose is to make the immediate observation (curl, test run, etc.) look clean.
   - For **simple pages or smoke scenarios** (e.g. home page rendering, basic navigation, login flow smoke): It is acceptable and preferred to drive the iteration using `curl`, `docker compose exec`, or lightweight shell commands as the verification mechanism.
   - For **any non-trivial functionality**, business logic, reusable component, or complex flow: You **must** drive the "Test + Fix" loop primarily with unit tests, characterization tests, or automated smoke/integration tests (not just manual curl or ad-hoc commands).
   - Only claim a task or PR complete when the verification (curl for simple cases, proper tests for complex ones) demonstrates that the root cause has been eliminated in source and no hiding remains.

6. **Preserve Business Logic**
   - Do not rewrite core ISO workflows (document trees, risk matrices, forms, permissions, questionnaires) from scratch.
   - Use strangler-fig / wrapper patterns when modernizing.
   - Every change must have a test (new or regression) that exercises the original logic.

7. **Tool Usage**
   - Prefer dedicated tools: `read_file`, `search_replace`, `list_dir`, `grep` over raw `cat`/`sed`/`grep` in terminal (except for docker commands and git).
   - For docker/php execution: ONLY via `run_terminal_command` with `docker compose ...`.
   - Use `todo_write` at start of any multi-step work.

8. **.agents/ as Single Source of Truth**
   - This directory contains the living plan. Update it after every significant step.
   - Do not create scripts or helpers outside `.agents/` until a stage authorizes it (e.g., a `bin/` dir added in Stage 1).

## How to Execute This Plan (Future Agent Workflow)

1. Read `.agents/MIGRATION-PLAN.md` and `.agents/STAGE-CHECKLISTS.md` fully.
2. Pick the next pending stage.
3. Create branch for that stage.
4. Open todo list using `todo_write` (copy items from the stage checklist).
5. Perform work **only inside Docker**.
6. Validate with commands in the checklist.
7. Update docs with results + evidence.
8. Commit only `.agents/` updates + any stage-authorized files.
9. Open PR with link to updated plan docs.
10. Mark stage complete only after user approval.

## Current Status Summary (from this session's audit)

- App is **not functional** in its current state on host.
- Significant partial modernization exists (namespacing, Phroute, Jasny/Auth, PDO wrapper, some Pages/) but incomplete and inconsistent (e.g. Bootstrap 3 still in templates, no PSR-4 autoload configured despite README claims).
- Heavy legacy debt: FCKeditor (3.2MB, abandoned), Image/Graph (2.5MB old lib), 36 root-level .php files, custom query builder using `addslashes` + string concat (SQLi risk), duplicated hardcoded config.
- Zero tests. No Docker. No modern CI.
- Postgres DB with old dumps (LATIN1/UNICODE mix).
- See MIGRATION-PLAN.md for full inventory and risks.

## References

- Full analysis performed in this session via local file reads, greps, and git commands (no host PHP executed for the app).
- Previous `.agents/` content (phase-0-*) was reviewed and archived/deleted per user permission to create a clean, executable plan.

**Last Updated:** This session (2026-05-25 context)  
**Next Agent Action:** Read MIGRATION-PLAN.md and begin Stage 1 only after explicit user go-ahead.