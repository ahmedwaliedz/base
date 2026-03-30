# Admin CRUD Module – AI Scaffolding Template

When this doc is used, **the first response MUST be questions only (Step 1)**. Do not generate code until the user has provided all required inputs and the plan (Step 2) has been confirmed.

---

## Mandatory Workflow

1. **Step 1 – Collect inputs:** Ask every question in this doc. Do not generate code until Step 1 is complete.
2. **Step 2 – Create plan:** From the collected inputs, produce a plan: file tree, which components are Create/Exists/Skip, and for Exists list the files to patch and what will change.
3. **Step 3 – Create section:** Generate full code for every Create component and full updated content + CHANGES for every Exists component.

---

## Critical Rules

1. **Only use passed columns:** Build CRUD (migration, model fillable, form fields, table columns, validation, factory, export) strictly from the columns provided in `{{COLUMNS}}`. Never infer, assume, or add extra columns beyond what was explicitly listed.
2. **All logic lives in the Controller:** Do NOT create Service classes. All query logic, data preparation, filtering, statistics computation, and business logic must be inside the Controller methods directly.
3. **Controllers pass everything to views:** Every variable a Blade view needs must be passed from the Controller. Blade files must NEVER run queries, call model scopes, or access DB directly. Use `compact()` or explicit `->with()` to pass all data.
4. **File inputs render at the top of forms:** In create and edit views, file/image upload fields must appear BEFORE all other form fields (text, select, textarea, etc.).
5. **Export support:** Ask for export types and columns. Generate export class and controller method only from the explicitly selected export columns.
6. **Route translations must be complete:** When adding Admin routes translations, add **all** route keys for the section (index, create, store, update, edit, show, destroy, delete_all/destroyAll, and export if applicable) in both `lang/ar/admin/routes.php` and `lang/en/admin/routes.php`. Do not add only the entity name or a single key; the whole section's routes must be translated. Follow the same structure as existing sections (e.g. `admins`, `users`, `roles`).
7. **Seeder Arabic locale:** For entities with `MULTILANG=true`, the Seeder MUST fill translatable columns per locale. For the **ar** locale, all translatable field values (e.g. name, title, description) MUST be real **Arabic** text, not English or placeholder strings. Use Faker with `ar_SA` or explicit Arabic strings.
8. **Statistics animation is mandatory:** Statistics cards and charts (الرسوم البيانيه) MUST include animation. No static-only rendering is allowed.

---

## 1) ALL INPUTS (collect everything first, code later)

**Ask any questions** when placeholders are ambiguous: e.g. chart type for الرسوم البيانيه (pie/bar/donut), which columns to export and their label keys, whether to show the map on the show page when lat/lng exist, or which diagram data (e.g. by status, by date) to use in the collapsible section.

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
| 6 | Controller | What is the status? Create / Exists / Skip |
| 7 | Views: index, table partial, create, edit, show | What is the status? Create / Exists / Skip |
| 8 | Routes (web.php or admin routes file) | What is the status? Create / Exists / Skip |
| 9 | Sidebar routes / menu registration (sidebar_routes.php) | What is the status? Create / Exists / Skip |
| 10 | Admin routes translations (admin/routes) | What is the status? Create / Exists / Skip |
| 11 | Admin inputs translations (admin/inputs) | What is the status? Create / Exists / Skip |
| 12 | Admin main translations (admin/main) | What is the status? Create / Exists / Skip |
| 13 | DatabaseSeeder registration (DatabaseSeeder.php) | What is the status? Create / Exists / Skip |
| 14 | Export class (Laravel Excel / CSV / PDF) | What is the status? Create / Exists / Skip |
| 15 | Optional: Policies/Permissions hooks, Notifications, Mailables | What is the status? Create / Exists / Skip |

---

### B) Project Paths / References

- What is the Laravel version? `{{LARAVEL_VERSION}}` *(default: 12)*
- Which existing CRUD entity/folder should be used as a style reference? `{{REFERENCE_CRUD_ENTITY}}` *(default: users)*

**Core paths:**

- What is the admin routes file path? `{{ADMIN_ROUTES_FILE}}` *(default: routes/web.php)*
- What is the sidebar routes file path? `{{SIDEBAR_ROUTES_FILE}}` *(default: routes/sidebar_routes.php)*
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

**Route translations rule:** When `ADD_ROUTE_TRANSLATIONS=true`, you MUST add **all** route keys for the CRUD section in both `lang/ar/admin/routes.php` and `lang/en/admin/routes.php`. The required keys per section are: `index`, `create`, `store`, `update`, `edit`, `show`, `destroy`, `delete_all` (or `destroyAll`), and `export` (if export is enabled). Follow the same nested-array structure as existing sections (`admins`, `users`, `roles`). Do NOT add only the entity name or a single key.

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

### D) Sidebar Registration (sidebar_routes.php)

- Should this be added to sidebar_routes.php? `{{ADD_TO_SIDEBAR}}` *(true | false)*
- Is this inside a sidebar group? `{{SIDEBAR_INSIDE_GROUP}}` *(true | false)*
- If SIDEBAR_INSIDE_GROUP=true: ask user to paste sidebar_routes.php to detect groups, then ask — which group should this be placed in? `{{SIDEBAR_GROUP_SELECTED}}`
- Does this sidebar item have children? `{{SIDEBAR_HAS_CHILD}}` *(true | false)*
- If SIDEBAR_HAS_CHILD=true: what are the child items? `{{SIDEBAR_CHILDREN}}`
  Format: `[{"title_key":"admin.routes....","route":"...","icon":"...","permission":"..."}]`
- If SIDEBAR_HAS_CHILD=false: what is the sidebar item config? `{{SIDEBAR_ITEM}}`
  Format: `{"title_key":"","route":"","icon":"","permission":""}`

**Rule:** If USE_TRANSLATIONS=true, title_key must point to admin/routes keys.

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

What are the model relations? `{{RELATIONS}}` *(default: AUTO_FROM_FOREIGN_KEYS_PLUS_MANUAL)*

---

### F) Columns (core data — single source of truth)

What are the columns for this CRUD? `{{COLUMNS}}`

Format: `[ ... ]`

**Rules:**
- Table headers MUST use admin/main translation keys (admin.main.*) and you must add them when ADD_MAIN_TRANSLATIONS=true.
- `{{COLUMNS}}` is the **single source of truth** for the entire CRUD. Migration columns, model fillable, form fields, validation rules, factory definitions, table columns, and export columns are ALL derived exclusively from this list.
- Do NOT add any column that is not explicitly present in `{{COLUMNS}}`.

---

### G) Index Actions / Filters / الرسوم البيانيه (Statistics)

What are the index page actions? `{{INDEX_ACTIONS}}`

What are the filters? `{{FILTERS}}`

- Do you want to add statistics cards on the index page? `{{INDEX_STATISTICS}}` *(true | false)*
- If INDEX_STATISTICS=true: what stat cards should be displayed? `{{STATISTICS_CARDS}}`
  Format (dynamic — view loops over this array):
  `[{"label_key":"admin/main.total","value":"count","icon":"ti ti-list","color":"primary"},{"label_key":"admin/main.active","value":"active_count","icon":"ti ti-check","color":"success"},{"label_key":"admin/main.inactive","value":"inactive_count","icon":"ti ti-x","color":"danger"}]`
- Do you want a collapsible الرسوم البيانيه (charts/diagrams) section? `{{STATISTICS_CHARTS}}` *(true | false)*
- If STATISTICS_CHARTS=true: what charts should be included? `{{STATISTICS_CHART_ITEMS}}`
  Format (each with type, label_key, and data key from controller):
  `[{"type":"pie|bar|donut","label_key":"admin/main.by_status","data_key":"chart_by_status","colors":["primary","success","danger"]}]`

**الرسوم البيانيه rules (dynamic, style, animation, diagrams):**

1. **Dynamic:** Statistics MUST be driven by the `STATISTICS_CARDS` array. The index view MUST loop over the cards passed from the Controller (e.g. `$statisticsCards` with precomputed values). No hardcoded card count or labels — add/remove cards by editing the config only.
2. **Style:** Stat cards MUST use a consistent, modern card layout: icon (e.g. Tabler icons), label (translated via `label_key`), value, and optional trend. Use Bootstrap/card classes and color variants (primary, success, danger, warning, info) from each card's `color` field. Cards must be responsive (e.g. grid that stacks on small screens).
3. **Animation (mandatory):**
   - **Stat cards:** Cards MUST have animation on page load (e.g. fade-in, slide-up, or stagger delay). The numeric value MUST animate (count-up) when the card becomes visible. Use CSS transitions or a lightweight JS solution; avoid blocking the main thread.
   - **الرسوم البيانيه (charts):** When `STATISTICS_CHARTS=true`, charts inside the collapsible section MUST have draw-in / render animation (e.g. ApexCharts `chart.animations.enabled: true` with easing and speed configured, or Chart.js equivalent). Charts must animate on first open of the collapsible section, not only on page load.
   - **No static rendering:** Both stat cards and charts must always include animation. Static-only output is not allowed.
4. **Collapsible section — الرسوم البيانيه:** If `STATISTICS_CHARTS=true`, the index view MUST include a collapsible block (e.g. Bootstrap collapse or similar) that contains the chart(s). The block title/trigger MUST be labeled **"الرسوم البيانيه"** (AR) / **"Charts"** (EN), using the translation key `admin/main.statistics_charts` (add this key to `admin/main` in both ar and en when ADD_MAIN_TRANSLATIONS=true; ar value: `الرسوم البيانيه`, en value: `Charts`). The block must have accessible open/close behavior. Inside the collapse, render one diagram per `STATISTICS_CHART_ITEMS` entry. The Controller must pass the chart data (e.g. `chart_by_status` with labels and values) so the view only renders; use a chart library (e.g. ApexCharts, Chart.js) consistent with the project. Chart labels MUST use translation keys where applicable.

**Rule:** If INDEX_STATISTICS=true, the Controller `index()` method must compute and pass the statistics data (cards + optional chart datasets) to the view. The index view must render stat cards above the table and, when STATISTICS_CHARTS=true, a collapsible الرسوم البيانيه section with the diagrams.

---

### G2) Export Configuration

- Do you want to enable export? `{{ENABLE_EXPORT}}` *(true | false)*
- What export types do you need? `{{EXPORT_TYPES}}` *(options: xlsx, csv, pdf)*
- Which columns should be exported (subset of COLUMNS)? `{{EXPORT_COLUMNS}}` *(list of field names; if empty, use all COLUMNS)*
- Export column **labels** (translatable): For each export column, the header in the exported file MUST be translatable. Use the structure `[{"key":"field_name","label_key":"admin/main.field_label"}, ...]`. The `label_key` is the translation key for the column header (e.g. `admin/main.name`, `admin/main.total`). When generating the Export class or passing columns to the export layer, use `__(label_key)` for headings so the export respects the current locale.
- What is the export class name? `{{EXPORT_CLASS}}` *(if empty, auto: {MODEL_NAME}Export)*
- What is the export file name? `{{EXPORT_FILE_NAME}}` *(if empty, auto: entity_plural_snake)*

**Rules:**
- Only columns listed in `{{EXPORT_COLUMNS}}` appear in the exported file. Never add unlisted columns.
- **Export columns must be translatable:** Export file headers MUST use translation keys (e.g. `admin/main.*`). The Export class (or the code that builds headings) MUST call `__($labelKey)` (or equivalent) for each column header so that CSV/Excel/PDF headers appear in the active language. Add any new header keys to `admin/main` (or the chosen lang file) when ADD_MAIN_TRANSLATIONS=true.
- The Controller must have an `export(Request $request)` method that triggers the download.
- A dedicated route must be registered: `GET admin/{entity_plural_snake}/export` named `admin.{entity_plural_snake}.export`.
- The index view must have an export button/dropdown that lets the user pick the export type.

---

### G3) Map / Location on show page

- Does this CRUD have map/location fields? `{{HAS_MAP}}` *(true | false — set true if COLUMNS include lat/lng or a map field)*
- Map provider / component: Use the project's existing map component (e.g. `App\View\Components\Form\Map` or the same Blade/JS used in forms) for read-only display when applicable.

**Rule:** If the CRUD has map/location fields (HAS_MAP=true or COLUMNS contain lat/lng), the **show** view MUST display the location as a **map**, not only as raw coordinates or address text. Render a read-only map (same map UI as in create/edit, with lat/lng pre-filled and search/editing disabled if appropriate) so the user sees the pin on the map. Omit the map block entirely if the entity has no lat/lng or they are null.

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
- If custom guard, what is the guard code? `{{DB_SEEDER_GUARD_CODE}}`

**Seeder translatable columns rule:** When `MULTILANG=true`, the Seeder MUST fill translatable columns for each locale. For the **ar** locale, all translatable field values (e.g. name, title, description) MUST be in **Arabic** (real Arabic text, not English or placeholder strings). Use Faker with `ar_SA` locale for Arabic when appropriate, or explicit Arabic strings. Follow the same pattern as existing seeders (e.g. `CategorySeeder` with `"ar" => ["name" => "إلكترونيات"]`). For the **en** locale, use English text.

---

## 2) Guidelines (follow strictly)

### Questions Phase
- **First response MUST be questions only:** Print the Component Checklist and ask Create/Exists/Skip for each component (including admin/main + DatabaseSeeder + Export). Ask only for missing placeholders; do not repeat filled values. If ADD_TO_SIDEBAR=true, ask user to paste sidebar_routes.php (to detect groups).
- **Always ask:** "Do you want to add statistics cards on the index page?" (INDEX_STATISTICS).
- **الرسوم البيانيه follow-up:** If INDEX_STATISTICS=true, ask: "Do you want a collapsible الرسوم البيانيه (charts/diagrams) section (e.g. pie or bar by status)?" (STATISTICS_CHARTS). If yes, ask which chart type(s) and which data (e.g. by status, by date) so STATISTICS_CHART_ITEMS can be filled.
- **Always ask for Export:** "Do you want export functionality? If yes, which types? (xlsx / csv / pdf) And which columns should be exported?" Remind that export column headers will use translation keys (admin/main.* or provided keys) so they are translatable.
- **Map on show:** If COLUMNS include latitude/longitude (or a map field), ask: "Should the show page display the location on a map?" (HAS_MAP). If yes, the show view will render the existing map component in read-only mode.
- **Route translations:** If ADD_ROUTE_TRANSLATIONS=true, confirm: "All route keys for this section will be added (index, create, store, update, edit, show, destroy, delete_all, export if applicable) in both ar and en route translation files. Is that correct?"
- **Seeder Arabic:** If MULTILANG=true, confirm: "Translatable columns in the Seeder will use Arabic text for the ar locale. Is that correct?"
- **Do not generate code until inputs are complete.**

### Code Generation Rules
- When you update an existing file, output **full updated file** + **CHANGES** list.
- Keep naming consistent with derived values unless overridden.
- Work step-by-step.

### Columns as Single Source of Truth
- Build everything (migration, fillable, form fields, table columns, validation, factory, export) **exclusively** from `{{COLUMNS}}`. Never add, infer, or assume columns beyond what was provided.

### Controller Owns All Logic (No Service Layer)
- **Do NOT generate Service classes.** All business logic goes directly inside Controller methods.
- The Controller handles: querying, filtering, sorting, pagination, statistics computation, file uploads, store/update logic, and delete logic.
- Every variable a view needs must be explicitly passed from the Controller via `compact()` or `view()->with()`.

### Blade Views Are Passive
- Blade files must **never** run Eloquent queries, call scopes, or access the DB.
- Blade files only render data received from the Controller.
- Loops, conditionals, and formatting are allowed; queries and business logic are not.

### Form Field Ordering (File Inputs First)
- In **create** and **edit** views, file/image upload fields (`{{FILES}}`) must render **at the top** of the form, before any other input fields (text, select, number, textarea, etc.).
- Group all file inputs together in a dedicated section/row at the top of the form body.

### Export
- If `{{ENABLE_EXPORT=true}}`, generate an Export class (using Laravel Excel or manual CSV/PDF) with only the columns listed in `{{EXPORT_COLUMNS}}`. Column headers in the export MUST be translatable (use `label_key` and `__()` so headers respect the current locale).
- The Controller `export()` method must accept a `type` parameter and return the correct download format.
- The index view must include an export button/dropdown.

### الرسوم البيانيه / Statistics (index page)
- When INDEX_STATISTICS=true, render statistics **dynamically** from the passed cards array (no hardcoded cards). Apply clear styling and **mandatory** entry animation (e.g. fade-in, stagger, count-up for values). If STATISTICS_CHARTS=true, add a collapsible block titled **الرسوم البيانيه** (translation key: `admin/main.statistics_charts`) containing the diagram(s); the Controller must pass the chart dataset(s) and the view must use the project's chart library (e.g. ApexCharts) for labels and values. Charts MUST animate on render (draw-in animation enabled).

### Route Translations (Complete Section)
- When ADD_ROUTE_TRANSLATIONS=true, add **all** route keys for the CRUD section in both `lang/ar/admin/routes.php` and `lang/en/admin/routes.php`. Required keys: `index`, `create`, `store`, `update`, `edit`, `show`, `destroy`, `delete_all`/`destroyAll`, and `export` (if applicable). Follow the same nested-array structure as existing sections (`admins`, `users`, `roles`). Do NOT add only the entity name or a single key.

### Seeder Arabic Locale
- When MULTILANG=true, the Seeder MUST populate translatable columns per locale. The **ar** locale values MUST be real Arabic text (not English, not lorem ipsum). Use Faker `ar_SA` or explicit Arabic strings following existing patterns (e.g. `CategorySeeder`).

### Show page map
- When the CRUD has map/location fields (HAS_MAP=true or lat/lng in COLUMNS), the **show** view MUST include a read-only map (e.g. the same Map component used in forms, with lat/lng set and editing disabled) so the location is shown on a map, not only as text.
