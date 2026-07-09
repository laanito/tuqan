# Stage 9.23 — Documentación editor/perfiles first slice

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.23-documentacion-editor-perfiles`
**Driver**: Suggested Next after 9.22. "Documentación editor/perfiles". Current Documentacion has basic shell (9.5) + tree (9.12); Formulario still uses old dupe ShowPage/Procesar (pre-base refactor). Table has rich fields including perfiles arrays (ver, nueva, etc.), content. First slice: refactor Form to modern Catalog helpers + add perfiles support.

Follows exact ritual.

## Goals
1. Refactor Pages/Documentacion/Formulario.php to use base helpers (getSelectForForm, loadItem, getPostData, validate, persist, buildFormVariables) — eliminate dupe.
2. Add perfiles support (perfil_ver, perfil_nueva, etc. as checkboxes or multi in form).
3. Update Listado if needed for perfiles display.
4. Update templates/documentacion/formulario.twig for perfiles.
5. Use relations if applicable (e.g. for tipo or area).
6. Extend verify + full playbook.
7. Update TODOS.
8. Plan first.

## Scope
**In:**
- Refactor Documentacion/Formulario to base pattern (like 9.17/9.18/9.21).
- Add perfiles fields to selects, map, post, persist.
- Templates updates for perfiles UI (checkboxes for each perfil_*).
- Possibly enhance Listado select for one perfil.
- Verify extension (php-l, DB for perfiles).
- 9.23 playbook.
- TODOS update.
- This plan (first).

**Out:**
- Full editor (rich content editing, workflows).
- Perfiles full (in tree, approval).
- PDF, other subs (docvigor etc.).
- Broader Documentacion.

## Pattern
- Mirror refactor of early modules (Mejora, Aspectos, etc.).
- Leverage Catalog + any relations.
- Perfiles as boolean arrays in Postgres; handle in PHP as arrays or separate checkboxes.

## Data Discovery
- documentos table has: ... perfil_ver BOOLEAN[], perfil_nueva[], etc.
- Current modern selects basic fields; need to extend.
- Seeds exist; perfiles may be null or set in data.

## Verification
- php-l on Documentacion/* + templates.
- Clean init, verify-8.6.sh, psql for perfiles.
- Browser: /admin/documentacion form has perfiles checkboxes, save works, list shows if added.
- No reg on Arbol or other.

## Risks
- Perfiles arrays: use text[] or handle as [] in queries.
- Scope: only basic perfiles + refactor; no full editor UI yet.

## Handoff
- Starts Documentación editor/perfiles.
- Next: more Documentacion, Mejora full, etc.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**