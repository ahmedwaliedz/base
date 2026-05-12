# Plan — User Profile Page: Bigger Tabs + Complaints/Contact/Wallet

**Status:** Draft (no code shipped yet)
**Audience:** Cursor / coding agent
**Scope:** `admin/users/show.blade.php` + its parts; `UserController::show()`;
new `Wallet` + `WalletTransaction` model/migration/service; new
`WalletController` for admin admin-side credit/debit.
**Branches affected:** master
**Companion to:** [`user_crud_form_polish_and_profile_page.md`](./user_crud_form_polish_and_profile_page.md)

---

## TL;DR

Two pieces of work:

1. **Resize tabs** — current `.user-profile__tab` is ~0.55rem padding,
   small icon, easy to miss. Bump padding, font, and icon size so the
   tab strip looks like a primary nav surface, not a polite footer.

2. **Three new tabs**:
   - **Complaints** — list of complaints this user filed (filter by
     `complaints.email = $user->email` OR `complaints.phone = $user->phone`).
   - **Contact** — list of contact messages with same matching.
   - **Wallet** — balance + transaction history + two admin actions
     ("Add credit" and "Debit"). Requires a tiny new `wallets` schema.

Existing 5 tabs (Overview, Activity, Sessions, Security, Danger zone)
stay. New tab count = **8**, ordered:

```
Overview · Activity · Sessions · Complaints · Contact · Wallet · Security · Danger zone
```

This plan covers everything end-to-end — schema, model, controller,
service, blade, CSS, translations.

---

## Part 1 · Tab sizing

### Current (from `user-crud.css` per the companion plan)

```css
.user-profile__tab.nav-link {
    padding: .55rem .9rem !important;
    border-radius: 12px !important;
    /* font size inherits — ~0.875rem */
}
.user-profile__tab.nav-link i { font-size: 1rem; }
```

### Target

```css
.user-profile__tab.nav-link {
    padding: 0.85rem 1.35rem !important;
    border-radius: 14px !important;
    font-size: 0.95rem;
    font-weight: 600;
    gap: 0.55rem;
}
.user-profile__tab.nav-link i {
    font-size: 1.15rem;
}
.user-profile__tabs.nav-tabs {
    padding: 0.5rem 0.5rem 0;
    gap: 0.4rem;
}
.user-profile__tab.nav-link.active {
    background: linear-gradient(135deg,
        rgba(var(--color-brand-primary-rgb), 0.20) 0%,
        rgba(var(--color-brand-primary-rgb), 0.08) 100%) !important;
    box-shadow:
        inset 0 0 0 1px rgba(var(--color-brand-primary-rgb), 0.25),
        0 2px 12px rgba(var(--color-brand-primary-rgb), 0.22);
}
@media (max-width: 991.98px) {
    .user-profile__tab.nav-link {
        padding: 0.65rem 1rem !important;
        font-size: 0.88rem;
    }
}
```

Also: on viewports < 768px the 8-tab nav will wrap to two lines.
That's fine — keep `flex-wrap: wrap` (already set). For very small
viewports consider `overflow-x: auto` + `flex-wrap: nowrap` so tabs
become a horizontal scroll:

```css
@media (max-width: 575.98px) {
    .user-profile__tabs.nav-tabs {
        flex-wrap: nowrap;
        overflow-x: auto;
        scrollbar-width: thin;
    }
    .user-profile__tab.nav-link {
        flex-shrink: 0;
        white-space: nowrap;
    }
}
```

---

## Part 2 · Complaints tab

### Backend — extend `UserController::show($id)`

Add to the existing show method (the one introduced by the companion plan):

```php
use App\Models\Complaint;

// Inside show($id) — after the existing $sessions / $recentOtps lookups:

$complaints = Schema::hasTable('complaints')
    ? Complaint::query()
        ->where(function ($q) use ($user) {
            $q->where('email', $user->email)
              ->orWhere('phone', $user->phone);
        })
        ->latest()
        ->take(20)
        ->get()
    : collect();
```

Pass `'complaints' => $complaints` into the view.

### Blade — `resources/views/admin/users/parts/show-complaints.blade.php`

```blade
@if($complaints->isEmpty())
    <div class="user-profile__empty">
        <i class="ti ti-mood-off"></i>
        <p>{{ __('admin/main.no_complaints_for_user') }}</p>
    </div>
@else
    <div class="user-profile__list">
        @foreach($complaints as $c)
            <div class="up-complaint up-complaint--{{ $c->status }}">
                <div class="up-complaint__head">
                    <span class="up-complaint__subject">{{ $c->subject }}</span>
                    <span class="up-complaint__status up-complaint__status--{{ $c->status }}">
                        {{ __('admin/main.complaint_status_' . $c->status) }}
                    </span>
                </div>
                <p class="up-complaint__body">{{ \Str::limit($c->message, 220) }}</p>
                <div class="up-complaint__foot">
                    <span><i class="ti ti-clock"></i> {{ $c->created_at->diffForHumans() }}</span>
                    @if(Route::has('admin.complaints.show'))
                        <a href="{{ route('admin.complaints.show', $c->id) }}" class="up-complaint__link">
                            {{ __('admin/main.view_details') }} →
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
```

### Blade — wire the include in `show.blade.php`:

```blade
<li class="nav-item">
    <a class="user-profile__tab nav-link" data-bs-toggle="tab" href="#tab-complaints">
        <i class="ti ti-mood-sad"></i> {{ __('admin/main.complaints') }}
        @if($complaints->isNotEmpty())
            <span class="user-profile__tab-count">{{ $complaints->count() }}</span>
        @endif
    </a>
</li>

{{-- tab pane --}}
<div class="tab-pane fade" id="tab-complaints">
    @include('admin.users.parts.show-complaints', compact('complaints'))
</div>
```

---

## Part 3 · Contact messages tab

### Backend — extend the same show method

```php
use Illuminate\Support\Facades\DB;

$contactMessages = Schema::hasTable('contact_messages')
    ? DB::table('contact_messages')
        ->where(function ($q) use ($user) {
            $q->where('email', $user->email)
              ->orWhere('phone', $user->phone);
        })
        ->orderByDesc('created_at')
        ->take(20)
        ->get()
    : collect();
```

### Blade — `resources/views/admin/users/parts/show-contact.blade.php`

```blade
@if($contactMessages->isEmpty())
    <div class="user-profile__empty">
        <i class="ti ti-mail-off"></i>
        <p>{{ __('admin/main.no_contact_messages_for_user') }}</p>
    </div>
@else
    <div class="user-profile__list">
        @foreach($contactMessages as $m)
            <div class="up-contact">
                <div class="up-contact__head">
                    <span class="up-contact__subject">{{ $m->subject ?? __('admin/main.no_subject') }}</span>
                    <span class="up-contact__time">{{ \Carbon\Carbon::parse($m->created_at)->diffForHumans() }}</span>
                </div>
                <p class="up-contact__body">{{ \Str::limit($m->message ?? '', 280) }}</p>
                @if(Route::has('admin.contact-messages.show'))
                    <a href="{{ route('admin.contact-messages.show', $m->id) }}" class="up-contact__link">
                        {{ __('admin/main.view_details') }} →
                    </a>
                @endif
            </div>
        @endforeach
    </div>
@endif
```

### Tab wire

```blade
<li class="nav-item">
    <a class="user-profile__tab nav-link" data-bs-toggle="tab" href="#tab-contact">
        <i class="ti ti-message"></i> {{ __('admin/main.contact_messages') }}
        @if($contactMessages->isNotEmpty())
            <span class="user-profile__tab-count">{{ $contactMessages->count() }}</span>
        @endif
    </a>
</li>

<div class="tab-pane fade" id="tab-contact">
    @include('admin.users.parts.show-contact', compact('contactMessages'))
</div>
```

---

## Part 4 · Wallet tab (the big one)

### 4.1 Schema

Two new tables.

**Migration 1: `wallets`** — `database/migrations/wallets/2026_XX_XX_create_wallets_table.php`

```php
Schema::create('wallets', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
    $table->decimal('balance', 12, 2)->default(0);
    $table->string('currency', 3)->default('SAR');
    $table->boolean('is_active')->default(true)->index();
    $table->timestamps();
});
```

One wallet per user (`user_id` UNIQUE). Auto-created on first transaction.

**Migration 2: `wallet_transactions`** — same directory:

```php
Schema::create('wallet_transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
    $table->foreignId('admin_id')->nullable()->constrained()->nullOnDelete();
    $table->enum('type', ['credit', 'debit']);
    $table->decimal('amount', 12, 2);
    $table->decimal('balance_after', 12, 2);
    $table->string('reason')->nullable();
    $table->text('note')->nullable();
    $table->timestamps();

    $table->index(['wallet_id', 'created_at']);
});
```

Every credit/debit logs **who did it** (`admin_id`) and the **balance
after**, so the audit trail survives even if a transaction is later
deleted.

### 4.2 Models

**`app/Models/Wallet.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'balance', 'currency', 'is_active'];

    protected $casts = [
        'balance'   => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class)->latest();
    }
}
```

**`app/Models/WalletTransaction.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id', 'admin_id', 'type', 'amount', 'balance_after', 'reason', 'note',
    ];

    protected $casts = [
        'amount'        => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
```

**`app/Models/User.php`** — add the relation:

```php
public function wallet()
{
    return $this->hasOne(Wallet::class);
}
```

### 4.3 Service — `app/Services/Admin/WalletService.php`

```php
<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function getOrCreate(User $user): Wallet
    {
        return $user->wallet ?: Wallet::create([
            'user_id'  => $user->id,
            'balance'  => 0,
            'currency' => 'SAR',
        ]);
    }

    public function credit(User $user, float $amount, ?string $reason = null, ?string $note = null): WalletTransaction
    {
        return $this->record($user, 'credit', $amount, $reason, $note);
    }

    public function debit(User $user, float $amount, ?string $reason = null, ?string $note = null): WalletTransaction
    {
        return $this->record($user, 'debit', $amount, $reason, $note);
    }

    private function record(User $user, string $type, float $amount, ?string $reason, ?string $note): WalletTransaction
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException(__('admin/main.wallet_amount_must_be_positive'));
        }

        return DB::transaction(function () use ($user, $type, $amount, $reason, $note) {
            $wallet = $this->getOrCreate($user);

            if ($type === 'debit' && $wallet->balance < $amount) {
                throw new \DomainException(__('admin/main.wallet_insufficient_balance'));
            }

            $delta = $type === 'credit' ? $amount : -$amount;
            $wallet->increment('balance', $delta);
            $wallet->refresh();

            return WalletTransaction::create([
                'wallet_id'     => $wallet->id,
                'admin_id'      => auth('admin')->id(),
                'type'          => $type,
                'amount'        => $amount,
                'balance_after' => $wallet->balance,
                'reason'        => $reason,
                'note'          => $note,
            ]);
        });
    }
}
```

### 4.4 Controller — `app/Http/Controllers/Admin/WalletController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(private WalletService $wallet) {}

    public function credit(Request $request, int $userId)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:1000000'],
            'reason' => ['nullable', 'string', 'max:120'],
            'note'   => ['nullable', 'string', 'max:500'],
        ]);

        $user = User::findOrFail($userId);
        $tx = $this->wallet->credit($user, (float) $data['amount'], $data['reason'] ?? null, $data['note'] ?? null);

        return response()->json([
            'message' => __('admin/main.wallet_credit_success'),
            'data'    => ['balance' => $tx->balance_after, 'route' => route('admin.users.show', $userId)],
        ]);
    }

    public function debit(Request $request, int $userId)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:1000000'],
            'reason' => ['nullable', 'string', 'max:120'],
            'note'   => ['nullable', 'string', 'max:500'],
        ]);

        $user = User::findOrFail($userId);
        try {
            $tx = $this->wallet->debit($user, (float) $data['amount'], $data['reason'] ?? null, $data['note'] ?? null);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'message' => __('admin/main.wallet_debit_success'),
            'data'    => ['balance' => $tx->balance_after, 'route' => route('admin.users.show', $userId)],
        ]);
    }
}
```

### 4.5 Routes — `routes/admin.php`

```php
Route::prefix('users/{user}/wallet')->name('users.wallet.')->group(function () {
    Route::post('credit', [WalletController::class, 'credit'])->name('credit');
    Route::post('debit',  [WalletController::class, 'debit'])->name('debit');
});
```

### 4.6 Backend — extend `UserController::show($id)`

```php
$wallet = $user->wallet
    ? $user->wallet->load(['transactions' => fn ($q) => $q->take(20)])
    : null;
$walletBalance = $wallet?->balance ?? 0;
$walletTransactions = $wallet?->transactions ?? collect();
```

Pass into view:

```php
return view('admin.users.show', [
    /* …existing… */
    'wallet'             => $wallet,
    'walletBalance'      => $walletBalance,
    'walletTransactions' => $walletTransactions,
]);
```

### 4.7 Blade — `resources/views/admin/users/parts/show-wallet.blade.php`

```blade
{{-- Wallet hero — big balance number --}}
<div class="up-wallet">
    <div class="up-wallet__hero">
        <div class="up-wallet__balance-label">{{ __('admin/main.current_balance') }}</div>
        <div class="up-wallet__balance">
            <span class="up-wallet__amount">{{ number_format($walletBalance, 2) }}</span>
            <span class="up-wallet__currency">{{ $wallet?->currency ?? 'SAR' }}</span>
        </div>
        <div class="up-wallet__actions">
            <button type="button" class="btn btn-primary"
                    data-bs-toggle="modal" data-bs-target="#walletCreditModal">
                <i class="ti ti-plus me-1"></i>{{ __('admin/main.add_credit') }}
            </button>
            <button type="button" class="btn btn-label-warning"
                    data-bs-toggle="modal" data-bs-target="#walletDebitModal">
                <i class="ti ti-minus me-1"></i>{{ __('admin/main.debit') }}
            </button>
        </div>
    </div>

    {{-- Transaction history --}}
    <h6 class="user-profile__subhead mt-4">{{ __('admin/main.transaction_history') }}</h6>
    @if($walletTransactions->isEmpty())
        <div class="user-profile__empty">
            <i class="ti ti-receipt-off"></i>
            <p>{{ __('admin/main.no_wallet_transactions') }}</p>
        </div>
    @else
        <table class="up-wallet-table">
            <thead>
                <tr>
                    <th>{{ __('admin/main.date') }}</th>
                    <th>{{ __('admin/main.type') }}</th>
                    <th class="text-end">{{ __('admin/main.amount') }}</th>
                    <th class="text-end">{{ __('admin/main.balance_after') }}</th>
                    <th>{{ __('admin/main.reason') }}</th>
                    <th>{{ __('admin/main.by_admin') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($walletTransactions as $tx)
                    <tr>
                        <td>{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <span class="up-wallet-pill up-wallet-pill--{{ $tx->type }}">
                                <i class="ti ti-{{ $tx->type === 'credit' ? 'arrow-down-right' : 'arrow-up-right' }}"></i>
                                {{ __('admin/main.tx_type_' . $tx->type) }}
                            </span>
                        </td>
                        <td class="text-end up-wallet__amount-cell up-wallet__amount-cell--{{ $tx->type }}">
                            {{ $tx->type === 'credit' ? '+' : '−' }}{{ number_format($tx->amount, 2) }}
                        </td>
                        <td class="text-end">{{ number_format($tx->balance_after, 2) }}</td>
                        <td>{{ $tx->reason ?? '—' }}</td>
                        <td>{{ $tx->admin?->name ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- Modals --}}
<x-wallet.credit-modal :user="$user" />
<x-wallet.debit-modal  :user="$user" />
```

### 4.8 Wallet modals (Blade components)

`resources/views/components/wallet/credit-modal.blade.php`:

```blade
@props(['user'])

<div class="modal fade" id="walletCreditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-simple modal-add-new-address">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <div class="mb-4">
                    <h3 class="address-title mb-2">{{ __('admin/main.add_credit') }}</h3>
                    <p class="text-muted address-subtitle">{{ __('admin/main.add_credit_hint') }}</p>
                </div>
                <form method="POST" action="{{ route('admin.users.wallet.credit', $user->id) }}"
                      class="row g-3 validated-form" novalidate>
                    @csrf
                    <x-form.number   :options="['name' => 'amount', 'label' => 'amount',  'class' => 'col-md-12', 'isRequired' => true]" />
                    <x-form.text     :options="['name' => 'reason', 'label' => 'reason',  'class' => 'col-md-12']" />
                    <x-form.text-area:options="['name' => 'note',   'label' => 'note',    'class' => 'col-md-12']" />
                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-plus me-1"></i>{{ __('admin/main.add_credit') }}
                        </button>
                        <button type="reset" class="btn btn-label-danger me-sm-3 me-1" data-bs-dismiss="modal">
                            <i class="ti ti-x me-1"></i>{{ __('admin/main.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
```

Mirror for debit-modal (same shape, target `…wallet.debit`, button label
"Debit").

### 4.9 Tab wire

```blade
<li class="nav-item">
    <a class="user-profile__tab nav-link" data-bs-toggle="tab" href="#tab-wallet">
        <i class="ti ti-wallet"></i> {{ __('admin/main.wallet') }}
        <span class="user-profile__tab-count">{{ number_format($walletBalance, 0) }}</span>
    </a>
</li>

<div class="tab-pane fade" id="tab-wallet">
    @include('admin.users.parts.show-wallet', compact('user','wallet','walletBalance','walletTransactions'))
</div>
```

---

## Part 5 · CSS — append to `user-crud.css`

```css
/* ── Tab count badge ────────────────────────────────── */
.user-profile__tab-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    height: 22px;
    padding: 0 0.4rem;
    background: rgba(var(--color-brand-primary-rgb), 0.20);
    color: var(--color-brand-primary);
    font-size: 0.7rem;
    font-weight: 700;
    border-radius: 999px;
    margin-inline-start: 0.35rem;
}
.user-profile__tab.nav-link.active .user-profile__tab-count {
    background: var(--color-brand-primary);
    color: #fff;
}

/* ── Complaint card ─────────────────────────────────── */
.user-profile__list { display: flex; flex-direction: column; gap: 0.85rem; }
.up-complaint {
    border-radius: var(--radius-lg);
    border: 1px solid rgba(var(--color-brand-primary-rgb), 0.12);
    background: rgba(var(--color-brand-primary-rgb), 0.03);
    padding: 1rem 1.15rem;
}
.up-complaint__head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 0.4rem; flex-wrap: wrap; }
.up-complaint__subject { font-weight: 700; color: var(--text-strong); }
.up-complaint__status { padding: 0.15rem 0.6rem; border-radius: 999px; font-size: 0.74rem; font-weight: 600; }
.up-complaint__status--pending    { background: rgba(245,158,11,.14); color: #8a5b00; }
.up-complaint__status--in_progress{ background: rgba(0,207,232,.14);  color: #00788b; }
.up-complaint__status--resolved   { background: rgba(40,199,111,.14); color: #1f7a4d; }
.up-complaint__status--rejected   { background: rgba(234,84,85,.14);  color: #b5302e; }
.up-complaint__body { color: var(--text-body); font-size: 0.88rem; margin: 0 0 0.5rem; }
.up-complaint__foot { display: flex; justify-content: space-between; color: var(--text-muted); font-size: 0.8rem; }
.up-complaint__link { color: var(--color-brand-primary); font-weight: 600; text-decoration: none; }

/* ── Contact card (same family) ─────────────────────── */
.up-contact {
    border-radius: var(--radius-lg);
    border: 1px solid rgba(var(--color-brand-primary-rgb), 0.12);
    background: rgba(var(--color-brand-primary-rgb), 0.03);
    padding: 1rem 1.15rem;
}
.up-contact__head { display: flex; justify-content: space-between; margin-bottom: 0.4rem; }
.up-contact__subject { font-weight: 700; color: var(--text-strong); }
.up-contact__time { color: var(--text-muted); font-size: 0.8rem; }
.up-contact__body { color: var(--text-body); font-size: 0.88rem; margin: 0 0 0.5rem; }
.up-contact__link { color: var(--color-brand-primary); font-weight: 600; text-decoration: none; }

/* ── Wallet hero ────────────────────────────────────── */
.up-wallet__hero {
    border-radius: var(--radius-xl);
    padding: 1.75rem 2rem;
    background:
        radial-gradient(ellipse 60% 80% at 0% 0%,
            rgba(var(--color-brand-secondary-rgb), 0.20) 0%, transparent 60%),
        linear-gradient(135deg,
            rgba(var(--color-brand-primary-rgb), 0.18) 0%,
            rgba(var(--color-brand-primary-rgb), 0.06) 100%);
    border: 1px solid rgba(var(--color-brand-primary-rgb), 0.22);
    text-align: center;
}
.up-wallet__balance-label {
    font-size: 0.78rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.35rem;
}
.up-wallet__balance {
    display: flex; align-items: baseline; justify-content: center; gap: 0.5rem;
    margin-bottom: 1.15rem;
}
.up-wallet__amount {
    font-size: 2.6rem;
    font-weight: 800;
    background: linear-gradient(135deg, var(--color-brand-primary), var(--color-brand-secondary));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}
.up-wallet__currency { font-size: 1rem; color: var(--text-muted); font-weight: 600; }
.up-wallet__actions { display: inline-flex; gap: 0.5rem; }

/* ── Wallet transaction table ───────────────────────── */
.up-wallet-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 0.88rem; }
.up-wallet-table th, .up-wallet-table td {
    padding: 0.7rem 0.85rem;
    border-bottom: 1px solid rgba(var(--color-brand-primary-rgb), 0.08);
}
.up-wallet-table thead th {
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.72rem;
    letter-spacing: 0.04em;
    text-align: start;
}
.up-wallet-pill {
    display: inline-flex; align-items: center; gap: 0.25rem;
    padding: 0.15rem 0.55rem; border-radius: 999px;
    font-size: 0.75rem; font-weight: 600;
}
.up-wallet-pill--credit { background: rgba(40,199,111,.14); color: #1f7a4d; }
.up-wallet-pill--debit  { background: rgba(234,84,85,.14);  color: #b5302e; }
.up-wallet__amount-cell--credit { color: #1f7a4d; font-weight: 700; }
.up-wallet__amount-cell--debit  { color: #b5302e; font-weight: 700; }

/* Dark-mode adjustments */
[data-theme*='dark'] .up-complaint,
.dark-style .up-complaint,
[data-theme*='dark'] .up-contact,
.dark-style .up-contact {
    background: rgba(var(--color-brand-primary-rgb), 0.10);
    border-color: rgba(var(--color-brand-primary-rgb), 0.22);
}
[data-theme*='dark'] .up-wallet__hero,
.dark-style .up-wallet__hero {
    background:
        radial-gradient(ellipse 60% 80% at 0% 0%,
            rgba(var(--color-brand-secondary-rgb), 0.18) 0%, transparent 60%),
        linear-gradient(135deg,
            rgba(var(--color-brand-primary-rgb), 0.22) 0%,
            rgba(30, 26, 64, 0.85) 100%);
}
```

---

## Part 6 · Translations

Add to both `lang/ar/admin/main.php` and `lang/en/admin/main.php`:

```php
// Tabs
'complaints'                      => 'Complaints' / 'الشكاوى',
'contact_messages'                => 'Contact Messages' / 'رسائل التواصل',
'wallet'                          => 'Wallet' / 'المحفظة',

// Complaints/contact common
'view_details'                    => 'View details' / 'عرض التفاصيل',
'no_subject'                      => '(No subject)' / '(بدون موضوع)',
'no_complaints_for_user'          => 'This user has no complaints yet' / 'لا يوجد شكاوى لهذا المستخدم',
'no_contact_messages_for_user'    => 'This user has not sent any contact messages' / 'لم يرسل المستخدم أي رسائل تواصل',
'complaint_status_pending'        => 'Pending' / 'قيد المعالجة',
'complaint_status_in_progress'    => 'In progress' / 'جاري التنفيذ',
'complaint_status_resolved'       => 'Resolved' / 'تم الحل',
'complaint_status_rejected'       => 'Rejected' / 'مرفوضة',

// Wallet
'current_balance'                 => 'Current balance' / 'الرصيد الحالي',
'add_credit'                      => 'Add credit' / 'إضافة رصيد',
'add_credit_hint'                 => 'Add funds to this user\'s wallet' / 'أضف رصيداً لمحفظة المستخدم',
'debit'                           => 'Debit' / 'خصم',
'debit_hint'                      => 'Deduct funds from this user\'s wallet' / 'خصم رصيد من محفظة المستخدم',
'transaction_history'             => 'Transaction history' / 'سجل المعاملات',
'no_wallet_transactions'          => 'No wallet transactions yet' / 'لا توجد معاملات للمحفظة',
'amount'                          => 'Amount' / 'المبلغ',
'balance_after'                   => 'Balance after' / 'الرصيد بعد',
'reason'                          => 'Reason' / 'السبب',
'note'                            => 'Note' / 'ملاحظة',
'by_admin'                        => 'By admin' / 'بواسطة',
'tx_type_credit'                  => 'Credit' / 'إيداع',
'tx_type_debit'                   => 'Debit' / 'خصم',
'wallet_credit_success'           => 'Credit added successfully' / 'تم إضافة الرصيد بنجاح',
'wallet_debit_success'            => 'Debit processed successfully' / 'تم الخصم بنجاح',
'wallet_insufficient_balance'     => 'Insufficient wallet balance' / 'رصيد المحفظة غير كافٍ',
'wallet_amount_must_be_positive'  => 'Amount must be greater than zero' / 'يجب أن يكون المبلغ أكبر من صفر',
'date'                            => 'Date' / 'التاريخ',
'type'                            => 'Type' / 'النوع',
```

---

## Part 7 · Implementation phases

| Phase | What                                                                | Verify                                |
| ----- | ------------------------------------------------------------------- | ------------------------------------- |
| **1** | Tab CSS resize in `user-crud.css`                                   | Tab strip looks bigger                |
| **2** | Add Complaints tab (controller query + partial + tab wire)          | New tab appears with count badge      |
| **3** | Add Contact tab (same pattern)                                      | New tab appears                       |
| **4** | Wallet migration + model + service                                  | `php artisan migrate` succeeds        |
| **5** | Wallet controller + routes                                          | `php artisan route:list` shows the 2 wallet endpoints |
| **6** | Wallet partial + 2 modal components                                 | Tab renders with balance + modals open|
| **7** | Translations                                                        | Both ar + en pages have correct labels|
| **8** | Wallet CSS                                                          | Wallet hero + transaction table styled|
| **9** | QA: credit + debit transactions log correctly, balance updates live | Manual run                            |

Ship phases 1–3 in one PR (tabs done); phases 4–8 in a second PR (wallet —
it's bigger and touches schema). Phase 9 is QA, no commit.

---

## Part 8 · Acceptance criteria

### Tabs
- Tab strip total height ~3rem (up from ~2.2rem).
- Active tab uses brand-gradient background + inner border + drop shadow.
- Each tab shows count badge if its dataset has > 0 rows.
- Switching brand color recolors all tab states live.
- On viewport < 576px, tabs become a horizontal scroll (no wrapping).

### Complaints tab
- Empty state with friendly icon + message when user has no complaints.
- Otherwise: list of up to 20 complaints, latest first.
- Each card shows subject, body (truncated 220 chars), status pill,
  timestamp, "View details" link if `admin.complaints.show` route exists.
- Filtering correctly by `email` OR `phone` (verified in tinker).

### Contact tab
- Same as complaints: empty state, list of latest 20.

### Wallet tab
- Big balance number with brand gradient text-fill.
- "Add credit" button (brand-primary) opens credit modal.
- "Debit" button (warning-yellow) opens debit modal.
- Both modals validate amount > 0 and ≤ 1,000,000.
- Debit returns 422 + message if insufficient balance — toast shows.
- Successful transaction reloads the page; balance + table reflect the change.
- Transaction table shows date, type pill (green=credit / red=debit),
  signed amount, balance after, reason, admin name.
- Wallet auto-created on first transaction (no manual setup needed).

### General
- All texts go through `__('admin/main.…')`.
- No regression on existing 5 tabs.
- Dark + light + RTL all work.

---

## Part 9 · Out of scope

- **Wallet for admins** — only users. Admin self-wallet is a separate
  concept and not requested.
- **Currency conversion** — single currency (SAR by default). Multi-
  currency support is a separate plan.
- **Wallet export / CSV** — out of scope; the existing CRUD export
  toolbar isn't extended to wallet transactions yet.
- **Reversing a transaction** — admin can credit/debit anew, but no
  "undo" button on a specific row. Audit-clean approach.
- **Notifications** — no email/push to the user when credited/debited.
  Add later via the existing notification system.
- **Wallet limits / KYC gates** — no per-user max balance. Out of scope.

---

## Notes for the implementing agent

1. **Schema-first.** Run migrations before writing the partial. Models
   should reference real columns.

2. **The Wallet has UNIQUE on user_id** — so `getOrCreate()` cannot race
   with itself for the same user. If you ever do parallel writes, wrap
   in `DB::transaction()` (the service already does).

3. **`auth('admin')->id()`** is the admin guard, not the user guard. The
   service assumes that's set; if a wallet transaction is ever triggered
   from a non-admin context, pass an explicit `?int $adminId` parameter.

4. **JS hooks must remain.** The credit/debit buttons use
   `data-bs-toggle="modal"` — both modals use the same `validated-form`
   class so `submit-form.js` handles them with AJAX. On success the
   handler reloads (current behavior) — adequate for v1.

5. **Tab order matters.** Place new tabs **between Sessions and Security**
   so the danger zone stays last.

6. **Don't add a "delete" action on wallet transactions in the UI.**
   Audit trail must be append-only. If a wrong transaction was logged,
   the fix is the reverse transaction with a clear `reason`.

7. **Balance is `decimal(12, 2)`** — handles up to 9,999,999,999.99.
   Don't store as float / int.

8. **PowerShell + PHP files = BOM danger.** Use
   `New-Object System.Text.UTF8Encoding $false` if editing PHP via
   PowerShell.
