# My Base Project

A production-ready Laravel 11 admin panel with API authentication and custom Role-Based Access Control (RBAC).

## Features

- **Admin Panel**: Full CRUD for admins, users, roles, countries
- **API Authentication**: Login with password or OTP code
- **Custom RBAC**: Permission-based route protection (no external packages)
- **Export**: CSV, Excel, PDF, Print, JSON, Word exports
- **Translations**: Multi-language support (Arabic, English)
- **Media**: File uploads with Spatie Media Library

## Tech Stack

- Laravel 11.x
- PHP 8.2+
- Laravel Sanctum
- Spatie Media Library
- Maatwebsite Excel
- PHPWord

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

OTP authentication can be configured via `.env`:

| Provider | Description |
|----------|-------------|
| `log` | Logs OTP codes to `storage/logs/laravel.log` via `LogCodeSender`.

### Example Configuration

```env
OTP_PROVIDER=log
```

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

> Note: When using `OTP_PROVIDER=log`, the code appears in `storage/logs/laravel.log`.

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
- `word`

## Troubleshooting

| Issue | Solution |
|-------|----------|
| "Unauthorized" error | Ensure the route name matches the permission string exactly in the database |
| Super Admin bypass not working | Verify the `type` field in the `admins` table is set to `super_admin` |
| Cache-related issues | Run `php artisan cache:clear` and `php artisan config:clear` |
| Session expired | Check `SANCTUM_STATEFUL_DOMAINS` in `.env` matches your domain |

## License

MIT
