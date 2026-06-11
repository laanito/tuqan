# Stage 9.2 — Proveedores Migration Plan

**Purpose**: Plan first slice of Proveedores module migration. This is a medium-complexity vertical under the "Proveedores" area (legacy line 67, now standalone top-level).

## Scope for First Leg

### What's In
- **Proveedores main entity**: Listado + Formulario CRUD
    - Table: `proveedores` (columns inferred from legacy: id, nombre, telefono, activo)
    - Legacy action: `administracion:proveedorlistado:baja:general` (view list), with alta/baja/edit variants
    - Modern routes: `/admin/proveedores`, `/admin/proveedores/nuevo`, `/admin/proveedores/editar/{id}`

### What's Out (Future Legs)
- **Contactos Proveedores** (`contactos_proveedores` table): id, nombre, telefono1, telefono2, activo
- **Incidencias** (`incidencias` table): id, nombre, fecha, activo — likely not a child of proveedores but shared
- **Productos** (`productos` table): id, nombre, homologado, fecha_revision, activo — complex relations (proveedor link)
- **Phomologados** view (proveedores with homologados productos)
- Any "homologacion" flows

## Pattern to Follow

Following the established `CatalogListado` + `CatalogFormulario` pattern from stages 8.5–9.1:

1. **Pages/Proveedores/Listado.php**: ~5 lines extending CatalogListado, setting table='proveedores', title='Proveedores', templateDir='proveedores', flashPrefix='proveedor'
2. **Pages/Proveedores/Formulario.php**: ~6 lines extending CatalogFormulario, same config + listRoute='/admin/proveedores'
3. **templates/proveedores/listado.twig**: Copy from existing (e.g., 'clientes/' or '/proveedores/'), add fields for nombre, telefono, activo + flash messages + link to "nuevo" form
4. **templates/proveedores/formulario.twig**: Similar structure with form POST to /admin/proveedores/nuevo or editar/{id}

## Routes to Add to index.php

Modern Phroute entries:
- `GET /admin/proveedores` → Listado ShowPage
- `GET /admin/proveedores/nuevo` → Formulario ShowPage  
- `GET /admin/proveedores/editar/{id}` → Formulario ShowPage
- `POST /admin/proveedores/nuevo` → Formulario Procesar
- `POST /admin/proveedores/editar/{id}` → Formulario Procesar

Legacy menu accion mappings:
- `GET /administracion/proveedores/listado/ver` → Listado (redirect/fallback to list)
- Legacy variants for actual legacy action codes (proveedorlistado:baja:general, etc.) — may not match perfectly; will note in checklist

## Data Patches Considerations

From the existing catalog pattern (see `0013-clientes-table-and-seed.sql`):
1. Create new patch `0021-proveedores-table-and-seed.sql` if `proveedores` table doesn't exist
    - `CREATE TABLE IF NOT EXISTS proveedores (id serial PRIMARY KEY, nombre VARCHAR(255) NOT NULL, telefono VARCHAR(64), activo BOOLEAN DEFAULT true)`
    - 2-3 demo INSERT rows ON CONFLICT DO NOTHING
    - Record in data_patches
2. If Proveedores menu entry already exists in menu_nuevo (from full legacy dump via 0004/0010), no additional patch needed for the row itself
3. Optionally: add menu_idiomas_nuevo entries for Spanish + English labels

## Data Discovery Notes

From `Classes/Procesar_Listados.php` around line 1341:
- Main list case: `'proveedores:listado:ver'` 
- Fields: `id`, `nombre as <gettext('sPCNombre')>`, `telefono as <gettext('sPCTelefono')>`
- Where clause: `'proveedores.activo=true'` → shows only active rows by default

Other legacy cases discovered:
- `'proveedores:incidencias:ver'` — not a direct child, shared incidencias table
- `'proveedores:contactos:ver'` — contactos_proveedores table  
- `'proveedores:productos:ver'` — products with homologado flag
- `'proveedores:phomologados:ver'` — filtered view of proveedores that have homologated products

## Verification Checklist

1. `docker compose exec app php -l Pages/Proveedores/Listado.php`
2. `docker compose exec app php -l Pages/Proveedores/Formulario.php`
3. `docker compose exec app php -l index.php` syntax check (PHP lint)
4. Verify table exists: `docker compose exec db psql -U qnova -qnova -c "SELECT count(*) FROM proveedores;"`
5. Browser: Login → navigate to Proveedores list → open "nuevo" → submit → verify row in DB
6. Verify no deprecation/warning noise on Proveedor pages

## Files To Be Created/Modified

New files:
- `Pages/Proveedores/Listado.php`
- `Pages/Proveedores/Formulario.php`  
- `templates/proveedores/listado.twig`
- `templates/proveedores/formulario.twig`
- `docker/db-init/data-patches/0021-proveedores-table-and-seed.sql`
- `reference/stage-9.2-proveedores-plan.md` (this file)

Modified files:
- `index.php` — add Proveedores routes (~30 lines)
- `.agents/MIGRATION-TODOS.md` — check Proveedores item, note stage 9.2 progress
- `scripts/verify-stage.sh` or extend existing verify script
