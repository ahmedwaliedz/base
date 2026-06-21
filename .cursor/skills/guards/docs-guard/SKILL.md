---
name: docs-guard
description: Review or guide technical documentation. Trigger when documentation is added or changed, when documented behavior may have drifted from implementation, or when API/Postman docs, README files, workflows, templates, or .cursor instructions are created or updated.
---

# docs-guard

Review technical documentation against actual project sources. Detect documentation drift, unverifiable claims, broken links, and outdated examples.

## When to use

- After adding or changing README files, API docs, Postman collections, PHPDoc, configuration docs, workflows, templates, or `.cursor` documentation.
- When documented behavior may no longer match implementation.
- When reviewing a pull request that changes documentation.

## Operating modes

- **Review-only mode** — Default. Verify claims and report findings; do not edit unless explicitly asked.
- **Guided update mode** — When the user asks, update documentation to match implementation and fix broken links.
- **Guard-pass mode** — Run automatically after authorized documentation changes. Inspect changed docs, report findings with severity, and fix confirmed critical/high findings that are in scope. Rerun the guard after fixes. Repeat up to two correction cycles; if a finding persists or needs expanded authority, report it to the user. Do not fix unrelated pre-existing issues.

## Mandatory baseline

Load these before every documentation review:

- [`../../../context/technology-baseline.md`](../../../context/technology-baseline.md)
- [`references/verification-procedure.md`](references/verification-procedure.md)

## Conditional rule routing

Load additional rules and skills based on the documentation type:

| Documentation type | Additional sources |
|---|---|
| API / Postman docs | [`../../../rules/07-api-postman-mcp-documentation-rules.mdc`](../../../rules/07-api-postman-mcp-documentation-rules.mdc), API routes, Form Requests, controllers, resources, response traits |
| README / setup docs | `composer.json`, `package.json`, `.env.example`, Artisan commands, [`setup-workflow`](../../../workflows/setup-workflow.md) |
| PHPDoc / docblocks | Actual class/method signatures and behavior |
| Configuration docs | Actual config file, `.env.example`, code reading the config key |
| Workflow / skill / template docs | Referenced `.cursor` files and actual project paths |
| RBAC docs | [`../../../rules/08-custom-rbac.mdc`](../../../rules/08-custom-rbac.mdc), [`auth-permissions`](../../specialized/auth-permissions/SKILL.md), middleware and route traits |
| Database docs | Migrations, models, relationships, [`../../../rules/05-database-rules.mdc`](../../../rules/05-database-rules.mdc), [`../../../rules/12-database-eloquent.mdc`](../../../rules/12-database-eloquent.mdc) |
| UI docs | [`../../../rules/03-frontend-rules.mdc`](../../../rules/03-frontend-rules.mdc), [`../../../rules/14-frontend-integration.mdc`](../../../rules/14-frontend-integration.mdc), actual components/templates |
| Integration docs | [`integration`](../../specialized/integration/SKILL.md), config keys, provider client implementation |

Load [`../../../rules/22-code-review.mdc`](../../../rules/22-code-review.mdc) only when conducting a formal review requiring its evidence/reporting standards.

## Review workflow

1. Identify the documentation files in scope.
2. Identify the implementation sources that should back each claim (routes, controllers, Form Requests, services, resources, models, config, tests).
3. Load the shared technology baseline from [`../../../context/technology-baseline.md`](../../../context/technology-baseline.md).
4. Load [`references/verification-procedure.md`](references/verification-procedure.md).
5. Verify every claim:
   - Class and method names exist.
   - Routes and route names exist.
   - Config keys and environment variables exist.
   - Request/response fields match the actual code.
   - Status codes match the controller/resource behavior.
   - Paths and file links resolve.
   - Code samples match the installed Laravel/PHP versions.
6. Report findings by severity with exact evidence.
7. If no findings remain, state so explicitly.

## Guard-pass correction loop

When this guard runs after implementation is authorized:

1. Report findings internally or in working notes.
2. Fix confirmed critical/high findings that are within the current task scope.
3. Fix medium findings only when safe, relevant, and within scope.
4. Do not fix unrelated pre-existing issues.
5. Rerun any relevant validation (e.g., link checks, claim verification).
6. Rerun this guard.
7. Repeat steps 2–6 for up to two cycles.
8. Stop and report to the user when:
   - no unresolved critical/high findings remain, or
   - a finding requires user clarification or expanded authority.

Completion requires no unresolved critical/high findings and no known correctness, security, or data-integrity defect. Medium/low findings may be fixed, accepted with justification, or reported as residual risk.

## Key principles

- Never document intended behavior as implemented behavior.
- Never fabricate unsupported error responses or fields.
- Remove filler and unverifiable claims.
- Keep Postman examples aligned with actual endpoint behavior.
- Verify internal links, especially after moving skills or references.

## References

- [`references/verification-procedure.md`](references/verification-procedure.md) — Step-by-step verification procedure for common documentation types.
