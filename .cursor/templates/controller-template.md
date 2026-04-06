# Controller Template

## Purpose

Thin controllers only: validate via Form Requests, call services, return responses. **No business rules** in the controller.

---

## API (`App\Http\Controllers\Api\V1`)

### Rules

- Extend **`App\Http\Controllers\Controller`**.
- Inject services via **constructor** (see `CountriesController`).
- Use **`SuccessResponseTrait`**, **`FailResponseTrait`**, and **`PaginationTrait`** when needed (`app/Traits/Response/`).
- Return JSON via **`respondWithSuccess` / `respondWithFail`**; shape **`data`** using **`JsonResource`** classes under `app/Http/Resources/Api/V1/`.
- Use **try/catch** only where the project already does for that area; prefer letting the framework handle validation and auth exceptions where `bootstrap/app.php` maps them for `api/*`.

### Structure (illustrative)

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\<Module>\<Action>Request;
use App\Http\Resources\Api\V1\<Entity>Resource;
use App\Services\<Area>\<Entity>Service;
use App\Traits\Response\FailResponseTrait;
use App\Traits\Response\SuccessResponseTrait;
use Illuminate\Http\JsonResponse;

class <Entity>Controller extends Controller
{
    use SuccessResponseTrait;
    use FailResponseTrait;

    public function __construct(
        protected <Entity>Service $<entity>Service
    ) {}

    public function store(<Action>Request $request): JsonResponse
    {
        $model = $this-><entity>Service->store($request->validated());

        return $this->respondWithSuccess(
            __('api/<module>.created'),
            (new <Entity>Resource($model))->resolve()
        );
    }
}
```

Adjust method names, guards, and try/catch to match neighboring controllers in the same module.

---

## Admin (`App\Http\Controllers\Admin`)

### Rules

- Extend **`AuthenticatableBaseController`** or **`AdminBaseController`** as siblings do (e.g. `UserController` + `UserService`).
- Pass the **service** into **`parent::__construct($service)`** when using the base CRUD pattern.
- Custom actions on the base can follow patterns in `AuthenticatableBaseController` (`switchBlock`, success/fail responses).

### Structure (illustrative)

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\<Entity>Service;

class <Entity>Controller extends AuthenticatableBaseController
{
    public function __construct(<Entity>Service $service)
    {
        parent::__construct($service);
    }
}
```

Add methods only when they are not already provided by the shared admin base; override or extend intentionally.

---

## References

- Admin bases: `app/Http/Controllers/Admin/AdminBaseController.php`, `AuthenticatableBaseController.php`
- API example: `app/Http/Controllers/Api/V1/CountriesController.php`
- Project layout: `.cursor/context/project-context.md`
