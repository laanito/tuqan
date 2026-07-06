# Stage 9.17 — Cross-cut: relations polish + Mejora base adoption

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.17-cross-cut-relations-polish-mejora`
**Driver**: Suggested Next Legs in `.agents/MIGRATION-TODOS.md` after 9.16 (full tree base): "More cross-cuts (relations polish, etc.)" or verticals like "Mejora full integration". The relations helpers from 9.15 (loadRelated/getRelatedLabel) saw only light demo usage; early rich modules (Mejora 9.4, Aspectos 9.7) still carry pre-9.8 duplicate ShowPage/Procesar boilerplate instead of using the helper override pattern established in Indicadores/Procesos. This leg polishes the relations cross-cut (promote helpers, add options support) and adopts it + base pattern in Mejora.

Follows the exact process ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size). Continues incremental base + cross-cut extraction.

## Goals (Aligned with Directives)
1. Polish the form/list relations cross-cut: promote `loadRelated`/`getRelatedLabel` to `CatalogListado` too; introduce `getRelatedOptions()` helper in both bases to support `<select>` dropdowns for FKs.
2. Refactor `Pages/Mejora/Formulario.php` to the modern pattern: override `getSelectForForm`/`loadItem`/`getPostData`/`validate`/`persist` (and optionally `buildFormVariables`) so it uses base `ShowPage`/`Procesar` and eliminates ~80 lines of duplicated DB/Twig/user-context boilerplate.
3. Enhance Mejora Listado + Formulario to actually use the relations helpers: resolve `tipo_label` / `cliente_label` (FKs to `tipo_acciones` and `clientes`), populate select options in form.
4. Update Mejora templates to display resolved human labels (instead of/raw IDs) and render proper `<select>` controls for tipo/cliente.
5. Update list SQL + mapRow to include `cliente`; enrich items post-fetch.
6. No new data patches, no route changes (already wired in 9.4).
7. Extend verify (php -l) + full "Stage 9.17 Verification Playbook" in STAGE-CHECKLISTS.
8. Update living MIGRATION-TODOS (advance Mejora notes + cross-cuts, refresh Suggested Next), MIGRATION-PLAN timeline if milestone.
9. 100% Docker-only (`docker compose exec app ...`), plan-first commit, clean verification, reviewable PR size.

## Scope (Reviewable cross-cut + vertical polish slice)
**In:**
- Add/promote relation helpers + new `getRelatedOptions()` to `Pages/Catalog/CatalogListado.php` and `CatalogFormulario.php`.
- Refactor `Pages/Mejora/Formulario.php` (use helper overrides; add build override for options/labels).
- Enhance `Pages/Mejora/Listado.php` (select cliente, map update, fetchItems override to enrich labels via helpers).
- Update `templates/mejora/listado.twig` (add Tipo/Cliente columns using *_label, update info note).
- Update `templates/mejora/formulario.twig` (replace number inputs for tipo/cliente with selects using options; use labels if desired; update comments).
- Extend `scripts/verify-8.6.sh` header/php -l for touched Catalog + Mejora files.
- Full 9.17 playbook section in `.agents/STAGE-CHECKLISTS.md`.
- TODOS refresh + Suggested next update (e.g. "apply similar polish to Aspectos/others", "Auditorías execution start", "Formación subs", "Documentación editor").
- MIGRATION-PLAN note (optional).
- This plan file (first commit on branch).

**Out (explicit future legs):**
- Full adoption of relations + selects across all remaining rich modules (Aspectos, Documentacion, Equipos, etc. can follow pattern now).
- Advanced relations (eager joins in SQL, multi-FK, editing related in place).
- Deeper Mejora workflow (users for detectado/verifica/cerrado/implantacion, links to auditoria, full state machine).
- Changing other forms to selects (padre in Procesos etc.).
- New UI filter components or full matrix/execution features.
- Twig or PDF cross-cuts.

## Pattern to Follow
- Same as 9.8/9.13/9.15/9.16: extract, promote helpers, refactor consumers to be smaller and consistent.
- Preserve exact behavior for existing Mejora flows (create/edit, flashes, redirects, validation, persisted data).
- Use `getDb()`, `getRelated*` etc. from bases.
- For selects: load options once per form render; allow NULL/empty for optional FKs (cliente can be null per seed).
- Verification focuses on: no regression on Mejora (and other modules), labels appear correctly after init, selects work for create/edit, DB values unchanged.
- Update living TODOS in the PR.

## Data Discovery
- `acciones_mejora.tipo` → FK to `tipo_acciones(id)` (nombre, activo, ...); values like 1=Auditorias, 3=Reclamación, 4=Preventiva/Correctiva (from schema + seeds).
- `acciones_mejora.cliente` → FK to `clientes(id)` (nombre, ...); can be NULL.
- Current Mejora Listado selects limited fields (no cliente); Form selects many but displays raw in UI.
- List template explicitly notes "Tipo y Cliente se muestran como IDs por ahora."
- Form template uses raw `<input type="number">` for them.
- Mejora Form is one of the last early modules (9.4) still on full-dupe ShowPage/Procesar (unlike 9.9+ modules).
- No change to underlying table or seeds (0022 + core schema already provide the rows).

## Verification Checklist (Non-Interactive + Human Gate)
- `php -l` on CatalogListado.php, CatalogFormulario.php, Pages/Mejora/*, templates touched, index.php (no change), verify script.
- After clean-room `docker compose down -v; up; ./scripts/init-db.sh`: data patches applied, counts >0 for acciones_mejora + related.
- `docker compose exec app ./scripts/verify-8.6.sh` fully green (existing + new -l).
- psql asserts: select sample acciones_mejora rows + confirm FK values.
- Browser flows (human):
  - Login → Aplicacion → Mejora (list shows resolved Tipo labels + Cliente names or '-'; no breakage on prior modules).
  - Create new acción: tipo and cliente are nice dropdowns (not raw id boxes); required fields still enforced; success flash + redirect.
  - Edit existing: selects pre-selected correctly, labels visible if added, save works, data matches.
- No behavior change to any other modern list/form.
- Flash/error paths, empty states still work.

## Risks & Mitigations
- FK table names: confirmed `tipo_acciones` and `clientes` via schema + 0022 + legacy data.
- Optional cliente (NULLs): handle in label (return null → '-') and in select (include empty option).
- Base build*Variables: call parent then merge/enrich; keep the singular key ('mejora') contract.
- Template var names: keep 'mejora' and 'tipo_options'/'cliente_options' (or consistent naming).
- If other modules break (unlikely): the changes are additive + Mejora-specific overrides.

## Related / Handoff
- Directly addresses "relations polish" + makes Mejora more "full" using cross-cuts.
- Unlocks easy follow-up polish legs for other early modules (Aspectos, Documentacion/Formacion, Equipos...).
- After: next verticals (Auditorías execution first slice, Formación cursos, deeper Documentación, or more cross-cuts like PDF).
- Enables future nicer UX without per-module query boilerplate.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**
