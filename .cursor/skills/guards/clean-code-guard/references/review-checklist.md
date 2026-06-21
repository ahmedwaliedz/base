# Clean Code Guard — Review Checklist

Use this checklist when reviewing changed production code.

## Correctness

- [ ] The code does what the requirement describes.
- [ ] Edge cases are handled explicitly rather than silently ignored.
- [ ] No fabricated success responses or swallowed errors.
- [ ] Exceptions are thrown or logged appropriately; error messages are useful.
- [ ] Boolean/toggle edit-state behavior matches the project convention.
- [ ] No mock or fixture behavior leaks into production code.

## Names and clarity

- [ ] Names reveal intent (variables, methods, classes, routes, config keys).
- [ ] Method names describe behavior, not implementation mechanics.
- [ ] Avoid abbreviations that are not project-standard.

## Focus and size

- [ ] Functions and methods do one thing.
- [ ] Size, complexity, and parameter count are treated as signals, not hard limits.
- [ ] Laravel constructor injection is permitted when dependencies are cohesive.
- [ ] Prefer DTOs or Form Requests when a parameter group represents one concept.

## Architecture

- [ ] Controllers remain thin; business logic lives in services.
- [ ] Form Requests handle HTTP validation.
- [ ] Services handle business rules and persistence coordination.
- [ ] Blade contains no database queries or business logic.
- [ ] Eager loading is used to avoid N+1 queries.
- [ ] Mass-assignment protection is respected (`$fillable` / `$guarded`).
- [ ] New abstractions are justified by current need, not speculation.

## Laravel project patterns

- [ ] Code matches neighboring project implementations.
- [ ] Uses existing base classes (`CrudBaseService`, `BaseApiRequest`, `BaseAdminRequest`, etc.) when applicable.
- [ ] Uses `$request->validated()` safely and does not re-validate in controllers.
- [ ] Uses existing response traits/resources for API responses.
- [ ] Respects custom RBAC: route name equals permission string; `AdminType::SUPER_ADMIN` bypasses checks.
- [ ] Does not invent packages, helper functions, or config keys.

## Security

- [ ] Authorization is checked explicitly (policy, gate, middleware, or RBAC).
- [ ] User input is validated before use.
- [ ] No hardcoded secrets or credentials.
- [ ] File uploads validate mime type and extension; no executable uploads.
- [ ] Output is escaped in Blade; no raw user data in HTML/JS attributes.

## Performance

- [ ] Database queries are scoped and eager-loaded.
- [ ] Indexes exist for queried foreign keys and search columns.
- [ ] No obvious N+1 or large unbounded queries.
- [ ] Jobs/queues are used for heavy or external work when the project already uses queues.

## AI-specific failure modes

- [ ] No hallucinated packages, APIs, or Laravel features.
- [ ] No code copied from similar-looking files without checking semantics.
- [ ] No dead code or commented-out blocks left behind.
- [ ] No speculative configuration options.
- [ ] No unnecessary interfaces or abstract classes.
- [ ] No framework-guarantee tests asserted as business logic.

## Refactoring discipline

- [ ] Refactors preserve behavior.
- [ ] Changes are small and focused.
- [ ] Tests still pass or are updated with the refactor.
- [ ] No unrelated code is changed.
