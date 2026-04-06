# Test Template

## Purpose

Use when adding PHPUnit tests for API endpoints, validation, services, or admin flows in this Laravel 11 app.

## Conventions in this repo

- **Feature tests:** `tests/Feature/<Area>/<Name>Test.php` (e.g. `tests/Feature/Auth/PasswordLoginTest.php`).
- **Unit tests:** `tests/Unit/...` for isolated service or domain logic.
- Base class: `Tests\TestCase`.
- Prefer `declare(strict_types=1);` when touching new test classes, matching existing auth tests.
- Use **`RefreshDatabase`** when tests hit the database.
- API calls: **`$this->postJson('/api/v1/...', [...])`** (prefix matches `bootstrap/app.php` routing). Assert JSON shape consistent with traits in `app/Traits/Response/` (e.g. `status`, `message`, `data`).
- **Admin / session:** use `actingAs` with the appropriate guard if you add web feature tests.

## Rules

- Cover success and representative failure cases (validation, auth, not found).
- Keep tests deterministic; avoid time/network/random coupling without fixing seeds or fakes.
- Prefer factories (`database/factories`) for models already factory-enabled.
- Do not assert on full exception messages from third parties; assert status codes and stable JSON keys.

## Example skeleton

```php
<?php

declare(strict_types=1);

namespace Tests\Feature\<Area>;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class <FeatureName>Test extends TestCase
{
    use RefreshDatabase;

    public function test_success_case(): void
    {
        // Arrange

        // Act
        // $response = $this->postJson('/api/v1/<path>', [...]);

        // Assert
        // $response->assertStatus(200)->assertJson([...]);
    }

    public function test_validation_or_error_case(): void
    {
        // Arrange

        // Act

        // Assert
    }
}
```

## Checklist

- [ ] Right URL prefix (`/api/v1/...` or admin route as appropriate)
- [ ] Database state isolated (`RefreshDatabase` or transactions)
- [ ] Assertions match actual response envelope (`status` / `message` / `data`)
