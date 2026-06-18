# Stage 9.6 — Auditorías basic slice (Programa de Auditoría)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.6-auditorias-programa` (fresh from master)
**Driver**: Next item from the living `.agents/MIGRATION-TODOS.md` Suggested Next Legs after 9.5 (Formación + Documentación shell). "Next verticals": Auditorías (legacy 71).

Follows the exact process ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size).

## Goals (Aligned with Directives)
1. Advance the next **Aplicacion vertical**: basic modern CRUD for `programa_auditoria` (Programas de Auditoría / "Auditoria anual" entry point).
2. Use/extend Catalog* + targeted full overrides (fields: nombre, vigente, activo, revision).
3. Patch 0025 (idempotent table+seed + data_patches).
4. Correct modern + legacy routes (map primary accions from 0004: auditorias:programa:* ).
5. Templates + Pages exactly matching style (with stage notes explaining deferred parts for execution, plan, findings, etc.).
6. Extend verify + full "Stage 9.6 Verification Playbook".
7. Update living MIGRATION-TODOS (flip Auditorías), MIGRATION-PLAN, STAGE-CHECKLISTS.
8. 100% Docker-only, plan-first commit, clean verification, PR sized as "1 vertical basic slice".

## Scope (Reviewable basic vertical slice)
**In:**
- New patch `0025-programa-auditoria-table-and-seed.sql` (columns from legacy schema + 3 demo rows).
- `Pages/Auditorias/{Listado,Formulario}.php` (overrides for all fields).
- `templates/auditorias/{listado,formulario}.twig`.
- Routes: `/admin/auditorias` + legacy for 'auditorias:programa:listado:ver' and related.
- verify-8.6.sh updates (table, count, patch, php-l, header).
- Full 9.6 playbook in STAGE-CHECKLISTS.
- MIGRATION-TODOS flip + Suggested refresh (cross-cut now primed, next: Aspectos Ambientales, Indicadores, Procesos, deeper sub-entities).
- MIGRATION-PLAN Last Updated.
- reference plan (this; first commit).

**Out (explicit future legs):**
- Full `auditorias` table CRUD (execution, findings, informes).
- `plan` / `horario_auditoria`, `tipo_estado_auditoria` integration + lists.
- Equipo auditor, estado transitions, "Hacer Vigente", revision/copia flows, Excel export, links to Mejora acciones.
- Integration with Auditorias side under Personalizacion (auditoriaanual etc. if different).
- Any new base for workflow/relations.

## Pattern to Follow
- Same as 9.3/9.4/9.5: plan first, patch with full columns + synthetic realistic seeds + ON CONFLICT, Catalog* + overrides for extra fields (vigente + revision), full field coverage in custom Form, exact template style (vars from templateDir/flashPrefix, bottom stage .alert notes, no flashes in content, consistent buttons/headers).
- Routes: after 9.5 Documentacion block, using the exact pattern.
- Use `/admin/auditorias` (vertical name) + flashPrefix 'auditorias', templateDir 'auditorias', title 'Programas de Auditoría'.
- Verification: clean room + psql for table/patch/rows + verify-8.6.sh + php -l + browser flows + no regression on prior modules.
- Docs: always update the living TODOS in the PR.

## Data Discovery
- `programa_auditoria` (from 00-schema.sql): id SERIAL, nombre VARCHAR(255) NOT NULL, activo BOOLEAN, vigente BOOLEAN, revision VARCHAR(16).
- Menu (0004): under auditorias branch (71, labels AUDITORIAS), specific 'auditorias:programa:listado:ver' (and :formulario:nuevo, :editar etc; also plan sibling).
- Related: auditorias table references programa via FK; tipo_estado_auditoria for execution state. These + plan/horario/equipo are deferred.
- No prior patch for this table in our sequence (0024 was documentos).
- 3 demo seeds for "Programa Auditoría 202X" with mix of vigente/activo.

## Verification Checklist (Non-Interactive + Human Gate)
- php -l on new Pages/* + index.php + verify.
- After clean init: programa_auditoria table present, 0025 in data_patches, >=3 rows.
- verify-8.6.sh fully green (new table/count in the lists, no breakage on prior 9.1-9.5 asserts or Personalizacion menu invariants).
- Browser (after login):
  - Sidebar to Auditorías → list (nombre, vigente, revision, activo), create/edit with all fields, flash, legacy path works, DB matches.
  - All prior modules (9.5 Formacion/Doc, 9.4 Mejora, etc.) untouched.
- No PHP errors, flashes via prefix, prior modules untouched.
- Post-submit selects confirm values (incl. revision).

## Files To Be Created/Modified
**New**:
- `reference/stage-9.6-auditorias-programa-plan.md` (this; first)
- `docker/db-init/data-patches/0025-programa-auditoria-table-and-seed.sql`
- `Pages/Auditorias/Listado.php`, `Pages/Auditorias/Formulario.php`
- `templates/auditorias/listado.twig`, `templates/auditorias/formulario.twig`

**Modified**:
- `index.php` (routes block)
- `scripts/verify-8.6.sh` (php-l, tables IN, counts, patch comment, header)
- `.agents/MIGRATION-TODOS.md` (flip Auditorías, update snapshot + Suggested + notes)
- `.agents/STAGE-CHECKLISTS.md` (full 9.6 section + todo json)
- `.agents/MIGRATION-PLAN.md` (Last Updated)

## Detailed Execution Order (Strict — Plan First)
1. (This branch) Write + commit this plan as absolute first change.
2. Create the patch 0025.
3. Implement the two Page classes (full overrides).
4. Update index.php routes.
5. Create the two twig templates with stage notes.
6. Extend verify script.
7. Update all .agents/ (TODOS first).
8. Full verification (clean room + targeted psql + verify-8.6.sh + php -l + browser flow).
9. Logical commits, push -u, open PR referencing the TODOS item + this plan.
10. (Post-merge) Human browser sign-off; flip checkbox if needed.

## Success Criteria
- Plan is the first commit.
- After init: programa_auditoria table + patch 0025 + sample rows.
- /admin/auditorias works (list + form + flashes + persistence).
- Legacy path resolves.
- verify-8.6.sh + psql + php -l all green; no regression on anything prior (9.5, Mejora, Equipos, etc.).
- Living TODOS has Auditorías marked + Suggested refreshed.
- PR self-contained, follows every ritual point, delivers working increment for the vertical.

## Risks & Mitigations
- Scope creep into full auditorias/plan/horario: explicitly scoped as "programa basic" only; stage note + deferred list is clear.
- Legacy paths: map the primary programa one + let LegacyAction fallback for others (plan, auditoria execution etc).
- Naming: /admin/auditorias + Auditorias namespace for the programa slice (consistent with how /admin/formacion covered planes; full execution can extend or add sub later).

## Related / Handoff
- Delivers the "Auditorías" vertical starter from Suggested Next.
- After this: Suggested will highlight cross-cut extraction opportunity (after several verticals), or Aspectos Ambientales (linked to Criterios), Indicadores, Procesos.
- Reinforces incremental vertical-by-vertical with basic first slice + ritual.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x). Uses the daily MIGRATION-TODOS navigation.*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**
