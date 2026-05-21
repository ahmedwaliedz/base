# Admin CRUD Orchestrator Skill

## Purpose
Build a complete Admin CRUD module from start to finish, fully aligned with the project architecture, rules, and development system.

This skill orchestrates the full CRUD implementation lifecycle, including:
- requirements collection
- feature analysis
- database design
- validation
- service layer
- controller
- views
- routes
- translations
- factory and seeder
- optional export
- optional statistics
- testing
- final validation

---

## When to Use
- When creating a new admin CRUD module
- When rebuilding or standardizing an existing CRUD module
- When the module requires full end-to-end implementation
- When migration, validation, service, controller, views, translations, and seeding are all needed together

---

## Core Principle
- CRUD generation must follow the project architecture.
- Validation belongs in Form Requests.
- Business logic belongs in Service classes.
- Controllers must remain thin.
- Blade views must remain presentation-only.
- All generated code must reuse existing project patterns when available.

---

## Execution Strategy
- Start by collecting all required inputs.
- Do not generate code before inputs are complete.
- Build a plan before implementation.
- Execute the CRUD in layer order.
- Validate the result before considering the task complete.

---

## Step 1: Collect Inputs

Collect all required inputs before generating code.

### Required Inputs
- model name
- Arabic singular label
- Arabic plural label
- columns definition
- features enabled:
  - soft deletes
  - multilingual
  - file uploads
  - map/location
  - statistics
  - export
- component status:
  - Create / Exists / Skip
- routes and sidebar requirements
- translations requirements
- export columns and types
- statistics cards and charts
- seed/factory requirements

### Rules
- Columns are the single source of truth.
- Do not assume extra columns.
- Ask follow-up questions for any ambiguous requirement.
- Do not proceed until inputs are clear enough to build the module correctly.

---

## Step 2: Analyze the CRUD

Use:
- feature-analysis skill

Goals:
- define the CRUD goal
- identify whether it is a new module or extension of an existing module
- identify affected layers
- identify optional parts:
  - export
  - statistics
  - uploads
  - map
  - translations

Output:
- CRUD scope summary
- affected layers
- implementation complexity
- dependencies

---

## Step 3: Design the Database Layer

Use:
- database-design skill

Goals:
- derive schema strictly from columns
- define proper field types
- define nullable/default values
- define foreign keys and relationships
- support soft deletes when enabled
- support multilingual storage strategy when enabled

Rules:
- do not add columns not present in the provided input
- keep schema normalized
- define explicit relationships

Output:
- migration structure
- model fields
- relationships
- DB notes

---

## Step 4: Map the CRUD to the Module Structure

Use:
- create-module skill

Goals:
- determine required files
- determine Create / Exists / Skip per component
- plan file tree
- map each responsibility to the correct layer

Required layers when relevant:
- migration
- model
- form requests
- services
- controller
- routes
- views
- translations
- factory
- seeder
- export
- tests

Output:
- file tree
- create/update/skip plan
- layer mapping

---

## Step 5: Implement Validation Layer

Use project rules for validation.

Requirements:
- create StoreRequest and UpdateRequest when needed
- derive validation rules from columns
- include file validation when file uploads are enabled
- keep validation out of controllers

Rules:
- validation must be handled through Form Requests
- no inline controller validation
- required, optional, nullable, enum, and relational rules must be explicit

Output:
- request classes
- validation rules summary

---

## Step 6: Implement Service Layer

Requirements:
- create service classes for business logic
- separate create, update, delete, and complex index logic when appropriate
- place upload handling, filtering logic, statistics preparation, and business operations in services when relevant

Rules:
- no business logic in controllers
- no business logic in Blade views
- keep services reusable and testable

Examples of service responsibilities:
- store entity
- update entity
- delete entity
- process uploaded files
- compute statistics
- prepare export data

Output:
- service classes
- service responsibilities map

---

## Step 7: Implement Controller Layer

Requirements:
- keep controllers thin
- orchestrate request → service → response/view
- pass all required variables to views
- use shared response format if API responses exist

Rules:
- no heavy logic in controller
- no validation logic in controller
- no direct business rules in controller

Output:
- controller methods
- action mapping
- passed view data summary

---

## Step 8: Implement Views

Use:
- ui-page-build skill
- `.cursor/styles/admin-ui-standards.md` for design patterns
- `.cursor/templates/show-view-template.md` for show page structure
- `.cursor/templates/table-view-template.md` for table page structure

Required views when enabled:
- index
- table partial
- create
- edit
- show

Rules:
- Blade is presentation-only
- no queries inside Blade
- no business logic inside Blade
- file inputs appear at the top of create/edit forms
- statistics cards must be dynamic
- charts must be animated if enabled
- map must be shown in read-only mode on show page when enabled
- Use section-specific action button CSS classes: `{section}-action-view/edit/delete/restore` (with base `{section}-action-btn`)
- Color source: view (blue via `[class*="-action-view"]`), edit (green via `[class*="-action-edit"]`), delete (red via `[class*="-action-delete"]`), restore (teal via `[class*="-action-restore"]`)
- Show page: header + stat cards row (4) + profile card (left, 4 cols) + details card (right, 8 cols)
- Form labels must be plain keys (not `__()` calls), components translate via `admin/inputs.{key}`
- Use `admins-form-section` divs to group related fields

Output:
- views structure
- UI sections
- table/form/show behavior

---

## Step 9: Implement Routes and Sidebar

Requirements:
- register CRUD routes
- register export route when export is enabled
- register sidebar/menu item when requested

Rules:
- route naming must follow project conventions
- sidebar config must follow existing project structure
- use translation keys when translations are enabled

Output:
- routes summary
- sidebar registration summary

---

## Step 10: Implement Translations

Requirements:
- admin/routes
- admin/inputs
- admin/main

Rules:
- add complete translation coverage for the CRUD section
- route translation entries must include:
  - index
  - create
  - store
  - update
  - edit
  - show
  - destroy
  - delete_all or destroyAll
  - export when enabled
- input labels and table headers must use translation keys
- statistics_charts translation must be present when charts are enabled

Output:
- translation keys added
- translation files affected

---

## Step 11: Implement Factory and Seeder

Requirements:
- create factory when enabled
- create seeder when enabled
- register seeder in DatabaseSeeder when requested

Rules:
- derive factory fields from columns only
- use realistic values
- for multilingual entities:
  - Arabic locale must contain real Arabic text
  - English locale must contain English text
- follow existing seeder/factory project style

Output:
- factory
- seeder
- seed registration summary

---

## Step 12: Implement Export

When export is enabled:

Requirements:
- create export class
- add export controller action
- add export route
- add export UI trigger

Rules:
- export only selected columns
- export headers must be translatable
- export types must match selected types
- do not include unlisted columns

Output:
- export class
- export method
- export route
- export UI integration

---

## Step 13: Implement Statistics and Charts

When statistics are enabled:

Requirements:
- prepare statistics cards data
- prepare chart datasets when enabled
- render cards dynamically in index view
- render charts in collapsible section

Rules:
- cards must be dynamic, not hardcoded
- cards must have animation
- charts must have animation
- chart data must come from controller/service, not Blade
- collapsible charts section must use the project translation key for title

Output:
- statistics data structure
- cards config
- chart config
- rendering notes

---

## Step 14: Add Testing

Use:
- testing skill

Required coverage when relevant:
- validation success/failure
- create/update/delete flow
- file upload behavior
- permissions/access
- export endpoint
- statistics-related behavior if logic is non-trivial

Rules:
- tests must follow project testing style
- cover both success and failure paths
- keep tests deterministic

Output:
- test coverage summary
- created test files

---

## Step 15: Finalize and Validate

Use:
- feature-finalization-and-validation skill
- 22-code-review.mdc (rule)

Validate:
- architecture compliance
- naming consistency
- CRUD completeness
- translation completeness
- export completeness
- statistics completeness
- testing completeness

Rules:
- do not consider the CRUD complete if any required layer is missing
- do not consider the CRUD complete if architecture rules were broken
- do not leave optional enabled features half-implemented

Output:
- final validation report
- issues list
- ready/not ready decision

---

## Rules Enforcement
- Validation must be in Form Requests.
- Business logic must be in Service classes.
- Controllers must remain thin.
- Blade views must remain presentation-only.
- Columns are the single source of truth.
- Do not invent fields, relationships, translations, export columns, or statistics data.
- Reuse existing project conventions before creating new structures.
- Optional enabled features must be fully implemented, not partially scaffolded.

---

## Output Format
- CRUD summary
- file tree
- create/update/skip matrix
- database layer summary
- validation layer summary
- service layer summary
- controller summary
- views summary
- routes/sidebar summary
- translations summary
- factory/seeder summary
- export summary
- statistics summary
- testing summary
- final validation report

---

## Completion Standard
An Admin CRUD module is NOT complete unless:

- inputs are fully collected
- analysis is complete
- schema is correct
- model is correct
- Form Requests are implemented
- Service layer is implemented
- controller is thin and correct
- views are clean
- routes are registered
- translations are complete
- factory/seeder are complete when enabled
- export is complete when enabled
- statistics/charts are complete when enabled
- tests are added
- final validation passes
