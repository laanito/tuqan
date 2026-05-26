#!/usr/bin/env python3
"""
One-time script to merge legacy ALTER TABLE ADD CONSTRAINT statements
back into their CREATE TABLE definitions.

This makes the schema file much more self-contained and safer to apply
multiple times (combined with CREATE TABLE IF NOT EXISTS).

Usage:
    python3 scripts/merge_legacy_constraints.py docker/db-init/00-schema.sql > docker/db-init/00-schema-clean.sql
"""

import re
import sys
from collections import defaultdict

def main():
    if len(sys.argv) < 2:
        print("Usage: python3 merge_legacy_constraints.py <schema.sql> [output.sql]", file=sys.stderr)
        sys.exit(1)

    input_file = sys.argv[1]
    output_file = sys.argv[2] if len(sys.argv) > 2 else None

    with open(input_file, "r", encoding="utf-8") as f:
        content = f.read()

    # --- Step 1: Collect all ADD CONSTRAINT statements ---
    # We look for lines containing "ADD CONSTRAINT" and then look backwards
    # for the most recent ALTER TABLE line to determine which table it belongs to.
    # This is robust against comments or slight formatting differences.
    lines = content.splitlines(keepends=True)
    constraints_by_table = defaultdict(list)

    for idx, line in enumerate(lines):
        stripped = line.strip()
        if "ADD CONSTRAINT" in stripped.upper():
            # Look backwards for the nearest ALTER TABLE line
            for j in range(idx - 1, -1, -1):
                prev = lines[j].strip()
                if prev.upper().startswith("ALTER TABLE"):
                    match = re.search(r'(?:ONLY\s+)?public\.(\w+)', prev, re.IGNORECASE)
                    if match:
                        table = match.group(1).lower()
                        m = re.search(r'ADD CONSTRAINT\s+(\w+)\s+(.+)', stripped, re.IGNORECASE)
                        if m:
                            name = m.group(1)
                            definition = m.group(2).rstrip(';').strip()
                            constraint_line = f"    CONSTRAINT {name} {definition}"
                            constraints_by_table[table].append(constraint_line)
                    break  # stop at the first ALTER TABLE we find going backwards

    print(f"Found constraints for {len(constraints_by_table)} tables.", file=sys.stderr)

    # --- Step 2: Line-based rewrite ---
    # We process the file line by line, and when we see a CREATE TABLE block
    # we inject any collected constraints before the closing ");"
    output_lines = []
    i = 0
    n = len(lines)

    while i < n:
        line = lines[i]
        stripped = line.strip().upper()

        # Detect start of a CREATE TABLE block we care about
        if stripped.startswith("CREATE TABLE IF NOT EXISTS PUBLIC."):
            # Extract table name
            match = re.search(r"CREATE TABLE IF NOT EXISTS PUBLIC\.(\w+)", line, re.IGNORECASE)
            table_name = match.group(1).lower() if match else None

            block_lines = [line]
            i += 1

            # Collect the entire block until we see the closing );
            while i < n and ");" not in lines[i]:
                block_lines.append(lines[i])
                i += 1

            if i < n:
                block_lines.append(lines[i])  # the ");" line
                i += 1

            # Inject constraints if we have any for this table
            extra = constraints_by_table.get(table_name, [])
            if extra and table_name:
                # Insert constraints before the final ");"
                # We assume the last line of block_lines is the ");"
                closing = block_lines.pop()
                # Make sure the last column line has a trailing comma
                if block_lines:
                    last = block_lines[-1].rstrip()
                    if not last.endswith(","):
                        block_lines[-1] = last + ",\n"
                    else:
                        block_lines[-1] = last + "\n"

                for c in extra:
                    block_lines.append("    " + c + ",\n")

                # Remove trailing comma from the last constraint if needed
                if block_lines:
                    last = block_lines[-1].rstrip()
                    if last.endswith(","):
                        block_lines[-1] = last[:-1] + "\n"

                block_lines.append(closing)

            output_lines.extend(block_lines)
            continue

        # Skip old ADD CONSTRAINT lines (they will be inlined)
        if "ADD CONSTRAINT" in stripped and stripped.startswith("ALTER TABLE"):
            i += 1
            # Skip any continuation lines of this ALTER
            while i < n and not lines[i].strip().endswith(";"):
                i += 1
            if i < n:
                i += 1  # skip the ; line
            continue

        output_lines.append(line)
        i += 1

    new_content = "".join(output_lines)

    # Clean up excessive blank lines left by removals
    new_content = re.sub(r"\n{3,}", "\n\n", new_content)

    # Write output
    if output_file:
        with open(output_file, "w", encoding="utf-8") as f:
            f.write(new_content)
        print(f"Wrote cleaned schema to {output_file}", file=sys.stderr)
    else:
        sys.stdout.write(new_content)

if __name__ == "__main__":
    main()
