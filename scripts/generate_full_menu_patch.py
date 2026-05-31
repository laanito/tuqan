#!/usr/bin/env python3
"""
Reliable generator for the full legacy menu patch.

This version uses a careful line-by-line accumulator that matches the exact
format seen in 00-schema-clean.sql.
"""

import re
import sys
from pathlib import Path

DUMP = Path("docker/db-init/00-schema-clean.sql")
OUT = Path("docker/db-init/data-patches/0004-full-legacy-menu.sql")

def main():
    if not DUMP.exists():
        print(f"ERROR: {DUMP} not found", file=sys.stderr)
        sys.exit(1)

    content = DUMP.read_text(encoding="utf-8", errors="replace")
    lines = content.splitlines()

    menu_rows = []
    label_rows = []

    i = 0
    while i < len(lines):
        line = lines[i]

        # --- menu_nuevo ---
        if "INSERT INTO menu_nuevo" in line:
            buf = line
            j = i + 1
            # Accumulate until we see a complete statement ending with );
            while j < len(lines) and not re.search(r"\)\s*;\s*$", buf):
                buf += "\n" + lines[j]
                j += 1

            # Extract the values inside the first ( ... )
            m = re.search(r"VALUES\s*\((.+)\)\s*;", buf, re.DOTALL | re.IGNORECASE)
            if m:
                inner = m.group(1).strip()
                # Split respecting quotes (very basic but sufficient here)
                parts = []
                current = ""
                in_quote = False
                for ch in inner:
                    if ch == "'" and (not current or current[-1] != "\\"):
                        in_quote = not in_quote
                    if ch == "," and not in_quote:
                        parts.append(current.strip())
                        current = ""
                    else:
                        current += ch
                if current.strip():
                    parts.append(current.strip())

                if len(parts) >= 5:
                    try:
                        mid = int(parts[0])
                        accion = parts[1]
                        permisos = parts[2]
                        padre = parts[3]
                        orden = int(parts[4])

                        menu_rows.append({
                            "id": mid,
                            "padre": padre,
                            "orden": orden,
                            "accion": accion,
                            "permisos": permisos,
                        })
                    except Exception:
                        pass
            i = j
            continue

        # --- menu_idiomas_nuevo ---
        if "INSERT INTO menu_idiomas_nuevo VALUES" in line:
            m = re.search(
                r"VALUES\s*\(\s*(\d+)\s*,\s*'((?:[^']|'')*)'\s*,\s*(\d+)\s*,\s*(\d+)\s*\)",
                line,
                re.IGNORECASE,
            )
            if m:
                valor = m.group(2).replace("''", "'")
                label_rows.append({
                    "menu": int(m.group(3)),
                    "idioma_id": int(m.group(4)),
                    "valor": valor,
                })
        i += 1

    # Dedup
    menu_rows = sorted({r["id"]: r for r in menu_rows}.values(), key=lambda x: x["id"])
    label_rows = sorted(
        {(r["menu"], r["idioma_id"]): r for r in label_rows}.values(),
        key=lambda x: (x["menu"], x["idioma_id"]),
    )

    # Write the patch
    with OUT.open("w", encoding="utf-8") as f:
        f.write("-- ============================================================================\n")
        f.write("-- 0004-full-legacy-menu.sql\n")
        f.write("-- Full real legacy menu imported for renderer verification and module planning.\n")
        f.write("-- Generated automatically. Safe to re-apply.\n")
        f.write("-- ============================================================================\n\n")

        f.write("-- menu_nuevo (full set from legacy)\n")
        f.write("INSERT INTO menu_nuevo (id, padre, orden, accion, permisos, activo) VALUES\n")
        values = []
        for r in menu_rows:
            padre = r["padre"]
            accion = r["accion"]
            permisos = r["permisos"]
            values.append(f"({r['id']}, {padre}, {r['orden']}, {accion}, {permisos}, true)")
        f.write(",\n".join(values))
        f.write("\nON CONFLICT (id) DO NOTHING;\n\n")

        f.write("-- menu_idiomas_nuevo (labels)\n")
        f.write("INSERT INTO menu_idiomas_nuevo (menu, idioma_id, valor) VALUES\n")
        lvalues = []
        for r in label_rows:
            valor = r["valor"].replace("'", "''")
            lvalues.append(f"({r['menu']}, {r['idioma_id']}, '{valor}')")
        f.write(",\n".join(lvalues))
        f.write("\nON CONFLICT (menu, idioma_id) DO NOTHING;\n\n")

        f.write(f"-- Total: {len(menu_rows)} menu items, {len(label_rows)} labels.\n")

    print(f"Generated {OUT}")
    print(f"  menu_nuevo rows : {len(menu_rows)}")
    print(f"  labels          : {len(label_rows)}")


if __name__ == "__main__":
    main()
