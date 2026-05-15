# Testing Skill

## Purpose

Write tests that verify functionality, protect against regressions, and ensure code quality.

---

## When to Use

- Adding tests for new features
- Adding tests for bug fixes
- Improving test coverage
- Writing tests for existing code that lacks coverage

---

## Test Structure

Tests live in `tests/` directory:

```
tests/
├── Feature/           # HTTP-level tests
│   ├── Api/          # API endpoint tests
│   └── Admin/        # Admin panel tests
├── Unit/             # Unit tests for services, classes
└── TestCase.php      # Base test class
```

---

## Test Types

### Feature Tests (HTTP Level)
- Test API endpoints
- Test HTTP responses
- Test request/response flow

### Unit Tests
- Test Service classes
- Test business logic
- Test validation logic
- Test model methods

---

## Project Testing Patterns

### TestCase Base
```php
use Tests\TestCase;

class MyTest extends TestCase
{
    // Automatically uses RefreshDatabase
}
```

### Database Seeding in Tests
```php
protected function setUp(): void
{
    parent::setUp();
    $this->seed(\Database\Seeders\Country\CountrySeeder::class);
}
```

### Model Factories
```php
Admin::factory()->create([...]);
// Or with specific attributes
Admin::factory()->create([
    'email' => 'admin@test.com',
    'password' => bcrypt('Password123!'),
]);
```

---

## What to Test

### Critical Coverage - Every API Must Have:
| Test Case | Description |
|-----------|-------------|
| Success | Valid request returns expected response |
| Validation Failure | Invalid input returns proper errors |
| Unauthorized | No auth returns 401 |
| Forbidden | Wrong permission returns 403 |
| Not Found | Invalid ID returns 404 |

### Service Layer Tests
- Test business logic
- Test state transitions
- Test edge cases

### Validation Tests
- Test Form Request rules
- Test required fields
- Test format validation
- Test max/min limits

---

## Writing Tests

### API Test Example
```php
public function test_admin_login_fails_with_invalid_credentials(): void
{
    Admin::factory()->create([
        'email' => 'admin@test.com',
        'password' => bcrypt('Password123!'),
    ]);

    $response = $this->postJson('/admin/login', [
        'email' => 'admin@test.com',
        'password' => 'WrongPass123!',
    ]);

    $response->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors',
        ]);
}
```

### Service Test Example
```php
public function test_service_throws_on_invalid_data(): void
{
    $this->expectException(ServiceException::class);

    $service = new OtpService();
    $service->send('');
}
```

---

## Testing Rules

- **Keep tests focused** - one test per behavior
- **Keep tests deterministic** - no random data, no timing dependencies
- **Test both success and failure** - cover the happy path and error paths
- **Use factories** - create test data via factories
- **Use meaningful names** - test names describe what they verify

---

## Common Test Assertions

### Response Assertions
```php
$response->assertStatus(200);
$response->assertJsonStructure(['message', 'data']);
$response->assertJsonFragment(['id' => 1]);
$response->assertRedirect('/expected-route');
```

### Database Assertions
```php
$this->assertDatabaseHas('admins', ['email' => 'test@test.com']);
$this->assertDatabaseMissing('admins', ['email' => 'deleted@test.com']);
```

### Validation Assertions
```php
$response->assertStatus(422);
$response->assertJsonValidationErrors(['email']);
```

---

## Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/Api/Auth/LoginTest.php

# Run tests matching pattern
php artisan test --filter=LoginTest
```

---

## Completion Standard

A test is NOT complete until:

- [ ] Tests success case
- [ ] Tests validation failures
- [ ] Tests unauthorized/forbidden access
- [ ] Tests not found scenarios
- [ ] Tests are deterministic (repeatable)
- [ ] Tests use project conventions and patterns
- [ ] Tests pass

---

## Output Format

- Test file location
- Test cases covered
- Test approach (Feature vs Unit)
- Coverage areas
- Any setup requirements