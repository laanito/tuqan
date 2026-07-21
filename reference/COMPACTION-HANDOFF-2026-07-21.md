# Context Compaction Handoff — 2026-07-21

## Current Git State (at time of compaction)
- Branch: **master** (clean after pull)
- Latest: **22a7176** Merge pull request **#107** (stage 9.35 Auditorías horario)
- All work through **stage 9.35** is merged and on `origin/master`.
- Data patches applied through **0045-auditorias-horario.sql**.

## Last Completed Stage
- **Stage 9.35 — Auditorías horario first slice** (PR #107)
  - Table `horario_auditoria` (legacy columns + modern `auditoria` FK)
  - CRUD `/admin/auditorias/horario` (datetime-local, requisito, auditor, area)
  - Filter/prefill by auditoría; ejecución reverse counts + **+ Horario** + form section
  - Plan first, Docker-only, verify + living docs

## Stages since previous handoff (2026-07-13 / post-9.27)
| Stage | PR | Summary |
|-------|-----|---------|
| 9.28 | #100 | Mejora full state machine (auto user/fecha, quick Verificar/Cerrar) |
| 9.29 | #101 | Mejora ↔ Auditorías cross-links |
| 9.30 | #102 | Formación Planes↔Cursos↔Inscripciones hub + nested list key fix |
| 9.31 | #103 | Documentación estado labels/filter + **`/admin` vs reusable modules note** |
| 9.32 | #104 | Auditorías hallazgos (`hallazgos_auditoria`) |
| 9.33 | #105 | Equipos revisiones (`mantenimientos`) |
| 9.34 | #106 | Documentación A revisión / Revisar / Aprobar |
| 9.35 | #107 | Auditorías horario |

## Living Documents (source of truth)
- **`.agents/MIGRATION-TODOS.md`** — read first on resume (Last updated: 9.35).
- **`.agents/STAGE-CHECKLISTS.md`** — per-stage playbooks through 9.35.
- **`.agents/AGENTS.md`** + ritual in TODOS “How to Use”.
- Plans: `reference/stage-9.xx-*-plan.md`.

### Suggested Next (sized picks from TODOS)
1. **Equipos calendario / plan mantenimiento** · calendar shell · ~15 files · patch? maybe  
2. **Documentación rich content / binario** · richer editor shell · ~15 files · patch? maybe  
3. **Auditorías informes** · informe fields/export shell · ~12 files · patch? small  

Also still open in backlog areas: Aspectos matrix/cuestionario, Procesos full árbol edit, Proveedores homologación, PDF/Excel, Twig upgrade, **Phroute permisos**, path/prefix config for non-admin reuse.

## Critical Ritual (never skip)
1. Read `.agents/MIGRATION-TODOS.md` (Suggested Next + sizing §6)
2. `git checkout master && git pull --ff-only`
3. `git checkout -b feat/stage-9.x-descriptive-name`
4. Write `reference/stage-9.x-...-plan.md` and **commit it first**
5. `todo_write` for multi-step legs
6. Docker-only (`docker compose --env-file .env.docker exec app ...`)
7. Catalog* + Pages/ + templates/; modern + legacy routes when needed
8. Patch `00XX-*.sql` if data/schema; extend `scripts/verify-8.6.sh`
9. Update TODOS + STAGE-CHECKLISTS
10. php -l, init-db / apply patch, verify script, human browser gate
11. Logical commits, `git push -u origin <branch>`, `gh pr create`
12. PR sizing: one clickable outcome, ~8–20 files, 0–1 patch, no second vertical

## Architecture notes to preserve
### `/admin` path vs reusable modules (TODOS §7)
- `/admin/*` is a **URL namespace**, not “admin-only product.”
- Domain (`Pages/*`, `Catalog*`, tables, workflows) is **Aplicación module logic** — reuse later via route aliases / path config / permission filter, **not** a second stack.
- Debt: hard-coded `/admin/...` in templates, fixed `listRoute`, company-login-only auth (legacy menu/perfil permisos not on Phroute yet).

### Catalog / patterns maturity
- **CatalogListado / CatalogFormulario / CatalogTree**: getDb, relations, filters (`auditoria`, `plan`, `curso`, `estado`, `equipo`), getCurrentUserId.
- **Nested list keys**: modules under `formacion/cursos` etc. must set list var = flashPrefix (`curso`, `inscripcion`, `hallazgo`, `horario`, `revision`) — base still uses `templateDir` as key.
- **Cross-links pattern** (Mejora/Auditorías/Formación/Equipos): filter `?fk=`, prefill on nuevo, reverse counts on parent list, related section on parent form.
- **Quick actions pattern** (Mejora 9.28, Documentación 9.34): dedicated POST methods + conditional list buttons + form checkboxes; auto user + today.

### Documentación estado codes (EstadoHelper)
1 En vigor · 2 Borrador · 3 Pend. revisión · 4 Revisado · 5 Pend. aprobación · 6 Histórico

### Login
- `$_SESSION['id_usuario']` set on user login (9.28) for workflow auto-assign.

### Data patches (high water mark)
- Through **0045** (horario). Tables added in late stages: `hallazgos_auditoria`, `mantenimientos` (revisiones), `horario_auditoria` (+ auditoria column), content/workflow patches 0036–0044.

### Verification
- Non-interactive: `docker compose exec app ./scripts/verify-8.6.sh` + php -l + psql.
- Human browser gate remains the contract for POST/UI flows.
- Clean room when schema/patch changes: `down -v`, `up`, `./scripts/init-db.sh`.

## When resuming after compaction
1. Re-read **full** current `.agents/MIGRATION-TODOS.md`
2. Confirm git: master, clean, pulled
3. Pick one sized next pick (or discovery if schema missing)
4. Fresh `todo_write` + plan.md **first commit** on new branch
5. Do **not** re-implement merged stages 9.28–9.35

## Optional / not started this session
- Blog posts in `../praderasblog/` (agentic series) — only if user asks
- Twig upgrade, PDF/Excel, questionnaire engine

This handoff supersedes **COMPACTION-HANDOFF-2026-07-13.md** for resume. Older detail lives in stage plans under `reference/stage-9.*-plan.md` and STAGE-CHECKLISTS.
