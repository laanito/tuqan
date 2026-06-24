# Stage 9.8 — Cross-cut base extraction (richer Catalog* bases)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.8-cross-cut-base-extraction` (fresh from master)
**Driver**: Top item from living `.agents/MIGRATION-TODOS.md` Suggested Next after 9.7 (Aspectos Ambientales basic). "**Cross-cut extraction** ... Strongly primed now." (Catalog* was 8.9; we now have many complex modules 9.2–9.7 that duplicate ShowPage / Procesar / mapping logic).

Follows the exact process ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size).

## Goals (Aligned with Directives)
1. Advance the **cross-cut priority**: Extract / enhance base classes so later legs (and refactors) stay tiny.
2. Address the specific callout: next candidates include "list-with-filters base, form-with-relations base".
3. Target the concrete duplication pain: every rich module (Mejora, Formacion, Aspectos, Auditorias Programa, etc.) copies 60-80 lines of DB connection, Twig setup, flash handling, load logic, and persist logic.
4. Deliver improved `CatalogListado` and `CatalogFormulario` with protected hooks while preserving 100% backward compatibility for simple catalog modules.
5. Update living docs + full 9.8 playbook.
6. 100% Docker-only, plan-first, clean verification.

## Scope (Reviewable cross-cut slice)
**In:**
- Enhance `Pages/Catalog/CatalogListado.php` and `CatalogFormulario.php`:
  - Add protected helpers: `getDb()`, `getSidebarMenu()`, `getCurrentUserContext()`, `loadItem($id)`, `getListItems()`, `buildListVariables($items)`, `persistItem(array $data, $id)`, `getValidationErrors(...)`, etc.
  - Keep the default simple `getSelectSql()` / `mapRow()` for id+nombre+activo modules.
  - Make the full ShowPage / Procesar use the new hooks so subclasses only implement the domain parts.
- Update phpdoc + class comments explaining the evolution (simple vs rich usage).
- Optionally demonstrate on **one** rich module (e.g. light refactor of `Pages/Aspectos/*` or `Pages/Mejora/*` to use new helpers) if it fits reviewable size.
- Extend `scripts/verify-8.6.sh` (php -l on Catalog files).
- Full "Stage 9.8 Verification Playbook" in STAGE-CHECKLISTS.md.
- Update `MIGRATION-TODOS.md` (mark cross-cut progress, refresh Suggested), `MIGRATION-PLAN.md`.
- Plan doc (this) as first commit.

**Out (explicit future legs or follow-ups):**
- Full "list-with-filters" UI (search, pagination improvements, etc.).
- "form-with-relations" (FK selects, multi-selects, sub-forms).
- Tree-view base or matrix/batch base (Permisos-style).
- Large-scale refactor of all existing rich modules.
- New module using the improved base from day one.

## Pattern to Follow
- Same ritual discipline as 9.0–9.7, even though this is cross-cutting rather than a new vertical.
- Plan first.
- No data patch needed (pure extraction / enhancement).
- Keep all existing behavior identical for simple modules (Clientes, Tipos*, Sedes, etc.).
- The enhancement should make rich modules dramatically shorter (see current duplication in Aspectos/Formulario ~120 lines of boilerplate that could be 20-30 lines).
- Verification focuses on: no breakage on existing modules + php -l + clean-room + spot-check that a rich module still works.

## Analysis (to be performed on branch)
- Current simple path: id, nombre, activo.
- Rich patterns across 9.x:
  - Extra columns in SELECT + mapRow (vigente, revision, scores, flags, dates, text).
  - Full custom ShowPage (almost identical DB + session + Twig setup + flash + render).
  - Full custom Procesar (POST guard, load id, collect many fields, validate, UPDATE/INSERT with many ?, redirect + flash).
- Goal of extraction: protected methods that the current rich overrides can call or override in small pieces.

## Verification Checklist (Non-Interactive + Human Gate)
- php -l on Catalog/* + any touched Pages + index.php + verify.
- After clean init: all prior tables/rows/patches still correct (no data change).
- verify-8.6.sh green (including new Catalog checks, all previous invariants).
- Existing simple modules unaffected (e.g. /admin/clientes, /admin/tiposmejora still load and work).
- At least one rich path (e.g. /admin/aspectos or /admin/mejora) still fully functional (list + create + edit + flash + persistence).
- No PHP errors, flashes work, redirects correct.
- Living docs updated (cross-cut item advanced).

## Files To Be Created/Modified
**New**:
- `reference/stage-9.8-cross-cut-base-extraction-plan.md` (this; first commit)

**Modified** (core):
- `Pages/Catalog/CatalogListado.php`
- `Pages/Catalog/CatalogFormulario.php`
- (Possibly light changes to 1 example rich Page pair)
- `scripts/verify-8.6.sh`
- `.agents/STAGE-CHECKLISTS.md` (full 9.8 section)
- `.agents/MIGRATION-TODOS.md`
- `.agents/MIGRATION-PLAN.md`

## Detailed Execution Order (Strict — Plan First)
1. Write + commit this plan as absolute first change on the branch.
2. Analyze duplication (read recent rich Pages + current bases).
3. Implement enhancements in the two Catalog base files.
4. Update comments/phpdoc.
5. (Optional) Light refactor of one rich module to prove the helpers.
6. Extend verify script.
7. Write full 9.8 playbook in STAGE-CHECKLISTS.
8. Update TODOS + MIGRATION-PLAN (note cross-cut progress).
9. Full clean-room verification + spot checks on rich + simple modules.
10. Logical commits, push -u, open PR referencing the cross-cut TODOS item + this plan.

## Success Criteria
- Plan is the first commit.
- Bases are enhanced but 100% backward compatible.
- Rich modules can now be much smaller when written or refactored.
- verify-8.6.sh + php -l + clean room all green.
- No regressions on any existing functionality (simple catalogs + previously delivered rich ones).
- TODOS shows clear progress on cross-cut extraction; Suggested is updated for what comes after (e.g. deeper verticals or more cross-cut).
- PR is self-contained and reviewable.

## Risks & Mitigations
- Breaking existing simple modules: Mitigated by keeping default implementations identical and testing them in verify.
- Scope creep into full filters/relations: Explicitly limited to reducing boilerplate in current patterns.
- "Too small" change: The duplication is the real pain point after 6+ rich modules; making the base better now prevents future bloat.

## Related / Handoff
- Directly addresses the #1 cross-cutting item that has been waiting "after a few complex modules".
- After this: Cross-cut item can be partially or fully closed; Suggested will promote Indicadores, Procesos, deeper subs on existing (Mejora full, Aspectos matrix, Documentación tree, etc.).
- Future legs will be smaller thanks to this.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x). Uses the daily MIGRATION-TODOS navigation.*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**