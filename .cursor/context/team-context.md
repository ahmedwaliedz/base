# Team Context

## Purpose

Define how the team prefers to build and structure code.

---

## Development Style

- **Clean code required** - readable, well-organized
- **Small methods preferred** - single responsibility
- **Clear naming required** - descriptive, follows conventions
- **Comments when needed** - explain "why", not "what"

---

## Architecture Preferences

| Layer | Rule |
|-------|------|
| Controllers | Thin - only orchestration |
| Services | Business logic resides here |
| Form Requests | All validation |
| Models | Data & relationships only |
| Blade | Presentation only |

---

## Code Organization

### File Locations

| Type | Location |
|------|----------|
| Admin Services | `app/Services/Admin/` |
| API Services | `app/Services/{Domain}/` (e.g., `app/Services/Countries/`) — no `app/Services/Api/` directory currently exists |
| Form Requests | `app/Http/Requests/` |
| Admin Controllers | `app/Http/Controllers/Admin/` |
| API Controllers | `app/Http/Controllers/Api/V1/` |
| Admin Views | `resources/views/admin/` |
| Migrations | `database/migrations/` |

---

## API Preferences

### Response Format

Use shared response format:
```php
// Success
return $this->successResponse($data, 'Message');

// Error
return $this->errorResponse('Error message', 422);
```

### API Structure

- Version: `api/v1/`
- Resources: Use Laravel API Resources
- Errors: Consistent error structure
- Pagination: Use LengthAwarePaginator

---

## Naming Preferences

- **Use descriptive names** - `getActiveUsers()` not `getUsers()`
- **Follow project conventions** - PascalCase for classes, snake_case for files
- **Avoid abbreviations** - `country_id` not `ctry_id`
- **Use prefixes for types** - `is_active`, `has_permission`

---

## Code Review Expectations

### Must Avoid
- ❌ Duplication (extract to shared method)
- ❌ Logic in controllers (move to service)
- ❌ Validation in controllers (use Form Request)
- ❌ Logic in Blade (move to controller/service)
- ❌ Raw queries when Eloquent works
- ❌ Missing foreign key constraints

### Must Have
- ✅ Proper validation (Form Request)
- ✅ Business logic in services
- ✅ Relationships defined in models
- ✅ Indexes on frequently queried columns
- ✅ Soft deletes when needed
- ✅ Tests for complex logic

---

## Testing Preferences

### Test Coverage

| What | How |
|------|-----|
| Validation | Test Form Request rules |
| Services | Test business logic |
| APIs | Test endpoints |
| Edge cases | Test failure scenarios |

### Test Patterns

- Use factories for test data
- Use RefreshDatabase trait
- Test both success and failure
- Keep tests deterministic

---

## Documentation Expectations

- **APIs** must be documented (Postman examples)
- **Form Requests** - rules are self-documenting
- **Services** - public methods documented
- **Complex logic** - inline comments explaining "why"

---

## Common Mistakes to Avoid

| Mistake | Solution |
|---------|----------|
| Logic in Blade | Move to controller/service |
| Validation in controller | Use Form Request |
| Fat controller | Extract to service |
| Duplicate code | Extract to trait/service |
| Raw queries | Use Eloquent relationships |
| Missing indexes | Add indexes to migrations |

---

## Collaboration Rules

- Follow existing patterns before creating new ones
- Do not introduce new structure randomly
- Keep code consistent across modules
- Reuse existing services before creating new
- Document any deviations from standard patterns

---

## Important References

- **Global Rules:** [`../rules/00-global-rules.mdc`](../rules/00-global-rules.mdc)
- **Backend Rules:** [`../rules/04-backend-rules.mdc`](../rules/04-backend-rules.mdc)
- **Database Rules:** [`../rules/05-database-rules.mdc`](../rules/05-database-rules.mdc)
- **API Documentation Rules:** [`../rules/07-api-postman-mcp-documentation-rules.mdc`](../rules/07-api-postman-mcp-documentation-rules.mdc)
- **Project Context:** [`./project-context.md`](./project-context.md)
- **Technology Baseline:** [`./technology-baseline.md`](./technology-baseline.md)