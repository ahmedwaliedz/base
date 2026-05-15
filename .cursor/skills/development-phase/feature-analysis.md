# Feature Analysis Skill

## Purpose

Analyze a feature requirement before implementation to identify what's needed, what will be affected, and how to implement it.

---

## When to Use

- At the start of any new feature implementation
- When extending existing functionality
- When building APIs, UI pages, or backend logic
- After receiving a feature request from user

---

## Process

### Step 1: Define the Goal

- What is the feature trying to accomplish?
- Who will use it?
- What problem does it solve?

### Step 2: Identify Impacted Modules

- Check `app/Services/Admin/` for existing services
- Check `app/Services/Api/` for API services
- Check `app/Models/` for existing models
- Determine if new module needed or existing can be extended

### Step 3: Identify Data/Schema Needs

- New table or column needed?
- Existing schema changes required?
- Multilingual support needed?
- File/media handling required?

### Step 4: Identify Backend Changes

- New Form Request in `app/Http/Requests/`?
- New Service in `app/Services/Admin/` or `app/Services/Api/`?
- Controller changes in `app/Http/Controllers/Admin/` or `app/Http/Controllers/Api/`?

### Step 5: Identify Frontend Changes

- New Blade views in `resources/views/admin/`?
- New routes in `routes/admin.php` or `routes/api.php`?
- UI components needed?

### Step 6: Identify Validation

- What validation rules needed?
- Use Form Request for validation
- Handle edge cases

### Step 7: Propose Implementation Steps

- List the specific files to create/modify
- Define order of implementation
- Note any dependencies

---

## Project-Specific Service Patterns

| Service Type | Location | Base Class |
|--------------|----------|------------|
| Admin CRUD | `app/Services/Admin/` | `CrudBaseService` |
| Auth | `app/Services/Admin/Auth/` | `AuthenticatableBaseService` |
| Export | `app/Services/Admin/Export/` | `ExportService` |
| API | `app/Services/Api/` | - |

---

## Key Questions to Answer

### Type of Feature
- **API-only?** → API endpoints, resources, validation
- **UI-only?** → Blade views, controller, routes
- **Both?** → Full stack (API + UI)

### New or Extension
- **New module?** → Create Service, Controller, Model, etc.
- **Extension?** → Extend existing service, add routes

### Architecture Requirements
- Needs Form Request validation?
- Needs Service class for business logic?
- Needs database migration?
- Needs translations?
- Needs export functionality?
- Needs file uploads?

---

## Output Format

```
## Feature Analysis

### Goal
[What feature accomplishes]

### Affected Areas
- Services: [list]
- Controllers: [list]
- Models: [list]
- Views: [list]
- Routes: [list]

### Data / Schema
- New tables: [list]
- Schema changes: [list]

### Backend
- Form Requests: [list]
- Services: [list]
- Controllers: [list]

### Frontend
- Views: [list]
- Components: [list]

### Validation
- [list of validation rules needed]

### Edge Cases
- [list of edge cases to handle]

### Implementation Plan
1. [Step 1]
2. [Step 2]
...
```

---

## Completion Standard

A feature analysis is NOT complete until:

- [ ] Goal clearly defined
- [ ] All affected areas identified
- [ ] Schema needs determined
- [ ] Backend changes mapped
- [ ] Frontend changes mapped
- [ ] Validation requirements listed
- [ ] Edge cases considered
- [ ] Implementation steps defined

---

## Important Notes

- Do not skip feature analysis before implementation
- Always consider existing services before creating new ones
- Use `CrudBaseService` for admin CRUD features
- Follow architecture: Controller → Form Request → Service → Model