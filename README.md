# Indian Nepali Kitchen

Full-stack website for **Indian Nepali Kitchen**, a Seattle-area restaurant serving Indian and Nepali cuisine. The app includes a customer-facing site (menu, ordering, reservations, gift cards, and more) and an admin dashboard to manage content, orders, and integrations.

Built with **Laravel 12**, **PHP 8.2**, **Blade**, **Tailwind CSS 4**, and **Vite**.

---

## What’s in this system

### Customer site (`/`)

| Area | Routes | Description |
|------|--------|-------------|
| Home | `/` | Hero, featured dishes, reviews, CTAs |
| Menu | `/menu` | Browse categories and add items to cart |
| Checkout | `/checkout` | Pickup/delivery checkout with Toast payments (or mock mode) |
| Reservations | `/reserve` | Table booking requests |
| Catering | `/catering` | Per-person packages and tray orders |
| Gift cards | `/gift-cards` | Purchase and check balance |
| Offers | `/offers` | Promotional deals |
| Gallery | `/gallery` | Photo gallery by category |
| About | `/about` | Story, team, values, stats |
| Contact | `/contact` | Contact form |
| Newsletter | POST `/newsletter` | Email signup |
| Unsubscribe | `/unsubscribe` | Email preference management |
| Customer account | `/account/*` | Register, login, order history, reviews, profile |
| Offline | `/offline` | PWA offline fallback page |

Cart actions use session storage (`POST/PATCH/DELETE /cart/*`).

### Admin dashboard (`/admin`)

Protected by admin authentication. Staff can manage:

- **Dashboard** — overview
- **Orders** — status updates
- **Reservations** — create and update bookings
- **Menu** — categories, items, availability
- **Catering** — inquiries, quotes, packages
- **Inquiries** — contact messages and replies
- **Content** — editable site content blocks
- **About** — hero, story, stats, values, team
- **Gallery** — categories and images
- **Promos** — offers and deals
- **Reviews** — featured customer reviews
- **Gift cards** — designs, amounts, issued cards
- **Newsletter** — subscriber list
- **Toast** — sync menu from Toast POS
- **Users** — admin user accounts and permissions
- **Settings** — hours, address, social links, map embed

### Integrations

- **Toast POS** — Live payments and menu sync when credentials are set in `.env`. Without them, checkout and gift cards run in **mock/demo mode** (safe for local development).
- **Gmail SMTP** — Order confirmations, gift card delivery, verification emails.
- **Service worker** (`public/sw.js`) — Basic offline/PWA support.

### Other folders

- `prototype/` — Early React UI prototypes (reference only; the live app uses Laravel Blade views).
- `.github/workflows/` — CI/CD deploy to dev (`dev` branch) and production (`main` branch) on Hostinger.

---

## Tech stack

| Layer | Tools |
|-------|-------|
| Backend | Laravel 12, PHP 8.2 |
| Frontend | Blade, Tailwind CSS 4, Vite |
| Database | SQLite (local) or MySQL/PostgreSQL (staging/production) |
| Payments | Toast API |
| Mail | SMTP (Gmail with App Password) |
| Auth | Laravel guards — `customer` (accounts) and `web` (admin) |

---

## Requirements

- PHP 8.2+ with extensions: `mbstring`, `pdo`, `sqlite` (or `pdo_mysql`), `bcmath`
- Composer
- Node.js 20+
- npm

---

## Local setup

```bash
# 1. Install dependencies
composer install
npm install

# 2. Create environment file (see next section)
cp .env.example .env
php artisan key:generate

# 3. Database
touch database/database.sqlite   # if using SQLite (default in .env.example)
php artisan migrate
php artisan db:seed              # optional: sample menu, content, admin users

# 4. Build assets
npm run build

# 5. Run the app
php artisan serve
```

Or use the combined dev script (server, queue, logs, Vite):

```bash
composer dev
```

Visit `http://127.0.0.1:8000`. Admin login: `/admin/login` (seeded users use password `password` — change in production).

---

## Environment file (`.env`)

The **`.env` file is required** but is **not committed to git** (see `.gitignore`). Each machine and environment needs its own copy.

**Always start from the template:**

```bash
cp .env.example .env
php artisan key:generate
```

Then edit `.env` with your values. The table below summarizes the important variables. Full comments and staging/production examples are in `.env.example`.

### Application

| Variable | Purpose |
|----------|---------|
| `APP_NAME` | Site name (e.g. `Indian Nepali Kitchen`) |
| `APP_ENV` | `local`, `staging`, or `production` |
| `APP_KEY` | Encryption key — run `php artisan key:generate` |
| `APP_DEBUG` | `true` locally; **`false` on live servers** |
| `APP_URL` | Base URL (e.g. `http://127.0.0.1:8000` or `https://www.yourdomain.com`) |

### Database

| Variable | Purpose |
|----------|---------|
| `DB_CONNECTION` | `sqlite` for local dev; `mysql` for staging/production |
| `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | MySQL/PostgreSQL settings when not using SQLite |

### Session, cache, queue

| Variable | Purpose |
|----------|---------|
| `SESSION_DRIVER` | `file` or `database` |
| `QUEUE_CONNECTION` | `sync` locally; `database` + worker on servers |
| `CACHE_STORE` | `file` or `database` |

### Mail (Gmail SMTP)

| Variable | Purpose |
|----------|---------|
| `MAIL_MAILER` | `smtp` |
| `MAIL_HOST` | `smtp.gmail.com` |
| `MAIL_PORT` | `587` |
| `MAIL_USERNAME` | Full Gmail address |
| `MAIL_PASSWORD` | **16-character Google App Password** (not your normal Gmail password) |
| `MAIL_ENCRYPTION` | `tls` |
| `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME` | From header for outgoing mail |

### Toast payments

Leave all `TOAST_*` variables empty for **mock checkout**. Set all core keys to enable live Toast:

| Variable | Purpose |
|----------|---------|
| `TOAST_CLIENT_ID` | Toast API client ID |
| `TOAST_CLIENT_SECRET` | Toast API secret |
| `TOAST_RESTAURANT_GUID` | Restaurant GUID |
| `TOAST_MERCHANT_UUID` | Merchant UUID |
| `TOAST_API_HOSTNAME` | Default: `ws-api.toasttab.com` |
| `TOAST_CARD_ENCRYPTION_KEY` | Card encryption key |
| `TOAST_CARD_ENCRYPTION_KEY_ID` | Key ID |
| `TOAST_DINING_OPTION_DELIVERY_GUID` | Delivery dining option |
| `TOAST_DINING_OPTION_PICKUP_GUID` | Pickup dining option |
| `TOAST_REVENUE_CENTER_GUID` | Revenue center |
| `TOAST_GIFT_CARD_MENU_ITEM_GUID` | Gift card menu item in Toast |

### Environment-specific notes

- **Local** — SQLite, mock Toast, `APP_DEBUG=true`
- **Staging** — Separate DB, `APP_KEY`, and URL; Toast optional (mock or sandbox)
- **Production** — Unique `APP_KEY`, MySQL, real Toast credentials, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`

Never reuse staging database credentials or `APP_KEY` on production.

---

## Project structure

```
app/
├── Http/Controllers/
│   ├── Admin/          # Dashboard, menu, orders, settings, Toast sync, …
│   └── Customer/       # Public site, cart, checkout, account auth
├── Models/             # Menu, Order, Customer, GiftCard, Reservation, …
├── Services/Toast/     # Toast payment gateway, order builder, encryption
├── Mail/               # Order, gift card, verification emails
└── Support/            # Helpers (catering quotes, email prefs, stock images)

resources/views/
├── customer/           # Public Blade pages
├── admin/              # Admin Blade pages
└── components/         # Reusable UI components

database/
├── migrations/         # Schema (menu, orders, catering, gift cards, …)
└── seeders/            # RestaurantSeeder — sample data and admin users

public/
├── css/                # Customer and admin styles
├── js/                 # Customer cart, admin promos, account auth
└── sw.js               # Service worker

config/toast.php        # Toast config (reads TOAST_* from .env)
routes/web.php          # All web routes
```

---

## Deployment

GitHub Actions workflows deploy automatically:

| Branch | Workflow | Target |
|--------|----------|--------|
| `dev` | `.github/workflows/deploy-dev.yml` | Dev server (Hostinger) |
| `main` | `.github/workflows/deploy-main.yml` | Production server |

Each server must have its own `.env` on disk (not in the repo). Required GitHub secrets include SSH key, host, username, app path, and health-check URL.

Deploy steps on the server: `git pull`, `composer install`, `npm ci && npm run build`, `php artisan migrate --force`, Laravel caches, and (when the app lives in `public_html/indiannepali-main/`) automatic `hostinger-sync-public.sh` to refresh `index.php`, `.htaccess`, and public assets in `public_html/`.

---

## Common commands

```bash
php artisan migrate              # Run migrations
php artisan db:seed              # Seed sample data
php artisan test                 # Run PHPUnit tests
npm run dev                      # Vite dev server (with HMR)
npm run build                    # Production asset build
php artisan queue:listen         # Process queued jobs (when QUEUE_CONNECTION=database)
```

---

## License

MIT (Laravel framework). Application code for Indian Nepali Kitchen.
