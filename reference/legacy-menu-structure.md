# Legacy Menu Structure Reference (Tuqan)

**Source**: Extracted from `docker/db-init/00-schema-clean.sql` (historical full dump)  
**Purpose**: The full real legacy menu is now automatically loaded via `0004-full-legacy-menu.sql` during normal `init-db.sh`.

This was done at the user's request so the team can properly verify and improve the menu renderer + layout with actual production data volume (instead of the previous small curated subset).

## Key Facts

- `menu_nuevo`: ~120 items (sequence reset to 120)
- `menu_idiomas_nuevo`: 216 labels (primarily idioma_id 1 = Spanish, 2 = Catalan)
- `permisos`: TEXT column storing boolean arrays of length ~22-23 (one slot per profile type). Position 0 often = admin.
- `accion`: Colon-separated legacy route (e.g. `administracion:usuarios:listado:ver`)
- Many items have `padre` pointing to other menu ids. Some children have `padre = NULL` in the dump (data quality issue in legacy).
- Depth: Mostly 2-3 levels; some administrative action leaves go 4+ levels.

## Top-Level Branches (approximate, from legacy)

| Legacy ID | Accion (or label)          | Notes |
|-----------|----------------------------|-------|
| 65 / 1    | Inicio                     | Dashboard / tasks |
| 66        | Documentacion              | SG docs, formats |
| 74        | Administracion             | **First target for modernization** (users, profiles, menus, messages, idiomas) |
| 76        | Procesos                   | Catalogos, arbol |
| 67        | Proveedores                | Listado, incidencias, contactos, productos |
| 70        | Equipos                    | Calendario, revisiones, listado |
| 68        | Mejora                     | Acciones de mejora |
| 69        | Formacion                  | Cursos, inscripcion, planes, ficha personal, req puesto |
| 71        | Auditorias                 | Programa, plan |
| 72        | Indicadores                | + Objetivos |
| 73        | mAspectos / A.Ambientales  | Matriz, revisiones |
| 75        | Logout                     | Special |

## Deep Dive: Administración Branch (id 74 in legacy)

This is the priority subtree for the first real module work ("user management").

From legacy data:

- 74 → ADMINISTRACION (root of admin)
  - 86, 84, 82, 83, 85, 87, 88, 89, 90, 91, 92, 95, 106, 107, 109, 114, 116, 119, ... (many sub-items)
  - Specific known good items we already have in the dev patch under our synthetic ids:
    - Usuarios (listado, nuevo, editar, borrar)
    - Perfiles (listado)
    - Mensajes (listado)

Legacy labels (idioma 1) for some admin items included "Menus", "Idiomas", "Permisos", "Ayuda", etc.

**Recommendation for modernization**:
- Do **not** load all 120 legacy items into the navbar for daily dev.
- Keep the curated `data-patches/0001-*.sql` + targeted additions (e.g. 0002-admin-*.sql) for the branches we are actively implementing.
- Use this reference document + the full dumps when we need the complete old action names for a new Placeholder/LegacyAction mapping.
- When a module is "done", its menu branch becomes fully modern (no more legacy colon route).

## Current Runtime Menu (dev DB)

See `docker/db-init/data-patches/0001-real-menu-from-legacy.sql` (19 items, 3 levels under Administración → Usuarios).

## How to Add More Menu Data (for new modules)

1. Add a new `00XX-description.sql` in `data-patches/`.
2. Use `INSERT ... ON CONFLICT (id) DO NOTHING` for menu_nuevo and the composite PK for labels.
3. The `init-db.sh` + `data_patches` table guarantees it runs exactly once.

## Next Steps (this leg)

- [ ] Verify current Bootstrap collapse renderer does not become unusable as we add the full Administración subtree.
- [ ] Design menu tests (hierarchy roundtrip, permission filtering, action resolution).
- [ ] Begin vertical slice: Administración → Usuarios (user management screens).

---
*Generated during Stage 8.4 "Full Menu + First Module" planning, May 2026.*
