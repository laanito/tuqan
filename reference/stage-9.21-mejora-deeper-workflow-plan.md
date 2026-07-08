# Stage 9.21 — Mejora deeper workflow (first slice)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.21-mejora-deeper-workflow`
**Driver**: Suggested Next Legs after 9.20 (Formación Cursos). Explicitly "Mejora deeper workflow". The basic CRUD + relations polish for acciones_mejora was done in 9.4/9.17; full workflow (user assignments for detectado/verifica/cerrado/implantacion, auditoria links, state transitions like verify/close) remains deferred. This is a contained first slice adding core workflow fields and relations.

Follows the exact ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size).

## Goals (Aligned with Directives)
1. Deepen Mejora: add support for workflow fields from schema (usuario_detectado, usuario_cerrado, usuario_implantacion, usuario_verifica, fecha_* for workflow, auditoria link).
2. Use Catalog helpers + relations (loadRelated/getRelatedLabel/getRelatedOptions for users and auditorias/programa).
3. Update Listado to fetch/enrich more fields + labels.
4. Update Formulario to handle additional fields in load/getPost/validate/persist (already on base pattern).
5. Enhance templates to display workflow info and selects for users/auditoria.
6. Optional small patch for better demo seeds with user/auditoria links (if needed for clean room).
7. Extend verify + full playbook.
8. Update TODOS, etc.
9. Plan committed first. No breaking changes to existing Mejora flows.

## Scope (Reviewable deeper slice)
**In:**
- Enhance Pages/Mejora/Listado.php and Formulario.php (add fields, relations for usuarios, auditorias).
- Update templates/mejora/listado.twig and formulario.twig (show workflow columns, selects for assignments, labels).
- If data gaps: small patch 0033-mejora-workflow.sql for additional seeds or updates linking to existing usuarios/auditorias.
- Extend scripts/verify-8.6.sh (php-l, specific selects for new fields, row asserts).
- Full 9.21 playbook in STAGE-CHECKLISTS.md.
- TODOS update (advance Mejora item, refresh Suggested).
- This plan (first commit).

**Out (explicit future legs):**
- Full workflow UI (buttons for "Verificar", "Cerrar", state machine, notifications).
- More links (to Aspectos, Indicadores).
- PDF/Excel enhancements, full sub-entities.
- Broader user profile fields.

## Pattern to Follow
- Builds directly on 9.17 relations polish + 9.4 base for Mejora.
- Use getRelated* for users (usuarios table) and auditoria (auditorias or programa_auditoria).
- Keep exact behavior for existing fields/flashes/redirects.
- Leverage all prior cross-cuts.

## Data Discovery
- Schema for acciones_mejora includes: usuario_detectado, fecha_verifica, usuario_verifica, fecha_cierre, usuario_cerrado, usuario_implantacion, auditoria, etc.
- FKs to usuarios (for assignments) and auditorias.
- Existing seeds from 0022 have basic data; may need to enrich with user ids (ensure usuarios exist from early patches).
- Use relations to show names instead of IDs.
- Verify after init that related tables have rows.

## Verification Checklist (Non-Interactive + Human Gate)
- php -l on Mejora/* + templates.
- Clean room: down -v, up, init-db.sh, psql asserts (acciones_mejora with workflow fields populated, joins to usuarios/auditorias).
- verify-8.6.sh green (extended).
- Browser: /admin/mejora list shows new labels (e.g. Detectado by, Cerrada by), form has selects for users/auditoria, save works, no regression on prior Mejora or other modules.
- Flashes, edit/create, cerrado status preserved.

## Risks & Mitigations
- User/auditoria data: use existing from init; defensive labels if null.
- Scope creep: limit to display + basic form support for key workflow fields; no new buttons/transitions yet.
- Table already has columns (from 0022 full create).

## Related / Handoff
- Delivers part of "Mejora deeper workflow".
- Enables future integration legs (with Auditorías 9.19, Aspectos).
- Next could be more Formación (inscripciones), Documentación editor, or Aspectos full matrix.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**