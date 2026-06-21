---
name: auth-permissions
description: Add or review authentication and the custom RBAC system. Trigger for auth changes, admin permissions, roles, Sanctum integration, or record-level authorization beyond route permissions.
---

# Auth and Permissions Skill

## Purpose

Implement authentication and authorization following project security standards and the existing custom RBAC system.

---

## When to Use

- Adding or changing authentication flows
- Adding new admin routes that need permission entries
- Assigning or reviewing role permissions
- Integrating Sanctum authentication
- Implementing record-level authorization when a feature genuinely needs ownership checks beyond route permissions

---

## Authentication

### Project Uses

This project uses **Laravel Sanctum** for authentication.

### Sanctum Setup

- Config: `config/sanctum.php`
- Default token model: `Laravel\Sanctum\PersonalAccessToken`
- A custom token model is **not currently registered** in this project.
- Stateful domains: configured in `config/sanctum.php`

### Custom token model verification

Before documenting or using a custom token model, verify **all** of the following:

1. The class exists (e.g., `app/Models/PersonalAccessToken.php`).
2. It extends Sanctum's token model appropriately (`extends \Laravel\Sanctum\PersonalAccessToken`).
3. It is registered through `Sanctum::usePersonalAccessTokenModel(...)` in a service provider or bootstrap file.

Search locations to check:

- `bootstrap/providers.php`
- `app/Providers/*`
- `config/sanctum.php`

**Never infer a custom token model from package conventions.**

### Middleware Protection

Protect routes using middleware:

```php
// API routes
Route::middleware('auth:sanctum')->group(function () {
    // Protected endpoints
});

// Admin routes
Route::middleware('auth:admin')->group(function () {
    // Protected admin endpoints
});
```

### Login Flow

1. Receive credentials (email, password)
2. Validate with Form Request
3. Authenticate via Service
4. Create Sanctum token: `$user->createToken('token-name')` — only when the authenticatable model uses `HasApiTokens` or otherwise exposes this method in the current project
5. Return token to client

---

## Admin Route Permissions (Custom RBAC)

> **CRITICAL:** This project uses a **custom RBAC system** (see [`../../../rules/08-custom-rbac.mdc`](../../../rules/08-custom-rbac.mdc)). Do NOT use Spatie or any external permission package. Do NOT replace or refactor the existing permission logic without explicit authorization.

### How admin route authorization works

- Admin route authorization is handled by the `CheckRolePermission` middleware.
- The route name **is** the permission string.
- `AdminType::SUPER_ADMIN` bypasses all permission checks automatically.
- Exception routes come from `exceptedRoutesFromRoles()` in the middleware.

### What to do for new admin routes

- Register the route with a named `admin.{module}.{action}` route.
- Add a matching permission string to the applicable `lang/{locale}/admin/routes.php` files so it appears in the permission assignment UI.
- Apply the `CheckRolePermission` middleware via the admin route group.
- Verify the route name matches the permission string exactly.

### What NOT to do

- Do **not** duplicate route permission checks in controllers, services, policies, gates, or Blade.
- Do **not** add manual `if (in_array(...))` role checks outside the middleware.
- Do **not** add routes to `exceptedRoutesFromRoles()` without explicit security review.
- Do **not** change the RBAC tables, middleware logic, or permission matching behavior without explicit confirmation.

### Existing RBAC tables

- `admins` - id, image, name, phone, country_code, email, password, is_blocked, is_notify, type, role_id, created_at, updated_at
- `roles` - id, created_at, updated_at
- `role_translations` - id, role_id, locale, name, created_at, updated_at
- `permissions` - id, permission (string), created_at, updated_at
- `permission_role` - role_id, permission_id (pivot)

Role names are stored in `role_translations`, not on the `roles` table. Do not add `guard_name`.

---

## Resource Ownership / Record-Level Authorization

Policies and gates are **optional** in this project. They are only appropriate when a feature needs ownership or record-level authorization that the route permission system does not cover.

### When policies may be appropriate

- A user must only edit their own profile.
- A customer must only see their own orders.
- An action depends on a record's owner rather than the route being accessed.

### When policies must NOT be used

- To enforce admin route permissions (use `CheckRolePermission` instead).
- To duplicate route-name-to-permission matching.
- As a universal requirement for every resource.

### Project convention note

`app/Policies/` does not currently exist in this project. Before creating a policy, confirm the feature genuinely requires record-level authorization. If you create one, place it in `app/Policies/` and rely on Laravel auto-discovery. Do not register policies manually unless the project already does so.

### Using policies when justified

```php
// In a controller, only when record-level authorization is required
$this->authorize('update', $post);
```

```blade
{{-- In Blade, only when record-level authorization is required --}}
@can('update', $post)
    <button>Edit</button>
@endcan
```

---

## Project Auth Services

This project has these auth-related services:

- `app/Services/Admin/Auth/LoginService.php` - Admin login
- `app/Services/Admin/Auth/LoginRateLimiter.php` - Rate limiting
- `app/Services/Admin/Auth/CheckStatus.php` - Status checking
- `app/Services/Auth/AuthService.php` - General auth logic
- `app/Services/Otp/OtpService.php` - OTP handling

---

## Security Rules

| Rule | Description |
|------|-------------|
| Never trust client input | Validate everything server-side |
| Use Form Requests | Validate auth data properly |
| Hash passwords | Use bcrypt/Hash::make() |
| Use Sanctum tokens | Don't roll custom tokens |
| Rate limit login | Prevent brute force |
| Log auth events | Track login attempts |
| Keep RBAC centralized | Route permissions belong in `CheckRolePermission` middleware |

---

## Controller Rules

- **Never handle admin route permissions manually** in controllers.
- **Use middleware** for route protection.
- **Use policies only** for genuine resource ownership checks.
- **Keep auth logic in services** - don't put it in controllers.
- **Never bypass CheckRolePermission middleware** without explicit confirmation.
- **Route name = permission string** — always verify this mapping for new admin routes.
- **Never document a custom Sanctum token model as active** unless it exists, extends Sanctum's model, and is registered with `Sanctum::usePersonalAccessTokenModel(...)`.

---

## Common Auth Patterns

### Login with Password

```text
1. FormRequest validates email/password
2. Service checks credentials
3. Service creates Sanctum token
4. Controller returns token
```

### Login with OTP

```text
1. FormRequest validates phone
2. Service generates OTP
3. Service sends OTP (SMS/Email)
4. User submits OTP
5. Service verifies and creates token
```

### Role-based Admin Access

```text
1. Route is registered with admin.* name
2. Route name matches a permission string in lang/*/admin/routes.php
3. CheckRolePermission middleware matches the route name against the admin's role permissions
4. SuperAdmin bypasses the check automatically
```

---

## Completion Standard

Auth implementation is NOT complete until:

- [ ] Routes properly protected with middleware
- [ ] Form Request validates auth input
- [ ] Service handles auth logic (not controller)
- [ ] New admin routes have matching permission entries in `lang/{locale}/admin/routes.php`
- [ ] Passwords properly hashed
- [ ] Rate limiting implemented
- [ ] Token generation follows Sanctum pattern
- [ ] If record-level authorization is required, a justified policy or gate is in place

Do **not** require policies for resources that only need route-level admin permissions.

---

## Output Format

- Auth setup (guards, providers)
- Middleware configuration
- Permission structure for new admin routes
- Service methods for auth operations
- Record-level authorization approach (only if needed)
- Example usage in controller/Blade
