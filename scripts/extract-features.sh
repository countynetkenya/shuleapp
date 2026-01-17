#!/usr/bin/env bash
# Lightweight scanner that extracts routes and PHPUnit testdox to tmp/
# Usage: ./scripts/extract-features.sh tmp
set -euo pipefail
OUT_DIR="${1:-tmp}"
mkdir -p "$OUT_DIR"

# Try Laravel routes
if command -v php >/dev/null 2>&1 && php artisan --version >/dev/null 2>&1; then
  echo "Collecting Laravel routes..."
  php artisan route:list --json > "$OUT_DIR/routes.json" 2>/dev/null || php artisan route:list > "$OUT_DIR/routes.txt" || true
else
  echo "php artisan not available; searching for routes/web.php..."
  if [ -f routes/web.php ]; then
    cp routes/web.php "$OUT_DIR/routes-web.php"
  fi
fi

# Try PHPUnit testdox
if [ -f vendor/bin/phpunit ]; then
  vendor/bin/phpunit --testdox --colors=never > "$OUT_DIR/testdox.txt" 2>/dev/null || true
elif command -v phpunit >/dev/null 2>&1; then
  phpunit --testdox --colors=never > "$OUT_DIR/testdox.txt" 2>/dev/null || true
else
  echo "PHPUnit not found; skipping tests extraction"
fi

# Basic README / changelog
if [ -f README.md ]; then
  cp README.md "$OUT_DIR/README.md"
fi

echo "Scanner outputs written to $OUT_DIR"
