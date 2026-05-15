# Project Context

## Purpose

Provide a clear understanding of the current project, its type, structure, and scope.

This file defines the overall identity of the project so that all development decisions remain aligned with its purpose.

---

## Project Overview

- Project Name: My Base Project
- Project Type: Fullstack (Admin Dashboard + API)
- Target Users: Admins, API consumers (mobile/web apps)

---

## Core Goal

Laravel 11 base project with:
- Admin panel with Blade templates
- Versioned REST API (api/v1)
- Authentication system (Sanctum + OTP + Password)
- Export functionality (Excel, PDF, Word, etc.)
- Multilingual support (Arabic/English)

---

## Tech Stack

| Component | Version/Details |
|-----------|-----------------|
| Backend | Laravel 11 |
| Language | PHP 8.2+ |
| Database | MySQL |
| Auth | Laravel Sanctum |
| API Versioning | api/v1 |
| Admin Panel | Blade templates |
| File Storage | Spatie Media Library |
| Translations | Astrotomic Translatable |
| Export | Maatwebsite Excel, PHPWord |
| Frontend | Vite (JS/CSS bundling) |

---

## Architecture Style

- Service-based architecture
- Form Request validation
- Thin controllers
- Blade (presentation only)
- API responses use response traits

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin panel controllers
│   │   └── Api/V1/         # API v1 controllers
│   ├── Requests/           # Form Request validation
│   └── Middleware/         # Custom middleware
├── Models/                 # Eloquent models
├── Services/
│   ├── Admin/              # Admin services
│   │   ├── Auth/           # Auth services
│   │   ├── Base/           # Base classes (CrudBaseService)
│   │   └── Export/         # Export services
│   ├── Api/                # API services
│   └── OTP/                # OTP handling
├── Traits/                 # Shared traits
├── Policies/               # Authorization policies
└── Exceptions/            # Custom exceptions

config/
├── sanctum.php            # Sanctum config
├── translatable.php       # Translatable config
├── medialibrary.php       # Media library config

database/
├── migrations/            # Database migrations
├── factories/             # Model factories
└── seeders/              # Database seeders

resources/views/
├── admin/                 # Admin Blade views
│   └── components/        # Blade components
│       ├── form/          # Form components
│       └── table/         # Table components

routes/
├── admin.php              # Admin routes
├── web.php                # Web routes
└── api/v1/                # API v1 routes
    ├── auth.php           # Auth endpoints
    ├── countries.php      # Countries endpoint
    ├── cities.php         # Cities endpoint
    ├── regions.php        # Regions endpoint
    ├── notifications.php  # Notifications endpoint
    └── settings.php       # Settings endpoint

lang/
├── ar/                    # Arabic translations
│   └── admin/             # Admin Arabic
└── en/                    # English translations
    └── admin/             # Admin English

tests/
├── Feature/               # Feature tests
│   ├── Api/               # API tests
│   └── Admin/             # Admin tests
└── Unit/                  # Unit tests
    └── Services/          # Service tests
```

---

## Key Packages

| Package | Purpose |
|---------|---------|
| `laravel/sanctum` | API authentication |
| `astrotomic/laravel-translatable` | Multilingual models |
| `spatie/laravel-medialibrary` | File/image uploads |
| `maatwebsite/excel` | Excel exports |
| `phpoffice/phpword` | Word document exports |

---

## Existing Base Features

- Authentication (login with password, login with OTP, logout)
- User management (admins, users)
- Role management
- Country/City/Region management
- Notification system
- Settings management
- Export functionality (Excel, PDF, Word, Print, etc.)
- File upload handling via Spatie
- API response structure

---

## Main Modules

- **Auth** - Login, logout, password, OTP
- **Users** - User management (admins, regular users)
- **Roles** - Role management
- **Countries** - Country listing
- **Cities** - City listing
- **Regions** - Region listing
- **Notifications** - Notification management
- **Settings** - Application settings
- **Profile** - Admin profile management

---

## API and UI

- **API:** Yes, versioned (api/v1)
  - Public endpoints: auth (login, register, etc.)
  - Protected endpoints: authenticated via Sanctum
- **UI:** Admin panel
  - Blade templates
  - Custom components (form inputs, tables)
  - Responsive design

---

## Naming Conventions

- Models: PascalCase (User, Category)
- Tables: snake_case (users, categories)
- Controllers: PascalCase (UserController)
- Services: PascalCase (UserService)
- Form Requests: PascalCase (StoreUserRequest)
- Routes: snake_case (admin.users.index)

---

## Constraints

- Must follow base project structure
- Must reuse existing services before creating new ones
- Must not introduce new architecture patterns
- Must follow response format in API
- Validation must be in Form Requests
- Business logic must be in Services

---

## Integration Needs

- SMS provider (for OTP)
- Email service (for notifications)
- File storage (local/S3)

---

## Documentation Requirements

- APIs must be documented
- Postman examples required
- Response format must be consistent

---

## Testing Expectations

- Validation must be testable
- Services must be testable
- API endpoints must be testable

---

## Current Phase

- Development (base project setup)

---

## Notes

- This is a base project for new Laravel applications
- All features are reusable and can be extended
- Admin CRUD uses CrudBaseService as base
- Export uses ExportService with multiple strategies