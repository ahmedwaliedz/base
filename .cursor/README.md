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

.cursor/
├── rules/              # 23 mandatory development standards
├── skills/             # Task execution guides
│   ├── setup-phase/    # New project setup
│   ├── development-phase/  # Feature implementation
│   └── specialized/    # Advanced tasks
├── context/            # Project-specific knowledge
├── workflows/          # Execution sequences
└── templates/          # Reusable code starting points

---

## 1. Rules (23 files)

Location: `/rules`

Defines mandatory development standards. Rules are strict and must always be followed.

| # | File | Purpose |
|---|------|---------|
| 00 | global-rules.mdc | Project stack, general principles, i18n, tooling |
| 01 | code-quality.mdc | Code quality standards |
| 02 | architecture.mdc | Architecture patterns |
| 03 | frontend-rules.mdc | Frontend/UI standards |
| 04 | backend-rules.mdc | Backend standards |
| 05 | database-rules.mdc | Database design rules |
| 06 | collaboration-rules.mdc | Team collaboration |
| 07 | api-postman-mcp-documentation-rules.mdc | API documentation |
| 10 | core-laravel.mdc | Laravel core |
| 11 | php-ecosystem.mdc | PHP ecosystem |
| 12 | database-eloquent.mdc | Eloquent ORM |
| 13 | api-integration.mdc | API integrations |
| 14 | frontend-integration.mdc | Frontend integrations |
| 15 | devops-deployment.mdc | DevOps & deployment |
| 16 | testing-qa.mdc | Testing standards |
| 17 | architecture-design.mdc | Design patterns |
| 18 | security.mdc | Security |
| 19 | performance.mdc | Performance |
| 20 | tooling.mdc | Development tools |
| 21 | ecosystem.mdc | Ecosystem |
| 22 | code-review.mdc | Code review |

---

## 2. Skills

Location: `/skills`

Defines how tasks are executed.

### Setup Phase (`/skills/setup-phase`)

Used when starting a new project from the base project:

- `initialize-from-base-project` - Initialize new project from base
- `adapt-base-project-to-new-domain` - Adapt base to new domain
- `project-readiness-review` - Review project readiness

### Development Phase (`/skills/development-phase`)

Used during feature implementation:

| Skill | Purpose |
|-------|---------|
| `feature-analysis` | Analyze feature goals, impacted modules, schema, validation |
| `feature-to-module-execution` | Map feature to existing/new module |
| `create-module` | Create full module structure |
| `database-design` | Design database schema |
| `backend-feature-implementation` | Implement backend logic |
| `create-api-with-postman` | API with Postman examples |
| `ui-page-build` | Build Blade UI pages |
| `admin-crud-orchestrator` | Full admin CRUD module generation |
| `laravel-feature-end-to-end` | Complete feature implementation from analysis to validation |
| `testing` | Write tests |
| `feature-finalization-and-validation` | Final validation |
| `refactor` | Refactor existing code |
| `bug-fixing` | Fix bugs |

### Specialized (`/skills/specialized`)

Used for advanced or specific tasks:

- `auth-permissions` - Authentication & permissions
- `file-upload` - File upload handling
- `integration` - External integrations

---

## 3. Context

Location: `/context`

Defines project-specific knowledge. Ensures AI decisions align with the real project.

| File | Purpose |
|------|---------|
| `project-context.md` | Project overview, tech stack, architecture, structure |
| `domain-context.md` | Business logic, domain models |
| `team-context.md` | Development style, team conventions |

---

## 4. Workflows

Location: `/workflows`

Defines execution sequences.

| Workflow | Purpose |
|----------|---------|
| `setup-workflow.md` | New project initialization |
| `development-workflow.md` | Feature implementation |
| `hotfix-workflow.md` | Urgent bug fixes |

---

## 5. Templates

Location: `/templates`

Provides reusable starting structures.

| Template | Purpose |
|----------|---------|
| `service-template.md` | Service class |
| `form-request-template.md` | Form Request validation |
| `controller-template.md` | Controller |
| `api-endpoint-template.md` | API endpoint |
| `test-template.md` | Test file |

---

## How to Use This System

### Admin CRUD Module (Fastest)

Use when building full CRUD with admin panel:

1. `admin-crud-orchestrator` - Complete CRUD generation

### Building a Feature

1. `feature-analysis` - Analyze the feature
2. `feature-to-module-execution` or `create-module` - Map to module
3. `backend-feature-implementation` - Implement backend
4. `create-api-with-postman` - If API (design + Postman docs)
5. `ui-page-build` - If UI
6. `testing` - Write tests
7. `feature-finalization-and-validation` - Validate

### Starting a New Project

1. `setup-workflow`
2. `initialize-from-base-project`
3. `adapt-base-project-to-new-domain`
4. `project-readiness-review`

### Fixing a Bug

1. `hotfix-workflow`
2. `bug-fixing`
3. `testing`
4. Validation

---

## Core Principles

- Reuse existing patterns before creating new ones
- Keep controllers thin - business logic in services
- Use Form Requests for validation
- Keep Blade for presentation only
- Always document APIs
- Always test critical flows

---

## Completion Standards

A task is NOT complete unless:

- Architecture rules are followed
- Code is tested
- APIs are documented
- Validation and review pass

---

## Notes

- This system is designed for Laravel 11, PHP 8.2+
- Do not bypass rules or workflows
- Do not introduce new patterns without strong justification
- Consistency is more important than creativity

---

## Goal

Turn development into a predictable, scalable, and high-quality system powered by AI and team standards.
