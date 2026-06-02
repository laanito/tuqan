# Database Initialization — Bare Minimum for Testing

This directory contains the controlled, minimal initialization for the Tuqan database inside Docker.

## Philosophy

- **Bare minimum only** — We apply just enough schema + data to allow login and basic navigation.
- **Incremental growth** — As we modernize specific features (risk matrix, document approval, etc.), we add only the additional reference data needed for those features.
- **Reproducible & transparent** — No magic, no huge opaque dumps loaded on every startup.

## Current Strategy (Bare Minimum Phase)

- The original historical dump (`scripts/qnovaintegraldumpvacio.sql`) is treated **as reference only**.
- We now use a small, hand-maintained minimal schema (`00-minimal-schema.sql`) containing only the tables required for login + basic main page.
- A matching minimal seed (`01-minimal-seed.sql`) provides one company + one admin user + basic menu data.
- This combination is the recommended way to get a testable environment during the modernization effort.
- The full legacy schema (`00-schema.sql`) is kept for reference/comparison only.

## Files (Active for Bare Minimum Phase)

- `00-minimal-schema.sql` — Hand-maintained minimal schema. Only the tables required for login + basic navigation.
- `01-minimal-seed.sql` — Matching minimal, idempotent seed data (one company + one admin user + essential reference data).

**All other files** in this directory or `scripts/` that reference the historical dump are for reference/comparison only and are not used in the default initialization path.

## How to Use (Developers)

**Recommended (and currently the only supported) way during the bare-minimum phase:**

```bash
docker compose --env-file .env.docker up -d
docker compose exec app ./scripts/init-db.sh
```

**Complete from-scratch reset** (use this for clean tests):

```bash
docker compose --env-file .env.docker down -v
docker volume rm tuqan_tuqan_pgdata 2>/dev/null || true
docker compose --env-file .env.docker up -d
docker compose exec app ./scripts/init-db.sh
```

This uses **only** the verified minimal files (`00-minimal-schema.sql` + `01-minimal-seed.sql`).

The automatic Postgres init mechanism is disabled (the `00-apply-schema.sh` is now a safe no-op, and the mount is removed from docker-compose.yml).

## Incremental Data Patches (Stage 8.3+)

We now support **incremental reference data** on top of the minimal base:

- New menu branches, additional companies, reference lists, etc. are added as numbered `.sql` files in `data-patches/`.
- The `init-db.sh` script tracks applied patches in the `data_patches` table.
- Patches are applied exactly once, in lexical order, and are safe to re-run.
- This lets us grow the dataset over time without ever blowing away existing developer data.

Example patch layout:
```
data-patches/
  0001-real-menu-from-legacy.sql          -- Core curated hierarchy for login + main nav
  ...
  0004-full-legacy-menu.sql               -- Full real legacy menu (loaded automatically)
  0005–0009 — menu cleanup & hierarchy fixes
  0010-menu-restructure-aplicacion-personalizacion.sql  -- Stage 8.5: Personalizacion + Empresas + Permisos move
  0011-empresas-table-and-seed.sql                      -- Supporting table for the renamed Empresas entry (historical)
  0012-rename-empresas-to-sedes.sql                     -- Stage 8.6 nitpick: table + menu actions/labels Empresas → Sedes (branches)
  ...
```

**Full legacy menu**:
The complete real legacy menu is now automatically loaded as part of normal
`init-db.sh` via `0004-full-legacy-menu.sql` (106 menu items + 212 labels).

This was added so the team can verify and improve the menu renderer + layout
with the actual production volume instead of a small curated subset.

The patch is idempotent and tracked via the `data_patches` table.

To add new data: just drop a new `00XX-*.sql` file with `ON CONFLICT DO NOTHING` (or idempotent) statements. Next `init-db.sh` will pick it up.

## Important Notes

- The large historical dumps (especially the 27 MB backup) are **not** loaded automatically. They live in `archive/db-dumps/` for manual restore when needed.
- This setup is intentionally minimal so we can test modernized code quickly and safely.

See `.agents/STAGE-CHECKLISTS.md` (Stage 8.3 / 8.5) for the current plan and evidence.

**Note on translations**: `.po` → `.mo` compilation now happens automatically on every container start (via `scripts/compile-locales.sh` + Docker entrypoint). Developers editing `.po` files no longer need to manually run `msgfmt`.