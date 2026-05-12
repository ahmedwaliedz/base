# Plan 04 — Users Show Page: Tabs & Advanced Overview

## Problem

1. **Tabs are missing** — show page has no tabs; all content is in one flat layout
2. **Edit button duplicated** — `edit` button appears twice in `@push('header')` (lines 13 and 24)
3. **Overview layout is basic** — single `<dl>` list, no visual hierarchy, no two-column card grid
4. **No related data tabs** — Complaints, Contact Messages, Wallet not accessible from user profile

---

## Files to Change

| File | Change |
|------|--------|
| `resources/views/admin/users/show.blade.php` | Full restructure: tabs + fix edit button + advanced overview |
| `app/Http/Controllers/Admin/UserController.php` | Pass related data to `show()` (or load via AJAX) |
| `app/Services/Admin/UserService.php` | Add `showVars()` with related model counts/data |
| `resources/views/admin/users/parts/` | Add tab partials: `tab-complaints.blade.php`, `tab-contacts.blade.php`, `tab-wallet.blade.php` |

---

## Implementation Steps

### Step 1 — Fix Duplicate Edit Button

In `show.blade.php` `@push('header')`, the edit `<a>` appears at lines 13–15 **and** 24–26.
Remove the second occurrence (lines 24–26).

```blade
{{-- Remove this duplicate block --}}
<a href="{{ route('admin.users.edit', $id) }}" class="btn btn-success me-2">
    <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
</a>
```

**Styled edit button** — match the show page header style:

```blade
<a href="{{ route('admin.users.edit', $id) }}" class="btn btn-sm btn-primary">
    <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
</a>
```

### Step 2 — Add Tab Navigation

Replace the current flat `@push('content')` with a tabbed layout:

```blade
@push('content')
<div class="row g-4 mb-4">
    {{-- Profile card (left col — stays visible across all tabs) --}}
    <div class="col-xl-3 col-md-4">
        @include('admin.users.parts.show-profile-card')
    </div>

    {{-- Tabs (right col) --}}
    <div class="col-xl-9 col-md-8">
        <ul class="nav nav-pills nav-fill mb-4 gap-1" role="tablist">
            <li class="nav-item">
                <a class="nav-link active px-4 py-2" data-bs-toggle="tab" href="#tab-overview">
                    <i class="ti ti-layout-dashboard me-1"></i>{{ __('admin/main.overview') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-4 py-2" data-bs-toggle="tab" href="#tab-complaints">
                    <i class="ti ti-message-report me-1"></i>{{ __('admin/main.complaints') }}
                    @if($complaintsCount)
                        <span class="badge bg-label-danger ms-1">{{ $complaintsCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-4 py-2" data-bs-toggle="tab" href="#tab-contacts">
                    <i class="ti ti-mail me-1"></i>{{ __('admin/main.contact_messages') }}
                    @if($contactsCount)
                        <span class="badge bg-label-info ms-1">{{ $contactsCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-4 py-2" data-bs-toggle="tab" href="#tab-wallet">
                    <i class="ti ti-wallet me-1"></i>{{ __('admin/main.wallet') }}
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-overview">
                @include('admin.users.parts.tab-overview')
            </div>
            <div class="tab-pane fade" id="tab-complaints">
                @include('admin.users.parts.tab-complaints')
            </div>
            <div class="tab-pane fade" id="tab-contacts">
                @include('admin.users.parts.tab-contacts')
            </div>
            <div class="tab-pane fade" id="tab-wallet">
                @include('admin.users.parts.tab-wallet')
            </div>
        </div>
    </div>
</div>
@endpush
```

### Step 3 — Advanced Overview Tab (Two-Column Grid)

`resources/views/admin/users/parts/tab-overview.blade.php`:

```blade
<div class="row g-3">

    {{-- Column 1: Personal Info --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pb-0">
                <h6 class="mb-0"><i class="ti ti-user me-2 text-primary"></i>{{ __('admin/main.personal_info') }}</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0 row-gap-2">
                    <dt class="col-5 text-muted small">{{ __('admin/main.name') }}</dt>
                    <dd class="col-7 fw-semibold small">{{ $user->name }}</dd>

                    <dt class="col-5 text-muted small">{{ __('admin/main.email') }}</dt>
                    <dd class="col-7 fw-semibold small">{{ $user->email ?? '—' }}</dd>

                    <dt class="col-5 text-muted small">{{ __('admin/inputs.phone') }}</dt>
                    <dd class="col-7 fw-semibold small">{{ $user->full_phone ?? '+' . $user->country_code . ' ' . $user->phone }}</dd>

                    <dt class="col-5 text-muted small">{{ __('admin/main.registered_at') }}</dt>
                    <dd class="col-7 fw-semibold small">{{ $user->created_at->format('Y-m-d') }}</dd>
                </dl>
            </div>
        </div>
    </div>

    {{-- Column 2: Account Status --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pb-0">
                <h6 class="mb-0"><i class="ti ti-shield-check me-2 text-primary"></i>{{ __('admin/main.account_status') }}</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0 row-gap-2">
                    <dt class="col-5 text-muted small">{{ __('admin/main.status') }}</dt>
                    <dd class="col-7 small">
                        <span class="badge status-badge {{ $user->statusData()['class'] }}">
                            {{ $user->statusData()['label'] }}
                        </span>
                    </dd>

                    <dt class="col-5 text-muted small">{{ __('admin/main.is_active') }}</dt>
                    <dd class="col-7 small">
                        <span class="badge {{ $user->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                            {{ $user->is_active ? __('admin/main.yes') : __('admin/main.no') }}
                        </span>
                    </dd>

                    <dt class="col-5 text-muted small">{{ __('admin/main.is_notify') }}</dt>
                    <dd class="col-7 small">
                        <span class="badge {{ $user->is_notify ? 'bg-label-info' : 'bg-label-secondary' }}">
                            {{ $user->is_notify ? __('admin/main.yes') : __('admin/main.no') }}
                        </span>
                    </dd>

                    <dt class="col-5 text-muted small">{{ __('admin/main.phone_verified') }}</dt>
                    <dd class="col-7 small">
                        <span class="badge {{ $user->phone_verified_at ? 'bg-label-success' : 'bg-label-warning' }}">
                            {{ $user->phone_verified_at ? $user->phone_verified_at->format('Y-m-d') : __('admin/main.not_verified') }}
                        </span>
                    </dd>
                </dl>
            </div>
        </div>
    </div>

</div>
```

### Step 4 — Complaints Tab Partial

`resources/views/admin/users/parts/tab-complaints.blade.php`:

```blade
@if($complaints->isEmpty())
    <div class="text-center text-muted py-5">
        <i class="ti ti-message-off fs-1 d-block mb-2"></i>
        {{ __('admin/main.no_complaints') }}
    </div>
@else
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>{{ __('admin/main.subject') }}</th>
                    <th>{{ __('admin/main.type') }}</th>
                    <th>{{ __('admin/main.status') }}</th>
                    <th>{{ __('admin/main.created_at') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($complaints as $complaint)
                <tr>
                    <td>{{ $complaint->id }}</td>
                    <td>{{ Str::limit($complaint->subject, 40) }}</td>
                    <td><span class="badge bg-label-secondary">{{ $complaint->type->value }}</span></td>
                    <td><span class="badge bg-label-{{ match($complaint->status->value) { 'pending'=>'warning', 'processing'=>'info', 'completed'=>'success', 'rejected'=>'danger', default=>'secondary' } }}">{{ $complaint->status->value }}</span></td>
                    <td class="text-muted small">{{ $complaint->created_at->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
```

### Step 5 — Wallet Tab Partial (Placeholder)

`resources/views/admin/users/parts/tab-wallet.blade.php`:

```blade
{{-- Wallet tab — wire up when wallet/transactions module is added to the project --}}
<div class="text-center text-muted py-5">
    <i class="ti ti-wallet fs-1 d-block mb-2"></i>
    {{ __('admin/main.wallet_coming_soon') }}
</div>
```

### Step 6 — Pass Data from Controller

In `UserController::show()` (or override `showVars()` in `UserService`):

```php
// app/Services/Admin/UserService.php
public function showVars(): array
{
    return [];
}

// Override in show() data merge — add to CrudBaseService show():
// OR override in UserService:
public function show($id): array
{
    $user = User::withTrashed()->findOrFail($id);

    return [
        'user'            => $user,
        'id'              => $id,
        'lowerClassName'  => 'user',
        'complaints'      => $user->complaints()->latest()->limit(20)->get(),
        'complaintsCount' => $user->complaints()->count(),
        'contacts'        => \App\Models\ContactMessage::where('user_id', $user->id)->latest()->limit(20)->get(),
        'contactsCount'   => \App\Models\ContactMessage::where('user_id', $user->id)->count(),
    ];
}
```

> **Note:** Add `user_id` FK to `contact_messages` table if not already present. If Complaint uses `complaiantable` morph, use `morphMany` scope instead of direct FK.

---

## Acceptance Criteria

- [ ] Edit button appears exactly once, styled consistently
- [ ] 4 tabs visible: Overview, Complaints, Contact Messages, Wallet
- [ ] Tabs are wide/prominent (`nav-pills nav-fill`)
- [ ] Badge counters on tabs show number of related records
- [ ] Overview tab shows two cards side by side (personal info + account status)
- [ ] Complaints tab shows a table with status badges
- [ ] Wallet tab shows a placeholder until module is built
- [ ] No duplicate data fetching — counts and lists loaded once from service
