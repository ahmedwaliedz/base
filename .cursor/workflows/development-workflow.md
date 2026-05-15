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
Use:
- feature-analysis skill

Goals:
- understand the feature goal
- identify affected modules
- identify schema needs
- identify API/UI requirements
- identify validation, states, and edge cases

Output:
- feature analysis
- affected layers
- implementation plan

---

### Step 2: Map Feature to Module
Use:
- feature-to-module-execution skill
- or create-module skill when a full module is needed

Goals:
- decide whether to extend an existing module or create a new one
- map the feature to the correct layers
- prepare execution order

Output:
- module mapping
- execution plan
- required layers

---

### Step 3: Implement Backend
Use:
- backend-feature-implementation skill

Goals:
- create or update Form Requests
- create or update Service classes
- keep controllers thin
- implement business logic in the correct layer

Output:
- backend implementation
- validation structure
- service logic
- controller orchestration

---

### Step 4: Implement API or UI
Choose based on feature type.

#### If API
Use:
- create-api-with-postman skill

Goals:
- define endpoints
- implement consistent responses
- document params/body/enums/responses
- prepare Postman examples

Output:
- endpoints
- response format
- examples
- documentation

#### If UI
Use:
- ui-page-build skill

Goals:
- build pages, forms, lists, or actions
- keep UI consistent
- keep Blade presentation-only

Output:
- page structure
- form structure
- table/list behavior
- UI actions

---

### Step 5: Test the Feature
Use:
- testing skill

Goals:
- test success cases
- test validation failures
- test permission/auth failures
- test edge cases
- protect against regressions

Output:
- test cases
- coverage notes

---

### Step 6: Finalize and Validate the Feature
Use:
- feature-finalization-and-validation skill

Goals:
- verify architecture compliance
- verify correctness
- verify naming consistency
- verify documentation/tests are complete

Output:
- final validation report
- issues list
- ready/not ready decision

---

## Rules Enforcement
- Do not skip feature analysis.
- Do not write business logic in controllers or Blade.
- Do not skip testing or final validation.
- Do not create undocumented APIs when documentation is required.
- Keep implementation aligned with project rules and context.

---

## Completion Standard
A development workflow is complete only when:
- the feature has been analyzed
- the correct module/layers are identified
- backend is implemented correctly
- API or UI is complete
- tests are included
- final validation is complete
- the feature is ready for delivery
