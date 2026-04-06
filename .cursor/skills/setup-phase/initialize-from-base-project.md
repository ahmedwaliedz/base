# Initialize From Base Project Skill

## Purpose
Initialize a new project starting from the team's existing base project.

The goal is to reuse the base project's established architecture, conventions, and shared features instead of rebuilding them.  
This skill prepares the project for the new domain while preserving consistency with the team standard.

---

## When to Use
- When starting a new project from the team base project
- When adapting the base project to a new business domain
- Before implementing the first real project-specific feature
- When preparing a clean project setup for the rest of the team

---

## Core Principle
- Start from what already exists.
- Reuse before creating.
- Adapt before extending.
- Do not rebuild shared foundations that already exist in the base project.

---

## Goals
- Understand the base project structure
- Identify reusable shared foundations
- Identify project-specific customization needs
- Remove ambiguity before feature development starts
- Prepare a clean, domain-ready starting point

---

## Process

### 1. Discover the Base Project
Review the existing base project before making any changes.

Identify:
- folder structure
- architectural patterns
- validation structure
- service layer structure
- response traits/helpers
- authentication setup
- authorization approach
- shared controllers
- shared middleware
- reusable UI/layout structure
- existing test structure
- seeders/factories
- common helpers/traits
- file upload handling
- existing integrations
- logging/error handling patterns

Do not assume the structure.  
Read the project and identify what is already implemented.

---

### 2. Identify What Must Be Reused
Determine which parts of the base project should remain unchanged and be reused directly.

Common reusable parts may include:
- authentication
- user management
- response format
- base controllers
- service conventions
- request validation conventions
- helper traits
- file upload utilities
- shared layouts/components
- test utilities
- common middleware
- standard API structure

Do not recreate alternative versions of existing foundations.

---

### 3. Identify What Must Be Customized
Determine what needs to change for the new project.

Typical customization areas:
- project/domain name
- business modules
- entities and relationships
- roles and permissions
- enums and statuses
- route groups
- seeders
- project configuration
- navigation/menu structure
- dashboard content
- environment variables
- integrations specific to the new domain

Only customize what is necessary for the new project.

---

### 4. Identify What Should Be Removed, Ignored, or Deferred
The base project may include generic or optional features that are not needed in the new project.

Classify existing parts into:
- reuse as-is
- customize
- remove
- ignore for now
- defer to a later phase

Do not keep unnecessary base features active if they do not serve the new project.

---

### 5. Define the New Project Scope on Top of the Base
Before implementation begins, define the new project clearly.

Document:
- project type
- domain
- target users
- required modules
- API/UI needs
- required permissions
- integrations
- uploads/files usage
- reporting requirements
- documentation requirements
- testing expectations

This becomes the working project context for all future tasks.

---

### 6. Align With Project Rules
Ensure the new project setup still follows the team rules.

Verify:
- validation will use Form Requests
- business logic will use Service classes
- controllers will remain thin
- Blade views will remain presentation-only
- API responses will use the shared response structure
- naming stays consistent with the project conventions
- database design follows the base rules
- existing patterns are reused before creating new ones

Do not allow project customization to break base architecture standards.

---

### 7. Prepare the Initial Project Structure
Create or confirm the required starting structure for the new project.

Examples:
- domain modules
- routes organization
- service classes
- request classes
- policies/permissions
- UI pages or API resources
- seeders/factories
- config entries
- enums/constants
- project context docs

Only prepare what is needed to start implementation cleanly.

---

### 8. Validate Readiness Before Building Features
Before starting actual features, confirm the project is ready.

The project should be ready in terms of:
- architecture clarity
- module clarity
- naming clarity
- permissions clarity
- API/UI direction
- configuration needs
- reusable foundations identified
- unnecessary parts removed or deferred

Do not start feature development on top of unclear project initialization.

---

## Rules Enforcement
- Reuse existing base project structures before creating new ones.
- Do not rebuild authentication, response helpers, service structure, or shared utilities if they already exist.
- Do not introduce alternative architectural patterns without strong justification.
- Do not start module development before the base adaptation is clear.
- Keep the new project aligned with all existing team rules.

---

## Output Format
- Base project summary
- Reusable parts
- Required customizations
- Features/modules to remove, ignore, or defer
- New project scope summary
- Required setup changes
- Initial structure to prepare
- Readiness checklist

---

## Completion Standard
A new project is not properly initialized until:
- the base project has been reviewed
- reusable foundations have been identified
- required customizations are clear
- unnecessary parts are classified
- project scope is documented
- architecture alignment is confirmed
- the project is ready for feature implementation
