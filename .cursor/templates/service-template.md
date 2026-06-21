# Service Template

## Purpose

Place **business logic** and orchestration here — not in controllers. Services in this project are plain PHP classes (or admin CRUD bases) invoked from controllers.

## Types of services in this repo

| Kind | When to use | Typical location |
|------|-------------|------------------|
| **Domain / API service** | Coordinate repositories, models, queries for API or shared use | `app/Services/<Name>Service.php` (e.g. `Countries\CountryService`) |
| **Admin CRUD** | Standard admin index/create/store/edit/update/delete for an Eloquent model | Extend or use patterns from `app/Services/Admin/Base/CrudBaseService.php` via `AdminBaseController` + thin `*Controller` |
| **Auth / OTP / etc.** | Vertical slices | `app/Services/Auth`, `app/Services/Otp`, … |

## Rules

- Accept **validated arrays** (`$request->validated()`), **IDs**, or **DTOs** — avoid passing the full `Request` into deep domain logic unless you need it for pagination/filters (as existing `index` methods do).
- Use **`DB::transaction`** for multi-step writes that must stay consistent (see `CrudBaseService::store`).
- Inject dependencies via the **constructor**; bind interfaces in a service provider when the project uses `app/Contracts/`.
- For translatable models, follow Astrotomic patterns already on models under `app/Models/`.
- For file uploads, prefer **Spatie Media Library** and traits under `app/Traits/Upload/` instead of ad-hoc storage logic.

---

## Example: API-oriented service (illustrative)

```php
<?php

namespace App\Services\<Area>;

use App\Models\<Entity>;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class <Entity>Service
{
    public function list(Request $request): mixed
    {
        $query = <Entity>::query()
            ->when($request->input('filter'), fn ($q, $filter) => $q->where('name', 'like', "%{$filter}%"));

        return $query->paginate((int) $request->input('per_page', 15));
    }

    public function store(array $data): <Entity>
    {
        return DB::transaction(fn () => <Entity>::create($data));
    }
}
```

Align return types (`paginator`, model, collection) with what the controller wraps in **JsonResource** / `respondWithSuccess`.

---

## Example: wiring from an admin CRUD controller

Admin resources often use **`UserController`-style** thin controllers: constructor receives a service extending or delegating to **`CrudBaseService`** (see `App\Services\Admin\UserService` and `AuthenticatableBaseController`).

Do **not** duplicate CRUD logic in the controller; add methods on the service or a dedicated action class.

---

## Checklist

- [ ] Service name and namespace match sibling features
- [ ] No HTTP-specific details (status codes) — those stay in the controller + response traits
- [ ] Transactions for multi-step persistence
- [ ] Uses existing models, repositories, and upload/translation patterns

## References

- `app/Services/Countries/CountryService.php`
- `app/Services/Admin/Base/CrudBaseService.php`
- [`../context/project-context.md`](../context/project-context.md)
