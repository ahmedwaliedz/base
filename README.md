# My Base Project

A Laravel 11 admin panel with API authentication.

## Features

- **Admin Panel**: Full CRUD for admins, users, roles, countries
- **API Authentication**: Login with password or OTP code
- **Role-based Access Control**: Permission-based route protection
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

## Getting Started

```bash
# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Start development server
php artisan serve
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/v1/auth/login | Login with credentials |
| POST | /api/v1/auth/login-code | Login with OTP |
| POST | /api/v1/auth/request-code | Request OTP |
| POST | /api/v1/auth/logout | Logout |
| GET | /api/v1/auth/me | Get current user |

## License

MIT