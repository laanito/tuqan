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

## Important Notes

- The large historical dumps (especially the 27 MB backup) are **not** loaded automatically. They live in `archive/db-dumps/` for manual restore when needed.
- This setup is intentionally minimal so we can test modernized code quickly and safely.

See `.agents/STAGE-CHECKLISTS.md` (Minimum Viable Working App section) for the current plan and evidence.