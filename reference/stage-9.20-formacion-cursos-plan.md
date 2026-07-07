# Stage 9.20 — Formación subs: Cursos basic slice

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.20-formacion-cursos`
**Driver**: Suggested Next Legs after 9.19 (Auditorías execution). Explicitly "Formación subs". Current Formación only covers plan_formacion (planes); cursos table exists in schema but has no modern pages. This is a contained vertical sub-slice under the existing Formación module.

Follows exact ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size).

## Goals (Aligned with Directives)
1. Add first Formación sub: basic modern list + form for `cursos` table.
2. Use Catalog base (with relation to plan_formacion).
3. New data patch `0032-cursos.sql` (CREATE + demo seeds linked to existing plans).
4. Sub-namespace structure: Pages/Formacion/Cursos + templates/formacion/cursos/ (consistent with Ejecucion, Arbol patterns).
5. Routes: /admin/formacion/cursos + legacy mapping if applicable.
6. Full 9.20 playbook, verify updates, TODOS refresh.
7. Plan committed first.
8. No behavior change to existing /admin/formacion (planes).

## Scope (Reviewable sub-vertical slice)
**In:**
- Data patch for cursos table + seeds (core fields: nombre, plan, num_horas, fecha_prevista, activo, tipo, estado, etc.).
- Pages/Formacion/Cursos/Listado.php and Formulario.php (extend Catalog*, use relations for plan).
- Templates: formacion/cursos/listado.twig + formulario.twig (basic, adapted from plan_formacion or otros).
- Add routes in index.php (modern + keep legacy for planes).
- Extend scripts/verify-8.6.sh (php-l, table count, patch presence).
- Full "Stage 9.20 Verification Playbook" in STAGE-CHECKLISTS.md.
- Update MIGRATION-TODOS.md (mark Formación subs progress, refresh Suggested).
- This plan (first commit).

**Out (explicit future legs):**
- Other Formación subs (inscripciones, reqs, ficha_personal_* integration).
- Full cursos features (material, hoja_firmas, estado transitions, links to empleados).
- Deeper integration with plan_formacion or RRHH.
- PDF/exports for cursos.

## Pattern to Follow
- Mirror 9.19 (Auditorías Ejecucion) and earlier subs (e.g. Procesos/Arbol).
- Leverage Catalog + relations polish.
- Keep existing Formación pages untouched.
- Use subdir for clarity.

## Data Discovery
- Table `cursos` (from schema): id, tipo, objetivos, contenido, num_horas, material_*, activo, plan (FK), fecha_*, estado, nombre, responsable, observaciones, calidad, medioambiente, etc.
- Link to plan_formacion (already modern).
- Tipo likely references TipoCursos (modern since early stages).
- Seed 3 rows linked to existing plan_formacion rows.
- Check if 'cursos' table appears in current clean DB (add patch to guarantee).

## Verification Checklist (Non-Interactive + Human Gate)
- php -l on new Pages + templates + index.php.
- Clean room: down -v, up, init-db.sh (0032 applied, cursos rows >0, linked to plans).
- verify-8.6.sh green (extended).
- psql asserts: cursos + join to plan_formacion.
- Browser: /admin/formacion/cursos list + form (create/edit with plan select, flashes).
- No regression on existing /admin/formacion planes or other modules.
- Spot-check one prior module.

## Risks & Mitigations
- FKs (plan, tipo, responsable): use relations helpers; start with plan (guaranteed).
- Table may be in full schema but missing from minimal clean init → patch ensures it.
- Naming: use /formacion/cursos to keep module grouped.

## Related / Handoff
- Advances Formación vertical (first sub).
- Next could be more Formación (inscripciones), Mejora deeper, Documentación editor, or Aspectos full matrix.
- Enables later RRHH/ficha integration.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**