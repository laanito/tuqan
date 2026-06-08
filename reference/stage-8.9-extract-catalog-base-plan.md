# Stage 8.9 Plan — Extract Shared Catalog Base Class (Reduce Repetition)

**Status**: Planning phase
**Branch target**: `feat/stage-8.9-extract-catalog-base` (fresh from origin/master)
**Driver**: After completing the Aplicacion/Personalizacion catalog modules in 8.5-8.8, the work became noticeably repetitive (user feedback: "this is repetitive work"). Extract a shared base to make the ~12 simple id/nombre/activo modules (and any future ones) much smaller while preserving exact original behavior, flash keys, templates, and routes. This is the natural "more extraction of logic" step called out in prior checklists. Keep PR size reviewable and similar to 8.6-8.8.

## Goals (Aligned with Previous User Directives)
1. Dramatically reduce boilerplate in the existing catalog modules (Perfiles, Sedes, Clientes, Criterios, TiposMejora, TiposAreas, TipoDocumento, TiposAmb, TiposImp, TipoCursos, Usuarios, and similar) by introducing clean base classes.
2. Make adding future simple master-data modules trivial (new module = ~15-25 lines instead of ~70-80).
3. Preserve 100% of current behavior (flash keys, variable names in templates, page titles, error messages, DB queries, redirects, legacy+modern routes).
4. Maintain the established verification discipline (Docker-only, verify script, playbook, psql asserts).
5. Update all .agents/ and reference docs. Commit the plan first on the branch.
6. Address the repetition noted after 8.8 without a massive rewrite.

## Scope (Sized Similarly to Previous Legs for Pace)
- Introduce `Pages/Catalog/CatalogListado.php` (abstract base) and `Pages/Catalog/CatalogFormulario.php` (abstract base) containing the common logic (Twig + sidebar setup, Manejador_Base_Datos connection from session/env, common flash handling pattern, error rendering, fullName calc, etc.).
- Refactor the catalog modules to extend the bases. Each specific Listado/Formulario becomes very small (declare $table, $title, $templatePrefix, $flashPrefix, and any overrides).
- Modules in scope (the simple nombre/activo ones):
  - Perfiles, Sedes, Clientes, Criterios
  - TiposMejora, TiposAreas, TipoDocumento, TiposAmb, TiposImp, TipoCursos
  - Usuarios (if pattern matches closely)
- Out of scope for this PR (to keep size reasonable):
  - Idiomas, Menus, Permisos (they have different editing patterns — matrix/batch — can be a later extraction).
  - No changes to templates (they stay per-module for now; the variable name like 'tiposmejora' or 'clientes' stays the same).
  - No behavior changes, no new tests beyond the existing verify+playbook strategy.
  - No DB or route changes.

**Expected size**: Touches the base + ~10-12 small refactors + verify extension + docs. Comparable reviewable volume to 8.6/8.7/8.8 (many files but each diff is tiny and mechanical).

## Current Reality (Post 8.8)
- All the above modules follow an extremely consistent ~70-80 line pattern in Listado and Formulario (see the repetition in ShowPage, Procesar, DB connection, flash keys with module prefix, variables array, template load, error catch).
- Templates are also nearly identical (table with ID/Name/Estado/Editar, form with nombre + activo, flashes, stage note).
- The local-model experiment on a prior branch attempted a "Stage 8.9 CatalogModule base" but produced git chaos (detached HEAD, untracked docker/scripts, etc.). We are now doing it cleanly on a fresh branch with proper planning and verification.
- Checklists explicitly call for "More extraction of logic for real unit tests" and deeper work after the catalog modules are complete.
- No existing Catalog base on master (clean state after stash of the previous experiment).

## Detailed Tasks
1. **Plan document** (this file) — written and committed first on the branch.
2. **Base classes**:
   - `Pages/Catalog/CatalogListado.php` — abstract, contains the common ShowPage skeleton. Subclasses provide protected $table, $title, $templateDir, $flashPrefix (and optionally override getItemsQuery() etc.).
   - `Pages/Catalog/CatalogFormulario.php` — abstract, contains common ShowPage($id) + Procesar logic. Subclasses provide the same config + table name.
3. **Refactor modules** (in small batches for reviewability):
   - Convert each Listado to `class Listado extends \Tuqan\Pages\Catalog\CatalogListado { ... }` + minimal config.
   - Same for Formulario.
   - Keep exact same flash key naming (e.g. 'tipomejora_flash_success') and template variable names so templates and existing code are untouched.
4. **Verify & docs**:
   - Extend `scripts/verify-8.6.sh` (php -l on bases + refactored files, DB checks remain the same).
   - New full "Stage 8.9 Verification Playbook" section in `.agents/STAGE-CHECKLISTS.md` (todo skeleton, commands, evidence gates, browser flows).
   - Update `.agents/MIGRATION-PLAN.md` (mark 8.8 complete, add 8.9 entry).
   - Minor updates to docker/db-init/README.md if any new notes, root README if it mentions stages.
5. **Testing**:
   - All work inside Docker.
   - After refactors: `docker compose exec app php -l` on every changed file + bases.
   - Run the verify script.
   - psql asserts (tables still have the same data).
   - Manual flows via browser (or LegacyAction paths) + post-action DB checks.
   - No regressions on any catalog (create/edit/list still work, flashes, sidebar, legacy routes).

## Risks & Mitigations
- Too many files touched: Mitigate by doing the base + 2-3 modules in first commit after plan, then batches. Each specific file diff will be very small (removal of boilerplate).
- Template variable names: We deliberately keep them per-module (no change to templates).
- Flash keys: Keep the exact module-prefixed keys the templates and existing code expect.
- "Preserve original Stages": The bases are additive; the individual classes still exist and can be further refactored later if desired.
- Local model precedent: This time we follow the established process (plan first, Docker-only, todo tracking, full docs + playbook).

## Execution Order (Strict)
1. Write this plan + supporting reference (this file). Commit as first thing on the new branch.
2. Create the two base classes in Pages/Catalog/.
3. Refactor the catalog modules (batch commits if needed).
4. Extend verify script + add the full 8.9 playbook section to STAGE-CHECKLISTS.
5. Update MIGRATION-PLAN and other docs.
6. Full Docker verification (php -l inside container, verify script, psql, exercise flows).
7. Commit in logical chunks, push, open PR.

## Success Criteria
- All catalog modules now extend the shared bases; individual Listado/Formulario files are dramatically smaller (target ~15-25 lines each).
- No behavior change: same queries, same flash keys, same redirects, same templates, same legacy+modern route support.
- `docker compose exec app ./scripts/verify-8.6.sh` (extended) passes.
- New 8.9 section in STAGE-CHECKLISTS with reproducible commands + evidence.
- MIGRATION-PLAN updated.
- Clean PR of reviewable size; all Docker-only; docs updated before review request.
- Future simple catalog module would require only a new subdir + tiny extending class + template + route entry.

## Related
- Directly addresses user feedback after 8.8 ("this is repetitive work").
- Continues the "more extraction of logic" direction noted in 8.8 checklists.
- Prepares the ground for deeper business logic or other top-level branches with less duplication tax.
- The previous local-model attempt at this exact idea (on a different branch) serves as a cautionary tale — we are now executing it with the proper agentic discipline.

---

*Part of the ongoing menu-driven modernization (Stage 8.x). June 2026.*

**Execution will be autonomous per AGENTS.md and the testing strategy once branch created. Only circle back on blockers.**

**Plan written on the feature branch (first commit).**