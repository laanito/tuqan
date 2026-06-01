# Stage 8.5 Plan — Profiles, Empresas, Personalización + Permission Assignment (Menu-Driven)

**Status**: Planning complete → Execution in progress  
**Branch target**: `feat/stage-8.5-profiles-empresas-personalizacion` (fresh from origin/master)  
**Driver**: Menu as source of truth + "Profiles before POST on Users" + explicit restructuring of Aplicacion / Administracion branches.

## Goals (User Explicit)
1. Implement **listing + forms (GET only)** for **Perfiles** (Profiles) first — prerequisite because Users reference `perfil` FK.
2. In "Aplicacion":
   - Rename "Hospitales" → **Empresas** and build the Empresas module (listing + form) to "define Empresas".
   - Move the "Permisos" (id 107, currently direct under Administracion) into Aplicacion and use it for **assigning menu permissions to profiles**.
   - Remove "Mensajes" and "Tareas" from inside Aplicacion (they stay in the system but not under that submenu).
   - Keep "Menus" (for future translations + ordering UI) and "Idiomas" (limited to available languages selection + default language).
3. Under **Administracion** create a new submenu **"Personalizacion"** containing exactly:
   - Clientes
   - Criterios
   - Tipos Acc. Mejora
   - Tipos area
   - T. Amb. Aplicable
   - Tipos Imp. Amb.
   - Tipo documento
   (These are currently scattered direct children of Administracion.)

Continue the established pattern from Stage 8.4 (Usuarios):
- Modern Phroute routes (`/admin/...` + legacy colon paths)
- `Pages/<Module>/{Listado,Formulario}.php` (or equivalent)
- Templates extending `layouts/app.twig`
- Full sidebar + red cabecera on every page (no regressions)
- GET listings + forms only — POST/validation deferred
- Idempotent data patches (0010+) for all menu restructuring + any new reference tables
- All work inside Docker, menu-driven

## Current Menu Reality (Post 0004–0009, verified live in dev DB)
**Aplicacion (id=82, padre=74 "Administración") children (padre=82):**
- 32 Usuarios (already modernized)
- 33 Perfiles (menu skeleton + children 320/321/322 exist — currently hits Placeholder)
- 35 Mensajes (messages:get) — **remove from Aplicacion**
- 36 Tareas (administracion:tareas:...) — **remove from Aplicacion**
- 93 Menus (administracion:menus:...)
- 94 Idiomas (administracion:idiomas:...)
- 108 Hospitales (administracion:hospitales:listado:nuevo) — **rename label + update for Empresas**

**Direct under Administracion (74) — relevant items:**
- 86 Clientes
- 84 Criterios
- 85 Tipos Acc. Mejora
- 87 Tipos area
- 88 T. Amb. Aplicacion (T. Amb. Aplicable)
- 90 Tipos Imp. Amb.
- 92 Tipo documento
- 107 Permisos (administracion:modulos:listado:nuevo, label "Permisos") — **move into Aplicacion**
- Others (Documentos, Formacion, Aspectos Ambientales, Proveedores, Equipos, Auditorias, Indicadores, Ayuda, Log. Aplicable...) stay as direct children for now.

Perfiles table already has 2 rows (0=Administrador, 1=Usuario).  
usuarios.perfil FK points to it.  
idiomas has 1 row (castellano id=1).  
No `empresas` table in minimal schema (legacy has very small `hospitales` table: id, nombre, activo, "password").

## Detailed Menu Restructuring (Data Patch 0010)
New patch `docker/db-init/data-patches/0010-menu-restructure-aplicacion-personalizacion.sql`

1. **Rename + re-accion Hospitales → Empresas**
   - UPDATE menu_nuevo SET ... label via menu_idiomas_nuevo (idioma 1 + others if present), accion to `administracion:empresas:listado:ver` (or modern equivalent).
   - Add corresponding child actions (nuevo, editar) if missing, modeled after perfiles/usuarios.

2. **Create "Personalizacion" parent under Administracion (74)**
   - INSERT INTO menu_nuevo (padre=74, orden=XX, accion='', permisos=..., activo=true)
   - INSERT labels into menu_idiomas_nuevo for id=1 (castellano) and any other idiomas present.
   - Capture the new id (use RETURNING or separate query in patch safety).

3. **Reparent the 7 items** (UPDATE padre = new Personalizacion id, adjust orden sequentially under it).
   - Clientes (86), Criterios(84), Tipos Acc. Mejora(85), Tipos area(87), T. Amb. Aplicable(88), Tipos Imp. Amb.(90), Tipo documento(92).

4. **Move Permisos (107)** into Aplicacion (padre = 82), adjust orden so it appears logically (e.g. after Idiomas or grouped with Menus/Idiomas).

5. **Remove Mensajes (35) and Tareas (36) from Aplicacion**
   - Option A (preferred for visibility): UPDATE padre = 74 (move up to direct under Administracion, outside Aplicacion).
   - Option B: Set activo=false on the parent entries if they are not wanted in primary nav yet.
   - Keep their sub-actions (nuevo, ver) for legacy compatibility.

6. **Add any missing child actions** under new/renamed entries (nuevo, editar, borrar) following the 320/321 pattern so future forms have menu targets.

All statements use `ON CONFLICT DO NOTHING` or conditional guards + tracked via data_patches table (init-db.sh already handles this).

## Scope per Module (MVP, GET-first)
### 1. Perfiles (Highest priority — do first)
- Table exists + data.
- Routes: `/admin/perfiles`, `/admin/perfiles/nuevo`, `/admin/perfiles/editar/{id}` + legacy `administracion/perfiles/*` mappings.
- Listado: id, nombre, activo. "Nuevo Perfil" button. Edit links. (Perfiles used by Usuarios — show usage count if easy.)
- Formulario (nuevo + edit share): nombre, activo toggle.
- Replace the existing Placeholder route.
- Full modern page with sidebar (copy exact Usuarios pattern + variables).
- After this, Usuarios forms can reference real Perfil selector (future POST leg).

### 2. Empresas (ex-Hospitales)
- Add minimal table via patch (or reuse/extend `hospitales` — decision: introduce `empresas` table with same columns + migration note for legacy; or point modern code at `hospitales` and document rename later. Lean toward adding `empresas` table + seed 1-2 demo rows for visibility).
- Modern listing + form at `/admin/empresas*` + updated legacy paths.
- Fields: nombre, activo (password column in legacy is suspicious — ignore or treat as legacy-only for now).
- Update menu label + accion for id 108.

### 3. Permisos / Permission Assignment (under Aplicacion after move)
- Legacy uses a 22-element boolean array `menu_nuevo.permisos[perfil_index]`.
- New page: simple matrix or list of profiles → clickable "Edit permissions" → table of top-level + key menus with checkboxes (read-only for this leg, or POST-deferred form that shows current state).
- Goal: make the "Permisos" menu item useful instead of placeholder. Start with read view of per-perfil menu visibility using existing data.
- Route under the moved Permisos entry.

### 4. Idiomas (limited scope)
- Current: only 1 row.
- Page shows list of available languages (from `idiomas` table).
- "Default language" indicator (tied to APP_LANG_ID / APP_LANG_INITIAL env or a new simple setting mechanism).
- No full translation management yet (Menus will own label editing later).
- Modern route + update existing menu accion if needed.

### 5. Personalizacion sub-items (7)
- Many are simple "tipos_*" master data tables in legacy (tipomejora, tiposareas, tiposamb, etc.).
- Approach: 
  - Add lightweight CREATE TABLE IF NOT EXISTS for the core ones in the restructure patch (or a companion 0011-types-tables.sql) with id + nombre + activo.
  - Seed 3-5 demo rows each.
  - Implement 1-2 fully (e.g. Clientes + Tipos Acc. Mejora) with list + form following Perfiles pattern.
  - Others get modern routes pointing to a smart Placeholder or a reusable generic master-data list (to avoid 7 near-identical modules in one PR).
- All 7 appear correctly under the new Personalizacion parent in sidebar.

### 6. Menus (under Aplicacion)
- Leave as-is for this leg (points to Placeholder or simple listing of menu_nuevo + labels).
- Future: dedicated UI for orden editing + label translation (menu_idiomas) — explicitly called out as the home for "menu translations and ordering".

### 7. Mensajes + Tareas
- Moved out of Aplicacion (see patch).
- No new modern implementation in this PR (keep hitting legacy/Placeholder as before).

## New/Changed Files (Expected)
- `docker/db-init/data-patches/0010-menu-restructure-....sql` (and 0011 if types tables needed)
- `Pages/Perfiles/{Listado.php, Formulario.php}`
- `Pages/Empresas/{Listado.php, Formulario.php}` (or shared master data)
- `templates/perfiles/`, `templates/empresas/`
- Possibly reusable `templates/admin/master-listado.twig` etc. (keep simple — duplicate a bit if it avoids over-abstraction in first pass)
- `index.php`: add 10-15 new route lines (modern + legacy fallbacks) + update any exclusion lists
- `reference/stage-8.5-....-plan.md` (this file)
- Updates to `.agents/STAGE-CHECKLISTS.md` (new Stage 8.5 section + evidence)
- Updates to `.agents/MIGRATION-PLAN.md` (timeline + status)
- CSS tweaks only if new UI patterns required (prefer existing)

## Risks & Mitigations
- **Table proliferation**: Many "tipos" tables. Mitigation: minimal schema additions only for what we actually render; use generic components where possible; document in patch comments.
- **Legacy `hospitales` references everywhere**: Do not DROP the old table. Add `empresas` or keep dual access. Update only menu + modern code.
- **Permisos array is tech debt**: Do not redesign RBAC now. Read-only or simple assignment form on top of existing column.
- **Menu orden / labels after restructure**: Patch must be careful with orden values so nothing jumps unexpectedly. Test sidebar after patch.
- **No POST yet**: Forms will be display + "submit would go here" notes if needed. Consistent with Usuarios decision.
- **Context size**: Keep PR focused — deliver working navigation + 2-3 fully functional modules (Perfiles + Empresas + 1-2 Personalizacion) + good scaffolding for the rest.

## Execution Order (Strict)
1. Write this plan + any supporting reference.
2. Create fresh branch from origin/master.
3. Implement + apply data patch 0010 (menu changes). Verify via `docker compose exec app ./scripts/init-db.sh` + browser sidebar inspection.
4. Perfiles module (highest value, unblocks Users).
5. Empresas (rename + basic table + pages).
6. Permisos assignment page.
7. Idiomas limited + Personalizacion parent + at least 2 sub-modules fully + rest routed cleanly.
8. Route updates + global verification (all Aplicacion / new Personalizacion items clickable and render with full chrome).
9. Docs updates (this plan → evidence in STAGE-CHECKLISTS, MIGRATION-PLAN).
10. Full Docker clean test (down -v, up, init-db, login, walk the exact new menus).
11. Commit, push, open PR. (User will be notified only on blockers during execution.)

## Success Criteria
- After patch, sidebar exactly matches the requested structure (Personalizacion under Administracion with the 7 items; Aplicacion has Empresas not Hospitales, has Permisos inside it, no Mensajes/Tareas inside it).
- Clicking Perfiles, Empresas, Permisos, Idiomas, and the Personalizacion children lands on modern pages (or clear "en desarrollo" with real sidebar).
- Perfiles list shows the 2 existing roles; form allows create/edit (GET).
- No regression on existing Usuarios or main navigation.
- All changes in one clean PR with proper evidence appended to agents docs.

## Related
- Complements PR #60 (Stage 8.4 menu + Usuarios).
- Prepares the ground for the deferred "POST for users" work.
- Menu remains the single source of truth for what to build next.

---
*Part of the ongoing menu-driven modernization (Stage 8.x). June 2026.*

**Execution started on branch `feat/stage-8.5-profiles-empresas-personalizacion` (2026-06).**

**Completed so far (no user input needed):**
- Live DB exploration + exact menu ID mapping.
- Detailed plan document.
- Fresh branch + corrected robust data patch `0010-menu-restructure-aplicacion-personalizacion.sql` (verified live after one self-repaired ID collision during development).
- Full Perfiles module (Listado + Formulario GET, matching Usuarios pattern exactly, with sidebar + cabecera).
- Route wiring in index.php (modern /admin/perfiles* + legacy accion paths + scaffolding for Empresas/Permisos/Personalizacion children).
- All PHP syntax clean.
- Live menu state now exactly matches the requested structure (Personalizacion under Administracion with the 7 items; Empresas + Permisos inside Aplicacion; Mensajes/Tareas moved out).

Next immediate steps in this leg (autonomous): Empresas basic table + pages, Permisos read matrix, limited Idiomas, docs updates, full Docker verification, PR.

**No blockers encountered after the self-contained patch repair. Proceeding.**