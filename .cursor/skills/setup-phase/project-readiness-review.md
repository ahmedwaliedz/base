# Project Readiness Review Skill

## Purpose
Ensure that the project is fully prepared before starting feature implementation.

This skill validates that:
- the base project is properly initialized
- the domain adaptation is complete
- architecture rules are respected
- the project is stable and ready for development

---

## When to Use
- After initializing the project from the base project
- After adapting the system to the new domain
- Before starting the first real feature
- Before assigning work to team members
- Before integrating frontend or external systems

---

## Core Principle
- Do not start feature development on top of an unclear or incomplete setup.
- Fix structure and architecture issues first.
- Ensure clarity before execution.

---

## Goals
- Validate project structure
- Validate architecture compliance
- Validate domain clarity
- Identify missing pieces
- Prevent future rework

---

## Process

### 1. Validate Base Project Initialization

Ensure:
- base project was reviewed
- reusable components are identified
- shared systems are understood
- no duplicate implementations were created

Check:
- auth system is clear
- response structure is known
- service pattern is understood
- validation pattern is consistent

---

### 2. Validate Domain Adaptation

Ensure:
- domain entities are defined
- relationships are clear
- modules are properly structured
- naming reflects domain language

Check:
- no generic or leftover naming from base project
- modules are not mixed or unclear

---

### 3. Validate Architecture Compliance

Ensure the project follows all core rules:

- validation is handled via Form Requests
- business logic is inside Service classes
- controllers are thin
- Blade templates are presentation-only
- API responses use shared response structure
- database follows normalization rules

Flag any violation immediately.

---

### 4. Validate Modules Structure

Ensure:
- modules are clearly separated
- each module has a clear responsibility
- no overlapping logic between modules

Check:
- correct folder placement
- correct naming
- consistency across modules

---

### 5. Validate Roles and Permissions

Ensure:
- roles are clearly defined
- permissions align with business logic
- policies or middleware are properly used

Check:
- no authorization logic inside controllers
- no missing access control

---

### 6. Validate API and UI Readiness

If API exists:
- endpoints follow consistent naming
- request structure is clear
- response format is consistent
- error handling is defined

If UI exists:
- pages are structured
- forms are clear
- no business logic in Blade

---

### 7. Validate Database Readiness

Ensure:
- tables are defined correctly
- relationships are clear
- foreign keys are used where needed
- no duplicated or unnecessary fields

Check:
- migrations are clean
- naming is consistent

---

### 8. Validate Configuration and Environment

Ensure:
- environment variables are defined
- configs are updated
- integrations are prepared (if any)

Check:
- no missing keys
- no hardcoded values

---

### 9. Validate Testing Readiness

Ensure:
- testing structure exists
- test strategy is clear

Check:
- ability to test:
  - validation
  - services
  - API endpoints

---

### 10. Identify Gaps and Risks

List:
- missing parts
- unclear areas
- risky assumptions
- incomplete modules

Do not ignore gaps.

---

## Rules Enforcement

- Do not proceed to feature development if critical issues exist.
- Do not ignore architecture violations.
- Do not leave unclear domain definitions.
- Do not allow inconsistent naming.
- Do not skip validation of permissions and workflows.

---

## Output Format

- Project readiness summary
- Base initialization status
- Domain adaptation status
- Architecture compliance report
- Modules structure validation
- Roles and permissions status
- API/UI readiness status
- Database readiness status
- Configuration readiness
- Testing readiness
- Identified gaps and risks
- Final decision (Ready / Not Ready)

---

## Completion Standard

A project is considered ready only if:

- base project is fully understood and reused correctly
- domain structure is clearly defined
- architecture rules are fully respected
- modules are properly structured
- naming is consistent
- roles and permissions are defined
- API/UI structure is clear
- database is correctly designed
- configuration is complete
- no critical gaps remain

If any of the above is missing → project is NOT ready.
