# Code Writing Context

This file is the compact source of truth for implementation sessions in this repo.
Use it instead of re-reading the full `.cursor` tree unless the task touches a routed area below.

## Writing Mode

- Implement code from an approved plan, checklist item, bug report, or clearly scoped request.
- Preserve the existing Laravel architecture and admin dashboard patterns.
- Make the smallest safe change that fully solves the requested item.
- Do not introduce new patterns unless the repo already uses them or they are clearly required.
- Do not touch unrelated files.
- If a change involves RBAC permissions, roles, or admin access control and the intended permission behavior is unclear, stop and ask.

## Always Use

1. `CODE_WRITING_CONTEXT.md` (this file)
2. The active plan/checklist file when provided
3. The specific files related to the requested item
4. Existing nearby implementation patterns
5. Related tests when available

## Implementation Priorities

1. Security and data integrity
2. Runtime correctness and route/page stability
3. Architecture compliance
4. Performance and N+1 prevention
5. Validation and error handling
6. Tests and regression safety
7. UI consistency, translations, and RTL/LTR behavior

## Laravel Implementation Rules

- Controllers stay thin and only coordinate request flow.
- Validation belongs in Form Requests for non-trivial writes.
- Business logic belongs in Services.
- Blade stays presentation-only.
- Do not query the database or call services directly from Blade.
- Reuse existing services, traits, Blade components, base controllers, and CRUD patterns before creating new ones.
- Use existing admin route conventions in `routes/admin.php` with the `admin.` name prefix.
- Route names must align with the custom RBAC permission string convention.
- Use Eloquent relationships, scopes, eager loading, and pagination where appropriate.
- Add or update tests when behavior changes, a route is added, or a regression is fixed.

## Work From A Plan

- Execute one checklist item or one small related group at a time.
- Before editing, identify the affected layers: route, controller, request, service, model, migration, view, translation, test.
- Mark checklist items complete only after implementation and verification.
- If the plan conflicts with current code, follow current code and update the plan or ask before risky changes.
- Keep old behavior untouched unless the plan explicitly says to change it.

## Open Only If Needed - Routing Table

Open these `.cursor` files only when the task area matches the routing rule:

| Task Area | Open `.cursor` File |
|-----------|---------------------|
| New feature, feature extension, or multi-layer implementation | `workflows/development-workflow.md` |
| Bug fix, broken page, runtime error, or urgent regression | `workflows/hotfix-workflow.md` + `skills/development-phase/bug-fixing.md` |
| Planning affected layers before implementation | `skills/development-phase/feature-analysis.md` |
| Backend logic, services, controllers, Form Requests, admin actions | `rules/02-architecture.mdc` + `rules/04-backend-rules.mdc` + `skills/development-phase/backend-feature-implementation.md` |
| Blade pages, admin UI, view components, RTL/LTR, admin assets | `rules/03-frontend-rules.mdc` + `skills/development-phase/ui-page-build.md` |
| `routes/admin.php`, sidebar/menu files, role/permission code, admin route names, permission checks | `rules/08-custom-rbac.mdc` |
| Models, migrations, seeders, factories, relationships, query-heavy services | `rules/05-database-rules.mdc` + `rules/12-database-eloquent.mdc` |
| Forms, uploads, auth, admin/user actions, permissions, user input, secrets | `rules/18-security.mdc` |
| List pages, dashboards, counts, filters, reports, loops, notifications, eager loading, heavy queries | `rules/19-performance.mdc` |
| API endpoints, API resources, response shape, Postman/docs | `rules/07-api-postman-mcp-documentation-rules.mdc` + `rules/13-api-integration.mdc` |
| Tests, missing tests, changed behavior without tests, regression coverage | `rules/16-testing-qa.mdc` + `skills/development-phase/testing.md` |
| Refactor-only request | `skills/development-phase/refactor.md` |
| Final validation before delivery | `rules/22-code-review.mdc` + `skills/development-phase/feature-finalization-and-validation.md` |

## Routing Rule

If the task is already covered by this file and nearby code patterns, do not open extra `.cursor` files.

## Safety Rules

- Never replace the custom RBAC system or change permission tables/logic without explicit confirmation.
- Never hide exceptions with broad `try/catch` unless the error handling is intentional and user-facing.
- Never fix symptoms when the root cause can be identified.
- Never add database columns without a migration and a data integrity reason.
- Never store calculated values unless performance, audit, or product requirements justify it.
- Never introduce duplicate components, services, or helpers when an existing pattern fits.
- Never mark work complete without at least one verification step.

## Verification Standard

At the end of implementation, report:

- Files changed
- What behavior changed
- Verification performed
- Tests added or updated
- Any tests not run and why
- Any remaining risk or follow-up

## Working Rule For Future Sessions

- Use this file first.
- Open deeper `.cursor` files only when the task area matches the routing table above.
- Keep implementation scoped to the requested plan item.
- Prefer root-cause fixes and project-native patterns over broad rewrites.
