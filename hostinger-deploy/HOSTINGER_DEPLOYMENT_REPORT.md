# Hostinger Deployment Report — Indian Nepali Kitchen

Generated for the **`hostinger-deploy/`** package. The original project at the repo root is **unchanged**; this folder is a copy ready to upload.

---

## 1. Final folder structure

```
hostinger-deploy/
└── public_html/                          ← upload contents to Hostinger public_html
    ├── index.php                         ← NEW (bootstraps Laravel from indiannepali-main/)
    ├── .htaccess                         ← copied from public/.htaccess + security rules
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
        ├── .htaccess                     ← NEW (Require all denied)
        ├── app/
        ├── bootstrap/
        ├── config/
        ├── database/
        ├── resources/
        ├── routes/
        ├── storage/
        ├── vendor/
        ├── artisan
        ├── composer.json
        ├── composer.lock
        └── .env
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
| `README.md`, `package.json`, etc. | Non-essential but harmless |

**Excluded from copy:** `public/`, `node_modules/`, `.git/`, `deploy/`, `hostinger-deploy/`, `prototype/`, `scripts/`, `tests/`

---

## 3. Files modified / created (not in original Laravel)

| File | Action |
|------|--------|
| `public_html/index.php` | **Created** — paths point to `indiannepali-main/` |
| `public_html/.htaccess` | **Modified** — Laravel rewrites + block `/indiannepali-main/` + hidden files |
| `public_html/.user.ini` | **Created** — PHP upload/memory limits |
| `public_html/indiannepali-main/.htaccess` | **Created** — deny all direct HTTP access |
| `public_html/storage` | **Symlink** → `indiannepali-main/storage/app/public` |

No controllers, routes, models, or Blade templates were changed.

---

## 4. New `public_html/index.php`

Based on `public/index.php`; only paths updated:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/indiannepali-main/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/indiannepali-main/vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/indiannepali-main/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

---

## 5. New `.htaccess` files

### `public_html/.htaccess`

Standard Laravel front-controller rules (from `public/.htaccess`) plus:

- `RewriteBase /` — routes work from domain root, not `/public`
- `RewriteRule ^indiannepali-main/ - [F,L]` — forbid browsing Laravel core
- `<FilesMatch "^\.">` — block hidden files

### `public_html/indiannepali-main/.htaccess`

```apache
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
    Order deny,allow
    Deny from all
</IfModule>
```

PHP still loads these files via the filesystem when handling requests through `index.php`.

---

## 6. Asset path verification

| Asset type | How referenced | Works from `public_html/`? |
|------------|----------------|----------------------------|
| CSS | `/css/theme.css`, etc. (absolute paths in layouts) | Yes — `public_html/css/` |
| JS | `/js/customer.js`, etc. | Yes — `public_html/js/` |
| Logo / SVG | `/logo.png`, `asset('Group 1171275134.svg')` | Yes — files at web root |
| Uploads | `asset('storage/...')` → `/storage/...` | Yes — via `storage` symlink |
| Service worker | `/sw.js` | Yes |

---

## 7. `storage:link` — is it needed?

**On your local machine:** you already have `public/storage` → `storage/app/public`.

**For this Hostinger package:** the symlink is **already created** at:

```
public_html/storage → indiannepali-main/storage/app/public
```

You do **not** need to run `php artisan storage:link` if you upload this symlink (FTP clients often preserve it; if not, recreate on the server):

```bash
cd ~/domains/yourdomain.com/public_html
rm -f storage
ln -sfn indiannepali-main/storage/app/public storage
```

Or via SSH:

```bash
php artisan storage:link
```

only works if your document root were `public/` inside Laravel; with this layout, the **manual symlink in `public_html/`** is the correct approach.

Uploaded gallery/about images in `storage/app/public/` are included in the copy.

---

## 8. `vendor/` status

**`vendor/` is present** in `hostinger-deploy/public_html/indiannepali-main/vendor/`.

If you rebuild locally without `vendor/`, run before packaging:

```bash
composer install --no-dev --optimize-autoloader
```

Then re-run:

```bash
./scripts/build-hostinger-deploy.sh
```

---

## 9. Production `.env` recommendations

Edit `public_html/indiannepali-main/.env` on the server (never commit production secrets):

```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://my-domain.com

# Generate a unique key if deploying fresh:
# php artisan key:generate

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_hostinger_db_name
DB_USERNAME=your_hostinger_db_user
DB_PASSWORD=your_hostinger_db_password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

SESSION_SECURE_COOKIE=true
LOG_LEVEL=warning
```

Also configure `MAIL_*` and `TOAST_*` for production. Remove or ignore `.env.test-ci` on the server (copied by mistake from dev).

---

## 10. Permissions (via SSH or File Manager)

From `public_html/indiannepali-main/`:

```bash
chmod -R 775 storage bootstrap/cache
```

If the web server user differs from your SSH user on Hostinger:

```bash
chown -R u123456789:u123456789 storage bootstrap/cache
```

(Replace `u123456789` with your Hostinger account user from hPanel.)

Ensure `storage/logs`, `storage/framework/cache`, `storage/framework/sessions`, and `storage/framework/views` are writable.

---

## 11. Commands to run on Hostinger (SSH)

After uploading `hostinger-deploy/public_html/*` into your domain's `public_html/`:

```bash
cd ~/domains/yourdomain.com/public_html/indiannepali-main

# 1. Edit .env (production values above)
nano .env

# 2. If vendor/ was not uploaded:
composer install --no-dev --optimize-autoloader

# 3. App key (only if APP_KEY is empty)
php artisan key:generate

# 4. Database
php artisan migrate --force

# 5. Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Permissions
chmod -R 775 storage bootstrap/cache

# 7. Recreate storage symlink if FTP dropped it
cd ..
ln -sfn indiannepali-main/storage/app/public storage
```

Set PHP **8.2+** in hPanel → Advanced → PHP Configuration.

---

## 12. Upload instructions

1. Zip `hostinger-deploy/public_html/` locally.
2. In Hostinger File Manager, open your domain's `public_html/`.
3. Upload and extract so `index.php` sits directly in `public_html/` (not `public_html/public_html/`).
4. Confirm `indiannepali-main/.htaccess` and the `storage` symlink exist.
5. Edit `.env`, run migrations, cache config.

To rebuild this package anytime (original repo untouched):

```bash
./scripts/build-hostinger-deploy.sh
```

---

## 13. Possible HTTP 500 causes

| Cause | Fix |
|-------|-----|
| Wrong PHP version (< 8.2) | Set PHP 8.2/8.3 in hPanel |
| Missing `vendor/` | `composer install --no-dev --optimize-autoloader` |
| Invalid or missing `APP_KEY` | `php artisan key:generate` |
| Database connection failure | Check MySQL host/credentials in `.env` (often `localhost`) |
| `storage/` or `bootstrap/cache/` not writable | `chmod -R 775 storage bootstrap/cache` |
| Broken `storage` symlink | Recreate: `ln -sfn indiannepali-main/storage/app/public storage` |
| Stale config cache after `.env` change | `php artisan config:clear` then `config:cache` |
| `mod_rewrite` disabled | Ensure `.htaccess` is allowed; contact Hostinger support |
| Symlink not allowed on plan | Copy `storage/app/public` contents to `public_html/storage/` (no symlink) |
| Missing PHP extensions | Enable `mbstring`, `pdo_mysql`, `bcmath`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo` |

Check `indiannepali-main/storage/logs/laravel.log` after enabling logging.

---

## 14. Security notes

- `indiannepali-main/` is blocked from direct HTTP access (double layer: root rewrite + folder `.htaccess`).
- Do **not** upload `.env` to a public repo; only to the server.
- Set `APP_DEBUG=false` in production.
- Delete `.env.test-ci` from the server if present.

---

## 15. Alternative (preferred if hPanel allows)

If Hostinger lets you set the document root to `.../public`, use standard Laravel layout instead:

- Document root → `indiannepali/public`
- No custom `index.php` needed

This package is for the common case where **document root must stay `public_html/`**.
