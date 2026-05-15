# Laravel Feature End-to-End Skill

## Purpose
Implement a complete feature from start to finish following project architecture, rules, and best practices.

This includes:
- analysis
- database design
- backend implementation
- API or UI
- validation
- business logic
- documentation
- Postman examples
- testing

---

## When to Use
- When implementing a new feature
- When building a full module
- When extending an existing module significantly
- When delivering production-ready functionality

---

## Execution Strategy
- Always execute in order.
- Do not skip steps.
- Do not jump between layers randomly.
- Each step must be completed before moving to the next.

---

## Step 1: Feature Analysis

Use: feature-analysis skill

- Define feature goal
- Identify affected modules
- Identify DB changes
- Identify API/UI requirements
- Identify edge cases
- Decide:
  - API only
  - UI only
  - both

---

## Step 2: Database Design

Use: database-design skill

- Define entities
- Define relationships
- Create migrations
- Ensure:
  - proper types
  - foreign keys
  - no duplication
  - normalized structure

---

## Step 3: Backend Structure

### Validation
- Create Form Request classes
- Define:
  - required fields
  - optional fields
  - validation rules

### Business Logic
- Create Service class
- Move all business logic into it

### Controller
- Keep controller thin
- Only orchestrate request → service → response

---

## Step 4: API or UI Implementation

### If API:

Use:
- create-api-with-postman skill

Implement:
- endpoints
- request handling
- response format
- error handling

Generate:
- Postman examples
- full documentation

---

### If UI:

Use:
- ui-page-build skill

Implement:
- pages
- forms
- lists
- actions

Ensure:
- Blade is presentation only
- no business logic in views

---

## Step 5: Response & Consistency

- Use shared response traits/helpers
- Keep responses consistent
- Do not invent new response formats

---

## Step 6: Documentation

- Document:
  - endpoint
  - params
  - request body
  - enums
  - responses
- Documentation must match actual implementation

---

## Step 7: Testing

Use: testing skill

- Test:
  - success cases
  - validation errors
  - unauthorized
  - edge cases
- Ensure regression safety

---

## Step 8: Review & Validation

Use: 22-code-review.mdc (rule) or feature-finalization-and-validation skill

- Ensure:
  - architecture compliance
  - no business logic in controllers
  - validation in Form Requests
  - service layer usage
  - naming consistency

---

## Rules Enforcement

- Validation must be in Form Requests
- Business logic must be in Service classes
- Controllers must be thin
- Blade must be presentation only
- Database must follow normalization rules
- API must follow response traits
- Do not invent patterns
- Reuse existing project structures

---

## Output Format

- Feature summary
- Database schema (tables, relations)
- Validation rules
- Service class structure
- Controller methods
- Routes
- API endpoints or UI pages
- Response structure
- Postman examples (if API)
- Documentation
- Test cases

---

## Completion Standard

A feature is NOT complete unless:

- database is implemented correctly
- backend logic is complete
- API/UI is working
- validation is applied
- documentation is complete
- Postman examples are generated (for APIs)
- tests are written
- code passes architecture rules
