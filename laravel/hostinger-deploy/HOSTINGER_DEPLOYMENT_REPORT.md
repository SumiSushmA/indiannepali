# Hostinger Deployment Report — Indian Nepali Kitchen

Generated for the **`hostinger-deploy/`** package. The original project at the repo root is **unchanged**; this folder is a copy ready to upload.

**Last updated:** 2026-06-17

---

## 1. Final folder structure

On Hostinger, Laravel core lives **outside** `public_html` (not web-accessible). The local package mirrors that layout:

```
hostinger-deploy/
├── indiannepali-main/                    ← upload to domain root (sibling of public_html)
│   ├── .htaccess                         ← Require all denied (extra protection)
│   ├── app/
│   ├── bootstrap/
│   │   └── app.php                       ← required
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   │   └── autoload.php                  ← required
│   ├── artisan
│   ├── composer.json
│   └── .env                              ← configure on server
└── public_html/                          ← upload contents to Hostinger public_html
    ├── index.php                         ← Laravel 12 bootstrap (loads ../indiannepali-main)
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
    └── storage → ../indiannepali-main/storage/app/public   ← symlink (uploaded files)
```

**On the server** (after upload), the layout should look like:

```
~/domains/yourdomain.com/
├── indiannepali-main/        ← secure Laravel core (not in web root)
└── public_html/              ← web root (only public files)
    ├── index.php
    ├── .htaccess
    ├── build/
    ├── css/
    ├── js/
    └── storage → ../indiannepali-main/storage/app/public
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

### Into `indiannepali-main/` (Laravel root, excluding `public/`)

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
| `public_html/index.php` | **Fixed** — `$appPath = __DIR__ . '/../indiannepali-main'` |
| `public_html/.htaccess` | **Updated** — Laravel rewrite rules + trailing slash redirect |
| `public_html/.user.ini` | **Created** — PHP upload/memory limits |
| `indiannepali-main/.htaccess` | **Created** — deny all direct HTTP access |
| `public_html/storage` | **Symlink** → `../indiannepali-main/storage/app/public` |
| `scripts/build-hostinger-deploy.sh` | **Updated** — core outside `public_html` |
| `scripts/hostinger-sync-public.sh` | **Updated** — sibling layout + storage symlink |
| `deploy/hostinger/templates/*` | **Updated** — canonical Hostinger entry files |

No controllers, routes, models, or Blade templates were changed.

---

## 4. `public_html/index.php`

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Point to the secure folder outside public_html
$appPath = __DIR__ . '/../indiannepali-main';

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
| Wrong folder name in `index.php` | ✅ Uses `../indiannepali-main` |
| Laravel core inside public_html | ✅ Moved outside (secure) |
| Missing `vendor/` | ✅ Present (~80 MB) |
| Missing `.env` on server | ⚠️ Copy/edit on server |
| PHP version < 8.2 | ⚠️ Set PHP 8.2+ in hPanel |
| Wrong `index.php` path | ✅ Fixed |
| `storage/` not writable | ⚠️ Set permissions on server (see below) |
| Broken `.htaccess` | ✅ Present |
| Missing public assets | ✅ Synced from `public/` |

---

## 6. Hostinger upload instructions

### Option A — Upload the pre-built package (recommended)

1. On your Mac, create **two** zip files (or upload folders directly):
   - `hostinger-deploy/indiannepali-main/` → extract to `~/domains/yourdomain.com/indiannepali-main/`
   - `hostinger-deploy/public_html/*` → extract into `~/domains/yourdomain.com/public_html/`
2. In hPanel → **Files** → open your domain folder (parent of `public_html`).
3. **Back up** existing files if the site is already live.
4. Confirm this layout on the server:

```
~/domains/yourdomain.com/
├── indiannepali-main/
│   ├── app/
│   ├── bootstrap/
│   ├── vendor/
│   ├── .env
│   └── ...
└── public_html/
    ├── index.php
    ├── .htaccess
    ├── build/
    ├── css/
    ├── js/
    └── storage → ../indiannepali-main/storage/app/public
```

**Important:** `index.php` must sit directly in `public_html/`, and `indiannepali-main/` must be a **sibling** folder (one level up from `public_html`), not nested inside it.

### Option B — Rebuild locally before upload

```bash
cd /path/to/indiannepali
bash scripts/build-hostinger-deploy.sh
```

Then upload both `hostinger-deploy/indiannepali-main/` and `hostinger-deploy/public_html/` as above.

---

## 7. Manual steps in hPanel (after upload)

1. **PHP version** — hPanel → **Advanced** → **PHP Configuration** → select **PHP 8.2** or **8.3**.

2. **Edit `.env`** — File Manager → `indiannepali-main/.env` (not inside `public_html`):
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://yourdomain.com`
   - Database credentials (MySQL from hPanel: `DB_HOST=localhost`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)
   - Toast API keys, SMTP settings, etc.

3. **Storage permissions** — via SSH or File Manager:
   ```bash
   chmod -R 775 indiannepali-main/storage
   chmod -R 775 indiannepali-main/bootstrap/cache
   ```

4. **Run optimizations** (SSH — run inside `indiannepali-main`):
   ```bash
   cd ~/domains/yourdomain.com/indiannepali-main
   composer install --no-dev --optimize-autoloader
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. **Symlink check** — `public_html/storage` should point to `../indiannepali-main/storage/app/public`. If symlinks are not supported, copy uploads manually or ask Hostinger support.

6. **SSL** — hPanel → **SSL** → enable free certificate for your domain.

7. **Test** — visit `https://yourdomain.com/` and `https://yourdomain.com/admin`.

---

## 8. Security notes

- Laravel core (`app/`, `config/`, `.env`, `vendor/`) is **outside** the web root and cannot be accessed via HTTP.
- `indiannepali-main/.htaccess` provides extra protection if document root is misconfigured.
- Do **not** commit `.env` to a public repo; only upload it to the server.
- Keep `APP_DEBUG=false` in production.

---

## 9. Rebuild after code changes

Whenever you change PHP, Blade, or front-end assets:

```bash
npm run build                    # if CSS/JS changed
bash scripts/build-hostinger-deploy.sh
```

Then re-upload changed files or the full package (both `indiannepali-main/` and `public_html/`).
