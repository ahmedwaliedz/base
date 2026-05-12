# Plan 06 — Roles CRUD: Full Visual & UX Parity with Users CRUD

> **Author hat:** Senior Frontend Engineer + UI/UX Designer.
> **Reference module:** `admin/users/` — the most polished CRUD in the project today.
> **Goal:** Bring `admin/roles/` to the same production-grade quality, while respecting that roles are presented as **cards** (not a table) — they're a small, semantically-grouped entity, and a grid speaks "roles" better than rows.

---

## 1 · Design Philosophy

Roles aren't transactional records — they're **identity containers**. A user opens the roles page to *recognize the role at a glance* and act on it, not to scan through a long list. So the design must:

- **Lead with identity** — name + icon + admin count visible without scrolling
- **Make state legible** — assigned/unassigned permissions readable without cognitive overhead
- **Feel cohesive with Users** — same chrome (statistics, toolbar, filter, modals, toaster, skeleton) so admins learn the dashboard once
- **Honor the token system** — every color, radius, shadow flows from `tokens.css`; dark mode is first-class, not an afterthought

What this **doesn't** mean: shoehorning a DataTable into the roles page. Cards stay. The chrome around them aligns with Users.

---

## 2 · Users → Roles Parity Matrix

| Concern | Users (reference) | Roles (current) | Roles (target) |
|---|---|---|---|
| Layout shell | `crud.index` / `crud.create` / `crud.edit` / `crud.show` | `master` directly | `crud.*` layouts (consistent chrome, breadcrumb, JS stack) |
| Fast statistics | `<x-table.statistics>` + 6 stat cards (Total, Active, Blocked, Today, Week, Month) | None | New role-specific stats: **Total Roles**, **Assigned Admins**, **Unassigned**, **Avg Permissions**, **Most Populated**, **Created This Month** |
| List rendering | DataTable rows | Bootstrap cards (intentional — kept) | Bootstrap cards, redesigned with `.role-card` |
| First-load placeholder | Lottie loader (`.table-loader`) | Lottie loader | **Skeleton grid (9 cards)** matching real `.role-card` structure |
| Action affordances | View / Edit / Delete + "more" dropdown | View / Edit / Delete (raw `custom-icon` classes) | Token-themed action buttons (`.role-card__action--view/edit/delete`) |
| Empty state | n/a (table empty cell) | Basic icon + text | Branded empty card with primary CTA |
| Filters | Filter row + date range | Filter row + date range (already aligned) | Keep as-is |
| Create/Edit form chrome | Card body inside `crud.create` layout | Custom card + dynamic AJAX-loaded form | Same dynamic load **but** styled identically to Users (validated-form, `submit-button`, primary btn) |
| Show page | Profile card (4 col) + details card (8 col) | Flat single card | Header card + permission grid + **role statistics widget** |
| Toaster / modal | Inherits global (covered in plans 01 & 02) | Same | Inherits global |

---

## 3 · Page-by-Page Plan

### 3.1 — `index.blade.php`

#### Files to Change

```
resources/views/admin/roles/
├── index.blade.php                ← layout switch + statistics + skeleton
└── parts/
    ├── statistics.blade.php       ← NEW (slot content)
    ├── loader.blade.php           ← rewritten as 9-card skeleton
    └── cards.blade.php            ← redesigned with .role-card
```

#### Backend additions (minimum required for statistics — separate from styling)

> The user's earlier constraint "styling only" applies to UI. Statistics is an additive feature explicitly requested in this plan, so a thin endpoint is required. No existing logic is touched.

| File | Change |
|---|---|
| `app/Http/Controllers/Admin/RoleController.php` | Add `statistics(Request $request)` method (≈ 20 lines, mirrors `UserController::statistics`) |
| `routes/admin.php` | Add `Route::get('roles/statistics', [RoleController::class, 'statistics'])->name('roles.statistics')` |
| `resources/views/admin/roles/parts/statistics.blade.php` | NEW partial — rendered by the statistics endpoint |
| `lang/ar/admin/main.php` & `lang/en/admin/main.php` | New keys: `total_roles`, `assigned_admins`, `unassigned_roles`, `avg_permissions`, `most_populated`, `created_this_month` |

The statistics endpoint:

```php
public function statistics(Request $request)
{
    $base = Role::query();

    $now              = now();
    $totalRoles       = (clone $base)->count();
    $assignedAdmins   = Admin::whereNotNull('role_id')->count();
    $unassignedRoles  = (clone $base)->doesntHave('admins')->count();
    $avgPermissions   = (int) round((clone $base)
        ->withCount('permissions')
        ->avg('permissions_count') ?? 0);
    $mostPopulated    = (clone $base)
        ->withCount('admins')
        ->orderByDesc('admins_count')
        ->first();
    $createdThisMonth = (clone $base)
        ->where('created_at', '>=', $now->copy()->startOfMonth())
        ->count();

    return response()->view('admin.roles.parts.statistics', compact(
        'totalRoles', 'assignedAdmins', 'unassignedRoles',
        'avgPermissions', 'mostPopulated', 'createdThisMonth'
    ));
}
```

#### `parts/statistics.blade.php` — 6 stat cards

Uses the existing `.crud-stats__card` variants from `crud-stats.css` — no new CSS needed.

```blade
<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--total d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.total_roles') }}</p>
            <p class="crud-stats__value">{{ number_format($totalRoles ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon"><i class="ti ti-shield-check"></i></span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--active d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.assigned_admins') }}</p>
            <p class="crud-stats__value">{{ number_format($assignedAdmins ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon"><i class="ti ti-users"></i></span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--blocked d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.unassigned_roles') }}</p>
            <p class="crud-stats__value">{{ number_format($unassignedRoles ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon"><i class="ti ti-user-off"></i></span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--today d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.avg_permissions') }}</p>
            <p class="crud-stats__value">{{ number_format($avgPermissions ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon"><i class="ti ti-lock"></i></span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--week d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.most_populated') }}</p>
            <p class="crud-stats__value text-truncate" title="{{ $mostPopulated?->name }}" style="font-size:1rem;">
                {{ $mostPopulated?->name ?? '—' }}
            </p>
        </div>
        <span class="crud-stats__icon"><i class="ti ti-crown"></i></span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--month d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.created_this_month') }}</p>
            <p class="crud-stats__value">{{ number_format($createdThisMonth ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon"><i class="ti ti-calendar-month"></i></span>
    </div>
</div>
```

#### `index.blade.php` — full rewrite

```blade
@extends('admin.layouts.crud.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/roles.css') }}">
@endpush

@push('content')
    <x-table.statistics :loaderCards="6" />

    <x-table.buttons
        createRoute="{{ route('admin.roles.create') }}"
        :hasReload="true"
        :hasFilter="true"
        :hasPagination="true"
        :perPage="9"
    />

    <x-table.filter
        :mainCol="'col-md-3'"
        :hasStartDate="true"
        :hasEndDate="true"
        :hasOrderBy="true"
        :filters="[['type' => 'text', 'name' => 'name']]"
    />

    <div class="row g-4 append-page-content mt-1">
        @include('admin.roles.parts.loader')  {{-- skeleton --}}
    </div>
@endpush

@push('js')
    <script>
        var statsUrl = "{{ route('admin.roles.statistics') }}";
    </script>
    <script src="{{ asset('style/admin/custom-js/stats.js') }}"></script>
@endpush
```

> Note: `crud.index` already loads SweetAlert2, filter.js, admin-table.js, delete.js, restore.js. The manual `@push('css')`/`@push('js')` from the current `index.blade.php` becomes redundant.

#### `parts/loader.blade.php` — Skeleton (9 cards)

```blade
<div class="row g-4 roles-skeleton-grid" id="form-loader">
    @for ($i = 0; $i < 9; $i++)
    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="role-skeleton">
            <div class="role-skeleton__header">
                <div class="d-flex align-items-start gap-3 flex-grow-1 min-w-0">
                    <div class="role-skeleton__icon flex-shrink-0"></div>
                    <div class="flex-grow-1 min-w-0 pt-1">
                        <div class="role-skeleton__line role-skeleton__line--title"></div>
                        <div class="role-skeleton__line role-skeleton__line--meta"></div>
                    </div>
                </div>
                <div class="role-skeleton__actions">
                    <div class="role-skeleton__dot"></div>
                    <div class="role-skeleton__dot"></div>
                    <div class="role-skeleton__dot"></div>
                </div>
            </div>
            <div class="role-skeleton__divider"></div>
            <div class="role-skeleton__footer">
                <div class="role-skeleton__avatar"></div>
                <div class="role-skeleton__avatar"></div>
                <div class="role-skeleton__avatar"></div>
            </div>
        </div>
    </div>
    @endfor
</div>
```

#### `parts/cards.blade.php` — Production role cards

```blade
@foreach($roles as $role)
<div class="col-xl-4 col-lg-6 col-md-6 data-rows">
    <div class="role-card h-100">

        {{-- Header --}}
        <div class="role-card__header">
            <div class="d-flex align-items-start gap-3 min-w-0 flex-grow-1">
                <div class="role-card__icon-wrap flex-shrink-0">
                    <i class="ti ti-shield-check"></i>
                </div>
                <div class="min-w-0">
                    <h6 class="role-card__name" title="{{ $role->name }}">{{ $role->name }}</h6>
                    <span class="role-card__meta">
                        <i class="ti ti-users" style="font-size:0.7rem;"></i>
                        {{ __('admin/main.total_admin_um', ['num' => $role->admins()->count()]) }}
                    </span>
                </div>
            </div>
            <div class="role-card__actions">
                <a href="{{ route('admin.roles.show', $role->id) }}"
                   class="role-card__action role-card__action--view"
                   data-bs-toggle="tooltip" title="{{ __('admin/main.show') }}">
                    <i class="ti ti-eye"></i>
                </a>
                <a href="{{ route('admin.roles.edit', $role->id) }}"
                   class="role-card__action role-card__action--edit"
                   data-bs-toggle="tooltip" title="{{ __('admin/main.edit') }}">
                    <i class="ti ti-pencil"></i>
                </a>
                <a href="javascript:void(0);"
                   class="role-card__action role-card__action--delete delete-row"
                   data-route="{{ route('admin.roles.destroy', $role->id) }}"
                   data-bs-toggle="tooltip" title="{{ __('admin/main.delete') }}">
                    <i class="ti ti-trash"></i>
                </a>
            </div>
        </div>

        <div class="role-card__divider"></div>

        {{-- Footer: avatars --}}
        <div class="role-card__footer">
            @if($role->admins->isNotEmpty())
                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                    @foreach($role->admins->take(5) as $admin)
                    <li class="avatar avatar-sm pull-up"
                        data-bs-toggle="tooltip" title="{{ $admin->name }}">
                        <img class="rounded-circle" src="{{ $admin->image_url }}" alt="{{ $admin->name }}">
                    </li>
                    @endforeach
                </ul>
                @if($role->admins->count() > 5)
                    <span class="role-card__avatar-overflow">+{{ $role->admins->count() - 5 }}</span>
                @endif
            @else
                <span class="role-card__meta-soft">
                    <i class="ti ti-user-off" style="font-size:0.75rem;"></i>
                    {{ __('admin/main.no_admins_assigned') }}
                </span>
            @endif
        </div>

    </div>
</div>
@endforeach

@if($roles->count() === 0)
<div class="col-12 data-rows">
    <div class="roles-empty">
        <div class="roles-empty__icon">
            <i class="ti ti-shield-off"></i>
        </div>
        <h5 class="roles-empty__title">{{ __('admin/main.no_data_found') }}</h5>
        <p class="roles-empty__desc">{{ __('admin/main.no_data_description') }}</p>
        <a href="{{ route('admin.roles.create') }}" class="btn-role-action btn-role-action--primary">
            <i class="ti ti-plus"></i>{{ __('admin/main.add') }}
        </a>
    </div>
</div>
@endif

<div class="data-rows">{{ $roles->links('admin.layouts.pagination') }}</div>
```

---

### 3.2 — `create.blade.php` & `edit.blade.php`

Switch to the `crud.create` / `crud.edit` layouts so the form sits inside the same card chrome Users uses. The dynamic AJAX form-loading pattern (via `getForm`) stays — only the wrapper changes.

```blade
{{-- create.blade.php --}}
@extends('admin.layouts.crud.create')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/roles.css') }}">
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/libs/bootstrap-select/bootstrap-select.css') }}">
    <link rel="stylesheet" href="{{ asset('style/admin/css/custom-select.css') }}">
@endpush

@push('content')
    @include('admin.roles.parts.buttons')
    @include('admin.roles.parts.loader-form')
    <div class="append-form">{{-- form injected by AJAX --}}</div>
@endpush

@push('js')
    <script src="{{ asset('style/admin/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('style/admin/js/select-unselect-all.js') }}"></script>
    <script>
        var formRoute = "{{ route('admin.roles.getForm') }}";
    </script>
@endpush
```

`edit.blade.php` is identical except `formRoute` includes `['id' => $role->id]`.

#### `parts/_form.blade.php` — Users-grade form

Adopt the Users form pattern: `validated-form form` class, primary submit on create / success on edit, `submit-button` class (jqBootstrapValidation expects this).

```blade
<form class="mb-3 validated-form form" novalidate
      method="POST"
      action="{{ isset($role) ? route('admin.roles.update', $role->id) : route('admin.roles.store') }}">
    @csrf
    @if(isset($role)) @method('PUT') @endif

    <div class="row g-3">

        {{-- Name field (multi-language) — unchanged behavior --}}
        <x-form.text :options="[
            'name'            => 'name',
            'value'           => isset($role) ? $role->getTranslationsArray('name') : null,
            'class'           => 'col-md-12',
            'isRequired'      => true,
            'isMultiLanguage' => true,
        ]" />

        {{-- Section divider --}}
        <div class="col-12">
            <div class="roles-section-divider">
                <span class="roles-section-divider__line"></span>
                <span class="roles-section-divider__label">
                    <i class="ti ti-lock"></i>{{ __('admin/main.permissions') }}
                </span>
                <span class="roles-section-divider__line"></span>
            </div>
        </div>

        {{-- Permission groups --}}
        @foreach($permissionsByGroup as $groupKey => $routes)
        <div class="col-xl-4 col-md-6">
            <div class="perm-group">
                <div class="perm-group__header">
                    <i class="ti ti-lock-square"></i>
                    {{ \App\Traits\Role\RoleTrait::translateRouteName('admin.' . $groupKey) }}
                </div>
                <div class="perm-group__body">
                    <select
                        placeholder="{{ __('admin/main.select_any_thing') }}"
                        name="permissions[]"
                        id="selectpickerSelectDeselect_{{ $groupKey }}"
                        class="selectpicker w-100"
                        data-style="btn-default"
                        multiple
                        data-multiple-separator=" - "
                        data-actions-box="true"
                        data-live-search-placeholder="{{ __('admin/main.search') }}"
                        data-live-search="true"
                        data-selected-text-format="count > 4"
                        data-count-selected-text="{{ __('admin/main.selected', ['count' => '{0}', 'total' => '{1}']) }}"
                        data-none-results-text="{{ __('admin/main.no_result') }}"
                        data-select-all-text="{{ __('admin/main.select_all') }}"
                        data-deselect-all-text="{{ __('admin/main.unselect_all') }}"
                    >
                        @foreach($routes as $route)
                        <option value="{{ $route['name'] }}"
                            {{ isset($permissions) && in_array($route['name'], $permissions) ? 'selected' : '' }}>
                            {{ $route['label'] }}
                        </option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        @endforeach

    </div>

    {{-- Match Users CRUD submit row exactly --}}
    <div class="pt-4 d-flex justify-content-center mt-3">
        <button type="submit"
                class="btn {{ isset($role) ? 'btn-success' : 'btn-primary' }} me-sm-3 me-1 waves-effect waves-light submit-button">
            {{ isset($role) ? __('admin/main.edit') : __('admin/main.create') }}
        </button>
    </div>
</form>
```

#### `parts/buttons.blade.php` — Token-themed toolbar

```blade
<div class="roles-toolbar">
    <span class="roles-toolbar__label">
        <i class="ti ti-lock" style="font-size:0.85rem;"></i>
        {{ __('admin/main.permissions') }}
    </span>
    <div class="roles-toolbar__group">
        <button type="button" class="btn-role-action btn-role-action--primary waves-effect select-all">
            <i class="ti ti-checks"></i>{{ __('admin/main.select_all') }}
        </button>
        <button type="button" class="btn-role-action btn-role-action--danger waves-effect unselect-all">
            <i class="ti ti-square-off"></i>{{ __('admin/main.unselect_all') }}
        </button>
        <button type="button" class="btn-role-action btn-role-action--neutral waves-effect reset">
            <i class="ti ti-refresh"></i>{{ __('admin/main.reset') }}
        </button>
    </div>
</div>
```

#### `parts/loader-form.blade.php` (rename or new file for the create/edit page loader)

A simple, themed centered loader for the AJAX form fetch — distinct from the skeleton on index:

```blade
<div id="form-loader" class="roles-form-loader">
    <div class="roles-form-loader__spinner" aria-hidden="true">
        <span></span><span></span><span></span>
    </div>
    <p class="roles-form-loader__label">{{ __('admin/main.loading') }}</p>
</div>
```

---

### 3.3 — `show.blade.php`

The Users show page has two columns: profile card (4 col) + details card (8 col). For roles, we replicate the **two-column overview** but tailored to roles:

- **Left col (4):** "Role identity" card — icon, name (AR + EN), admin count, permission count, created date, edit/delete CTA
- **Right col (8):** Permission grid (3-column on xl, 2 on md) — group cards with assigned/unassigned badges
- **Below:** Statistics row (4 mini cards) — permission coverage, assigned admins, system-wide rank, last admin added
- **Header (in `crud.show` layout's @push('header')):** title + edit/delete + back

```blade
@extends('admin.layouts.crud.show', ['model' => $role])

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/roles.css') }}">
@endpush

@push('header')
    <h5 class="mb-0 d-flex align-items-center gap-2">
        <i class="ti ti-shield-check" style="color:var(--color-brand-primary);"></i>
        {{ __('admin/main.role_details') }}
    </h5>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-success">
            <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
        </a>
        <a href="javascript:void(0);"
           data-id="{{ $role->id }}"
           data-route="{{ route('admin.roles.destroy', $role->id) }}"
           class="btn btn-sm btn-danger delete-row">
            <i class="ti ti-trash me-1"></i>{{ __('admin/main.delete') }}
        </a>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
        </a>
    </div>
@endpush

@push('content')

{{-- Mini stats row --}}
<div class="col-12 mb-4">
    <div class="row g-3">
        <div class="col-6 col-md-3">
            <div class="role-stat-card role-stat-card--primary">
                <div class="role-stat-card__icon"><i class="ti ti-users"></i></div>
                <div>
                    <div class="role-stat-card__label">{{ __('admin/main.assigned_admins') }}</div>
                    <div class="role-stat-card__value">{{ $role->admins->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="role-stat-card role-stat-card--success">
                <div class="role-stat-card__icon"><i class="ti ti-lock-check"></i></div>
                <div>
                    <div class="role-stat-card__label">{{ __('admin/main.granted_permissions') }}</div>
                    <div class="role-stat-card__value">{{ count($permissions ?? []) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="role-stat-card role-stat-card--info">
                <div class="role-stat-card__icon"><i class="ti ti-chart-pie"></i></div>
                <div>
                    <div class="role-stat-card__label">{{ __('admin/main.coverage') }}</div>
                    <div class="role-stat-card__value">{{ $coverage ?? 0 }}%</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="role-stat-card role-stat-card--warning">
                <div class="role-stat-card__icon"><i class="ti ti-calendar-plus"></i></div>
                <div>
                    <div class="role-stat-card__label">{{ __('admin/main.created_at') }}</div>
                    <div class="role-stat-card__value" style="font-size:0.95rem;">
                        {{ $role->created_at->format('Y-m-d') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Identity (left) + permissions (right) --}}
<div class="col-xl-4 col-md-5 mb-4">
    <div class="role-identity-card">
        <div class="role-identity-card__avatar">
            <i class="ti ti-shield-check"></i>
        </div>

        <div class="role-identity-card__names">
            <div>
                <div class="role-identity-card__lang">AR</div>
                <div class="role-identity-card__name">{{ $role->translate('ar')->name }}</div>
            </div>
            <div class="role-identity-card__sep"></div>
            <div>
                <div class="role-identity-card__lang">EN</div>
                <div class="role-identity-card__name">{{ $role->translate('en')->name }}</div>
            </div>
        </div>

        @if($role->admins->isNotEmpty())
        <div class="role-identity-card__admins">
            <div class="role-identity-card__admins-label">{{ __('admin/main.assigned_admins') }}</div>
            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 mt-2">
                @foreach($role->admins->take(6) as $admin)
                <li class="avatar avatar-sm pull-up" data-bs-toggle="tooltip" title="{{ $admin->name }}">
                    <img class="rounded-circle" src="{{ $admin->image_url }}" alt="{{ $admin->name }}">
                </li>
                @endforeach
                @if($role->admins->count() > 6)
                <li class="role-identity-card__more">+{{ $role->admins->count() - 6 }}</li>
                @endif
            </ul>
        </div>
        @endif
    </div>
</div>

<div class="col-xl-8 col-md-7 mb-4">
    {{-- Legend --}}
    <div class="perm-legend">
        <span class="perm-legend__item">
            <span class="perm-badge perm-badge--on"><i class="ti ti-check"></i></span>
            {{ __('admin/main.assigned_permissions') }}
        </span>
        <span class="perm-legend__item">
            <span class="perm-badge perm-badge--off"><i class="ti ti-x"></i></span>
            {{ __('admin/main.unassigned_permissions') }}
        </span>
    </div>

    {{-- Permission groups --}}
    <div class="row g-3">
        @foreach($permissionsByGroup as $groupKey => $routes)
        <div class="col-md-6">
            <div class="perm-group h-100">
                <div class="perm-group__header">
                    <i class="ti ti-lock-square"></i>
                    {{ \App\Traits\Role\RoleTrait::translateRouteName('admin.' . $groupKey) }}
                </div>
                <div class="perm-group__body">
                    @foreach($routes as $route)
                        @php $on = isset($permissions) && in_array($route['name'], $permissions); @endphp
                        <span class="perm-badge {{ $on ? 'perm-badge--on' : 'perm-badge--off' }}">
                            @if($on)<i class="ti ti-check"></i>@endif
                            {{ $route['label'] }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endpush
```

#### Backend support for show statistics

`RoleController::show($id)` (or `RoleService::show($id)`) should pass `$coverage` (percentage):

```php
// In RoleService::show() — compute alongside existing $permissions
$totalPermissions = Permission::count();
$granted          = count($permissions);
$coverage         = $totalPermissions > 0
    ? (int) round(($granted / $totalPermissions) * 100)
    : 0;

return array_merge(parent::show($id), compact('coverage'));
```

---

## 4 · CSS Architecture — `public/style/admin/css/roles.css`

One dedicated file, loaded via `@push('css')` on each roles page. Token-based. Dark-mode first-class.

> Full CSS body (see plan-05 for the bulk — `.role-card`, `.role-skeleton`, `.perm-group`, `.perm-badge`, `.btn-role-action`, `.role-header-card`).
> Below are the **additions** specific to this plan:

```css
/* ─── Role meta (soft variant) ────────────────────── */
.role-card__meta {
    font-size: 0.72rem;
    color: var(--text-muted);
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.role-card__meta-soft {
    font-size: 0.72rem;
    color: var(--text-muted);
    opacity: 0.75;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-style: italic;
}


/* ─── Empty state (rich) ──────────────────────────── */
.roles-empty {
    padding: 3rem 1rem;
    text-align: center;
    border-radius: var(--radius-xl);
    border: 1px dashed var(--surface-border);
    background: rgba(var(--color-brand-primary-rgb), 0.02);
}

.roles-empty__icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(var(--color-brand-primary-rgb), 0.08);
    border: 1px solid rgba(var(--color-brand-primary-rgb), 0.18);
    color: var(--color-brand-primary);
    font-size: 2.2rem;
    opacity: 0.85;
}

.roles-empty__title {
    color: var(--text-strong);
    font-weight: 700;
    margin-bottom: 0.4rem;
}

.roles-empty__desc {
    color: var(--text-muted);
    font-size: 0.85rem;
    margin-bottom: 1.25rem;
}


/* ─── Section divider (form) ──────────────────────── */
.roles-section-divider {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    margin: 0.75rem 0;
}

.roles-section-divider__line {
    flex: 1;
    height: 1px;
    background: linear-gradient(
        to right,
        transparent,
        var(--surface-border),
        transparent
    );
}

.roles-section-divider__label {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.35rem 0.85rem;
    border-radius: var(--radius-pill);
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--color-brand-primary);
    background: rgba(var(--color-brand-primary-rgb), 0.08);
    border: 1px solid rgba(var(--color-brand-primary-rgb), 0.18);
}


/* ─── Form loader (3-dot bounce) ──────────────────── */
.roles-form-loader {
    min-height: 240px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 2rem;
}

.roles-form-loader__spinner {
    display: inline-flex;
    gap: 0.4rem;
}

.roles-form-loader__spinner span {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--color-brand-primary);
    animation: roles-bounce 1.2s var(--ease-smooth) infinite;
}

.roles-form-loader__spinner span:nth-child(2) { animation-delay: 0.15s; }
.roles-form-loader__spinner span:nth-child(3) { animation-delay: 0.30s; }

@keyframes roles-bounce {
    0%, 60%, 100% { transform: translateY(0);    opacity: 0.4; }
    30%           { transform: translateY(-8px); opacity: 1;   }
}

@media (prefers-reduced-motion: reduce) {
    .roles-form-loader__spinner span { animation: none; opacity: 1; }
}

.roles-form-loader__label {
    color: var(--text-muted);
    font-size: 0.82rem;
    margin: 0;
}


/* ─── Show page: role identity card ───────────────── */
.role-identity-card {
    border-radius: var(--radius-xl);
    border: 1px solid var(--surface-border);
    background:
        linear-gradient(135deg,
            rgba(var(--color-brand-primary-rgb), 0.07) 0%,
            rgba(var(--color-brand-secondary-rgb), 0.04) 100%),
        var(--surface-base);
    backdrop-filter: blur(12px) saturate(150%);
    -webkit-backdrop-filter: blur(12px) saturate(150%);
    box-shadow: 0 4px 14px rgba(var(--color-brand-primary-rgb), 0.06);
    padding: 1.5rem;
    height: 100%;
}

[data-theme*='dark'] .role-identity-card,
.dark-style .role-identity-card {
    background:
        linear-gradient(135deg,
            rgba(var(--color-brand-primary-rgb), 0.12) 0%,
            rgba(var(--color-brand-secondary-rgb), 0.06) 100%),
        rgba(44, 49, 72, 0.80);
}

.role-identity-card__avatar {
    width: 72px;
    height: 72px;
    margin: 0 auto 1rem;
    border-radius: var(--radius-xl);
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--color-brand-primary-rgb), 0.14);
    color: var(--color-brand-primary);
    border: 1px solid rgba(var(--color-brand-primary-rgb), 0.28);
    font-size: 2rem;
}

.role-identity-card__names {
    display: flex;
    align-items: center;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 1.25rem;
}

.role-identity-card__sep {
    width: 1px;
    align-self: stretch;
    background: var(--surface-border);
}

.role-identity-card__lang {
    font-size: 0.65rem;
    font-weight: 800;
    letter-spacing: 0.1em;
    color: var(--text-muted);
    text-transform: uppercase;
    margin-bottom: 0.15rem;
    text-align: center;
}

.role-identity-card__name {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--text-strong);
    text-align: center;
}

.role-identity-card__admins-label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--text-muted);
    text-align: center;
    margin-bottom: 0.25rem;
}

.role-identity-card__admins {
    padding-top: 1rem;
    border-top: 1px solid var(--surface-border);
    text-align: center;
}

.role-identity-card__admins ul {
    justify-content: center;
}

.role-identity-card__more {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: rgba(var(--color-brand-primary-rgb), 0.10);
    color: var(--color-brand-primary);
    font-size: 0.7rem;
    font-weight: 700;
    margin-inline-start: 0.25rem;
}


/* ─── Show page: stat mini-cards ──────────────────── */
.role-stat-card {
    --accent-rgb: var(--color-brand-primary-rgb);
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 1rem 1.1rem;
    border-radius: var(--radius-lg);
    border: 1px solid rgba(var(--accent-rgb), 0.22);
    background:
        linear-gradient(135deg, rgba(var(--accent-rgb), 0.10) 0%, transparent 70%),
        var(--surface-base);
    backdrop-filter: blur(10px) saturate(150%);
    -webkit-backdrop-filter: blur(10px) saturate(150%);
    box-shadow: 0 2px 8px rgba(var(--accent-rgb), 0.05);
    transition: transform var(--duration-base) var(--ease-smooth);
}

.role-stat-card:hover { transform: translateY(-2px); }

[data-theme*='dark'] .role-stat-card,
.dark-style .role-stat-card {
    background:
        linear-gradient(135deg, rgba(var(--accent-rgb), 0.14) 0%, transparent 70%),
        rgba(44, 49, 72, 0.78);
}

.role-stat-card--primary { --accent-rgb: var(--color-brand-primary-rgb); }
.role-stat-card--success { --accent-rgb: var(--color-success-rgb);       }
.role-stat-card--info    { --accent-rgb: var(--color-brand-secondary-rgb); }
.role-stat-card--warning { --accent-rgb: var(--color-warning-rgb);       }

.role-stat-card__icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-md);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--accent-rgb), 0.14);
    color: rgb(var(--accent-rgb));
    border: 1px solid rgba(var(--accent-rgb), 0.28);
    flex-shrink: 0;
    font-size: 1.15rem;
}

.role-stat-card__label {
    font-size: 0.68rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--text-muted);
    margin-bottom: 0.15rem;
}

.role-stat-card__value {
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--text-strong);
    line-height: 1.15;
    letter-spacing: -0.01em;
}


/* ─── Permission legend ───────────────────────────── */
.perm-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    padding: 0.6rem 0.85rem;
    border-radius: var(--radius-md);
    background: var(--surface-overlay);
    border: 1px solid var(--surface-border);
    margin-bottom: 1rem;
}

.perm-legend__item {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.78rem;
    color: var(--text-body);
}
```

> All `role-card`, `role-skeleton`, `perm-group`, `perm-badge`, `btn-role-action`, `roles-toolbar`, `btn-form-submit`/`btn-form-back` CSS classes from **plan-05** carry forward verbatim.

---

## 5 · Acceptance Criteria

### Index page
- [ ] Page uses `crud.index` layout (same chrome as Users)
- [ ] Statistics row shows 6 cards using `<x-table.statistics>` + `crud-stats__card` variants
- [ ] Statistics endpoint `admin.roles.statistics` returns a rendered Blade partial
- [ ] On AJAX list load, skeleton shows **exactly 9** cards that visually match the real `.role-card` (icon + title + meta + 3 action dots + 3 avatars)
- [ ] Skeleton respects `prefers-reduced-motion`
- [ ] Real role cards use `.role-card` glass surface with hover lift
- [ ] Action buttons are color-coded (primary view / success edit / danger delete) and use token colors
- [ ] Empty state is a styled card with primary CTA
- [ ] Avatar overflow shows `+N` chip when admins > 5
- [ ] Tooltips work on action buttons + avatars

### Create / Edit
- [ ] Both extend `crud.create` / `crud.edit` (form sits in standard card chrome)
- [ ] Toolbar (select-all / unselect-all / reset) uses `.btn-role-action` variants
- [ ] AJAX form loader is the new 3-dot bouncer (not Bootstrap spinner)
- [ ] Form has `validated-form form` classes — jqBootstrapValidation still works
- [ ] Submit uses `btn-primary` (create) / `btn-success` (edit) + `submit-button` class (matches Users)
- [ ] Section divider between name and permissions is the new pill divider
- [ ] Permission groups use `.perm-group` cards (3-col xl, 2-col md)

### Show
- [ ] Uses `crud.show` layout — header with title + edit/delete/back
- [ ] 4 mini stat cards at top: Assigned Admins / Granted Permissions / Coverage % / Created date
- [ ] Left col (4): `.role-identity-card` with avatar + AR/EN names + admin avatars
- [ ] Right col (8): legend + permission groups grid
- [ ] `$coverage` computed in `RoleService::show()` and passed to view
- [ ] Permission badges use `.perm-badge--on/--off` with brand colors
- [ ] No instance of `btn-label-dribbble` anywhere

### Global
- [ ] All new CSS lives in `public/style/admin/css/roles.css` (single file)
- [ ] All colors source from `tokens.css` — no hardcoded hex
- [ ] Dark mode tested on every new component
- [ ] All new strings are in `lang/ar/admin/main.php` + `lang/en/admin/main.php`
- [ ] No JS file modified (the dynamic form-loading flow is preserved)
- [ ] Routes file gets exactly one new line (`admin.roles.statistics`)
- [ ] `RoleController` gets exactly one new method (`statistics`)
- [ ] `RoleService::show` gets `$coverage` calculation

---

## 6 · UX Rationale Cheat-Sheet (for code reviewers)

| Decision | Why |
|---|---|
| Cards over table for roles | Roles are few (~5–20 typically), card grid scans faster and conveys "identity" better |
| 9-card skeleton (not 4) | Roles page uses `perPage=9`; skeleton count == real card count avoids visual jump on swap |
| Icon-only action buttons | Roles cards are visual; verbose labels on small cards hurt scannability — tooltips give the affordance |
| `+N` avatar overflow | Avoids horizontal scroll inside a card when a role has 12+ admins |
| Statistics show **Most Populated** | Operationally meaningful (who has the most reach) more than dry counts |
| Show page **Coverage %** | Single number that tells "is this role over- or under-permissioned" |
| Form 3-col on xl | Permission groups are typically 6–12; 3-col fits viewport without scrolling for most roles |
| Pill section divider | Bootstrap's default divider is invisible against the card background; the pill anchors the eye |
| Dark-mode `.dark-style` selector | Matches existing sidebar.css / crud-stats.css patterns — no new dark-mode strategy |
