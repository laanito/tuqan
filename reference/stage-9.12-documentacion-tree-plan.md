# Stage 9.12 — Documentación tree first slice (modern arbol view shell)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.12-documentacion-tree` (fresh from master)
**Driver**: Suggested Next from `.agents/MIGRATION-TODOS.md` after 9.11 (Procesos deeper). "Documentación tree (high value)" is explicitly called out. Cross-cut tree helpers noted as open.

Follows the exact process ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size). Builds on the 9.5 Documentación shell and the tree pattern from 9.11 Procesos.

## Goals (Aligned with Directives)
1. Advance the **high value** Documentación vertical: first modern tree/arbol view shell (modern landing for the document hierarchy/tree, replacing or augmenting legacy arbol_documentos).
2. Leverage 9.8+ Catalog base helpers + pattern from Procesos Arbol (9.11).
3. No new table patch needed (use existing `documentos` from 0024); possible small data or menu hygiene if surfaced.
4. Pages/Documentacion/Arbol (or Tree) + template with stage notes.
5. Routes: modern `/admin/documentacion/arbol` + map relevant legacy tree/document tree paths.
6. Extend verify + full "Stage 9.12 Verification Playbook".
7. Update living MIGRATION-TODOS (advance Documentación tree item), MIGRATION-PLAN, STAGE-CHECKLISTS.
8. 100% Docker-only, plan-first commit, clean verification, reviewable PR.

## Scope (Reviewable first slice for tree)
**In:**
- Modern tree/arbol view for Documentación (shell that shows documentos in hierarchical/grouped/tree-like structure — e.g. by tipo_documento/area or simple parent simulation if data allows; defer full legacy tree logic).
- `Pages/Documentacion/Arbol.php` (reuses helpers, builds tree data, renders dedicated template).
- `templates/documentacion/arbol.twig` (tree UI with links to edit/list, stage notes on deferred editor, perfiles, workflows, PDF).
- Routes in index.php for the tree view + legacy mappings for document tree entry points.
- Extend scripts/verify-8.6.sh (php -l, specific asserts, table counts).
- Full 9.12 playbook in STAGE-CHECKLISTS.
- Update living docs.
- This plan (first commit).

**Out (explicit future legs):**
- Full editor modernization (FCK/ modern rich text).
- Perfil permission arrays editing (the BOOLEAN[] columns).
- Approval workflow (revisado/aprobado states, dates).
- Sub-accions (manual, politica, pg, pe, frl, registros, formatos, etc.).
- PDF/GenPDF ficha generation.
- True hierarchical structure if legacy uses folders/categories (vs flat + tree renderer).
- Cross-cut tree base extraction (can be done alongside or next).

## Pattern to Follow
- Same as 9.5 shell + 9.11 Procesos Arbol: plan first, focused shell that still delegates heavy legacy parts.
- Use Catalog helpers for data fetching where possible.
- Template style with explicit .alert notes.
- Modern + legacy route mapping.
- Verification: clean room + psql (documentos table + rows) + verify-8.6.sh + php -l + browser flow for tree view + no regression on flat Documentación list or other modules.
- Update living TODOS in the PR.

## Data Discovery
- `documentos` table (from 0024 + 00-schema): id, nombre, codigo, estado, revisado_por, aprobado_por, revision, activo, tipo_documento, area, calidad, medioambiente, perfil_* BOOLEAN[], fecha_* .
- No obvious `padre` self-ref (unlike procesos); tree in legacy (arbol_documentos.php) likely uses tipo_documento, area, or custom logic + iframes in Procesar_Arbol.
- Current modern: /admin/documentacion (flat list from 9.5), some legacy listados mapped.
- Legacy heavy parts: arbol_documentos.php, editor.php, permisos_documentos, GenPDF, etc. (deferred).
- Menu accions: many documentacion:xxx:listado:ver (already some mapped); tree likely accessed via top Documentación or specific arbol entry.

## Verification Checklist (Non-Interactive + Human Gate)
- php -l on new Pages + index + verify.
- After clean init: documentos table + rows present.
- verify-8.6.sh green (new tree view, no breakage on 9.5 Documentación or priors).
- Browser (after login):
  - /admin/documentacion (flat) still works.
  - New /admin/documentacion/arbol or legacy tree path shows modern tree/grouped view.
  - Links to edit/form work; content looks reasonable.
  - No regression on other modules.
- Templates have clear deferred notes.

## Files To Be Created/Modified
**New**:
- reference/stage-9.12-documentacion-tree-plan.md (this; first)
- Pages/Documentacion/Arbol.php
- templates/documentacion/arbol.twig

**Modified**:
- index.php (routes)
- scripts/verify-8.6.sh
- .agents/MIGRATION-TODOS.md (advance item, refresh Suggested)
- .agents/STAGE-CHECKLISTS.md (full 9.12 section)
- .agents/MIGRATION-PLAN.md (Last Updated)

## Detailed Execution Order (Strict — Plan First)
1. Write + commit this plan as absolute first change.
2. Research current legacy arbol_documentos + how tree is built (if needed for scope).
3. Implement Arbol page + tree data logic + template.
4. Wire routes.
5. Extend verify script.
6. Update all .agents/ (TODOS first).
7. Full verification (clean room + psql + verify-8.6.sh + php -l + browser).
8. Logical commits, push -u, open PR referencing TODOS + plan.
9. (Post-merge) Human browser sign-off.

## Success Criteria
- Plan first commit.
- Modern tree/arbol view for Documentación works (grouped/tree-like display of documentos).
- Legacy tree paths resolve to modern where mapped.
- Flat list + other modules unaffected.
- verify + psql + php -l green.
- Living TODOS updated, Documentación tree item advanced.
- Clear notes on what remains legacy (editor, perfiles, workflows, PDF).

## Risks & Mitigations
- No simple padre in documentos: implement grouped tree (by tipo/area) or flat with tree UI affordances for first slice. Full structure can evolve.
- Complex perfil arrays: display only or note for later form work.
- Legacy tree renderer heavy: shell that provides usable modern view; delegate remaining to legacy for now.

## Related / Handoff
- Delivers the "Documentación tree (high value)" item.
- After: cross-cut tree helpers (now that both Procesos and Documentación have tree views), or next vertical (Mejora full, etc.).
- Continues the pattern of incremental shells for complex modules.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**