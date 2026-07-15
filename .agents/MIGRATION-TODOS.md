# Tuqan Migration TODOs — Remaining Modules to Migrate (Daily Navigable List)

**Purpose**: This is the **primary daily work list** for ongoing Stage 8.x+ module migration legs.  
MIGRATION-PLAN.md = architecture, constraints, high-level stages + history.  
STAGE-CHECKLISTS.md = detailed per-leg playbooks, exact commands, evidence, retrospective lessons.  
**This file** = scannable "what is left, how big is it, what should the next PR be?" + handoff package for other agents.

**Last updated**: Stage 9.29 on `feat/stage-9.29-mejora-auditorias-cross-links` (Mejora ↔ Auditorías cross-links). Previous: 9.28 full state machine polish.

---

## How to Use (Agents + Humans — Read This Every Leg)

1. **Start of leg**: Read this file (focus on "Suggested Next Legs" + the area groups). Pick a reviewable slice (target: similar volume to 8.6 / 8.7 / 8.8 / 8.9 — a few full simple modules, or 1 complex vertical + supporting patch/routes/docs).
2. **Ritual (non-negotiable, from AGENTS.md + all prior stages)**:
   - Stay in local folder, use relative paths.
   - `git checkout master && git pull --ff-only` (or fetch + ensure clean).
   - `git checkout -b feat/stage-9.x-descriptive-name`
   - Write (or update) a short plan in `reference/stage-9.x-...-plan.md` and commit it **first**.
   - Implement (Docker-only: `docker compose exec app ...` for everything).
   - Update **this file**: flip checkboxes to [x], add notes/evidence links, refresh "Current snapshot" and "Suggested next".
   - Add/extend a full "Stage 9.x Verification Playbook" section in STAGE-CHECKLISTS.md (todo items, validation commands, DB asserts, browser flows, evidence).
   - Update MIGRATION-PLAN.md timeline/status if the stage milestone warrants it.
   - Verify: `docker compose exec app ./scripts/verify-8.6.sh` (extended as needed), php -l on touched, psql asserts after init-db.sh, clean room where possible.
   - Commit (logical chunks), `git push -u origin <branch>`, open PR.
   - "if git fails retry".
3. **Marking done**: Only `[x]` items **after merge + verified** (the human who asked does final browser pass on the flows; non-interactive gates must be green).
4. **Discovery**: During any leg, if you surface a new module, missing accion, extra table, or gap — add it to the right group here *immediately* with a short note + suggested size.
5. **Handoff to other agents**: The checkboxes + "Suggested Next Legs" shortlist + latest snapshot + links to the just-completed stage plan/checklist section are 90% of what the next agent needs. They still read the top of MIGRATION-PLAN + AGENTS.md once.
6. **PR sizing**: "the size of PRs we want from now on". One vertical or 2-3 catalogs + patch + routes + verify + docs is the model. Avoid giant refactors.

**Quick navigation**: Use your editor's outline / search for `### ` (area headers) or `[ ]` (open items).

---

## Current Snapshot (Post 8.9 Catalog Base + Menu Hygiene)

**Modern (Pages/ + templates/ + modern+legacy routes, most on Catalog base)**: 16 modules
- Usuarios, Perfiles, Sedes (ex-Empresas)
- Menus (batch orden + labels via Listado::Procesar), Idiomas, Permisos (matrix)
- Clientes, Criterios (catalog base)
- TiposMejora, TiposAreas, TipoDocumento, TiposAmb, TiposImp, TipoCursos (all catalog base post 8.7-8.9)
- Proveedores, Equipos, Acciones Mejora, Formación (Planes), Documentación (shell + tree start), Auditorías (Programa), Indicadores, Procesos (shell + arbol) — Stages 9.2-9.12

**Personalizacion (under Administracion/Aplicacion) catalogs**: Complete for the 7 original + Tipo Cursos (stages 8.5-8.9). Menu cleaned in 8.9 (0017 reparent actionable, 0018 delete redundant empty sections).

**Pending small note from 8.9/0018**: Row 84 ("Criterios" section, no accion) was removed as redundant. User recall: it should be "Criterios Ambientales" with a proper action and placed as direct child of Personalizacion (padre = 1400). Capture below as XS item.

**Everything else**: Still served via legacy entry points (arbol_*.php, cuestionario.php, editor.php, GenPDF, crearExcel, graficaIndicadores.php, items.php, procesa_*, etc.) + LegacyAction fallback for unmapped colon accions. Top-level Aplicacion branches (Documentación 66, Procesos 76 [basic shell], Proveedores 67, Equipos 70, Mejora 68, Formación 69, Auditorías 71, Indicadores 72, Aspectos Ambientales 73) have no modern Pages/* equivalents yet (or only basic for Procesos).

**Infra ready for more modules**: Docker-only, init-db.sh + data_patches (idempotent), Phroute (clean /admin/* + legacy /administracion/*), Twig layouts/app + per-module, prepared statements path via Manejador_Base_Datos::consultaPreparada, MainPage sidebar from real menu_nuevo, flash pattern, verify-8.6.sh + playbook discipline.

**No uncommitted work** on this branch at creation (pure new leg).

---

## Suggested Next Legs (Start Here for Daily Pace)

Pick 1-2 related items that together form a reviewable PR. Update this list when you complete legs.

- [x] **XS hygiene + note closure** (Criterios Ambientales): Delivered in Stage 9.1 (see above checkbox + 0019/9.1 plan).
- [x] **One real Aplicacion vertical (medium)**: Proveedores delivered Stage 9.2; Equipos (core listado + form for numero/descripcion + basic fields + activo) delivered in Stage 9.3 (0021 patch, correct routes + Listado/Formulario, templates, verify, full playbook + TODOS refresh). Route wiring bug from 9.2 Proveedores list also corrected here as hygiene.
- [x] **Small vertical or next Aplicacion**: Basic slice for Mejora (acciones beyond the TiposMejora catalog) delivered in Stage 9.4 (0022 patch for acciones_mejora, Pages/Mejora + full custom overrides for descripcion/fecha/cerrada + other fields, routes, templates, verify + playbook, TODOS refresh). See 9.4 plan and STAGE-CHECKLISTS.
- [x] **Formación focused leg + Documentación shell**: Delivered in Stage 9.5 (0023 plan_formacion + 0024 documentos; Pages/Formacion + Pages/Documentacion basic shell + list/landing + routes + templates + verify + playbook). Planes as first Formación slice (nombre + activo + flags); Documentación as modern landing + basic list (tree/editor/workflow/perfiles deferred). See 9.5 plan and STAGE-CHECKLISTS.
- [x] **Auditorías basic vertical**: Delivered in Stage 9.6 (0025 programa_auditoria; Pages/Auditorias + templates for core nombre/vigente/revision/activo programa list+form; routes + verify + playbook). First slice of Auditorías (legacy 71 "programa"/"Auditoria anual"); execution, plan, horario, findings, informes and links deferred. See 9.6 plan and STAGE-CHECKLISTS.
- [x] **Aspectos Ambientales basic vertical**: Delivered in Stage 9.7 (0026 for `aspectos`; Pages/Aspectos + templates for nombre + score fields + tipo + area + activo basic list+form; routes + verify + playbook). First shell of Aspectos Ambientales (legacy 73); matrix, revisiones, cuestionario integration and supporting catalogs deferred. See 9.7 plan and STAGE-CHECKLISTS.
- [x] **Cross-cut extraction (first delivery)**: Stage 9.8 — richer helpers added to CatalogListado + CatalogFormulario (getDb, getSidebar, loadItem, getPostData, validate, persist, build*Variables, fetchItems). Base ShowPage/Procesar now use them. Duplication for future rich modules (and refactors of existing) dramatically reduced. See 9.8 plan. More extraction (filters, relations, tree) can continue in follow-ups.
- [x] **Indicadores basic vertical**: Delivered in Stage 9.9 (0027 for core `indicadores`; Pages/Indicadores using 9.8 base + overrides for definicion/valores/responsables/frecuencias/etc + templates + routes + verify + playbook). Charts, objetivos/metas, calculations and dashboard deferred. See 9.9 plan and STAGE-CHECKLISTS.
- [x] **Procesos basic vertical**: Delivered in Stage 9.10 (0028 for core `procesos`; Pages/Procesos using 9.8 base + overrides for nombre/codigo/revision/padre/activo + templates + routes + verify + playbook). Árbol/tree, contenido_procesos, flujogramas, per-process indicators, ficha and workflows deferred. See 9.10 plan and STAGE-CHECKLISTS.
- [x] **Procesos deeper (Árbol + contenido basic)**: Delivered in Stage 9.11 (0029 for contenido_procesos seeds + Pages/Procesos/Arbol modern tree view using padre hierarchy + contenido summaries; legacy arbol route now modern; flat list preserved). Full editing, drag-drop, flujogramas and arrays deferred. See 9.11 plan and STAGE-CHECKLISTS.
- [x] **Documentación tree first slice**: Delivered in Stage 9.12 (modern Arbol/tree view shell for documentos, grouped display, new page + template + route). Full tree structure, editor, perfiles and workflows deferred. See 9.12 plan and STAGE-CHECKLISTS.
- [x] **Cross-cut tree helpers (first delivery)**: Delivered in Stage 9.13 (added resolveParentNames, groupItems, initTwig, buildCommonVariables to CatalogListado; refactored Procesos/Arbol and Documentacion/Arbol to use them). See 9.13 plan and STAGE-CHECKLISTS.
- [x] **Aspectos matrix first slice**: Delivered in Stage 9.14 (modern grouped matrix view for aspectos with evaluation scores, using helpers; route added). Full editable matrix, revisiones and cuestionario integration deferred. See 9.14 plan and STAGE-CHECKLISTS.
- [x] **Cross-cut: list-with-filters + form relations (first delivery)**: Delivered in Stage 9.15 (getFilterParams + fetchFilteredItems in Listado; loadRelated + getRelatedLabel in Formulario; demo in Aspectos list). See 9.15 plan and STAGE-CHECKLISTS.
- [x] **Cross-cut: full tree base (first delivery)**: Delivered in Stage 9.16 (new CatalogTree base class; Procesos/Arbol and Documentacion/Arbol now extend it). See 9.16 plan and STAGE-CHECKLISTS.
- [x] **Cross-cut: relations polish (first delivery + adoption)**: Delivered in Stage 9.17 (promoted helpers to Listado, added getRelatedOptions for selects; refactored Mejora Form to use base overrides + enriched list+form with resolved labels + dropdowns). See 9.17 plan and STAGE-CHECKLISTS.
- [x] **Cross-cut: relations polish for Aspectos**: Delivered in Stage 9.18 (refactored Aspectos Form to base overrides, Listado enrichment, select + label for tipo_aspecto). See 9.18 plan and STAGE-CHECKLISTS.
- [x] **Auditorías execution first slice**: Delivered in Stage 9.19 (basic list+form over auditorias table + patch + programa relation + routes/templates). See 9.19 plan and STAGE-CHECKLISTS.
- [x] **Formación subs first slice (Cursos)**: Delivered in Stage 9.20 (basic list+form for cursos + patch + plan relation + routes/templates). See 9.20 plan and STAGE-CHECKLISTS.
- [x] **Mejora deeper workflow first slice**: Delivered in Stage 9.21 (added user assignments + auditoria link via relations; list/form enriched). See 9.21 plan and STAGE-CHECKLISTS.
- [x] **Formación more subs (Inscripciones)**: Delivered in Stage 9.22 (basic list+form for alumnos/inscripciones + patch + relations to cursos/usuarios). See 9.22 plan and STAGE-CHECKLISTS.
- [x] **Documentación editor/perfiles first slice**: Delivered in Stage 9.23 (refactored Form to base + added perfiles checkboxes + tipo/area). See 9.23 plan and STAGE-CHECKLISTS.
- [x] **Documentación deeper (workflows) first slice**: Delivered in Stage 9.24 (added revisado_por/aprobado_por + dates + labels). See 9.24 plan and STAGE-CHECKLISTS.
- [x] **Mejora deeper workflow: remaining fields and basic state**: Delivered in Stage 9.25 (added usuario_verifica, fecha_verifica, usuario_implantacion, fecha_cierre + labels). See 9.25 plan and STAGE-CHECKLISTS.
- [x] **Documentación content editor first slice**: Delivered in Stage 9.26 (basic text content editing via contenido_texto in form). See 9.26 plan and STAGE-CHECKLISTS.
- [x] **Mejora deeper: basic state machine**: Delivered in Stage 9.27 (added verify/close action checkboxes, computed states in list, basic validation). See 9.27 plan and STAGE-CHECKLISTS.
- [x] **Mejora full state machine polish**: Delivered in Stage 9.28 (auto user+fecha on transitions, quick Verificar/Cerrar routes+buttons from list, estado badge in form, login id hygiene + base helper). See 9.28 plan and STAGE-CHECKLISTS.
- [x] **Mejora ↔ Auditorías cross-links first slice**: Delivered in Stage 9.29 (filter Mejora by auditoría, reverse counts/list on ejecución, prefilled create, clickable links both ways). See 9.29 plan and STAGE-CHECKLISTS.
- [ ] **Next verticals / polish**: Mejora links to Aspectos/Indicadores (when schema allows), more Formación subs, Documentación (rich editor), Auditorías hallazgos, or broader polish.

Aim for a mix: one "close the small gaps" + one "new vertical" per couple of legs. Keep delivering working, reviewable increments.

---

## Remaining Modules by Functional Area

### Personalización / Aplicación Admin (largely complete; small gaps)
- [x] All 7 original Personalizacion items + Tipo Cursos + supporting (Clientes, Criterios, Tipos* x6) — Stages 8.5-8.9, catalog base.
- [x] Core admin under Aplicacion/Administracion: Usuarios, Perfiles, Sedes, Menus, Idiomas, Permisos (8.5+).
- [x] Criterios Ambientales (row 84) — restored as direct actionable child of Personalizacion (padre=1400) with label "Criterios Ambientales" + proper accion. Done in Stage 9.1 (0019 patch + 0018 NOTE closure + title updates in Criterios pages + verify assert). See 9.1 plan and playbook.
- [ ] Any remaining child actions (nuevo/editar) under existing modern parents if menu expectations differ from in-page buttons (sedes has some; most don't — decide consistently).

### Aspectos Ambientales / mAspectos (linked to Criterios)
- [x] Aspectos / Aspectos Ambientales basic shell — core `aspectos` table (nombre + scores + tipo + activo) delivered in Stage 9.7 (0026 patch + Pages/Aspectos + routes + verify). Basic list + form. Full matrix, revisiones, cuestionario flows, FK resolution and "Emergencia" deferred. See 9.7 plan/playbook.
- [x] Aspectos matrix first slice — modern grouped matrix view delivered in Stage 9.14 (Pages/Aspectos/Matriz + template showing scores by area). Full editable matrix, revisiones and cuestionario integration deferred. See 9.14 plan and STAGE-CHECKLISTS.
- [x] Relations polish + base adoption for Aspectos (labels/select for tipo_aspecto) delivered in Stage 9.18. See 9.18 plan.
- [ ] Full matrix + revisiones + cuestionario integration + reporting tie-in to Indicadores.

### Mejora (Improvement)
- [x] Acciones de Mejora (legacy 68) — basic CRUD + relations in 9.4/9.17; deeper workflow slices in 9.21 (initial assignments), 9.25 (remaining fields + basic state), 9.27 (basic state machine with verify/close), 9.28 (fuller: auto transitions, quick actions from list, estado UI), 9.29 (Auditorías bidirectional cross-links: filter, reverse list, prefilled create). See 9.4 + 9.17 + 9.21 + 9.25 + 9.27 + 9.28 + 9.29 plans.
- [x] Integration with Auditorías (first slice) — Stage 9.29. Aspectos / Indicadores cross links still deferred (no FK columns on acciones_mejora yet).

### Formación (Training)
- [x] Formación basic (Planes) delivered in Stage 9.5. Cursos (9.20), Inscripciones (9.22) subs delivered. More (reqs, ficha etc.) deferred. See 9.5 + 9.20 + 9.22 plans.
- [ ] Link to Empleados / RRHH concepts if present in legacy (ficha personal); remaining Formación subs.

### Proveedores (Suppliers)
- [x] Proveedores (legacy 67) — basic listado + form (nombre + telefono + activo) in Stage 9.2. Followed catalog base + full routes + data patch + verify + playbook. Sub-entities (contactos, incidencias, productos/homologados) deferred to later legs. (See 9.2 plan and STAGE-CHECKLISTS.)
- [ ] Homologacion / evaluation flows (common in ISO supplier management).

### Equipos (Equipment / Assets)
- [x] Equipos (legacy 70) — basic listado + form (core fields: numero, descripcion, modelo, ubicacion, activo + supporting) in Stage 9.3. Followed catalog base + full routes (corrected pattern) + data patch 0021 + verify + playbook. Sub-flows (revisiones, calendario, plan mantenimiento, etc.) deferred to later legs. (See 9.3 plan and STAGE-CHECKLISTS.)
- [ ] Revisiones + compliance records + maintenance workflows (tie to Auditorias / Mejora).

### Documentación (Core ISO — high value, likely larger)
- [x] Documentación initial shell delivered in Stage 9.5. Tree in 9.12. Editor/perfiles in 9.23, workflows in 9.24, content editor (texto) in 9.26. Full rich editor, binario, more, PDF deferred. See 9.5 + 9.12 + 9.23 + 9.24 + 9.26 plans.
- [ ] Full tree + editor modernization, perfiles, workflows, formats/plantillas.
- [ ] PDF ficha / export modernization (GenPDF.inc, related) — cross-cutting but surfaces here.

### Auditorías (Audits)
- [x] Auditorías (legacy 71) basic slice — `programa_auditoria` delivered in Stage 9.6. Execution first slice (auditorias table basic CRUD + programa link) delivered in Stage 9.19. Mejora reverse links (counts + related list + create) in Stage 9.29. Full execution (plan/horario, hallazgos, informes) deferred. See 9.6 + 9.19 + 9.29 plans.
- [ ] Full execution + plan/horario + findings / follow-up. Integration with Indicadores + Aspectos.

### Indicadores + Objetivos (KPIs)
- [x] Indicadores basic (legacy 72) — core `indicadores` table delivered in Stage 9.9 (0027 patch + Pages/Indicadores using enhanced 9.8 bases + templates + routes + verify). Fields: nombre, definicion, valores (inicial/objetivo/tolerable), tecnica, responsables, frecuencias, activo, genera_objetivo. Charts/graphs, full calculations, metas_indicadores, objetivos and dashboard deferred. See 9.9 plan/playbook.
- [ ] Full Objetivos + Metas + reporting/dashboard integration. Charts may stay legacy or modernized later.

### Procesos (Processes)
- [x] Procesos (legacy 76) — basic catalog shell (core `procesos` table) delivered in Stage 9.10 (0028 patch + Pages/Procesos + templates + routes + verify). Core fields + list/form. Tree/arbol, contenido_procesos, flujogramas, indicators linkage and workflows deferred. See 9.10 plan and STAGE-CHECKLISTS.
- [x] Procesos deeper — Árbol + contenido basic delivered in Stage 9.11 (0029 + Pages/Procesos/Arbol modern tree view with hierarchy + contenido summaries; legacy arbol path updated). Full interactive tree editing, flujogramas and rich workflows deferred. See 9.11 plan and STAGE-CHECKLISTS.
- [ ] Full Árbol editing + complete sub-entities (flujos, indicators de proceso, approval flows).

### Other / Smaller or Cross
- [ ] Mensajes / Tareas (were reparented to Administracion in 0010; check if still using legacy or need modern pages).
- [ ] Ayuda / context help system (Manejador_Ayuda etc.).
- [ ] Full Usuarios enhancements (contact info was added early; more profile / company user fields?).
- [ ] Permisos/Menus/Idiomas deeper (they are modern but batch/matrix; possible later base extraction or UI polish).
- [ ] Calendar component modernization (used by Equipos/Formacion/etc.).
- [ ] Bulk import/export (crearExcel.php patterns) + reporting.

**Notes on discovery**: Many legacy accions in the full 0004 menu (and 0005 reorders) point to colon routes that currently fall to LegacyAction or 404-ish. As we modernize we either (a) add matching modern routes + update accion in a patch or (b) keep legacy path mapping in index.php (current pattern for all done modules).

---

## Cross-Cutting / Non-Module Backlog (Important but Not "a Module")

- [ ] Twig 1.x → 2/3 (or 3) proper upgrade (deferred repeatedly; vendor patches in place; will touch all templates + possibly custom extensions when we have many modules).
- [x] More base class extraction — first delivery in Stage 9.8: enhanced CatalogListado + CatalogFormulario with protected helpers. 9.13 tree, 9.15 filters/relations, 9.16 full tree base (CatalogTree), 9.17 relations polish + getRelatedOptions + adoption in early modules, 9.18 Aspectos, 9.19 vertical, 9.20 Formación sub, 9.21 Mejora deeper, 9.22 Formación sub, 9.23 Documentación, 9.24 workflows, 9.25 Mejora more, 9.26 content editor, 9.27 Mejora state, 9.28 Mejora state + getCurrentUserId + login id capture, 9.29 getFilterParams auditoria + Mejora/Auditorías cross-nav. See 9.8–9.29. Broader adoption remains open.
- [ ] PDF / Excel / report generation modernization (GenPDF, crearExcel, related generators — used by almost every vertical for "ficha", exports, compliance outputs).
- [ ] Tree / arbol UI + generators (arbol_documentos.php, estructura_arbol.php, generador_arboles.php, dhtmlgoodies tree, saveNodes etc. — core for Documentación + Procesos; big but high leverage).
- [ ] Questionnaire / checklist engine (cuestionario.php + procesa_cuestionario.php + procesa_Editor — used by Aspectos, Auditorias, possibly others; aspects/audits/reqs depend on it).
- [ ] Real integration + characterization tests for modern Pages/*/Formulario::Procesar and business rules (current strategy is verify script + playbook + human gate; move more to automated as DB layer cleans).
- [ ] Legacy bloat continued cleanup (FCKeditor in document context, old Image/, PEAR remnants once usage is fully mapped).
- [ ] PHPStan level raises + more strict CI (after more surface is modern).
- [ ] i18n completeness (catalan partial, more strings in modern templates, Locale/ maintenance).
- [ ] Auth / permissions matrix full coverage + admin UX for assigning (Permisos module exists but depth?).
- [ ] Main page / dashboard evolution (currently enhanced placeholder + tree; surface indicators, tasks, recent docs).

These often make good "alongside a vertical" work or dedicated legs when a cluster of modules would benefit.

---

## Recipe: How to Migrate One (More) Module in a Future Leg

(Extracted from 8.5-8.9 patterns — keep this section updated with new learnings.)

1. **Identify**: Pick from this list. Find the legacy accion (e.g. administracion:proveedores:listado:ver) in data-patches/0004 or 0005 or via menu query after init. Note parent id for possible patch.
2. **Data (if needed)**: New patch `00XX-foo.sql` — CREATE TABLE IF NOT EXISTS (id serial pk, nombre..., activo..., other fields), INSERT demo rows ON CONFLICT DO NOTHING, INSERT INTO data_patches. Or just menu fixes / seeds for existing tables.
3. **Menu (if reparent or new children)**: Idempotent UPDATE/INSERT menu_nuevo + menu_idiomas_nuevo (use the MAX(id)+gap or high base technique from 0010). Record in patch.
4. **Code + templates**:
   - If simple catalog (id, nombre, activo): `Pages/NewName/{Listado,Formulario}.php` extending Catalog* (4-6 protected string lines), `templates/newname/{listado,formulario}.twig` (copy from tiposmejora or sedes, adapt titles/vars/flashPrefix).
   - Complex: may need custom query/map, extra fields in form, relations, or new base first.
5. **Routes (index.php)**: Add the set — modern `/admin/new-name`, `/admin/new-name/nuevo`, `/admin/new-name/editar/{id}`, POSTs, and legacy `/administracion/.../listado/ver` (and /nuevo/editar variants to match existing accions). Follow the comment blocks style from 8.8/8.9.
6. **Verification**: Extend scripts/verify-8.6.sh (php -l, new table counts or patch presence, specific SELECTs). New section in STAGE-CHECKLISTS (clean room down -v + init + psql + php -l + browser flows for create/edit + post DB assert + flashes + no regression on prior modules).
7. **Docs**: Update this TODOS (flip the item + add "done in stage 9.x, branch X, PR Y"), MIGRATION-PLAN if milestone, db-init/README patch list, any other.
8. **Size control**: Stop at 1-3 modules + 1 patch + routes + verify + 2-4 doc files. If bigger, split.

**For trees / questionnaires / PDF**: the first leg will usually be "modern shell + route + basic list or landing that still delegates heavy parts", plus plan for follow-ups.

---

## Handoff & Maintenance Notes

- **When a leg finishes**: Update the snapshot, flip items here, refresh "Suggested Next", make sure the just-completed stage checklist section points back to the items it delivered.
- **Staleness prevention**: This file is edited in *every* functional PR. If you didn't touch it, the PR is probably missing the "update the living list" step.
- **Agent handoff package**: (1) this file, (2) the reference/stage-9.x-*-plan.md of the last merged leg, (3) the corresponding section at the bottom of STAGE-CHECKLISTS.md, (4) current git tip on master after merge.
- **"I have eyeballed the code, but haven't tested (deliberate)"**: The verify script + playbook + human browser gate remain the contract. Add more invariants (like the Personalizacion menu tree checks added in 8.9) when a leg mutates shared structures.
- **Bilingual / praderas notes**: Not directly in scope here (see BLOG-POSTING.md + praderas rules for any blog articles spun out of the work).

---

**This file turns the long-term migration into an executable, daily-navigable backlog that scales to multiple agents.**

**Next leg starts by reading the "Suggested Next Legs" section and picking something that fits the "reviewable size" + "plan first" rules.**

*Created during Stage 9.0 on `feat/stage-9.0-migration-todos`. Part of the menu-driven modernization (Stage 8.x+). June 2026.*