# Menu Renderer Scalability Analysis — Stage 8.4

**Date**: 2026-05-30 (start of full-menu + first module leg)  
**Author**: Agent analysis + human direction

## Current Implementation (MainPage.php:99-177)

Two code paths exist:

1. **Legacy path** (`arbol_listas` + `TreeMenu.php`): Still present as fallback. Uses the old tree generator. Has known Docker + host/port + connection assumption problems. Only used when no company context in session.

2. **Active modern path** (`buildSimpleMenuHtml`): Used for all real post-login flows since Stage 8.3.
   - Raw SQL via `Manejador_Base_Datos` (correct host/port from session).
   - Simple `SELECT ... WHERE activo = true ORDER BY orden`.
   - In-memory grouping by `padre`.
   - Recursive closure that emits:
     - `<ul class="nav navbar-nav">` at root
     - For nodes with children: `<a data-toggle="collapse">` + `<div id="menu-N" class="collapse">` + recursive children
     - Leaves: plain `<a href="...">`
   - No permission filtering (`permisos[perfil]` is ignored in this path).
   - No caching whatsoever.

## Problems with Scale

| Aspect              | With 19 items (current patch) | With full ~120 legacy items |
|---------------------|-------------------------------|-----------------------------|
| Top-level branches  | 6                             | 16+                         |
| Avg depth           | 2-3                           | 3-4 (some action leaves deeper) |
| Navbar height       | Acceptable                    | Will exceed viewport on most screens |
| Usability           | "Works" but ugly              | Unusable (no search, no folding strategy, no context) |
| Permission aware    | No                            | Critical gap                |
| Performance         | 1 DB roundtrip per page       | Same, but result set 6x larger |
| Layout conflict     | Already collides with #usermenu col-md-2 | Worse |

The Bootstrap 3 `.navbar-nav` + manual collapse was a pragmatic bridge for the "make the menu work as-is" phase. It is not a long-term container for the real legacy menu volume.

## Layout Conflict with User Card (cabecera3 / #usermenu)

See [templates/main.twig:33-82](/Users/lamigo/codigo/tuqan/templates/main.twig):

```html
<div class="row">
    <div class="collapse navbar-collapse col-md-10" id="submenu">
        {{ submenu|raw }}
    </div>
    <div class="col-md-2" id="usermenu">
        ... dropdown with hardcoded "Nombre Apellido", "correoElectronico@email.com" ...
    </div>
</div>
```

The user card (with its 305px `.navbar-login` popup) sits in the same flex row as the menu. When the menu grows, the 2-column split breaks. This is exactly the "User card in cabecera 3 so it's not interrupting the menu" item.

## Permission Model Reality

Legacy `permisos` column stores strings like:
- `'{f,t,f,f,...}'` (22 positions)
- `'[0:22]={f,f,f,...}'`

Position 0 = admin (full access). The old code did `menu_nuevo.permisos[perfil] = true`.

Our modern renderer must eventually replicate this (or migrate to a proper join table when we modernize profiles).

## Recommendations (for this leg and next)

1. **Immediate (this leg)**:
   - Move user card out of the menu row (new row or inside a top-right fixed area).
   - Make user card data 100% real from session + a cheap extra query on login (real name, email, company display name).
   - Add permission filtering to `buildSimpleMenuHtml` (or document that the curated patch only contains items visible to the demo admin profile).

2. **Short-term (while building first modules)**:
   - Limit the rendered menu to depth 2 in the global navbar.
   - For deeper actions inside a module (e.g. Usuarios → Nuevo), render a secondary "module menu" or breadcrumb + local actions on the actual page.
   - Add very basic "current branch" highlighting using the request path vs `accion`.

3. **Medium-term (when we have real module pages)**:
   - Evaluate a proper sidebar navigation component (persistent or hamburger on mobile).
   - Consider reviving a lightweight version of the old tree only inside the content area for legacy Placeholder pages.
   - Introduce Twig fragment caching or a MenuService with in-request memoization.

4. **Long-term**:
   - When Former is cleaned and real forms exist, the menu should be context-sensitive (only show branches relevant to the current user's most common tasks + admin can see everything).

## Test Implications (see goal 4)

Any menu test suite must cover:
- Hierarchy reconstruction from flat rows
- Permission filtering (different perfiles see different subsets)
- Action → URL resolution (colon → slash + special cases)
- Graceful degradation when DB has no rows or user has no permissions
- Rendering produces valid HTML with correct collapse ids

## Files to Watch

- `Pages/MainPage.php` (the two builders)
- `templates/main.twig` (layout coupling)
- `docker/db-init/data-patches/*.sql` (the data we actually test against)
- `reference/legacy-menu-structure.md` (planning source of truth)

---
*Part of Stage 8.4 "Full Menu + First Real Module" work.*
