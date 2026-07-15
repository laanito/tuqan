# Context Compaction Handoff — 2026-07-13

## Current Git State (at time of compaction)
- Branch: master (clean, no uncommitted changes)
- Latest commit: 98acea7 Merge pull request #99 (stage 9.27)
- All work up to and including stage 9.27 has been reviewed, merged, and pulled.

## Last Completed Stage
- **Stage 9.27 — Mejora deeper: basic state machine**
  - Added verify/close action checkboxes and computed states (Pendiente/Verificada/Cerrada) in modern Mejora.
  - Updated Listado/Formulario and templates.
  - Small patch for demo data.
  - Plan committed first, full verify, living docs updated.
  - PR: #99

## Living Documents (source of truth)
- `.agents/MIGRATION-TODOS.md` — **read this first** on any resume.
- Suggested Next (as of now):
  - More Mejora (full state machine/links)
  - more Formación
  - Documentación (rich editor)
  - or broader polish.
- All stages through 9.27 are marked [x].
- ~16+ modern modules on the Catalog base (snapshot may lag slightly; actual progress includes Cursos, Inscripciones, etc.).
- Strong foundation in base classes, relations, trees, and initial workflows.

## Critical Ritual Rules (never skip)
1. Read `.agents/MIGRATION-TODOS.md` (focus on Suggested Next Legs)
2. `git checkout master && git pull --ff-only`
3. New branch: `feat/stage-9.x-descriptive-name`
4. Write `reference/stage-9.x-...-plan.md` and **commit it first**
5. `todo_write` for the leg (for 3+ steps)
6. Docker-only (`docker compose exec app ...`)
7. Pages/ + templates/ extending Catalog* (use recent patterns for workflows, relations)
8. Wire modern + legacy routes in index.php when applicable
9. Extend `scripts/verify-8.6.sh` + append full playbook section to `.agents/STAGE-CHECKLISTS.md`
10. Update living TODOS + MIGRATION-PLAN
11. Full clean-room verify (down -v, init-db.sh, psql asserts, verify script, php -l, browser flows)
12. Logical commits, push, open PR

## Technical Notes to Preserve
- Mejora now has extensive workflow fields (detectado, verifica, implantacion, cerrado, auditoria links, etc.) and basic state machine (states computed from fields, actions in form).
- Documentación has content editor (basic texto via contenido_texto), perfiles, workflows.
- Catalog base is mature with relations, filters, trees (CatalogTree), etc.
- Data patches up to 0037 (Mejora states).
- Verification strategy (non-interactive + human gate) has held.
- Blog article work for recent progress was handled in sibling praderasblog repo in prior compactions.

## When Resuming After Compaction
- Immediately re-read the full current `.agents/MIGRATION-TODOS.md`
- Confirm git state (master + clean)
- Choose next slice (reviewable size, vertical or focused cross-cut; e.g. full Mejora state machine or Documentación rich editor)
- Open fresh `todo_write`
- Start with a plan.md committed first on a new feature branch

## Open / Next Areas (high level)
- Mejora: full state machine (transitions, more integration with Auditorias/Aspectos/Indicadores)
- Formación: more subs (reqs, ficha personal, etc.)
- Documentación: rich editor (beyond basic texto), full workflows, formats/plantillas, PDF
- Aspectos: full matrix + revisiones + cuestionario
- Auditorías: full execution (plan/horario, findings)
- Equipos: revisiones + maintenance
- Procesos: full Árbol editing
- Cross-cuts: PDF/Excel modernization, questionnaire engine, broader base polish, Twig upgrade
- Broader polish on early modules or dashboard/main page.

This handoff replaces the older 2026-07-05 version. All prior context is summarized in the living `.agents/` files and reference plans.
