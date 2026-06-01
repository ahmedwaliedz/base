# Auth and Permissions Skill

## Purpose

Implement authentication and authorization following project security standards.

---

## When to Use

- Adding authentication to new features
- Implementing role-based access control
- Creating protected routes
- Setting up authorization policies
- Adding permission checks

---

## Authentication

### Project Uses

This project uses **Laravel Sanctum** for authentication.

### Sanctum Setup

- Config: `config/sanctum.php`
- Token model: `App\Models\PersonalAccessToken`
- Stateful domains: configured in `config/sanctum.php`

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
4. Create Sanctum token: `$user->createToken('token-name')`
5. Return token to client

---

## Authorization

### Policies

Use Laravel Policies in `app/Policies/`:
```php
// php artisan make:policy PostPolicy
// Laravel 11 auto-discovers policies - no manual registration needed
```

### Gates

For simple checks, use Gates in service providers:
```php
Gate::define('update-post', function ($user, $post) {
    return $user->id === $post->user_id;
});
```

### Policy Binding

Laravel 11 auto-discovers policies in `app/Policies/`. No manual registration needed.

### Using Policies

In Controller:
```php
$this->authorize('update', $post);
```

In Blade:
```php
@can('update', $post)
    <button>Edit</button>
@endcan
```

---

## Roles & Permissions

> ⚠️ **CRITICAL:** This project uses a **custom RBAC system** (see `rules/08-custom-rbac.mdc`). Do NOT use Spatie or any external permission package. Route names must exactly match permission strings.

### Existing Roles

- **Super Admin** - Full access (bypasses all permission checks)
- **Admin** - Admin panel access
- **User** - Regular user

### Permission Structure

This project uses a **custom RBAC system** with these tables:
- `admins` - id, image, name, phone, country_code, email, password, is_blocked, is_notify, type, role_id, created_at, updated_at
- `roles` - id, created_at, updated_at
- `role_translations` - id, role_id, locale, name, created_at, updated_at
- `permissions` - id, permission (string), created_at, updated_at
- `permission_role` - role_id, permission_id (pivot)

Role names are stored in `role_translations`, not on the `roles` table. Do not add `guard_name`.

### Checking Permissions

```php
// Permission is checked automatically via CheckRolePermission middleware
// Route name must match permission string exactly:
// Route: admin.users.index → Permission: admin.users.index

// SuperAdmin bypasses all permission checks automatically
// AdminType::SUPER_ADMIN skips CheckRolePermission middleware

// For routes excluded from permission checks:
// See exceptedRoutesFromRoles() in CheckRolePermission middleware
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
| Use policies | Don't hardcode permissions |

---

## Controller Rules

- **Never handle permissions manually** in controllers
- **Use middleware** for route protection
- **Use policies** for resource authorization
- **Keep auth logic in services** - don't put in controllers
- **Never bypass CheckRolePermission middleware** without explicit confirmation
- **Route name = permission string** — always verify this mapping

---

## Common Auth Patterns

### Login with Password
```
1. FormRequest validates email/password
2. Service checks credentials
3. Service creates Sanctum token
4. Controller returns token
```

### Login with OTP
```
1. FormRequest validates phone
2. Service generates OTP
3. Service sends OTP (SMS/Email)
4. User submits OTP
5. Service verifies and creates token
```

### Role-based Access
```
1. Middleware checks role
2. Policy checks permission on resource
3. Service checks permission for business logic
```

---

## Completion Standard

Auth implementation is NOT complete until:

- [ ] Routes properly protected with middleware
- [ ] Form Request validates auth input
- [ ] Service handles auth logic (not controller)
- [ ] Policies defined for resources
- [ ] Permissions checked in services
- [ ] Passwords properly hashed
- [ ] Rate limiting implemented
- [ ] Token generation follows Sanctum pattern

---

## Output Format

- Auth setup (guards, providers)
- Middleware configuration
- Policy structure
- Permission structure
- Service methods for auth operations
- Example usage in controller/Blade