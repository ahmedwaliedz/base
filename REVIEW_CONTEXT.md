# Review Context

This file is the authoritative review router for code-review sessions in this repo. Use it instead of re-reading the full `.cursor` tree unless the changed area matches a routing rule below.

## Review mode

- Focus on code review, bug finding, and actionable feedback.
- Keep feedback strict, clear, and ordered by severity.
- Prefer minimal changes that preserve the existing Laravel architecture.
- Do not introduce new patterns unless the repo already uses them or they are clearly required.
- **Review-only mode is the default.** Do not edit code during review unless the user explicitly authorizes fixes.

## Review workflow

1. **Establish the review scope.** Identify the changed files, diff, and intended behavior.
2. **Inspect the diff and changed files.**
3. **Classify the review category:**
   - **Production code** — controllers, services, models, requests, jobs, middleware, queries, migrations, Blade, frontend, RBAC, file uploads, integrations, or any business logic.
   - **Tests** — PHPUnit test files covering production behavior.
   - **Documentation** — README files, API/Postman docs, PHPDoc, configuration docs, workflows, skill docs, or other technical documentation.
   - **Mixed** — multiple categories in the same diff.
4. **Select the appropriate guard(s) based on the category:**
   - Production code → [`clean-code-guard`](.cursor/skills/guards/clean-code-guard/SKILL.md)
   - Tests → [`test-guard`](.cursor/skills/guards/test-guard/SKILL.md)
   - Documentation → [`docs-guard`](.cursor/skills/guards/docs-guard/SKILL.md)
   - Mixed → all applicable guards
5. **Load the minimal baseline for the selected guard(s).** Each guard defines its mandatory files and conditional routing rules.
6. **Load specialized skills governing the changed behavior** (e.g. `auth-permissions`, `file-upload`, `integration`).
7. **For mixed reviews:** Load the union of only the applicable baselines. Do not duplicate identical files in the context list.
8. **Compare code against actual neighboring project patterns.**
9. **Verify claims against source files.**
10. **Run relevant non-destructive verification when feasible.**
11. **Report findings before summaries.**
12. **Order findings by severity.**
13. **Include exact file and line evidence.**
14. **Label inference explicitly.**
15. **Separate findings, testing gaps, and residual risks.**
16. **State clearly when no findings are discovered.**

## Review category and guard routing

### Production code review

**Route through:** [`clean-code-guard`](.cursor/skills/guards/clean-code-guard/SKILL.md)

**Minimal baseline:**
- [`.cursor/context/technology-baseline.md`](.cursor/context/technology-baseline.md)
- [`.cursor/rules/01-code-quality.mdc`](.cursor/rules/01-code-quality.mdc)
- [`.cursor/rules/02-architecture.mdc`](.cursor/rules/02-architecture.mdc)
- [`.cursor/rules/18-security.mdc`](.cursor/rules/18-security.mdc)
- [`.cursor/rules/22-code-review.mdc`](.cursor/rules/22-code-review.mdc)

**Conditional domain rules** (load only when the changed files match):

| Domain | Additional rules | Governing skill |
|---|---|---|
| Controllers, services, Form Requests, jobs, middleware | [`.cursor/rules/04-backend-rules.mdc`](.cursor/rules/04-backend-rules.mdc) | [`backend-feature-implementation`](.cursor/skills/development-phase/backend-feature-implementation/SKILL.md) or area skill |
| Models, migrations, factories, seeders, queries | [`.cursor/rules/05-database-rules.mdc`](.cursor/rules/05-database-rules.mdc), [`.cursor/rules/12-database-eloquent.mdc`](.cursor/rules/12-database-eloquent.mdc) | [`database-design`](.cursor/skills/development-phase/database-design/SKILL.md) |
| Blade, frontend JS/CSS, components | [`.cursor/rules/03-frontend-rules.mdc`](.cursor/rules/03-frontend-rules.mdc), [`.cursor/rules/14-frontend-integration.mdc`](.cursor/rules/14-frontend-integration.mdc) | [`ui-page-build`](.cursor/skills/development-phase/ui-page-build/SKILL.md) |
| API routes, controllers, resources, contracts | [`.cursor/rules/07-api-postman-mcp-documentation-rules.mdc`](.cursor/rules/07-api-postman-mcp-documentation-rules.mdc), [`.cursor/rules/13-api-integration.mdc`](.cursor/rules/13-api-integration.mdc) | [`create-api-with-postman`](.cursor/skills/development-phase/create-api-with-postman/SKILL.md) |
| RBAC, admin permissions, roles | [`.cursor/rules/08-custom-rbac.mdc`](.cursor/rules/08-custom-rbac.mdc) | [`auth-permissions`](.cursor/skills/specialized/auth-permissions/SKILL.md) |
| File uploads / media | [`.cursor/rules/18-security.mdc`](.cursor/rules/18-security.mdc), [`.cursor/rules/21-ecosystem.mdc`](.cursor/rules/21-ecosystem.mdc) | [`file-upload`](.cursor/skills/specialized/file-upload/SKILL.md) |
| External integrations / webhooks | [`.cursor/rules/13-api-integration.mdc`](.cursor/rules/13-api-integration.mdc), [`.cursor/rules/18-security.mdc`](.cursor/rules/18-security.mdc) | [`integration`](.cursor/skills/specialized/integration/SKILL.md) |
| Performance-sensitive (query volume, caching, indexes, loops, queues, exports, datasets) | [`.cursor/rules/19-performance.mdc`](.cursor/rules/19-performance.mdc) | governing production skill |

### Test review

**Route through:** [`test-guard`](.cursor/skills/guards/test-guard/SKILL.md)

**Minimal baseline:**
- [`.cursor/context/technology-baseline.md`](.cursor/context/technology-baseline.md)
- [`.cursor/rules/16-testing-qa.mdc`](.cursor/rules/16-testing-qa.mdc)
- [`references/laravel-phpunit.md`](.cursor/skills/guards/test-guard/references/laravel-phpunit.md)

**Always load the production-code skill that governs the behavior under test.**

**Conditional domain rules** (load only when relevant to the tested behavior):

| Tested behavior | Additional rules / skills |
|---|---|
| Formal review requiring evidence/reporting standards | [`.cursor/rules/22-code-review.mdc`](.cursor/rules/22-code-review.mdc) |
| Test design, boundaries, or architecture concerns | [`.cursor/rules/02-architecture.mdc`](.cursor/rules/02-architecture.mdc) |
| RBAC / permissions | [`.cursor/rules/08-custom-rbac.mdc`](.cursor/rules/08-custom-rbac.mdc), [`auth-permissions`](.cursor/skills/specialized/auth-permissions/SKILL.md) |
| API endpoints | [`.cursor/rules/07-api-postman-mcp-documentation-rules.mdc`](.cursor/rules/07-api-postman-mcp-documentation-rules.mdc), [`.cursor/rules/13-api-integration.mdc`](.cursor/rules/13-api-integration.mdc), [`create-api-with-postman`](.cursor/skills/development-phase/create-api-with-postman/SKILL.md) |
| Database / persistence / migrations | [`.cursor/rules/05-database-rules.mdc`](.cursor/rules/05-database-rules.mdc), [`.cursor/rules/12-database-eloquent.mdc`](.cursor/rules/12-database-eloquent.mdc), [`database-design`](.cursor/skills/development-phase/database-design/SKILL.md) |
| External integrations | [`integration`](.cursor/skills/specialized/integration/SKILL.md), [`.cursor/rules/13-api-integration.mdc`](.cursor/rules/13-api-integration.mdc), [`.cursor/rules/18-security.mdc`](.cursor/rules/18-security.mdc) |
| File uploads | [`file-upload`](.cursor/skills/specialized/file-upload/SKILL.md), [`.cursor/rules/18-security.mdc`](.cursor/rules/18-security.mdc) |
| General backend behavior | [`backend-feature-implementation`](.cursor/skills/development-phase/backend-feature-implementation/SKILL.md), [`.cursor/rules/04-backend-rules.mdc`](.cursor/rules/04-backend-rules.mdc) |
| Admin CRUD | [`admin-crud-orchestrator`](.cursor/skills/development-phase/admin-crud-orchestrator/SKILL.md) |

### Documentation review

**Route through:** [`docs-guard`](.cursor/skills/guards/docs-guard/SKILL.md)

**Minimal baseline:**
- [`.cursor/context/technology-baseline.md`](.cursor/context/technology-baseline.md)
- [`references/verification-procedure.md`](.cursor/skills/guards/docs-guard/references/verification-procedure.md)

**Conditional domain rules** (load only for the documentation type being reviewed):

| Documentation type | Additional rules / sources |
|---|---|
| API / Postman documentation | [`.cursor/rules/07-api-postman-mcp-documentation-rules.mdc`](.cursor/rules/07-api-postman-mcp-documentation-rules.mdc), API routes, Form Requests, controllers, resources, response traits |
| README / setup documentation | `composer.json`, `package.json`, `.env.example`, Artisan commands, [`setup-workflow`](.cursor/workflows/setup-workflow.md) |
| PHPDoc / docblocks | Actual class/method signatures and behavior |
| Configuration documentation | Config file, `.env.example`, code reading the config key |
| Workflow / skill / template documentation | Referenced `.cursor` files and actual project paths |
| RBAC documentation | [`.cursor/rules/08-custom-rbac.mdc`](.cursor/rules/08-custom-rbac.mdc), [`auth-permissions`](.cursor/skills/specialized/auth-permissions/SKILL.md), middleware and route traits |
| Database documentation | Migrations, models, relationships, [`.cursor/rules/05-database-rules.mdc`](.cursor/rules/05-database-rules.mdc), [`.cursor/rules/12-database-eloquent.mdc`](.cursor/rules/12-database-eloquent.mdc) |
| UI documentation | [`.cursor/rules/03-frontend-rules.mdc`](.cursor/rules/03-frontend-rules.mdc), [`.cursor/rules/14-frontend-integration.mdc`](.cursor/rules/14-frontend-integration.mdc), actual components/templates |
| Integration documentation | [`integration`](.cursor/skills/specialized/integration/SKILL.md), config keys, provider client implementation |

**Note:** Load [`.cursor/rules/22-code-review.mdc`](.cursor/rules/22-code-review.mdc) only when conducting a formal review requiring its evidence/reporting standards. Do not load API/Postman rules automatically for README files, installation guides, PHPDoc, configuration documentation, workflow documentation, skill documentation, template documentation, `.cursor` documentation, database documentation, UI documentation, or RBAC documentation.

### Mixed review

**Route through:** all applicable guards

When a diff spans multiple categories:

1. Classify each changed file independently as production code, tests, or documentation.
2. Route production files through [`clean-code-guard`](.cursor/skills/guards/clean-code-guard/SKILL.md) and load its minimal baseline.
3. Route tests through [`test-guard`](.cursor/skills/guards/test-guard/SKILL.md) and load its minimal baseline.
4. Route documentation through [`docs-guard`](.cursor/skills/guards/docs-guard/SKILL.md) and load its minimal baseline.
5. Load only the conditional domain rules applicable to each category.
6. Do not duplicate identical files in the context list.
7. Review tests against the production behavior they claim to cover.
8. Verify documentation against the implementation it describes.

## Key principle for correct routing

**Do not apply the production-code baseline to a test-only or documentation-only review. For mixed reviews, combine only the baselines required by the changed-file categories.**

## Evidence requirements

- Every finding must cite the exact file path and line number.
- Do not reference files, methods, traits, routes, or configs that do not exist in the repository.
- If a finding is based on inference, label it `[inference]`.
- Do not bury findings beneath a long overview.

## Prompt style for fixes

When review finds a problem, give a strict fix prompt:

```text
Fix [problem] in [file(s)].

Constraints:
- Preserve existing architecture.
- Make the smallest safe change.
- Do not touch unrelated files.
- Keep validation in Form Requests and business logic in Services.
- Use $request->validated(), not $request->all().
- Add or update tests if behavior changes.

Expected result:
- [clear success condition]
```

## Working rule for future sessions

- Start with this file to determine the review category and select the appropriate guard.
- Use the guard's routing table for conditional rules.
- Only open deeper `.cursor` files when the changed area matches the routing table.
- If the task is localized, review only the impacted files plus the rules that apply to them.
- Keep token usage efficient: do not read the full `.cursor` tree unless explicitly asked.
- For documentation-only reviews: do not load the production baseline.
- For test-only reviews: do not load the production baseline; load the governing production skill instead.
- For mixed reviews: load the union of applicable baselines only.
