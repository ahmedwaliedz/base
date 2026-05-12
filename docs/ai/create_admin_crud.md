# Admin CRUD Module – AI Scaffolding Template

When this doc is used, **the first response MUST be questions only (Step 1)**. Do not generate code until the user has provided all required inputs and the plan (Step 2) has been confirmed.

**Default reference implementation:** **`users`** admin CRUD (see [Reference CRUD – Users](#reference-crud--users) below). Match file layout, naming, `AdminBaseController` + Service layer, Blade components (`x-table.*`), routes in `routes/admin.php`, and `config/sidebar_routes.php` unless the user explicitly chooses another reference entity.

**Visual/UI parity (mandatory):** All **styles and structure** for **tables**, **statistics** (cards + loaders), **toolbar buttons**, and **form inputs** must be taken from the chosen **`{{REFERENCE_CRUD_ENTITY}}`** (default **users**). Reuse the same Blade layout parents (`admin.layouts.crud.index`, `admin.layouts.crud.table`, create/edit/show partials), the same **`x-table.*`** components with the same prop patterns, and the same Bootstrap / card / datatable classes as the reference. Do **not** introduce alternate markup or ad hoc CSS for those surfaces unless the reference module already does.

---

## Mandatory Workflow

1. **Step 1 – Collect inputs:** Ask every question in this doc. Do not generate code until Step 1 is complete.
2. **Step 2 – Create plan:** From the collected inputs, produce a plan: file tree, which components are Create/Exists/Skip, and for Exists list the files to patch and what will change.
3. **Step 3 – Create section:** Generate full code for every Create component and full updated content + CHANGES for every Exists component.

### Step 1 - Required prompts (NEVER skip, NEVER infer)

Ask these explicitly and separately before anything else:

1. **Statistics cards:** Do you want **statistics cards** on the index page? -> `{{INDEX_STATISTICS}}`
   - If yes -> ask: Which cards make sense? (total / active / blocked / today / this week / this month+growth -- or custom) -> `{{STATISTICS_CARDS}}`
2. **Diagrams / charts:** Do you want **diagrams or charts** (pie/bar/donut via ApexCharts, collapsible section)? -- independent of cards -> `{{INDEX_DIAGRAMS}}`
3. **Export:** Do you want **export** on the index? Which formats? (copy / pdf / excel / word / json) -> `{{ENABLE_EXPORT}}` + `{{EXPORT_TYPES}}`
4. **Send Email:** Do you want a **bulk email** button in the toolbar? -> `{{HAS_EMAIL}}`
5. **Send Notification:** Do you want a **bulk notification** button in the toolbar? -> `{{HAS_NOTIFICATION}}`
6. **Sidebar display mode** (ask only when ADD_TO_SIDEBAR=true): Single direct link or dropdown? -> `{{SIDEBAR_DISPLAY_MODE}}`. **Never infer.**
7. **Show page - related data:** List every **relation** to expose. Stat tiles / tables / both? -> `{{SHOW_RELATED_PRESENTATION}}`

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

## Index View Pattern (reference: `users/index.blade.php`)

```blade
@extends('admin.layouts.crud.index')

@push('content')
    {{-- 1. Statistics (only when INDEX_STATISTICS=true) --}}
    <x-table.statistics :loaderCards="{{STATISTICS_CARD_COUNT}}" />

    {{-- 2. Toolbar --}}
    <x-table.buttons
        createRoute="{{ route('admin.{{entity}}.create') }}"
        :hasNotification="{{HAS_NOTIFICATION}}"
        :hasEmail="{{HAS_EMAIL}}"
        :hasDeleteAll="true"
        :deleteAllRoute="route('admin.{{entity}}.destroyAll')"
        :hasReload="true"
        :hasSearch="true"
        :hasFilter="true"
        :hasExport="{{ENABLE_EXPORT}}"
        :exportCopy="{{exportCopy}}"
        :exportPdf="{{exportPdf}}"
        :exportExcel="{{exportExcel}}"
        :exportWord="{{exportWord}}"
        :exportJson="{{exportJson}}"
        :hasPagination="true"
        :perPage="20" />

    {{-- 3. Filter panel --}}
    <x-table.filter
        :mainCol="'col-md-3'"
        :hasStartDate="true"
        :hasEndDate="true"
        :hasOrderBy="true"
        :hasRetrieve="$is_retreivable"
        :filters="[/* per-column filters */]" />

    {{-- 4. Bulk-actions bar (always when hasDeleteAll=true) --}}
    <x-table.bulk-actions
        :hasDelete="true"
        :deleteRoute="route('admin.{{entity}}.destroyAll')" />

    {{-- 5. Table --}}
    <x-table.table
        :hasCheckbox="true"
        :hasActions="true"
        :headers="[/* translated column headers */]" />

    {{-- 6. Modals (conditional) --}}
    @if({{HAS_NOTIFICATION}})
        <x-model.notification
            :route="route('admin.notifications.sendNotifications')"
            :class="'App\Models\{{ModelName}}'" />
    @endif
    @if({{HAS_EMAIL}})
        <x-model.email />
    @endif
@endpush

@push('js')
    @if({{INDEX_STATISTICS}})
    <script>
        var statsUrl = "{{ route('admin.{{entity}}.statistics') }}";
    </script>
    <script src="{{ asset('style/admin/custom-js/stats.js') }}"></script>
    @endif
@endpush
```

**`<x-table.buttons>` props:**

| Prop | Type | Purpose |
|------|------|---------|
| `createRoute` | string | "Create" button href |
| `hasNotification` | bool | Bulk notify button (btn-label-warning) -> opens #notificationModal |
| `hasEmail` | bool | Bulk email button (btn-label-info) -> opens #emailModal |
| `hasDeleteAll` | bool | Wires bulk-delete JS; visible button is in bulk-actions bar |
| `deleteAllRoute` | string | Route for bulk delete |
| `hasReload` | bool | Reload table button |
| `hasSearch` | bool | Quick-search input in toolbar |
| `hasFilter` | bool | Filter panel toggle |
| `hasExport` | bool | Export dropdown master switch |
| `exportCopy/Pdf/Excel/Word/Json` | bool | Individual export formats |
| `hasPagination` | bool | Per-page selector |
| `perPage` | int | Default rows per page |

---

## Statistics Pattern (reference: `users`)

### When `INDEX_STATISTICS = true`

**Controller method:**

```php
public function statistics(Request $request)
{
    $base = $this->service->index($request);
    $now  = Carbon::now();

    $total     = (clone $base)->count();
    $active    = (clone $base)->where('is_blocked', false)->count();
    $blocked   = (clone $base)->where('is_blocked', true)->count();
    $today     = (clone $base)->whereDate('created_at', $now->toDateString())->count();
    $thisWeek  = (clone $base)->where('created_at', '>=', $now->copy()->startOfWeek())->count();
    $thisMonth = (clone $base)->where('created_at', '>=', $now->copy()->startOfMonth())->count();
    $lastMonth = (clone $base)->whereBetween('created_at', [
        $now->copy()->subMonth()->startOfMonth(),
        $now->copy()->subMonth()->endOfMonth(),
    ])->count();

    $growth = $lastMonth > 0
        ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1)
        : ($thisMonth > 0 ? 100.0 : 0.0);

    return response()->view(
        'admin.{{entity}}.parts.statistics',
        compact('total', 'active', 'blocked', 'today', 'thisWeek', 'thisMonth', 'growth')
    );
}
```

**Adapt cards per entity type:**
- Has is_blocked/is_active: use --active / --blocked cards.
- Has status enum: one card per status (--pending, --approved, --rejected).
- No status: use --total, --today, --week, --month only.
- Always include month card with $growth delta when entity has created_at.
- Set `:loaderCards` to the **exact** card count rendered.

**Statistics partial (6-card example):**

```blade
@php $growthUp = ($growth ?? 0) >= 0; @endphp

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--total d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.total') }}</p>
            <p class="crud-stats__value">{{ number_format($total ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon" aria-hidden="true"><i class="ti ti-{ICON}"></i></span>
    </div>
</div>

{{-- Month card with growth delta --}}
<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--month d-flex align-items-center justify-content-between">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                <p class="crud-stats__label mb-0">{{ __('admin/main.this_month') }}</p>
                @if(($growth ?? 0) !== 0)
                    <span class="crud-stats__delta {{ $growthUp ? 'crud-stats__delta--up' : 'crud-stats__delta--down' }}">
                        <i class="ti {{ $growthUp ? 'ti-trending-up' : 'ti-trending-down' }}" aria-hidden="true"></i>
                        {{ abs($growth) }}%
                    </span>
                @endif
            </div>
            <p class="crud-stats__value">{{ number_format($thisMonth ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon" aria-hidden="true"><i class="ti ti-calendar-month"></i></span>
    </div>
</div>
```

**CSS modifier classes** (in `crud-stats.css`):

| Class | Accent color |
|-------|-------------|
| `crud-stats__card--total` | brand primary (violet) |
| `crud-stats__card--active` | success (green) |
| `crud-stats__card--blocked` | warning (amber) |
| `crud-stats__card--today` | info (cyan) |
| `crud-stats__card--week` | tertiary |
| `crud-stats__card--month` | danger / neutral |
| `crud-stats__card--pending` | warning |
| `crud-stats__card--approved` | success |
| `crud-stats__card--rejected` | danger |

**Statistics rules:**
- Always `number_format()` every value in the partial.
- Icon inherits color from `--card-accent-rgb` via CSS -- never use inline `style=`.
- Route: `GET admin/{entity}/statistics` -> `{Entity}Controller@statistics`.
- JS wiring: push `statsUrl` var + `stats.js` in `@push('js')` of index view.

---

## Table Row Pattern (reference: `users/table.blade.php`)

```blade
@extends('admin.layouts.crud.table', [
    'rows'        => ${{entities}},
    'createRoute' => route('admin.{{entity}}.create'),
])

@section('table')
    @foreach (${{entities}} as $item)
        <tr class="data-rows {{entity}}-table-row {{ $item->deleted_at ? 'deleted-table-row' : '' }}"
            data-{{entity}}-id="{{ $item->id }}">

            @if (!$item->deleted_at)
                <td class="dt-checkboxes-cell">
                    <input type="checkbox" value="{{ $item->id }}" data-id="{{ $item->id }}"
                           class="dt-checkboxes form-check-input"
                           aria-label="{{ __('admin/main.select_row', ['name' => $item->name]) }}">
                </td>
            @else
                <td></td>
            @endif

            <td>...primary column...</td>
            <td class="d-none d-md-table-cell">...secondary column (hidden on mobile)...</td>

            <td class="{{entity}}-actions-cell">
                <div class="d-flex align-items-center gap-2 flex-nowrap {{entity}}-row-actions">

                    <a href="{{ route('admin.{{entity}}.show', $item) }}"
                       class="custom-icon {{entity}}-action-btn {{entity}}-action-view"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="@lang('admin/main.show')" aria-label="@lang('admin/main.show')">
                        <i class="ti ti-eye" aria-hidden="true"></i>
                    </a>

                    @if (!$item->deleted_at)
                        <a href="{{ route('admin.{{entity}}.edit', $item) }}"
                           class="custom-icon {{entity}}-action-btn {{entity}}-action-edit"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.edit')" aria-label="@lang('admin/main.edit')">
                            <i class="ti ti-pencil" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if ($item->deleted_at)
                        <a href="javascript:void(0);" data-id="{{ $item->id }}"
                           data-route="{{ route('admin.{{entity}}.restore', ['id' => $item->id]) }}"
                           class="custom-icon {{entity}}-action-btn {{entity}}-action-restore restore-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.restore')" aria-label="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-id="{{ $item->id }}"
                           data-route="{{ route('admin.{{entity}}.destroy', $item) }}"
                           class="custom-icon {{entity}}-action-btn {{entity}}-action-delete delete-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.delete')" aria-label="@lang('admin/main.delete')">
                            <i class="ti ti-trash" aria-hidden="true"></i>
                        </a>
                    @endif

                    {{-- "More" dropdown: NEVER put data-bs-toggle="tooltip" on the same element as data-bs-toggle="dropdown" --}}
                    @if (!$item->deleted_at && ({{HAS_NOTIFICATION}} || {{HAS_EMAIL}}))
                        <div class="dropdown {{entity}}-more-dropdown">
                            <button type="button"
                                    class="custom-icon {{entity}}-action-btn {{entity}}-action-more"
                                    data-bs-toggle="dropdown" aria-expanded="false"
                                    aria-label="@lang('admin/main.more_actions')">
                                <i class="ti ti-dots-vertical" aria-hidden="true"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if({{HAS_NOTIFICATION}})
                                <li>
                                    <button type="button" class="dropdown-item send-notification"
                                            data-bs-toggle="modal" data-bs-target="#notificationModal"
                                            data-id="{{ $item->id }}">
                                        <i class="ti ti-bell-plus me-2" aria-hidden="true"></i>
                                        @lang('admin/main.send_notification')
                                    </button>
                                </li>
                                @endif
                                @if({{HAS_EMAIL}})
                                <li>
                                    <button type="button" class="dropdown-item"
                                            data-bs-toggle="modal" data-bs-target="#emailModal"
                                            data-id="{{ $item->id }}">
                                        <i class="ti ti-mail-plus me-2" aria-hidden="true"></i>
                                        @lang('admin/main.send_email')
                                    </button>
                                </li>
                                @endif
                            </ul>
                        </div>
                    @endif

                </div>
            </td>
        </tr>
    @endforeach
@endsection
```

**Table row rules:**
- `<tr>` must have class `data-rows` + entity row class (e.g. `users-table-row`).
- `data-{entity}-id="{{ $item->id }}"` attribute on the `<tr>`.
- Secondary data columns (phone, email, etc.): add `d-none d-md-table-cell` for mobile hiding.
- NEVER put `data-bs-toggle` twice on the same element -- tooltip + dropdown on same button = conflict. Notify/email triggers go inside `dropdown-menu`.
- `data-id="{{ $item->id }}"` required on every notify and email button.
- Edit button hidden when `$item->deleted_at` is set.

---

## Form Pattern (reference: `users/create.blade.php`, `users/edit.blade.php`)

### Form tag

```blade
{{-- Create --}}
<form class="mb-3 validated-form form" novalidate method="POST"
      action="{{ route('admin.{{entity}}.store') }}" enctype="multipart/form-data">
    @csrf

{{-- Edit --}}
<form class="mb-3 validated-form form" novalidate method="POST"
      action="{{ route('admin.{{entity}}.update', $id) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
```

### Grid layout - column-fill rule

All fields live inside `<div class="row g-3">`. **Every row of fields must sum to exactly 12 Bootstrap columns** -- never leave orphaned inputs next to blank space.

| Valid row combination | Total |
|-----------------------|-------|
| `col-md-12` | 12 -- image, full-width textarea |
| `col-md-6` + `col-md-6` | 6+6 = 12 |
| `col-md-4` + `col-md-4` + `col-md-4` | 4+4+4 = 12 |
| `col-md-6` + `col-md-3` + `col-md-3` | 6+3+3 = 12 |
| `col-md-4` + `col-md-8` | 4+8 = 12 |

**Multilingual expansion:** `x-form.text` / `x-form.text-area` with `'isMultiLanguage' => true` generates **one `col-*` div per locale** -- NOT a single wrapper. Example: `class='col-md-6'` with 2 locales (ar + en) = 2 x col-md-6 = 12 columns = row is full. Next fields start a fresh row.

**WRONG (the screenshot bug -- 6-col gap after multilingual field):**
```blade
{{-- name[ar] col-6 + name[en] col-6 = 12, row is done --}}
<x-form.text :options="['name'=>'name','class'=>'col-md-6','isMultiLanguage'=>true,'isRequired'=>true]" />
{{-- Only 3+3=6 of 12 used -- creates a 6-col empty gap! --}}
<x-form.text   :options="['name'=>'code',      'class'=>'col-md-3','isRequired'=>true]" />
<x-form.select :options="['name'=>'is_active', 'class'=>'col-md-3','options'=>[...]]" />
```

**CORRECT -- widen to fill 12:**
```blade
<x-form.text :options="['name'=>'name','class'=>'col-md-6','isMultiLanguage'=>true,'isRequired'=>true]" />
{{-- 6+6=12 --}}
<x-form.text   :options="['name'=>'code',      'class'=>'col-md-6','isRequired'=>true]" />
<x-form.select :options="['name'=>'is_active', 'class'=>'col-md-6','options'=>[...]]" />
```

**CORRECT -- group three fields to fill 12:**
```blade
<x-form.text   :options="['name'=>'code',       'class'=>'col-md-4','isRequired'=>true]" />
<x-form.select :options="['name'=>'is_active',  'class'=>'col-md-4','options'=>[...]]" />
<x-form.select :options="['name'=>'country_id', 'class'=>'col-md-4','options'=>$countries]" />
```

### Available form components

| Component | Tag | Notes |
|-----------|-----|-------|
| Text | `<x-form.text>` | Supports `isMultiLanguage`, `minLength` |
| Number | `<x-form.number>` | `minLength` / `maxLength` |
| Email | `<x-form.email>` | |
| Password | `<x-form.password>` | `isRequired=false` on edit |
| Select | `<x-form.select>` | Pass `options` array with `id`/`name` keys |
| Checkbox | `<x-form.checkbox>` | Simple boolean toggle |
| Textarea | `<x-form.text-area>` | Supports `isMultiLanguage` |
| Image | `<x-form.image>` | Always `col-md-12`, placed first |
| Multi-image | `<x-form.multi-image>` | Always `col-md-12`, placed first |
| Date | `<x-form.date>` | |
| Datetime | `<x-form.datetime>` | |
| Map | `<x-form.map>` | When entity has lat/lng |

**File inputs always first** -- `x-form.image` / `x-form.multi-image` before all other fields.

### Boolean / status fields

Use `x-form.select` with **semantic options** matching the field meaning -- never generic Yes/No strings:

```blade
{{-- is_active --}}
<x-form.select :options="[
    'name'    => 'is_active',
    'label'   => 'is_active',
    'class'   => 'col-md-4',
    'value'   => true,
    'options' => [
        ['id' => 1, 'name' => __('admin/main.active')],
        ['id' => 0, 'name' => __('admin/main.inactive')],
    ],
]" />

{{-- is_blocked --}}
<x-form.select :options="[
    'name'    => 'is_blocked',
    'label'   => 'is_blocked',
    'class'   => 'col-md-4',
    'value'   => false,
    'options' => [
        ['id' => 0, 'name' => __('admin/main.active')],
        ['id' => 1, 'name' => __('admin/main.blocked')],
    ],
]" />

{{-- Enum status (pending/approved/rejected) -- options from controller/service --}}
<x-form.select :options="[
    'name'    => 'status',
    'label'   => 'status',
    'class'   => 'col-md-4',
    'options' => $statuses,
]" />
```

**Boolean rules:**
- Options from controller/service when list is reused (like `$receiveNotificationsOptions` in users), or inline with `admin/main` keys.
- Default `value` must reflect sensible initial state (e.g. `true` for `is_active`, `false` for `is_blocked`).
- Never use plain PHP `true`/`false` as option labels.

### Action buttons

```blade
{{-- Create form --}}
<div class="pt-4 d-flex justify-content-center mt-3">
    <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button">
        {{ __('admin/main.create') }}
    </button>
</div>

{{-- Edit form --}}
<div class="pt-4 d-flex justify-content-center mt-3">
    <button type="submit" class="btn btn-success me-sm-3 me-1 waves-effect waves-light submit-button">
        {{ __('admin/main.edit') }}
    </button>
</div>
```

**Action button rules:**
- Create -> `btn-primary` | Edit -> `btn-success` -- never swap, never use other colors.
- Always include `waves-effect waves-light submit-button` -- submit-form.js targets `.submit-button`.
- Container always: `pt-4 d-flex justify-content-center mt-3`.

---

## Routes Translations Pattern

Two files must be updated. They have **different structures**:

**`lang/ar/admin/routes.php`** -- entity nested under outer `'admin'` key:
```php
return [
    'admin' => [
        // existing entities...
        '{{entity_plural}}' => [
            'index'      => 'قائمة {{ENTITY_PLURAL_AR}}',
            'create'     => 'صفحة إنشاء {{ENTITY_SINGULAR_AR}}',
            'store'      => 'إنشاء {{ENTITY_SINGULAR_AR}}',
            'update'     => 'صفحة تعديل {{ENTITY_SINGULAR_AR}}',
            'edit'       => 'تعديل {{ENTITY_SINGULAR_AR}}',
            'show'       => 'عرض {{ENTITY_SINGULAR_AR}}',
            'destroy'    => 'حذف {{ENTITY_SINGULAR_AR}}',
            'delete_all' => 'حذف {{ENTITY_PLURAL_AR}}',
        ],
    ],
];
```

**`lang/en/admin/routes.php`** -- entity flat at root (NO `'admin'` wrapper):
```php
return [
    // existing entities...
    '{{entity_plural}}' => [
        'index'      => '{{ENTITY_PLURAL_EN}}',
        'create'     => 'Create {{ENTITY_SINGULAR_EN}} Page',
        'store'      => 'Create {{ENTITY_SINGULAR_EN}}',
        'update'     => 'Update {{ENTITY_SINGULAR_EN}} Page',
        'edit'       => 'Edit {{ENTITY_SINGULAR_EN}}',
        'show'       => 'Show {{ENTITY_SINGULAR_EN}}',
        'destroy'    => 'Delete {{ENTITY_SINGULAR_EN}}',
        'delete_all' => 'Delete {{ENTITY_PLURAL_EN}}',
    ],
];
```

**Always-required keys:** `index`, `create`, `store`, `update`, `edit`, `show`, `destroy`, `delete_all`.
**Add custom keys** for: `statistics`, `restore`, `switchBlock`, `diagrams`, and any entity-specific routes.

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
10. **Email and Notification modals:** Include `<x-model.notification>` ONLY when `HAS_NOTIFICATION=true`. Include `<x-model.email>` ONLY when `HAS_EMAIL=true`. Both require `data-id="{{ $item->id }}"` on every row trigger button inside the "more" dropdown.
11. **Bulk-actions bar:** Always include `<x-table.bulk-actions>` when `hasDeleteAll=true`. The delete-all button inside `<x-table.buttons>` is hidden (d-none) -- the visible delete UI is the bulk-actions bar.
12. **Form grid -- no orphaned inputs:** Every row in `<div class="row g-3">` must sum to exactly **12 Bootstrap columns**. Multilingual field with `isMultiLanguage=true` at `col-md-6` generates one `col-md-6` per locale (2 locales x 6 = 12) -- that row is consumed. Widen or add fields so each row totals 12. See **Form Pattern** above.
13. **Form action buttons:** Create -> `btn btn-primary`. Edit -> `btn btn-success`. Both must include `waves-effect waves-light submit-button`. Container: `<div class="pt-4 d-flex justify-content-center mt-3">`. Never swap colors or omit `submit-button`.
14. **Boolean / status form fields:** Use `x-form.select` with semantic options from `admin/main` keys -- never generic Yes/No strings. Options for reused lists come from controller/service. Default `value` must be set to the sensible initial state.

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
- **Always ask all 7 from Step 1:**
  1. Statistics cards? -> `INDEX_STATISTICS` (if yes -> which cards?)
  2. Diagrams/charts? -> `INDEX_DIAGRAMS` (independent)
  3. Export? Formats? -> `ENABLE_EXPORT` + `EXPORT_TYPES`
  4. Bulk email button? -> `HAS_EMAIL`
  5. Bulk notification button? -> `HAS_NOTIFICATION`
  6. Sidebar: single or dropdown? -> `SIDEBAR_DISPLAY_MODE` (when ADD_TO_SIDEBAR=true)
  7. Show page relations: tiles / tables / both? -> `SHOW_RELATED_PRESENTATION`
- **Filters:** Confirm full filter set: date range, order, retrieve (soft deletes), per-column filters.
- **Map on show:** If lat/lng exist, confirm read-only map.
- **Translations:** Confirm AR+EN coverage plan; run review after coding (Critical Rules rule 7).
- **Do not generate code until all inputs are confirmed.**

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

### Form Layout
- File inputs (`x-form.image`, `x-form.multi-image`) always first, always `col-md-12`.
- Every row in `<div class="row g-3">` must sum to **12 columns** -- no orphaned inputs next to blank space.
- `isMultiLanguage=true` generates one `col-*` div per locale: plan rows so each sums to 12 after expansion (e.g. 2 locales x col-md-6 = 12 = full row).
- Widen remaining fields or add fields; never leave a partial row with empty grid space.
- Create button: `btn-primary` | Edit button: `btn-success` | Both: `waves-effect waves-light submit-button` | Container: `pt-4 d-flex justify-content-center mt-3`.
- Boolean/status fields: `x-form.select` with semantic `admin/main` translation labels, not raw yes/no. Set default `value` to sensible initial state.

- File inputs first in create/edit.

### Index: filters + statistics + diagrams
- `<x-table.filter>`: set `hasStartDate`, `hasEndDate`, `hasOrderBy`, `hasRetrieve` as appropriate; pass a **complete** `filters` array for the entity.
- Statistics/diagrams: follow **Users** wiring when those flags are true.

### Show page
- All **relations** from `{{RELATIONS}}`: expose summary stats and/or tables per `SHOW_RELATED_PRESENTATION`. Eager-load in the service; Blade uses passed variables only.

### Route and page translations
- Add **all** route keys in both `lang/ar/admin/routes.php` AND `lang/en/admin/routes.php`.
- **Structure difference:** AR file nests entities under outer `'admin'` key; EN file is flat at root. See **Routes Translations Pattern** section.
- Required keys: `index`, `create`, `store`, `update`, `edit`, `show`, `destroy`, `delete_all`. Add custom keys for `statistics`, `restore`, `switchBlock`, `diagrams`, etc.
- After implementation, complete translations review (rule 7): `inputs`, `main`, `routes`, `validation`, Blade titles.

---

## 3) Quick checklist before coding

- [ ] Reference entity (default **users**) confirmed -- UI copied from reference (tables, stats, buttons, inputs, forms)
- [ ] `INDEX_STATISTICS` asked -> card list confirmed -> `:loaderCards` set to exact count
- [ ] `INDEX_DIAGRAMS` asked (independent of stats)
- [ ] `ENABLE_EXPORT` asked -> formats confirmed
- [ ] `HAS_EMAIL` asked -> `<x-model.email>` + row 'more' dropdown buttons planned
- [ ] `HAS_NOTIFICATION` asked -> `<x-model.notification>` + row 'more' dropdown buttons planned
- [ ] `SIDEBAR_DISPLAY_MODE` asked when `ADD_TO_SIDEBAR=true` (single vs dropdown; maps to `has_child`)
- [ ] Index **filters** cover all applicable column/date/order/retrieve cases
- [ ] Show page **relations** + stats/tables/both confirmed
- [ ] `<x-table.bulk-actions>` included when `hasDeleteAll=true`
- [ ] `hasSearch=true` set on `<x-table.buttons>`
- [ ] Row actions: `data-id` on all notify/email buttons; no `data-bs-toggle` conflict (tooltip + dropdown on same element)
- [ ] **Form grid:** every row sums to 12 cols; multilingual expansion planned (N locales x col-md-X <= 12 per row)
- [ ] **Form buttons:** Create = `btn-primary`, Edit = `btn-success`; both have `waves-effect waves-light submit-button`
- [ ] **Boolean/status fields:** `x-form.select` with semantic `admin/main` labels (not raw yes/no)
- [ ] **Routes translations:** both `lang/ar/admin/routes.php` (nested under 'admin') and `lang/en/admin/routes.php` (flat) updated with all required keys
- [ ] Statistics: `number_format()` on all values; no inline `style=` on `crud-stats__icon`; `stats.js` + `statsUrl` wired
- [ ] Skeleton loader `:loaderCards` + `data-rows` verified
- [ ] `routes/admin.php`, `config/sidebar_routes.php`, lang files scoped
- [ ] Service + `AdminBaseController` pattern agreed -- no DB logic in Blade
- [ ] Full AR/EN translation review planned (inputs, main, routes, validation, page titles)