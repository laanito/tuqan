#!/usr/bin/env python3
"""
Extract the FULL legacy menu (menu_nuevo + menu_idiomas_nuevo) from the
historical schema dump and produce a safe, idempotent data patch.

Usage:
    python3 scripts/extract-full-menu-patch.py > docker/db-init/data-patches/0004-full-legacy-menu.sql

The resulting patch uses ON CONFLICT DO NOTHING so it is safe to re-apply.
"""

import re
import sys
from pathlib import Path

DUMP_PATH = Path("docker/db-init/00-schema-clean.sql")

def extract_menu_nuevo(content: str) -> list[dict]:
    """Robust line-based parser for the legacy dump format."""
    rows = []
    marker = "-- Data for Name: menu_nuevo; Type: TABLE DATA;"
    start = content.find(marker)
    if start == -1:
        print("ERROR: Could not find menu_nuevo data section", file=sys.stderr)
        return rows

    lines = content[start:].splitlines()
    i = 0
    current_buf = ""
    while i < len(lines):
        line = lines[i]
        if "INSERT INTO menu_nuevo" in line or (current_buf and not current_buf.rstrip().endswith(";")):
            current_buf += " " + line.strip()
            if current_buf.rstrip().endswith(";"):
                # Try to extract one VALUES tuple
                m = re.search(r"VALUES\s*\(([^)]+)\)\s*;", current_buf, re.IGNORECASE)
                if m:
                    parts = [p.strip() for p in re.split(r",\s*", m.group(1))]
                    if len(parts) >= 5:
                        try:
                            mid = int(parts[0])
                            accion = parts[1]
                            permisos = parts[2]
                            padre = parts[3]
                            orden = int(parts[4])
                            rows.append({
                                "id": mid,
                                "padre": padre,
                                "orden": orden,
                                "accion": accion,
                                "permisos": permisos,
                            })
                        except Exception:
                            pass
                current_buf = ""
        i += 1

    seen = set()
    unique = []
    for r in sorted(rows, key=lambda x: x["id"]):
        if r["id"] not in seen:
            seen.add(r["id"])
            unique.append(r)
    return unique


def extract_menu_idiomas(content: str) -> list[dict]:
    """Parse menu_idiomas_nuevo rows."""
    rows = []
    marker = "-- Data for Name: menu_idiomas_nuevo; Type: TABLE DATA;"
    start = content.find(marker)
    if start == -1:
        return rows

    block = content[start : start + 300_000]

    # Typical lines:
    # INSERT INTO menu_idiomas_nuevo VALUES (5, 'Usuarios', 32, 1);
    pattern = re.compile(
        r"INSERT INTO menu_idiomas_nuevo\s+VALUES\s*\(\s*"
        r"(\d+)\s*,\s*"                    # internal id (ignored)
        r"'((?:[^'\\]|\\.)*)'\s*,\s*"      # valor
        r"(\d+)\s*,\s*"                    # menu id
        r"(\d+)\s*"                        # idioma_id
        r"\)",
        re.IGNORECASE,
    )

    for m in pattern.finditer(block):
        valor = m.group(2).replace("''", "'")  # unescape
        menu_id = int(m.group(3))
        idioma_id = int(m.group(4))
        rows.append({
            "menu": menu_id,
            "idioma_id": idioma_id,
            "valor": valor,
        })

    # Dedup on (menu, idioma_id)
    seen = set()
    unique = []
    for r in rows:
        key = (r["menu"], r["idioma_id"])
        if key not in seen:
            seen.add(key)
            unique.append(r)
    return unique


def main():
    if not DUMP_PATH.exists():
        print(f"ERROR: Dump not found at {DUMP_PATH}", file=sys.stderr)
        sys.exit(1)

    content = DUMP_PATH.read_text(encoding="utf-8", errors="replace")

    menu_rows = extract_menu_nuevo(content)
    label_rows = extract_menu_idiomas(content)

    print("-- ============================================================================")
    print("-- 0004-full-legacy-menu.sql")
    print("-- Complete import of the real legacy menu_nuevo + menu_idiomas_nuevo")
    print("-- extracted from the historical dump for verification and renderer testing.")
    print("-- Safe for repeated application (ON CONFLICT DO NOTHING).")
    print("-- ============================================================================")
    print()

    print("-- menu_nuevo (full legacy set)")
    print("INSERT INTO menu_nuevo (id, padre, orden, accion, permisos, activo) VALUES")
    values = []
    for r in sorted(menu_rows, key=lambda x: x["id"]):
        padre = r["padre"]
        accion = r["accion"]
        # Ensure permisos is properly quoted
        permisos = r["permisos"]
        if not (permisos.startswith("'") or permisos.startswith("{")):
            permisos = f"'{permisos}'"
        values.append(f"({r['id']}, {padre}, {r['orden']}, {accion}, {permisos}, true)")
    print(",\n".join(values))
    print("ON CONFLICT (id) DO NOTHING;")
    print()

    print("-- menu_idiomas_nuevo labels")
    print("INSERT INTO menu_idiomas_nuevo (menu, idioma_id, valor) VALUES")
    label_values = []
    for r in sorted(label_rows, key=lambda x: (x["menu"], x["idioma_id"])):
        # Escape single quotes for SQL
        valor = r["valor"].replace("'", "''")
        label_values.append(f"({r['menu']}, {r['idioma_id']}, '{valor}')")
    print(",\n".join(label_values))
    print("ON CONFLICT (menu, idioma_id) DO NOTHING;")
    print()

    print(f"-- Extracted {len(menu_rows)} menu items and {len(label_rows)} labels.")
    print("-- Patch generated by scripts/extract-full-menu-patch.py")


if __name__ == "__main__":
    main()
