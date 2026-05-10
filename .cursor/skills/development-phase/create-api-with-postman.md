# Create API with Postman Skill

## Purpose
Build a complete API endpoint including:
- backend implementation
- validation
- business logic
- response structure
- Postman examples
- full documentation

---

## When to Use
- When creating a new API endpoint
- When updating an existing API
- When preparing API for frontend or external use

---

## Process

### 1. Analyze Endpoint
- Define purpose and behavior
- Identify required inputs
- Identify expected outputs
- Identify edge cases

---

### 2. Laravel Implementation

#### Validation
- Use Form Request class
- Define:
  - required fields
  - optional fields
  - nullable fields
  - validation rules

#### Business Logic
- Place logic inside Service class
- Do not place logic inside controller

#### Controller
- Keep controller thin
- Only call service and return response

#### Response
- Use project response traits/helpers
- Follow standard API structure

---

### 3. Request Construction

- Build request body from:
  - Form Request
  - validation rules
- Do not invent fields

---

### 4. Example Data

- Use realistic values from:
  - seeders
  - factories
- If unavailable → use realistic values
- Do not use generic placeholders

---

### 5. Response Examples

Generate all cases:

- Success
- Validation Error
- Unauthorized
- Forbidden
- Not Found
- Business Rule Failure (if exists)

---

### 6. Documentation

Document everything:

#### Endpoint Info
- method
- URL
- auth requirements

#### Request
- body fields
- query params
- path params
- headers

#### Fields
For each field:
- name
- type
- required/optional
- description
- example

#### Enums
- list all values
- explain each value

#### Responses
- explain each case
- include status codes
- include example

---

### 7. Postman MCP

- Generate request
- Attach examples
- Name examples:
  - Success
  - Validation Error
  - Unauthorized
  - Not Found

---

## Output Format

- Endpoint definition
- Request schema
- Validation rules
- Service logic structure
- Controller method
- Response format
- Postman examples
- Full documentation

## Rules Enforcement

- Request body must be derived from Form Request or validation rules only.
- Do not invent fields not present in the codebase.
- Response structure must follow project response traits.
- Example data must be realistic (prefer seeders/factories).
- Documentation must reflect actual implementation, not assumptions.

## Completion Standard

- An API task is not complete until:
  - implementation is done
  - Postman examples are generated
  - documentation is complete
  - all cases are covered
