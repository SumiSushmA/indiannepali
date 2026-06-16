#!/usr/bin/env bash
# One-time / post-deploy Hostinger setup for Indian Nepali Kitchen (Laravel 12).
# Usage: hostinger-setup.sh <laravel_root> [public_html_path]
set -euo pipefail

LARAVEL_ROOT="${1:?Laravel root path required}"
PUBLIC_HTML="${2:-}"

cd "$LARAVEL_ROOT"

echo "🔧 Hostinger setup — $LARAVEL_ROOT"

if [ ! -f .env ]; then
  echo "⚠️  .env missing — copy from .env.example and configure MySQL + APP_KEY"
  if [ -f .env.example ]; then
    cp .env.example .env
    php artisan key:generate --force
  fi
fi

# Dependencies (production)
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

if command -v npm >/dev/null 2>&1; then
  npm install
  npm run build
fi

# Writable directories
chmod -R ug+rwx storage bootstrap/cache 2>/dev/null || true

# Laravel storage link (laravel/public/storage)
php artisan storage:link 2>/dev/null || true

php artisan migrate --force

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optional: split public_html layout (shared hosting)
if [ -n "$PUBLIC_HTML" ]; then
  bash "$LARAVEL_ROOT/scripts/hostinger-sync-public.sh" "$LARAVEL_ROOT" "$PUBLIC_HTML"
fi

php artisan queue:restart 2>/dev/null || true

echo "✅ Hostinger setup complete"
