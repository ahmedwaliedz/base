# Development Workflow

## Purpose

Coordinate the standard sequence for implementing a feature in the project. This workflow ensures each feature is analyzed, structured, implemented, documented, tested, reviewed, and finalized in the correct order.

## Trigger

- Implementing a new feature
- Extending an existing module
- Building APIs, UI pages, or workflows
- Normal feature development

## Preconditions

- Project has been initialized and adapted to the domain.
- Project readiness review is complete.
- Required context files are available.

## Workflow

### Step 1: Analyze the Feature

- **Primary skill:** [`feature-analysis`](../skills/development-phase/feature-analysis/SKILL.md)
- **Rules:** [`../rules/02-architecture.mdc`](../rules/02-architecture.mdc), [`../rules/05-database-rules.mdc`](../rules/05-database-rules.mdc)
- **Output:** Structured analysis document

### Step 2: Map Feature to Module

- **Primary skill:** [`feature-to-module-execution`](../skills/development-phase/feature-to-module-execution/SKILL.md)
- **Alternative when a new module is needed:** [`create-module`](../skills/development-phase/create-module/SKILL.md)
- **Rules:** [`../rules/02-architecture.mdc`](../rules/02-architecture.mdc)
- **Output:** Module mapping and execution order

### Step 3: Implement Backend

- **Primary skill:** [`backend-feature-implementation`](../skills/development-phase/backend-feature-implementation/SKILL.md)
- **Rules:** [`../rules/04-backend-rules.mdc`](../rules/04-backend-rules.mdc), [`../rules/08-custom-rbac.mdc`](../rules/08-custom-rbac.mdc)
- **Templates:** [`../templates/service-template.md`](../templates/service-template.md), [`../templates/form-request-template.md`](../templates/form-request-template.md), [`../templates/controller-template.md`](../templates/controller-template.md)
- **Output:** Service, Form Request, thin controller, migration

### Step 4: Implement API or UI

Choose based on feature type.

#### API

- **Primary skill:** [`create-api-with-postman`](../skills/development-phase/create-api-with-postman/SKILL.md)
- **Rules:** [`../rules/07-api-postman-mcp-documentation-rules.mdc`](../rules/07-api-postman-mcp-documentation-rules.mdc)
- **Templates:** [`../templates/api-endpoint-template.md`](../templates/api-endpoint-template.md)
- **Output:** Routes, JsonResource, Postman documentation

#### UI

- **Primary skill:** [`ui-page-build`](../skills/development-phase/ui-page-build/SKILL.md)
- **Rules:** [`../rules/03-frontend-rules.mdc`](../rules/03-frontend-rules.mdc)
- **Templates:** [`../templates/show-view-template.md`](../templates/show-view-template.md), [`../templates/table-view-template.md`](../templates/table-view-template.md)
- **Output:** Blade pages and components

### Step 5: Test the Feature

- **Primary skill:** [`testing`](../skills/development-phase/testing/SKILL.md)
- **Rules:** [`../rules/16-testing-qa.mdc`](../rules/16-testing-qa.mdc)
- **Templates:** [`../templates/test-template.md`](../templates/test-template.md)
- **Output:** PHPUnit feature/unit tests

### Step 6: Finalize and Validate

- **Primary skill:** [`feature-finalization-and-validation`](../skills/development-phase/feature-finalization-and-validation/SKILL.md)
- **Rules:** [`../rules/01-code-quality.mdc`](../rules/01-code-quality.mdc), [`../rules/22-code-review.mdc`](../rules/22-code-review.mdc)
- **Output:** Validation report and ready/not-ready decision

## Guard passes

Run guards after the affected content is changed. When implementation is already authorized, use guard-pass mode: report findings, fix confirmed critical/high findings in scope, rerun the guard, and repeat up to two cycles. If a finding persists or needs expanded authority, report it to the user.

- Production code changed → [`clean-code-guard`](../skills/guards/clean-code-guard/SKILL.md)
- Tests changed → [`test-guard`](../skills/guards/test-guard/SKILL.md)
- Documentation, Postman, or README changed → [`docs-guard`](../skills/guards/docs-guard/SKILL.md)
- Mixed changes → run all applicable guards

## Verification

- `php artisan test`
- `vendor/bin/pint` when formatting is in scope
- Manual verification of changed routes or UI flows

## Completion criteria

A development workflow is complete only when:

- The feature has been analyzed thoroughly.
- The correct module (new or existing) has been identified.
- Backend is implemented with proper service/controller separation.
- API or UI is complete and documented.
- All critical paths have tests.
- Relevant guard passes produce no unresolved critical or high findings.
- No known correctness, security, or data-integrity defect remains.
- Medium/low findings are either fixed, accepted with justification, or reported as residual risk.
