#!/usr/bin/env bash
# Simple scan for hardcoded domains, URLs, and localhost assumptions
set -euo pipefail

ROOT_DIR="$(dirname "$0")/.."
cd "$ROOT_DIR"

# Patterns to search for
PATTERNS=("shule.wangombe.com" "wangombe.com" "shulelabs.cloud" "app.shulelabs.cloud" "http://" "https://")

echo "Scanning for domain and absolute URL occurrences..."
for p in "${PATTERNS[@]}"; do
  echo "\n----- Pattern: $p -----"
  # include ignored files to be thorough
  grep -RIn --color=always --exclude-dir=.git --exclude=vendor --exclude-dir=node_modules "$p" . || true
done

echo "Scan complete. Review matches and update to env-driven configuration where possible."
