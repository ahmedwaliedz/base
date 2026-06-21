# Admin CRUD Project Conventions

Load this reference when implementing views, routes, translations, factories, or seeders for an admin CRUD module.

## View structure

Required views when enabled:

- `resources/views/admin/{module}/index.blade.php`
- `resources/views/admin/{module}/create.blade.php`
- `resources/views/admin/{module}/edit.blade.php`
- `resources/views/admin/{module}/show.blade.php`

Use the project table/show templates:

- [`../../../../templates/show-view-template.md`](../../../../templates/show-view-template.md)
- [`../../../../templates/table-view-template.md`](../../../../templates/table-view-template.md)

Follow the admin UI standards in [`../../../../styles/admin-ui-standards.md`](../../../../styles/admin-ui-standards.md).

## UI rules

- Blade is presentation-only.
- No queries, service calls, or model lookups inside Blade.
- Use project components: `<x-form.*>` and `<x-table.*>`.
- Form labels inside components must be plain keys (e.g., `'name'`), not `__('admin/main.name')`.
- All other labels use `__('admin/...')` keys.
- Group form fields inside `admins-form-section` divs.
- File inputs appear at the top of create/edit forms.

## Action button CSS

Use section-specific classes:

```text
{section}-action-btn      (base)
{section}-action-view     (blue)
{section}-action-edit     (green)
{section}-action-delete   (red)
{section}-action-restore  (teal)
```

Color source is the CSS class, not inline styles.

## Show page layout

```text
header
stat cards row (4 cards)
profile card (left, 4 cols)
details card (right, 8 cols)
```

## Routes and sidebar

- Register CRUD routes in `routes/admin.php`.
- Add export route when export is enabled.
- Register sidebar/menu item using existing project config.
- Every `admin.{module}.{action}` route must have a matching entry in `lang/{locale}/admin/routes.php`.

## Translations

Required translation files:

- `lang/ar/admin/routes.php` and `lang/en/admin/routes.php`
- `lang/ar/admin/inputs.php` and `lang/en/admin/inputs.php`
- `lang/ar/admin/main.php` and `lang/en/admin/main.php`

Route translation entries must include:

- index
- create
- store
- update
- edit
- show
- destroy
- delete_all or destroyAll
- export (when enabled)

## Factories and seeders

- Derive factory fields from the columns definition only.
- Use realistic values.
- For multilingual entities:
  - Arabic locale must contain real Arabic text.
  - English locale must contain English text.
- Register seeders in `DatabaseSeeder` when requested.
