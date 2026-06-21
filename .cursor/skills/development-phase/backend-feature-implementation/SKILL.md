---
name: backend-feature-implementation
description: Implement backend behavior using services, Form Requests, thin controllers, and models. Trigger for business logic, API backends, or service changes.
---

# Backend Feature Implementation Skill

## Purpose

Implement backend logic following Laravel architecture rules: thin controllers, business logic in services, validation in Form Requests.

---

## When to Use

- Building backend feature logic
- Creating new API endpoints
- Implementing business operations
- Adding new service methods

---

## Process

### Step 1: Validation (Form Request)

- Create Form Request in `app/Http/Requests/`
- Define all validation rules
- Handle custom validation logic
- **Never validate in controller**

### Step 2: Service Layer

- Create or extend Service in `app/Services/Admin/` or `app/Services/{Domain}/` (e.g., `app/Services/Countries/`)
- Implement business logic
- Keep service methods focused and single-purpose
- Use `CrudBaseService` for CRUD operations
- **All business logic goes here**

### Step 3: Controller (Thin)

- Receive Form Request
- Call Service method(s)
- Return response (view or JSON)
- **No business logic in controller**
- **No validation in controller**
- **Only orchestration**

### Step 4: Model Interaction

- Use models for data operations
- Define relationships in models
- Use scopes for common queries
- Avoid raw queries when Eloquent works

### Step 5: Error Handling

- Throw exceptions for errors.
- Use custom exception classes.
- Return consistent error responses.
- Distinguish admin-controller behavior from API-controller behavior:
  - **Admin CRUD controllers:** follow the existing `AdminBaseController` pattern. Catch expected `ServiceException` or `ModelNotFoundException` only where the base/project pattern requires transforming them into shared responses. Log unexpected failures safely and use existing shared response helpers. Prefer inheriting existing base behavior over duplicating catches in every controller.
  - **API controllers:** do not add broad controller-level catch blocks by default. Allow expected exceptions to reach the centralized API exception renderer in `bootstrap/app.php` when that is the established project path. Catch only errors the controller can genuinely recover from or translate intentionally. Never expose raw exception messages or stack traces.

---

## Project Service Structure

### CRUD Services
```
app/Services/Admin/Base/CrudBaseService.php
app/Services/Admin/{Module}Service.php  // extends CrudBaseService
```

### Auth Services
```
app/Services/Admin/Auth/LoginService.php
app/Services/Auth/AuthService.php
```

### Export Services
```
app/Services/Admin/Export/ExportService.php
app/Services/Admin/Export/Strategies/ExcelExporter.php
```

---

## Examples

See [`references/examples.md`](references/examples.md) for concrete Form Request, service, controller, model, error handling, filter, and relation examples.

---

## Rules Enforcement

| Layer | Responsibility |
|-------|----------------|
| Controller | Orchestration only |
| Form Request | Validation |
| Service | Business logic |
| Model | Data access & relationships |

---

## Completion Standard

Backend implementation is NOT complete until:

- [ ] Validation in Form Request (not controller)
- [ ] Business logic in Service (not controller)
- [ ] Controller is thin (only orchestration)
- [ ] Model relationships defined
- [ ] Error handling in place
- [ ] Follows project patterns (CrudBaseService, etc.)
- [ ] Tests added for service layer

---

## Output Format

- Form Request classes
- Service methods
- Controller methods
- Model changes
- Error handling approach
- Any patterns to follow