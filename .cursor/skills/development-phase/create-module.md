# Create Module Skill

## Purpose

Create a complete new module with all required layers (database, model, validation, service, controller, routes, UI).

---

## When to Use

- Building a new feature that doesn't extend existing module
- Creating a new CRUD entity
- Starting from scratch on a new functional area

---

## Process

### Step 1: Determine Module Scope

- What is the module name? (e.g., "Products")
- What are the operations? (CRUD, workflow, etc.)
- Is it Admin only, API only, or both?

### Step 2: Design Database

- Create migration in `database/migrations/`
- Define columns, types, constraints
- Add foreign keys with `onDelete('cascade')`
- Consider soft deletes, multilingual, media

### Step 3: Create Model

- Create in `app/Models/`
- Define fillable, relationships
- Add traits if needed (Translatable, HasMedia)

### Step 4: Create Validation

- Create Form Request classes in `app/Http/Requests/`
- StoreRequest and UpdateRequest
- Define all validation rules

### Step 5: Create Service

- Create Service class in `app/Services/Admin/` or `app/Services/Api/`
- Use `CrudBaseService` as base for admin CRUD
- Implement all business logic

### Step 6: Create Controller

- Create in `app/Http/Controllers/Admin/` or `app/Http/Controllers/Api/V1/`
- Keep thin - only orchestrate
- Call service methods

### Step 7: Create Routes

- Add routes in `routes/admin.php` or `routes/api.php`
- Follow naming conventions: `admin.{module}.index`

### Step 8: Create UI (if Admin)

- Create views in `resources/views/admin/{module}/`
- Index, create, edit, show pages
- Use existing Blade components

### Step 9: Create Translations

- Add translation keys in `lang/ar/admin/` and `lang/en/admin/`

### Step 10: Create Factory/Seeder

- Create factory in `database/factories/`
- Create seeder if needed
- Register in DatabaseSeeder

---

## Project Structure Reference

### Admin Module Structure
```
app/
├── Http/
│   ├── Controllers/Admin/{Module}Controller.php
│   └── Requests/{Module}/
│       ├── Store{Module}Request.php
│       └── Update{Module}Request.php
├── Models/{Module}.php
Services/Admin/{Module}Service.php
routes/admin.php

resources/views/admin/{module}/
├── index.blade.php
├── create.blade.php
├── edit.blade.php
└── show.blade.php

lang/
├── ar/admin/{module}.php
└── en/admin/{module}.php

database/migrations/xxxx_create_{module}_table.php
database/factories/{Module}Factory.php
```

### API Module Structure
```
app/
├── Http/
│   ├── Controllers/Api/V1/{Module}Controller.php
│   └── Requests/Api/{Module}Request.php
├── Models/{Module}.php
├── Services/Api/{Module}Service.php
routes/api.php

database/migrations/xxxx_create_{module}_table.php
```

---

## Naming Conventions

| Element | Pattern | Example |
|---------|---------|---------|
| Model | Singular, PascalCase | `Category` |
| Controller | {Name}Controller.php | `CategoryController.php` |
| Service | {Name}Service.php | `CategoryService.php` |
| Request | Store/Update{Name}Request.php | `StoreCategoryRequest.php` |
| Migration | create_{table}_table.php | `create_categories_table.php` |
| Factory | {Name}Factory.php | `CategoryFactory.php` |
| Route | admin.category.index | `admin.category.index` |

---

## Standards

- Use Form Request for validation (never in controller)
- Business logic in Service classes
- Controllers must be thin
- Blade for presentation only
- Follow project response structure for APIs

---

## Features to Include (When Needed)

| Feature | Implementation |
|---------|----------------|
| Pagination | Controller → paginate() |
| Search/Filter | Service → applyFilters() |
| Sort | Request → sortBy, sortOrder |
| Export | Use ExportService |
| Statistics | Service → getStatistics() |
| Soft Deletes | Migration → softDeletes() |
| Translations | Astrotomic Translatable |
| Media | Spatie Media Library |

---

## Completion Standard

A module is NOT complete until:

- [ ] Migration runs without errors
- [ ] Model relationships defined
- [ ] Form Requests handle validation
- [ ] Service contains business logic
- [ ] Controller is thin
- [ ] Routes registered
- [ ] UI works (if admin)
- [ ] Translations complete
- [ ] Tests added
- [ ] Architecture rules followed

---

## Output Format

- Module name and purpose
- File tree structure
- Each layer's implementation summary
- Any deviations from standard pattern