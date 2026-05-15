# Feature to Module Execution Skill

## Purpose

Map a feature requirement to an existing or new module, then execute the implementation in proper layer order.

---

## When to Use

- After feature analysis is complete
- When implementing a new feature or extending existing module
- When deciding between creating new module vs extending current

---

## Process

### Step 1: Identify Target Module

Check if feature extends existing module:

**Existing Admin Services:**
- `app/Services/Admin/CountryService.php`
- `app/Services/Admin/UserService.php`
- `app/Services/Admin/AdminService.php`
- `app/Services/Admin/RoleService.php`
- `app/Services/Admin/Settings/SettingService.php`

**Existing API Services:**
- `app/Services/Auth/AuthService.php`
- `app/Services/Otp/OtpService.php`
- `app/Services/Countries/CountryService.php`
- `app/Services/Cities/CityService.php`

**Decision:**
- **Extend existing?** → Feature fits current module
- **Create new module?** → Feature is new domain

---

### Step 2: Define Scope

- **Entities involved:** What data is affected?
- **Actions:** CRUD operations or workflow?
- **Delivery:** API only? UI only? Both?

---

### Step 3: Map Feature to Layers

For each layer, determine what's needed:

| Layer | If Needed |
|-------|-----------|
| Database | Migration in `database/migrations/` |
| Model | New or existing in `app/Models/` |
| Form Request | In `app/Http/Requests/` |
| Service | Extend existing or create new in `app/Services/` |
| Controller | New method or new controller |
| Routes | New routes in `routes/admin.php` or `routes/api.php` |
| Views | New Blade files in `resources/views/admin/` |

---

### Step 4: Ensure Architecture Compliance

Before proceeding, verify:

| Rule | Check |
|------|-------|
| Validation | Use Form Request, not controller |
| Business Logic | In Service, not Controller |
| Thin Controller | Only orchestration |
| Blade | Presentation only, no logic |
| API Response | Use response traits |

---

### Step 5: Prepare Execution Plan

List implementation steps in order:

```
1. Create migration (if new table)
2. Create/Update Model
3. Create Form Requests (Store, Update)
4. Create/Extend Service
5. Add Controller methods
6. Add Routes
7. Create Views (if UI)
8. Add Translations
9. Add Tests
```

---

## Decision Framework

### Extend Existing Module When:
- Same entity type (e.g., adding "status" to existing "posts")
- Same domain (e.g., "categories" extends "products" in e-commerce)
- Related functionality (e.g., "comments" related to "posts")

### Create New Module When:
- New entity type (e.g., "products" vs "categories")
- Different domain (e.g., "analytics" vs "products")
- Standalone functionality (e.g., "settings" separate from "products")

---

## Execution Order

Always implement in this order:

1. **Database** → Migration first
2. **Model** → Then model
3. **Form Request** → Then validation
4. **Service** → Then business logic
5. **Controller** → Then orchestration
6. **Routes** → Then routing
7. **Views** → Then UI
8. **Translations** → Then i18n
9. **Tests** → Then tests

---

## Example: Adding "Export" to Products

**Step 1: Identify Target**
- Existing module: ProductService
- Extend with export functionality

**Step 2: Scope**
- Entities: Products
- Actions: Export to Excel, PDF, CSV, Print
- Delivery: Admin UI + API option

**Step 3: Map Layers**
- Service: Add export methods to ProductService (or use ExportService)
- Controller: Add export endpoint
- Routes: Add export route
- Views: Add export button on index

**Step 4: Architecture Check**
- Validation: N/A (no input needed) or use FormRequest
- Business Logic: In ProductService or ExportService
- Controller: Thin, just call service

**Step 5: Execution Plan**
1. Use existing ExportService
2. Add export method to ProductService
3. Add controller method
4. Add route
5. Add button to Blade

---

## Output Format

- **Target Module:** Existing name or "New Module: {name}"
- **Feature Scope:** Entities, Actions, Delivery type
- **Layer Mapping:** What needs to change in each layer
- **Execution Plan:** Ordered step-by-step list

---

## Completion Standard

Feature mapping is NOT complete until:

- [ ] Target module identified (extend vs create)
- [ ] Scope defined (entities, actions, delivery)
- [ ] All layers mapped (what's needed where)
- [ ] Architecture compliance verified
- [ ] Execution plan ordered correctly

---

## Key References

- **Base Services:** `app/Services/Admin/Base/CrudBaseService.php`
- **Export Service:** `app/Services/Admin/Export/ExportService.php`
- **Form Requests:** `app/Http/Requests/`
- **Admin Controllers:** `app/Http/Controllers/Admin/`
- **API Controllers:** `app/Http/Controllers/Api/V1/`