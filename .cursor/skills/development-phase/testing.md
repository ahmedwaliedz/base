# Testing Skill

When adding or suggesting tests:

## Focus On
- critical business logic
- validations
- state transitions
- edge cases
- regression-prone paths

## Rules
- Prefer focused tests over overly broad tests.
- Cover both expected and failure cases.
- Keep tests readable and deterministic.

## Laravel Testing

- Test Form Request validation.
- Test service layer logic.
- Test API responses structure.

## API Testing

- Test success responses.
- Test validation errors.
- Test unauthorized and forbidden cases.

## Critical Coverage

- Every API must have tests for:
  - success
  - validation failure
  - unauthorized
  - not found
