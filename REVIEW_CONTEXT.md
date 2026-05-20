# Review Context

This file is the compact source of truth for code-review sessions in this repo.
Use it instead of re-reading the full `.cursor` tree unless the task touches a new area.

## Review Mode

- Focus on code review, bug finding, and fix prompts.
- Keep feedback strict, clear, and actionable.
- Prefer minimal changes that preserve the existing Laravel architecture.
- Do not introduce new patterns unless the repo already uses them or they are clearly required.

## Always Use

1. `REVIEW_CONTEXT.md` (this file)
2. Current git diff / changed files
3. Related tests when available

## Review Priorities

1. Security and data integrity
2. Runtime errors and broken routes
3. Architecture compliance
4. Performance and N+1 issues
5. Validation and tests
6. Documentation and RBAC alignment

## Laravel Review Rules

- Controllers should stay thin.
- Validation belongs in Form Requests.
- Business logic belongs in Services.
- Blade should stay presentation-only.
- Reuse existing base CRUD patterns.
- Check RBAC before adding or changing admin routes.
- Watch for N+1 queries in list pages and API responses.
- Ensure tests cover happy path, validation failures, and critical edge cases.

## Open Only If Needed - Routing Table

Open these `.cursor` files only when the changed area matches the routing rule:

| Changed Area | Open `.cursor` File |
|--------------|---------------------|
| `resources/views/**`, `app/View/Components/**`, `resources/js/**`, `resources/css/**`, `public/style/admin/**` | `rules/03-frontend-rules.mdc` |
| `routes/admin.php`, sidebar/menu files, role/permission code, admin route names, permission checks | `rules/08-custom-rbac.mdc` |
| `app/Models/**`, `database/migrations/**`, seeders, factories, relationships, query-heavy services | `rules/05-database-rules.mdc` + `rules/12-database-eloquent.mdc` |
| Forms, Form Requests, uploads, auth, admin/user actions, permissions, user input | `rules/18-security.mdc` |
| List pages, dashboards, counts, filters, reports, loops, notifications, eager loading, heavy queries | `rules/19-performance.mdc` |
| `tests/**`, missing tests, changed behavior without tests | `rules/16-testing-qa.mdc` |
| Full PR / deep audit only | `rules/22-code-review.mdc` |
| Implementation workflow / process review only | `workflows/development-workflow.md` or `workflows/hotfix-workflow.md` |

## Routing Rule

**If a changed area is already covered by this file, do not open extra `.cursor` files.**

## Prompt Style For Fixes

When review finds a problem, give a strict fix prompt in this format:

```text
Fix [problem] in [file(s)].

Constraints:
- Preserve existing architecture.
- Make the smallest safe change.
- Do not touch unrelated files.
- Keep validation in Form Requests and business logic in Services.
- Add or update tests if behavior changes.

Expected result:
- [clear success condition]
```

## Working Rule For Future Sessions

- Use this file first.
- Only open deeper `.cursor` files when the changed area matches the routing table above.
- If the task is localized, review only the impacted files plus the rules that apply to them.