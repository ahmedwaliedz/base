# Plan 07 — Admins CRUD: Full Visual & UX Parity with Users CRUD

> **Author hat:** Senior Frontend Engineer + UI/UX Designer.
> **Reference module:** `admin/users/` — current gold standard CRUD.
> **Subject:** `admin/admins/` — manages dashboard operators (super_admin + admin types). Higher-stakes than Users since these accounts hold dashboard access.

---

## 1 · Design Philosophy

Admins ≠ Users. Where Users are *customers in the system*, Admins are **operators of the system itself**. This shifts the UX priorities:

- **Identity & authority first.** Type (`super_admin` / `admin`) and Role assignment are not secondary metadata — they are the primary signal a viewer needs in under a second.
- **Security-grade affordances.** Block/unblock is a sensitive action; it deserves clearer states than a toggle hidden next to a badge. Super-admin protection (`id == 1` can't be deleted) should be visible in the UI, not silent.
- **Permission transparency.** On a single admin's profile, "what can this person actually do?" matters more than dry attributes. Permissions inherited from the role belong on the show page.
- **Parity with Users where parity buys consistency.** Same chrome (statistics widget, bulk actions, status pill pattern, action dropdown). Same skeleton/loader patterns. Different *content* — same *grammar*.

---

## 2 · Current State vs. Target

### What admins CRUD does well today
- Already extends `crud.index` / `crud.create` / `crud.edit` / `crud.show` ✓
- Create/Edit forms use `<x-form.*>` components correctly ✓
- Has filters for status & role ✓
- Has email/notification modals integrated ✓
- Super-admin delete protection in `AdminService::destroy()` ✓

### Gaps vs. Users reference
| Concern | Users (reference) | Admins (current) | Δ |
|---|---|---|---|
| Statistics widget | Present, 6 cards | **Missing** | Add `<x-table.statistics>` + endpoint |
| Bulk-actions bar | Present | **Missing** | Add `<x-table.bulk-actions>` |
| Mobile-compact row | Yes (name+email stacked on `<md`) | No — fixed 3-line cell at all sizes | Refactor `table.blade.php` |
| Status visual | `user-status-pill` w/ animated dot | Raw `badge` + bare `form-switch` | New `admins-status-pill` (or shared) |
| Action buttons | Themed `users-action-view/edit/delete` + dropdown for secondary | 4 inline icon buttons w/ raw `bg-success` etc. | Themed `admins-action-*` + "more" dropdown |
| Restore visibility | Themed in row + tooltip | Themed but inconsistent | Match Users pattern |
| Super-admin signal | n/a | n/a | **NEW:** crown/lock indicator on row + show page |
| Show page extras | Profile + details (clean) | Profile + details (slightly older styling) | Add stats row + **permissions panel** + type badge prominence |

---

## 3 · Page-by-Page Plan

### 3.1 — `index.blade.php`

#### Files to Change

```
resources/views/admin/admins/
├── index.blade.php           ← add statistics + bulk-actions
└── table.blade.php           ← full refactor (mobile-compact, themed actions)

public/style/admin/css/
└── admins.css                ← NEW — admin-specific tokens
```

#### Backend additions (statistics only)

| File | Change |
|---|---|
| `app/Http/Controllers/Admin/AdminController.php` | Add `statistics(Request $request)` method (≈ 25 lines, mirrors `UserController::statistics`) |
| `routes/admin.php` | Add `Route::get('admins/statistics', [AdminController::class, 'statistics'])->name('admins.statistics')` (place BEFORE `Route::resource('admins', …)` to avoid `{admin}` capture) |
| `resources/views/admin/admins/parts/statistics.blade.php` | NEW partial |
| `lang/*/admin/main.php` | New keys: `super_admins`, `regular_admins`, `roles_in_use`, `recently_active` |

```php
public function statistics(Request $request)
{
    $base = $this->service->index($request);

    $now = Carbon::now();

    $total       = (clone $base)->count();
    $active      = (clone $base)->where('is_blocked', false)->count();
    $blocked     = (clone $base)->where('is_blocked', true)->count();
    $superAdmins = (clone $base)->where('type', AdminType::SUPER_ADMIN->value)->count();
    $rolesInUse  = (clone $base)->whereNotNull('role_id')->distinct('role_id')->count('role_id');
    $thisMonth   = (clone $base)->where('created_at', '>=', $now->copy()->startOfMonth())->count();
    $lastMonth   = (clone $base)
        ->whereBetween('created_at', [
            $now->copy()->subMonth()->startOfMonth(),
            $now->copy()->subMonth()->endOfMonth(),
        ])
        ->count();

    $growth = $lastMonth > 0
        ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1)
        : ($thisMonth > 0 ? 100.0 : 0.0);

    return response()->view(
        'admin.admins.parts.statistics',
        compact('total', 'active', 'blocked', 'superAdmins', 'rolesInUse', 'thisMonth', 'growth')
    );
}
```

#### `parts/statistics.blade.php` — 6 stat cards

Reuses existing `.crud-stats__card` variants. No new CSS.

```blade
@php $growthUp = ($growth ?? 0) >= 0; @endphp

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--total d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.total') }}</p>
            <p class="crud-stats__value">{{ number_format($total ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon"><i class="ti ti-shield"></i></span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--active d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.active') }}</p>
            <p class="crud-stats__value">{{ number_format($active ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon"><i class="ti ti-circle-check"></i></span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--blocked d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.blocked') }}</p>
            <p class="crud-stats__value">{{ number_format($blocked ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon"><i class="ti ti-lock"></i></span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--today d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.super_admins') }}</p>
            <p class="crud-stats__value">{{ number_format($superAdmins ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon"><i class="ti ti-crown"></i></span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--week d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.roles_in_use') }}</p>
            <p class="crud-stats__value">{{ number_format($rolesInUse ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon"><i class="ti ti-shield-check"></i></span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--month d-flex align-items-center justify-content-between">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                <p class="crud-stats__label mb-0">{{ __('admin/main.this_month') }}</p>
                @if(($growth ?? 0) !== 0)
                    <span class="crud-stats__delta {{ $growthUp ? 'crud-stats__delta--up' : 'crud-stats__delta--down' }}">
                        <i class="ti {{ $growthUp ? 'ti-trending-up' : 'ti-trending-down' }}"></i>
                        {{ abs($growth) }}%
                    </span>
                @endif
            </div>
            <p class="crud-stats__value">{{ number_format($thisMonth ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon"><i class="ti ti-calendar-month"></i></span>
    </div>
</div>
```

#### `index.blade.php` — additions

```blade
@extends('admin.layouts.crud.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
@endpush

@push('content')
    {{-- NEW: statistics --}}
    <x-table.statistics :loaderCards="6" />

    <x-table.buttons
        createRoute="{{ route('admin.admins.create') }}"
        :hasNotification="true"
        :hasDeleteAll="true"
        :deleteAllRoute="route('admin.admins.destroyAll')"
        :hasEmail="true"
        :hasReload="true"
        :hasFilter="true"
        :hasSearch="true"
        :hasExport="true"
        :exportCopy="true"
        :exportPdf="true"
        :exportExcel="true"
        :exportWord="true"
        :exportJson="true"
        :hasPagination="true"
        :perPage="20"
    />

    <x-table.filter :mainCol="'col-md-3'" :hasStartDate="true" :hasEndDate="true"
                    :hasOrderBy="true" :hasRetrieve="$is_retreivable"
                    :filters="[
                        ['type' => 'text',   'name' => 'name'],
                        ['type' => 'text',   'name' => 'phone'],
                        ['type' => 'text',   'name' => 'email'],
                        ['type' => 'select', 'name' => 'status', 'options' => [
                            ['id' => '',        'name' => __('admin/main.all')],
                            ['id' => 'active',  'name' => __('admin/main.available')],
                            ['id' => 'blocked', 'name' => __('admin/main.blocked')],
                        ]],
                        ['type' => 'select', 'name' => 'role_id', 'options' => $roles],
                    ]" />

    {{-- NEW: bulk actions --}}
    <x-table.bulk-actions :hasDelete="true"
                          :deleteRoute="route('admin.admins.destroyAll')" />

    <x-table.table :hasCheckbox="true" :hasActions="true"
                   :headers="[
                       __('admin/main.name'),
                       __('admin/main.role'),
                       __('admin/main.status'),
                   ]" />

    <x-model.notification :route="route('admin.notifications.sendNotifications')"
                          :class="'App\Models\Admin'" />
    <x-model.email />
@endpush

@push('js')
    <script>
        var statsUrl = "{{ route('admin.admins.statistics') }}";
    </script>
    <script src="{{ asset('style/admin/custom-js/stats.js') }}"></script>
@endpush
```

#### `table.blade.php` — Refactored row (mobile-compact, themed, super-admin aware)

```blade
@extends('admin.layouts.crud.table', [
    'rows'        => $admins,
    'createRoute' => route('admin.admins.create'),
])

@section('table')
    @foreach ($admins as $admin)
        @php $isSuper = $admin->type?->value === \App\Enums\AdminType::SUPER_ADMIN->value; @endphp
        @php $isProtected = $admin->id === 1; @endphp

        <tr class="data-rows admins-table-row {{ $admin->deleted_at ? 'deleted-table-row' : '' }} {{ $isSuper ? 'is-super-admin-row' : '' }}"
            data-admin-id="{{ $admin->id }}">

            {{-- Checkbox (hide for protected/super-admin to prevent accidental bulk-delete) --}}
            @if (!$admin->deleted_at && !$isProtected)
                <td class="dt-checkboxes-cell">
                    <input type="checkbox" value="{{ $admin->id }}" data-id="{{ $admin->id }}"
                           class="dt-checkboxes form-check-input"
                           aria-label="{{ __('admin/main.select_row', ['name' => $admin->name]) }}">
                </td>
            @else
                <td></td>
            @endif

            {{-- Name cell: avatar + name + (mobile) role + (mobile) phone/email --}}
            <td>
                <div class="d-flex product-name align-items-center gap-2">
                    <div class="avatar-wrapper flex-shrink-0 position-relative">
                        <div class="avatar rounded-2">
                            <img src="{{ $admin->image }}" alt="{{ $admin->name }}" class="rounded-2">
                        </div>
                        @if ($isSuper)
                            <span class="admin-crown" title="{{ __('admin/main.super_admin') }}">
                                <i class="ti ti-crown-filled"></i>
                            </span>
                        @endif
                    </div>
                    <div class="d-flex flex-column min-w-0">
                        <span class="admin-name fw-semibold text-truncate">{{ $admin->name }}</span>
                        <span class="admin-contact-mobile d-md-none text-muted small text-truncate">
                            <i class="ti ti-mail" aria-hidden="true"></i> {{ $admin->email }}
                        </span>
                        <span class="admin-contact-mobile d-md-none d-none text-muted small text-truncate">
                            <i class="ti ti-phone" aria-hidden="true"></i> {{ $admin->phone }}
                        </span>
                        <span class="admin-role-mobile d-md-none text-muted small text-truncate">
                            <i class="ti ti-shield-check" aria-hidden="true"></i> {{ $admin->role_name }}
                        </span>
                    </div>
                </div>
            </td>

            {{-- Role + type --}}
            <td class="d-none d-md-table-cell admins-role-cell">
                <div class="d-flex flex-column gap-1">
                    <span class="admin-role-badge">
                        <i class="ti ti-shield-check"></i>{{ $admin->role_name }}
                    </span>
                    @if ($admin->type)
                        <span class="admin-type-badge admin-type-badge--{{ $isSuper ? 'super' : 'regular' }}">
                            @if ($isSuper)<i class="ti ti-crown"></i>@endif
                            {{ $admin->type->label() }}
                        </span>
                    @endif
                </div>
            </td>

            {{-- Status (pill + hidden checkbox toggle, same pattern as users) --}}
            <td class="admins-status-cell">
                <div class="admins-status-wrap">
                    @php
                        $isBlocked   = $admin->is_blocked;
                        $statusLabel = $admin->statusData()['label'];
                    @endphp
                    <label class="admin-status-toggle"
                           title="{{ $isBlocked ? __('admin/main.unblock') : __('admin/main.block') }}">
                        <input class="form-check-input switch-block visually-hidden" type="checkbox" role="switch"
                               data-id="{{ $admin->id }}"
                               data-route="{{ route('admin.admins.switchBlock', ['id' => $admin->id]) }}"
                               data-active-label="{{ __('admin/main.active') }}"
                               data-blocked-label="{{ __('admin/main.blocked') }}"
                               {{ !$isBlocked ? 'checked' : '' }}
                               aria-label="{{ $isBlocked ? __('admin/main.click_to_unblock') : __('admin/main.click_to_block') }}">
                        <span class="admin-status-pill status-badge {{ $isBlocked ? 'is-blocked' : 'is-active' }}"
                              data-active-label="{{ __('admin/main.active') }}"
                              data-blocked-label="{{ __('admin/main.blocked') }}">
                            <span class="admin-status-pill__dot" aria-hidden="true"></span>
                            {{ $statusLabel }}
                        </span>
                    </label>
                </div>
            </td>

            {{-- Actions --}}
            <td class="admins-actions-cell">
                <div class="d-flex align-items-center gap-2 flex-nowrap admins-row-actions">

                    <a href="{{ route('admin.admins.show', ['admin' => $admin]) }}"
                       class="custom-icon admins-action-btn admins-action-view"
                       data-bs-toggle="tooltip" title="@lang('admin/main.show')">
                        <i class="ti ti-eye"></i>
                    </a>

                    @if (!$admin->deleted_at)
                        <a href="{{ route('admin.admins.edit', ['admin' => $admin]) }}"
                           class="custom-icon admins-action-btn admins-action-edit"
                           data-bs-toggle="tooltip" title="@lang('admin/main.edit')">
                            <i class="ti ti-pencil"></i>
                        </a>
                    @endif

                    @if ($admin->deleted_at)
                        <a href="javascript:void(0);" data-id="{{ $admin->id }}"
                           data-route="{{ route('admin.admins.restore', ['id' => $admin->id]) }}"
                           class="custom-icon admins-action-btn admins-action-restore restore-row"
                           data-bs-toggle="tooltip" title="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up"></i>
                        </a>
                    @elseif (!$isProtected)
                        <a href="javascript:void(0);" data-id="{{ $admin->id }}"
                           data-route="{{ route('admin.admins.destroy', ['admin' => $admin]) }}"
                           class="custom-icon admins-action-btn admins-action-delete delete-row"
                           data-bs-toggle="tooltip" title="@lang('admin/main.delete')">
                            <i class="ti ti-trash"></i>
                        </a>
                    @else
                        <span class="custom-icon admins-action-btn admins-action-locked"
                              data-bs-toggle="tooltip"
                              title="@lang('admin/main.protected_super_admin')">
                            <i class="ti ti-lock"></i>
                        </span>
                    @endif

                    {{-- Secondary actions dropdown --}}
                    @if (!$admin->deleted_at)
                        <div class="dropdown admin-more-dropdown">
                            <button type="button"
                                    class="custom-icon admins-action-btn admins-action-more"
                                    data-bs-toggle="dropdown" aria-expanded="false"
                                    aria-label="@lang('admin/main.more_actions')">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end admin-more-menu">
                                <li>
                                    <button type="button" class="dropdown-item send-notification"
                                            data-bs-toggle="modal"
                                            data-bs-target="#notificationModal"
                                            data-id="{{ $admin->id }}">
                                        <i class="ti ti-bell-plus me-2"></i>@lang('admin/main.send_notification')
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item"
                                            data-bs-toggle="modal"
                                            data-bs-target="#emailModal"
                                            data-id="{{ $admin->id }}">
                                        <i class="ti ti-mail-plus me-2"></i>@lang('admin/main.send_email')
                                    </button>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
@endsection
```

---

### 3.2 — `create.blade.php` & `edit.blade.php`

The current forms are **already well-aligned** with Users CRUD. The refinements are:

1. **Section dividers** to group fields visually (Identity → Auth → Role & Type → Preferences)
2. **Conditional role visibility** — when `type = super_admin`, hide `role_id` (the `crud.create` layout already has this JS hook; ensure both create + edit use the same select name conventions, which they do)
3. **Submit button parity** — already uses `btn-primary` (create) / `btn-success` (edit) + `submit-button` class ✓

#### `create.blade.php` — refactored

```blade
@extends('admin.layouts.crud.create')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
@endpush

@push('content')
    <form id="createAdminForm" class="mb-3 validated-form form" novalidate method="POST"
          action="{{ route('admin.admins.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Identity section --}}
        <div class="admins-form-section">
            <div class="admins-form-section__head">
                <i class="ti ti-user-circle"></i>
                <span>{{ __('admin/main.identity') }}</span>
            </div>
            <div class="row g-3">
                <x-form.image :options="['name' => 'image', 'label' => 'image', 'class' => 'col-md-12']" />
                <x-form.text  :options="['name' => 'name', 'label' => 'name', 'class' => 'col-md-6', 'isRequired' => true]" />
                <x-form.number :options="['name' => 'phone', 'label' => 'phone', 'class' => 'col-md-4', 'isRequired' => true, 'minLength' => 9, 'maxLength' => 15]" />
                <x-form.select :options="['name' => 'country_code', 'label' => 'country_code', 'class' => 'col-md-2', 'isRequired' => true, 'options' => $countries]" />
            </div>
        </div>

        {{-- Auth section --}}
        <div class="admins-form-section">
            <div class="admins-form-section__head">
                <i class="ti ti-lock"></i>
                <span>{{ __('admin/main.authentication') }}</span>
            </div>
            <div class="row g-3">
                <x-form.email :options="['name' => 'email', 'label' => 'email', 'class' => 'col-md-6', 'isRequired' => true]" />
                <x-form.password :options="['name' => 'password', 'label' => 'password', 'class' => 'col-md-6', 'isRequired' => true]" />
            </div>
        </div>

        {{-- Role & Type section --}}
        <div class="admins-form-section">
            <div class="admins-form-section__head">
                <i class="ti ti-shield-check"></i>
                <span>{{ __('admin/main.role_and_type') }}</span>
            </div>
            <div class="row g-3">
                <x-form.select :options="['name' => 'type', 'label' => 'type', 'class' => 'col-md-6', 'isRequired' => true, 'options' => $types]" />
                <x-form.select :options="['name' => 'role_id', 'label' => 'role', 'class' => 'col-md-6', 'options' => $roles, 'isRequired' => true]" />
            </div>
        </div>

        {{-- Preferences section --}}
        <div class="admins-form-section">
            <div class="admins-form-section__head">
                <i class="ti ti-adjustments"></i>
                <span>{{ __('admin/main.preferences') }}</span>
            </div>
            <div class="row g-3">
                <x-form.select :options="['name' => 'is_notify', 'value' => true, 'label' => 'receive_notifications', 'class' => 'col-md-6', 'options' => $receiveNotificationsOptions]" />
            </div>
        </div>

        <div class="pt-4 d-flex justify-content-center mt-3">
            <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button">
                <i class="ti ti-device-floppy me-1"></i>{{ __('admin/main.create') }}
            </button>
        </div>
    </form>
@endpush
```

`edit.blade.php` follows the same structure with `@method('PUT')` + `'value' => $admin->...` on each field + `btn-success` submit.

---

### 3.3 — `show.blade.php`

The current show is decent but lacks: stats row, permission visibility, super-admin signal, and account-age context.

```blade
@extends('admin.layouts.crud.show', ['model' => $admin])

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
@endpush

@push('header')
    <h5 class="mb-0 d-flex align-items-center gap-2">
        <i class="ti ti-shield" style="color:var(--color-brand-primary);"></i>
        {{ __('admin/main.admin_details') }}
        @if ($admin->id === 1)
            <span class="admin-type-badge admin-type-badge--super ms-2">
                <i class="ti ti-crown"></i>{{ __('admin/main.super_admin') }}
            </span>
        @endif
    </h5>
    <div class="d-flex gap-2 flex-wrap">
        @if ($admin->deleted_at)
            <a href="#" data-id="{{ $admin->id }}"
               data-route="{{ route('admin.admins.restore', ['id' => $admin->id]) }}"
               class="btn btn-sm btn-success restore-row">
                <i class="ti ti-arrow-back-up me-1"></i>{{ __('admin/main.restore') }}
            </a>
        @else
            <a href="{{ route('admin.admins.edit', $id) }}" class="btn btn-sm btn-success">
                <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
            </a>
            @if ($admin->id !== 1)
                <a href="#" data-id="{{ $admin->id }}"
                   data-route="{{ route('admin.admins.destroy', ['admin' => $admin]) }}"
                   class="btn btn-sm btn-danger delete-record">
                    <i class="ti ti-trash me-1"></i>{{ __('admin/main.delete') }}
                </a>
            @endif
        @endif
        <a href="{{ route('admin.admins.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
        </a>
    </div>
@endpush

@push('content')

    {{-- Stat row --}}
    <div class="col-12 mb-3">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--primary">
                    <div class="admin-stat-card__icon"><i class="ti ti-shield-check"></i></div>
                    <div>
                        <div class="admin-stat-card__label">{{ __('admin/main.role') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $admin->role_name }}">
                            {{ $admin->role_name }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--success">
                    <div class="admin-stat-card__icon"><i class="ti ti-lock-check"></i></div>
                    <div>
                        <div class="admin-stat-card__label">{{ __('admin/main.granted_permissions') }}</div>
                        <div class="admin-stat-card__value">{{ $permissionsCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--{{ $admin->is_blocked ? 'danger' : 'info' }}">
                    <div class="admin-stat-card__icon">
                        <i class="ti {{ $admin->is_blocked ? 'ti-lock' : 'ti-circle-check' }}"></i>
                    </div>
                    <div>
                        <div class="admin-stat-card__label">{{ __('admin/main.status') }}</div>
                        <div class="admin-stat-card__value">
                            {{ $admin->is_blocked ? __('admin/main.blocked') : __('admin/main.active') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--warning">
                    <div class="admin-stat-card__icon"><i class="ti ti-calendar-plus"></i></div>
                    <div>
                        <div class="admin-stat-card__label">{{ __('admin/main.account_age') }}</div>
                        <div class="admin-stat-card__value">
                            {{ $admin->created_at->diffForHumans(null, true) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Profile card --}}
    <div class="col-xl-4 col-md-5 mb-4">
        <div class="admin-profile-card">
            <div class="admin-profile-card__avatar-frame">
                <img src="{{ $admin->image }}" alt="{{ $admin->name }}" class="admin-profile-card__avatar">
                @if ($admin->type?->value === \App\Enums\AdminType::SUPER_ADMIN->value)
                    <span class="admin-profile-card__crown" title="{{ __('admin/main.super_admin') }}">
                        <i class="ti ti-crown-filled"></i>
                    </span>
                @endif
            </div>
            <h5 class="admin-profile-card__name">{{ $admin->name }}</h5>
            <div class="admin-profile-card__email">{{ $admin->email }}</div>

            <div class="admin-profile-card__chips">
                <span class="admin-status-pill {{ $admin->is_blocked ? 'is-blocked' : 'is-active' }}">
                    <span class="admin-status-pill__dot"></span>
                    {{ $admin->statusData()['label'] }}
                </span>
                @if ($admin->type)
                    <span class="admin-type-badge admin-type-badge--{{ $admin->type?->value === 'super_admin' ? 'super' : 'regular' }}">
                        @if ($admin->type?->value === 'super_admin')<i class="ti ti-crown"></i>@endif
                        {{ $admin->type->label() }}
                    </span>
                @endif
                @if (!empty($admin->role_name))
                    <span class="admin-role-badge"><i class="ti ti-shield-check"></i>{{ $admin->role_name }}</span>
                @endif
            </div>

            {{-- Block toggle (hidden for protected super admin) --}}
            @if ($admin->id !== 1)
                <div class="admin-profile-card__toggle">
                    <label class="d-flex align-items-center justify-content-between gap-2 m-0">
                        <span class="text-muted small">
                            <i class="ti ti-power"></i> {{ __('admin/main.account_state') }}
                        </span>
                        <input class="form-check-input switch-block" type="checkbox" role="switch"
                               data-id="{{ $admin->id }}"
                               data-route="{{ route('admin.admins.switchBlock', ['id' => $admin->id]) }}"
                               {{ !$admin->is_blocked ? 'checked' : '' }}>
                    </label>
                </div>
            @endif
        </div>
    </div>

    {{-- Details card --}}
    <div class="col-xl-8 col-md-7 mb-4">
        <div class="admin-details-card">
            <div class="admin-details-card__head">
                <h6 class="mb-0">
                    <i class="ti ti-info-circle me-1" style="color:var(--color-brand-primary);"></i>
                    {{ __('admin/main.admin_details') }}
                </h6>
            </div>
            <div class="admin-details-card__body">
                <div class="row g-3">
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-user',  'label' => __('admin/main.name'),         'value' => $admin->name])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-mail',  'label' => __('admin/main.email'),        'value' => $admin->email])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-phone', 'label' => __('admin/inputs.phone'),      'value' => $admin->full_phone ?? ('+' . $admin->country_code . ' ' . $admin->phone)])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-flag',  'label' => __('admin/inputs.country_code'), 'value' => $admin->country_code])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-crown', 'label' => __('admin/main.type'),         'value' => $admin->type?->label()])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-shield','label' => __('admin/main.role'),         'value' => $admin->role_name])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-bell',  'label' => __('admin/inputs.is_notify'),  'value' => $admin->is_notify ? __('admin/main.yes') : __('admin/main.no')])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar', 'label' => __('admin/main.created_at'), 'value' => $admin->created_at->format('Y-m-d H:i')])
                </div>
            </div>
        </div>
    </div>

    {{-- Permissions panel (full width) --}}
    @if (!empty($permissionsByGroup ?? []))
    <div class="col-12 mb-4">
        <div class="admin-permissions-panel">
            <div class="admin-permissions-panel__head">
                <h6 class="mb-0 d-flex align-items-center gap-2">
                    <i class="ti ti-lock-check" style="color:var(--color-brand-primary);"></i>
                    {{ __('admin/main.permissions') }}
                </h6>
                <span class="admin-permissions-panel__count">
                    {{ $permissionsCount ?? 0 }} {{ __('admin/main.permissions') }}
                </span>
            </div>
            <div class="admin-permissions-panel__body">
                <div class="row g-3">
                    @foreach ($permissionsByGroup as $groupKey => $routes)
                        <div class="col-md-6 col-xl-4">
                            <div class="perm-group h-100">
                                <div class="perm-group__header">
                                    <i class="ti ti-lock-square"></i>
                                    {{ \App\Traits\Role\RoleTrait::translateRouteName('admin.' . $groupKey) }}
                                </div>
                                <div class="perm-group__body">
                                    @foreach ($routes as $route)
                                        @php $on = in_array($route['name'], $permissions ?? []); @endphp
                                        <span class="perm-badge {{ $on ? 'perm-badge--on' : 'perm-badge--off' }}">
                                            @if ($on)<i class="ti ti-check"></i>@endif
                                            {{ $route['label'] }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

@endpush

@push('js')
    <script src="{{ asset('style/admin/custom-js/admin-table.js') }}"></script>
@endpush
```

#### `parts/detail-row.blade.php` — NEW reusable detail row

```blade
{{-- @param string $icon, string $label, mixed $value --}}
<div class="col-md-6">
    <div class="admin-detail-row">
        <span class="admin-detail-row__icon"><i class="ti {{ $icon }}"></i></span>
        <div class="min-w-0">
            <div class="admin-detail-row__label">{{ $label }}</div>
            <div class="admin-detail-row__value">{{ $value ?? '—' }}</div>
        </div>
    </div>
</div>
```

#### Backend support for show page

`AdminService::show($id)` needs to pass permission data — without changing the parent contract, add an override:

```php
// app/Services/Admin/AdminService.php
public function show($id): array
{
    $base = parent::show($id);            // returns ['admin' => ..., 'id' => ..., 'lowerClassName' => ...]
    $admin = $base[$this->lowerClassName] ?? $base['admin'];

    // Build permissions grouped (same shape used in roles show)
    $permissions = $admin->role
        ? $admin->role->permissions->pluck('permission')->toArray()
        : [];

    // Group all system routes the same way Role show does
    $permissionsByGroup = \App\Traits\Role\RoleTrait::groupedRoutes(); // returns ['users' => [...], ...]
    $permissionsCount   = count($permissions);

    return array_merge($base, compact('permissions', 'permissionsByGroup', 'permissionsCount'));
}
```

> If `RoleTrait::groupedRoutes()` doesn't exist by that name, use whatever helper the `roles.show` page already uses to build `$permissionsByGroup`. The goal is **reuse**, not duplicate logic.

---

## 4 · CSS Architecture — `public/style/admin/css/admins.css`

One dedicated file. Token-based. Dark-mode first-class.

```css
/* ═══════════════════════════════════════════════════════════
   ADMIN ROW — table cell styling
═══════════════════════════════════════════════════════════ */

.admins-table-row {
    transition: background var(--duration-fast) var(--ease-smooth);
}

.admins-table-row:hover {
    background: var(--surface-overlay);
}

.admins-table-row.is-super-admin-row::before {
    content: '';
    position: absolute;
    inset-inline-start: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg,
        rgba(var(--color-warning-rgb), 0.9),
        rgba(var(--color-warning-rgb), 0.3));
    pointer-events: none;
}

/* Super-admin crown overlay on avatar */
.admin-crown {
    position: absolute;
    top: -4px;
    inset-inline-end: -4px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #FBBF24, #D97706);
    color: #fff;
    font-size: 0.7rem;
    box-shadow: 0 2px 6px rgba(217, 119, 6, 0.45);
    z-index: 2;
}

.admin-name {
    color: var(--text-strong);
    font-size: 0.9rem;
}


/* ═══════════════════════════════════════════════════════════
   STATUS PILL — animated dot
═══════════════════════════════════════════════════════════ */

.admin-status-toggle {
    display: inline-block;
    cursor: pointer;
    margin: 0;
}

.admin-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.32rem 0.7rem;
    border-radius: var(--radius-pill);
    font-size: 0.72rem;
    font-weight: 600;
    line-height: 1;
    transition:
        background var(--duration-fast) var(--ease-smooth),
        border-color var(--duration-fast) var(--ease-smooth),
        color var(--duration-fast) var(--ease-smooth),
        transform var(--duration-fast) var(--ease-smooth);
    border: 1px solid transparent;
}

.admin-status-pill:hover { transform: scale(1.04); }

.admin-status-pill.is-active {
    background: var(--color-success-soft);
    color: var(--color-success);
    border-color: rgba(var(--color-success-rgb), 0.30);
}
.admin-status-pill.is-blocked {
    background: var(--color-danger-soft);
    color: var(--color-danger);
    border-color: rgba(var(--color-danger-rgb), 0.30);
}

.admin-status-pill__dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
    position: relative;
}

.admin-status-pill.is-active .admin-status-pill__dot::after {
    content: '';
    position: absolute;
    inset: -3px;
    border-radius: 50%;
    background: currentColor;
    opacity: 0.35;
    animation: admin-pulse 1.8s ease-out infinite;
}

@keyframes admin-pulse {
    0%   { transform: scale(0.85); opacity: 0.50; }
    100% { transform: scale(1.80); opacity: 0;    }
}

@media (prefers-reduced-motion: reduce) {
    .admin-status-pill.is-active .admin-status-pill__dot::after { animation: none; }
}


/* ═══════════════════════════════════════════════════════════
   TYPE & ROLE BADGES
═══════════════════════════════════════════════════════════ */

.admin-role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.28rem 0.6rem;
    border-radius: var(--radius-md);
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--color-brand-primary);
    background: rgba(var(--color-brand-primary-rgb), 0.10);
    border: 1px solid rgba(var(--color-brand-primary-rgb), 0.22);
    width: fit-content;
}

.admin-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.22rem 0.55rem;
    border-radius: var(--radius-sm);
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    width: fit-content;
}

.admin-type-badge--super {
    color: #B45309;
    background: linear-gradient(135deg,
        rgba(251, 191, 36, 0.20),
        rgba(217, 119, 6, 0.12));
    border: 1px solid rgba(217, 119, 6, 0.35);
}

.admin-type-badge--regular {
    color: var(--text-body);
    background: rgba(var(--color-brand-primary-rgb), 0.06);
    border: 1px solid var(--surface-border);
}


/* ═══════════════════════════════════════════════════════════
   ACTION BUTTONS  — themed, color-coded
═══════════════════════════════════════════════════════════ */

.admins-action-btn {
    width: 32px;
    height: 32px;
    border-radius: var(--radius-md);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid transparent;
    transition:
        background var(--duration-fast) var(--ease-smooth),
        border-color var(--duration-fast) var(--ease-smooth),
        color var(--duration-fast) var(--ease-smooth),
        transform var(--duration-fast) var(--ease-smooth);
    text-decoration: none;
    cursor: pointer;
}

.admins-action-btn:hover { transform: scale(1.08); }

.admins-action-view {
    background: rgba(var(--color-brand-primary-rgb), 0.10);
    border-color: rgba(var(--color-brand-primary-rgb), 0.22);
    color: var(--color-brand-primary);
}
.admins-action-view:hover {
    background: rgba(var(--color-brand-primary-rgb), 0.18);
    color: var(--color-brand-primary);
}

.admins-action-edit {
    background: var(--color-success-soft);
    border-color: rgba(var(--color-success-rgb), 0.28);
    color: var(--color-success);
}
.admins-action-edit:hover {
    background: rgba(var(--color-success-rgb), 0.20);
    color: var(--color-success);
}

.admins-action-delete {
    background: var(--color-danger-soft);
    border-color: rgba(var(--color-danger-rgb), 0.28);
    color: var(--color-danger);
}
.admins-action-delete:hover {
    background: rgba(var(--color-danger-rgb), 0.20);
    color: var(--color-danger);
}

.admins-action-restore {
    background: var(--color-info-soft);
    border-color: rgba(var(--color-brand-secondary-rgb), 0.28);
    color: var(--color-brand-secondary);
}

.admins-action-locked {
    background: rgba(0, 0, 0, 0.04);
    border-color: var(--surface-border);
    color: var(--text-muted);
    cursor: not-allowed;
}

.admins-action-more {
    background: rgba(var(--color-brand-primary-rgb), 0.04);
    border-color: var(--surface-border);
    color: var(--text-body);
}
.admins-action-more:hover {
    background: rgba(var(--color-brand-primary-rgb), 0.10);
    color: var(--text-strong);
}

.admin-more-menu {
    border-radius: var(--radius-md);
    border: 1px solid var(--surface-border);
    box-shadow: 0 8px 24px rgba(var(--color-brand-primary-rgb), 0.10);
    padding: 0.3rem;
}

.admin-more-menu .dropdown-item {
    border-radius: var(--radius-sm);
    font-size: 0.8rem;
    padding: 0.45rem 0.7rem;
}

.admin-more-menu .dropdown-item:hover {
    background: var(--surface-overlay);
    color: var(--color-brand-primary);
}


/* ═══════════════════════════════════════════════════════════
   FORM SECTIONS (create/edit)
═══════════════════════════════════════════════════════════ */

.admins-form-section {
    margin-bottom: 1.5rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid var(--surface-border);
}

.admins-form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0.5rem;
}

.admins-form-section__head {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    padding: 0.4rem 0.85rem;
    border-radius: var(--radius-pill);
    background: rgba(var(--color-brand-primary-rgb), 0.08);
    color: var(--color-brand-primary);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    border: 1px solid rgba(var(--color-brand-primary-rgb), 0.18);
}

.admins-form-section__head i { font-size: 0.85rem; }


/* ═══════════════════════════════════════════════════════════
   SHOW PAGE: stat cards (4 mini)
═══════════════════════════════════════════════════════════ */

.admin-stat-card {
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
    height: 100%;
}

.admin-stat-card:hover { transform: translateY(-2px); }

[data-theme*='dark'] .admin-stat-card,
.dark-style .admin-stat-card {
    background:
        linear-gradient(135deg, rgba(var(--accent-rgb), 0.14) 0%, transparent 70%),
        rgba(44, 49, 72, 0.78);
}

.admin-stat-card--primary { --accent-rgb: var(--color-brand-primary-rgb); }
.admin-stat-card--success { --accent-rgb: var(--color-success-rgb);       }
.admin-stat-card--info    { --accent-rgb: var(--color-brand-secondary-rgb); }
.admin-stat-card--warning { --accent-rgb: var(--color-warning-rgb);       }
.admin-stat-card--danger  { --accent-rgb: var(--color-danger-rgb);        }

.admin-stat-card__icon {
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

.admin-stat-card__label {
    font-size: 0.68rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--text-muted);
    margin-bottom: 0.15rem;
}

.admin-stat-card__value {
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--text-strong);
    line-height: 1.15;
    letter-spacing: -0.01em;
}


/* ═══════════════════════════════════════════════════════════
   SHOW PAGE: profile card
═══════════════════════════════════════════════════════════ */

.admin-profile-card {
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
    padding: 1.75rem 1.5rem 1.25rem;
    height: 100%;
    text-align: center;
}

[data-theme*='dark'] .admin-profile-card,
.dark-style .admin-profile-card {
    background:
        linear-gradient(135deg,
            rgba(var(--color-brand-primary-rgb), 0.12) 0%,
            rgba(var(--color-brand-secondary-rgb), 0.06) 100%),
        rgba(44, 49, 72, 0.80);
}

.admin-profile-card__avatar-frame {
    width: 140px;
    height: 140px;
    margin: 0 auto 1rem;
    padding: 4px;
    border-radius: 50%;
    background: linear-gradient(135deg,
        rgba(var(--color-brand-primary-rgb), 0.30),
        rgba(var(--color-brand-secondary-rgb), 0.15));
    position: relative;
}

.admin-profile-card__avatar {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--surface-base);
    background: var(--surface-base);
}

.admin-profile-card__crown {
    position: absolute;
    top: 0;
    inset-inline-end: 4px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #FBBF24, #D97706);
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
    box-shadow: 0 4px 12px rgba(217, 119, 6, 0.45);
    border: 2px solid var(--surface-base);
}

.admin-profile-card__name {
    color: var(--text-strong);
    font-weight: 700;
    margin: 0.25rem 0 0.1rem;
}

.admin-profile-card__email {
    color: var(--text-muted);
    font-size: 0.82rem;
    margin-bottom: 1rem;
}

.admin-profile-card__chips {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 0.4rem;
    margin-bottom: 1rem;
}

.admin-profile-card__toggle {
    padding: 0.6rem 0.8rem;
    border-radius: var(--radius-md);
    background: var(--surface-overlay);
    border: 1px solid var(--surface-border);
    margin-top: 1rem;
}


/* ═══════════════════════════════════════════════════════════
   SHOW PAGE: details + detail-row
═══════════════════════════════════════════════════════════ */

.admin-details-card,
.admin-permissions-panel {
    border-radius: var(--radius-xl);
    border: 1px solid var(--surface-border);
    background: var(--surface-base);
    box-shadow: 0 4px 14px rgba(var(--color-brand-primary-rgb), 0.04);
    height: 100%;
    overflow: hidden;
}

[data-theme*='dark'] .admin-details-card,
[data-theme*='dark'] .admin-permissions-panel,
.dark-style .admin-details-card,
.dark-style .admin-permissions-panel {
    background: rgba(44, 49, 72, 0.78);
}

.admin-details-card__head,
.admin-permissions-panel__head {
    padding: 0.85rem 1.25rem;
    border-bottom: 1px solid var(--surface-border);
    background: var(--surface-overlay);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.admin-details-card__body,
.admin-permissions-panel__body {
    padding: 1.25rem;
}

.admin-permissions-panel__count {
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.22rem 0.6rem;
    border-radius: var(--radius-pill);
    background: rgba(var(--color-brand-primary-rgb), 0.10);
    color: var(--color-brand-primary);
    border: 1px solid rgba(var(--color-brand-primary-rgb), 0.22);
}

.admin-detail-row {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.6rem 0.75rem;
    border-radius: var(--radius-md);
    transition: background var(--duration-fast) var(--ease-smooth);
}

.admin-detail-row:hover {
    background: var(--surface-overlay);
}

.admin-detail-row__icon {
    width: 32px;
    height: 32px;
    border-radius: var(--radius-sm);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--color-brand-primary-rgb), 0.10);
    color: var(--color-brand-primary);
    flex-shrink: 0;
    font-size: 1rem;
}

.admin-detail-row__label {
    font-size: 0.68rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--text-muted);
    margin-bottom: 0.2rem;
}

.admin-detail-row__value {
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--text-strong);
    word-break: break-word;
}
```

> Permission display reuses `.perm-group`, `.perm-badge--on/--off` from **plan-05/06**. Zero duplication.

---

## 5 · Acceptance Criteria

### Index page
- [ ] Statistics widget shows 6 cards: Total / Active / Blocked / Super Admins / Roles in Use / This Month (with growth delta)
- [ ] `<x-table.bulk-actions>` present (currently missing)
- [ ] Statistics endpoint `admin.admins.statistics` registered **before** `Route::resource` to avoid `{admin}` param capture
- [ ] Mobile (<md): row collapses to compact stack (avatar + name + role + email below)
- [ ] Super-admin (`id === 1`) row has a left-edge gold accent + crown on avatar
- [ ] Super-admin (`id === 1`) row has **no checkbox** and **no delete button** (replaced with locked icon + tooltip)
- [ ] Status uses `.admin-status-pill` with animated pulse on active
- [ ] Action buttons use `.admins-action-view/edit/delete/restore` + "more" dropdown for notification/email
- [ ] All tooltips render via `data-bs-toggle="tooltip"`

### Create / Edit
- [ ] Form is grouped into 4 sections (Identity / Auth / Role & Type / Preferences) with pill-style section heads
- [ ] Submit button has `ti ti-device-floppy` icon prefix
- [ ] `validated-form form submit-button` classes preserved (jqBootstrapValidation still works)
- [ ] Conditional role visibility (from `crud.create` layout JS) continues working when `type=super_admin`

### Show
- [ ] 4 mini stat cards at top: Role / Granted Permissions / Status / Account age
- [ ] Profile card has gradient avatar frame + crown badge for super admin
- [ ] Profile card has chip cluster (status pill + type badge + role badge)
- [ ] Profile card has explicit "Account state" toggle row (hidden for `id === 1`)
- [ ] Details card uses icon+label+value row pattern (`.admin-detail-row`)
- [ ] Permissions panel below shows grouped permission badges (assigned/unassigned) — reuses `.perm-group` from roles
- [ ] Delete button hidden for `id === 1` (super-admin protection visible in UI, not just server-side)

### Global
- [ ] All new CSS in `public/style/admin/css/admins.css`
- [ ] All colors from `tokens.css` — no hardcoded hex except crown gradient (intentional gold)
- [ ] Dark mode tested on every new class
- [ ] All new strings in `lang/ar/admin/main.php` + `lang/en/admin/main.php`: `super_admins`, `regular_admins`, `roles_in_use`, `granted_permissions`, `account_age`, `account_state`, `protected_super_admin`, `identity`, `authentication`, `role_and_type`, `preferences`
- [ ] No existing JS files modified — only new `<script>` tag for stats URL in index
- [ ] Permissions display on show page reuses `.perm-group` / `.perm-badge` (no duplicate CSS)

---

## 6 · UX Rationale (for reviewers)

| Decision | Why |
|---|---|
| Statistics include **"Super Admins"** + **"Roles in Use"** instead of generic Today/Week | Operationally meaningful: how many people have unrestricted access? how diverse is the role distribution? |
| Crown badge on super-admin row | Recognition in <100ms — super admins are operationally distinct, this is a security signal |
| Hide delete for `id === 1` in UI | Server already rejects this. Showing the locked icon teaches users *why* — better UX than a failed action |
| Hide checkbox for super-admin row | Prevents accidental inclusion in bulk delete — defense in depth |
| Form sections with pill heads | 9 fields without grouping is a wall of inputs. Sections create cognitive checkpoints |
| Animated pulse on active status pill | "alive" feels different from "ok" — subtle but conveys live state |
| Permission panel on show page | The single most important question about an admin is "what can they do?" — answer it inline |
| Account age via `diffForHumans` | "3 months ago" reads faster than `2025-02-12` when scanning |
| Type badge with gold gradient for super | Distinctive without being loud; aligns with `--tier-gold-*` tokens that already exist |
| 32-row action buttons (not 28 or 36) | Same metric as Users CRUD — visual rhythm across the dashboard |
| Mobile stack: name → role → email | Role is more important than email when triaging — promote it above |
