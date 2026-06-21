---
name: test-guard
description: Review tests after they are written or changed, and guide test writing when explicitly invoked. Trigger when test files are added or modified, when test quality is in question, or during code review of test code.
---

# test-guard

Review tests for quality, correctness, and alignment with the project's Laravel 11 / PHPUnit 11 stack. Prevent brittle, duplicated, misleading, mock-heavy, or framework-only tests.

## When to use

- After writing or changing tests.
- During code review when test files are in scope.
- When the user explicitly asks for test guidance.

## Operating modes

- **Guard-pass mode** — Run automatically after authorized test changes. Inspect changed test files, report findings with severity, and fix confirmed critical/high findings that are in scope. Rerun the guard after fixes. Repeat up to two correction cycles; if a finding persists or needs expanded authority, report it to the user. Do not fix unrelated pre-existing issues.
- **Live mode** — Run continuously while tests are being written, then perform a final guard pass before delivery.
- **Review-only mode** — Run on request. Produce findings only; never edit code unless the user explicitly authorizes fixes.

## Current stack

- Laravel 11
- PHP 8.2 (production compatibility)
- PHPUnit 11

Pest is **not** installed. Do not generate Pest syntax.

## Mandatory baseline

Load these before every test review:

- [`../../../context/technology-baseline.md`](../../../context/technology-baseline.md)
- [`../../../rules/16-testing-qa.mdc`](../../../rules/16-testing-qa.mdc)
- [`references/laravel-phpunit.md`](references/laravel-phpunit.md)

## Conditional rule routing

Load additional rules and skills based on what the tests cover:

| Tested behavior | Additional rules / skills |
|---|---|
| Formal review requiring evidence/reporting standards | [`../../../rules/22-code-review.mdc`](../../../rules/22-code-review.mdc) |
| Test design, boundaries, or architecture concerns | [`../../../rules/02-architecture.mdc`](../../../rules/02-architecture.mdc) |
| RBAC / permissions | [`../../../rules/08-custom-rbac.mdc`](../../../rules/08-custom-rbac.mdc), [`auth-permissions`](../../specialized/auth-permissions/SKILL.md) |
| API endpoints | [`../../../rules/07-api-postman-mcp-documentation-rules.mdc`](../../../rules/07-api-postman-mcp-documentation-rules.mdc), [`../../../rules/13-api-integration.mdc`](../../../rules/13-api-integration.mdc), [`create-api-with-postman`](../../development-phase/create-api-with-postman/SKILL.md) |
| Database / persistence / migrations | [`../../../rules/05-database-rules.mdc`](../../../rules/05-database-rules.mdc), [`../../../rules/12-database-eloquent.mdc`](../../../rules/12-database-eloquent.mdc), [`database-design`](../../development-phase/database-design/SKILL.md) |
| External integrations | [`integration`](../../specialized/integration/SKILL.md), [`../../../rules/13-api-integration.mdc`](../../../rules/13-api-integration.mdc), [`../../../rules/18-security.mdc`](../../../rules/18-security.mdc) |
| File uploads | [`file-upload`](../../specialized/file-upload/SKILL.md), [`../../../rules/18-security.mdc`](../../../rules/18-security.mdc) |
| General backend behavior | [`backend-feature-implementation`](../../development-phase/backend-feature-implementation/SKILL.md), [`../../../rules/04-backend-rules.mdc`](../../../rules/04-backend-rules.mdc) |
| Admin CRUD | [`admin-crud-orchestrator`](../../development-phase/admin-crud-orchestrator/SKILL.md) |

Always load the production-code skill that governs the behavior under test.

## Review workflow

1. Identify changed test files and the production code they claim to cover.
2. Read the production behavior under test.
3. Load the shared technology baseline from [`../../../context/technology-baseline.md`](../../../context/technology-baseline.md).
4. Load [`references/laravel-phpunit.md`](references/laravel-phpunit.md).
5. Evaluate tests against the checklist below.
6. Report findings by severity with exact file/line evidence.
7. If no findings remain, state so explicitly.

## Guard-pass correction loop

When this guard runs after implementation is authorized:

1. Report findings internally or in working notes.
2. Fix confirmed critical/high findings that are within the current task scope.
3. Fix medium findings only when safe, relevant, and within scope.
4. Do not fix unrelated pre-existing issues.
5. Rerun targeted tests or validation.
6. Rerun this guard.
7. Repeat steps 2–6 for up to two cycles.
8. Stop and report to the user when:
   - no unresolved critical/high findings remain, or
   - a finding requires user clarification or expanded authority.

Completion requires no unresolved critical/high findings and no known correctness, security, or data-integrity defect. Medium/low findings may be fixed, accepted with justification, or reported as residual risk.

## Test-quality checklist

- [ ] Tests are deterministic and repeatable.
- [ ] Feature tests cover HTTP, routing, middleware, authorization, validation, and persistence behavior.
- [ ] Unit tests cover isolated domain logic where valuable.
- [ ] Tests use real Eloquent models and factories where persistence matters.
- [ ] `RefreshDatabase` is used according to project conventions.
- [ ] Laravel fakes are used for external side effects (Mail, Notification, Queue, Event, Storage, HTTP) where appropriate.
- [ ] Genuine boundaries and expensive external integrations are mocked.
- [ ] Internal collaborators are mocked only when isolation is intentional and observable behavior is still verified.
- [ ] Tests do not assert irrelevant implementation details.
- [ ] Tests do not verify Laravel framework guarantees in isolation.
- [ ] Validation tests reflect project-specific rules.
- [ ] Regression tests for real defects are preserved.
- [ ] Data providers are used when scenarios differ only by input and expected output.
- [ ] Test names describe scenarios, not method names.
- [ ] No valid test is weakened, skipped, or deleted merely to make the suite pass.
- [ ] Every new test protects a meaningful behavior.

## What to avoid

- Pest syntax or structure.
- WordPress/WooCommerce test patterns.
- Universal prohibition against all internal mocks without considering scope.
- Mocking every internal service by default.
- Testing only that Laravel works.

## References

- [`references/laravel-phpunit.md`](references/laravel-phpunit.md) — Laravel and PHPUnit 11 conventions used by this project.
