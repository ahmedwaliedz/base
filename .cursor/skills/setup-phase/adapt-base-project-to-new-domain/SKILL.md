---
name: adapt-base-project-to-new-domain
description: Adapt the initialized base project to a new domain. Trigger after initialization and before the first feature.
---

# Adapt Base Project to New Domain Skill

## Purpose
Adapt the existing base project to fit the new business domain.

This skill transforms a generic or shared base project into a domain-specific system by:
- defining domain entities
- structuring modules
- customizing roles and permissions
- aligning naming and architecture with the new domain

---

## When to Use
- After initializing a project from the base project
- Before starting feature implementation
- When adapting the system to a new business domain
- When converting a generic structure into domain-specific modules

---

## Core Principle
- Adapt, do not rebuild.
- Keep the base architecture intact.
- Apply domain logic on top of existing structure.
- Maintain consistency with base project standards.

---

## Goals
- Define domain-specific structure
- Map business logic to modules
- Align system naming with domain terminology
- Customize roles, permissions, and workflows
- Prepare a clean domain-driven project structure

---

## Process

### 1. Define the Domain Model, Modules, Naming, Roles, and Workflows

Load [`references/domain-modeling.md`](references/domain-modeling.md) for detailed guidance on defining entities, modules, naming, roles/permissions, and workflows.

Output:

- domain summary
- module structure
- naming conventions
- role and permission map
- workflow definitions

---

### 2. Customize Existing Base Features

Adjust base project features to match domain:

- update seeders
- update factories
- update default data
- update dashboard content
- update navigation/menu
- update config values
- update environment variables

Do not leave base placeholders unchanged.

---

### 3. Remove or Disable Irrelevant Features

Identify features from base project that do not belong to the new domain.

Classify:
- remove completely
- disable
- defer

Examples:
- unused modules
- unused integrations
- unused UI components
- unused routes

Avoid carrying unnecessary complexity.

---

### 4. Align API and UI With Domain

Ensure:

- API endpoints reflect domain actions
- request/response naming is domain-based
- UI labels use domain language
- documentation reflects real business meaning

Do not expose generic or base-level terminology in final project.

---

### 5. Validate Architecture Alignment

Ensure domain adaptation did not break base rules:

- validation still in Form Requests
- business logic still in Services
- controllers still thin
- Blade still presentation-only
- response format unchanged
- database rules respected

Do not compromise architecture for domain customization.

---

### 6. Prepare Domain Context

Document the domain clearly for future work:

- entities
- modules
- roles
- workflows
- enums
- business rules

This becomes the reference for all future features.

---

## Rules Enforcement

- Do not change core base architecture.
- Do not introduce new patterns unless necessary.
- Do not mix domain logic into infrastructure layers incorrectly.
- Do not leave generic naming from the base project.
- Do not skip defining workflows and roles.

---

## Output Format

- Domain summary
- Entities and relationships
- Modules definition
- Naming conventions
- Roles and permissions
- Workflows and states
- Customizations applied
- Features removed or deferred
- API/UI alignment notes
- Architecture validation results

---

## Completion Standard

The base project is not fully adapted until:

- domain entities are clearly defined
- modules are structured around the domain
- naming reflects business language
- roles and permissions are defined
- workflows are clear
- unnecessary base features are removed or disabled
- API/UI reflect domain terminology
- architecture rules are still fully respected
