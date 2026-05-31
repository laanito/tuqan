# First Vertical Slice: Administración → Usuarios (User Management)

**Status**: Planning phase (Stage 8.4)  
**Driver**: Real menu data + "menu as the source of truth for what to build next"

## Why This Branch First?

- It is the most self-contained administrative function.
- It touches core concepts we already partially modernized (login, perfiles, session).
- It gives immediate value: real "Nuevo Usuario", "Editar", "Borrar", export, etc. instead of 404 placeholders.
- The legacy actions under it are well understood (`administracion:usuarios:*`).

## Current Menu State for This Branch (in dev DB after patches 0001 + 0002)

From `data-patches/`:

- 30  Administración (top)
  - 300 Usuarios (listado)
    - 310 Nuevo Usuario
    - 311 Editar Usuario
    - 312 Borrar Usuario
    - 313 Exportar Usuarios (added in 0002)
    - 314 Dar de Baja (added in 0002)
  - 301 Perfiles (listado)
    - 320 Nuevo Perfil
    - 321 Editar Perfil
    - 322 Borrar Perfil
  - 302 Mensajes (listado)
    - 330 Nuevo Mensaje
    - 331 Ver Mensaje

All point to legacy-style colon actions that our router + `LegacyAction` will catch and show a friendly "not modernized yet" page with the real menu still present.

## Target Modernized Pages (MVP for this slice)

1. **Listado de Usuarios** (`/administracion/usuarios/listado`)
   - Table of users (id, login, nombre, perfil, activo)
   - Filters (perfil, activo)
   - "Nuevo" button → form
   - Row actions: Editar, Borrar, Dar de Baja

2. **Formulario Usuario** (nuevo + editar share)
   - Fields from `usuarios` table + any extended profile data
   - Password handling (current md5? plan migration)
   - Perfil selector (from perfiles table)
   - Validation (Former or modern equivalent)

3. **Perfiles** (lower priority in first cut, or read-only list to start)

4. **Permissions matrix** (very later — the 22-slot array is legacy tech debt)

## Data Model Notes (from minimal schema + legacy)

- `usuarios`: id, login, pass (md5), perfil, area, activo, nombre
- `perfiles` table exists in legacy (need to bring minimal columns when we reach it)
- `qnova_acl` + `qnova_bbdd` are in the central "etc" DB (already used by LoginEmpresa)

## Risks / Open Questions

- Password hashing strategy (keep md5 for compatibility during transition? introduce bcrypt + migration column?)
- How much of the old "botones" / sndReq machinery do we need to keep alive for the non-modernized sibling pages?
- Permission checks: do we implement the old `permisos[perfil]` array first, or design a cleaner RBAC as we build the first real module?

## Success Criteria for This Slice

- Clicking any "Usuarios" or child menu item from the real menu lands on a modern page (not cloud 404).
- The modern user list page shows the real rows from the DB (the "admin" user from seed + any we create via the form).
- Create/Edit/Delete work end-to-end with proper validation and feedback.
- Menu remains visible and functional on all these pages (no regression on the Stage 8.3 fixes).
- At least 2-3 new automated tests (form handling, list rendering with real data, permission gate if we implement it).

## Estimated Order of Work

1. Router + Placeholder/LegacyAction already mostly handle unknown routes → good.
2. New `Pages/Usuarios/Listado.php` (or under a Module namespace later).
3. Form page + Former (or lightweight replacement) for create/edit.
4. Wire the menu actions in `resolveLegacyAction` or a new dedicated resolver if we want clean URLs early (`/admin/usuarios` instead of the long colon version).
5. Add the corresponding data patch if any reference data (perfiles seed, etc.) is missing.
6. Tests + docs.

## Related Reference Files

- `reference/legacy-menu-structure.md`
- `reference/menu-renderer-analysis.md`
- `docker/db-init/data-patches/0002-admin-branch-expansion.sql`

---
*Part of the menu-driven modernization plan, May 2026.*
