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
- Export functionality (Excel, PDF, CSV, JSON, Copy, Print, HTML)
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
| Export | Maatwebsite Excel |
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
| `phpoffice/phpword` | Word document upload validation (password-protected docs, generic uploads) |

---

## Existing Base Features

- Authentication (login with password, login with OTP, logout)
- User management (admins, users)
- Role management
- Country/City/Region management
- Notification system
- Settings management
- Export functionality (Excel, PDF, CSV, JSON, Copy, Print, HTML)
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
- Word export is intentionally unsupported. Supported export formats are Excel, CSV, PDF, Print, JSON, Copy, HTML. Future features must not add Word export unless explicitly requested.

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

## Database Schema Highlights

| Table | Key Fields |
|-------|------------|
| `admins` | id, image, name, phone, country_code, email, password, is_blocked, is_notify, type, role_id, created_at, updated_at |
| `permissions` | id, permission (string), created_at, updated_at |
| `permission_role` | role_id, permission_id |
| `roles` | id, created_at, updated_at |
| `role_translations` | id, role_id, locale, name, created_at, updated_at |

---

## Middleware Aliases

| Alias / Usage | Class |
|---------------|-------|
| Applied by class in `routes/admin.php` | CheckRolePermission |
| `auth:sanctum` | Laravel Sanctum |
| `api.lang` | ApiLang |

---

## API Response Standard

- Always use traits from `app/Traits/Response/`
- Format: `{ "success": true|false, "data": {...}|null, "message": "..." }`
- Pagination: Laravel default (`paginate(15)`)

---

## Security Hardening (Active)

| Security Layer | Configuration |
|----------------|---------------|
| Rate Limiting (Auth) | `throttle:5,1` |
| Rate Limiting (API) | `throttle:60,1` |
| Production HTTPS | `URL::forceScheme('https')` enforced |
| Headers | `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff` |

---

## Dashboard Architecture

- **Controller → Service → Model → View** flow for admin pages.
- `AdminBaseController` provides generic CRUD (index, create, store, edit, update, show, destroy). Custom controllers may extend `Controller` directly for non-CRUD pages.
- `CrudBaseService` (extended by `AuthenticatableBaseService`) handles data access, pagination, and CRUD operations.
- Services return either a query builder (for index lists) or a prepared `$vars` array (for create/edit/show pages).
- Controllers call services and pass results to Blade — they do not query the database.

## Responsibilities

| Layer | Responsibility |
|-------|---------------|
| Controller | Orchestrate request flow, resolve FormRequest, call service, return view/response |
| Service | Business logic, data access, eager loading, export |
| Form Request | Validation rules, authorization, boolean normalization, FK validation |
| Blade view | Presentation only — no DB queries, service calls, or model lookups |

## Admin UI Conventions

- **Dark RTL theme** using Bootstrap admin template with RTL/LTR toggle via theme customizer.
- Form components: `<x-form.*>` and table components: `<x-table.*>`.
- Form labels in `<x-form.*>` use plain keys (translated internally); all other labels use `__('admin/...')` keys.
- CSS uses class namespacing per section (`{section}-action-{type}`, `{section}-show-header`, `admin-stat-card`).
- Action buttons follow a color convention: view (blue), edit (green), delete (red), restore (teal).

## Translation Conventions

- Admin translations stored in `lang/{locale}/admin/` split by domain: `main.php`, `routes.php`, `inputs.php`, `auth.php`, `validation.php`.
- Permission display labels live in `routes.php` — one entry per admin route name.
- Enum labels use `__('admin/main.{value}')` via `GeneralEnumTrait`.
- Component labels use a `admin/inputs.{key}` lookup path — plain keys are passed, not full translation strings.

## Seeder / File Upload Caveat

- Models with `UPLOAD_TYPE = 'custom'` (via `app/Traits/Upload/`) expect file paths as plain strings in the DB. Seeders can provide known filename strings (e.g. `'default.png'`) directly.
- Models using Spatie Media Library should be seeded through the model's `create()` method only if upload traits are bypassed or properly mocked.
- Translation seeders must insert parent rows first, then batch both locales in a single `->insert([])` call.

## RBAC Convention

- Route name === permission string (e.g. `admin.admins.index`).
- `CheckRolePermission` middleware intercepts all authenticated admin routes and compares the current route name against the admin's role permissions.
- `AdminType::SUPER_ADMIN` bypasses all checks.
- Exception routes (bypassed permissions) live in `app/Traits/Route/RouteTrait.php::exceptedRoutesFromRoles()`.
- Permission labels are defined in `lang/{locale}/admin/routes.php` and appear in the role assignment UI.

## Performance / N+1 Expectations

- All admin index/show services must eager-load every relation displayed in the view.
- Blade templates must never contain `->count()`, `->first()`, `->get()`, or model lookups.
- Shared dashboard data (menus, counts) should use view composers or controller `view()->share()`.
- The project uses `Model::shouldBeStrict()` in `AppServiceProvider` for local development, which catches lazy loading, attribute discarding, and missing attributes.

## Notes

- This is a base project for new Laravel applications
- All features are reusable and can be extended
- Admin CRUD uses CrudBaseService as base
- Export uses ExportService with multiple strategies