# API Endpoint Template

## Purpose

Use when adding a new **API v1** endpoint so routing, validation, service, controller, and responses stay consistent with this project.

## Routing (this project)

- Routes live in **`routes/api/v1/<topic>.php`** (e.g. `countries.php`, `auth.php`).
- Laravel loads every `routes/api/v{version}/*.php` under prefix **`api/{version}`** with middleware alias **`api.lang`** (`ApiLangMiddleware`) — see `bootstrap/app.php`.
- Full URL pattern: **`/api/v1/<segments>`**.
- Use `Route::prefix(...)->group(...)` inside the file when grouping related URLs.

### Route file example

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\<Entity>Controller;

Route::prefix('<resource>')->group(function () {
    Route::get('/', [<Entity>Controller::class, 'index']);
    // Route::post('/', [<Entity>Controller::class, 'store'])->middleware('auth:sanctum');
});
```

## Required layers (typical)

| Layer | Location |
|--------|----------|
| Route | `routes/api/v1/<name>.php` |
| Form request | `app/Http/Requests/Api/<Module>/<Action>Request.php` — extend **`BaseApiRequest`** when applicable |
| Controller | `app/Http/Controllers/Api/V1/<Entity>Controller.php` |
| Service / repository | `app/Services/...`, `app/Repositories/...` as needed |
| Resource / collection | `app/Http/Resources/Api/V1/<Entity>Resource.php` |

## Controller response pattern

- Use **`SuccessResponseTrait`**, **`FailResponseTrait`**, and when listing paginated data **`PaginationTrait`** from `app/Traits/Response/`.
- Prefer **`$this->respondWithSuccess($message, $data)`** and **`$this->respondWithFail($message, $data, $code)`** — see `CountriesController` for examples.
- Wrap resource output with **`JsonResource` / `Resource::collection`** and pass arrays or structured data as already done in existing controllers.

## Auth

- Protect routes with **`auth:sanctum`** (or project-standard middleware) when the endpoint requires an authenticated user.
- Do not skip `api.lang` or auth middleware without an explicit decision.

## Build checklist

- [ ] Purpose and HTTP method defined
- [ ] New route file registered (no manual `bootstrap` edit; new file under `routes/api/v1/` is picked up automatically)
- [ ] Form Request with `rules()`; extend **`App\Http\Requests\Api\BaseApiRequest`** for API endpoints that need shared normalization / API validation behavior
- [ ] Thin controller: delegate to service
- [ ] Responses use shared traits + JsonResource where appropriate
- [ ] Translatable copy via `__('...')` / `lang/*` where user-facing strings are returned
- [ ] Feature test under `tests/Feature/...` for happy path and one failure path
- [ ] Postman / OpenAPI only if the team requires it for this change
