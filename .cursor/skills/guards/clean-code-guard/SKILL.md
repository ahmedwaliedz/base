---
name: clean-code-guard
description: Review changed production code after implementation for correctness, maintainability, architecture, and common AI-generated mistakes. Trigger when production code was added or modified, after implementation or during code review. Supports guard-pass, live, and review-only modes.
---

# clean-code-guard

Review changed production code after implementation. This guard complements Laravel-specific rules and project conventions; it does not replace tests, security review, or mechanical tooling such as Pint.

## When to use

- After implementing or changing production code.
- Before marking a feature, bug fix, or refactor as complete.
- During code review when production files are in scope.

## Operating modes

- **Guard-pass mode** — Run automatically after authorized implementation. Inspect changed production files, report findings with severity, and fix confirmed critical/high findings that are in scope. Rerun the guard after fixes. Repeat up to two correction cycles; if a finding persists or needs expanded authority, report it to the user. Do not fix unrelated pre-existing issues.
- **Live mode** — Run continuously while code is being written. Flag issues as they appear, then perform a final guard pass before delivery.
- **Review-only mode** — Run on request. Produce findings only; never edit code unless the user explicitly authorizes fixes.

## Mandatory baseline

Load these before every review:

- [`../../../context/technology-baseline.md`](../../../context/technology-baseline.md)
- [`../../../rules/01-code-quality.mdc`](../../../rules/01-code-quality.mdc)
- [`../../../rules/02-architecture.mdc`](../../../rules/02-architecture.mdc)
- [`../../../rules/18-security.mdc`](../../../rules/18-security.mdc)
- [`../../../rules/22-code-review.mdc`](../../../rules/22-code-review.mdc)
- [`references/review-checklist.md`](references/review-checklist.md)

## Conditional rule routing

Load additional rules and skills only when the changed files match:

| Changed area | Additional rules | Governing skill |
|---|---|---|
| Controllers, services, Form Requests, jobs, middleware | [`../../../rules/04-backend-rules.mdc`](../../../rules/04-backend-rules.mdc) | [`backend-feature-implementation`](../../development-phase/backend-feature-implementation/SKILL.md) or area skill |
| Models, migrations, factories, seeders, queries | [`../../../rules/05-database-rules.mdc`](../../../rules/05-database-rules.mdc), [`../../../rules/12-database-eloquent.mdc`](../../../rules/12-database-eloquent.mdc) | [`database-design`](../../development-phase/database-design/SKILL.md) |
| Blade, frontend JS/CSS, components | [`../../../rules/03-frontend-rules.mdc`](../../../rules/03-frontend-rules.mdc), [`../../../rules/14-frontend-integration.mdc`](../../../rules/14-frontend-integration.mdc) | [`ui-page-build`](../../development-phase/ui-page-build/SKILL.md) |
| API routes, controllers, resources, contracts | [`../../../rules/07-api-postman-mcp-documentation-rules.mdc`](../../../rules/07-api-postman-mcp-documentation-rules.mdc), [`../../../rules/13-api-integration.mdc`](../../../rules/13-api-integration.mdc) | [`create-api-with-postman`](../../development-phase/create-api-with-postman/SKILL.md) |
| RBAC, admin permissions, roles | [`../../../rules/08-custom-rbac.mdc`](../../../rules/08-custom-rbac.mdc) | [`auth-permissions`](../../specialized/auth-permissions/SKILL.md) |
| File uploads / media | [`../../../rules/18-security.mdc`](../../../rules/18-security.mdc), [`../../../rules/21-ecosystem.mdc`](../../../rules/21-ecosystem.mdc) | [`file-upload`](../../specialized/file-upload/SKILL.md) |
| External integrations / webhooks | [`../../../rules/13-api-integration.mdc`](../../../rules/13-api-integration.mdc), [`../../../rules/18-security.mdc`](../../../rules/18-security.mdc) | [`integration`](../../specialized/integration/SKILL.md) |
| Database/performance-sensitive code (query volume, caching, indexes, loops, queues, exports, large datasets) | [`../../../rules/19-performance.mdc`](../../../rules/19-performance.mdc) | governing production skill |
| Architecture refactor / cross-layer design changes | [`../../../rules/17-architecture-design.mdc`](../../../rules/17-architecture-design.mdc) | [`refactor`](../../development-phase/refactor/SKILL.md) or [`feature-analysis`](../../development-phase/feature-analysis/SKILL.md) |
| Tests | route to [`test-guard`](../../guards/test-guard/SKILL.md); do not use `clean-code-guard` as the primary test reviewer | [`testing`](../../development-phase/testing/SKILL.md) + governing production skill |
| Documentation only | route to [`docs-guard`](../../guards/docs-guard/SKILL.md); do not run a production-code review unless implementation claims must be checked | [`docs-guard`](../../guards/docs-guard/SKILL.md) |

When a diff spans multiple layers, load every matching category.

## Review workflow

1. Identify changed production files.
2. Read neighboring project code to establish the local style.
3. Load the shared technology baseline from [`../../../context/technology-baseline.md`](../../../context/technology-baseline.md).
4. Load relevant rules and the specialized skill governing the changed area.
5. Walk through the detailed checklist in [`references/review-checklist.md`](references/review-checklist.md).
6. For each finding, provide:
   - Severity (critical / high / medium / low)
   - Exact file and line
   - Observed behavior
   - Why it matters
   - Minimal recommended fix
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

## What to prioritize

Behavior, security, data integrity, and maintainability take priority over cosmetic concerns. Treat size, complexity, and parameter count as signals, not hard limits. Respect Laravel conventions and existing project patterns.

## References

- [`references/review-checklist.md`](references/review-checklist.md) — Detailed review checklist including common AI failure modes.
