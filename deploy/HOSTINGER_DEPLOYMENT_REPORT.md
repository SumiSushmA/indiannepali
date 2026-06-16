# Hostinger Deployment Report — Indian Nepali Kitchen (Laravel 12)

This document describes how to deploy this Laravel application on **Hostinger shared hosting** safely, without exposing `app/`, `config/`, `.env`, or `vendor/` to the web.

---

## 1. Framework compatibility

| Item | Value |
|------|--------|
| Laravel | **12.x** |
| PHP required | **8.2+** |
| Extensions | `mbstring`, `pdo_mysql`, `bcmath`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo` |
| Node (build only) | 20+ for `npm run build` |

Set PHP version to **8.2 or 8.3** in hPanel → **Advanced → PHP Configuration**.

---

## 2. Original structure (development / git repo)

```
indiannepali/
├── app/
├── bootstrap/
├── config/
├── database/
├── deploy/                 ← Hostinger templates (added)
├── public/                 ← Laravel web root (standard)
│   ├── index.php
│   ├── .htaccess
│   ├── css/
│   ├── js/
│   ├── build/              ← Vite output (after npm run build)
│   └── ...
├── resources/
├── routes/
├── storage/
├── tests/
├── vendor/
├── artisan
├── composer.json
└── .env                    ← NOT in git; per-server only
```

---

## 3. Recommended production structure on Hostinger

### Option A — Best (if hPanel allows custom document root)

Point the domain document root to:

```
/home/u123456789/indiannepali/public
```

**No file moves required.** Use the existing `public/index.php` unchanged.

```
/home/u123456789/
└── indiannepali/              ← entire repo (private except public/)
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── ...
    └── public/                ← document root points HERE
        ├── index.php          ← standard Laravel (unchanged)
        └── ...
```

### Option B — Shared hosting (document root fixed to `public_html`)

Keep Laravel **outside** the web root; only `public/` assets + custom `index.php` go in `public_html`.

```
/home/u123456789/
├── indiannepali/              ← Laravel app (NOT web-accessible)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── deploy/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── artisan
│   └── .env
└── domains/staging.example.com/
    ├── laravel/               ← OR symlink: laravel → ../../indiannepali
    └── public_html/           ← WEB ROOT (only public files)
        ├── index.php          ← deploy/hostinger/public_html/index.php
        ├── .htaccess
        ├── .user.ini
        ├── css/
        ├── js/
        ├── build/
        ├── logo.png
        └── storage → symlink to ../../indiannepali/storage/app/public
```

**Alternative sibling layout:**

```
domains/example.com/
├── laravel/                   ← full app (git clone here)
└── public_html/               ← synced from public/ + custom index.php
```

---

## 4. Files moved (Option B only)

| From (repo) | To (server) |
|-------------|-------------|
| `public/css/*` | `public_html/css/` |
| `public/js/*` | `public_html/js/` |
| `public/build/*` | `public_html/build/` |
| `public/logo.png`, `public/*.svg`, etc. | `public_html/` |
| `deploy/hostinger/public_html/index.php` | `public_html/index.php` |
| `deploy/hostinger/public_html/.htaccess` | `public_html/.htaccess` |
| `deploy/hostinger/public_html/.user.ini` | `public_html/.user.ini` |

**Not moved (stay outside public_html):** `app/`, `bootstrap/`, `config/`, `database/`, `resources/`, `routes/`, `storage/`, `vendor/`, `artisan`, `.env`

---

## 5. Files modified / created in this repo

| File | Purpose |
|------|---------|
| `deploy/hostinger/public_html/index.php` | Bootstrap Laravel from outside `public_html` |
| `deploy/hostinger/public_html/.htaccess` | URL rewriting + block dotfiles |
| `deploy/hostinger/public_html/.user.ini` | PHP limits for Hostinger |
| `scripts/hostinger-sync-public.sh` | Sync `public/` → `public_html/` |
| `scripts/hostinger-setup.sh` | Post-deploy commands (composer, migrate, cache, permissions) |
| `deploy/HOSTINGER_DEPLOYMENT_REPORT.md` | This report |

**Unchanged:** `public/index.php` (still used for Option A and local dev).

---

## 6. Updated `index.php` (Option B — public_html)

Used when Laravel lives **outside** `public_html`. Auto-detects app root:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$laravelRoot = null;

foreach ([
    dirname(__DIR__).'/laravel',
    dirname(__DIR__, 2).'/indiannepali',
    dirname(__DIR__),
] as $candidate) {
    if (is_file($candidate.'/artisan')) {
        $laravelRoot = realpath($candidate) ?: $candidate;
        break;
    }
}

if ($laravelRoot === null) {
    http_response_code(500);
    exit('Laravel root not found.');
}

if (file_exists($maintenance = $laravelRoot.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $laravelRoot.'/vendor/autoload.php';

$app = require_once $laravelRoot.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

**Standard `public/index.php` (Option A)** — unchanged:

```php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

---

## 7. Hardcoded paths audit

| Location | Path style | Safe after deploy? |
|----------|------------|-------------------|
| `public/index.php` | `__DIR__.'/../vendor'` | ✅ Option A |
| `deploy/hostinger/public_html/index.php` | Resolves Laravel root dynamically | ✅ Option B |
| Blade views | `/css/...`, `/js/...`, `asset('storage/...')` | ✅ Served from web root |
| `config/filesystems.php` | `storage_path()`, `public_path()` | ✅ Laravel helpers |
| `bootstrap/app.php` | `dirname(__DIR__)` | ✅ |
| `.env` | `DB_*`, `APP_URL` | ✅ Per-server file |

**No absolute Mac/Windows paths found.** No code changes required for assets.

**Note:** Uploaded images use `asset('storage/...')`. On Option B, run sync script so `public_html/storage` symlinks to `storage/app/public`.

---

## 8. Permissions (fix many HTTP 500 errors)

```bash
cd /path/to/laravel
chmod -R ug+rwx storage bootstrap/cache
chown -R u123456789:u123456789 storage bootstrap/cache   # use your Hostinger user
```

---

## 9. Hostinger hPanel settings

| Setting | Value |
|---------|--------|
| PHP version | 8.2 or 8.3 |
| Document root | `.../public` (Option A) **or** `public_html` (Option B) |
| MySQL database | Create in Databases → MySQL |
| SSL | Enable free SSL for domain |
| SSH | Enable if using GitHub Actions deploy |
| Cron (optional) | `* * * * * cd /path/to/laravel && php artisan schedule:run` |

### Server `.env` (MySQL — not SQLite)

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456789_indiannepali
DB_USERNAME=u123456789_dbuser
DB_PASSWORD=your_hpanel_password
```

---

## 10. Commands to run on server

### First-time setup

```bash
cd ~/indiannepali   # or domains/example.com/laravel

cp .env.example .env
nano .env           # set MySQL + APP_URL + mail + Toast keys
php artisan key:generate

bash scripts/hostinger-setup.sh ~/indiannepali ~/domains/staging.example.com/public_html
```

### Option A only (document root = public)

```bash
bash scripts/hostinger-setup.sh ~/indiannepali
```

### Every deploy (manual or CI/CD)

```bash
cd ~/indiannepali
git pull origin dev    # or main
composer install --no-dev --optimize-autoloader --no-interaction
npm install && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart

# Option B only:
bash scripts/hostinger-sync-public.sh ~/indiannepali ~/domains/staging.example.com/public_html
```

---

## 11. HTTP 500 — common causes & fixes

| Cause | Symptom | Fix |
|-------|---------|-----|
| Missing `.env` | 500 immediately | `cp .env.example .env` + `php artisan key:generate` |
| Wrong `APP_KEY` | 500 / encryption errors | `php artisan key:generate` on that server |
| SQLite on server | DB errors | Set `DB_CONNECTION=mysql` + hPanel credentials |
| `vendor/` missing | Class not found | `composer install --no-dev` |
| Storage not writable | 500 on login/upload | `chmod -R ug+rwx storage bootstrap/cache` |
| Cached config with wrong URL | Wrong redirects | `php artisan config:clear` then `config:cache` |
| `public/build` missing | Broken Vite assets | `npm run build` |
| Wrong `index.php` paths | 500 on every request | Use correct Option A or B index.php |
| `storage` symlink missing | 404 on uploaded images | `php artisan storage:link` + sync script for Option B |
| PHP &lt; 8.2 | 500 / parse errors | Set PHP 8.2+ in hPanel |
| Document root points to project root | Exposes `.env` / 500 | Point to `public/` or use Option B |

**Enable temporary debug (staging only):**

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

Then check `storage/logs/laravel.log`. Set `APP_DEBUG=false` before production.

---

## 12. Asset resolution after restructure

This app serves static files from the web root:

- `/css/theme.css`, `/js/customer.js`, `/logo.png` → files in `public/` (copied to `public_html/` on Option B)
- `/build/*` → created by `npm run build`
- `/storage/*` → symlink to `storage/app/public` (uploads)

As long as `public_html` contains synced `public/` files and the `storage` symlink, all assets resolve correctly.

---

## 13. GitHub Actions integration

Current workflows deploy the full repo to `DEV_APP_PATH` / `PROD_APP_PATH`.

**If using Option A:** set `DEV_APP_PATH` to the Laravel root; ensure hPanel document root is `{APP_PATH}/public`.

**If using Option B:** after deploy SSH block, add:

```bash
bash scripts/hostinger-sync-public.sh "$APP_PATH" "/home/u123456789/domains/staging.example.com/public_html"
```

Store `public_html` path as a GitHub secret (e.g. `DEV_PUBLIC_HTML_PATH`).

---

## 14. Quick decision guide

```
Can you set document root to .../public in hPanel?
├── YES → Option A (easiest, standard Laravel)
└── NO  → Option B (public_html + scripts in this repo)
```

---

## 15. Summary

| | Option A | Option B |
|---|--------|--------|
| Laravel root | `~/indiannepali` | `~/indiannepali` or `domains/.../laravel` |
| Web root | `~/indiannepali/public` | `domains/.../public_html` |
| index.php | `public/index.php` (default) | `deploy/hostinger/public_html/index.php` |
| Extra sync | Not needed | `scripts/hostinger-sync-public.sh` |
| Security | ✅ Core dirs not web-accessible | ✅ Core dirs not web-accessible |

No application files were deleted. Deployment templates and scripts were added under `deploy/` and `scripts/`.
