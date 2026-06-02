# My Base Project

A production-ready Laravel 11 admin panel with API authentication and custom Role-Based Access Control (RBAC).

## Features

- **Admin Panel**: Full CRUD for admins, users, roles, countries
- **API Authentication**: Login with password or OTP code
- **Custom RBAC**: Permission-based route protection (no external packages)
- **Export**: CSV, Excel, PDF, Print, JSON, Copy, HTML exports
- **Translations**: Multi-language support (Arabic, English)
- **Media**: File uploads with Spatie Media Library

## Tech Stack

- Laravel 11.x
- PHP 8.2+
- Laravel Sanctum
- Spatie Media Library
- Maatwebsite Excel

## Prerequisites

- PHP 8.2+
- Composer 2.x
- Node.js 18+
- MySQL 8.0+ or PostgreSQL
- Redis (optional, for caching)

## Quick Installation

```bash
# Clone the repository
git clone https://github.com/ahmedwaliedz/base.git
cd base

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Copy and configure environment
cp .env.example .env
php artisan key:generate

# Configure database in .env file
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=your_database
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Run migrations and seeders
php artisan migrate --seed

# Start the development server
php artisan serve

# Start frontend (in another terminal)
npm run dev
```

## Custom RBAC System

This project uses a custom RBAC system that must not be replaced with external packages like `spatie/laravel-permission`.

### How It Works

- **Permissions**: Stored as strings (e.g., `admin.roles.index`) in the `permissions` table
- **Route Matching**: The `CheckRolePermission` middleware compares the current route name against the admin's permissions
- **Super Admin**: Accounts with `AdminType::SUPER_ADMIN` automatically bypass all permission checks
- **Excluded Routes**: Specific routes can be excluded from permission checks via `exceptedRoutesFromRoles()` in the middleware

### Adding a New Permission

1. Create a permission record in the `permissions` table using the exact admin route name, for example `admin.products.index`.
2. Attach the permission to the intended role using the existing role-permission relationship.
3. Place the route inside the existing protected admin route group in `routes/admin.php`. Routes in that group are automatically checked by `App\Http\Middleware\Admin\CheckRolePermission`.

**Example:**
```php
Route::get('/users', [UserController::class, 'index'])
    ->name('admin.users.index');
```

If a route must bypass permission checks, document it in the middleware exception list (`exceptedRoutesFromRoles()` in `app/Traits/Route/RouteTrait.php`) instead of adding ad-hoc controller or Blade checks.

> **Warning**: Do not install external permission packages as they will conflict with the existing custom RBAC architecture.

## OTP Authentication Setup

OTP authentication uses `LogCodeSender` to deliver activation codes. OTP codes are logged to `storage/logs/laravel.log` for local/development use.

```env
# No OTP_PROVIDER setting is needed — LogCodeSender is always used.
```

Mail / Twilio provider switching is not currently implemented.

### API Requests

**Request OTP Code:**
```bash
POST /api/v1/auth/request-code
Content-Type: application/json

{
    "phone": "+1234567890"
}
```

**Login with OTP:**
```bash
POST /api/v1/auth/login-code
Content-Type: application/json

{
    "phone": "+1234567890",
    "code": "123456"
}
```

> Note: Activation codes appear in `storage/logs/laravel.log` via `LogCodeSender`.

## API Examples

### Login with Credentials

```bash
POST /api/v1/auth/login
Content-Type: application/json

{
    "email": "admin@example.com",
    "password": "password"
}
```

### Get Current User

```bash
GET /api/v1/auth/me
Authorization: Bearer {token}
```

### Admin Export

Admin list pages support exporting data in multiple formats through the admin UI.

### Available Export Formats

- `csv`
- `excel` (default)
- `pdf`
- `print`
- `json`
- `copy`
- `html`

> **Note:** Word export (`docx`) is intentionally unsupported because it caused memory instability on low-memory/shared hosting. Use Excel or PDF instead.

## Troubleshooting

| Issue | Solution |
|-------|----------|
| "Unauthorized" error | Ensure the route name matches the permission string exactly in the database |
| Super Admin bypass not working | Verify the `type` field in the `admins` table is set to `super_admin` |
| Cache-related issues | Run `php artisan cache:clear` and `php artisan config:clear` |
| Session expired | Check `SANCTUM_STATEFUL_DOMAINS` in `.env` matches your domain |

## Deployment to InfinityFree

InfinityFree is a free shared hosting provider with **no SSH/terminal access**. Files are deployed via FTP using the included GitHub workflow (`.github/workflows/infinity-free.yml`).

### Important Production Settings

Before deploying, configure the production `.env` file **on the server** with these values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Never use 127.0.0.1 or localhost — use the MySQL hostname from the InfinityFree panel
DB_HOST=sqlXXX.infinityfree.com
DB_PORT=3306
DB_DATABASE=your_infinityfree_database
DB_USERNAME=your_infinityfree_username
DB_PASSWORD=your_infinityfree_password

# Use file-based cache/session (database cache causes the "SQLSTATE[HY000] Connection refused" error)
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# Disable debug bar
DEBUGBAR_ENABLED=false
```

### Generate APP_KEY

Run locally (never on the server):

```bash
php artisan key:generate --show
```

Copy the output and set it as `APP_KEY` in the production `.env` file.

### Clearing Cache on the Server

Since there is no terminal access:

1. **During deploy** — The GitHub workflow automatically removes `bootstrap/cache/*.php` files before uploading.
2. **Emergency** — Use the protected script at `public/server-maintenance-clear-cache.php` (see below). Delete this script after use.

### Secret Rotation Warning

> **`.env` was previously tracked in git.** Removing `.env` from tracking with `git rm --cached` does **not** remove it from git history. Anyone with repository access can view past versions of `.env`.
>
> You **must manually rotate** every secret that was ever in the tracked `.env`:
>
> - `APP_KEY` — generate a new key with `php artisan key:generate --show`
> - Database credentials (`DB_USERNAME`, `DB_PASSWORD`)
> - `JWT_SECRET`
> - `X_API_KEY`
> - Mail credentials (`MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`)
> - `MAINTENANCE_CLEAR_TOKEN` (if set)
> - Any API keys or tokens
>
> After rotating, update the production `.env` with the new values. Do **not** re-add `.env` to git tracking.

### Emergency Cache Clear Script

The file `public/server-maintenance-clear-cache.php` can delete stale Laravel bootstrap cache files without terminal access.

**How token loading works:**
The script does **not** rely on `getenv()` (which is unavailable on InfinityFree outside Laravel's boot cycle). Instead it:
1. Reads `MAINTENANCE_CLEAR_TOKEN` directly from the project root `.env` file using a minimal line-by-line parser
2. Falls back to `getenv()` if the `.env` file is not readable
3. Uses `hash_equals()` for timing-safe token comparison

**Setup:**
1. Generate a random token: `openssl rand -hex 32`
2. Add to production `.env`: `MAINTENANCE_CLEAR_TOKEN=your_generated_token`
3. Visit: `https://your-domain.com/server-maintenance-clear-cache.php?token=your_generated_token`

**Security:**
- Reads only `MAINTENANCE_CLEAR_TOKEN` from `.env` — does not load or expose other values
- Falls back to `getenv()` if `.env` is unreadable
- Returns a generic `403 Forbidden` for any missing/invalid token (does not reveal whether a token exists)
- Uses `hash_equals()` for timing-safe comparison — prevents timing attacks
- Only deletes known bootstrap cache files (`config.php`, `routes-v7.php`, `events.php`, `packages.php`, `services.php`)
- Does not accept file names from request input
- Does not run shell commands or bootstrap Laravel

**After use:**
- Delete the script from the production server, or
- Remove `MAINTENANCE_CLEAR_TOKEN` from `.env` to disable it, or
- Generate and set a new token to re-authorize future use

### Deployment Checklist

1. [ ] Set real DB credentials in server `.env` (hostname from InfinityFree panel, not `127.0.0.1`)
2. [ ] Set `APP_KEY` to the output of `php artisan key:generate --show`
3. [ ] Set `CACHE_STORE=file`, `SESSION_DRIVER=file`, `QUEUE_CONNECTION=sync`
4. [ ] Set `APP_DEBUG=false`, `DEBUGBAR_ENABLED=false`
5. [ ] Delete remote `bootstrap/cache/config.php` once, or use the emergency cache clearer
6. [ ] Redeploy through GitHub Actions
7. [ ] Verify the site loads without SQL cache errors

### Known Issues

- **SQLSTATE[HY000] [2002] Connection refused on `cache` table** — Caused by `CACHE_STORE=database` (the Laravel 11 default) or stale cached config with `DB_HOST=127.0.0.1`. Fix by setting `CACHE_STORE=file` and clearing `bootstrap/cache/config.php`.

## License

MIT
