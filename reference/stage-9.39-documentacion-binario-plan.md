# Stage 9.39 — Documentación content / binario first slice

**Status**: Implemented (verify + PR)
**Branch**: `feat/stage-9.39-documentacion-binario`
**Driver**: Sized next after 9.38. Text content exists (9.26); legacy `contenido_binario` + `tipos_fichero` + muestrabinario not modernized. Tables missing from minimal DB.

## Goals
1. Patch **0049**: CREATE `tipos_fichero` + `contenido_binario` (+ PK); seed mime types; demo binario for doc 1 (BYTEA, not large-object OID).
2. Document form: multipart upload; show binario metadata (tipo, size); remove attachment option.
3. Download route `/admin/documentacion/binario/{id}` (Content-Type from tipos_fichero).
4. Listado: flag if documento has binario; download shortcut.
5. Slight content UX polish (taller textarea + char hint) — not full WYSIWYG.
6. verify + playbook + TODOS.

## Out
- GenPDF, large-object OID pipeline, full FCKeditor/WYSIWYG, multi-adjunto (contenido_adjunto).

## Sizing
- One outcome: attach/download binary file on a document + see it in list.
- ~12–15 files, 1 patch.

---
*Plan committed first. Docker-only.*
