# Stage 9.16 — Cross-cut: full tree base (CatalogTree helpers + base class)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.16-cross-cut-full-tree-base`
**Driver**: Suggested Next Legs after 9.15 (filters/relations cross-cut). "More cross-cuts (full tree base, relations polish)". The cross-cutting backlog explicitly lists "full tree base" as remaining work after 9.8 base extraction + 9.13 tree helpers + tree shells in 9.11/9.12.

Follows the exact process ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size). Continues the pattern of incremental base class extraction.

## Goals (Aligned with Directives)
1. Deliver the "full tree base" cross-cut: introduce a proper base for tree/arbol views (e.g. CatalogTree or enhanced helpers in CatalogListado) so the existing Procesos/Arbol and Documentacion/Arbol (and future trees) can inherit more common logic.
2. Extract common tree patterns: tree data fetching, hierarchy building, variable building, rendering boilerplate.
3. Refactor the two existing Arbol classes to extend/use the new base (behavior identical, less duplication).
4. No data patch or route changes.
5. Extend verify + full "Stage 9.16 Verification Playbook".
6. Update living MIGRATION-TODOS (advance the full tree base item), MIGRATION-PLAN, STAGE-CHECKLISTS.
7. 100% Docker-only, plan-first commit, clean verification, reviewable PR.

## Scope (Reviewable cross-cut slice)
**In:**
- New or enhanced base: Pages/Catalog/CatalogTree.php (abstract, extends CatalogListado or standalone with shared methods) or additional protected methods in CatalogListado for full tree support.
- Common helpers: e.g. buildTreeData(), fetchTreeItems(), buildTreeVariables(), renderTreeTemplate(), common hierarchy resolution beyond the 9.13 ones.
- Refactor Pages/Procesos/Arbol.php and Pages/Documentacion/Arbol.php to use the new base (smaller code, consistent).
- Update class docs/comments to mark "full tree base" as delivered.
- Extend scripts/verify-8.6.sh (php -l on new base + Arbols).
- Full 9.16 playbook in STAGE-CHECKLISTS.
- TODOS advance + Suggested refresh (next could be relations polish, PDF cross-cut, or a vertical like Mejora full / Auditorías execution).
- MIGRATION-PLAN note.
- This plan (first commit).

**Out (explicit future legs):**
- Full drag-and-drop / editing tree UI (still legacy generators).
- Applying to more than the two current trees.
- Deeper relations polish (if not covered here).
- PDF/Excel reports, questionnaire engine, etc.

## Pattern to Follow
- Same as 9.8 (base helpers) and 9.13 (tree helpers): extract duplication from existing rich modules into reusable base, refactor the consumers.
- Keep exact same output/behavior for the two Arbol views.
- Leverage all prior helpers (9.8, 9.13, 9.15).
- Verification focuses on no regression + cleaner code for trees.

## Data Discovery
- Procesos Arbol (9.11): padre hierarchy, parent name resolution, contenido attachment, custom fetch/build/ShowPage.
- Documentacion Arbol (9.12): grouping by tipo/area, similar custom boilerplate.
- 9.13 already extracted some (initTwig, buildCommonVariables, resolveParentNames, groupItems).
- "Full tree base" means a dedicated base class or comprehensive tree methods so new tree views need even less code (e.g. just declare table + map + overrides for tree-specific).

## Verification Checklist (Non-Interactive + Human Gate)
- php -l on Catalog* + Arbols + index.
- After clean init: no data impact.
- verify-8.6.sh green (tree views still work, no breakage on any module).
- Browser:
  - /admin/procesos/arbol and /admin/documentacion/arbol render exactly as before.
  - Flat lists and other modules untouched.
- Code in Arbols is noticeably smaller / delegates to base.
- All priors (up to 9.15) unaffected.

## Files To Be Created/Modified
**New**:
- reference/stage-9.16-cross-cut-full-tree-base-plan.md (this; first)
- Pages/Catalog/CatalogTree.php (or equivalent full base)

**Modified**:
- Pages/Catalog/CatalogListado.php (if extending helpers there)
- Pages/Procesos/Arbol.php
- Pages/Documentacion/Arbol.php
- scripts/verify-8.6.sh
- .agents/MIGRATION-TODOS.md
- .agents/STAGE-CHECKLISTS.md (full 9.16 section)
- .agents/MIGRATION-PLAN.md

## Detailed Execution Order (Strict — Plan First)
1. Write + commit this plan as absolute first change.
2. Create the full tree base class with extracted logic.
3. Refactor the two Arbols to extend/use it.
4. Extend verify.
5. Update all .agents/ (TODOS first).
6. Full verification (clean room + psql + verify-8.6.sh + php -l + browser trees unchanged + no regression).
7. Logical commits, push -u, open PR.
8. (Post-merge) Human browser sign-off.

## Success Criteria
- Plan first.
- Trees render identically.
- New base provides the "full tree" foundation.
- verify + php -l green.
- Living TODOS advanced.
- Clear notes on what the base now enables.

## Risks & Mitigations
- Over-extraction: keep it practical for the current two use cases + clear extension points.
- Behavior drift: test trees thoroughly in browser before committing changes.

## Related / Handoff
- Delivers the "full tree base" item.
- After: relations polish if needed, PDF cross-cut, or switch to verticals (Mejora full integration looks like a natural rich vertical now that bases are strong).

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**