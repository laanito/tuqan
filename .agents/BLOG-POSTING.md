# Creating Blog Articles for Tuqan Progress

**Related Repo:** `../praderasblog` (Pico CMS site at blog.praderas.org)

This document explains how to create new articles about Tuqan modernization work so that after completing a stage (e.g., after Stage 2), an agent can generate a proper blog post.

## Article Location and Naming

### Spanish (primary)
- Path: `content/blog/`
- Naming: `tuqan-<stage-or-topic>-<short-description>.md`
  - Example: `tuqan-php8-docker-migration-plan.md`
  - Example for future: `tuqan-stage-2-testing-harness.md`

### English (translation)
- Path: `content/en/blog/`
- Use the same filename when possible, or coordinate via `Translation_Key`.

## Frontmatter Template (Spanish)

```markdown
---
Title: Tuqan — Stage 2: Arnés de pruebas y línea base PHP 8.3
Description: Resumen de lo logrado en Stage 2 de la migración: instalación de PHPUnit + PHPStan dentro de Docker, estructura de tests inicial, y los retos encontrados con dependencias legacy.
Date: 2026-05-XX HH:MMAM/PM
Template: post
Author: Luis Amigo
Tags: Desarrollo Web, Sistemas, Productividad, Tuqan
Lang: es
Translation_Key: tuqan-stage-2-testing-harness
Series: Tuqan — Modernización
Series_Slug: tuqan-modernization
Series_Order: 3
Image: /assets/images/tuqan-stage-2-hero.webp
---
```

### Key Frontmatter Fields

| Field              | Purpose                                      | Notes |
|--------------------|----------------------------------------------|-------|
| `Title`            | Article title                                | Keep it descriptive |
| `Description`      | SEO + social description                     | 1-2 sentences |
| `Date`             | Publication date + time                      | Use consistent format |
| `Lang`             | `es` or `en`                                 | Required for multilingual |
| `Translation_Key`  | Links ES ↔ EN versions                       | Must match exactly between languages |
| `Series`           | Display name of the series                   | Use `Tuqan — Modernización` |
| `Series_Slug`      | URL-friendly series identifier               | `tuqan-modernization` |
| `Series_Order`     | Position in the series                       | Increment sequentially |
| `Image`            | Hero image path                              | Must exist in `assets/images/` |

## Series Management for Tuqan

- **Series name**: `Tuqan — Modernización`
- **Series_Slug**: `tuqan-modernization`
- Always increment `Series_Order` based on the last Tuqan post (check the live series on the blog for the current highest number).

The authoritative list of Tuqan articles lives in the "Tuqan — Modernización" series on the blog itself (praderasblog). As of August 2026 the series has reached **Series_Order 17** (post-9.40 user QA plateau + P0 hotfixes). Do not rely on the static list below — it is historical.

## Hero Images

**Preferred process**: Hero images for Tuqan articles are generated with **ComfyUI + SDXL** for visual consistency with the rest of the series.

Relevant tools live in the blog repo:
- `scripts/comfyui/`
- `scripts/comfyui/export_cover.py`
- Various batch scripts and JSON workflows (see `sdxl_ubersimple.api.json` etc.)

**For Tuqan articles**, use consistent naming:
- `tuqan-<descriptive-slug>-hero.webp` (or `.png` for some earlier agentic-lessons posts)

After generating the image, place it in:
`assets/images/`

**Practical note (2026-06)**: When ComfyUI is not immediately available or for rapid iteration, the xAI image_gen tool (or equivalent) may be used to produce the initial hero. In that case, a follow-up chore commit on the article PR (or a separate small PR) should replace it with a real ComfyUI-generated WebP, exactly as was done for the "menu red herring" article (see commit 2c7b56b "add real ComfyUI hero"). Always add a `.notes` file next to the hero explaining the generation method and prompt.

## English Version

1. Create the equivalent file in `content/blog/en/` (note: the actual deployed structure uses this path, not `content/en/blog/`)
2. Use the same `Translation_Key`
3. Translate the content (or mark it for later translation)
4. Update `Lang: en`
5. Keep the same `Series` / `Series_Slug` / `Series_Order`

## Linking Back to Tuqan

**Always** include clear links to:
- The relevant GitHub PR(s) in the Tuqan repo (e.g. the 2407-line PR #60)
- The Tuqan repository
- Specific `.agents/` documents (especially `STAGE-CHECKLISTS.md` for the stage evidence and `MIGRATION-PLAN.md`)

Example (use in both language versions of the article):
> Este artículo complementa [PR #60](https://github.com/laanito/tuqan/pull/60) (rama `feat/stage-8.3-gettext-login-menu-data`) y la documentación viva en `.agents/STAGE-CHECKLISTS.md` (Stage 8.4) y `MIGRATION-PLAN.md`.

## Workflow Recommendation (for Agents)

**Mandatory order** (do not skip steps):

1. Finish the Tuqan stage/leg work.
2. **Update the relevant `.agents/` docs first** (MIGRATION-PLAN.md, STAGE-CHECKLISTS.md evidence sections, and this BLOG-POSTING.md if any conventions have drifted). Capture the self-critique, lessons, and concrete outcomes while they are fresh.
3. Create the Spanish article in `content/blog/`.
4. Generate or reuse a hero image (follow the ComfyUI preference + .notes rule above).
5. Create the English version.
6. **In both language versions of the article, add explicit links** to the relevant Tuqan `.agents/` documents (example: "Este artículo complementa la documentación en `.agents/STAGE-CHECKLISTS.md` (Stage 8.4) y `MIGRATION-PLAN.md`.").
7. Update any series index pages if needed (`content/blog.md`, etc.).
8. Commit and push to the blog repo. Open the PR with a body that references the Tuqan PR(s) and the updated `.agents/` docs.

**Recent policy (as of 2026-05)**: Do **not** include manual "Related reading" or "Artículos relacionados" sections at the end of Tuqan articles. The site has an automatic related module (see the chore commit that removed them from the menu-red-herring article, PR #52 in praderasblog).

## Notes for Future Agents

- The blog uses **Pico CMS** (flat-file PHP).
- There is heavy investment in AI-assisted image generation and multilingual support.
- The long "Reviviendo Praderas" daily log series (`reviviendo-praderas-dia-XX-...`) is the main working log. Tuqan articles should feel like higher-level milestone posts within the broader "Tuqan — Modernización" series.

---

**Last updated:** 2026-06 (after Series_Order 13 article on fully autonomous leg execution without babysitting, cron/agentic loop strategy, introspective reflection on 100% solo work; PR #57 in praderasblog)
**Owner:** Tuqan modernization project

**Note for future agents:** This document (BLOG-POSTING.md) itself must be kept in sync with reality. The article creation for the 2407-line leg (June 2026) revealed several drifts (English path, hero process notes, workflow emphasis on .agents/ updates + links). Treat any new article as an opportunity to improve this guide.

Recent example (Series_Order 13): full introspective article written after 100% autonomous execution of a leg (plan + code + verification + PR) with no human interaction during the work. The user request was high-level and could be cron/scheduler triggered. Updated this file on fresh branch after the blog PR.
