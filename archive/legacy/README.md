# Legacy Bloat Archive — Tuqan Migration

This directory preserves large or historically significant legacy components that have been removed from the active codebase during the PHP 8 + Docker migration (see [.agents/MIGRATION-PLAN.md](../..) Stage 5).

## Purpose

- **Attack surface reduction**: FCKeditor (abandoned 2010-era editor with known vulnerabilities) and old PEAR Image/Graph libraries are no longer in the live tree.
- **Repo & Docker image size reduction**: ~30MB+ reclaimed.
- **Business continuity**: Functionality is preserved by updating the (few) call sites to point into this archive. The components remain usable until modern replacements are introduced in later stages.

## Contents (as of Stage 5)

- `fckeditor/` — Full FCKeditor 2.x distribution (was `javascript/FCKeditor/`).  
  Still required by the document editor flow (`fckeditor.php`, `editor.php`, `Procesar_*`).
- `Image/` — PEAR Image + Image_Graph + supporting libs (was `Image/`).  
  Used only by `graficamensajes.php` for legacy charting.

## Database Dumps

Large historical dumps live in `../db-dumps/` (moved from `scripts/`):
- `qnova.backup` (27MB binary)
- (optional) full SQL dumps

These are **not** mounted into the dev postgres container. They exist for manual restore / audit only.

## Future Work

- Replace FCKeditor with a modern editor (CKEditor 5, TinyMCE, or ProseMirror) in a dedicated stage.
- Remove or rewrite the Image/Graph charting dependency.
- Once the above are done, the contents of this archive can be deleted (after one last git tag).

## References

- Stage 5 checklist and evidence: `.agents/STAGE-CHECKLISTS.md`
- Migration plan: `.agents/MIGRATION-PLAN.md`
- All path updates in this stage are mechanical string changes only.

**Do not add new code that depends on anything under `archive/`.**
