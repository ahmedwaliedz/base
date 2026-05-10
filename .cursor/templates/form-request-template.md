# Form Request Template

## Purpose

All non-trivial HTTP validation belongs in **Form Request** classes — not in controllers.

## API requests

- Namespace: **`App\Http\Requests\Api\<Module>`** (e.g. `App\Http\Requests\Api\Auth`).
- Extend **`App\Http\Requests\Api\BaseApiRequest`** unless you have a narrower intermediate base.
- `BaseApiRequest` provides **`prepareForValidation`**, common normalization hooks, and API-oriented validation handling — override **`normalizeInputs()`** when the module needs extra merging/sanitization.
- Use **`__('api/...')`** (or existing lang keys) in `messages()` and `attributes()`.

### Structure (API)

```php
<?php

namespace App\Http\Requests\Api\<Module>;

use App\Http\Requests\Api\BaseApiRequest;

class <Action>Request extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            // 'field' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            // 'field.required' => __('api/<module>.field_required'),
        ];
    }
}
```

---

## Admin requests

- Namespace: **`App\Http\Requests\Admin\<Entity>`** with action names like **`StoreRequest`**, **`UpdateRequest`** (see `App\Http\Requests\Admin\User\StoreRequest`).
- Extend **`App\Http\Requests\Admin\BaseAdminRequest`**.
- Use **`prepareForValidation`** to merge defaults / booleans as in existing admin requests.
- Use **`Illuminate\Validation\Rules\Password`** (or project rules) for passwords.

### Structure (Admin)

```php
<?php

namespace App\Http\Requests\Admin\<Entity>;

use App\Http\Requests\Admin\BaseAdminRequest;

class StoreRequest extends BaseAdminRequest
{
    public function prepareForValidation(): void
    {
        $this->merge([
            // 'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function rules(): array
    {
        return [
            // ...
        ];
    }
}
```

---

## Authorization

- Default **`authorize(): bool`** may return `true` only when routes/middleware already restrict access.
- Prefer **policies** or explicit checks for sensitive operations; keep rules in one place per request class.

## Project references

- `app/Http/Requests/Api/BaseApiRequest.php`
- `app/Http/Requests/Admin/BaseAdminRequest.php`
- Examples: `app/Http/Requests/Api/Auth/PasswordLoginRequest.php`, `app/Http/Requests/Admin/User/StoreRequest.php`
