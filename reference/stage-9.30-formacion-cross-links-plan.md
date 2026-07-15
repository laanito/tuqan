# Stage 9.30 — Formación cross-links hub (Planes ↔ Cursos ↔ Inscripciones)

**Status**: Planning phase (plan committed first)
**Branch target**: `feat/stage-9.30-formacion-cross-links`
**Driver**: Suggested Next after 9.29 — switch verticals (Mejora streak ended). Formación has Planes (9.5), Cursos (9.20), Inscripciones (9.22) as isolated catalogs; missing bidirectional nav/filters/prefill (same pattern as Mejora↔Auditorías 9.29). Also fix nested list item keys so Cursos/Inscripciones templates actually receive rows.

**On the flight (light ritual)**: tighten PR sizing note in MIGRATION-TODOS (target one clickable outcome, hard stop second vertical, Suggested Next template line).

## Goals
1. Planes list: course count + link to filtered cursos; “+ Curso” prefilled.
2. Cursos list: filter by plan; plan link; inscription count + link; “+ Inscripción” / “+ Curso” with prefills.
3. Inscripciones list: filter by curso; curso link; filter UI.
4. Prefill forms: `?plan=` on curso nuevo; `?curso=` on inscripción nuevo.
5. Fix list variable keys for nested `formacion/cursos` and `formacion/inscripciones` (expose `curso` / `inscripcion`).
6. Optional tiny patch 0040 if demo links need polish (usually already good).
7. verify + playbook + TODOS (+ sizing hygiene).

## Scope
**In:** Pages/Formacion/{Listado,Cursos/*,Inscripciones/*}, templates/formacion/**, CatalogListado getFilterParams plan+curso, docs, verify.
**Out:** New Formación entities (ficha personal, requisitos puesto), Excel, asistentes workflows, Documentación.

## Sizing (ritual)
- One product outcome: “navigate Formación plan → courses → inscriptions with filters and prefilled create”.
- ~10–18 files, 0–1 patch. No second vertical.

## Verification
- php -l, init if patch, verify asserts (counts by plan/curso), browser round-trip.

## Handoff
- Next: Documentación rich editor / estado, Auditorías hallazgos, Equipos revisiones, etc.

---
*Part of Stage 8.x+/9.x modernization. Plan committed first. Docker-only.*
