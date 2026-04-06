# Auth and Permissions Skill

## Purpose
Implement authentication and authorization in a secure and structured way.

---

## Process

### Authentication
- Use Laravel built-in auth (Sanctum / Passport / session)
- Protect routes using middleware

---

### Authorization
- Use Policies or Gates
- Do not mix authorization with business logic

---

### Roles & Permissions
- Define clear roles (admin, user, etc.)
- Use consistent naming
- Store roles/permissions properly

---

### Controller Rules
- Do not handle permissions manually inside controller
- Use policies or middleware

---

### Security
- Never trust client input
- Validate all auth-related data

---

## Output Format
- Auth setup
- Middleware usage
- Policies/Gates
- Role structure
- Example usage

## Policy Enforcement

- All sensitive actions must be protected by policies or permissions.
- Do not rely on frontend checks for authorization.
