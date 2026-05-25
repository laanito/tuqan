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
- Always increment `Series_Order` based on the last Tuqan post.

Current known Tuqan articles (as of May 2026):
- Phase 0 / Strategic Foundation
- Stage 1 — PHP 8 + Docker Migration Plan (PR #44)

## Hero Images

Hero images are generated with **ComfyUI + SDXL**.

Relevant tools live in the blog repo:
- `scripts/comfyui/`
- `scripts/comfyui/export_cover.py`
- Various batch scripts and JSON workflows

**For Tuqan articles**, use consistent naming:
- `tuqan-<stage>-<topic>-hero.webp`

After generating the image, place it in:
`assets/images/`

## English Version

1. Create the equivalent file in `content/en/blog/`
2. Use the same `Translation_Key`
3. Translate the content (or mark it for later translation)
4. Update `Lang: en`
5. Keep the same `Series` / `Series_Slug` / `Series_Order`

## Linking Back to Tuqan

Always include clear links to:
- The relevant GitHub PR(s)
- The Tuqan repository
- Specific `.agents/` documents when relevant (e.g. `MIGRATION-PLAN.md`, `STAGE-CHECKLISTS.md`)

Example:
> Este artículo complementa [PR #45](https://github.com/laanito/tuqan/pull/45) y la documentación en `.agents/`.

## Workflow Recommendation (for Agents)

1. Finish the Tuqan stage work + update `.agents/` docs.
2. Create the Spanish article in `content/blog/`.
3. Generate or reuse a hero image.
4. Create the English version (or stub it).
5. Update any series index pages if needed (`content/blog.md`, etc.).
6. Commit and push to the blog repo.

## Notes for Future Agents

- The blog uses **Pico CMS** (flat-file PHP).
- There is heavy investment in AI-assisted image generation and multilingual support.
- The long "Reviviendo Praderas" daily log series (`reviviendo-praderas-dia-XX-...`) is the main working log. Tuqan articles should feel like higher-level milestone posts within the broader "Tuqan — Modernización" series.

---

**Last updated:** 2026-05 (before starting Stage 2)
**Owner:** Tuqan modernization project
