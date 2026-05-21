# Admin UI Standards

## Action Button Classes

Every table row must have colored action buttons using the pattern `{section}-action-{type}`:

| Action | CSS Class | Color Source |
|--------|-----------|--------------|
| View | `{section}-action-view` | Blue (`[class*="-action-view"]`) |
| Edit | `{section}-action-edit` | Green (`[class*="-action-edit"]`) |
| Delete | `{section}-action-delete` | Red (`[class*="-action-delete"]`) |
| Restore | `{section}-action-restore` | Teal (`[class*="-action-restore"]`) |

Each button also gets the base shape class `{section}-action-btn`, styled by `admins.css`/`filter.css` attribute selectors (`[class*="-action-btn"]`).

### Implementation (table row)

```blade
<div class="d-flex align-items-center gap-2 flex-nowrap {section}-row-actions">
    <a href="{{ route('admin.{section}.show', ${model_var}) }}"
       class="custom-icon {section}-action-btn {section}-action-view"
       data-bs-toggle="tooltip" data-bs-placement="top"
       title="@lang('admin/main.show')"
       aria-label="@lang('admin/main.show')">
        <i class="ti ti-eye" aria-hidden="true"></i>
    </a>

    @if (! ${model_var}->deleted_at)
        <a href="{{ route('admin.{section}.edit', ${model_var}) }}"
           class="custom-icon {section}-action-btn {section}-action-edit"
           data-bs-toggle="tooltip" data-bs-placement="top"
           title="@lang('admin/main.edit')"
           aria-label="@lang('admin/main.edit')">
            <i class="ti ti-pencil" aria-hidden="true"></i>
        </a>
    @endif

    @if (${model_var}->deleted_at)
        <a href="javascript:void(0);" data-id="{{ ${model_var}->id }}"
           data-route="{{ route('admin.{section}.restore', ['id' => ${model_var}->id]) }}"
           class="custom-icon {section}-action-btn {section}-action-restore restore-row"
           data-bs-toggle="tooltip" data-bs-placement="top"
           title="@lang('admin/main.restore')"
           aria-label="@lang('admin/main.restore')">
            <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
        </a>
    @else
        <a href="javascript:void(0);" data-id="{{ ${model_var}->id }}"
           data-route="{{ route('admin.{section}.destroy', ${model_var}) }}"
           class="custom-icon {section}-action-btn {section}-action-delete delete-record"
           data-bs-toggle="tooltip" data-bs-placement="top"
           title="@lang('admin/main.delete')"
           aria-label="@lang('admin/main.delete')">
            <i class="ti ti-trash" aria-hidden="true"></i>
        </a>
    @endif
</div>
```

Generic CSS selectors in `filter.css`:
```css
[class*="-action-btn"]      { /* base shape */ }
[class*="-action-view"]     { /* blue */ }
[class*="-action-edit"]     { /* green */ }
[class*="-action-delete"]   { /* red */ }
[class*="-action-restore"]  { /* teal */ }
```

---

## Show Page Layout

### Structure
```
+------------------------------------+
|  Header: icon + title + actions    |
+----------------+-------------------+
|  Profile Card  |  Details Card     |
|  (left column) |  (right column)   |
+----------------+-------------------+
|  Stat Cards Row (4 cards)          |
+------------------------------------+
|  Related Data Section (optional)   |
+------------------------------------+
```

### Header
```blade
<div class="admins-show-header">
    <div class="d-flex align-items-center gap-3">
        <div class="admins-show-header-icon">
            <i class="ti ti-{icon}"></i>
        </div>
        <h4 class="mb-0">{{ __("admin/main.{title_key}") }}</h4>
    </div>
    <div class="admins-show-header-actions">
        <a href="{{ route('admin.{section}.edit', ${model_var}) }}" class="btn btn-success btn-sm" title="{{ __('admin/main.edit') }}">
            <i class="ti ti-pencil"></i> {{ __('admin/main.edit') }}
        </a>
        <a href="{{ route('admin.{section}.index') }}" class="btn btn-secondary btn-sm">
            <i class="ti ti-arrow-left"></i> {{ __('admin/main.back') }}
        </a>
    </div>
</div>
```

### Stat Cards
4 cards using icon + label + value pattern with color variants:

```blade
<x-table.statistics>
    <div class="card admin-stat-card bg-primary text-white">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="mb-0">{{ __('admin/main.{label_key}') }}</h6>
                    <h3 class="mb-0">{{ ${value} }}</h3>
                </div>
                <i class="ti ti-{icon} fs-2 opacity-50"></i>
            </div>
        </div>
    </div>
    <!-- repeat for success, info, warning variants -->
</x-table.statistics>
```

Color variants: `bg-primary`, `bg-success`, `bg-info`, `bg-warning`, `bg-danger`.

### Profile Card
```blade
<div class="card admin-profile-card h-100">
    <div class="card-body text-center">
        <div class="admin-avatar mb-3">
            @if(${model}->getFirstMediaUrl('{collection}'))
                <img src="{{ ${model}->getFirstMediaUrl('{collection}') }}" alt="..." class="rounded-circle" width="120">
            @else
                <div class="admin-avatar-placeholder rounded-circle d-inline-flex align-items-center justify-content-center" style="width:120px;height:120px;">
                    <i class="ti ti-user fs-1"></i>
                </div>
            @endif
        </div>
        <h5 class="mb-1">{{ ${model}->name }}</h5>
        <p class="text-muted mb-0">{{ ${model}->email ?? '' }}</p>
    </div>
</div>
```

### Details Card
```blade
<div class="card admin-details-card h-100">
    <div class="card-header">
        <h5 class="mb-0">{{ __('admin/main.details') }}</h5>
    </div>
    <div class="card-body">
        @include('admin.{section}.parts.detail-row', ['label' => __('admin/main.{field}'), 'value' => ${model}->{field}])
        <!-- repeat for each field -->
    </div>
</div>
```

### Detail Row Partial
```blade
{{-- resources/views/admin/admins/parts/detail-row.blade.php --}}
<div class="row mb-2">
    <div class="col-sm-4 fw-bold text-muted">{{ $label }}</div>
    <div class="col-sm-8">{{ $value ?? '&mdash;' }}</div>
</div>
```

---

## Form Styling

### Structure
Create/edit forms follow a section-based layout:

```blade
<x-form.text :options="['name' => '{field}', 'label' => '{field}', 'class' => 'col-md-6', 'isRequired' => true]" />
<x-form.select :options="['name' => '{relation_id}', 'label' => '{relation}', 'class' => 'col-md-6', 'isRequired' => true, 'options' => $options]" />
```

### Rules
- File uploads at top of form
- Bilingual tabs for translatable fields
- Use `admins-form-section` divs to group related fields
- Form component labels must be plain keys (e.g. `'name'`, `'slug'`), NOT `__('admin/main.name')` -- components translate via `admin/inputs.{key}`
- All labels, placeholders, and buttons must use translation keys
- Validation errors displayed via Blade's `@error` directive

### Image Upload
```blade
<x-form.image :options="['name' => 'image', 'label' => 'image', 'class' => 'col-md-12', 'isRequired' => true, 'editValue' => ${model}?->getFirstMediaUrl('images') ?? null, 'accept' => 'png,jpg,jpeg,svg,webp']" />
```

---

## Table View Structure

### Action Buttons
The `{section}-row-actions` container holds inline action buttons. See [Action Button Classes](#action-button-classes) above for exact markup.

### Deleted Row Handling
- Check `$item->deleted_at` before rendering delete/restore buttons
- Disable delete checkbox for soft-deleted rows
- Show soft-delete banner via `crud.show` layout

---

## Translation Requirements

All UI text must use `__()` helper with one of these translation file namespaces:
- `admin/main.{key}` -- for section names, field labels in show/table views, action labels
- `admin/inputs.{key}` -- for form input labels (used by form components internally)
- `admin/routes.{key}` -- for route/sidebar labels
- `admin/{section}.{key}` -- for section-specific translations

Global keys that must exist:
```
admin/main: name, email, phone, image, is_active, status, type, slug, icon, title, content,
            description, link, question, answer, country, region, city, district,
            category, sub_category, page, slider, faq, intro_page, social,
            contact_message, complaint, seo, user, admin, provider, role,
            created_at, updated_at, deleted_at, details, show, edit, delete, restore,
            create, update, back, yes, no, actions, related_data, no_data

admin/inputs: name, email, phone, image, is_active, type, slug, icon, title, content,
              description, link, question, answer, country, region, city, district,
              category_id, country_id, region_id, city_id, password, role_id,
              meta_title, meta_description, meta_keywords
```

---

## CSS Classes Reference

| Class | Purpose |
|-------|---------|
| `admins-show-header` | Show page header container |
| `admins-show-header-icon` | Icon container in header |
| `admins-show-header-actions` | Action buttons area in header |
| `admin-stat-card` | Statistics card |
| `admin-profile-card` | Profile/avatar card |
| `admin-details-card` | Details information card |
| `admins-form-section` | Form section grouping |
| `admin-avatar` | Avatar container |
| `admin-avatar-placeholder` | Avatar placeholder when no image |
| `{section}-row-actions` | Action buttons container in table |
| `custom-icon` | Applied alongside action buttons |
| `{section}-action-btn` | Base button shape (shared across sections) |
| `{section}-action-view` | View action button |
| `{section}-action-edit` | Edit action button |
| `{section}-action-delete` | Delete action button |
| `{section}-action-restore` | Restore action button |
