# Laravel / PHPUnit 11 Test Conventions

This reference documents the project's active test stack and conventions.

## Stack

- Laravel 11
- PHPUnit 11
- PHP 8.2-compatible syntax
- Mockery 1.6.x via `mockery/mockery`
- Laravel collision package for test output

Pest is not installed. Do not use Pest syntax.

## Test structure

- Feature tests live in `tests/Feature`.
- Unit tests live in `tests/Unit`.
- Tests extend `Tests\TestCase` for HTTP/feature tests or `PHPUnit\Framework\TestCase` for pure unit tests.
- Use `RefreshDatabase` when the test touches the database.
- Use factories for model setup.

## Feature-test focus

Prefer feature tests for:

- HTTP endpoints
- Routing and middleware
- Authentication and authorization
- Validation behavior
- Database persistence
- Full request/response behavior

## Unit-test focus

Prefer focused unit tests for:

- Pure domain calculations
- Service logic with no Laravel HTTP coupling
- Value objects and helpers

## Fakes and mocks

- Use Laravel fakes for framework abstractions: `Mail::fake()`, `Notification::fake()`, `Queue::fake()`, `Event::fake()`, `Storage::fake()`, `Http::fake()`.
- Mock genuine boundaries: external APIs, payment gateways, SMS providers.
- Mock internal collaborators only when the test intentionally isolates a unit and still verifies observable behavior.

## Assertions and helpers

- Use PHPUnit 11 assertions (`assertOk`, `assertForbidden`, `assertNotFound`, `assertJson`, `assertDatabaseHas`, etc.).
- Use Laravel test helpers (`actingAs`, `postJson`, `getJson`, etc.).
- Use descriptive scenario names such as `test_guests_cannot_delete_admins` instead of `test_delete_1`.

## Running tests

- Run targeted tests: `php artisan test --filter=ClassName`
- Run the full suite: `php artisan test`
- Fix failures before declaring work complete.
