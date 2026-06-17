# Hostinger Deployment Report — Indian Nepali Kitchen

Generated for the **`hostinger-deploy/`** package. The original project at the repo root is **unchanged**; this folder is a copy ready to upload.

**Last updated:** 2026-06-17

---

## 1. Final folder structure

```
hostinger-deploy/
└── public_html/                          ← upload contents to Hostinger public_html
    ├── index.php                         ← Laravel 12 bootstrap (loads indiannepali-main/)
    ├── .htaccess                         ← URL rewrite to index.php
    ├── .user.ini                         ← PHP limits for Hostinger
    ├── css/                              ← from public/css/
    ├── js/                               ← from public/js/
    ├── build/                            ← Vite build output (from public/build/)
    ├── logo.png
    ├── favicon.ico
    ├── robots.txt
    ├── sw.js
    ├── Group 1171275134.svg
    ├── storage → indiannepali-main/storage/app/public   ← symlink (uploaded files)
    └── indiannepali-main/                ← Laravel app (HTTP access denied)
        ├── .htaccess                     ← Require all denied
        ├── app/
        ├── bootstrap/
        │   └── app.php                   ← required
        ├── config/
        ├── database/
        ├── resources/
        ├── routes/
        ├── storage/
        ├── vendor/
        │   └── autoload.php              ← required
        ├── artisan
        ├── composer.json
        └── .env                          ← configure on server
```

There is **no** separate `images/` folder in this project. Images live at the web root (`logo.png`, SVG) or under `/storage/` (uploads).

---

## 2. Files copied

### Into `public_html/` (from `public/`, excluding `index.php` and `.htaccess`)

| Path | Notes |
|------|--------|
| `css/*` | All customer/admin styles |
| `js/*` | All front-end scripts |
| `build/` | Vite compiled assets |
| `logo.png`, `favicon.ico`, `robots.txt`, `sw.js` | Static assets |
| `Group 1171275134.svg` | Footer graphic |

### Into `public_html/indiannepali-main/` (Laravel root, excluding `public/`)

| Path | Notes |
|------|--------|
| `app/`, `bootstrap/`, `config/`, `database/`, `resources/`, `routes/`, `storage/` | Core Laravel |
| `vendor/` | Composer dependencies (**present** in this build) |
| `artisan`, `composer.json`, `composer.lock` | Laravel tooling |
| `.env`, `.env.example` | Environment (edit `.env` on server) |

**Excluded from copy:** `public/`, `node_modules/`, `.git/`, `deploy/`, `hostinger-deploy/`, `prototype/`, `scripts/`, `tests/`

---

## 3. Files modified / created

| File | Action |
|------|--------|
| `public_html/index.php` | **Created/fixed** — Laravel 12 bootstrap with `$appPath = __DIR__ . '/indiannepali-main'` |
| `public_html/.htaccess` | **Created** — rewrite non-file requests to `index.php` |
| `public_html/.user.ini` | **Created** — PHP upload/memory limits |
| `public_html/indiannepali-main/.htaccess` | **Created** — deny all direct HTTP access |
| `public_html/storage` | **Symlink** → `indiannepali-main/storage/app/public` |
| `scripts/build-hostinger-deploy.sh` | **Fixed** — public sync no longer deletes `indiannepali-main/` |
| `deploy/hostinger/templates/*` | **Updated** — canonical Hostinger entry files |

**Note:** `dex.php` was not found in this build; `index.php` is the correct Hostinger entry point.

No controllers, routes, models, or Blade templates were changed.

---

## 4. `public_html/index.php`

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$appPath = __DIR__ . '/indiannepali-main';

if (file_exists($maintenance = $appPath . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $appPath . '/vendor/autoload.php';

/** @var Application $app */
$app = require_once $appPath . '/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

Folder name **`indiannepali-main`** is case-sensitive on Linux/Hostinger.

---

## 5. HTTP 500 checklist

| Cause | Status in this build |
|-------|----------------------|
| Wrong folder name in `index.php` | ✅ Uses `indiannepali-main` |
| Missing `vendor/` | ✅ Present (~80 MB) |
| Missing `.env` on server | ⚠️ Copy/edit on server (local `.env` included in package) |
| PHP version < 8.2 | ⚠️ Set PHP 8.2+ in hPanel |
| Wrong `index.php` path | ✅ Fixed |
| `storage/` not writable | ⚠️ Set permissions on server (see below) |
| Broken `.htaccess` | ✅ Present |
| Missing public assets | ✅ Synced from `public/` |

---

## 6. Hostinger upload instructions

### Option A — Upload the pre-built package (recommended)

1. On your Mac, zip the **contents** of `hostinger-deploy/public_html/` (not the `hostinger-deploy` folder itself).
2. In hPanel → **Files** → open your domain’s `public_html/`.
3. **Back up** existing files if the site is already live.
4. Upload the zip and **Extract** so `index.php` sits directly in `public_html/` (not `public_html/public_html/`).
5. Confirm this layout on the server:

```
public_html/
├── index.php
├── .htaccess
├── build/
├── css/
├── js/
├── favicon.ico
├── logo.png
└── indiannepali-main/
    ├── app/
    ├── bootstrap/
    ├── vendor/
    ├── .env
    └── ...
```

### Option B — Rebuild locally before upload

```bash
cd /path/to/indiannepali
bash scripts/build-hostinger-deploy.sh
```

Then upload `hostinger-deploy/public_html/` as above.

---

## 7. Manual steps in hPanel (after upload)

1. **PHP version** — hPanel → **Advanced** → **PHP Configuration** → select **PHP 8.2** or **8.3**.

2. **Edit `.env`** — File Manager → `public_html/indiannepali-main/.env`:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://yourdomain.com`
   - Database credentials (MySQL from hPanel)
   - Toast API keys, SMTP settings, etc.

3. **Storage permissions** — via SSH or File Manager:
   ```bash
   chmod -R 775 public_html/indiannepali-main/storage
   chmod -R 775 public_html/indiannepali-main/bootstrap/cache
   ```

4. **Run migrations** (SSH or Hostinger terminal):
   ```bash
   cd ~/domains/yourdomain.com/public_html/indiannepali-main
   php artisan migrate --force
   php artisan storage:link   # only if /storage symlink is missing
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. **Symlink check** — `public_html/storage` should point to `indiannepali-main/storage/app/public`. If symlinks are not supported, copy uploads manually or ask Hostinger support.

6. **SSL** — hPanel → **SSL** → enable free certificate for your domain.

7. **Test** — visit `https://yourdomain.com/` and `https://yourdomain.com/admin`.

---

## 8. Security notes

- `indiannepali-main/.htaccess` blocks direct browser access to Laravel core files.
- Do **not** upload `.env` to a public repo; only to the server.
- Keep `APP_DEBUG=false` in production.

---

## 9. Rebuild after code changes

Whenever you change PHP, Blade, or front-end assets:

```bash
npm run build                    # if CSS/JS changed
bash scripts/build-hostinger-deploy.sh
```

Then re-upload changed files or the full `public_html/` package.
