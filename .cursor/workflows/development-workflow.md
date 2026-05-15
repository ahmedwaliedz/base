# Development Workflow

## Purpose
Define the standard development sequence for implementing a feature in the project.

This workflow ensures each feature is analyzed, structured, implemented, documented, tested, and reviewed in the correct order.

---

## When to Use
- When implementing a new feature
- When extending an existing module
- When building APIs, UI pages, or workflows
- During normal feature development

---

## Workflow Steps

### Step 1: Analyze the Feature
- **Trigger:** `feature-analysis` skill
- **Must follow:** `rules/02-architecture.mdc`, `rules/05-database-rules.mdc`
- **Goals:**
  - Understand the feature goal and business intent
  - Identify affected modules and layers
  - Define schema needs and validation rules
  - Identify API/UI requirements
  - Map edge cases and state transitions
- **Output:** Structured analysis doc

---

### Step 2: Map Feature to Module
- **Trigger:** `feature-to-module-execution` skill OR `create-module` skill (if new module needed)
- **Must follow:** `rules/02-architecture.mdc`
- **Goals:**
  - Decide: extend existing module or create new one?
  - Map feature to correct layers (Service, Controller, Request, Routes)
  - Prepare execution order
  - Identify required templates
- **Output:** Module mapping document, execution order

---

### Step 3: Implement Backend
- **Trigger:** `backend-feature-implementation` skill
- **Must follow:** `rules/04-backend-rules.mdc`, `rules/08-custom-rbac.mdc`
- **Goals:**
  - Create or update Form Requests for validation
  - Create or update Service classes (business logic)
  - Keep controllers thin (orchestration only)
  - Implement RBAC checks if needed
  - Write database migrations
- **Output:** Service, FormRequest, Controller (thin), Migration

---

### Step 4: Implement API or UI
Choose based on feature type.

#### If API:
- **Trigger:** `create-api-with-postman` skill
- **Must follow:** `rules/07-api-postman-mcp-documentation-rules.mdc`
- **Goals:**
  - Define endpoints (routes)
  - Implement consistent response format
  - Document params, body, enums, responses
  - Prepare Postman examples
- **Output:** Routes, JsonResource, Postman docs, examples

#### If UI:
- **Trigger:** `ui-page-build` skill
- **Must follow:** `rules/03-frontend-rules.mdc`
- **Goals:**
  - Build pages, forms, lists, or actions
  - Keep UI consistent with existing design
  - Keep Blade for presentation only
  - Implement form validation feedback
- **Output:** Page structure, form structure, list behavior, UI actions

---

### Step 5: Test the Feature
- **Trigger:** `testing` skill
- **Must follow:** `rules/16-testing-qa.mdc`
- **Goals:**
  - Test success cases and happy path
  - Test validation failures
  - Test permission/auth failures
  - Test edge cases and state transitions
  - Protect against regressions
- **Output:** Feature test class, test coverage report

---

### Step 6: Finalize and Validate
- **Trigger:** `feature-finalization-and-validation` skill
- **Must follow:** `rules/01-code-quality.mdc`, `rules/22-code-review.mdc`
- **Goals:**
  - Verify architecture compliance
  - Verify correctness and edge cases
  - Verify naming consistency
  - Verify documentation is complete
  - Verify tests cover critical flows
- **Output:** Validation report, issues list, ready/not ready decision

---

## Rules Enforcement
- Do not skip feature analysis.
- Do not skip module mapping (decide module scope before implementation).
- Do not write business logic in controllers or Blade.
- Do not skip testing or final validation.
- Do not create undocumented APIs when documentation is required.
- Keep implementation aligned with project rules and context.
- Always verify RBAC and permissions for protected resources.

---

## Completion Standard
A development workflow is complete only when:
- the feature has been analyzed thoroughly
- the correct module (new or existing) has been identified
- backend is implemented correctly with proper service/controller separation
- API or UI is complete and documented
- all critical paths have tests
- final validation passes all checks
- no rule violations remain
- the feature is ready for delivery