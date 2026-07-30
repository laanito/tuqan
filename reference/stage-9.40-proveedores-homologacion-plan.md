# Stage 9.40 — Proveedores homologación first slice

**Status**: Planning (plan first)
**Branch**: `feat/stage-9.40-proveedores-homologacion`
**Driver**: Sized next after 9.39. Proveedores shell is nombre/telefono only (9.2). Legacy has fecha_homologacion/deshomologacion, productos.homologado, criterios_homologacion.

## Goals
1. Patch **0050**: extend `proveedores` (homologation dates + optional contact fields); CREATE `criterios_homologacion` + `productos`; demo seeds.
2. Proveedor list/form: homologation status + dates; quick Homologar / Deshomologar; producto count + link.
3. `Pages/Proveedores/Productos/{Listado,Formulario}` — filter/prefill by proveedor; homologado flag.
4. `Pages/Proveedores/Criterios/{Listado,Formulario}` — simple catalog (nombre, valor, activo).
5. Routes modern + verify + playbook + TODOS.

## Out
- Full criteria multi-select scoring + historico_productos workflow; contactos/incidencias.

## Sizing
- One outcome: manage supplier homologation + product homologado + criteria catalog.
- ~15–18 files, 1 patch (near ceiling; single vertical).

---
*Plan committed first. Docker-only.*
