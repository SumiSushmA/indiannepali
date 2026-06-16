#!/usr/bin/env bash
# Build hostinger-deploy/ from the Laravel project (original repo stays untouched).
# Re-run anytime after code or asset changes.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
DEPLOY="$ROOT/hostinger-deploy"
PUBLIC_HTML="$DEPLOY/public_html"
LARAVEL="$PUBLIC_HTML/indiannepali-main"
TEMPLATES="$ROOT/deploy/hostinger/templates"

echo "Building Hostinger deployment package..."

mkdir -p "$PUBLIC_HTML" "$LARAVEL"

# Laravel core (everything except public/, dev-only dirs, and deploy output)
rsync -a --delete \
  --exclude 'public/' \
  --exclude 'node_modules/' \
  --exclude '.git/' \
  --exclude 'deploy/' \
  --exclude 'hostinger-deploy/' \
  --exclude 'prototype/' \
  --exclude 'scripts/' \
  --exclude '.DS_Store' \
  --exclude 'tests/' \
  "$ROOT/" "$LARAVEL/"

# Public assets → public_html (not the original index.php or .htaccess)
rsync -a --delete \
  --exclude 'index.php' \
  --exclude '.htaccess' \
  --exclude 'storage' \
  "$ROOT/public/" "$PUBLIC_HTML/"

# Custom entry point, rewrite rules, and core protection
cp "$TEMPLATES/public_html/index.php" "$PUBLIC_HTML/index.php"
cp "$TEMPLATES/public_html/.htaccess" "$PUBLIC_HTML/.htaccess"
cp "$TEMPLATES/public_html/.user.ini" "$PUBLIC_HTML/.user.ini"
cp "$TEMPLATES/indiannepali-main/.htaccess" "$LARAVEL/.htaccess"

# Uploaded files served at /storage/*
rm -f "$PUBLIC_HTML/storage"
ln -sfn indiannepali-main/storage/app/public "$PUBLIC_HTML/storage"

echo "✅ Done: $DEPLOY"
echo "   See hostinger-deploy/HOSTINGER_DEPLOYMENT_REPORT.md"
