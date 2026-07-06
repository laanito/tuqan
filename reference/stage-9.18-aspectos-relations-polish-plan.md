# Stage 9.18 — Cross-cut: relations polish + Aspectos base adoption

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.18-aspectos-relations-polish`
**Driver**: Suggested Next Legs in `.agents/MIGRATION-TODOS.md` after 9.17: "Apply relations polish to other early modules (Aspectos etc.)". Aspectos (9.7 basic + 9.14 matrix) still carries the pre-9.17 full-dupe ShowPage/Procesar pattern in Formulario (like Mejora pre-refactor) and shows raw IDs for tipo_aspecto / area in lists. This leg applies the now-mature 9.17 helpers (getRelatedOptions, promoted load/getRelatedLabel) + base override pattern to Aspectos.

Follows the exact process ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size).

## Goals (Aligned with Directives)
1. Refactor `Pages/Aspectos/Formulario.php` to the modern helper-override pattern (getSelectForForm, loadItem, getPostData, validate, persist, buildFormVariables) so it uses base ShowPage/Procesar and drops duplication.
2. Enhance Aspectos Listado to enrich items with `tipo_aspecto_label` and `area_label` (using the promoted relations helpers).
3. Update Aspectos templates (listado.twig + formulario.twig) to display resolved human labels and use `<select>` dropdowns for tipo_aspecto / area where it makes sense.
4. Discover correct related table names at implementation time (legacy shows `tipo_aspectos` for tipo_aspecto; `areas` likely for area).
5. No new data patches or routes needed.
6. Extend verify + full "Stage 9.18 Verification Playbook".
7. Update living MIGRATION-TODOS (advance Aspectos polish item, refresh suggested), STAGE-CHECKLISTS, possibly MIGRATION-PLAN note.
8. 100% Docker-only, plan-first commit, clean verification, reviewable PR.

## Scope (Reviewable cross-cut + module polish slice)
**In:**
- Refactor Aspectos/Formulario to override the protected helpers (parallel to 9.17 Mejora).
- Override fetchItems (or post-process) in Listado to attach labels via getRelatedLabel (Listado already uses fetchFilteredItems).
- Add `getRelatedOptions` usage in buildFormVariables for selects.
- Update `templates/aspectos/listado.twig` and `formulario.twig`.
- Extend `scripts/verify-8.6.sh` (php -l).
- Full 9.18 playbook in `.agents/STAGE-CHECKLISTS.md`.
- TODOS update + Suggested refresh (next could be more polish on Formacion/Documentacion or start Auditorías execution / Mejora deeper).
- This plan (first commit).

**Out (explicit future legs):**
- Full editable matrix / revisiones / cuestionario integration for Aspectos (still deferred).
- Applying polish to every remaining early module in one go (do in follow-ups: Documentacion, Equipos, etc.).
- Deeper Aspectos FKs (e.g. to Criterios) or calculations.
- Changes to the Matriz view.

## Pattern to Follow
- Exact mirror of Stage 9.17 for Mejora: promote/adopt the relations cross-cut, refactor early rich module to smaller overrides.
- Preserve behavior for existing Aspectos flows (list, create/edit, matrix, flashes, filters).
- Leverage all prior cross-cuts (9.8 base, 9.15 filters/relations, 9.17 polish).
- Verification: no regression on Aspectos or other modules; labels and selects work where added.

## Data Discovery (to be confirmed in leg)
- `aspectos.tipo_aspecto` → likely `tipo_aspectos(id, nombre, ...)` (seen in legacy queries).
- `aspectos.area` → `areas(id, nombre)` or similar (area column is varchar in some contexts, but FK label resolution expected).
- Table may have `activo` or other filters; use simple options load like 9.17.
- Use clean-room init + psql to confirm + test labels post-changes.

## Verification Checklist (Non-Interactive + Human Gate)
- `php -l` on Catalog* (no change), Pages/Aspectos/*, templates/aspectos/*, verify script.
- After clean-room init: no data impact.
- verify-8.6.sh green.
- psql: sample aspectos rows + confirm related tables have matching data.
- Browser (human):
  - /admin/aspectos list: now shows resolved Tipo and Área labels instead of raw IDs (in addition to existing matrix).
  - Create/edit Aspecto: selects for tipo_aspecto and area (pre-selected on edit).
  - Quick regression check on /admin/aspectos/matriz and other modules.
  - Filters (?activo=) still work.
- No breakage to prior 9.17 Mejora improvements or trees.

## Risks & Mitigations
- Table name mismatches: treat as data discovery (like tipo table in 9.17); fix during impl + note in evidence.
- Matrix view: do not touch in this leg (keep scope tight).
- Area may be free-text in some legacy paths: still provide select + allow the existing varchar behavior if needed.

## Related / Handoff
- Directly delivers the top item in Suggested Next after 9.17.
- Continues pattern of cleaning up early modules with the maturing Catalog base + relations helpers.
- After: more polish (Documentación, etc.), or a new vertical slice (Auditorías execution first slice, Formación subs, Mejora workflow).

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**
