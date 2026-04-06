# AI Development System

## Overview

This directory defines the AI-powered development system used by the Backend Team.

It standardizes how features are analyzed, implemented, reviewed, and delivered using:

* rules (what must be followed)
* skills (how tasks are executed)
* context (project understanding)
* workflows (execution order)
* templates (starting points)

The goal is to ensure:

* consistency across projects
* maintainable architecture
* predictable AI behavior
* production-ready output

---

## System Structure

.cursor/
├── rules/
├── skills/
├── context/
├── workflows/
└── templates/

---

## 1. Rules

Location:
/rules

Defines mandatory development standards.

Examples:

* architecture rules (services, validation, controllers)
* API response format
* database rules
* code quality rules

Rules are strict and must always be followed.

---

## 2. Skills

Location:
/skills

Defines how tasks are executed.

### Setup Phase

Used when starting a new project from the base project:

* initialize-from-base-project
* adapt-base-project-to-new-domain
* project-readiness-review

### Development Phase

Used during feature implementation:

* feature-analysis
* feature-to-module-execution
* backend-feature-implementation
* api-end-to-end-execution
* create-api-with-postman
* testing
* feature-finalization-and-validation

### Specialized

Used for advanced or specific tasks:

* auth-permissions
* file-upload
* integration

---

## 3. Context

Location:
/context

Defines project-specific knowledge.

Includes:

* project-context (overall project)
* domain-context (business logic)
* team-context (development style)

Context ensures AI decisions align with the real project.

---

## 4. Workflows

Location:
/workflows

Defines execution sequences.

### Available Workflows:

* setup-workflow → for new projects
* development-workflow → for feature implementation
* hotfix-workflow → for urgent fixes

Workflows ensure tasks are executed in the correct order.

---

## 5. Templates

Location:
/templates

Provides reusable starting structures.

Examples:

* service template
* form request template
* controller template
* API endpoint template
* test template

Templates ensure consistency and speed.

---

## How to Use This System

### Starting a New Project

Follow:

1. setup-workflow
2. initialize-from-base-project
3. adapt-base-project-to-new-domain
4. project-readiness-review

---

### Building a Feature

Follow:

1. feature-analysis
2. feature-to-module-execution
3. backend-feature-implementation
4. api-end-to-end-execution (if API)
5. create-api-with-postman (if needed)
6. testing
7. feature-finalization-and-validation

---

### Fixing a Bug

Follow:

1. hotfix-workflow
2. bug-fixing
3. testing
4. validation

---

## Core Principles

* Reuse existing patterns before creating new ones
* Keep controllers thin
* Keep business logic in services
* Use Form Requests for validation
* Follow project response structure
* Keep Blade for presentation only
* Always document APIs
* Always test critical flows

---

## Completion Standards

A task is not complete unless:

* it follows architecture rules
* it is tested
* it is documented (for APIs)
* it passes validation and review

---

## Notes

* This system is designed to work with a base Laravel project.
* Do not bypass rules or workflows.
* Do not introduce new patterns without strong justification.
* Consistency is more important than creativity.

---

## Goal

Turn development into a predictable, scalable, and high-quality system powered by AI and team standards.
