# Stage 9.22 — Formación more subs: Inscripciones (alumnos) basic slice

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.22-formacion-inscripciones`
**Driver**: Suggested Next Legs after 9.21 (Mejora deeper first slice). Explicitly "more Formación subs (inscripciones etc.)". After Cursos (9.20), next logical sub is inscripciones/alumnos table (links cursos + usuarios, basic inscrito/verificado flags). Contained vertical sub-slice.

Follows exact ritual (plan first, Docker-only, living docs, full verify, reviewable size).

## Goals (Aligned with Directives)
1. Add next Formación sub: basic modern list + form over `alumnos` table (inscripciones).
2. Use Catalog base + relations (to cursos from 9.20, usuarios).
3. New data patch for table (if needed in clean) + seeds.
4. Sub structure: Pages/Formacion/Inscripciones + templates/formacion/inscripciones.
5. Routes under /admin/formacion/inscripciones.
6. Full playbook, verify, TODOS update.
7. Plan first.

## Scope (Reviewable sub-vertical)
**In:**
- Patch 0034-alumnos-inscripciones.sql (CREATE + demo seeds linking to cursos/usuarios).
- Pages/Formacion/Inscripciones/Listado.php + Formulario.php (Catalog, relations).
- Templates: formacion/inscripciones/...
- Routes in index.php.
- Verify extend + 9.22 playbook.
- TODOS refresh.
- This plan (first).

**Out:**
- Full inscripciones features (asistentes, detalles, solicitar plaza/baja, Excel, per-curso views).
- Ficha personal integration, other Formación subs.
- PDF etc.

## Pattern
- Mirror 9.20 Cursos: subdir, Catalog + relations (cursos, usuarios).
- Link to existing Cursos.

## Data
- Table `alumnos` (id, usuario, curso, inscrito, verificado).
- Seeds linked to cursos and usuarios (from init).

## Verification
- php-l, clean init, verify-8.6.sh, psql, browser /admin/formacion/inscripciones list+form, no reg on planes/cursos.

## Risks
- Table name "alumnos" vs legacy "inscripcion": use Inscripciones for modern naming, table 'alumnos'.
- Data: ensure links in seeds.

## Handoff
- Continues Formación vertical.
- Next: more subs, Documentación editor, etc.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**