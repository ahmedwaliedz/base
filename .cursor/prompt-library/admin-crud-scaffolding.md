# Admin CRUD Module – AI Scaffolding (System Compatible)

## Purpose

Generate a complete Admin CRUD module fully aligned with the project architecture, rules, and development system.

This includes:

* migration
* model
* form requests (validation)
* service classes (business logic)
* controller (thin)
* views
* routes
* translations
* seeder + factory
* optional export
* optional statistics

---

## Mandatory Workflow

1. **Step 1 – Collect Inputs (Questions Only)**

   * Ask all required questions
   * Do NOT generate code

2. **Step 2 – Create Execution Plan**

   * Define:

     * module structure
     * files to create/update
     * mapping to layers (DB → Service → Controller → UI/API)

3. **Step 3 – Generate Code**

   * Create full implementation aligned with:

     * rules
     * skills
     * architecture

---

## Core Architecture Rules (MANDATORY)

* Validation MUST be in Form Requests
* Business logic MUST be in Service classes
* Controllers MUST be thin (request → service → response)
* Blade views MUST be presentation-only
* API responses MUST follow project response traits
* Do NOT place logic in controllers or Blade
* Reuse existing project structure and patterns

---

## Step 1 – Required Inputs (Questions)

### A) Module Definition

* What is the model name? (StudlyCase)
* What is the Arabic name (singular)?
* What is the Arabic name (plural)?

---

### B) Columns (Single Source of Truth)

Provide all columns:

Format:
[
{"name":"title","type":"string","required":true},
{"name":"image","type":"file","required":false},
{"name":"status","type":"enum","values":["active","inactive"]}
]

Rules:

* ALL layers MUST be derived from these columns
* No extra fields allowed

---

### C) Features

* Does this module use:

  * soft deletes? (true/false)
  * multi-language? (true/false)
  * file uploads? (true/false)
  * map/location? (true/false)
  * statistics? (true/false)
  * export? (true/false → which types: csv/xlsx/pdf)

---

### D) CRUD Scope

For each component:

* Model → Create / Exists / Skip
* Migration → Create / Exists / Skip
* Factory → Create / Exists / Skip
* Seeder → Create / Exists / Skip
* Form Requests → Create / Exists / Skip
* Service → Create / Exists / Skip
* Controller → Create / Exists / Skip
* Views → Create / Exists / Skip
* Routes → Create / Exists / Skip
* Translations → Create / Exists / Skip

---

### E) Sidebar

* Add to sidebar? (true/false)
* Inside group? (true/false)
* Sidebar config

---

### F) Export (if enabled)

* Which columns to export?
* Translation keys for headers

---

### G) Statistics (if enabled)

* What cards?
* What charts?

  * type (pie/bar/donut)
  * data source (by status / by date)

---

## Step 2 – Execution Plan

The plan MUST include:

* Module name
* File tree
* Layer mapping:

  * Migration
  * Model
  * Form Requests
  * Service
  * Controller
  * Views
* Create / Update / Skip per file
* Dependencies
* Execution order

---

## Step 3 – Code Generation Rules

### 1. Migration

* Derived ONLY from columns
* Include:

  * types
  * nullable
  * defaults
  * foreign keys

---

### 2. Model

* fillable from columns
* casts from types
* relationships defined
* no business logic

---

### 3. Form Requests

* StoreRequest
* UpdateRequest
* rules from columns
* no validation in controller

---

### 4. Service Layer (MANDATORY)

Create service class extending CrudBaseService:

Example:

```php
// app/Services/Admin/<Model>Service.php
class <Model>Service extends CrudBaseService
{
    // Business logic here
    // Inherits: index, store, update, delete from CrudBaseService
    // Override or add methods as needed

    public function store(array $data): Model
    {
        return DB::transaction(fn () => <Model>::create($data));
    }
}
```

Rules:

* Extend CrudBaseService for standard CRUD
* All business logic in service, not controller
* Reusable and testable

---

### 5. Controller

Rules:

* thin controller only
* no business logic
* no validation logic

Example:

```php
// Controller extends AuthenticatableBaseController or AdminBaseController
// Service is injected via parent::__construct($service)

public function store(StoreRequest $request)
{
    $data = $this->service->store($request->validated());

    return redirect()->route('admin.<model>.index');
}
```

---

### 6. Views

Rules:

* no DB queries
* no business logic
* only render data

Special:

* file inputs at top
* statistics cards dynamic
* charts animated

### CSS/Styling Checklist for Generated Views

- [ ] Action buttons use section-specific CSS classes: `{section}-action-view`, `{section}-action-edit`, `{section}-action-delete`, `{section}-action-restore` (with base `{section}-action-btn`)
- [ ] Color source: view (blue via `[class*="-action-view"]`), edit (green), delete (red), restore (teal)
- [ ] Show page: header + 4 stat cards + profile card (left, 4 cols) + details card (right, 8 cols)
- [ ] Detail rows use `@include('admin.{section}.parts.detail-row')` partial
- [ ] Fallback for empty values uses `&mdash;` HTML entity
- [ ] Form labels are plain keys (e.g. `'name'`), NOT `__('admin/main.name')`
- [ ] Form uses `admins-form-section` divs for field grouping
- [ ] File uploads at top of create/edit forms
- [ ] All UI text uses `__()` with `admin/main` or `admin/inputs` keys
- [ ] Stat cards use color variants: `bg-primary`, `bg-success`, `bg-info`, `bg-warning`
- [ ] Soft-delete handling: check `deleted_at`, show restore button, add `table-danger` class
- [ ] Reference [`.cursor/styles/admin-ui-standards.md`](../styles/admin-ui-standards.md) for full design patterns

---

### 7. Routes

* follow project naming
* use route groups
* use middleware if needed

---

### 8. Translations

* admin/routes
* admin/inputs
* admin/main
* all keys must exist

---

### 9. Seeder

Rules:

* use factory
* Arabic locale must be real Arabic
* follow existing pattern

---

### 10. Export

Rules:

* only selected columns
* headers translatable
* controller export method required

---

### 11. Statistics

Rules:

* dynamic cards
* animated
* charts inside collapsible section (الرسوم البيانيه)
* data from controller

---

## Rules Enforcement

* Do NOT use Service-less architecture
* Do NOT put logic in controllers
* Do NOT invent columns
* Do NOT skip validation layer
* Do NOT break project naming
* Do NOT skip documentation if API exists

---

## Output Format

* Module summary
* File tree
* Full code
* Updated files (if exists)
* Changes list
* Notes

---

## Completion Standard

The CRUD is NOT complete unless:

* migration is correct
* model is correct
* validation exists
* service layer implemented
* controller is thin
* views clean
* routes working
* translations complete
* seeder works
* export works (if enabled)
* statistics work (if enabled)
* everything follows project rules
