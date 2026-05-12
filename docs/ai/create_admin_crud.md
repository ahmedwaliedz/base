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
8. **Block/unblock support:** Does this entity need block/unblock toggle (uses `is_blocked` column + `switchBlock` route + `AuthenticatableBaseService`)? -> `{{HAS_BLOCK_TOGGLE}}`
9. **Soft-delete + restore:** Should rows be soft-deletable and restorable (`CanRetrieve` trait + `restore` route)? -> `{{SOFT_DELETES}}` + `{{HAS_RESTORE}}`

---

## Reference CRUD – Users

Use these paths and patterns when generating a new admin CRUD unless overrides are provided.

| Area | Reference |
|------|-----------|
| Routes | `routes/admin.php` — resource route + extras (`destroyAll`, `restore`, `statistics`, optional `diagrams`, entity-specific actions like `switchBlock`). **All custom GET/PUT/DELETE lines MUST be placed BEFORE `Route::resource()` to avoid `{user}` param capture.** |
| Base controller | `App\Http\Controllers\Admin\AdminBaseController` — `index`, `create`, `store`, `edit`, `update`, `show`, `destroy`, `destroyAll`, `restore`, export via `?export=` |
| Authenticatable entities | `AuthenticatableBaseController` + `AuthenticatableBaseService` when the model supports block/unblock like users. `switchBlock()` lives in `AuthenticatableBaseService` and flips `is_blocked` |
| Service | `App\Services\Admin\UserService` extends `AuthenticatableBaseService` → `CrudBaseService` — implements `createVars()` / `editVars()`, optional `indexVars()`, `showVars()` |
| Model | `App\Models\User` — uses `BaseAuthModelTrait` (auth entities) or `BaseModelTrait` (regular entities), `CanRetrieve`, `HasApiTokens` (auth), declares `RELATIONS`, `EXPORT_COLUMNS`, `FILES`, `UPLOAD_DIRECTORY`, optional `$availableNotificationTypes`, custom `applyColumnFilter` for non-LIKE columns |
| Index view | `resources/views/admin/users/index.blade.php` extends `admin.layouts.crud.index`; uses `<x-table.statistics>`, `<x-table.buttons>`, `<x-table.filter>`, `<x-table.bulk-actions>`, `<x-table.table>`, `<x-model.notification>`, `<x-model.email>` |
| Statistics partial + route | `UserController::statistics()` returns `admin.users.parts.statistics`; `statsUrl` in index pushes `stats.js` |
| Table partial | `resources/views/admin/users/table.blade.php` loaded via AJAX from `AdminBaseController::index` — uses `users-table-row`, `user-status-pill`, `users-action-*` themed classes |
| Show view | `resources/views/admin/users/show.blade.php` extends `admin.layouts.crud.show` — profile card (4 col) + details card (8 col) |
| Create/Edit views | extend `admin.layouts.crud.create` / `admin.layouts.crud.edit` — already wrap form in `<div class="card"><div class="card-body">` |
| Sidebar | `config/sidebar_routes.php` — entry key under `admin` (e.g. `users`) with `has_child`, `icon`, optional `group`, `childes` |
| Form Requests | `app/Http/Requests/Admin/User/{Store,Update}Request.php` extends `BaseAdminRequest` — implements `prepareForValidation()` + `rules()` |
| Factory | `database/factories/UserFactory.php` — uses global `fake()` helper, can pull FK from existing rows |
| Seeder | `database/seeders/User/UserSeeder.php` — nested by entity, calls factory; registered in `DatabaseSeeder` |
| Permissions | Auto-generated by `database/seeders/Admin/PermissionSeeder.php` from all routes matching `admin.*` — **no per-CRUD entry needed** |
| Table AJAX + skeleton | `public/style/admin/custom-js/admin-table.js` — `showTableLoader` / `hideTableLoader` / `loadTable`; table partial rows use class **`data-rows`** (removed on reload). Index uses `<x-table.table>` which embeds a **`.table-loader`** skeleton row whose column layout mirrors `:headers`, `:hasCheckbox`, `:hasActions`. Skeleton has fixed **9 rows** |
| Stats AJAX | `stats.js` — `loadStats()` runs on document ready AND inside `loadTable()` so stats refresh with filters |
| CSS host | `public/style/admin/css/filter.css` carries all users-table CSS (status pill, action buttons, hover glow, RTL accent, deleted-state). Tokens come from `tokens.css` |

---

## Detected Users CRUD Feature Inventory (must be replicated)

The following catalog is what the **current users CRUD actually ships** at the time of writing. Any new CRUD that uses `{{REFERENCE_CRUD_ENTITY}} = users` MUST mirror these features unless explicitly skipped in Step 1.

### Backend

1. **Routes file (`routes/admin.php`)** — for `users`:
   - `DELETE /users/destroy-all` → `destroyAll`
   - `PUT /users/{id}/switch-block` → `switchBlock`
   - `PUT /users/{id}/restore` → `restore`
   - `GET /users/statistics` → `statistics`
   - `GET /users/diagrams` → `diagrams` *(route stub; controller method may be empty until enabled)*
   - `Route::resource('users', UserController::class)` LAST

2. **Controller (`UserController`)** — extends `AuthenticatableBaseController` (which extends `AdminBaseController`):
   - Inherits the full CRUD pipeline
   - `statistics(Request $request)` — clones service base query and returns Blade partial with `total`, `active`, `blocked`, `today`, `thisWeek`, `thisMonth`, `lastMonth`, `growth`

3. **Service (`UserService`)** — extends `AuthenticatableBaseService` → `CrudBaseService`:
   - `createVars()` returns `countries` (active only) + `receiveNotificationsOptions`
   - `editVars()` returns `createVars()` (reuse)
   - **`AuthenticatableBaseService::switchBlock($id)`** flips `is_blocked` and returns the new value

4. **Model (`User`)** — extends `Illuminate\Foundation\Auth\User`:
   - Traits: `BaseAuthModelTrait`, `HasFactory`, `SoftDeletes`, `CanRetrieve`, `HasApiTokens`
   - `BaseAuthModelTrait` brings: `Notifiable`, `BaseFilesTrait`, `GeneralTrait`, `FilterableTrait`, password mutator, `statusData()`, `getStatusLabelAttribute()`
   - Constants: `UPLOAD_DIRECTORY = 'users'`, `FILES = ['image']`, `RELATIONS = []`, `EXPORT_COLUMNS = [...]`
   - Optional: `static array $availableNotificationTypes` + `static getAvailableNotificationTypes()`
   - Custom `applyColumnFilter($query, $column, $value)` override — for columns where `LIKE` is wrong (booleans, enums)
   - Default `$attributes` set sensible defaults: `is_active => true`, `is_blocked => false`, `image => 'default.png'`
   - `$casts`: datetime fields cast to `'datetime'`, password to `'hashed'`, booleans to `'boolean'`
   - `$hidden`: password, remember_token
   - Optional: entity-specific mutators like `setPhoneNormalizedAttribute()` for computed columns

5. **Form Requests** — `StoreRequest` + `UpdateRequest` under `app/Http/Requests/Admin/{Model}/`:
   - Extend `BaseAdminRequest`
   - **`prepareForValidation()`** coerces booleans (`boolval($this->is_notify)`) and seeds non-user-controlled defaults (`is_active=true`, `is_completed=true` on store)
   - **`rules()`** — store: `required`; update: `nullable` on password
   - **Unique-with-ignore on update**: `'unique:users,email,' . $this->user` (route binding ID lives in `$this->{$entity_singular_snake}`)
   - Password rule: `Password::defaults()` from `Illuminate\Validation\Rules\Password`
   - Image rule: `'nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'`

6. **Factory (`UserFactory`)** — uses global `fake()` helper:
   - Pull FK values from existing rows: `Country::inRandomOrder()->first()->code`
   - Project helper for normalized phone: `PhoneNormalizer::normalize(...)`
   - Plain password string — model mutator hashes it
   - `email_verified_at => now()` for already-active users
   - Optional `unverified()` state method
   - `created_at` randomized in last year for realistic stats

7. **Seeder (`UserSeeder`)** — namespaced under entity: `Database\Seeders\User`:
   - Simple `factory()->count(N)->create()` for most cases
   - Registered in `DatabaseSeeder::run()` via `$this->call([UserSeeder::class])`

8. **PermissionSeeder** — auto-discovers all `admin.*` route names and inserts each as a permission. **No CRUD-specific entry required.**

### Frontend / Views

9. **Index view (`index.blade.php`)** — extends `admin.layouts.crud.index`:
   - `<x-table.statistics :loaderCards="N" />` ← exact count of stat cards
   - `<x-table.buttons>` with: `createRoute`, `hasNotification`, `hasDeleteAll`, `deleteAllRoute`, `hasEmail`, `hasReload`, `hasFilter`, `hasSearch`, `hasExport`, `exportCopy/Pdf/Excel/Word/Json`, `hasPagination`, `perPage`
   - `<x-table.filter>` with: `mainCol`, `hasStartDate`, `hasEndDate`, `hasOrderBy`, `hasRetrieve` (=$is_retreivable, auto-injected by `AdminBaseController::index`), `filters[]`
   - `<x-table.bulk-actions :hasDelete="true" :deleteRoute="...">` — visible bulk-delete UI (the button inside `<x-table.buttons>` is hidden)
   - `<x-table.table :hasCheckbox="true" :hasActions="true" :headers="[...]">` — owns the skeleton row + AJAX target
   - `<x-model.notification :route="..." :class="'App\Models\User'" />` — only when `HAS_NOTIFICATION=true`
   - `<x-model.email />` — only when `HAS_EMAIL=true`
   - `@push('js')` declares `var statsUrl = "{{ route('admin.users.statistics') }}";` then loads `stats.js`

10. **Table partial (`table.blade.php`)** — extends `admin.layouts.crud.table` and yields `@section('table')`:
    - `<tr class="data-rows users-table-row {{ $user->deleted_at ? 'deleted-table-row' : '' }}" data-user-id="{{ $user->id }}">`
    - Checkbox cell omitted (empty `<td></td>`) when row is soft-deleted
    - **Primary cell** is a flex row: avatar + name (`fw-semibold text-truncate`) + **mobile-only** stacked email/phone via `d-md-none text-muted small text-truncate`
    - Secondary columns hidden on mobile with `d-none d-md-table-cell`
    - **Status cell** uses `.user-status-pill` pattern (see Status Pill section below)
    - **Actions cell** uses `.users-action-btn .users-action-{view|edit|delete|restore|notify|email|more}` themed buttons
    - **"More" dropdown** (`.user-more-dropdown`) groups secondary actions (notification + email) — required to avoid `data-bs-toggle` collision between tooltip & dropdown on the same element
    - Restore button **replaces** edit + delete when `deleted_at` is set
    - Every notify/email button carries `data-id="{{ $user->id }}"`

11. **Create view (`create.blade.php`)** — extends `admin.layouts.crud.create`:
    - `<form class="mb-3 validated-form form" novalidate method="POST" action="..." enctype="multipart/form-data">`
    - `@csrf`
    - `<x-form.image>` **first**, `col-md-12`
    - All other `<x-form.*>` components in a single `<div class="row g-3">`
    - Submit row: `<div class="pt-4 d-flex justify-content-center mt-3">` with `btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button`

12. **Edit view (`edit.blade.php`)** — same as create but:
    - Action targets `route('admin.users.update', $id)`
    - `@method('PUT')` after `@csrf`
    - Every field passes `'value' => $user->...`
    - Password field has `'isRequired' => false`
    - Submit button is `btn-success` instead of `btn-primary`

13. **Show view (`show.blade.php`)** — extends `admin.layouts.crud.show`:
    - `@push('header')` provides title + edit/delete/back buttons (delete becomes restore for soft-deleted)
    - `@push('content')` is a `row g-4` with:
      - **Profile card** (`col-xl-4 col-md-5`): gradient avatar frame + name + email + status pill + role/type badges + inline `switch-block` toggle
      - **Details card** (`col-xl-8 col-md-7`): `<dl class="row mb-0">` with `dt.col-sm-4.text-muted` + `dd.col-sm-8.fw-semibold` pairs for every scalar field
    - `@push('js')` loads `admin-table.js` so the inline switch-block toggle works
    - The `admin.layouts.crud.show` layout already wraps everything in a `<div class="card">` and shows a **deleted-state banner** when `$model->deleted_at` is set — **do not duplicate this banner in the show view**

### JS / CSS pipeline (auto-loaded by `crud.index`)

14. **`admin.layouts.crud.index`** pushes:
    - CSS: `validation/form-validation.css`, `vendor/libs/sweetalert2/sweetalert2.css`, `css/filter.css`, `css/crud-stats.css`, `vendor/libs/apex-charts/apex-charts.css`
    - JS: `sweetalert2.js`, `apexcharts.js`, `extended-ui-sweetalert2.js`, `filter.js`, `admin-table.js`, `delete.js`, `restore.js`, `jqBootstrapValidation.js`, `submit-form.js`, `handel-error.js`, and the error-handler stack (`show-validation-on-inputs`, `show-block`, `show-un-authorize`, `show-unknown-error`)

15. **`admin-table.js` behaviors**:
    - `loadTable(filters)` calls `loadStats(filters.filters)` first → **stats and table refresh in sync** when filters change
    - `.switch-block` change handler **branches by context**: inside `<tr>` → revert switch and reload table; on show page → flip switch + update badge classes locally without reload
    - Per-page selector + custom per-page input
    - Pagination, filter form submit, reset, reload all delegate to `loadTable`
    - `.append-page-content` is the AJAX target; old `.data-rows` are removed on reload
    - Export handlers (copy/pdf/excel/word/json/print) wired via `.export-action[data-format]`

16. **`stats.js` behaviors**:
    - Container ID: `#usersStatsContainer` (the `<x-table.statistics>` component renders this exact ID — must remain stable)
    - On AJAX success: replaces `#usersStatsContent .row` HTML
    - Three visibility states: `hide_on_load`, `show_on_load`, `show_on_error`
    - Retry button: `.js-crud-stats-reload`
    - Auto-runs on `$(function(){ loadStats(); })` after script load

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

**`$is_retreivable` auto-availability** — `AdminBaseController::index()` automatically computes `$is_retreivable = $this->service->getIsRetreivable()` and exposes it to the index view. You don't need to pass it from the service. It evaluates to `true` only when the model uses both `SoftDeletes` + `CanRetrieve` (see [Soft-Delete + Restore Pattern](#soft-delete--restore-pattern)).

---

## Routes Pattern (`routes/admin.php`)

**Critical order:** All custom routes MUST be declared **BEFORE** `Route::resource(...)`. Otherwise Laravel binds `/{entity}/{anything}` to `show` first.

```php
// users routes
Route::delete('users/destroy-all', [UserController::class, 'destroyAll'])->name('users.destroyAll');
Route::put('users/{id}/switch-block', [UserController::class, 'switchBlock'])->name('users.switchBlock');
Route::put('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
Route::get('users/statistics', [UserController::class, 'statistics'])->name('users.statistics');
Route::get('users/diagrams', [UserController::class, 'diagrams'])->name('users.diagrams');
Route::resource('users', UserController::class);
```

**Rules:**
- `destroy-all` → `DELETE`, named `{entity}.destroyAll`
- `{id}/switch-block` → `PUT`, named `{entity}.switchBlock` (only when `HAS_BLOCK_TOGGLE=true`)
- `{id}/restore` → `PUT`, named `{entity}.restore` (only when `HAS_RESTORE=true`)
- `statistics` → `GET`, named `{entity}.statistics` (only when `INDEX_STATISTICS=true`)
- `diagrams` → `GET`, named `{entity}.diagrams` (only when `INDEX_DIAGRAMS=true`)

All routes are inside the `CheckRolePermission` middleware group (see existing `routes/admin.php`). The permission seeder auto-discovers route names matching `admin.*`, so each named route becomes a permission with no extra config.

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
- **Stats refresh with filters automatically** — `admin-table.js::loadTable()` calls `loadStats(filters)` before re-fetching the table.
- Container ID `#usersStatsContainer` is generated by `<x-table.statistics>` — **do not rename**; `stats.js` selects it by ID.

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

            {{-- Primary cell: avatar + name + (mobile-only) email/phone --}}
            <td>
                <div class="d-flex product-name align-items-center gap-2">
                    <div class="avatar-wrapper flex-shrink-0">
                        <div class="avatar rounded-2">
                            <img src="{{ $item->image }}" alt="{{ $item->name }}" class="rounded-2">
                        </div>
                    </div>
                    <div class="d-flex flex-column min-w-0">
                        <span class="{{entity}}-name fw-semibold text-truncate">{{ $item->name }}</span>
                        <span class="{{entity}}-email-mobile d-md-none text-muted small text-truncate">
                            {{ $item->email }}
                        </span>
                    </div>
                </div>
            </td>

            {{-- Secondary columns hidden on mobile --}}
            <td class="d-none d-md-table-cell">{{ $item->phone ?? '—' }}</td>
            <td class="d-none d-md-table-cell">{{ $item->email ?? '—' }}</td>

            {{-- Status pill + hidden form-switch toggle --}}
            <td class="{{entity}}-status-cell">
                <div class="{{entity}}-status-wrap">
                    @php
                        $isBlocked = $item->is_blocked;
                        $statusLabel = $item->statusData()['label'];
                    @endphp
                    <label class="{{entity}}-status-toggle"
                           title="{{ $isBlocked ? __('admin/main.unblock') : __('admin/main.block') }}">
                        <input class="form-check-input switch-block visually-hidden" type="checkbox" role="switch"
                               data-id="{{ $item->id }}"
                               data-route="{{ route('admin.{{entity}}.switchBlock', ['id' => $item->id]) }}"
                               data-active-label="{{ __('admin/main.active') }}"
                               data-blocked-label="{{ __('admin/main.blocked') }}"
                               {{ !$isBlocked ? 'checked' : '' }}
                               aria-label="{{ $isBlocked ? __('admin/main.click_to_unblock') : __('admin/main.click_to_block') }}">
                        <span class="{{entity}}-status-pill status-badge {{ $isBlocked ? 'is-blocked' : 'is-active' }}"
                              data-active-label="{{ __('admin/main.active') }}"
                              data-blocked-label="{{ __('admin/main.blocked') }}">
                            <span class="{{entity}}-status-pill__dot" aria-hidden="true"></span>
                            {{ $statusLabel }}
                        </span>
                    </label>
                </div>
            </td>

            {{-- Actions --}}
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
- Soft-deleted rows: empty checkbox `<td></td>` + add `deleted-table-row` class (drives red hover accent).
- **Primary cell pattern:** avatar + name; **stack** email/phone below name with `d-md-none text-muted small text-truncate` so the row is readable on mobile when other columns are hidden.
- Secondary data columns (phone, email, etc.): add `d-none d-md-table-cell` for mobile hiding.
- **Status pill is required when entity has `is_blocked` or boolean state** — uses `.{entity}-status-pill` with `.is-active` / `.is-blocked` modifiers, wraps a `.visually-hidden` `.switch-block` input, and exposes `data-active-label` / `data-blocked-label` so JS can swap labels without DOM mutation.
- NEVER put `data-bs-toggle` twice on the same element -- tooltip + dropdown on same button = conflict. Notify/email triggers go inside `dropdown-menu`.
- `data-id="{{ $item->id }}"` required on every notify and email button.
- Edit button hidden when `$item->deleted_at` is set; replaced with restore button.
- Bulk-delete uses checkbox selection — to prevent specific rows from being included (e.g. super-admin row), conditionally render the checkbox `<td>` empty.

---

## Status Pill Component (mandatory when entity has block/status toggle)

The CSS is already in `public/style/admin/css/filter.css` for `users-*`. For a new entity replicate the same selector set with the entity prefix, OR generalize: same markup, same class names, just swap `users-` → `{entity}-`.

**Markup pattern** (already shown in table row above):

```blade
<label class="{{entity}}-status-toggle" title="...">
    <input class="form-check-input switch-block visually-hidden" type="checkbox" role="switch"
           data-id="..." data-route="..."
           data-active-label="..." data-blocked-label="..."
           {{ !$isBlocked ? 'checked' : '' }}>
    <span class="{{entity}}-status-pill status-badge {{ $isBlocked ? 'is-blocked' : 'is-active' }}"
          data-active-label="..." data-blocked-label="...">
        <span class="{{entity}}-status-pill__dot" aria-hidden="true"></span>
        {{ $statusLabel }}
    </span>
</label>
```

**CSS contract (must exist in your entity-specific CSS or generic CSS):**

- `.{entity}-status-pill` — pill container (border, padding, transition)
- `.{entity}-status-pill.is-active` — success colors
- `.{entity}-status-pill.is-blocked` — warning/danger colors
- `.{entity}-status-pill__dot` — circular indicator; `.is-active` variant gets a pulsing `::after` ring
- `[dir='rtl'] .{entity}-status-pill` — flip dot order for RTL
- `.{entity}-status-toggle:hover .{entity}-status-pill` — hover state
- `.{entity}-status-toggle:has(.switch-block:focus-visible) .{entity}-status-pill` — focus ring

**JS contract** — already in `admin-table.js`:
- `.switch-block` change handler reads `data-route`, sends `PUT`, then:
  - Inside `<tr>`: reverts the switch + reloads the table via `loadTable(getFilters())`
  - Outside `<tr>` (show page): updates switch + swaps badge class between `bg-label-success` ↔ `bg-label-warning` and text between `data-active-label` ↔ `data-blocked-label`

---

## Table Row CSS Pattern (`{entity}-table-row`)

Every entity table row needs:

| CSS rule | Purpose |
|---|---|
| `.{entity}-table-row` + `position:relative; min-height:60px; transition: background var(--duration-fast)` | Smooth hover state |
| `.{entity}-table-row > td` + `padding-block: 0.9rem; color: var(--text-body)` | Row breathing room |
| `.{entity}-table-row:hover` + `background: var(--surface-overlay)` | Hover background |
| `.{entity}-table-row:hover > td:first-child` + `box-shadow: inset 3px 0 0 var(--color-brand-primary)` | Accent edge on hover (3px inset on the start side) |
| `[dir='rtl'] .{entity}-table-row:hover > td:first-child` + `box-shadow: inset -3px 0 0 var(--color-brand-primary)` | RTL-flipped accent edge |
| `.{entity}-table-row.deleted-table-row` + `background: rgba(var(--color-danger-rgb), 0.04)` | Soft-deleted row tint |
| `.{entity}-table-row.deleted-table-row:hover > td:first-child` + danger accent | RTL-aware danger edge |
| `.{entity}-actions-cell` + `width: 1%; white-space: nowrap; vertical-align: middle` | Actions column hugs its content |

These rules live in `filter.css` for users today. For a new CRUD, either:
- **Reuse**: rename via `find/replace` `users-` → `{entity}-` in the same CSS file (preferred for project consistency), **or**
- **Create**: a sibling CSS file `public/style/admin/css/{entity}.css` and `@push('css')` it from `index/show/create/edit`. Take all colors from `tokens.css`.

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

## Show Page Pattern (reference: `users/show.blade.php`)

```blade
@extends('admin.layouts.crud.show', ['model' => $user])

@push('header')
    <h5 class="mb-0">{{ __('admin/main.{{entity_singular}}_details') }}</h5>
    <div>
        @if ($user->deleted_at)
            <a href="#" data-id="{{ $user->id }}"
               data-route="{{ route('admin.{{entity}}.restore', ['id' => $user->id]) }}"
               class="btn btn-success me-2 restore-row">
                <i class="ti ti-arrow-back-up me-1"></i>{{ __('admin/main.restore') }}
            </a>
        @else
            <a href="{{ route('admin.{{entity}}.edit', $id) }}" class="btn btn-success me-2">
                <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
            </a>
            <a href="#" data-id="{{ $user->id }}"
               data-route="{{ route('admin.{{entity}}.destroy', ['{{entity_singular}}' => $user]) }}"
               class="btn btn-danger me-2 delete-record">
                <i class="ti ti-trash"></i> {{ __('admin/main.delete') }}
            </a>
        @endif
        <a href="{{ route('admin.{{entity}}.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
        </a>
    </div>
@endpush

@push('content')
    <div class="row g-4">
        {{-- Profile card (col-xl-4 col-md-5) --}}
        <div class="col-xl-4 col-md-5">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center p-4">
                    {{-- Gradient avatar frame --}}
                    <div class="mx-auto mb-3"
                         style="width: 148px; height: 148px; padding: 4px;
                                background: linear-gradient(135deg, rgba(115,103,240,.15), rgba(115,103,240,.05));
                                border-radius: 50%;">
                        <img src="{{ $user->image }}" alt="{{ $user->name }}" class="rounded-circle border"
                             style="width: 140px; height: 140px; object-fit: cover;" />
                    </div>
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <div class="text-muted mb-3">{{ $user->email }}</div>
                    <div class="d-flex justify-content-center flex-wrap gap-2 align-items-center">
                        <span class="badge status-badge {{ $user->statusData()['class'] }}"
                              data-active-label="{{ __('admin/main.active') }}"
                              data-blocked-label="{{ __('admin/main.blocked') }}">
                            {{ $user->statusData()['label'] }}
                        </span>
                        {{-- Block toggle (only if entity has is_blocked) --}}
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input switch-block" type="checkbox" role="switch"
                                   data-id="{{ $user->id }}"
                                   data-route="{{ route('admin.{{entity}}.switchBlock', ['id' => $user->id]) }}"
                                   {{ !$user->is_blocked ? 'checked' : '' }}
                                   title="{{ __('admin/main.blocked') }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Details card (col-xl-8 col-md-7) --}}
        <div class="col-xl-8 col-md-7">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="mb-0">{{ __('admin/main.{{entity_singular}}_details') }}</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted">{{ __('admin/main.name') }}</dt>
                        <dd class="col-sm-8 fw-semibold">{{ $user->name }}</dd>
                        {{-- repeat for every scalar attribute --}}
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script src="{{ asset('style/admin/custom-js/admin-table.js') }}"></script>
@endpush
```

**Show page rules:**
- The `admin.layouts.crud.show` layout already shows a deleted-state banner when `$model->deleted_at` is set — **do not duplicate it**.
- Profile-card pattern: gradient avatar frame + name + email + status pill + role/type chips + inline switch-block.
- Details-card pattern: `<dl class="row mb-0">` with `dt.col-sm-4.text-muted` + `dd.col-sm-8.fw-semibold` pairs.
- Pass relation data (counts, lists) via `showVars()` in the service; **never query in Blade**.
- Push `admin-table.js` in `@push('js')` so the inline switch-block works on this page.

---

## Form Requests (Store + Update)

**Location:** `app/Http/Requests/Admin/{Model}/{Store|Update}Request.php`

**Extend:** `App\Http\Requests\Admin\BaseAdminRequest`

### `StoreRequest` template

```php
namespace App\Http\Requests\Admin\{Model};

use App\Http\Requests\Admin\BaseAdminRequest;
use Illuminate\Validation\Rules\Password;

class StoreRequest extends BaseAdminRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'is_notify'    => boolval($this->is_notify),
            'is_active'    => true,         // sensible default for new rows
            'is_completed' => true,
        ]);
    }

    public function rules()
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:{{table}},email'],
            'country_code' => ['required', 'string', 'exists:countries,code', 'digits_between:3,5'],
            'phone'        => ['required', 'numeric', 'unique:{{table}},phone', 'digits_between:9,15'],
            'password'     => ['required', Password::defaults()],
            'image'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_notify'    => ['nullable', 'boolean'],
            // ... other rules derived from COLUMNS
        ];
    }
}
```

### `UpdateRequest` template

```php
namespace App\Http\Requests\Admin\{Model};

use App\Http\Requests\Admin\BaseAdminRequest;
use Illuminate\Validation\Rules\Password;

class UpdateRequest extends BaseAdminRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'is_notify' => boolval($this->is_notify),
        ]);
    }

    public function rules()
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            // Note: ',$this->{entity_singular}' = route binding ID -- ignore current row
            'email'        => ['required', 'email', 'unique:{{table}},email,' . $this->{{entity_singular}}],
            'country_code' => ['required', 'string', 'exists:countries,code', 'digits_between:3,5'],
            'phone'        => ['required', 'numeric', 'unique:{{table}},phone,' . $this->{{entity_singular}}, 'digits_between:9,15'],
            'password'     => ['nullable', Password::defaults()],  // optional on update
            'image'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_notify'    => ['nullable', 'boolean'],
        ];
    }
}
```

**Form Request rules:**
- `prepareForValidation()` coerces booleans (`boolval(...)`) and seeds **system defaults** (not user-controlled fields).
- Unique-with-ignore on update: append `,' . $this->{{entity_singular}}` where `{{entity_singular}}` is the route param name (Laravel auto-binds it).
- Password: `Password::defaults()` rule from `Illuminate\Validation\Rules\Password`. `required` on store, `nullable` on update.
- Image: `'nullable', 'image', 'mimes:...', 'max:2048'` (2 MB default).
- `exists:` rules must match the FK target table + column.

---

## Model Pattern

### Auth-style entities (with login)

```php
namespace App\Models;

use App\Enums\ModelNotificationType;
use App\Traits\Models\BaseAuthModelTrait;
use App\Traits\Models\CanRetrieve;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class {{Model}} extends Authenticatable
{
    use BaseAuthModelTrait, HasFactory, SoftDeletes, CanRetrieve, HasApiTokens;

    protected static array $availableNotificationTypes = [
        ModelNotificationType::ACTIVE,
    ];

    protected $fillable = [/* from COLUMNS */];
    protected $casts    = [/* datetimes => 'datetime', booleans => 'boolean', password => 'hashed' */];
    protected $attributes = [
        'is_active'  => true,
        'is_blocked' => false,
        'image'      => 'default.png',
    ];
    protected $hidden = ['password', 'remember_token'];

    protected const UPLOAD_DIRECTORY = '{{entity_plural_snake}}';
    protected const FILES            = ['image'];
    public    const RELATIONS        = [/* relation method names */];

    public const EXPORT_COLUMNS = [
        ['key' => 'id',         'label' => 'admin/main.id'],
        ['key' => 'name',       'label' => 'admin/main.name'],
        // ... only columns explicitly chosen for export
    ];

    public static function getAvailableNotificationTypes(): array {
        return self::$availableNotificationTypes;
    }

    // Override only when a column needs equality / scope (not LIKE)
    protected function applyColumnFilter($query, $column, $value): void
    {
        if ($column === 'is_blocked') {
            if ($value === 'blocked_only') { $query->where('is_blocked', true); return; }
            if ($value === 'not_blocked')  { $query->where('is_blocked', false); return; }
            return;
        }
        $query->where($column, 'like', '%' . $value . '%');
    }
}
```

### Regular entities (no login)

Extend `Illuminate\Database\Eloquent\Model`, use `BaseModelTrait` (brings `GeneralTrait`, `InteractsWithMedia`, `FilterableTrait`, `HasDynamicRelations`, `ModelHasCacheTrait`). Drop `HasApiTokens` and the password mutator.

**Model rules:**
- Constants `UPLOAD_DIRECTORY`, `FILES`, `RELATIONS`, `EXPORT_COLUMNS` are required for the base service contract.
- `RELATIONS` drives eager-loading in `show()` / `edit()` (`CrudBaseService`).
- `EXPORT_COLUMNS` items can be: `['key' => 'col', 'label' => 'admin/main.key']` — labels resolved by `ExportService`.
- `$attributes` defaults reduce NULL handling everywhere downstream.
- `$casts` enforces type at the model layer — never rely on string coercion.
- Override `applyColumnFilter` whenever a filter value should produce something other than `WHERE column LIKE '%v%'` (booleans, enums, ranges).
- Custom scope methods (`scopeStatus`, etc.) auto-resolve via `FilterableTrait::applyScopeFilter` when `column` doesn't exist as a table column.

---

## Service Pattern

### Authenticatable entities

```php
namespace App\Services\Admin;

use App\Models\{{Model}};
use App\Models\Country;
use App\Services\Admin\Base\AuthenticatableBaseService;

class {{Model}}Service extends AuthenticatableBaseService
{
    public function __construct()
    {
        parent::__construct({{Model}}::class);
    }

    public function indexVars(): array
    {
        return [/* data needed by index.blade besides the table */];
    }

    public function createVars(): array
    {
        return [
            'countries'                   => Country::where('is_active', true)
                                                     ->forSelect(['code as id', 'code as name'])->toArray(),
            'receiveNotificationsOptions' => [
                ['id' => true,  'name' => __('admin/main.yes')],
                ['id' => false, 'name' => __('admin/main.no')],
            ],
            // ... any other select options
        ];
    }

    public function editVars(): array
    {
        return $this->createVars();
    }

    public function showVars(): array
    {
        return [/* extra variables for the show view that aren't on the model itself */];
    }
}
```

### Regular entities

Extend `App\Services\Admin\Base\CrudBaseService` instead. Drop the `AuthenticatableBaseService` parent. No `switchBlock` available.

**Service rules:**
- One service per CRUD; controller stays thin.
- `createVars()` / `editVars()` return only **non-model** data needed by the form (select options, dropdown lists).
- `showVars()` returns only **non-model** data needed by show (e.g. related counts / lists).
- For relations, declare in `Model::RELATIONS` — `CrudBaseService::show()` and `edit()` auto-eager-load them.
- Use `Country::where('is_active', true)->forSelect(['code as id', 'code as name'])->toArray()` pattern when feeding selects from FK tables (filters out inactive options).
- Use the `forSelect()` scope when you have one — keeps the `id`/`name` shape `<x-form.select>` expects.

---

## Soft-Delete + Restore Pattern

When `SOFT_DELETES=true`:

1. Migration uses `$table->softDeletes()`.
2. Model uses `SoftDeletes` trait. To enable the "Retrieve deleted" filter and full relation restore, **also add `CanRetrieve`** (auto-validates that all `RELATIONS` targets use `SoftDeletes`).
3. Routes include `PUT /{id}/restore` → `restore` (only when `HAS_RESTORE=true`).
4. Filter prop `:hasRetrieve="$is_retreivable"` enables the toggle. `$is_retreivable` is automatically passed by `AdminBaseController::index()` and resolves to `true` only when the model uses both `SoftDeletes` + `CanRetrieve`.
5. Table row: when `$item->deleted_at` is set, render `deleted-table-row` class + empty checkbox `<td></td>` + restore action instead of edit/delete.
6. `restore.js` (auto-loaded by `crud.index`) handles the AJAX confirmation.

---

## Block Toggle Pattern

When `HAS_BLOCK_TOGGLE=true`:

1. Migration has `is_blocked` boolean column (default `false`).
2. Model uses `BaseAuthModelTrait` (provides `statusData()` + `getStatusLabelAttribute()`).
3. Routes include `PUT /{id}/switch-block` → `switchBlock` named `{entity}.switchBlock`.
4. Service extends `AuthenticatableBaseService` (NOT plain `CrudBaseService`).
5. Controller extends `AuthenticatableBaseController` — inherits the `switchBlock()` action.
6. Filter exposes a select with `blocked_only` / `not_blocked` values (handled by your model's `applyColumnFilter` override).
7. Table row renders the [Status Pill Component](#status-pill-component-mandatory-when-entity-has-blockstatus-toggle).
8. Show page renders a `.switch-block` checkbox in the profile card.
9. `admin-table.js` handles AJAX + UI sync automatically — no extra JS needed.

---

## Factory Pattern

```php
namespace Database\Factories;

use App\Models\Country;
use App\Support\PhoneNormalizer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class {{Model}}Factory extends Factory
{
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'phone'             => PhoneNormalizer::normalize($this->faker->unique()->regexify('\05[0-9]{8}')),
            'country_code'      => Country::inRandomOrder()->first()->code,
            'email'             => fake()->unique()->userName() . '@gmail.com',
            'email_verified_at' => now(),
            'password'          => 'password',           // model mutator will hash
            'remember_token'    => Str::random(10),
            'created_at'        => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attrs) => ['email_verified_at' => null]);
    }
}
```

**Factory rules:**
- Use global `fake()` helper (Laravel 9+).
- For FKs, pull from existing rows: `RelatedModel::inRandomOrder()->first()->key`.
- For translatable fields with `MULTILANG=true`, use `ar_SA` locale OR explicit Arabic strings — never English for the `ar` locale.
- Use project utilities where available (`PhoneNormalizer`, etc.).
- Spread `created_at` over time when statistics need realistic distributions.

---

## Seeder Pattern

```php
namespace Database\Seeders\{{Model}};

use App\Models\{{Model}};
use Illuminate\Database\Seeder;

class {{Model}}Seeder extends Seeder
{
    public function run(): void
    {
        {{Model}}::factory()->count({{SEED_ROWS}})->create();
    }
}
```

Registered in `DatabaseSeeder::run()`:

```php
$this->call([
    // ...
    {{Model}}Seeder::class,
]);
```

**Seeder rules:**
- Seeder lives in `database/seeders/{Model}/{Model}Seeder.php` (nested by entity).
- For large counts (> 200 with related rows), use bulk insert pattern: `DB::table(...)->insert($chunk)` to avoid Eloquent event overhead.
- **Permissions seeder** (`database/seeders/Admin/PermissionSeeder.php`) auto-discovers every `admin.*` route → no per-CRUD permission entries needed.
- Arabic locale values for translatable fields must be **real Arabic text**, not English.

---

## Sidebar Registration (`config/sidebar_routes.php`)

```php
return [
    'admin' => [
        // ...
        '{{entity_plural_snake}}' => [
            // 'group'     => 'admin_roles_management',     // OPTIONAL — only when grouped under a section header
            'has_child' => {{SIDEBAR_DISPLAY_MODE === 'dropdown' ? 'true' : 'false'}},
            'icon'      => '<i class="ti ti-{ICON} me-2"></i>',
            'childes'   => [
                // Sub-routes if SIDEBAR_DISPLAY_MODE='dropdown' and the section has more than just index.
                // Can be empty array when dropdown UX is desired but only an index exists.
            ],
        ],
    ],
];
```

**Sidebar rules:**
- Entry key = `{{entity_plural_snake}}`.
- `has_child: false` → single link to index (matches the `home` pattern).
- `has_child: true` → dropdown parent. `childes` can be empty (just enables the dropdown chevron).
- `group` is **optional** — only set when this entry belongs to a labeled section (like `admin_roles_management` for admins + roles together).
- Icon must use Tabler Icons class (`ti ti-*`) with the `me-2` spacer.
- Title is auto-resolved via `admin.routes.{{entity_plural_snake}}.index` translation.

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
            'destroyAll' => 'حذف {{ENTITY_PLURAL_AR}}',
            'statistics' => 'إحصائيات {{ENTITY_PLURAL_AR}}',
            'switchBlock'=> 'تغيير حالة {{ENTITY_SINGULAR_AR}}',
            'restore'    => 'استرجاع {{ENTITY_SINGULAR_AR}}',
            'diagrams'   => 'رسوم بيانية {{ENTITY_PLURAL_AR}}',
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
        'destroyAll' => 'Delete {{ENTITY_PLURAL_EN}}',
        'statistics' => '{{ENTITY_PLURAL_EN}} Statistics',
        'switchBlock'=> 'Toggle Block {{ENTITY_SINGULAR_EN}}',
        'restore'    => 'Restore {{ENTITY_SINGULAR_EN}}',
        'diagrams'   => '{{ENTITY_PLURAL_EN}} Diagrams',
    ],
];
```

**Always-required keys:** `index`, `create`, `store`, `update`, `edit`, `show`, `destroy`, `destroyAll`.
**Add custom keys** for: `statistics`, `restore`, `switchBlock`, `diagrams`, and any entity-specific routes.

---

## Translations Audit (after coding)

After implementation, audit every user-facing string:

| File | Must contain |
|---|---|
| `lang/{ar,en}/admin/main.php` | Every `__('admin/main.*')` key used in views (table headers, button labels, statistics labels, empty states, status labels) |
| `lang/{ar,en}/admin/inputs.php` | Every form label, placeholder, help text from create/edit |
| `lang/{ar,en}/admin/routes.php` | Full nested section per [Routes Translations Pattern](#routes-translations-pattern) |
| `lang/{ar,en}/admin/validation.php` and/or `lang/{ar,en}/validation.php` | Attribute names + custom rule messages used by your Store/Update requests |

**No orphaned keys** in either locale. No English-only or Arabic-only halves unless intentionally skipped.

---

## Critical Rules

1. **Only use passed columns:** Build CRUD (migration, model fillable, form fields, table columns, validation, factory, export) strictly from the columns provided in `{{COLUMNS}}`. Never infer, assume, or add extra columns beyond what was explicitly listed.
2. **Controller + Service layer (project standard):** Implement a **Service** class extending `CrudBaseService` (or `AuthenticatableBaseService` when applicable). Put query building, filtering, `indexVars` / `createVars` / `editVars` / `showVars`, export, and transactional persistence in the Service. The **Controller** extends `AdminBaseController` (or `AuthenticatableBaseController`) and stays thin: delegate to the service, return views/responses/JSON. **Do not** duplicate large query blocks in Blade.
3. **Controllers pass everything to views:** Every variable a Blade view needs must be passed from the Controller/Service (`showVars`, `edit`, etc.). Blade files must NEVER run queries, call model scopes, or access DB directly. Use `compact()` or explicit `->with()` / merged arrays from the service.
4. **File inputs render at the top of forms:** In create and edit views, file/image upload fields must appear BEFORE all other form fields (text, select, textarea, etc.).
5. **Export support:** Use the model's `EXPORT_COLUMNS` (label keys) and `CrudBaseService::export()` / `ExportService` like existing admin CRUD. Ask for export types and columns; respect `AdminBaseController` export trigger (`?export=` on index request).
6. **Route translations must be complete:** When adding Admin routes translations, add **all** route keys for the section (index, create, store, update, edit, show, destroy, destroyAll, statistics, restore, switchBlock, diagrams, and any custom actions) in both `lang/ar/admin/routes.php` and `lang/en/admin/routes.php`. Follow the same structure as `users`, `admins`, `roles`.
7. **Translations review (inputs + pages):** After implementation, **audit every user-facing string** on the new module against language files (see [Translations Audit](#translations-audit-after-coding)).
8. **Seeder Arabic locale:** For entities with `MULTILANG=true`, the Seeder MUST fill translatable columns per locale. For the **ar** locale, all translatable field values MUST be real **Arabic** text, not English or placeholder strings. Use Faker with `ar_SA` or explicit Arabic strings.
9. **Statistics / diagrams behavior:** If `INDEX_STATISTICS` or `INDEX_DIAGRAMS` is true, follow the **Users** pattern: statistics cards via dedicated route + partial, `crud-stats` CSS, optional ApexCharts (already included in `admin.layouts.crud.index`). Stats refresh with table filters via `loadStats()` invoked inside `loadTable()`.
10. **Email and Notification modals:** Include `<x-model.notification>` ONLY when `HAS_NOTIFICATION=true`. Include `<x-model.email>` ONLY when `HAS_EMAIL=true`. Both require `data-id="{{ $item->id }}"` on every row trigger button inside the "more" dropdown.
11. **Bulk-actions bar:** Always include `<x-table.bulk-actions>` when `hasDeleteAll=true`. The delete-all button inside `<x-table.buttons>` is hidden (d-none) -- the visible delete UI is the bulk-actions bar.
12. **Form grid -- no orphaned inputs:** Every row in `<div class="row g-3">` must sum to exactly **12 Bootstrap columns**. Multilingual field with `isMultiLanguage=true` at `col-md-6` generates one `col-md-6` per locale (2 locales x 6 = 12) -- that row is consumed. Widen or add fields so each row totals 12. See **Form Pattern** above.
13. **Form action buttons:** Create -> `btn btn-primary`. Edit -> `btn btn-success`. Both must include `waves-effect waves-light submit-button`. Container: `<div class="pt-4 d-flex justify-content-center mt-3">`. Never swap colors or omit `submit-button`.
14. **Boolean / status form fields:** Use `x-form.select` with semantic options from `admin/main` keys -- never generic Yes/No strings. Options for reused lists come from controller/service. Default `value` must be set to the sensible initial state.
15. **Status pill in tables when block/status toggle exists:** Always use the `.{entity}-status-pill` + hidden `switch-block` checkbox pattern (not bare `.badge` + raw `form-switch`). Include `data-active-label` / `data-blocked-label` on both the input and the badge.
16. **Mobile-compact primary cell:** When secondary columns are hidden on mobile via `d-none d-md-table-cell`, stack the corresponding info (email/phone/role) **inside** the primary cell with `d-md-none text-muted small text-truncate` so the row stays readable.
17. **`applyColumnFilter` override required for non-LIKE filters:** When the index has a boolean or enum filter, override `applyColumnFilter($query, $column, $value)` on the model to apply equality/scope instead of LIKE.
18. **Custom routes BEFORE `Route::resource`:** All extra GET/PUT/DELETE lines (destroyAll, switchBlock, restore, statistics, diagrams) must be declared **before** `Route::resource(...)` in `routes/admin.php`. Otherwise Laravel will route them to `show` first.
19. **Soft delete + restore requires `CanRetrieve`:** Just `SoftDeletes` alone does not enable the "Retrieve deleted" filter. Add `App\Traits\Models\CanRetrieve` to the model — it also validates that every relation in `RELATIONS` targets a soft-deletable model.
20. **`$is_retreivable` is auto-injected:** Do not pass it manually from the service. `AdminBaseController::index()` resolves it from the model.
21. **Stats container ID is stable:** `<x-table.statistics>` renders `#usersStatsContainer`; `stats.js` selects by that exact ID. Do not rename.
22. **Permission registration is automatic:** The PermissionSeeder discovers all `admin.*` route names. Do not add per-CRUD entries.
23. **PhoneNormalizer + active-only FK selects:** Use `App\Support\PhoneNormalizer::normalize()` for any phone column. Use `where('is_active', true)` on FK lookups feeding selects (countries, etc.).

---

## Table skeleton loader (post-change verification)

When the index **table columns**, **checkbox column**, or **actions column** change, **re-verify** the lazy-load UX:

1. **`resources/views/admin/{entity}/index.blade.php`** — `<x-table.table>` props `:headers`, `:hasCheckbox`, and `:hasActions` must match the real table: same column count/order as the AJAX partial. The component computes skeleton **`colspan`** and skeleton cells from these props (`resources/views/components/table/table.blade.php`).
2. **`resources/views/admin/{entity}/table.blade.php`** (or equivalent partial returned by `AdminBaseController::index` for AJAX) — Each data row must keep **`tr.data-rows`** so `admin-table.js` can remove old rows before showing the skeleton; follow `admin.layouts.crud.table` + reference entity row structure.
3. **`hideTableLoader` flow** — Injected HTML is appended to **`.append-page-content`**; ensure the partial still supplies the same wrapper row structure the reference uses so the skeleton hides cleanly and pagination/scripts keep working.
4. **Statistics cards loader** — If `<x-table.statistics>` is used, align `:loaderCards` and card layout with the reference (e.g. users) so placeholder count matches the final grid.
5. **Skeleton row count is fixed at 9** inside `<x-table.table>` (`@php $rowsCount = 9; @endphp`). This is intentional — not configurable per CRUD.

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
| 15 | Permission seeder / permissions (auto via `database/seeders/Admin/PermissionSeeder.php`) | What is the status? Create / Exists / Skip |
| 16 | DatabaseSeeder registration | What is the status? Create / Exists / Skip |
| 17 | Export (via model `EXPORT_COLUMNS` + service) | What is the status? Create / Exists / Skip |
| 18 | Entity CSS file (`public/style/admin/css/{entity}.css` for table row styles, status pill, action buttons) | What is the status? Create / Exists / Skip |
| 19 | Optional: Policies, Notifications, Mailables | What is the status? Create / Exists / Skip |

---

### B) Project Paths / References

- What is the Laravel version? `{{LARAVEL_VERSION}}` *(default: 12)*
- Which existing CRUD entity/folder should be used as a style reference? `{{REFERENCE_CRUD_ENTITY}}` *(default: **users**)*

**Core paths (defaults for this codebase):**

- What is the admin routes file path? `{{ADMIN_ROUTES_FILE}}` *(default: **routes/admin.php**)*
- What is the sidebar routes file path? `{{SIDEBAR_ROUTES_FILE}}` *(default: **config/sidebar_routes.php**)*
- What is the DatabaseSeeder path? `{{DATABASE_SEEDER_FILE}}` *(default: database/seeders/DatabaseSeeder.php)*

**Translation paths:**

- AR routes: `{{ADMIN_ROUTES_LANG_PATH_AR}}` *(default: lang/ar/admin/routes.php)*
- EN routes: `{{ADMIN_ROUTES_LANG_PATH_EN}}` *(default: lang/en/admin/routes.php)*
- AR inputs: `{{ADMIN_INPUTS_LANG_PATH_AR}}` *(default: lang/ar/admin/inputs.php)*
- EN inputs: `{{ADMIN_INPUTS_LANG_PATH_EN}}` *(default: lang/en/admin/inputs.php)*
- AR main:   `{{ADMIN_MAIN_LANG_PATH_AR}}`   *(default: lang/ar/admin/main.php)*
- EN main:   `{{ADMIN_MAIN_LANG_PATH_EN}}`   *(default: lang/en/admin/main.php)*

**Translation strategy:**

- Use translation keys instead of hardcoded strings? `{{USE_TRANSLATIONS}}` *(true | false)*
- Translation namespace prefix? `{{TRANS_PREFIX}}` *(default: "admin")*
- Add route translation entries? `{{ADD_ROUTE_TRANSLATIONS}}` *(true | false)*
- Add input translation entries? `{{ADD_INPUT_TRANSLATIONS}}` *(true | false)*
- Add main translation entries? `{{ADD_MAIN_TRANSLATIONS}}` *(true | false)*

**Route translations rule:** When `ADD_ROUTE_TRANSLATIONS=true`, you MUST add **all** route keys for the CRUD section in both AR and EN. Include custom actions (statistics, diagrams, switch-*, restore, etc.) if those routes exist.

---

### C) CRUD Identity

You must derive everything below dynamically from the model/class name, unless overridden.

**Required:**

- Model/class name (English, singular, StudlyCase): `{{MODEL_NAME}}`
- Arabic entity name (singular): `{{ENTITY_SINGULAR_AR}}`
- Arabic entity name (plural): `{{ENTITY_PLURAL_AR}}`

**Optional overrides** (leave empty to auto-generate from MODEL_NAME):

- Entity plural in English: `{{ENTITY_PLURAL_EN}}`
- Table name: `{{TABLE_NAME}}`
- Route prefix: `{{ROUTE_PREFIX}}`
- Controller namespace/path: `{{CONTROLLER_PATH}}`
- Views folder: `{{VIEWS_PATH}}`
- Primary key: `{{PRIMARY_KEY}}` *(default: id)*

**AUTO-DERIVATION RULES:**

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

- Soft deletes? `{{SOFT_DELETES}}` *(true | false)*
- Multi-language translations for fields? `{{MULTILANG}}` *(true | false)*
- Supported locales (if MULTILANG=true): `{{LOCALES}}` *(default: ["ar","en"])*
- Authenticatable (login + block/unblock + Sanctum)? `{{IS_AUTHENTICATABLE}}` *(true | false)*
- Block toggle? `{{HAS_BLOCK_TOGGLE}}` *(true | false — usually equals IS_AUTHENTICATABLE)*
- Restore action? `{{HAS_RESTORE}}` *(true | false — usually true when SOFT_DELETES=true)*

---

### D) Sidebar Registration (`config/sidebar_routes.php`)

**Mandatory question (do not skip, do not guess):** Should this menu item behave as a **single link** or a **dropdown**?

- `{{SIDEBAR_DISPLAY_MODE}}` *(**single** | **dropdown**)* — **Ask in Step 1** whenever `ADD_TO_SIDEBAR=true`.
  - **`single`:** one clickable entry pointing at the index route; set `has_child` = **false**.
  - **`dropdown`:** parent entry with `has_child` = **true**; fill `childes` when there are multiple child routes (sub-pages). Empty `childes` is OK when only the index exists but dropdown UX is desired.

Additional questions:

- Add to sidebar_routes.php? `{{ADD_TO_SIDEBAR}}` *(true | false)*
- Inside a sidebar **group** (e.g. `admin_roles_management`)? `{{SIDEBAR_GROUP_KEY}}` *(string | empty)*
- Icon HTML class? `{{SIDEBAR_ICON}}` *(e.g. `ti ti-world`)*
- Title translation key under `admin.routes`? `{{SIDEBAR_TITLE_KEY}}`
- Permission name (if any)? `{{SIDEBAR_PERMISSION}}`

**Rule:** If `USE_TRANSLATIONS=true`, the sidebar title must resolve via `admin.routes.*` keys like existing modules.

---

### E) Model Config

- Fillable: derived strictly from `{{COLUMNS}}` field names (never add unlisted fields).
- Translated fields (if MULTILANG=true): `{{TRANSLATED_FIELDS}}` *(empty array if MULTILANG=false)*
- Casts: derived from `{{COLUMNS}}` type definitions.
- Attributes defaults: derived from `{{COLUMNS}}` default values + sensible defaults for booleans (`is_active=true`, `is_blocked=false`, `image='default.png'` for auth entities).
- Upload directory constant: `{{UPLOAD_DIRECTORY}}` *(default: entity_plural_snake)*

**FILES upload config:**

What are the file upload fields? `{{FILES}}`

Format:
```
[
  {"field":"avatar","disk":"public","dir":"","mimes":"jpg,png,webp","max_kb":2048,"nullable":true}
]
```

Rule: If `FILES[].dir` empty => use entity_plural_snake.

**RELATIONS:**

What are the model relations? `{{RELATIONS}}` *(list relation method names to eager-load; align with `Model::RELATIONS` constant used by `CrudBaseService::show` and `edit`)*

**`applyColumnFilter` override list:**

Which columns need NON-LIKE filtering (booleans, enums, ranges)? `{{NON_LIKE_FILTERS}}` *(array of column names — each will get an `if ($column === '...')` branch in the override)*

---

### F) Columns (core data — single source of truth)

What are the columns for this CRUD? `{{COLUMNS}}`

Format: `[ ... ]`

**Rules:**
- Table headers MUST use `admin/main` translation keys and you must add them when `ADD_MAIN_TRANSLATIONS=true`.
- `{{COLUMNS}}` is the **single source of truth** for the entire CRUD. Migration columns, model fillable, form fields, validation rules, factory definitions, table columns, and export columns are ALL derived exclusively from this list.
- Do NOT add any column that is not explicitly present in `{{COLUMNS}}`.

---

### G) Index actions, filters, statistics, diagrams

**Index actions:** What extra toolbar actions are needed (copy Users: notification, email, reload, export formats)? `{{INDEX_ACTIONS}}`

**Filters (mandatory breadth):** The index **must** support every filter that is meaningful for `{{COLUMNS}}` and for list UX, implemented via `<x-table.filter>` + the model's `scopeSearch` / filter handling (see `CrudBaseService::index` + `$request->filters`).

Ask explicitly:

- Date range? `{{FILTER_DATE_RANGE}}` → `hasStartDate` / `hasEndDate`
- Order? `{{FILTER_ORDER_BY}}` → `hasOrderBy`
- "Retrieve deleted" filter (only when soft-deletes + CanRetrieve)? `{{FILTER_RETRIEVE}}` → `hasRetrieve` (pass `$is_retreivable` which is auto-injected)
- Per-field filters: `{{INDEX_FILTERS_CONFIG}}` — array of `{ type, name, options? }` for **every** filterable field derived from `{{COLUMNS}}`. Text fields → `text`; boolean/status → `select` with semantic options; foreign keys → `select` with options from `indexVars()`.

**Statistics (index):**

- `{{INDEX_STATISTICS}}` *(true | false)*
- If true: card list and data sources `{{STATISTICS_CARDS}}` (follow Users adapted per entity)
- Implementation: `GET .../statistics` route + Blade partial + `statsUrl` + `stats.js` + `<x-table.statistics>`.

**Diagrams (index) — ask separately:**

- `{{INDEX_DIAGRAMS}}` *(true | false)* — independent of stats cards
- If true: `{{DIAGRAM_ITEMS}}` — `[{"type":"pie|bar|donut","label_key":"admin/main...","data_key":"...","colors":[...]}]`
- Optional route: `GET admin/{entity}/diagrams`

**Diagrams rules:**

1. Use `admin/main.statistics_charts` / `admin/main.diagrams*` keys.
2. ApexCharts only (already bundled in `crud.index` layout).
3. Controller/service prepares datasets; Blade only renders.

---

### G2) Show page — related relations and overview

**Goal:** On **show**, present **all** related data the admin should see: scalar columns + **relations** declared in `{{RELATIONS}}`.

Ask:

- How should related data be presented? `{{SHOW_RELATED_PRESENTATION}}` *(**stats** | **tables** | **both**)*
  - **stats:** compact metric cards / summary rows (counts).
  - **tables:** sortable/paginated tables for `hasMany` / `belongsToMany`.
  - **both:** counts on top + detailed tables below.

Implementation rules:

1. Extend `showVars()` in the Service to pass:
   - Main model `::with(RELATIONS...)`
   - Per relation: counts and/or limited collections (latest 10) or full paginator.
2. **Never** query inside Blade; pass named variables for each block.
3. Align with `admin.layouts.crud.show` + **Users** show layout patterns (profile card + details column).

---

### G3) Export Configuration

- Enable export via index request? `{{ENABLE_EXPORT}}` *(true | false)* — `AdminBaseController` checks `$request->has('export')`.
- Export types? `{{EXPORT_TYPES}}` *(copy/excel/pdf/word/json as supported by `ExportService`)*
- Model-defined `EXPORT_COLUMNS` lists keys and `label` translation keys — confirm subset or full `{{COLUMNS}}`.

---

### G4) Map / Location on show page

- Has map/location fields? `{{HAS_MAP}}` *(true | false)*
- Map provider/component: project's existing map component for read-only display.

**Rule:** If COLUMNS include lat/lng, the **show** view MUST display the location as a **map** when coordinates exist, not only raw numbers.

---

### H) Form Requests

- Store request class name: `{{STORE_REQUEST_CLASS}}` *(default: Store{MODEL_NAME}Request → matches existing pattern of class StoreRequest namespaced under Model)*
- Update request class name: `{{UPDATE_REQUEST_CLASS}}` *(default: Update{MODEL_NAME}Request → matches existing pattern of class UpdateRequest namespaced under Model)*
- Defaults to set in `prepareForValidation()` (store): `{{STORE_DEFAULTS}}` *(e.g. is_active=true, is_completed=true)*
- Boolean coercions to apply in `prepareForValidation()`: `{{BOOLEAN_COERCIONS}}` *(e.g. is_notify via boolval)*

> Note: project convention places the request file under `app/Http/Requests/Admin/{Model}/StoreRequest.php` and `.../UpdateRequest.php` — class name is simply `StoreRequest` / `UpdateRequest` with the model in the namespace. `AdminBaseController` resolves both by convention.

---

### I) Factory + Seeder + DatabaseSeeder Hook

- Use factory? `{{USE_FACTORY}}` *(true | false)*
- Faker locale: `{{FAKER_LOCALE}}` *(default: "ar_SA")*
- Seeder class name: `{{SEEDER_CLASS}}` *(default: {MODEL_NAME}Seeder)*
- Number of rows: `{{SEED_ROWS}}` *(default: 100)*
- DatabaseSeeder guard: `{{DB_SEEDER_GUARD}}` *(none | local_only | custom)*
- Use bulk insert (`DB::table()->insert($chunk)`) for > 200 rows? `{{BULK_SEED}}` *(true | false)*

**Seeder translatable rule:** When `MULTILANG=true`, AR values must be real Arabic text.

---

## 2) Guidelines (follow strictly)

### Questions Phase
- **First response MUST be questions only:** Print the Component Checklist and ask Create/Exists/Skip for each component.
- **Always ask all Step 1 questions:**
  1. Statistics cards? → `INDEX_STATISTICS` (if yes → which cards?)
  2. Diagrams/charts? → `INDEX_DIAGRAMS` (independent)
  3. Export? Formats? → `ENABLE_EXPORT` + `EXPORT_TYPES`
  4. Bulk email button? → `HAS_EMAIL`
  5. Bulk notification button? → `HAS_NOTIFICATION`
  6. Sidebar: single or dropdown? → `SIDEBAR_DISPLAY_MODE` (when ADD_TO_SIDEBAR=true)
  7. Show page relations: tiles / tables / both? → `SHOW_RELATED_PRESENTATION`
  8. Block toggle? → `HAS_BLOCK_TOGGLE`
  9. Soft-delete + restore? → `SOFT_DELETES` + `HAS_RESTORE`
- **Filters:** Confirm full filter set: date range, order, retrieve (soft deletes), per-column filters.
- **Map on show:** If lat/lng exist, confirm read-only map.
- **Translations:** Confirm AR+EN coverage plan; run review after coding (Critical Rules rule 7).
- **Do not generate code until all inputs are confirmed.**

### Code Generation Rules
- When you update an existing file, output **full updated file** + **CHANGES** list.
- Keep naming consistent with derived values unless overridden.
- Follow **`users`** (or `{{REFERENCE_CRUD_ENTITY}}`) file structure, **components**, and **CSS/classes** for tables, statistics, buttons, and inputs — see **Visual/UI parity (mandatory)** at the top.
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
- Create button: `btn-primary` | Edit button: `btn-success` | Both: `waves-effect waves-light submit-button` | Container: `pt-4 d-flex justify-content-center mt-3`.
- Boolean/status fields: `x-form.select` with semantic `admin/main` translation labels.

### Table Row
- `data-rows {{entity}}-table-row` + `data-{entity}-id` always.
- `{{entity}}-status-pill` pattern for block/status toggles.
- Mobile-compact primary cell with stacked info hidden on desktop (`d-md-none`).
- Themed `{{entity}}-action-*` buttons + `more` dropdown for secondary actions.

### Index: filters + statistics + diagrams
- `<x-table.filter>`: set `hasStartDate`, `hasEndDate`, `hasOrderBy`, `hasRetrieve` as appropriate; pass a **complete** `filters` array.
- Statistics/diagrams: follow **Users** wiring.

### Show page
- All **relations** from `{{RELATIONS}}`: expose summary stats and/or tables per `SHOW_RELATED_PRESENTATION`. Eager-load in the service; Blade uses passed variables only.
- Profile card (gradient avatar frame + name + email + status pill + chips + inline switch-block) + details card (dt/dd `dl.row`).

### Routes and page translations
- Add **all** route keys in both `lang/ar/admin/routes.php` AND `lang/en/admin/routes.php`.
- **Structure difference:** AR file nests entities under outer `'admin'` key; EN file is flat at root.
- Required keys + custom keys: `index`, `create`, `store`, `update`, `edit`, `show`, `destroy`, `destroyAll`, `statistics`, `restore`, `switchBlock`, `diagrams`.
- After implementation, full translations review (rule 7).

---

## 3) Quick checklist before coding

- [ ] Reference entity (default **users**) confirmed -- UI copied from reference (tables, stats, buttons, inputs, forms)
- [ ] `INDEX_STATISTICS` asked → card list confirmed → `:loaderCards` set to exact count
- [ ] `INDEX_DIAGRAMS` asked (independent of stats)
- [ ] `ENABLE_EXPORT` asked → formats confirmed
- [ ] `HAS_EMAIL` asked → `<x-model.email>` + row 'more' dropdown buttons planned
- [ ] `HAS_NOTIFICATION` asked → `<x-model.notification>` + row 'more' dropdown buttons planned
- [ ] `HAS_BLOCK_TOGGLE` asked → route + service parent + status-pill markup planned
- [ ] `SOFT_DELETES` + `HAS_RESTORE` asked → `CanRetrieve` trait + restore route + retrieve filter
- [ ] `SIDEBAR_DISPLAY_MODE` asked when `ADD_TO_SIDEBAR=true` (single vs dropdown; maps to `has_child`)
- [ ] Index **filters** cover all applicable column/date/order/retrieve cases
- [ ] Show page **relations** + stats/tables/both confirmed
- [ ] `<x-table.bulk-actions>` included when `hasDeleteAll=true`
- [ ] `hasSearch=true` set on `<x-table.buttons>`
- [ ] Row actions: `data-id` on all notify/email buttons; no `data-bs-toggle` conflict (tooltip + dropdown on same element)
- [ ] **Form grid:** every row sums to 12 cols; multilingual expansion planned
- [ ] **Form buttons:** Create = `btn-primary`, Edit = `btn-success`; both have `waves-effect waves-light submit-button`
- [ ] **Boolean/status fields:** `x-form.select` with semantic `admin/main` labels
- [ ] **Routes translations:** both AR (nested) and EN (flat) updated with all required keys (including statistics/restore/switchBlock/diagrams)
- [ ] Statistics: `number_format()` on all values; no inline `style=` on `crud-stats__icon`; `stats.js` + `statsUrl` wired
- [ ] **Status pill markup** rendered in table when entity has block/status toggle
- [ ] **`applyColumnFilter` override** added for non-LIKE columns (booleans, enums)
- [ ] **Custom routes** declared BEFORE `Route::resource(...)` in `routes/admin.php`
- [ ] **Mobile-compact primary cell** with stacked email/phone (d-md-none) for readability when secondary cols hidden
- [ ] **CanRetrieve trait** added when SOFT_DELETES=true and `hasRetrieve` is desired
- [ ] Skeleton loader `:loaderCards` + `data-rows` verified
- [ ] `routes/admin.php`, `config/sidebar_routes.php`, lang files scoped
- [ ] Service + `AdminBaseController` pattern agreed -- no DB logic in Blade
- [ ] Full AR/EN translation review planned (inputs, main, routes, validation, page titles)
- [ ] Show page pushes `admin-table.js` if inline `switch-block` exists
- [ ] Entity-specific CSS file (`{entity}.css`) created or `filter.css` extended with `{{entity}}-table-row`, `{{entity}}-status-pill`, `{{entity}}-action-*`, hover glow, RTL accent
