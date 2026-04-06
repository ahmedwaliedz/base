# API Design Skill

When building or updating an API:

## Process
- Define the resource or use case clearly.
- Use predictable naming for endpoints and handlers.
- Validate request data.
- Keep response structures consistent with the project.
- Handle success, validation failure, not found, and server error cases.

## Output Format
- Endpoints
- Request fields
- Validation rules
- Response shape
- Error cases

## Laravel API Standards

- Use Form Request classes for validation.
- Use Service classes for business logic.
- Keep controllers thin.
- Use the project's shared response traits/helpers.

## Documentation Requirement

- API must be documented with:
  - request parameters
  - request body
  - validation rules
  - response structure
  - all possible error cases

## Example Coverage

- Always include:
  - success
  - validation error
  - unauthorized
  - not found

  ## Integration with Other Skills

- After defining the API, use the "Create API with Postman" skill to:
  - generate examples
  - generate documentation
  - prepare Postman requests
