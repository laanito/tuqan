# User QA Checklist — Post Stage 9.40 Plateau

**When to run:** After merge of **Proveedores homologación (9.40 / PR #112)** and before investing heavily in GenPDF, WYSIWYG, or deep sub-entities (contactos scoring, ICS, etc.).

**Why this plateau:** Core ISO day-to-day modules are walkable end-to-end in modern `/admin/*` (Documentación, Auditorías↔Mejora, Formación hub, Equipos plan/calendario, Proveedores homologación). Later roadmap items mostly freeze presentation or dig into one vertical.

**Time budget:** ~45–90 minutes for a full pass; ~20 minutes for smoke + one journey only.

**Scope of this round**

| In scope | Out of scope (note only) |
|----------|---------------------------|
| Modern `/admin/*` journeys below | GenPDF binary fidelity |
| Demo data after patches through **0050** | Menu/perfil Phroute authz |
| Happy path + a few edge cases | Non-admin multi-role UX |
| Explicit “shell only” modules | Full Aspectos cuestionario / Indicadores charts |

**Pass criteria for the plateau:** All **P0** items Pass; **P1** failures logged with severity (blocker / major / minor / polish); shell-only items may be Soft-pass if they open and basic edit works.

---

## 0. Environment prep (before browser)

Run once so demo data matches the checklist.

```bash
# Prefer clean room if unsure about patch state
# docker compose --env-file .env.docker down -v
# docker compose --env-file .env.docker up -d
# docker compose --env-file .env.docker exec app ./scripts/init-db.sh

docker compose --env-file .env.docker exec app ./scripts/verify-8.6.sh
```

| # | Check | Expected | P | ☐ Pass / Fail / Notes |
|---|--------|----------|---|------------------------|
| 0.1 | `verify-8.6.sh` | Finishes without error; patches through 0050 present | P0 | |
| 0.2 | App reachable | e.g. `http://localhost:8080` (or your compose URL) | P0 | |
| 0.3 | Login | Company + user (seed: **demo/admin** then **admin/admin** unless you changed it) | P0 | |

**Tester:** _______________ **Date:** _______________ **Commit / master tip:** _______________

---

## 1. Smoke — modern modules open (≈5 min)

Open each list (no deep edit). Fail only on white screen, 500, or “route not found”.

| # | URL | Expected | P | ☐ |
|---|-----|----------|---|---|
| 1.1 | `/admin/documentacion` | List + estado filters | P0 | |
| 1.2 | `/admin/documentacion/arbol` | Tree/group view | P1 | |
| 1.3 | `/admin/auditorias` | Programa list | P0 | |
| 1.4 | `/admin/auditorias/ejecucion` | Ejecución list + Mejora/Hallazgos/Horario cols | P0 | |
| 1.5 | `/admin/auditorias/hallazgos` | Hallazgos list | P0 | |
| 1.6 | `/admin/auditorias/horario` | Horario list | P0 | |
| 1.7 | `/admin/mejora` | Acciones list + estado / filters | P0 | |
| 1.8 | `/admin/formacion` (or planes path you use) | Planes list | P0 | |
| 1.9 | `/admin/formacion/cursos` | Cursos list | P0 | |
| 1.10 | `/admin/formacion/inscripciones` | Inscripciones list | P0 | |
| 1.11 | `/admin/equipos` | Equipos + revisiones counts | P0 | |
| 1.12 | `/admin/equipos/revisiones` | Revisiones list | P0 | |
| 1.13 | `/admin/equipos/calendario` | Year grid + event list | P0 | |
| 1.14 | `/admin/equipos/plan/1` | Plan for demo equipo (or first id) | P0 | |
| 1.15 | `/admin/proveedores` | Homologación badge + productos | P0 | |
| 1.16 | `/admin/proveedores/productos` | Productos list | P0 | |
| 1.17 | `/admin/proveedores/criterios` | Criterios catalog | P0 | |
| 1.18 | `/admin/aspectos` | List (shell) | P1 | |
| 1.19 | `/admin/aspectos/matriz` | Matrix view (shell) | P1 | |
| 1.20 | `/admin/indicadores` | List (shell) | P1 | |
| 1.21 | `/admin/procesos` | List (shell) | P1 | |
| 1.22 | `/admin/procesos/arbol` | Tree (shell) | P1 | |

**Sidebar / menu:** note whether main nav reaches the modules you care about (legacy accion vs modern path). Mismatch is P1 unless module is unreachable entirely (P0).

---

## 2. Journey A — Documentación (workflow + content + binario)

**Story:** Manage a document through estado actions; see text content; download or replace binary attachment.

| # | Steps | Expected | P | ☐ |
|---|--------|----------|---|---|
| A.1 | Open `/admin/documentacion` | Demo rows; estado badges; optional **binario** badge on doc 1 | P0 | |
| A.2 | Filter by estado (e.g. Borrador / Pend. revisión) | List narrows; clear filter restores all | P1 | |
| A.3 | Edit a document (e.g. id 1) | Form loads; estado label; contenido texto; adjunto section if any | P0 | |
| A.4 | Change contenido texto slightly → Guardar | Success flash; content persists on re-open | P0 | |
| A.5 | If adjunto present: Descargar | File downloads (demo `man-001-demo.txt` or replacement) | P0 | |
| A.6 | Upload/replace a small txt/pdf (&lt;5 MB) → Guardar | Metadata updates; download works | P0 | |
| A.7 | Workflow: **Enviar a revisión** (list or form checkbox/action) | Estado → Pend. revisión (3) | P0 | |
| A.8 | **Revisar** | Estado → Revisado; revisado_por / fecha filled | P0 | |
| A.9 | **Aprobar** | Estado → En vigor; aprobado_por / fecha filled | P0 | |
| A.10 | Attempt Aprobar without Revisar (if easy) | Blocked or clear error | P1 | |
| A.11 | Árbol view | Documents visible; no crash | P1 | |

**Notes / bugs:**

---

## 3. Journey B — Auditorías ↔ Mejora ↔ Informe

**Story:** Run an auditoría: schedule slot, finding, improvement action, write informe, print ficha.

| # | Steps | Expected | P | ☐ |
|---|--------|----------|---|---|
| B.1 | `/admin/auditorias/ejecucion` | Rows; counts for Mejora / Hallazgos / Horario | P0 | |
| B.2 | Open ejecución edit (e.g. id 1) | Form + related sections | P0 | |
| B.3 | **+ Horario** or `/admin/auditorias/horario/nuevo?auditoria=1` | Prefill auditoría; create franja; appears on list + ejecución | P0 | |
| B.4 | **+ Hallazgo** prefilled | Create hallazgo; reverse list on ejecución | P0 | |
| B.5 | **+ Mejora** from ejecución | Prefill `auditoria`; create acción; reverse list + Mejora filter `?auditoria=1` | P0 | |
| B.6 | On Mejora: **Verificar** then **Cerrar** (if pending) | Estado transitions; user/fecha auto | P0 | |
| B.7 | **Informe** `/admin/auditorias/informes/editar/{id}` | Edit conclusiones / recomendaciones / lugar / fechas | P0 | |
| B.8 | Guardar informe → ficha `/admin/auditorias/informes/ver/{id}` | Shows meta, conclusions, hallazgos, mejoras | P0 | |
| B.9 | **Imprimir** (browser print) | Print CSS hides chrome; content readable | P1 | |
| B.10 | Hallazgos filter by auditoría | Filter works; clear filter | P1 | |

**Notes / bugs:**

---

## 4. Journey C — Formación hub

**Story:** Walk Planes → Cursos → Inscripciones with counts and filters.

| # | Steps | Expected | P | ☐ |
|---|--------|----------|---|---|
| C.1 | Planes list | Curso counts visible | P0 | |
| C.2 | Open cursos filtered by plan | Only that plan’s cursos; prefill on nuevo | P0 | |
| C.3 | Curso list shows inscripción counts | Click → inscripciones filtered | P0 | |
| C.4 | Create or edit inscripción with curso | Saves; list key not empty (`inscripcion` rows show) | P0 | |
| C.5 | Nav links between the three lists | No 404; filters preserved where expected | P1 | |

**Notes / bugs:**

---

## 5. Journey D — Equipos plan + calendario

**Story:** See maintenance interval, schedule preventivo, see it on calendar and revisiones.

| # | Steps | Expected | P | ☐ |
|---|--------|----------|---|---|
| D.1 | `/admin/equipos` | List; revisión counts; **Plan** button | P0 | |
| D.2 | Edit equipo: change `mantenimiento cada` / días vs meses | Saves | P0 | |
| D.3 | `/admin/equipos/plan/{id}` | Shows interval, next due, historial | P0 | |
| D.4 | **Programar preventivo** | New `mantenimientos` row; flash success; historial updates | P0 | |
| D.5 | `/admin/equipos/calendario` (current year, optional equipo filter) | Marker on planned date; year list includes row | P0 | |
| D.6 | Click marker / edit revisión | Opens revisiones edit | P1 | |
| D.7 | Manual **+ Revisión** (correctivo/revision tipo) | Appears in list + calendar if dated this year | P1 | |

**Notes / bugs:**

---

## 6. Journey E — Proveedores homologación

**Story:** Homologate a supplier; manage products and criteria.

| # | Steps | Expected | P | ☐ |
|---|--------|----------|---|---|
| E.1 | `/admin/proveedores` | Homologación badge; producto counts | P0 | |
| E.2 | **Homologar** on non-homologated row | Badge → Homologado; dates set | P0 | |
| E.3 | **Deshomologar** (confirm) | Badge → No homologado | P0 | |
| E.4 | Homologar again | Works | P1 | |
| E.5 | Edit proveedor: dates / CIF / web | Persist | P1 | |
| E.6 | Productos filter by proveedor | Only that supplier’s products | P0 | |
| E.7 | Nuevo producto prefilled | Create with homologado checkbox | P0 | |
| E.8 | Criterios list + create/edit | Nombre + valor + activo | P0 | |

**Notes / bugs:**

---

## 7. Shell-only modules (short)

Do **not** fail the plateau solely on missing depth. Fail if broken.

| # | Module | Min check | P | ☐ |
|---|--------|-----------|---|---|
| S.1 | Aspectos list + one edit | Saves nombre/scores | P1 | |
| S.2 | Aspectos matriz | Renders groups | P1 | |
| S.3 | Indicadores list + one edit | Saves | P1 | |
| S.4 | Procesos list + arbol | List + tree open | P1 | |
| S.5 | Admin catalogs (e.g. Usuarios, Tipos Mejora) | List opens | P2 | |

---

## 8. Cross-cutting UX (spot checks)

| # | Check | Expected | P | ☐ |
|---|--------|----------|---|---|
| X.1 | After successful POST | Flash success; redirect not lost | P0 | |
| X.2 | Validation error (empty required name) | Error flash; no silent fail | P0 | |
| X.3 | Empty filter result | Friendly empty message | P1 | |
| X.4 | Nested lists (cursos/inscripciones/productos) | Rows render (not always empty) | P0 | |
| X.5 | Prefill from `?fk=` query | Nuevo form shows related parent | P0 | |
| X.6 | Reverse counts on parent | Clickable; match child list | P1 | |
| X.7 | Hard-coded `/admin` only | Acceptable this round; note for authz later | P2 | |

---

## 9. Results summary

| Area | P0 Pass? | P1 open issues | Blocker? |
|------|----------|----------------|----------|
| Env / smoke | | | |
| Documentación | | | |
| Auditorías / Mejora / Informe | | | |
| Formación | | | |
| Equipos | | | |
| Proveedores | | | |
| Shells | | | |
| Cross-cut | | | |

**Plateau decision**

- [ ] **Go** — proceed with next feature legs (GenPDF / contactos / etc.)
- [ ] **Go with fixes** — list P0 bugs to fix first (one or more small PRs)
- [ ] **No-go** — stop feature work until blockers fixed

**Top findings** (link issues / notes):

1.  
2.  
3.  

**Suggested next engineering work after QA:**

1. Fix P0/P1 from this pass  
2. Then roadmap: Auditorías GenPDF · Proveedores contactos · Documentación WYSIWYG (pick one)  

---

## 10. How to re-use this checklist later

- Copy this file to `reference/USER-QA-CHECKLIST-post-X.Y.md` (or add a dated section) when you hit the next plateau (e.g. after GenPDF + authz).  
- Keep **P0 journeys** stable; add rows only for new product depth.  
- Non-interactive `verify-8.6.sh` remains mandatory but **does not replace** this human pass.  
- Living pointer: `.agents/MIGRATION-TODOS.md` (User QA section) and `.agents/STAGE-CHECKLISTS.md`.

---

*Checklist baseline: master with stages through 9.40 (patches through 0050). Update demo row IDs if your seed differs after clean-room init.*
