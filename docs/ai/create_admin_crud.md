# Admin CRUD Module – AI Scaffolding Template

When this doc is used, **the first response MUST be questions only (Step 1)**. Do not generate code until the user has provided all required inputs and the plan (Step 2) has been confirmed.

**Default reference implementation:** **`users`** admin CRUD (see [Reference CRUD – Users](#reference-crud--users) below). Match file layout, naming, `AdminBaseController` + Service layer, Blade components (`x-table.*`), routes in `routes/admin.php`, and `config/sidebar_routes.php` unless the user explicitly chooses another reference entity.

**Visual/UI parity (mandatory):** All **styles and structure** for **tables**, **statistics** (cards + loaders), **toolbar buttons**, and **form inputs** must be taken from the chosen **`{{REFERENCE_CRUD_ENTITY}}`** (default **users**). Reuse the same Blade layout parents (`admin.layouts.crud.index`, `admin.layouts.crud.table`, create/edit/show partials), the same **`x-table.*`** components with the same prop patterns, and the same Bootstrap / card / datatable classes as the reference. Do **not** introduce alternate markup or ad hoc CSS for those surfaces unless the reference module already does.

---

## Mandatory Workflow

1. **Step 1 – Collect inputs:** Ask every question in this doc. Do not generate code until Step 1 is complete.
2. **Step 2 – Create plan:** From the collected inputs, produce a plan: file tree, which components are Create/Exists/Skip, and for Exists list the files to patch and what will change.
3. **Step 3 – Create section:** Generate full code for every Create component and full updated content + CHANGES for every Exists component.

### Step 1 – Required prompts (do not skip)

Ask clearly and separately:

1. **Statistics on index:** “Do you want **statistics cards** on the index page (totals, active/blocked, today, etc.)?” → `{{INDEX_STATISTICS}}`
2. **Diagrams / charts on index:** “Do you want **diagrams or charts** (الرسوم البيانيه / collapsible charts section, pie/bar/donut, etc.) on the index page?” This is **independent** of statistics cards → `{{INDEX_DIAGRAMS}}` (alias: `STATISTICS_CHARTS` when charts are used)
3. **Sidebar display mode (`SIDEBAR_DISPLAY_MODE`) — always ask explicitly:** “Should the menu entry be a **single direct link** or a **dropdown** (parent with optional children)?” → `{{SIDEBAR_DISPLAY_MODE}}` (`single` | `dropdown`). **Never infer** this from other answers; if `ADD_TO_SIDEBAR=true`, the user must choose. If `ADD_TO_SIDEBAR=false`, record as N/A.
4. **Show page – related data:** “For the show page, list every **relation** to expose. Should related data appear as **quick stat tiles**, **tables**, or **both**?” → `{{SHOW_RELATED_PRESENTATION}}`

---

## Reference CRUD – Users

Use these paths and patterns when generating a new admin CRUD unless overrides are provided.

| Area | Reference |
|------|-----------|
| Routes | `routes/admin.php` — resource route + extras (`destroyAll`, `restore`, `statistics`, optional `diagrams`, entity-specific actions like `switchBlock`) |
| Base controller | `App\Http\Controllers\Admin\AdminBaseController` — `index`, `create`, `store`, `edit`, `update`, `show`, `destroy`, `destroyAll`, `restore`, export via `?export=` |
| Authenticatable entities | `AuthenticatableBaseController` + `AuthenticatableBaseService` when the model supports block/unblock like users |
| Service | `App\Services\Admin\UserService` extends `AuthenticatableBaseService` → `CrudBaseService` — implements `createVars()` / `editVars()`, optional `indexVars()` |
| Model | `App\Models\User` — `RELATIONS`, `EXPORT_COLUMNS`, `FILES`, `UPLOAD_DIRECTORY`, search scope for filters |
| Index view | `resources/views/admin/users/index.blade.php` extends `admin.layouts.crud.index`; uses `<x-table.statistics>`, `<x-table.buttons>`, `<x-table.filter>`, `<x-table.table>` |
| Statistics partial + route | `UserController::statistics()` returns `admin.users.parts.statistics`; `statsUrl` in index pushes `stats.js` |
| Table partial | `resources/views/admin/users/table.blade.php` loaded via AJAX from `AdminBaseController::index` |
| Show view | `resources/views/admin/users/show.blade.php` extends `admin.layouts.crud.show` |
| Sidebar | `config/sidebar_routes.php` — entry key under `admin` (e.g. `users`) with `has_child`, `icon`, optional `group`, `childes` |
| Table AJAX + skeleton | `public/style/admin/custom-js/admin-table.js` — `showTableLoader` / `hideTableLoader` / `loadTable`; table partial rows use class **`data-rows`** (removed on reload). Index uses `<x-table.table>` which embeds a **`.table-loader`** skeleton row whose column layout mirrors `:headers`, `:hasCheckbox`, `:hasActions` |

---

## Critical Rules

1. **Only use passed columns:** Build CRUD (migration, model fillable, form fields, table columns, validation, factory, export) strictly from the columns provided in `{{COLUMNS}}`. Never infer, assume, or add extra columns beyond what was explicitly listed.
2. **Controller + Service layer (project standard):** Implement a **Service** class extending `CrudBaseService` (or `AuthenticatableBaseService` when applicable). Put query building, filtering, `indexVars` / `createVars` / `editVars` / `showVars`, export, and transactional persistence in the Service. The **Controller** extends `AdminBaseController` (or `AuthenticatableBaseController`) and stays thin: delegate to the service, return views/responses/JSON. **Do not** duplicate large query blocks in Blade.
3. **Controllers pass everything to views:** Every variable a Blade view needs must be passed from the Controller/Service (`showVars`, `edit`, etc.). Blade files must NEVER run queries, call model scopes, or access DB directly. Use `compact()` or explicit `->with()` / merged arrays from the service.
4. **File inputs render at the top of forms:** In create and edit views, file/image upload fields must appear BEFORE all other form fields (text, select, textarea, etc.).
5. **Export support:** Use the model’s `EXPORT_COLUMNS` (label keys) and `CrudBaseService::export()` / `ExportService` like existing admin CRUD. Ask for export types and columns; respect `AdminBaseController` export trigger (`?export=` on index request).
6. **Route translations must be complete:** When adding Admin routes translations, add **all** route keys for the section (index, create, store, update, edit, show, destroy, delete_all/destroyAll, export, restore, statistics, diagrams, and any custom actions) in both `lang/ar/admin/routes.php` and `lang/en/admin/routes.php`. Follow the same structure as `users`, `admins`, `roles`.
7. **Translations review (inputs + pages):** After implementation, **audit every user-facing string** on the new module against language files. Requirements:
   - **`lang/*/admin/inputs.php`:** Every form label, placeholder, and help text key used in create/edit (and any custom partials) exists in **both AR and EN** with correct copy.
   - **`lang/*/admin/main.php`:** Every table header, button label, empty state, statistics label, and shared UI string referenced via `__('admin/main....')` exists in **both** locales.
   - **`lang/*/admin/routes.php`:** Full nested section for the entity (see rule 6); breadcrumbs/sidebar titles must resolve.
   - **`lang/*/admin/validation.php`** and/or root **`lang/*/validation.php`:** Attribute names and custom rule messages used by Store/Update requests are defined where the project expects them.
   - **Blade `@section('title')` / headings:** Any literal or `__()` keys must have matching translations; no orphaned keys and no English-only or Arabic-only halves unless the product intentionally skips a locale (default here: **both**).
8. **Seeder Arabic locale:** For entities with `MULTILANG=true`, the Seeder MUST fill translatable columns per locale. For the **ar** locale, all translatable field values MUST be real **Arabic** text, not English or placeholder strings. Use Faker with `ar_SA` or explicit Arabic strings.
9. **Statistics / diagrams behavior:** If `INDEX_STATISTICS` or `INDEX_DIAGRAMS` is true, follow the **Users** pattern: statistics cards via dedicated route + partial when applicable, `crud-stats` CSS, and optional ApexCharts (already included in `admin.layouts.crud.index`). Animate stat/charts consistently with existing assets (`stats.js`, ApexCharts config). Do not ship completely static dashboards when the user asked for statistics or diagrams.

---

## Table skeleton loader (post-change verification)

When the index **table columns**, **checkbox column**, or **actions column** change, **re-verify** the lazy-load UX:

1. **`resources/views/admin/{entity}/index.blade.php`** — `<x-table.table>` props `:headers`, `:hasCheckbox`, and `:hasActions` must match the real table: same column count/order as the AJAX partial. The component computes skeleton **`colspan`** and skeleton cells from these props (`resources/views/components/table/table.blade.php`).
2. **`resources/views/admin/{entity}/table.blade.php`** (or equivalent partial returned by `AdminBaseController::index` for AJAX) — Each data row must keep **`tr.data-rows`** so `admin-table.js` can remove old rows before showing the skeleton; follow `admin.layouts.crud.table` + reference entity row structure.
3. **`hideTableLoader` flow** — Injected HTML is appended to **`.append-page-content`**; ensure the partial still supplies the same wrapper row structure the reference uses so the skeleton hides cleanly and pagination/scripts keep working.
4. **Statistics cards loader** — If `<x-table.statistics>` is used, align `:loaderCards` and card layout with the reference (e.g. users) so placeholder count matches the final grid.

---

## 1) ALL INPUTS (collect everything first, code later)

**Ask any questions** when placeholders are ambiguous: e.g. chart type for diagrams, which columns to export, whether to show the map on the show page when lat/lng exist, or which diagram dimensions (by status, by date) to use.

### A) CRUD Scaffolding Mode (Component Checklist)

For every component below, ask: **What is the status? [Create | Exists | Skip]**

- **Create:** generate from scratch
- **Exists:** review + update to match spec + output full updated file + list changes
- **Skip:** do not generate

| # | Component | Question |
|---|-----------|----------|
| 1 | Model | What is the status? Create / Exists / Skip |
| 2 | Migration | What is the status? Create / Exists / Skip |
| 3 | Factory | What is the status? Create / Exists / Skip |
| 4 | Seeder | What is the status? Create / Exists / Skip |
| 5 | FormRequests (Store + Update) | What is the status? Create / Exists / Skip |
| 6 | Service (`App\Services\Admin\{Model}Service`) | What is the status? Create / Exists / Skip |
| 7 | Controller | What is the status? Create / Exists / Skip |
| 8 | Views: index, table partial, create, edit, show (+ optional statistics partial) | What is the status? Create / Exists / Skip |
| 9 | Routes (`routes/admin.php` or project admin routes file) | What is the status? Create / Exists / Skip |
| 10 | Sidebar (`config/sidebar_routes.php`) | What is the status? Create / Exists / Skip |
| 11 | Admin routes translations (`admin/routes`) | What is the status? Create / Exists / Skip |
| 12 | Admin inputs translations (`admin/inputs`) | What is the status? Create / Exists / Skip |
| 13 | Admin main translations (`admin/main`) | What is the status? Create / Exists / Skip |
| 14 | Admin validation translations (`admin/validation` / `validation.php`) | What is the status? Create / Exists / Skip |
| 15 | Permission seeder / permissions (`database/seeders/Admin/PermissionSeeder.php` or project equivalent) | What is the status? Create / Exists / Skip |
| 16 | DatabaseSeeder registration | What is the status? Create / Exists / Skip |
| 17 | Export (via model `EXPORT_COLUMNS` + service) | What is the status? Create / Exists / Skip |
| 18 | Optional: Policies, Notifications, Mailables | What is the status? Create / Exists / Skip |

---

### B) Project Paths / References

- What is the Laravel version? `{{LARAVEL_VERSION}}` *(default: 12)*
- Which existing CRUD entity/folder should be used as a style reference? `{{REFERENCE_CRUD_ENTITY}}` *(default: **users**)*

**Core paths (defaults for this codebase):**

- What is the admin routes file path? `{{ADMIN_ROUTES_FILE}}` *(default: **routes/admin.php**)*
- What is the sidebar routes file path? `{{SIDEBAR_ROUTES_FILE}}` *(default: **config/sidebar_routes.php**)*
- What is the DatabaseSeeder path? `{{DATABASE_SEEDER_FILE}}` *(default: database/seeders/DatabaseSeeder.php)*

**Translation paths:**

- What is the admin routes translation file path (AR)? `{{ADMIN_ROUTES_LANG_PATH_AR}}` *(default: lang/ar/admin/routes.php)*
- What is the admin routes translation file path (EN)? `{{ADMIN_ROUTES_LANG_PATH_EN}}` *(default: lang/en/admin/routes.php)*
- What is the admin inputs translation file path (AR)? `{{ADMIN_INPUTS_LANG_PATH_AR}}` *(default: lang/ar/admin/inputs.php)*
- What is the admin inputs translation file path (EN)? `{{ADMIN_INPUTS_LANG_PATH_EN}}` *(default: lang/en/admin/inputs.php)*
- What is the admin main translation file path (AR)? `{{ADMIN_MAIN_LANG_PATH_AR}}` *(default: lang/ar/admin/main.php)*
- What is the admin main translation file path (EN)? `{{ADMIN_MAIN_LANG_PATH_EN}}` *(default: lang/en/admin/main.php)*

**Translation strategy:**

- Should translation keys be used instead of hardcoded strings in views/menu? `{{USE_TRANSLATIONS}}` *(true | false)*
- What is the translation namespace prefix? `{{TRANS_PREFIX}}` *(default: "admin")*
- Should route translation entries be added? `{{ADD_ROUTE_TRANSLATIONS}}` *(true | false)*
- Should input translation entries be added? `{{ADD_INPUT_TRANSLATIONS}}` *(true | false)*
- Should main translation entries be added? `{{ADD_MAIN_TRANSLATIONS}}` *(true | false)*

**Route translations rule:** When `ADD_ROUTE_TRANSLATIONS=true`, you MUST add **all** route keys for the CRUD section in both AR and EN. Include custom actions (statistics, diagrams, switch-*, restore, etc.) if those routes exist. Follow the nested-array structure used for **`users`**. Do NOT add only the entity name or a single key.

---

### C) CRUD Identity

You must derive everything below dynamically from the model/class name, unless overridden.

**Required:**

- What is the model/class name (English, singular, StudlyCase)? `{{MODEL_NAME}}`
- What is the Arabic entity name (singular, for UI)? `{{ENTITY_SINGULAR_AR}}`
- What is the Arabic entity name (plural, for UI)? `{{ENTITY_PLURAL_AR}}`

**Optional overrides** (leave empty to auto-generate from MODEL_NAME):

- What is the entity plural in English? `{{ENTITY_PLURAL_EN}}` *(if empty, auto-derive)*
- What is the table name? `{{TABLE_NAME}}` *(if empty, auto-derive)*
- What is the route prefix? `{{ROUTE_PREFIX}}` *(if empty, auto-derive)*
- What is the controller namespace/path? `{{CONTROLLER_PATH}}` *(if empty, auto-derive)*
- What is the views folder? `{{VIEWS_PATH}}` *(if empty, auto-derive)*
- What is the primary key? `{{PRIMARY_KEY}}` *(default: id)*

**AUTO-DERIVATION RULES (must apply):**

Given `{{MODEL_NAME}}`:

1. `entity_singular_snake` = snake_case(MODEL_NAME) — e.g. BlogPost → blog_post
2. `entity_plural_snake` = pluralize(entity_singular_snake) — e.g. blog_posts
3. `entity_plural_en` = pluralize(MODEL_NAME) — e.g. Test → Tests
4. `table_name` = entity_plural_snake — e.g. tests
5. `route_prefix` = "admin/" + entity_plural_snake — e.g. admin/tests
6. `controller_class` = MODEL_NAME + "Controller" — e.g. TestController
7. `controller_namespace` = CONTROLLER_PATH if set else "App/Http/Controllers/Admin"
8. `views_folder` = VIEWS_PATH if set else "resources/views/admin/" + entity_plural_snake
9. `route_name_prefix` = "admin." + entity_plural_snake — e.g. admin.tests
10. `permission_prefix` = entity_plural_snake — e.g. tests

If any override placeholder is non-empty, it must replace the derived value.

**Flags:**

- Does this entity use soft deletes? `{{SOFT_DELETES}}` *(true | false)*
- Does this entity use multi-language translations for fields? `{{MULTILANG}}` *(true | false)*
- What are the supported locales (if MULTILANG=true)? `{{LOCALES}}` *(default: ["ar","en"])*

---

### D) Sidebar Registration (`config/sidebar_routes.php`)

**Mandatory question (do not skip, do not guess):** Should this menu item behave as a **single link** or a **dropdown**?

- What is the sidebar display mode? `{{SIDEBAR_DISPLAY_MODE}}` *(**single** | **dropdown**)* — **Ask the user in Step 1** whenever `ADD_TO_SIDEBAR=true`. Copy the value into `config/sidebar_routes.php` as `has_child`: **`single` → `has_child` = false**, **`dropdown` → `has_child` = true**.
  - **`single`:** one clickable entry pointing at the index route; set `has_child` => **false** (same idea as `home` in `sidebar_routes.php`).
  - **`dropdown`:** parent entry with `has_child` => **true**; fill `childes` when there are multiple child routes (sub-pages). If the product only has the index under that parent, `childes` may be empty but the UX is still “dropdown-capable” — confirm with the user.

Additional questions:

- Should this be added to sidebar_routes.php? `{{ADD_TO_SIDEBAR}}` *(true | false)*
- Is this inside a sidebar **group** (e.g. `admin_roles_management`)? `{{SIDEBAR_GROUP_KEY}}` *(string | empty)*
- Which icon HTML class? `{{SIDEBAR_ICON}}` *(e.g. `ti ti-world`)*
- Title: translation key under `admin.routes`? `{{SIDEBAR_TITLE_KEY}}`
- Permission name (if any)? `{{SIDEBAR_PERMISSION}}`

**Rule:** If `USE_TRANSLATIONS=true`, the sidebar title must use `admin.routes.*` keys like existing modules.

---

### E) Model Config

- Fillable: derived strictly from `{{COLUMNS}}` field names (never add unlisted fields).
- What are the translated fields (if MULTILANG=true)? `{{TRANSLATED_FIELDS}}` *(empty array if MULTILANG=false)*
- Casts: derived from `{{COLUMNS}}` type definitions.
- Attributes defaults: derived from `{{COLUMNS}}` default values.
- What is the upload directory constant? `{{UPLOAD_DIRECTORY}}` *(if empty, auto: "uploads/" + entity_plural_snake)*

**FILES upload config:**

What are the file upload fields? `{{FILES}}`

Format:
```
[
  {"field":"avatar","disk":"public","dir":"","mimes":"jpg,png,webp","max_kb":2048,"nullable":true}
]
```

Rule: If FILES[].dir empty => use entity_plural_snake.

**RELATIONS:**

What are the model relations? `{{RELATIONS}}` *(list relation method names to eager-load; align with `Model::RELATIONS` constant used by `CrudBaseService::show` and `edit`)*

---

### F) Columns (core data — single source of truth)

What are the columns for this CRUD? `{{COLUMNS}}`

Format: `[ ... ]`

**Rules:**
- Table headers MUST use `admin/main` translation keys and you must add them when `ADD_MAIN_TRANSLATIONS=true`.
- `{{COLUMNS}}` is the **single source of truth** for the entire CRUD. Migration columns, model fillable, form fields, validation rules, factory definitions, table columns, and export columns are ALL derived exclusively from this list.
- Do NOT add any column that is not explicitly present in `{{COLUMNS}}`.

---

### G) Index actions, **full filters**, statistics, diagrams

**Index actions:** What extra toolbar actions are needed (copy Users: notification, email, reload, export formats)? `{{INDEX_ACTIONS}}`

**Filters (mandatory breadth):** The index **must** support every filter that is meaningful for `{{COLUMNS}}` and for list UX, implemented via `<x-table.filter>` + the model’s `scopeSearch` / filter handling (see `CrudBaseService::index` + `$request->filters`).

Ask explicitly:

- Enable date range? `{{FILTER_DATE_RANGE}}` — maps to `hasStartDate` / `hasEndDate` on `<x-table.filter>` when the model has `created_at` or relevant date columns.
- Enable ordering? `{{FILTER_ORDER_BY}}` — `hasOrderBy`
- Show “retrieve deleted” filter when soft deletes? `{{FILTER_RETRIEVE}}` — `hasRetrieve` (only if entity is retrievable / soft-deleted like users)
- Per-field filters: `{{INDEX_FILTERS_CONFIG}}` — array of `{ type, name }` for **every** filterable field derived from `{{COLUMNS}}` (text fields → `text`; boolean/status → `select` or appropriate type; foreign keys → `select` with options from `createVars`/`indexVars` if needed). **Goal:** no arbitrary limit — include **all possible** filters consistent with the schema and search implementation.

**Statistics (index):**

- Do you want **statistics cards** on the index page? `{{INDEX_STATISTICS}}` *(true | false)*
- If true: describe cards and data sources? `{{STATISTICS_CARDS}}` *(or follow Users: total, active/inactive split, today/new — adapted per entity)*
- Implement using the same approach as Users when applicable: optional `GET .../statistics` route returning a Blade partial, `statsUrl` + `stats.js`, `<x-table.statistics>`.

**Diagrams / charts (index) — ask separately from statistics cards:**

- Do you want **diagrams or charts** on the index (collapsible section, pie/bar/donut, ApexCharts)? `{{INDEX_DIAGRAMS}}` *(true | false)* — same intent as `{{STATISTICS_CHARTS}}`
- If true: `{{DIAGRAM_ITEMS}}` or `{{STATISTICS_CHART_ITEMS}}` — `[{"type":"pie|bar|donut","label_key":"admin/main...","data_key":"...","colors":[...]}]`
- Optional route: `GET admin/{entity}/diagrams` when the project uses lazy-loaded chart HTML/JSON (see `routes/admin.php` for `users.diagrams`).

**الرسوم البيانيه rules (when diagrams are enabled):**

1. Use `admin/main.statistics_charts` / `admin/main.diagrams*` translation keys where applicable.
2. Charts should use ApexCharts (bundled in CRUD layout) with sensible animation (not a static PNG substitute).
3. Controller or service prepares datasets; Blade only renders.

**Rule:** If `INDEX_STATISTICS=true`, wire statistics the same way as **Users** unless the user requests a simpler inline pass. If `INDEX_DIAGRAMS=true`, add the collapsible charts section + data endpoints as agreed in `DIAGRAM_ITEMS`.

---

### G2) Show page — related relations and “fast” overview

**Goal:** On **show**, present **all** related data the admin should see: not only scalar columns but **relations** declared in `{{RELATIONS}}`.

Ask:

- How should related data be presented? `{{SHOW_RELATED_PRESENTATION}}` *(**stats** | **tables** | **both**)*
  - **stats:** compact metric cards or summary rows (counts: e.g. number of orders, comments, children).
  - **tables:** sortable/paginated tables for `hasMany` / `belongsToMany` when row counts can be large; for small sets, a simple table is enough.
  - **both:** counts in stat-style blocks + detailed tables below.

Implementation rules:

1. Extend `showVars()` in the Service (or override `show` pipeline) to pass:
   - The main model with `::with(Relations...)`
   - For each important relation: **counts** and, when needed, **limited collections** (e.g. latest 10) or **full paginator** passed explicitly.
2. **Never** query inside Blade; pass named variables for each block (`$relatedOrders`, `$orderStats`, etc.).
3. Align with `admin.layouts.crud.show` and **Users** show layout patterns (card + detail column).

---

### G3) Export Configuration

- Do you want export via index request? `{{ENABLE_EXPORT}}` *(true | false)* — `AdminBaseController` checks `$request->has('export')`.
- What export types? `{{EXPORT_TYPES}}` *(match project: copy/excel/pdf/word/json as supported by `ExportService`)*
- Model-defined `EXPORT_COLUMNS` lists keys and `label` translation keys — confirm subset or full `{{COLUMNS}}`.

---

### G4) Map / Location on show page

- Does this CRUD have map/location fields? `{{HAS_MAP}}` *(true | false — set true if COLUMNS include lat/lng or a map field)*
- Map provider / component: Use the project’s existing map component for read-only display when applicable.

**Rule:** If the CRUD has map/location fields, the **show** view MUST display the location as a **map** when coordinates exist, not only raw numbers.

---

### H) Form Requests

- What is the Store request class name? `{{STORE_REQUEST_CLASS}}` *(if empty, auto: Store{MODEL_NAME}Request)*
- What is the Update request class name? `{{UPDATE_REQUEST_CLASS}}` *(if empty, auto: Update{MODEL_NAME}Request)*

---

### I) Factory + Seeder + DatabaseSeeder Hook

- Should a factory be used? `{{USE_FACTORY}}` *(true | false)*
- What is the Faker locale? `{{FAKER_LOCALE}}` *(default: "ar_SA")*
- What is the Seeder class name? `{{SEEDER_CLASS}}` *(if empty, auto: {MODEL_NAME}Seeder)*
- How many rows should the seeder create? `{{SEED_ROWS}}` *(default: 100)*
- What is the DatabaseSeeder guard strategy? `{{DB_SEEDER_GUARD}}` *(none | local_only | custom)*

**Seeder translatable columns rule:** When `MULTILANG=true`, Arabic locale values must be real Arabic text.

---

## 2) Guidelines (follow strictly)

### Questions Phase
- **First response MUST be questions only:** Print the Component Checklist and ask Create/Exists/Skip for each component.
- **Always ask (explicit):**
  1. “Do you want **statistics cards** on the index page?” → `INDEX_STATISTICS`
  2. “Do you want **diagrams/charts** on the index page?” → `INDEX_DIAGRAMS` (independent question)
  3. **`SIDEBAR_DISPLAY_MODE` (mandatory when sidebar is in scope):** “Should the sidebar item be a **single direct link** or a **dropdown**?” — ask when `ADD_TO_SIDEBAR=true`; do not default silently.
  4. “On the **show** page, how should **related data** appear (**stats**, **tables**, or **both**)?” → `SHOW_RELATED_PRESENTATION`
- If `ADD_TO_SIDEBAR=true`, also confirm optional **`childes`**, **group**, **icon**, and **permission** as in [Sidebar Registration](#d-sidebar-registration-configsidebar_routesphp).
- **Filters:** Confirm **full** filter set: date range, order, retrieve (if soft deletes), and **per-column** filters for all relevant `{{COLUMNS}}`.
- **Export:** Ask for types and columns; headers use translation keys on the model export schema.
- **Map on show:** If lat/lng exist, confirm read-only map.
- **Translations:** Confirm route/input/main/validation coverage plan; after coding, run the **translations review** checklist in Critical Rules (rule 7).
- **Do not generate code until inputs are complete.**

### Code Generation Rules
- When you update an existing file, output **full updated file** + **CHANGES** list.
- Keep naming consistent with derived values unless overridden.
- Follow **`users`** (or `{{REFERENCE_CRUD_ENTITY}}`) file structure, **components**, and **CSS/classes** for tables, statistics, buttons, and inputs — see **Visual/UI parity (mandatory)** at the top of this document.
- After any table/index change, apply [Table skeleton loader (post-change verification)](#table-skeleton-loader-post-change-verification).

### Columns as Single Source of Truth
- Build everything **exclusively** from `{{COLUMNS}}` for scalar fields.

### Service + Controller
- Use **CrudBaseService** patterns: `index($request)` applies `$request->filters` + `search()`, `show()` uses `RELATIONS`, `export()` uses `EXPORT_COLUMNS`.
- Controller mirrors `AdminBaseController` / `UserController` for statistics routes when enabled.

### Blade Views Are Passive
- No DB in Blade; only render data from Controller/Service.

### Form Field Ordering
- File inputs first in create/edit.

### Index: filters + statistics + diagrams
- `<x-table.filter>`: set `hasStartDate`, `hasEndDate`, `hasOrderBy`, `hasRetrieve` as appropriate; pass a **complete** `filters` array for the entity.
- Statistics/diagrams: follow **Users** wiring when those flags are true.

### Show page
- All **relations** from `{{RELATIONS}}`: expose summary stats and/or tables per `SHOW_RELATED_PRESENTATION`. Eager-load in the service; Blade uses passed variables only.

### Route and page translations
- Add **all** route keys for the section in AR and EN (same as Critical Rules rule 6).
- After implementation, complete the **translations review** (Critical Rules rule 7): `inputs`, `main`, `routes`, `validation`, and any Blade titles/headings.

---

## 3) Quick checklist before coding

- [ ] Reference entity (default **users**) confirmed — **UI copied from that reference** (tables, statistics, buttons, inputs)
- [ ] `INDEX_STATISTICS` and `INDEX_DIAGRAMS` asked separately
- [ ] **`SIDEBAR_DISPLAY_MODE` asked** when `ADD_TO_SIDEBAR=true` (single vs dropdown; maps to `has_child`)
- [ ] Index **filters** cover all applicable column/date/order/retrieve cases
- [ ] Show page **relations** + **stats/tables/both** confirmed
- [ ] `routes/admin.php`, `config/sidebar_routes.php`, lang files scoped
- [ ] Service + `AdminBaseController` pattern agreed
- [ ] Plan to **verify skeleton loader + `data-rows`** after table partial / headers change
- [ ] Plan for **full AR/EN translation review** (inputs, main, routes, validation, page titles)
