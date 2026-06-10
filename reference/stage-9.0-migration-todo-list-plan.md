# Stage 9.0 Plan — Migration TODOs: Remaining Modules List (Daily Navigable Backlog)

**Status**: Planning phase
**Branch target**: `feat/stage-9.0-migration-todos` (fresh from origin/master)
**Driver**: The high-level MIGRATION-PLAN.md (architecture, stages, constraints) and STAGE-CHECKLISTS.md (detailed per-leg playbooks + evidence) are excellent and well maintained. However, per user review: "although well documented and useful this is not handy as a todo list". Before continuing more change legs, produce a single, scannable, checkbox-driven artifact listing *all remaining modules to migrate*. This becomes the primary daily navigation tool for picking reviewable-sized work and for handing off to other agents. Follow "new branch every leg", "plan first", "similarly sized change", ".agents/ updates before review", "push the leg".

## Goals (Aligned with Project Directives)
1. Create a practical, living "todo list of all the remaining modules to migrate" that any agent (or human) can open and immediately understand current state, suggested next legs, and exact scope for a small PR.
2. Make it navigable: grouped by functional area (from legacy top-level Aplicacion + Administración/Personalizacion), with per-item status, complexity, est. PR size, deps, verification hints, and links back to plans/checklists.
3. Capture the full inventory (done vs remaining) based on current menu (data-patches), registered routes (index.php), Pages/ + templates/ presence, and legacy entry points (root .php + arbol_*/cuestionario etc.).
4. Include a clear "how to use for daily work + handoff" + recipe for adding a new module leg.
5. Record the pending "Criterios Ambientales" (row 84) note in the actionable list.
6. Update MIGRATION-PLAN.md and STAGE-CHECKLISTS.md to point to the new list as the go-to for ongoing Stage 8.x+ legs; add 9.0 skeleton + evidence.
7. Keep this leg itself reviewable-sized and docs-only (plan + todos file + 2-3 pointer updates). No new PHP behavior.
8. Commit the plan document first on the branch; push the completed leg.

## Scope (Sized Similarly to Prior Legs)
- **New reference plan** (this file) — committed first.
- **Primary artifact**: `.agents/MIGRATION-TODOS.md` (or equivalent name) — comprehensive but concise structured backlog with:
  - Usage instructions (start of leg, update rules, handoff).
  - Snapshot of completed modern modules (with stage refs).
  - Grouped remaining modules by area (Aplicacion top-levels: Documentacion, Procesos, Proveedores, Equipos, Mejora, Formacion, Auditorias, Indicadores, Aspectos Ambientales + any Admin leftovers).
  - For each: legacy accion examples, key old files/tables, complexity (simple catalog vs tree/arbol vs workflow vs matrix), suggested approach (reuse catalog base? new base needed?), est. size for a leg, specific verification notes, checkbox.
  - Cross-cutting backlog (Twig upgrade, test extraction, PDF/Excel, full tree UI, questionnaire engine, etc.).
  - "Suggested next 3-5 legs" (small, reviewable, building on catalog base wins).
  - Recipe: "How to migrate one more module in a future leg".
- Pointer updates only:
  - MIGRATION-PLAN.md: update "Current Project Status" / Last Updated + add explicit "For daily leg planning and agent handoff, use .agents/MIGRATION-TODOS.md (this file is the architecture + history view)".
  - STAGE-CHECKLISTS.md: add short "## Stage 9.0 — Migration TODOs & Living Backlog (this leg)" section with todo skeleton, evidence (plan commit, file created, updates), and note that future stage sections will reference the TODOS list for scope.
- No changes to code, routes, patches, templates, or verify scripts (pure docs + inventory leg). This keeps it the "first step" before more changes.
- (Optional light) run php -l on index.php + git hygiene as verification.

**Expected size**: 1 plan + 1 substantial but focused TODOS.md (~150-250 lines with tables/checklists) + 2 doc edits. Comparable review volume to a small stage plan + evidence update (e.g. lighter than 8.9 code changes).

## Current Reality (Post 8.9)
- 14 modules have modern Pages/*/Listado + Formulario (most using Catalog* base post-8.9, a few special like Menus/Idiomas/Permisos): Usuarios, Perfiles, Sedes, Clientes, Criterios, TiposMejora, TiposAreas, TipoDocumento, TiposAmb, TiposImp, TipoCursos, Menus, Idiomas, Permisos. All have /admin/* + legacy /administracion/* routes + Twig templates.
- Personalizacion vertical under Aplicacion/Administracion is functionally complete for the catalog items (with 0017/0018 menu hygiene in 8.9).
- Top-level Aplicacion branches (from 0005-menu-cleanup + legacy ref + 0004 full menu) that are still legacy-driven: Documentación (66), Procesos (76), Proveedores (67), Equipos (70), Mejora (68), Formación (69), Auditorías (71), Indicadores (72), Aspectos Ambientales (73).
- Many core business files remain the primary implementation: arbol_documentos.php + related, cuestionario.php + procesa, editor.php, generacionPdf/GenPDF, crearExcel, graficaIndicadores.php, items.php, permisos_usuarios.php (partial), calendar bits, etc.
- LegacyAction + resolveLegacyAction in MainPage still catch unmigrated colon accions and render the old pages.
- Sidebar (buildSidebarMenuHtml) now renders the real (patched) menu_nuevo tree for logged-in users.
- Criterios Ambientales note (menu row 84 deleted as redundant section; should be restored as direct actionable under Personalizacion when full data processed).
- MIGRATION-PLAN and STAGE-CHECKLISTS have grown long with excellent history — exactly why a separate concise "current remaining work" view is now needed.
- All work continues Docker-only + init-db.sh + data_patches discipline.

## Detailed Tasks / Execution Order (Strict)
1. Write this plan (reference/stage-9.0-migration-todo-list-plan.md). Commit as the *first* thing on the new branch.
2. Create .agents/MIGRATION-TODOS.md with the designed handy format (see design notes in thinking trace; usage header, done snapshot, grouped backlog with checkboxes, cross-cuts, recipe, suggested order).
3. Update MIGRATION-PLAN.md (status note + pointer to the todos as daily list).
4. Update STAGE-CHECKLISTS.md (append 9.0 section skeleton modeled on prior: goal, selected scope, key changes (the two files), evidence commands (git log of plan, head of TODOS, docs diffs), gates, next).
5. Verify: clean branch state, no untracked, plan + todos files look good when read, optionally `docker compose exec app php -l index.php` (or full verify-8.6.sh if stack is convenient), confirm references are consistent.
6. Commit the implementation (plan was first; follow-up commit for the list + updates), push -u origin, report ready for PR.

## Success Criteria
- New branch `feat/stage-9.0-migration-todos` pushed with plan committed first.
- .agents/MIGRATION-TODOS.md exists, is the clear "go-to" list: one can open it and in <2 minutes know exactly what modules are left, which are small vs large, and what a good next leg looks like.
- All top-level legacy Aplicacion areas + known sub-modules surfaced from menu data + file scan are listed (no major omissions).
- Criterios Ambientales note is captured as an actionable item.
- MIGRATION-PLAN and STAGE-CHECKLISTS now explicitly delegate daily navigation / handoff to the new list while keeping their strengths (history, detailed commands, evidence).
- No behavior or code changes; pure enabling artifact for the "couple more legs" pace.
- PR of appropriate size; all .agents/ discipline followed; ready for user review + merge before next change leg.

## Risks & Mitigations
- Inventory incomplete: Mitigate by cross-referencing legacy-menu-structure.md, 0005/0010 patches (top levels + Personalizacion), index.php addRoute calls (modern surface), Pages/ + templates/ dirs, and root *.php files with "legacy" names. Call out "discovered during work" items as "add when found".
- List too long / overwhelming: Group strictly, use summary tables + "suggested next" shortlist at top, keep per-item bullets short (details in linked stage plans).
- Becomes stale: Rule in the file itself: "update on every leg before PR". Agents must edit it when scope changes.
- Overlaps with existing docs: This is deliberately the *backlog view*, not duplicating playbook commands or architecture rationale.

## Related
- Directly follows user request at end of 8.9 leg: "Before continuing more changes... create a todo list of all the remaining modules to migrate so we can navigate it for daily work (and eventually handle to other agents)".
- Prepares the ground for many future reviewable legs of similar size to 8.6-8.9.
- Reinforces agent evolution (Junior literal following checklists → Senior uses high-level plan + this living todos for autonomous scope selection).
- Part of Stage 8 "Core Functionality Modernization" (menu-driven population of real modules).

---

*Part of the ongoing menu-driven modernization (Stage 8.x+). June 2026.*

**Execution will be autonomous per AGENTS.md and the testing strategy. Plan committed first on the feature branch. Only circle back on blockers.**

**Plan written on the feature branch (first commit).**