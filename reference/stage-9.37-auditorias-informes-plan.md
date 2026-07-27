# Stage 9.37 — Auditorías informes first slice

**Status**: Implemented (verify + PR)
**Branch**: `feat/stage-9.37-auditorias-informes`
**Driver**: Sized next after 9.36 (diversify from Equipos). Legacy `auditorias:informeauditoria` edits lugar/fecha/conclusiones/recomendaciones on `auditorias` and supports print. Modern ejecución already has lugar_informe + fecha_informe; conclusions/recommendations missing from 0031.

## Goals
1. Patch **0047**: ADD `recomendaciones_informe` + `conclusiones_informe` if missing; demo text on seed auditoría.
2. `Pages/Auditorias/Informe/{Formulario,Ficha}` — edit informe fields; printable HTML ficha (header + body + hallazgos + mejoras; no GenPDF).
3. Routes: `/admin/auditorias/informes/editar/{id}`, POST same, `/admin/auditorias/informes/ver/{id}`; legacy path optional.
4. Links from ejecución list + form (+ Informe / Ver ficha).
5. verify + playbook + TODOS.

## Out
- GenPDF binary export, full formal layout, email, multi-format templates.

## Sizing
- One outcome: edit and view/print audit informe.
- ~12–15 files, 1 patch.

---
*Plan committed first. Docker-only.*
