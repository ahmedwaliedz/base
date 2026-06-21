# Code Writing Context

This file is the authoritative execution router for implementation sessions in this repo. Use it instead of re-reading the full `.cursor` tree unless the task touches a routed area below.

## Execution mode

- Implement code from an approved plan, checklist item, bug report, or clearly scoped request.
- Preserve the existing Laravel architecture and admin dashboard patterns.
- Make the smallest complete change that solves the requested item.
- Do not introduce new patterns unless the repo already uses them or they are clearly required.
- Do not touch unrelated files.
- If a change involves RBAC permissions, roles, or admin access control and the intended behavior is unclear, stop and ask.

## Execution workflow

1. **Parse the requested outcome.** Understand the business behavior, scope, and acceptance criteria.
2. **Identify target files and affected project areas.** Look at the diff, plan, or request.
3. **Inspect current code and neighboring implementations.** Match existing patterns.
4. **Detect the current technology baseline.** Read `.cursor/context/technology-baseline.md` and confirm installed versions from `composer.json`, `composer.lock`, and `phpunit.xml`.
5. **Load the smallest relevant context:**
   - Start with the mandatory baseline:
     - [`.cursor/context/technology-baseline.md`](.cursor/context/technology-baseline.md)
     - [`.cursor/rules/01-code-quality.mdc`](.cursor/rules/01-code-quality.mdc)
     - [`.cursor/rules/02-architecture.mdc`](.cursor/rules/02-architecture.mdc)
     - [`.cursor/rules/18-security.mdc`](.cursor/rules/18-security.mdc)
   - Then load only the domain-specific rules that match the changed files. See [`clean-code-guard`](.cursor/skills/guards/clean-code-guard/SKILL.md) for the full production-code routing table.
   - Examples:
     - Controllers/services/requests/jobs → [`.cursor/rules/04-backend-rules.mdc`](.cursor/rules/04-backend-rules.mdc)
     - Models/migrations/database → [`.cursor/rules/05-database-rules.mdc`](.cursor/rules/05-database-rules.mdc) + [`.cursor/rules/12-database-eloquent.mdc`](.cursor/rules/12-database-eloquent.mdc)
     - Blade/frontend → [`.cursor/rules/03-frontend-rules.mdc`](.cursor/rules/03-frontend-rules.mdc) + [`.cursor/rules/14-frontend-integration.mdc`](.cursor/rules/14-frontend-integration.mdc)
     - API → [`.cursor/rules/07-api-postman-mcp-documentation-rules.mdc`](.cursor/rules/07-api-postman-mcp-documentation-rules.mdc) + [`.cursor/rules/13-api-integration.mdc`](.cursor/rules/13-api-integration.mdc)
     - RBAC changes → [`.cursor/rules/08-custom-rbac.mdc`](.cursor/rules/08-custom-rbac.mdc)
6. **Select one primary implementation skill.** Do not load every skill automatically.
7. **Select secondary skills only when required.**
8. **Load only relevant references.**
9. **Select the relevant workflow.**
10. **Select relevant templates.**
11. **Identify project-specific constraints** from context files and neighboring code.
12. **Implement the smallest complete solution.**
13. **Add or update meaningful tests.**
14. **Update affected documentation** when behavior, routes, or API contracts change.
15. **Run appropriate guard passes.** In implementation mode, guards may fix confirmed critical/high findings and rerun up to two cycles. Report persistent or unclear findings to the user.
16. **Run available verification commands.**
17. **Report completed work, verification, and residual risks.**

## Task routing

| Task type | Primary skill | Secondary skills | Workflow | Templates |
|---|---|---|---|---|
| Requirement analysis | [`feature-analysis`](.cursor/skills/development-phase/feature-analysis/SKILL.md) | [`database-design`](.cursor/skills/development-phase/database-design/SKILL.md) if schema changes | [`development-workflow`](.cursor/workflows/development-workflow.md) | none |
| Backend behavior | [`backend-feature-implementation`](.cursor/skills/development-phase/backend-feature-implementation/SKILL.md) | [`feature-analysis`](.cursor/skills/development-phase/feature-analysis/SKILL.md) | [`development-workflow`](.cursor/workflows/development-workflow.md) | `service-template.md`, `form-request-template.md`, `controller-template.md` |
| Full feature | [`laravel-feature-end-to-end`](.cursor/skills/development-phase/laravel-feature-end-to-end/SKILL.md) | As needed per layer | [`development-workflow`](.cursor/workflows/development-workflow.md) | All relevant |
| New module | [`create-module`](.cursor/skills/development-phase/create-module/SKILL.md) | [`backend-feature-implementation`](.cursor/skills/development-phase/backend-feature-implementation/SKILL.md), [`ui-page-build`](.cursor/skills/development-phase/ui-page-build/SKILL.md) | [`development-workflow`](.cursor/workflows/development-workflow.md) | All relevant |
| Admin CRUD | [`admin-crud-orchestrator`](.cursor/skills/development-phase/admin-crud-orchestrator/SKILL.md) | [`ui-page-build`](.cursor/skills/development-phase/ui-page-build/SKILL.md), [`testing`](.cursor/skills/development-phase/testing/SKILL.md) | [`development-workflow`](.cursor/workflows/development-workflow.md) | `show-view-template.md`, `table-view-template.md`, `service-template.md`, `form-request-template.md`, `controller-template.md`, `test-template.md` |
| API and Postman | [`create-api-with-postman`](.cursor/skills/development-phase/create-api-with-postman/SKILL.md) | [`backend-feature-implementation`](.cursor/skills/development-phase/backend-feature-implementation/SKILL.md) | [`development-workflow`](.cursor/workflows/development-workflow.md) | `api-endpoint-template.md`, `form-request-template.md`, `controller-template.md` |
| UI/Blade page | [`ui-page-build`](.cursor/skills/development-phase/ui-page-build/SKILL.md) | [`admin-crud-orchestrator`](.cursor/skills/development-phase/admin-crud-orchestrator/SKILL.md) if part of CRUD | [`development-workflow`](.cursor/workflows/development-workflow.md) | `show-view-template.md`, `table-view-template.md` |
| Database changes | [`database-design`](.cursor/skills/development-phase/database-design/SKILL.md) | [`feature-analysis`](.cursor/skills/development-phase/feature-analysis/SKILL.md) | [`development-workflow`](.cursor/workflows/development-workflow.md) | none |
| Bug fix | [`bug-fixing`](.cursor/skills/development-phase/bug-fixing/SKILL.md) | [`testing`](.cursor/skills/development-phase/testing/SKILL.md) | [`hotfix-workflow`](.cursor/workflows/hotfix-workflow.md) | `test-template.md` |
| Refactor | [`refactor`](.cursor/skills/development-phase/refactor/SKILL.md) | [`testing`](.cursor/skills/development-phase/testing/SKILL.md) | [`hotfix-workflow`](.cursor/workflows/hotfix-workflow.md) or [`development-workflow`](.cursor/workflows/development-workflow.md) | none |
| Tests | [`testing`](.cursor/skills/development-phase/testing/SKILL.md) | Governing production skill | [`development-workflow`](.cursor/workflows/development-workflow.md) or [`hotfix-workflow`](.cursor/workflows/hotfix-workflow.md) | `test-template.md` |
| Authentication/RBAC | [`auth-permissions`](.cursor/skills/specialized/auth-permissions/SKILL.md) | [`backend-feature-implementation`](.cursor/skills/development-phase/backend-feature-implementation/SKILL.md) | [`development-workflow`](.cursor/workflows/development-workflow.md) | none |
| File handling | [`file-upload`](.cursor/skills/specialized/file-upload/SKILL.md) | [`backend-feature-implementation`](.cursor/skills/development-phase/backend-feature-implementation/SKILL.md) | [`development-workflow`](.cursor/workflows/development-workflow.md) | none |
| External systems | [`integration`](.cursor/skills/specialized/integration/SKILL.md) | [`backend-feature-implementation`](.cursor/skills/development-phase/backend-feature-implementation/SKILL.md) | [`development-workflow`](.cursor/workflows/development-workflow.md) | none |
| Realtime functionality | [`realtime-chat`](.cursor/skills/specialized/realtime-chat/SKILL.md) | As needed | [`development-workflow`](.cursor/workflows/development-workflow.md) | none |

## After implementation

Run guards based on what changed. In authorized implementation mode, each guard inspects the change, reports findings, fixes confirmed critical/high in-scope findings, reruns validation, and repeats up to two cycles. Review-only mode reports findings without editing.

- Production code changed → [`clean-code-guard`](.cursor/skills/guards/clean-code-guard/SKILL.md)
- Tests changed → [`test-guard`](.cursor/skills/guards/test-guard/SKILL.md)
- Documentation changed or documented behavior affected → [`docs-guard`](.cursor/skills/guards/docs-guard/SKILL.md)
- Substantial features, modules, APIs, CRUD work, cross-layer changes, or risky fixes → [`feature-finalization-and-validation`](.cursor/skills/development-phase/feature-finalization-and-validation/SKILL.md)

Each guard loads a minimal mandatory baseline and then conditionally adds rules, skills, and references based on the changed area. See the guard skill for its routing table.

## Verification

- `php artisan test`
- `vendor/bin/pint` when formatting is in scope
- Targeted route or UI verification when needed

## Safety rules

- Never replace the custom RBAC system or change permission tables/logic without explicit confirmation.
- Never hide exceptions with broad `try/catch` unless the error handling is intentional and user-facing.
- Never fix symptoms when the root cause can be identified.
- Never add database columns without a migration and a data integrity reason.
- Never store calculated values unless performance, audit, or product requirements justify it.
- Never introduce duplicate components, services, or helpers when an existing pattern fits.
- Never mark work complete without at least one verification step.

## Reporting standard

At the end of implementation, report:

- Files changed
- What behavior changed
- Verification performed
- Tests added or updated
- Guard passes run and their results
- Any tests not run and why
- Any remaining risk or follow-up
