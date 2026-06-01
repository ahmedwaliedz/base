# AI Development System

## Overview

This directory defines the AI-powered development system used by the Backend Team.

It standardizes how features are analyzed, implemented, reviewed, and delivered using:

- rules (what must be followed)
- skills (how tasks are executed)
- context (project understanding)
- workflows (execution order)
- templates (starting points)

The goal is to ensure:

- consistency across projects
- maintainable architecture
- predictable AI behavior
- production-ready output

---

## System Structure

```
.cursor/
├── rules/              # 21 mandatory development standards
├── skills/             # 19 task execution guides
│   ├── setup-phase/    # New project setup (3)
│   ├── development-phase/  # Feature implementation (13)
│   └── specialized/    # Advanced tasks (3)
├── context/            # 3 project-specific knowledge files
├── workflows/          # 3 execution sequences
└── templates/          # 5 reusable code starting points
```

---

## Quick Reference — How to Use This System

| I want to... | Use this | Prompt |
|---|---|---|
| **Build admin CRUD (fastest)** | `admin-crud-orchestrator` | "Use admin-crud-orchestrator skill for [Entity]" |
| **Build a complete feature** | `development-workflow` | "Use development-workflow to implement [Feature]" |
| **Fix a production bug** | `hotfix-workflow` | "Use hotfix-workflow to fix [Bug]" |
| **Start a new project** | `setup-workflow` | "Use setup-workflow to initialize project for [Domain]" |
| **Build an API endpoint** | `create-api-with-postman` | "Use create-api-with-postman skill for [Endpoint]" |
| **Build a UI page** | `ui-page-build` | "Use ui-page-build skill to create [Page] for [Module]" |
| **Write tests** | `testing` | "Use testing skill to test [Feature]" |
| **Refactor code** | `refactor` | "Use refactor skill on [File/Module]" |

---

## 📋 Rules Reference (21 files)

Rules auto-apply based on which files you're editing. **No prompt needed** — they're always active.

### Always-Applied Rules (00–08)

These 9 rules apply to all development:

#### `00-global-rules.mdc`
- **What:** Project identity, coding principles, i18n, and AI execution priorities
- **How to use:** Automatic (always applied)
- **Connected to:** All other rules (overrides conflicts), `context/project-context.md`
- **Key sections:** Project stack (Laravel 11, PHP 8.2+), SOLID principles, before-you-code checklist

#### `01-code-quality.mdc`
- **What:** Code standards — naming, readability, comments, style
- **How to use:** Automatic (applied to all code)
- **Connected to:** `16-testing-qa.mdc`, `22-code-review.mdc`, `07-api-postman-mcp-documentation-rules.mdc`
- **Key sections:** Naming conventions, PSR-12, complexity limits, comments policy

#### `02-architecture.mdc`
- **What:** Architecture patterns — service layer, thin controllers, dependency injection, admin dashboard backend rules
- **How to use:** Automatic (enforced on every skill)
- **Connected to:** `04-backend-rules.mdc`, skills in `development-phase/`, `feature-analysis.md`
- **Key sections:** Service-based architecture, layer responsibilities, coupling reduction, **Admin Dashboard Backend** (thin controllers, services, Form Requests, Blade restrictions, eager loading)

#### `03-frontend-rules.mdc`
- **What:** Frontend/UI standards — Blade, components, forms, responsive design, dark RTL dashboard
- **How to use:** Automatic (applied when building UI)
- **Connected to:** `ui-page-build.md`, Blade components in `resources/views/`
- **Key sections:** Blade practices, component structure, accessibility, CSS conventions, **Dark RTL Dashboard Rules** (RTL-safe CSS, table alignment, toggle state, form grid consistency, translation labels)

#### `04-backend-rules.mdc`
- **What:** Backend standards — controllers (thin), services, Form Requests, validation
- **How to use:** Automatic (applied to all backend code)
- **Connected to:** `backend-feature-implementation.md`, `08-custom-rbac.mdc`, `05-database-rules.mdc`
- **Key sections:** Controller structure, service layer, Form Request validation, response format

#### `05-database-rules.mdc`
- **What:** Database design — migrations, models, relationships, seeders, model conventions
- **How to use:** Automatic (applied when touching database)
- **Connected to:** `12-database-eloquent.mdc`, `database-design.md`, migrations in `database/`
- **Key sections:** Migration naming, model structure, indexes, soft deletes, relationship conventions, **Seeders** (migration-constraint matching, upload-trait caveat, batch translations), **Model conventions** ($casts, RELATIONS constant, eager loading)

#### `06-collaboration-rules.mdc`
- **What:** Team collaboration — commits, code review, documentation, communication
- **How to use:** Automatic (enforced in development workflow)
- **Connected to:** `22-code-review.mdc`, `feature-finalization-and-validation.md`
- **Key sections:** Git conventions, PR standards, review process, documentation expectations

#### `07-api-postman-mcp-documentation-rules.mdc`
- **What:** API documentation — endpoint specs, Postman examples, response format, error codes
- **How to use:** Automatic (applied when creating APIs)
- **Connected to:** `create-api-with-postman.md`, `13-api-integration.mdc`
- **Key sections:** Endpoint documentation, response schema, Postman example format, versioning

#### `08-custom-rbac.mdc` ⭐ **CRITICAL**
- **What:** RBAC protection — enforces the custom permission system, prevents refactoring or replacement
- **How to use:** Automatic + STOP before any permission-related code
- **Connected to:** `04-backend-rules.mdc`, `backend-feature-implementation.md`, `auth-permissions.md`
- **Key sections:** RBAC tables (admins, roles, permissions), middleware-owned permission checks, route name = permission string matching, bypass rules, **exception routes** (`exceptedRoutesFromRoles()`), new `admin.*` route requirements, RBAC audit checklist

---

### Contextual Rules (10–22)

Applied based on which files you're editing. Use their skills/workflows to trigger them.

#### `10-core-laravel.mdc`
- **What:** Core Laravel patterns — routing, middleware, containers, providers, Blade, validation, queues, events
- **How to use:** Auto-applied when editing `routes/`, `app/Http/Controllers/`, `app/Providers/`, etc.
- **Connected to:** All Laravel-related skills, `04-backend-rules.mdc`
- **Applies to:** Request lifecycle, service container, dependency injection, routing, middleware

#### `11-php-ecosystem.mdc`
- **What:** PHP ecosystem — Composer, PSR standards, typed properties, enums, match expressions, modern PHP patterns
- **How to use:** Auto-applied when editing `composer.json` or PHP files
- **Connected to:** All skills, SOLID/GoF patterns
- **Applies to:** Type declarations, PHP 8.2+ features (readonly, enums), SOLID principles

#### `12-database-eloquent.mdc`
- **What:** Eloquent ORM — models, queries, relationships, accessors/mutators, scopes, eager loading
- **How to use:** Auto-applied when editing models or database code
- **Connected to:** `05-database-rules.mdc`, `database-design.md`, `12-database-eloquent.mdc`
- **Applies to:** Query optimization, N+1 prevention, relationship definition, model scopes

#### `13-api-integration.mdc`
- **What:** API integrations — external APIs, webhooks, request/response mapping, error handling
- **How to use:** Auto-applied when working on integrations
- **Connected to:** `create-api-with-postman.md`, `integration.md`, services
- **Applies to:** HTTP client usage, webhook parsing, retry logic, request transformation

#### `14-frontend-integration.mdc`
- **What:** Frontend integration — JavaScript, Vite, asset bundling, interactivity, form submission
- **How to use:** Auto-applied when editing frontend files
- **Connected to:** `ui-page-build.md`, `resources/views/`, components
- **Applies to:** Blade component interactivity, Alpine/JavaScript patterns, form handling

#### `15-devops-deployment.mdc`
- **What:** DevOps & deployment — environment config, migrations, caching, performance, server setup
- **How to use:** Auto-applied when configuring environments or deployment
- **Connected to:** `.env` files, `config/`, AppServiceProvider
- **Applies to:** Production hardening, caching strategies, background job setup

#### `16-testing-qa.mdc`
- **What:** Testing standards — unit tests, feature tests, assertion patterns, test organization
- **How to use:** Auto-applied when writing tests
- **Connected to:** `testing.md`, `tests/` directory, `01-code-quality.mdc`
- **Applies to:** Test structure, assertion clarity, test data setup, coverage targets

#### `17-architecture-design.mdc`
- **What:** Design patterns — SOLID, GoF patterns (factory, strategy, observer, adapter), composition
- **How to use:** Auto-applied when designing new structures
- **Connected to:** `02-architecture.mdc`, service/controller design
- **Applies to:** Loose coupling, extensibility, polymorphism, abstraction

#### `18-security.mdc`
- **What:** Security — OWASP Top 10, CSRF/XSS prevention, encryption, rate limiting, secure headers, CORS
- **How to use:** Auto-applied when working with auth, APIs, or sensitive data
- **Connected to:** `08-custom-rbac.mdc`, `auth-permissions.md`, middleware
- **Applies to:** Input validation, output escaping, cryptography, request authentication

#### `19-performance.mdc`
- **What:** Performance — query optimization, caching, eager loading, lazy loading, asset optimization
- **How to use:** Auto-applied during implementation and optimization
- **Connected to:** `12-database-eloquent.mdc`, services, `refactor.md`
- **Applies to:** N+1 query prevention, cache strategies, response time optimization

#### `20-tooling.mdc`
- **What:** Development tools — Composer, Artisan, Laravel Pint, linters, package management
- **How to use:** Auto-applied when managing dependencies or using CLI tools
- **Connected to:** `composer.json`, scripts, CI/CD
- **Applies to:** Package versioning, script configuration, dev environment setup

#### `21-ecosystem.mdc`
- **What:** Ecosystem overview — Laravel packages (Sanctum, Translatable, Spatie), integration patterns
- **How to use:** Auto-applied when using framework packages
- **Connected to:** `project-context.md`, key packages (Sanctum, Media Library, Translatable)
- **Applies to:** Package conventions, trait usage, service provider configuration

#### `22-code-review.mdc`
- **What:** Code review standards — review checklist, architecture compliance, test coverage, documentation
- **How to use:** Applied during validation step of workflows
- **Connected to:** `feature-finalization-and-validation.md`, `01-code-quality.mdc`
- **Applies to:** Pre-commit validation, completeness verification, standards compliance

---

## 🎯 Skills Reference (19 files)

Skills are **task execution guides**. Use them by triggering with a prompt or workflow.

### Setup Phase (3 skills)

Used when initializing a new project from the base project.

#### `initialize-from-base-project.md`
- **What:** Initializes a new project by cloning the base project structure, updating configs, and preparing for customization
- **Trigger:** "Use initialize-from-base-project skill for [ProjectName]"
- **Connected to:** `setup-workflow.md`, `adapt-base-project-to-new-domain.md`, `project-context.md`
- **Part of:** Step 1 in `setup-workflow`
- **Output:** Base project initialized, configs updated, ready for domain adaptation

#### `adapt-base-project-to-new-domain.md`
- **What:** Adapts the base project to a specific business domain — updates module names, database schema, business logic
- **Trigger:** "Use adapt-base-project-to-new-domain skill to adapt base for [Domain]"
- **Connected to:** `setup-workflow.md`, `project-context.md`, `domain-context.md`
- **Part of:** Step 2 in `setup-workflow`
- **Output:** Domain-specific modules, database schema, services configured

#### `project-readiness-review.md`
- **What:** Reviews the initialized project against standards — checks architecture, database, configs, documentation
- **Trigger:** "Use project-readiness-review skill"
- **Connected to:** `setup-workflow.md`, all rules, `project-context.md`
- **Part of:** Step 3 in `setup-workflow`
- **Output:** Readiness report, issues list, ready-to-develop decision

---

### Development Phase (13 skills)

Used during feature implementation. Most commonly used skills.

#### `feature-analysis.md`
- **What:** Analyzes a feature — defines goals, impacted modules, schema needs, validation rules, edge cases
- **Trigger:** "Use feature-analysis skill to analyze [Feature]"
- **Connected to:** `development-workflow.md`, `02-architecture.mdc`, `05-database-rules.mdc`, `domain-context.md`
- **Part of:** Step 1 in `development-workflow`
- **Input:** Feature description or user story
- **Output:** Structured analysis doc with goals, scope, schema, validation, edge cases

#### `feature-to-module-execution.md`
- **What:** Maps a feature to modules — decides whether to extend an existing module or create a new one
- **Trigger:** "Use feature-to-module-execution skill to map [Feature]"
- **Connected to:** `development-workflow.md`, `create-module.md`, `02-architecture.mdc`, `project-context.md`
- **Part of:** Step 2 in `development-workflow`
- **Input:** Feature analysis output
- **Output:** Module mapping, layer assignment, execution order

#### `create-module.md`
- **What:** Creates a full module structure — models, controllers, services, routes, views, tests
- **Trigger:** "Use create-module skill to create [ModuleName] module"
- **Connected to:** `feature-to-module-execution.md`, `backend-feature-implementation.md`, templates
- **Part of:** Alternative to Step 3 when new module is needed
- **Output:** Complete module structure with all layers

#### `database-design.md`
- **What:** Designs database schema — creates migrations, models, relationships, seeders
- **Trigger:** "Use database-design skill for [Entity]"
- **Connected to:** `05-database-rules.mdc`, `12-database-eloquent.mdc`, `feature-analysis.md`
- **Used alongside:** `backend-feature-implementation.md`
- **Output:** Migrations, models with relationships, seeders

#### `backend-feature-implementation.md` ⭐ **Most Used**
- **What:** Implements backend logic — creates services, Form Requests, thin controllers, RBAC checks
- **Trigger:** "Use backend-feature-implementation skill to implement [Feature] backend"
- **Connected to:** `development-workflow.md`, `04-backend-rules.mdc`, `08-custom-rbac.mdc`, service/form-request templates
- **Part of:** Step 3 in `development-workflow`
- **Output:** Service class, FormRequest, Controller, RBAC middleware checks

#### `create-api-with-postman.md` ⭐ **Must Use for APIs**
- **What:** Creates API endpoints with documentation — routes, controllers, resources, Postman examples
- **Trigger:** "Use create-api-with-postman skill to create [Endpoint] API"
- **Connected to:** `07-api-postman-mcp-documentation-rules.mdc`, `13-api-integration.mdc`, `api-endpoint-template.md`
- **Part of:** Step 4 in `development-workflow` (if API needed)
- **Output:** Routes, JsonResource, Postman examples, API documentation

#### `ui-page-build.md`
- **What:** Builds UI pages — creates Blade views, forms, lists, components, form handling
- **Trigger:** "Use ui-page-build skill to create [Page] for [Module]"
- **Connected to:** `03-frontend-rules.mdc`, `14-frontend-integration.mdc`, Blade components
- **Part of:** Step 4 in `development-workflow` (if UI needed)
- **Output:** Blade views, form components, list templates, page structure

#### `admin-crud-orchestrator.md` ⭐⭐⭐ **FASTEST PATH**
- **What:** Generates complete admin CRUD module in one shot — migration, model, service, controller, views, routes, tests
- **Trigger:** "Use admin-crud-orchestrator skill for [Entity]"
- **Connected to:** All development-phase skills, `backend-feature-implementation.md`, `testing.md`
- **Replaces:** Steps 2–5 of `development-workflow` for CRUD modules
- **Output:** Complete, tested CRUD module ready for use

#### `laravel-feature-end-to-end.md`
- **What:** Implements a complete feature from analysis to validation in one guided execution
- **Trigger:** "Use laravel-feature-end-to-end skill to implement [Feature]"
- **Connected to:** All development-phase skills, `development-workflow.md`, templates
- **Replaces:** All 6 steps of `development-workflow`
- **Output:** Complete feature ready for deployment

#### `testing.md`
- **What:** Writes tests — feature tests, unit tests, assertion patterns, test organization
- **Trigger:** "Use testing skill to test [Feature/Service]"
- **Connected to:** `16-testing-qa.mdc`, `test-template.md`, services/controllers being tested
- **Part of:** Step 5 in `development-workflow`
- **Input:** Implemented feature or service
- **Output:** Feature test class, test cases covering happy path, validation failures, edge cases

#### `feature-finalization-and-validation.md`
- **What:** Validates feature completeness — verifies architecture, code quality, tests, documentation
- **Trigger:** "Use feature-finalization-and-validation skill to validate [Feature]"
- **Connected to:** `22-code-review.mdc`, `01-code-quality.mdc`, all rules
- **Part of:** Step 6 in `development-workflow`
- **Input:** Implemented and tested feature
- **Output:** Validation report, issues list, ready-to-merge decision

#### `refactor.md`
- **What:** Refactors existing code — improves structure, reduces duplication, optimizes performance
- **Trigger:** "Use refactor skill on [File/Module]"
- **Connected to:** `02-architecture.mdc`, `17-architecture-design.mdc`, `19-performance.mdc`
- **When to use:** During code review or optimization pass
- **Input:** File/module to refactor
- **Output:** Refactored code, improvements documented

#### `bug-fixing.md`
- **What:** Fixes bugs systematically — identifies root cause, applies minimal fix, validates no regression
- **Trigger:** "Use bug-fixing skill to fix [Bug Description]"
- **Connected to:** `hotfix-workflow.md`, all rules, testing
- **When to use:** When fixing production or staging bugs
- **Input:** Bug description, expected vs actual behavior
- **Output:** Root cause identified, fix applied, regression tested

---

### Specialized (3 skills)

Used for advanced or domain-specific tasks.

#### `auth-permissions.md`
- **What:** Sets up authentication and permissions — RBAC, gates, policies, Sanctum tokens
- **Trigger:** "Use auth-permissions skill to setup [Module] permissions"
- **Connected to:** `08-custom-rbac.mdc`, `18-security.mdc`, middleware
- **When to use:** When building modules with access control
- **Output:** Permission entries, RBAC middleware, auth checks in services/controllers

#### `file-upload.md`
- **What:** Handles file uploads — Spatie Media Library, upload trait, validation, storage
- **Trigger:** "Use file-upload skill to add [Image/Document] upload to [Module]"
- **Connected to:** `21-ecosystem.mdc`, `app/Traits/Upload` trait, media library config
- **When to use:** When module needs file/image upload
- **Output:** Upload trait, Media Library configuration, form components, validation

#### `integration.md`
- **What:** Integrates external services — payment gateways, SMS providers, email services, webhooks
- **Trigger:** "Use integration skill to integrate [Service] for [Purpose]"
- **Connected to:** `13-api-integration.mdc`, `18-security.mdc`, services
- **When to use:** When adding third-party integrations
- **Output:** Service class, webhook handlers, API key configuration, error handling

---

## 🔄 Workflows Reference (3 files)

Workflows orchestrate multiple skills in the correct sequence.

#### `setup-workflow.md`
- **What:** Sequences project initialization — initializes, adapts to domain, reviews readiness
- **How to use:** "Use setup-workflow to initialize project for [Domain]"
- **Triggers these skills:** `initialize-from-base-project.md` → `adapt-base-project-to-new-domain.md` → `project-readiness-review.md`
- **Connected to:** Context files, all rules
- **When to use:** When starting a completely new project
- **Completion:** Project ready for development

#### `development-workflow.md`
- **What:** Sequences feature implementation — analysis → module mapping → backend → API/UI → testing → validation
- **How to use:** "Use development-workflow to implement [Feature]"
- **Triggers these skills in order:**
  1. `feature-analysis.md` (analyze)
  2. `feature-to-module-execution.md` or `create-module.md` (map or create)
  3. `backend-feature-implementation.md` (backend)
  4. `create-api-with-postman.md` or `ui-page-build.md` (API or UI)
  5. `testing.md` (test)
  6. `feature-finalization-and-validation.md` (validate)
- **Connected to:** All development rules, all development skills
- **When to use:** For any new feature or significant feature extension
- **Completion:** Feature tested, validated, ready to merge

#### `hotfix-workflow.md`
- **What:** Sequences urgent bug fixes — identify problem → find root cause → apply minimal fix → validate regression
- **How to use:** "Use hotfix-workflow to fix [Bug]"
- **Triggers these skills:** `bug-fixing.md` (identify + fix) → `testing.md` (validate)
- **Connected to:** All rules, `feature-finalization-and-validation.md` for final review
- **When to use:** For production/staging bugs blocking critical flows
- **Completion:** Root cause fixed, regression tested, ready to deploy

---

## 📚 Context Reference (3 files)

Context files provide project-specific knowledge. They're **passive reference** — no prompt needed.

#### `project-context.md`
- **What:** Project identity and structure — tech stack, architecture, database schema, modules, middleware, constraints
- **Auto-loaded:** Yes, by all skills and rules
- **Connected to:** All rules, all skills, `00-global-rules.mdc`
- **Update when:** Framework version changes, new packages added, major architecture changes
- **Key sections:** Tech stack, project structure, key packages, database schema, middleware aliases, API response format, security hardening

#### `domain-context.md`
- **What:** Business logic and domain models — entities, relationships, workflows, rules, constraints
- **Auto-loaded:** Yes, by `feature-analysis.md`, `create-module.md`, and other analysis skills
- **Connected to:** Domain-specific skills, business rule enforcement
- **Update when:** New entities added, business rules change, workflows updated
- **Key sections:** Entity relationships, business workflows, domain constraints, validation rules, integration points

#### `team-context.md`
- **What:** Development style and team conventions — naming, code review expectations, communication, preferences
- **Auto-loaded:** Yes, by all rules and skills
- **Connected to:** `01-code-quality.mdc`, `06-collaboration-rules.mdc`, code review standards
- **Update when:** Team preferences change, new conventions established
- **Key sections:** Naming conventions, architecture preferences, file organization, code review expectations, communication standards

---

## 🏗️ Templates Reference (5 files)

Templates are **reusable starting structures** for common code patterns. Used by skills automatically.

#### `service-template.md`
- **What:** Template for Service classes — method structure, dependency injection, error handling
- **Used by:** `backend-feature-implementation.md`, `create-module.md`, `admin-crud-orchestrator.md`
- **Auto-applied:** When creating services
- **Key sections:** Constructor, public methods, protected methods, error handling

#### `form-request-template.md`
- **What:** Template for Form Request validation classes — rules, authorization, custom messages
- **Used by:** `backend-feature-implementation.md`, `create-module.md`
- **Auto-applied:** When creating Form Requests
- **Key sections:** Authorization, validation rules, custom messages, attributes, error handling

#### `controller-template.md`
- **What:** Template for thin Controllers — dependency injection, method structure, orchestration (no business logic)
- **Used by:** `backend-feature-implementation.md`, `create-module.md`, `admin-crud-orchestrator.md`
- **Auto-applied:** When creating controllers
- **Key sections:** Constructor, thin controller methods, service delegation, response format

#### `api-endpoint-template.md`
- **What:** Template for API endpoints — route structure, controller method, JsonResource, response format
- **Used by:** `create-api-with-postman.md`, `create-module.md`
- **Auto-applied:** When creating API endpoints
- **Key sections:** Route definition, controller method, JsonResource class, error responses, Postman example

#### `test-template.md`
- **What:** Template for Feature Tests — test structure, setup, assertions, naming conventions
- **Used by:** `testing.md`, `admin-crud-orchestrator.md`
- **Auto-applied:** When creating tests
- **Key sections:** Test class structure, setUp method, test method naming, assertions, mocking patterns

---

## 🎯 Common Combinations — How to Use Multiple Files Together

Use these patterns when building features. Each combination shows which rules, skills, and templates work together.

### Combination 1: Build Admin CRUD (Fastest Path)

**When:** Building a simple Create-Read-Update-Delete module with admin panel.

**Files involved:**
- Workflow: `development-workflow.md` OR `admin-crud-orchestrator.md` (single skill, one step)
- Skills: `admin-crud-orchestrator.md` (does everything)
- Rules applied automatically: `00`, `01`, `02`, `04`, `05`, `06`, `07`, `08`, `10`, `11`, `12`
- Context: `project-context.md`, `domain-context.md`, `team-context.md`
- Templates: `service-template.md`, `form-request-template.md`, `controller-template.md`, `test-template.md`

**Prompt:**
```
Use admin-crud-orchestrator skill to create a full admin CRUD module for [Entity]

Include:
- Database migration
- Model with relationships
- Service with RBAC checks
- Controller (thin)
- Form Request validation
- Blade views (list, create, edit, show, delete)
- Routes
- Feature tests
```

**Output:** Complete CRUD module, tested, documented, ready to use.

**Estimated time:** 15–30 minutes per skill execution

---

### Combination 2: Build Feature with API

**When:** Building a feature with REST API + admin panel UI.

**Workflow:** Follow `development-workflow.md` (all 6 steps)

**Files involved:**

| Step | Skills | Rules | Templates |
|------|--------|-------|-----------|
| 1 | `feature-analysis.md` | 02, 05, domain-context | — |
| 2 | `feature-to-module-execution.md` | 02 | — |
| 3 | `backend-feature-implementation.md` | 04, 08, 10, 12 | service, form-request, controller |
| 4a | `create-api-with-postman.md` | 07, 13, 18 | api-endpoint |
| 4b | `ui-page-build.md` | 03, 14 | — |
| 5 | `testing.md` | 16 | test |
| 6 | `feature-finalization-and-validation.md` | 01, 22 | — |

**Prompt:**
```
Use development-workflow to implement [Feature]

Follow all 6 steps:
1. Analyze the feature (goals, schema, API/UI needs)
2. Map to module (new or existing)
3. Implement backend (service, FormRequest, controller)
4. Create API with Postman examples
5. Create admin UI page
6. Write tests and validate
```

**Output:** Complete feature with API + UI, tested, documented.

**Estimated time:** 1–2 hours

---

### Combination 3: Fix Production Bug (Hotfix)

**When:** Fixing an urgent bug in production/staging.

**Workflow:** Follow `hotfix-workflow.md` (focused, minimal)

**Files involved:**

| Step | Skills | Rules | Templates |
|------|--------|-------|-----------|
| 1 | `bug-fixing.md` (identify problem) | 00, 02, 04 | — |
| 2 | `bug-fixing.md` (find root cause) | 02, 04 | — |
| 3 | `bug-fixing.md` (apply minimal fix) | 00, 04 | — |
| 4 | `testing.md` (validate no regression) | 16 | test |
| 5 | `feature-finalization-and-validation.md` (final review) | 01, 22 | — |

**Prompt:**
```
Use hotfix-workflow to fix [Bug Description]

Follow these steps:
1. Identify the problem clearly
2. Find the root cause
3. Apply the smallest safe fix
4. Validate regression risk
5. Final review
```

**Output:** Minimal fix, tested, regression-verified, ready to deploy.

**Estimated time:** 30–60 minutes

---

### Combination 4: Start New Project

**When:** Initializing a completely new project from the base.

**Workflow:** Follow `setup-workflow.md` (all 3 steps)

**Files involved:**

| Step | Skills | Rules | Context |
|------|--------|-------|---------|
| 1 | `initialize-from-base-project.md` | 00, 02, 04, 05, 10 | project-context |
| 2 | `adapt-base-project-to-new-domain.md` | 00, 02, 04, 05 | project-context, domain-context |
| 3 | `project-readiness-review.md` | All rules | All context |

**Prompt:**
```
Use setup-workflow to initialize project for [BusinessDomain]

Steps:
1. Initialize from base project (clone structure)
2. Adapt to [Domain] (update modules, schema, logic)
3. Review project readiness (verify all standards)
```

**Output:** New project initialized, domain-specific, ready for feature development.

**Estimated time:** 2–3 hours

---

### Combination 5: Refactor & Optimize

**When:** Improving code quality and performance without changing functionality.

**Files involved:**
- Skill: `refactor.md`
- Rules applied: `01` (code quality), `02` (architecture), `17` (design patterns), `19` (performance)
- Templates: Affected templates (service, controller, etc.)

**Prompt:**
```
Use refactor skill on [File/Module]

Focus on:
- Reducing duplication
- Improving naming clarity
- Optimizing N+1 queries
- Applying design patterns
- Simplifying complex logic
```

**Output:** Refactored code, tests passing, performance improved.

**Estimated time:** 30–90 minutes depending on scope

---

### Combination 6: Add File Upload to Module

**When:** Adding image/document upload to an existing module.

**Files involved:**
- Skill: `file-upload.md`
- Rules applied: `21` (ecosystem), `18` (security), `04` (backend)
- Connected to: Spatie Media Library, Upload trait in `app/Traits/`

**Prompt:**
```
Use file-upload skill to add [image/document] upload to [Module]

Include:
- Spatie Media Library integration
- File validation (type, size)
- Form component for upload
- Security checks
```

**Output:** Upload feature integrated, validated, tested.

**Estimated time:** 20–40 minutes

---

### Combination 7: Setup Permissions for Module

**When:** Implementing role-based access control for a module.

**Files involved:**
- Skill: `auth-permissions.md`
- Rules applied: `08` (RBAC), `04` (backend), `18` (security)
- Connected to: `permissions` table, `roles` table, `CheckRolePermission` middleware

**Prompt:**
```
Use auth-permissions skill to setup [Module] permissions

Define:
- Permissions needed (list, create, edit, delete, etc.)
- Roles that have each permission
- Middleware checks in routes
- Service-layer authorization
```

**Output:** Permissions created, RBAC checks implemented, documented.

**Estimated time:** 20–30 minutes

---

### Combination 8: Integrate External Service

**When:** Adding payment gateway, SMS provider, or other external API.

**Files involved:**
- Skill: `integration.md`
- Rules applied: `13` (API integration), `18` (security), `04` (backend)
- Connected to: Services, webhooks, `app/Services/Integration/`

**Prompt:**
```
Use integration skill to integrate [Service] for [Purpose]

Include:
- API key configuration
- Service class with API calls
- Error handling and retries
- Webhook handler (if needed)
- Tests
```

**Output:** Integration implemented, tested, documented.

**Estimated time:** 40–90 minutes

---

## How Combinations Work

**The key principle:** Rules apply automatically based on which files you edit. Skills are **triggered by you**. Workflows orchestrate skills in the right sequence.

**Example workflow:**
```
User: "Use development-workflow to implement User Profile feature"
       ↓
Workflow triggers Step 1: "Use feature-analysis skill"
       ↓
Rules auto-apply: 02-architecture, 05-database-rules, etc.
       ↓
Context loads: project-context, domain-context
       ↓
Skill generates: Analysis document
       ↓
Workflow triggers Step 2: "Use feature-to-module-execution skill"
       ↓
Rule auto-applies: 02-architecture
       ↓
Skill generates: Module mapping
       ↓
... continues through steps 3–6 ...
```

**Multiple files working together:**
- **Rules** enforce standards (passive)
- **Context** provides knowledge (passive)
- **Templates** structure code (passive)
- **Skills** execute tasks (active — you trigger them)
- **Workflows** orchestrate skills (you trigger the workflow, it orchestrates)

---

## Core Principles

- Reuse existing patterns before creating new ones
- Keep controllers thin - business logic in services
- Use Form Requests for validation
- Keep Blade for presentation only
- Always document APIs
- Always test critical flows
- Never bypass RBAC protection
- Prefer consistency with existing codebase

---

## Completion Standards

A feature/task is NOT complete unless:

- Architecture rules are followed
- RBAC permissions are checked (if applicable)
- Code is tested (critical paths)
- APIs are documented (if applicable)
- Validation passes all checks
- No rule violations remain

---

## Notes

- This system is designed for **Laravel 11, PHP 8.2+**
- Do not bypass rules or workflows
- Do not introduce new patterns without strong justification
- **RBAC system (`08-custom-rbac.mdc`) is CRITICAL — protect it**
- Consistency is more important than creativity
- All 51 files work together — they're not independent

---

## Compact Entry Points

Before diving into the full file tree, start with these two root-level documents. They route you to the smallest relevant set of `.cursor` files by task category.

| Document | When to use |
|----------|-------------|
| `REVIEW_CONTEXT.md` | Code review and PR audit sessions — routing table by changed area |
| `CODE_WRITING_CONTEXT.md` | Implementation sessions — routing table by task area |

Both files tell agents to open deeper `.cursor` files only when the task matches the routing rule, keeping token usage efficient.

---

## Goal

Turn development into a **predictable, scalable, and high-quality system** powered by AI and team standards.

Use this system, and every feature will follow the same proven patterns, meet the same standards, and integrate seamlessly with existing code.
