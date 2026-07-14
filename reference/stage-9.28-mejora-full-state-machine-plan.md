# Stage 9.28 — Mejora: full state machine polish (auto transitions + quick actions)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.28-mejora-full-state-machine`
**Driver**: Suggested Next after 9.27. "More Mejora (full state machine/links)". Previous 9.27 delivered basic checkboxes + computed states. Now polish to fuller transitions: auto-apply user/date on actions, direct quick-action routes/buttons (mimicking legacy Verificar/Cerrar Accion), visible current estado in form, better UX for workflow.

Follows exact ritual.

## Goals
1. Add auto-transition logic: when accion_verificar / accion_cerrar (or new quick actions), default usuario_* to current logged-in user and fecha_* to today if not explicitly provided.
2. Implement dedicated quick transition methods + routes (POST /admin/mejora/verificar/{id}, /admin/mejora/cerrar/{id}) so list can offer one-click state changes.
3. Display prominent current "Estado" in the edit form (computed or from data).
4. Enhance list template with conditional quick action buttons (Verificar for Pendiente, Cerrar for Verificada).
5. Small supporting patch 0038 (better demo states, perhaps a couple more rows with mixed states).
6. Store id_usuario in session during login (enables real current user for auto-assign) + expose getCurrentUserId() helper in Catalog base.
7. Extend verify + full 9.28 playbook section.
8. Update living docs (TODOS flip items, snapshot, suggested next).
9. Plan first.

## Scope
**In:**
- Minor hygiene: LoginUsuario sets $_SESSION['id_usuario']; Catalog* getCurrentUserId() (defaults to 1 for safety).
- Mejora/Formulario.php: enhance getPostData/persist/validate for auto-dates/users; add public Verificar($id), Cerrar($id) methods.
- index.php: add the two new POST routes for quick actions (modern only for now).
- templates/mejora/{listado,formulario}.twig: estado badge in form; conditional quick buttons in list (POST forms or links that post).
- docker/db-init/data-patches/0038-mejora-state-transitions.sql (idempotent UPDATEs/INSERTs for demo variety).
- scripts/verify-8.6.sh: add 9.28 counts/asserts.
- .agents/STAGE-CHECKLISTS.md: new section with todo items + exact commands + evidence template.
- .agents/MIGRATION-TODOS.md: mark progress, update suggested.
- This plan.md committed first.
- Full clean-room run + php -l + browser flows (create + transition via checkbox + via quick button).

**Out:**
- Legacy colon action remapping (mejora:accmejora:... still fall to LegacyAction; can map later).
- Full audit trail / history table for changes.
- Re-open / other transitions.
- Cross-module auto creation (e.g. from Auditorias findings).
- Broader links (Aspectos/Indicadores) — note as follow-up.
- UI for implantacion as separate explicit step (if needed).

## Pattern
- Builds directly on 9.21/9.25/9.27 (fields + basic machine).
- Use Catalog helpers + add minimal getCurrentUserId for auto.
- Quick actions are simple POST that mutate specific fields then redirect (flash "Acción verificada", etc.).
- State remains derived: Cerrada > Verificada > Pendiente (from fields).
- Validation remains (cannot close without verifica).

## Data
- 0038 patch for richer state examples in clean init (ensure 1+ Pendiente, Verificada, Cerrada after init).
- ON CONFLICT safe.

## Verification
- Clean: docker compose down -v ; up ; ./docker/db-init/init-db.sh
- php -l Pages/Mejora/* Pages/Catalog/* Pages/LoginUsuario.php index.php
- psql asserts for patch + state counts.
- scripts/verify-8.6.sh (extended)
- Browser: login, /admin/mejora list (see estados + quick btns), edit one, use checkboxes (auto fill), submit; also use quick Verificar/Cerrar from list; check flashes, DB values, computed labels/estado; no regression on other modules.
- Post-action: SELECTs show correct usuario_*/fecha_* + cerrada.

## Risks / Notes
- User id: now properly captured on login (small cross-cut hygiene).
- Quick actions keep simple (no full form re-render).
- Dates: server-side today (Y-m-d) for consistency.
- If no logged id, graceful default to 1 (matches all prior seeds/patches).

## Handoff
- Advances Mejora to fuller state machine + direct actions.
- Next suggested: remaining Mejora links/integration, Formación remaining subs, Documentación rich editor, or Aspectos full matrix.
- All prior Mejora stages (9.4/9.17/9.21/9.25/9.27) now have stronger workflow support.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**