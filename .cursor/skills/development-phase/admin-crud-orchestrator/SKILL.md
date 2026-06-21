---
name: admin-crud-orchestrator
description: Build a full admin CRUD module in one guided execution. Trigger for new admin CRUD modules.
---

# Admin CRUD Orchestrator Skill

## Purpose

Build a complete Admin CRUD module from start to finish, fully aligned with the project architecture, rules, and development system.

This skill orchestrates:

- requirements collection
- feature analysis
- database design
- validation
- service layer
- controller
- views
- routes and sidebar
- translations
- factory and seeder
- optional export, statistics, file uploads, maps, soft deletes
- testing
- final validation

---

## When to Use

- When creating a new admin CRUD module
- When rebuilding or standardizing an existing CRUD module
- When the module requires full end-to-end implementation

---

## Core principles

- Collect all inputs before generating code.
- Validation belongs in Form Requests; business logic in Services; controllers stay thin; Blade stays presentation-only.
- Columns are the single source of truth.
- Reuse existing project patterns.

---

## Step 1: Collect Inputs

Required inputs:

- model name
- Arabic singular label
- Arabic plural label
- columns definition
- features enabled: soft deletes, multilingual, file uploads, map/location, statistics, export
- component status: Create / Exists / Skip
- routes and sidebar requirements
- export columns and types
- statistics cards and charts
- seed/factory requirements

Rules:

- Columns are the single source of truth.
- Do not assume extra columns.
- Ask follow-up questions for ambiguous requirements.

---

## Step 2: Analyze the CRUD

Use [`feature-analysis`](../feature-analysis/SKILL.md).

Output:

- CRUD scope summary
- affected layers
- implementation complexity
- dependencies

---

## Step 3: Design the Database Layer

Use [`database-design`](../database-design/SKILL.md).

Goals:

- derive schema strictly from columns
- define field types, nullable/default values, foreign keys
- support soft deletes and multilingual storage when enabled

Rules:

- do not add columns not in the input
- keep schema normalized
- define explicit relationships

---

## Step 4: Map the CRUD to the Module Structure

Use [`create-module`](../create-module/SKILL.md).

Output:

- file tree
- create/update/skip plan
- layer mapping

---

## Step 5: Implement Validation Layer

Requirements:

- create `StoreRequest` and `UpdateRequest` when needed
- derive rules from columns
- include file validation when uploads are enabled
- keep validation out of controllers

Rules:

- validation must be handled through Form Requests
- required, optional, nullable, enum, and relational rules must be explicit

---

## Step 6: Implement Service Layer

Requirements:

- create service classes for business logic
- separate create, update, delete, and complex index logic
- place upload handling, filtering, statistics preparation, and business operations in services

Rules:

- no business logic in controllers
- no business logic in Blade views
- keep services reusable and testable

---

## Step 7: Implement Controller Layer

Requirements:

- keep controllers thin
- orchestrate request → service → response/view
- pass all required variables to views

Rules:

- no heavy logic in controller
- no validation logic in controller
- no direct business rules in controller

---

## Step 8: Implement Views

Use:

- [`ui-page-build`](../ui-page-build/SKILL.md)
- [`../../../styles/admin-ui-standards.md`](../../../styles/admin-ui-standards.md)
- [`../../../templates/show-view-template.md`](../../../templates/show-view-template.md)
- [`../../../templates/table-view-template.md`](../../../templates/table-view-template.md)
- [`references/project-crud-conventions.md`](references/project-crud-conventions.md)

Required views when enabled: index, table partial, create, edit, show.

---

## Step 9: Implement Routes and Sidebar

Requirements:

- register CRUD routes in `routes/admin.php`
- register export route when export is enabled
- register sidebar/menu item when requested

Rules:

- route naming must follow project conventions
- sidebar config must follow existing project structure
- use translation keys when translations are enabled

See [`references/project-crud-conventions.md`](references/project-crud-conventions.md) for route and translation details.

---

## Step 10: Implement Translations

Required translation files:

- `lang/{locale}/admin/routes.php`
- `lang/{locale}/admin/inputs.php`
- `lang/{locale}/admin/main.php`

See [`references/project-crud-conventions.md`](references/project-crud-conventions.md) for required route keys.

---

## Step 11: Implement Factory and Seeder

Requirements:

- create factory when enabled
- create seeder when enabled
- register seeder in `DatabaseSeeder` when requested

See [`references/project-crud-conventions.md`](references/project-crud-conventions.md) for factory/seeder rules.

---

## Step 12: Implement Optional Features

When export, statistics/charts, file uploads, maps, soft deletes, or multilingual storage are enabled, see [`references/optional-features.md`](references/optional-features.md).

---

## Step 13: Add Testing

Use [`testing`](../testing/SKILL.md).

Required coverage when relevant:

- validation success/failure
- create/update/delete flow
- file upload behavior
- permissions/access
- export endpoint
- statistics-related behavior if logic is non-trivial

---

## Step 14: Finalize and Validate

Use:

- [`feature-finalization-and-validation`](../feature-finalization-and-validation/SKILL.md)
- [`../../../rules/22-code-review.mdc`](../../../rules/22-code-review.mdc)

Run guards in guard-pass mode:

- [`clean-code-guard`](../../../skills/guards/clean-code-guard/SKILL.md)
- [`test-guard`](../../../skills/guards/test-guard/SKILL.md)
- [`docs-guard`](../../../skills/guards/docs-guard/SKILL.md)

Fix confirmed critical/high findings, rerun, and repeat up to two cycles. Report persistent findings to the user.

See [`references/completion-checklist.md`](references/completion-checklist.md) for the final checklist.

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

- CRUD summary, file tree, create/update/skip matrix
- database, validation, service, controller, views summaries
- routes/sidebar, translations, factory/seeder, optional features summaries
- testing summary and final validation report

---

## Completion Standard

An Admin CRUD module is NOT complete unless all items in [`references/completion-checklist.md`](references/completion-checklist.md) are satisfied.
