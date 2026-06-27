# Stage 9.11 — Procesos Árbol + contenido basic shell (tree view for hierarchy and details)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.11-procesos-arbol` (fresh from master)
**Driver**: Suggested Next from `.agents/MIGRATION-TODOS.md` after 9.10 (Procesos basic). "Deeper on Procesos (tree/arbol + contenido)" listed first.

Follows the exact process ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size). Builds directly on the 9.10 Procesos shell + 9.8 base helpers.

## Goals (Aligned with Directives)
1. Advance **deeper on Procesos** (legacy 76): deliver first modern Árbol / tree view shell for the hierarchy (using `padre` self-reference) + basic integration with `contenido_procesos` for details/rich data.
2. Re-target (or augment) the existing legacy arbol entry point (`procesos:catalogos:arbol:ver`) to the modern tree view while keeping the flat catalog working.
3. Optional/related patch 0029 (CREATE IF NOT EXISTS for contenido_procesos if needed + sample rows linked to existing procesos seeds).
4. New `Pages/Procesos/Arbol.php` (tree collection + render) + possible template + small enhancements to existing or new details.
5. Templates with stage notes (full drag/drop editing, flujogramas, array editing, legacy generator full replacement, indicators linkage etc. deferred).
6. Extend verify + full "Stage 9.11 Verification Playbook".
7. Update living MIGRATION-TODOS (advance the deeper Procesos item), MIGRATION-PLAN, STAGE-CHECKLISTS.
8. 100% Docker-only, plan-first commit, clean verification, reviewable PR size.

## Scope (Reviewable deeper vertical slice on existing module)
**In:**
- Analysis of legacy arbol (generador_arboles, Procesar_Arbol, HTML/TreeMenu) + contenido_procesos table.
- New/updated patch `0029-procesos-arbol-contenido.sql` (safe CREATE IF NOT EXISTS for contenido_procesos + 2-4 demo rows tied to procesos 1-4 from 0028; data_patches row).
- `Pages/Procesos/Arbol.php` (or equivalent tree view class): fetch procesos with hierarchy info, build simple tree structure, pass to template. Optionally load basic contenido summary per node or on details.
- `templates/procesos/arbol.twig` (hierarchical/indented list or nested ul, links to edit or details, stage alert).
- Possibly light updates to existing Formulario/Listado or a small shared details include for contenido fields (entradas/salidas/proveedor/etc.).
- Routes: keep `/admin/procesos` flat; add `/admin/procesos/arbol`; update legacy arbol path to modern Arbol.
- verify-8.6.sh updates + full 9.11 playbook.
- TODOS advance + Suggested refresh (e.g. full Documentación tree next, or cross-cut tree helpers).
- MIGRATION-PLAN update.
- This plan (first commit).

**Out (explicit future legs):**
- Full interactive tree editing / drag-and-drop / saveNodes (legacy dhtmlgoodies + generador_arboles patterns).
- Complete contenido_procesos editing (arrays, flujograma link, anejos).
- Visual flujogramas.
- Per-process indicadores + objectives.
- "Ficha", "Matriz", revision/approval workflows.
- Deep integration or replacement of legacy Procesar_Arbol / TreeMenu.
- Same tree treatment for Documentación (arbol_documentos.php etc.).
- Cross-cut tree base class extraction (can be alongside or follow-up).

## Pattern to Follow
- Same as 9.5 shell + 9.10: plan first, incremental on existing module.
- Leverage 9.8 helpers for any catalog-like fetches (getDb, loadItem style).
- For tree: custom but minimal — one query or two + PHP build of parent/child map or indented list; no new base yet (note for cross-cut).
- Exact template style + explicit .alert notes on deferred parts.
- Route update style: add modern arbol path, re-point the legacy one (or support both).
- Verification: clean room + psql (contenido rows if patched, procesos, patch 0029) + verify-8.6.sh + php -l + browser (tree view loads, hierarchy visible, contenido snippets, legacy path works, flat list untouched) + no regression.
- Update living TODOS in the PR.

## Data Discovery (from schema + prior patches)
- `procesos`: id, nombre, revision, padre (INTEGER self-ref), codigo, activo (from 0028 + 00-schema).
- `contenido_procesos`: id, proceso (FK), proveedor/entradas/salidas/cliente/propietario/doc_asociada/registros/indicaciones/instalaciones_ambiente (TEXT), indicadores/anejos (INTEGER[]), flujograma/documento (INTEGER), ...
- Legacy menu entry still `procesos:catalogos:arbol:ver` (mapped in 9.10 to flat Listado).
- Old code: Classes/generador_arboles.php, Procesar_Arbol.php, HTML/TreeMenu.php required globally.
- Our current Procesos pages (9.10) are flat catalog only.
- No dedicated seed for contenido_procesos in our 00xx patches yet (full schema has it; we'll make idempotent).
- Demo: link a couple contenido rows to proceso ids 1 ("Proceso de Diseño...") and 3.

## Verification Checklist (Non-Interactive + Human Gate)
- php -l on new/updated Pages/* + index + verify.
- After clean init: procesos + contenido_procesos + 0028/0029 + sample linked rows.
- verify-8.6.sh green (counts, patches, no breakage on 9.10 Procesos flat list or priors).
- Browser (after login):
  - /admin/procesos (flat) still works.
  - Legacy arbol path or /admin/procesos/arbol shows hierarchical view (indent or nesting by padre, names + basic info).
  - Clicking a node or "details" shows relevant contenido_procesos fields (e.g. entradas, salidas, doc_asociada) or a summary.
  - Create/edit via existing flow unaffected; flashes, persistence, navigation ok.
- No PHP errors; priors (incl. 9.10 flat Procesos, Indicadores, bases) untouched.
- Post actions confirm DB + tree structure.

## Files To Be Created/Modified
**New**:
- `reference/stage-9.11-procesos-arbol-plan.md` (this; first)
- `docker/db-init/data-patches/0029-procesos-arbol-contenido.sql` (optional but likely)
- `Pages/Procesos/Arbol.php`
- `templates/procesos/arbol.twig` (and possibly partial for contenido details)

**Modified**:
- `index.php` (add modern arbol route; update legacy mapping)
- Existing Pages/Procesos/* if small shared logic added
- `scripts/verify-8.6.sh`
- `.agents/MIGRATION-TODOS.md` (advance deeper Procesos item)
- `.agents/STAGE-CHECKLISTS.md` (full 9.11 section)
- `.agents/MIGRATION-PLAN.md` (Last Updated)

## Detailed Execution Order (Strict — Plan First)
1. Write + commit this plan as absolute first change.
2. (Discovery) Inspect current code + schema for arbol + contenido.
3. Create patch 0029 (if seeding contenido_procesos).
4. Implement the Arbol page + tree building logic + template.
5. Wire routes (modern + legacy).
6. Small enhancements if needed for details.
7. Extend verify script.
8. Update all .agents/ (TODOS first).
9. Full verification (clean room + psql + verify-8.6.sh + php -l + browser tree + flat flows).
10. Logical commits, push -u, open PR referencing the TODOS deeper item + this plan.
11. (Post-merge) Human browser sign-off; update checkboxes.

## Success Criteria
- Plan first commit.
- After init: contenido_procesos (if patched) + linked rows + tree view renders hierarchy correctly.
- Modern arbol view + legacy path work; flat /admin/procesos unaffected.
- verify + psql + php -l green; no regression on 9.10+.
- Living TODOS shows deeper Procesos item advanced (e.g. basic arbol+contenido done) + Suggested refreshed (Documentación tree, cross-cut tree helpers, other verticals).
- Clear notes on what is still legacy (full editing, flujos, etc.).
- Usable modern entry point for the "Árbol de Procesos" that the menu points to.

## Risks & Mitigations
- Tree building complexity: keep simple (flat fetch + parent map or indent in PHP/Twig); full nested objects or drag later.
- contenido_procesos arrays/FKs: display only for this shell; editing deferred.
- Re-pointing legacy route: keep flat catalog as primary modern list; arbol as the "catalog tree" experience.
- Legacy generator still loaded globally: no removal, just provide parallel modern path.

## Related / Handoff
- Directly delivers the top item in current "Next verticals".
- After this: Suggested can promote Documentación tree, cross-cut for tree helpers (since arbol now surfaces in two modules), Mejora full, Aspectos matrix, or Auditorías execution.
- Sets pattern for the other major tree (Documentación).

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**