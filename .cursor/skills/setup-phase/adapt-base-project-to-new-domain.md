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

### 1. Define the Domain Model

Identify and define:

- core entities (e.g. User, Order, Product, Payment)
- relationships between entities
- domain terminology
- business rules
- lifecycle of main entities
- statuses and enums

Avoid generic naming.  
Use domain-specific language everywhere.

---

### 2. Define Modules Based on Domain

Break the system into clear modules based on domain logic.

Examples:
- Users
- Orders
- Payments
- Reports
- Settings

For each module define:
- responsibility
- main entities
- expected actions (CRUD or workflows)

Do not mix unrelated responsibilities in one module.

---

### 3. Align Naming With Domain

Ensure all naming reflects the business domain:

- model names
- table names
- route names
- service names
- request classes
- controllers
- variables

Avoid:
- generic names (data, item, thing)
- inconsistent naming

Naming must be:
- clear
- consistent
- domain-driven

---

### 4. Define Roles and Permissions

Based on the domain:

- identify user roles
- define permissions per role
- map actions to permissions

Examples:
- Admin
- Manager
- Employee
- Customer

Ensure:
- permissions align with real business rules
- policies are consistent
- no authorization logic is placed in controllers directly

---

### 5. Define Business Workflows

For each main entity:

- define lifecycle
- define state transitions
- define allowed actions per state
- define validation rules per action

Examples:
- Order: created → processing → shipped → delivered
- Payment: pending → paid → failed

Make workflows explicit and consistent.

---

### 6. Customize Existing Base Features

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

### 7. Remove or Disable Irrelevant Features

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

### 8. Align API and UI With Domain

Ensure:

- API endpoints reflect domain actions
- request/response naming is domain-based
- UI labels use domain language
- documentation reflects real business meaning

Do not expose generic or base-level terminology in final project.

---

### 9. Validate Architecture Alignment

Ensure domain adaptation did not break base rules:

- validation still in Form Requests
- business logic still in Services
- controllers still thin
- Blade still presentation-only
- response format unchanged
- database rules respected

Do not compromise architecture for domain customization.

---

### 10. Prepare Domain Context

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
