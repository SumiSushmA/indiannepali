#!/usr/bin/env bash
# Sync Laravel public/ assets into Hostinger public_html (web root).
# Usage: hostinger-sync-public.sh <laravel_root> <public_html_path>
set -euo pipefail

LARAVEL_ROOT="${1:?Laravel root path required}"
PUBLIC_HTML="${2:?public_html path required}"

if [ ! -f "$LARAVEL_ROOT/artisan" ]; then
  echo "❌ Not a Laravel root: $LARAVEL_ROOT"
  exit 1
fi

if [ ! -d "$LARAVEL_ROOT/public" ]; then
  echo "❌ Missing public directory: $LARAVEL_ROOT/public"
  exit 1
fi

mkdir -p "$PUBLIC_HTML"

echo "📂 Syncing public assets → $PUBLIC_HTML"

if command -v rsync >/dev/null 2>&1; then
  rsync -a --delete \
    --exclude 'index.php' \
    --exclude '.htaccess' \
    "$LARAVEL_ROOT/public/" "$PUBLIC_HTML/"
else
  find "$PUBLIC_HTML" -mindepth 1 -maxdepth 1 ! -name 'index.php' ! -name '.htaccess' ! -name '.user.ini' -exec rm -rf {} +
  cp -R "$LARAVEL_ROOT/public/." "$PUBLIC_HTML/"
  rm -f "$PUBLIC_HTML/index.php" "$PUBLIC_HTML/.htaccess"
fi

# Hostinger entry point + rewrite rules (keep our customized versions)
cp "$LARAVEL_ROOT/deploy/hostinger/public_html/index.php" "$PUBLIC_HTML/index.php"
cp "$LARAVEL_ROOT/deploy/hostinger/public_html/.htaccess" "$PUBLIC_HTML/.htaccess"

if [ -f "$LARAVEL_ROOT/deploy/hostinger/public_html/.user.ini" ]; then
  cp "$LARAVEL_ROOT/deploy/hostinger/public_html/.user.ini" "$PUBLIC_HTML/.user.ini"
fi

# Uploaded files: public_html/storage → laravel/storage/app/public
rm -f "$PUBLIC_HTML/storage"
ln -sfn "$LARAVEL_ROOT/storage/app/public" "$PUBLIC_HTML/storage"

echo "✅ public_html sync complete"
