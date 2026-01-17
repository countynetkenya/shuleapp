#!/usr/bin/env bash
# Generate a basic features index and per-controller feature templates
set -euo pipefail
ROOT="$(dirname "$0")/.."
cd "$ROOT"
OUT="docs/FEATURE_INDEX.md"
FEATURE_DIR="docs/features"

echo "# FEATURE INDEX" > "$OUT"

echo "Auto-generated feature list from controllers (run this script to refresh)." >> "$OUT"

echo "" >> "$OUT"

if [ -d mvc/controllers ]; then
  echo "Detected controllers:" >> "$OUT"
  for f in mvc/controllers/*.php; do
    name=$(basename "$f" .php)
    echo "- $name" >> "$OUT"
    featfile="$FEATURE_DIR/${name}.md"
    if [ ! -f "$featfile" ]; then
      cat > "$featfile" <<EOF
# Feature: $name

- Source: `mvc/controllers/$name.php`
- Purpose: TODO (describe what this controller does by reading the code)
- Routes: TODO
- DB tables: TODO
- Validation: TODO
- Edge cases: TODO

EOF
    fi
  done
else
  echo "No `mvc/controllers` directory found; skipping controller scan." >> "$OUT"
fi

echo "Feature index generation complete. Edit files under docs/features/ to document behavior." >> "$OUT"
