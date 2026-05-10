# Code Review Skill

When reviewing code:

## Review For
- correctness
- readability
- maintainability
- consistency
- validation and error handling
- unnecessary complexity
- duplication
- risky side effects

## Output Format
- What is good
- Issues found
- Why each issue matters
- Suggested improvements

## Laravel Architecture Review

- Ensure validation is in Form Requests.
- Ensure business logic is in Service classes.
- Ensure controllers are thin.
- Ensure Blade files do not contain business logic.

## API Review

- Check response consistency with project standards.
- Check error handling structure.

## Architecture Violations

- Flag any violation of:
  - Form Request validation rule
  - Service layer rule
  - Thin controller rule
  - Blade presentation-only rule
