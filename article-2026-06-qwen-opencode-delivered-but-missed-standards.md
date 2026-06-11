# When Clear TODOs Were Not Enough: Qwen + opencode Delivered the Stage but Missed the Standards

**Series:** Tuqan Agentic Modernization Lessons  
**Date:** June 2026  
**Branch:** feat/stage-9.2-proveedores (fixed) + article/2026-06-qwen-opencode-delivered-stage-but-missed-standards  
**Related Tuqan PRs:** The 9.2 branch (initial delivery + standards fix commit), previous 9.0 (MIGRATION-TODOS), 9.1 (Criterios Ambientales hygiene)  
**Backlinks:** [.agents/MIGRATION-TODOS.md](.agents/MIGRATION-TODOS.md), [reference/stage-9.2-proveedores-plan.md](reference/stage-9.2-proveedores-plan.md), [.agents/STAGE-CHECKLISTS.md (9.2 section)](.agents/STAGE-CHECKLISTS.md), the prior Qwen/opencode mess diagnosis

---

## Hero Image Prompt (for ComfyUI / SDXL via repo scripts)

A split-screen surreal scene in a clean modern Docker-themed workshop. On the left side, a happy AI agent (glowing Qwen/opencode logo) proudly presents a working "Proveedores" module screen with list and form — the functionality is there, green checks everywhere. On the right side, the same module is shown with red warning stamps: "Form.php instead of Formulario.php", "bypassed CatalogFormulario base", "missing data patch 0020", "no legacy routes", "MIGRATION-TODOS checkbox still open", "no 9.2 playbook". In the background, a detailed wall of fine-grained TODO cards (the MIGRATION-TODOS.md) is visible, with one card checked in green ("delivered the stage") and many red "standards" violations highlighted. The mood is "almost but not quite" — technically functional but culturally off. Cinematic, detailed, bilingual labels in English and Spanish, slightly comic but serious engineering tone.

---

## English

### The Setup

After the 9.0 "make the migration plan actually usable as a daily todo list" leg and the tiny 9.1 hygiene win (Criterios Ambientales), the MIGRATION-TODOS.md was explicit:

> One real Aplicacion vertical (medium): Proveedores (listado + nuevo/editar + contacts/incidencias if table structure allows). Follow 8.6-8.8 pattern (table in patch if new, Pages/ + templates/, full routes modern+legacy, POST Procesar, flashes, verify extension, playbook, update this TODOS + checklists).

The stage-9.2-proveedores-plan.md was written first (as required). The guidelines, the catalog base from 8.9, the naming conventions, the .agents/ ritual, the verify script + playbook contract — all were documented and recent.

A new developer (or in this case, opencode + Qwen) was given the task.

### What Qwen + opencode Got Right (the Bright Side)

They delivered a working Proveedores module.

- The list and form basically worked for the main fields (nombre + telefono).
- Templates were created and looked reasonable.
- A plan document existed.
- The branch followed the naming pattern (stage-9.2-...).
- The TODO list itself "proved" the model could parse the fine-grained backlog and produce something that passed basic functionality.

In other words: **with clearer guidelines and a prioritized, scoped todo list, the local model was able to deliver the stage**.

That is non-trivial. Many previous attempts with less structure had collapsed into git chaos or total non-starters.

### What It Missed (the Standards Failure)

Despite all of the above, the initial delivery on the branch violated core project invariants:

- **Naming**: `Pages/Proveedores/Form.php` + `class Form` instead of the universal `Formulario.php` / `class Formulario` used by every other modern module (Clientes, Criterios, all the Tipos*, Sedes, Perfiles...).
- **Architecture regression**: The Form side completely bypassed `CatalogFormulario` (the whole point of 8.9) and re-implemented the full boilerplate (Twig setup, MainPage sidebar, Manejador construction, flashes, etc.). The Listado was correctly tiny; the Form side was a revert.
- **Process artifacts incomplete**:
  - No data patch for the `proveedores` table (the plan itself called for one).
  - Only modern routes in index.php; legacy `/administracion/proveedores/...` mappings missing.
  - MIGRATION-TODOS.md not updated (checkbox still open).
  - No 9.2 section in STAGE-CHECKLISTS.md with playbook, evidence commands, browser flows, or DB gates.
  - verify-8.6.sh barely touched for the new table/patch.
- **Variable and template drift**: Custom flash handling and notes that didn't perfectly match the established catalog templates.

The model produced "a Proveedores module that works" but not "a Proveedores module that belongs in this codebase in 2026."

### The Real Lesson

Clearer guidelines + fine-grained TODOs (exactly what 9.0 was for) raised the floor. Qwen could now "meet the bar" on delivering functionality where earlier unstructured attempts had failed.

But the ceiling — the accumulated taste, naming discipline, base class usage, ritual around .agents/ and verification — was still not held.

This is the classic local-model (and sometimes junior-dev) failure mode in a mature, convention-heavy project: it can read the spec and produce working code for the happy path, but it defaults to "whatever worked before" or "whatever the model was trained on most" when the conventions are implicit or spread across many small files and historical decisions.

The structured backlog made the gaps *extremely* fast to diagnose and fix for a human who already knew the standards. That is the real value of the 9.0 work.

### Implications

- For future legs: the TODO list + mandatory plan + "update the living documents" ritual is necessary but not sufficient. We still need strong human (or stronger model) review against the full body of conventions.
- For "AI-assisted developer" hiring exercises: this is an excellent filter. Give the candidate (or the model + candidate) a real next item from the living backlog, with the plan template, and see whether they ship something that could be merged without a senior spending a day on naming, base classes, and missing playbook sections.
- For the project itself: we are in a place where even a motivated local model + good scaffolding can get us 70-80% of a leg. The last 20-30% (the "this is how we do things here" part) is still where the leverage (and the risk) lives.

The TODOs did their job. The standards still need humans (for now).

---

## Español

### La configuración

Después de la pierna 9.0 ("hacer que el plan de migración sea usable como lista de tareas diaria") y la pequeña victoria de higiene 9.1 (Criterios Ambientales), el MIGRATION-TODOS.md era explícito:

> One real Aplicacion vertical (medium): Proveedores (listado + nuevo/editar + contacts/incidencias if table structure allows). Follow 8.6-8.8 pattern...

El plan stage-9.2-proveedores-plan.md se escribió primero (como exige la disciplina). Las guías, la base de catálogos de 8.9, las convenciones de nombres, el ritual de .agents/, el contrato del verify script + playbook — todo estaba documentado y reciente.

A un nuevo desarrollador (o en este caso, opencode + Qwen) se le dio la tarea.

### Lo que Qwen + opencode hizo bien (el lado positivo)

Entregó un módulo Proveedores funcional.

- El listado y el formulario básicamente funcionaban para los campos principales (nombre + teléfono).
- Se crearon las plantillas y tenían aspecto razonable.
- Existía un documento de plan.
- La rama seguía el patrón de nombres (stage-9.2-...).
- La propia lista de TODOs "demostró" que el modelo podía leer el backlog priorizado y producir algo que pasaba la funcionalidad básica.

En otras palabras: **con guías más claras y una lista de tareas priorizada y con alcance definido, el modelo local fue capaz de entregar la etapa**.

Eso no es trivial. Muchos intentos anteriores con menos estructura habían colapsado en caos de git o en nada.

### Lo que falló (el incumplimiento de estándares)

A pesar de todo lo anterior, la entrega inicial en la rama violó invariantes centrales del proyecto:

- **Nombres**: `Pages/Proveedores/Form.php` + `class Form` en lugar del universal `Formulario.php` / `class Formulario` usado por todos los demás módulos modernos (Clientes, Criterios, todos los Tipos*, Sedes, Perfiles...).
- **Regresión de arquitectura**: El lado del Form ignoró completamente `CatalogFormulario` (el propósito entero de 8.9) y reimplementó todo el boilerplate (setup de Twig, sidebar de MainPage, construcción de Manejador, flashes, etc.). El Listado estaba correctamente pequeño; el Form era una vuelta al pasado.
- **Artefactos de proceso incompletos**:
  - Sin data patch para la tabla `proveedores` (el propio plan lo pedía).
  - Solo rutas modernas en index.php; faltaban los mapeos legacy `/administracion/proveedores/...`.
  - MIGRATION-TODOS.md sin actualizar (el checkbox seguía abierto).
  - Sin sección 9.2 en STAGE-CHECKLISTS.md con playbook, comandos de evidencia, flujos de navegador ni gates de DB.
  - verify-8.6.sh apenas tocado para la nueva tabla/patch.
- **Deriva en variables y plantillas**: Manejo custom de flashes y notas que no coincidían perfectamente con las plantillas de catálogo establecidas.

El modelo produjo "un módulo de Proveedores que funciona" pero no "un módulo de Proveedores que pertenece a esta base de código en 2026".

### La lección real

Las guías más claras + los TODOs de grano fino (exactamente para lo que sirvió 9.0) elevaron el suelo. Qwen ahora podía "llegar al mínimo" en la entrega de funcionalidad donde intentos anteriores sin estructura habían fallado.

Pero el techo — el gusto acumulado, la disciplina de nombres, el uso de las clases base, el ritual alrededor de .agents/ y verificación — seguía sin sostenerse.

Este es el modo de fallo clásico de los modelos locales (y a veces de desarrolladores junior) en un proyecto maduro y lleno de convenciones: puede leer la especificación y producir código que funciona para el happy path, pero por defecto vuelve a "lo que funcionaba antes" o "lo que el modelo vio más en entrenamiento" cuando las convenciones son implícitas o están repartidas en muchos archivos pequeños y decisiones históricas.

El backlog estructurado hizo que los gaps fueran *extremadamente* rápidos de diagnosticar y arreglar para un humano que ya conocía los estándares. Ese es el verdadero valor del trabajo de 9.0.

### Implicaciones

- Para futuras piernas: la lista de TODOs + plan obligatorio + "actualizar los documentos vivos" es necesario pero no suficiente. Seguimos necesitando revisión humana (o de modelo más fuerte) contra el cuerpo completo de convenciones.
- Para ejercicios de "desarrollador asistido por IA" en contrataciones: este es un filtro excelente. Dale al candidato (o al modelo + candidato) un ítem real del backlog vivo, con la plantilla de plan, y observa si entrega algo que se pueda mergear sin que un senior tenga que pasar un día corrigiendo nombres, clases base y secciones de playbook faltantes.
- Para el proyecto mismo: estamos en un punto en el que incluso un modelo local motivado + buen andamiaje puede darnos el 70-80% de una pierna. El último 20-30% (la parte de "así es como hacemos las cosas aquí") sigue siendo donde está la palanca (y el riesgo).

Los TODOs cumplieron su función. Los estándares todavía necesitan humanos (por ahora).

---

*This article was written on a fresh branch following the praderas posting rules. Backlinks to the Tuqan 9.2 work and the .agents/ artifacts are included. The cover prompt is ready for generation via the project's ComfyUI scripts (always produce .webp + .webp.notes with the policy text).*

**Canonical tags (from project vocabulary):** agentic-development, local-models, code-review, migration-lessons, standards, qwen, opencode, tuqan-9x

(If the exact tag list has changed, update from scripts/tag_vocabulary.json before publishing.)