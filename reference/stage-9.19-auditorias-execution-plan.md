# Stage 9.19 — Auditorías execution first slice

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.19-auditorias-execution`
**Driver**: Suggested Next Legs after 9.18 (relations polish on Aspectos). Explicitly lists "Auditorías execution start" as a priority vertical. The basic "programa_auditoria" was done in 9.6; the actual execution (auditorias table, findings, horario, links to Mejora, etc.) remains legacy.

Follows the exact ritual: plan first, Docker-only, living docs, full verify, reviewable size.

## Goals (Aligned with Directives)
1. Deliver first slice of Auditorías execution: basic modern CRUD over the `auditorias` table (core fields: nombre, programa link, fecha, estado, activo, descripcion, etc.).
2. Use CatalogListado + CatalogFormulario (with relations for programa).
3. New data patch for table + demo seeds (idempotent).
4. Add modern routes + legacy mapping if needed.
5. Basic templates (copy/adapt from existing auditorias or other).
6. Full playbook in STAGE-CHECKLISTS, update TODOS, verify.
7. Plan committed first.
8. Docker-only, clean verify, logical PR.

## Scope (Reviewable vertical slice)
**In:**
- Data patch `0031-auditorias-execution.sql`: CREATE TABLE IF NOT EXISTS auditorias (core columns from schema), INSERT demo rows, data_patches record.
- Pages/Auditorias/Ejecucion/Listado.php and Formulario.php (or reuse/extend under Auditorias if clean; use sub for clarity like Arbols).
- Templates: auditorias/ejecucion/ (listado.twig, formulario.twig) basic.
- Routes in index.php: /admin/auditorias/ejecucion and legacy if applicable.
- Use relations polish (loadRelated for programa).
- Extend scripts/verify-8.6.sh (php-l, table presence, row counts).
- Full 9.19 playbook section.
- Update MIGRATION-TODOS (mark item), STAGE-CHECKLISTS.
- This plan (first commit).

**Out (explicit future):**
- Full features: plan/horario, equipo/auditores, hallazgos/findings, informes, estado transitions, links to Mejora acciones, integration with Aspectos/Indicadores.
- PDF/exports, advanced filters.
- Changes to the existing Programa pages.

## Pattern to Follow
- Similar to 9.6 (programa) but now for execution table.
- Leverage current Catalog base + relations (9.15/9.17/9.18).
- Keep programa separate (current /admin/auditorias remains for programas).
- Use sub-namespace or clear naming for execution (e.g. Ejecucion).
- Verification: list shows linked programa name, create/edit works, DB asserts.

## Data Discovery
- Table: auditorias (from schema: id, programa (FK to programa_auditoria), estado, descripcion, observaciones, activo, requisitos, alcance, interna, fecha_realiza, etc.).
- Seed 3-4 demo rows with links to existing programa rows.
- Programa relation using loadRelated/getRelatedLabel.

## Verification Checklist
- php -l on new files + touched.
- Clean room: down -v, up, init-db.sh (patch applied, auditorias rows >0, linked to programa).
- verify-8.6.sh green (extended for new table).
- Browser: /admin/auditorias/ejecucion list + form, create with programa select/label, edit, flashes, no regression on programa list or other modules.
- Post DB: SELECT with join to programa.

## Risks & Mitigations
- Table may have many columns: start with core subset in first slice (expand later).
- Naming: use Ejecucion subdir to keep "Auditorias" for programa clear.
- FK: ensure programa seeds exist (they do from 0025).

## Related / Handoff
- Advances the Auditorías vertical.
- Next could be more execution (horario/findings) or Formación subs or Documentación editor.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**