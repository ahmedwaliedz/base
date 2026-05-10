# Feature Analysis Skill

When asked to implement a feature, analyze it before coding.

## Process
1. Define the goal.
2. Identify impacted modules and files.
3. Identify required data structures or schema changes.
4. Identify backend changes.
5. Identify frontend changes.
6. Identify validation, states, and edge cases.
7. Propose implementation steps.
8. Then generate code.

## Architecture Awareness
- Identify if the feature requires:
  - new database tables or schema changes
  - Form Request validation
  - Service class for business logic
  - API endpoint or Blade UI or both
- Determine where business logic should live (Service vs existing logic).
- Ensure the feature follows project architecture rules.

## Output Format
- Goal
- Affected Areas
- Data / Schema
- Backend
- Frontend
- Validation
- Edge Cases
- Implementation Plan

## Decision Making

- Decide early:
  - Is this API-only feature?
  - Is this UI feature?
  - Is it both?
- Decide if feature requires:
  - new module
  - extension of existing module
