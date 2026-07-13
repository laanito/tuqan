# Stage 9.26 — Documentación content editor first slice

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.26-documentacion-content-editor`
**Driver**: Suggested Next after 9.25. Explicitly "Documentación (content editor)". Previous slices covered shell (9.5), tree (9.12), editor/perfiles (9.23), workflows (9.24). The core "editor modernization" for document content (linked via contenido_texto / contenido_binario tables) remains. This is a contained first slice adding basic text content editing support to the modern Formulario.

Follows the exact ritual (plan first, Docker-only, living docs, full verify/playbook, reviewable size).

## Goals (Aligned with Directives)
1. Add first slice of content editor: support for editing basic text content (via contenido_texto table linked to documentos).
2. Enhance Documentacion/Formulario to include content field (textarea for 'contenido'), using joins or helpers to load/persist from related table.
3. Update Listado to show content excerpt or flag.
4. Update templates/documentacion/formulario.twig with textarea for content.
5. Use Catalog base (already refactored) + any relations if needed.
6. Extend verify + full "Stage 9.26 Verification Playbook".
7. Update living MIGRATION-TODOS (advance Documentación item), STAGE-CHECKLISTS.
8. 100% Docker-only, plan-first commit, clean verification.

## Scope (Reviewable slice)
**In:**
- Enhance Pages/Documentacion/Formulario.php (add content load/persist logic for contenido_texto; extend getSelectForForm if needed, or use custom helpers).
- Optionally enhance Listado for content preview.
- Update templates/documentacion/formulario.twig (add <textarea> for content).
- Small data patch if needed (e.g., 0036 for sample contenido_texto rows linked to existing docs).
- Extend scripts/verify-8.6.sh (php -l, specific content checks).
- Full 9.26 playbook in STAGE-CHECKLISTS.md.
- TODOS advance + Suggested refresh.
- This plan (first commit).

**Out (explicit future legs):**
- Full rich content editor (HTML, formatting, images).
- Binario content support.
- Integration with workflows/perfiles (e.g., approval of content changes).
- PDF export modernization.
- More in tree or other subs.

## Pattern to Follow
- Builds on 9.23/9.24 Documentación modernizations (base refactor + perfiles + workflows).
- Similar to how other rich fields were added (e.g., in Mejora).
- Keep metadata + perfiles/workflows intact.
- Content stored in linked contenido_texto (id references documentos.id, contenido TEXT).

## Data Discovery
- documentos links to contenido_texto (id, contenido) and contenido_binario.
- Current modern selects don't include content yet (only metadata + perfiles + workflow users).
- Existing docs in DB (from init) can have sample contenido added via patch.
- Use custom query or helper to join/load content.

## Verification Checklist (Non-Interactive + Human Gate)
- php -l on Documentacion/* + templates.
- After clean init: patch applied, contenido_texto rows linked.
- verify-8.6.sh green (extended).
- psql: SELECT with join shows content for docs.
- Browser: /admin/documentacion edit shows content textarea, save persists content, list shows excerpt if added; no regression on perfiles/workflows/Arbol.

## Risks & Mitigations
- Linked table: handle load/persist carefully (insert if not exists, or update).
- Scope creep: stick to basic TEXT content; rich editor later.
- No schema change needed (tables exist).

## Related / Handoff
- Delivers the "content editor" item.
- Unlocks richer Documentación (with existing perfiles/workflows).
- After: more Formación or Mejora full state, or cross-cut like PDF.

---
*Part of the menu-driven modernization (Stage 8.x+ / 9.x).*

**Execution autonomous per AGENTS.md + testing strategy in STAGE-CHECKLISTS. Plan committed first. Docker-only. If git fails: retry.**

**Plan written on the feature branch (first commit).**