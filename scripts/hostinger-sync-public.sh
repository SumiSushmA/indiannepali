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

# Remove legacy nested layout if present
rm -rf "$PUBLIC_HTML/indiannepali-main"

if command -v rsync >/dev/null 2>&1; then
  rsync -a --delete \
    --exclude 'index.php' \
    --exclude '.htaccess' \
    --exclude 'storage' \
    "$LARAVEL_ROOT/public/" "$PUBLIC_HTML/"
else
  find "$PUBLIC_HTML" -mindepth 1 -maxdepth 1 ! -name 'index.php' ! -name '.htaccess' ! -name '.user.ini' -exec rm -rf {} +
  cp -R "$LARAVEL_ROOT/public/." "$PUBLIC_HTML/"
  rm -f "$PUBLIC_HTML/index.php" "$PUBLIC_HTML/.htaccess"
fi

# Hostinger entry point + rewrite rules (keep our customized versions)
TEMPLATES="$LARAVEL_ROOT/deploy/hostinger/templates"
cp "$TEMPLATES/public_html/index.php" "$PUBLIC_HTML/index.php"
cp "$TEMPLATES/public_html/.htaccess" "$PUBLIC_HTML/.htaccess"

if [ -f "$TEMPLATES/public_html/.user.ini" ]; then
  cp "$TEMPLATES/public_html/.user.ini" "$PUBLIC_HTML/.user.ini"
fi

# Extra protection for Laravel core (useful if document root is misconfigured)
if [ -f "$TEMPLATES/indiannepali-main/.htaccess" ]; then
  cp "$TEMPLATES/indiannepali-main/.htaccess" "$LARAVEL_ROOT/.htaccess"
fi

# Uploaded files: public_html/storage → laravel/storage/app/public
rm -f "$PUBLIC_HTML/storage"
if [ "$(basename "$LARAVEL_ROOT")" = "indiannepali-main" ]; then
  ln -sfn ../indiannepali-main/storage/app/public "$PUBLIC_HTML/storage"
else
  ln -sfn "$LARAVEL_ROOT/storage/app/public" "$PUBLIC_HTML/storage"
fi

echo "✅ public_html sync complete"
