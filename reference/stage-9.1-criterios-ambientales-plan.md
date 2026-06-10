# Stage 9.1 Plan — Criterios Ambientales (Close 8.9 "NOTE FOR LATER" + First Item from Daily TODOs)

**Status**: Planning phase
**Branch target**: `feat/stage-9.1-criterios-ambientales` (fresh from master)
**Driver**: The very first "Suggested Next Legs" item in the new daily-navigable `.agents/MIGRATION-TODOS.md` (XS hygiene). This also directly closes the explicit open thread left in the 0018 data patch and the full 8.9 retrospective in STAGE-CHECKLISTS: row 84 ("Criterios" section with no accion) should have been "Criterios Ambientales" with a proper action, placed as a direct child of Personalizacion (padre=1400). Although the 9.0 TODOS PR is created but not yet merged, we have the context and the user's prior note ("they are 'Criterios Ambientales'"). Small, focused, reviewable change that demonstrates using the new list for leg selection. Keeps the "size of PRs we want".

## Goals (Aligned with Directives)
1. Deliver the menu label "Criterios Ambientales" (with working accion) as a direct actionable child under Personalizacion (1400).
2. Make the modern Criterios pages and templates consistent with the new label ("Criterios Ambientales" / "Criterio Ambiental").
3. Mark the NOTE FOR LATER in 0018 as implemented (via 0019).
4. Strengthen the existing Personalizacion menu invariants in verify-8.6.sh with a specific assert for this case.
5. Full .agents/ discipline: plan first (this file), new 9.1 playbook section in STAGE-CHECKLISTS, update MIGRATION-PLAN, extend verify.
6. All work Docker-only, reproducible via init-db.sh + psql asserts.
7. Note the open 9.0 PR so that once it merges the checkbox in MIGRATION-TODOS.md can be flipped.

## Scope (Deliberately XS — First Post-9.0 Hygiene Leg)
- New data patch: `docker/db-init/data-patches/0019-criterios-ambientales.sql`
  - Idempotent DO block that locates the current Criterios actionable row under 1400 (by accion LIKE '%criterios%listado%' / '%criterios%ver%' or current Spanish label), then:
    - UPDATE menu_idiomas_nuevo for idioma_id=1 to 'Criterios Ambientales'.
    - INSERT/UPDATE English label 'Environmental Criteria' (or 'Ambiental Criteria').
    - Guard/ensure the row has a sensible accion (the one added in prior stages for /administracion/criterios/listado/ver).
  - INSERT INTO data_patches for '0019-...sql'.
- Edit the 0018 patch file: replace the "NOTE FOR LATER" block with a short "Implemented in Stage 9.1 via 0019" comment (keep history).
- Tiny polish on the module for label consistency (still tiny catalog-base extends):
  - `Pages/Criterios/Listado.php`: `$title = 'Criterios Ambientales';`
  - `Pages/Criterios/Formulario.php`: `$title = 'Criterio Ambiental';`
  - `templates/criterios/listado.twig` (and formulario if it has parallel strings): update the `<h2>`, "Nuevo ..." button text, `{% block title %}`, and the bottom stage note.
- Extend `scripts/verify-8.6.sh`:
  - The php -l line already covers Criterios (good).
  - In the Personalizacion menu invariants psql block (or a follow-up query): assert a row under padre=1400 whose Spanish label is 'Criterios Ambientales' (or contains it) and has non-empty accion. Update header comment and patch-tracking comment.
- Docs:
  - New short plan (this file) — committed first.
  - Full "Stage 9.1 Verification Playbook" section in `.agents/STAGE-CHECKLISTS.md` (modeled on 9.0/8.9: goal, scope, commands, clean-room steps, psql before/after the label, browser flow: login → Personalizacion → see the exact label and click it → create/edit a criterio → post-submit DB assert on the row + flash).
  - Update `.agents/MIGRATION-PLAN.md` (last updated + short note referencing the open 9.0 PR and that this closes the first TODOS item).
- Out of scope (to keep XS): new tables, new routes (already present from 8.6), behavior changes, full verticals, touching the 9.0 TODOS file itself (it lives on the unmerged PR branch).

**Expected size**: 1 patch + 2 tiny PHP files + 1-2 twig strings + verify extension + 0018 note edit + plan + 1 new checklists section + 1 MIGRATION-PLAN edit. Very small reviewable delta, perfect "next leg" after the meta 9.0 list work.

## Current Reality (Post 8.9 on master)
- 0017 reparented the actionable Criterios (and other) items directly to 1400.
- 0018 deleted only the old empty section headers (including the no-accion row 84).
- The 0018 NOTE and 8.9 retrospective explicitly call out that the real desire was "Criterios Ambientales" with a proper action as direct child.
- Modern routes + Pages/Criterios (catalog base) + templates already exist and are wired (legacy + /admin/criterios).
- The current Spanish label on the actionable row is still the old "Criterios".
- verify-8.6.sh already has good Personalizacion subtree asserts (direct children count, tree dump, orphan empty-section count) added in 8.9.
- 9.0 PR (the MIGRATION-TODOS list) is open on GitHub but not merged, so master does not yet contain the list file. This leg proceeds from master per "fresh branch from master" and will conceptually deliver the top suggested item; the checkbox flip happens after 9.0 lands.
- All prior catalog work (including Criterios) is on master.

## Detailed Tasks / Execution Order (Strict)
1. Write this plan document. Commit as the *first* thing on the new branch.
2. Create the 0019 patch (idempotent label fix + data_patches row). Edit 0018 to close the NOTE.
3. Update the two Criterios PHP files (titles) + the relevant template strings (listado.twig primarily; formulario if symmetric).
4. Extend verify-8.6.sh (invariants + comments).
5. Add the complete 9.1 playbook section to STAGE-CHECKLISTS.md.
6. Update MIGRATION-PLAN.md.
7. Full verification inside Docker:
   - `docker compose --env-file .env.docker down -v && docker compose --env-file .env.docker up -d`
   - `docker compose exec app ./scripts/init-db.sh`
   - Targeted psql: confirm the exact label 'Criterios Ambientales' under padre=1400, non-empty accion, patch 0019 recorded, no orphan empty sections.
   - php -l on changed files.
   - Run `./scripts/verify-8.6.sh` (the Personalizacion block must pass the new specific check).
   - (Browser/manual per playbook): after login, navigate to Personalizacion, see "Criterios Ambientales" as direct item, click it, create + edit a record, observe flash, assert DB side-effect.
8. Commit the implementation work, push -u, open PR. Reference the open 9.0 PR in the description.

## Success Criteria
- After clean init-db.sh: `psql ... SELECT ... FROM menu_nuevo m JOIN menu_idiomas_nuevo mi ... WHERE padre=1400 AND mi.valor = 'Criterios Ambientales';` returns the row, and its `accion` is non-empty and matches the known criterios route.
- The English label is also present.
- `data_patches` contains the 0019 filename.
- Page header in /admin/criterios (and the form) uses the Ambientales wording.
- verify-8.6.sh (with the extension) passes, including the new specific menu assert.
- 0018 patch file no longer has an open "NOTE FOR LATER" for this item.
- Full 9.1 section with reproducible commands + evidence in STAGE-CHECKLISTS.
- MIGRATION-PLAN updated.
- Clean, small PR on the new branch; Docker-only; plan was first commit.
- Once the 9.0 PR merges, the corresponding item in MIGRATION-TODOS.md can be checked off with a note pointing to this PR.

## Risks & Mitigations
- The exact current row for Criterios under 1400 might have a slightly different label/accion after all the 001x patches: the patch uses LIKE on accion (proven in 0017) + fallback on label, plus safe UPDATE only. If nothing matches we log/insert defensively (but history says it should exist).
- Template hardcodes: we touch the obvious strings in listado.twig (and will check formulario). Stage note at bottom can stay or be lightly refreshed.
- 9.0 not merged yet: we note it explicitly in the plan, the 9.1 playbook, and MIGRATION-PLAN. No dependency on the TODOS file contents for this change.
- Duplicate labels: the existing 8.9 invariants (orphan empty + tree dump) plus the new specific check will surface problems immediately.

## Related
- Directly implements the #1 item from the just-created daily TODO list (even pre-merge).
- Closes the exact open thread the user called out during 8.9 menu work and the retrospective ("your tests did not detect... take note for later").
- Keeps momentum with a tiny, high-signal hygiene leg before moving to larger verticals (Proveedores, Equipos, Mejora, Documentación, etc.).
- Reinforces the new process: consult the living list, plan first, small+reviewable, update .agents/ + verify, push.

---

*Part of the ongoing menu-driven modernization (Stage 8.x+ / 9.x). Uses the daily MIGRATION-TODOS navigation introduced in 9.0.*

**Execution will be autonomous per AGENTS.md and the testing strategy. Plan committed first on the feature branch. Only circle back on blockers.**

**Plan written on the feature branch (first commit).**