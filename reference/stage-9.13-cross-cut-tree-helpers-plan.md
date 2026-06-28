# Stage 9.13 — Cross-cut tree helpers (first delivery for arbol views)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.13-cross-cut-tree-helpers` (fresh from master)
**Driver**: Suggested Next Legs after 9.12 (Documentación tree first slice). Explicitly "Cross-cut tree helpers" now that both Procesos (9.11) and Documentación (9.12) have modern tree/arbol views. The cross-cutting backlog calls out "Tree / arbol UI + generators" and "more base class extraction (tree...)" as open.

Follows the exact process ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size). Leverages the 9.8 helpers pattern.

## Goals (Aligned with Directives)
1. Deliver first cross-cut for tree/arbol views: extract common patterns from the two existing Arbol implementations into CatalogListado (or small dedicated helpers) to reduce duplication for future tree modules.
2. Refactor Pages/Procesos/Arbol and Pages/Documentacion/Arbol to use the new helpers (keep behavior identical, smaller code).
3. No data patch (existing tables only).
4. Improve maintainability for tree logic (hierarchy resolution, grouping, common build variables).
5. Extend verify + full "Stage 9.13 Verification Playbook".
6. Update living MIGRATION-TODOS (advance cross-cut tree item), MIGRATION-PLAN, STAGE-CHECKLISTS.
7. 100% Docker-only, plan-first commit, clean verification, reviewable PR.

## Scope (Reviewable cross-cut slice)
**In:**
- Add protected helper methods to CatalogListado (e.g. fetchItems with map, buildTreeVariables, resolveParentNames, groupItemsBy, common ShowPage tree variant or extractable parts).
- Refactor the two Arbol classes to delegate to helpers (e.g. use new buildArbolVariables, shared fetch logic).
- Keep exact current output/behavior for both tree views.
- No changes to templates (or minimal if variables stay the same).
- Routes unchanged (existing /arbol paths).
- Extend scripts/verify-8.6.sh (php -l on Catalog + the Arbols).
- Full 9.13 playbook in STAGE-CHECKLISTS.
- TODOS flip + Suggested refresh (next could be more cross-cuts like filters/relations, or next vertical).
- MIGRATION-PLAN note.
- This plan (first commit).

**Out (explicit future legs):**
- Full tree base class (TreeListado extending CatalogListado).
- Tree editing / drag-drop support.
- Integration with legacy generators (arbol_documentos, generador_arboles).
- Matrix helpers or other cross-cuts.
- New tree views for other modules.

## Pattern to Follow
- Same as 9.8 (first cross-cut extraction): plan first, extract common helpers from existing rich modules, update the modules to use them (code gets shorter).
- Leverage existing 9.8 helpers (getDb, getSidebarMenu, getUserContext, getFlashData, fetchItems, buildListVariables).
- Keep the "shell" nature: trees still delegate heavy parts.
- Verification: clean room + psql (no change), verify-8.6.sh (passes for trees), php -l, browser confirm both trees look/work exactly as before + no regression.
- Update living TODOS in the PR.

## Data Discovery
- Procesos Arbol (9.11): padre-based hierarchy, resolve parent names, attach contenido summaries. Custom fetchArbolItems + buildArbolVariables + ShowPage boilerplate.
- Documentacion Arbol (9.12): group by tipo_documento/area, fetchTreeItems + buildTreeVariables + ShowPage boilerplate.
- Both heavily duplicate: Config init, Twig loader, sidebar, user context, flashes, build variables merge, render.
- CatalogListado already provides: getDb, getSidebarMenu, getUserContext, getFlashData, fetchItems, buildListVariables, ShowPage.
- Opportunity: generalize build* for trees, extract common fetch/build, perhaps a buildTreeData or resolveHierarchy helper.

## Verification Checklist (Non-Interactive + Human Gate)
- php -l on CatalogListado + both Arbols + index.
- After clean init: no data changes expected.
- verify-8.6.sh green (tree views still pass their asserts implicitly via no breakage).
- Browser (after login):
  - /admin/procesos/arbol and /admin/documentacion/arbol render exactly as before (hierarchy/grouping, links, notes).
  - Flat lists and other modules unaffected.
  - No visual or functional change.
- No PHP errors; priors untouched.
- Code size in Arbols reduced.

## Files To Be Created/Modified
**New**:
- reference/stage-9.13-cross-cut-tree-helpers-plan.md (this; first)

**Modified**:
- Pages/Catalog/CatalogListado.php (new protected helpers)
- Pages/Procesos/Arbol.php (refactor to use helpers)
- Pages/Documentacion/Arbol.php (refactor to use helpers)
- scripts/verify-8.6.sh (php -l updates)
- .agents/MIGRATION-TODOS.md (advance cross-cut, refresh Suggested)
- .agents/STAGE-CHECKLISTS.md (full 9.13 section)
- .agents/MIGRATION-PLAN.md (Last Updated)

## Detailed Execution Order (Strict — Plan First)
1. Write + commit this plan as absolute first change.
2. Add tree helpers to CatalogListado (buildArbolVariables, resolveParentNames, groupByKey, etc.).
3. Refactor both Arbols to use them (smaller, no behavior change).
4. Extend verify script.
5. Update all .agents/ (TODOS first).
6. Full verification (clean room + psql + verify-8.6.sh + php -l + browser tree confirmation + no regression).
7. Logical commits, push -u, open PR referencing the TODOS cross-cut item + this plan.
8. (Post-merge) Human browser sign-off.

## Success Criteria
- Plan first commit.
- Both tree views identical in output and behavior.
- Duplication reduced in Arbol classes (use base helpers).
- verify + psql + php -l green; no regression on anything (incl. 9.11/9.12 trees and bases).
- Living TODOS has cross-cut tree item marked + Suggested refreshed.
- Clear comments in code on the extraction.

## Risks & Mitigations
- Behavior drift: copy exact current logic into helpers first, test via browser + psql.
- Scope creep: only helpers + the two existing Arbols; no new trees, no template changes, no editing.
- Future trees: this is "first delivery" per the 9.8 pattern — more (base class, filters) can follow.

## Related / Handoff
- Delivers the "Cross-cut tree helpers" item.
- After: can do more cross-cuts (tree editing helpers, relations) or pick a new vertical (Mejora full, Aspectos matrix, Formación remaining, Auditorías execution).
- Reinforces incremental cross-cut + leveraging prior tree shells.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**