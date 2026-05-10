# Refactor Skill

When refactoring code:

## Goals
- improve readability
- reduce duplication
- improve structure
- preserve behavior

## Rules
- Do not change behavior unless explicitly requested.
- Refactor in small, understandable steps.
- Keep interfaces stable unless necessary.
- Preserve compatibility with surrounding code.

## Architecture Alignment

- Move business logic to Service classes if misplaced.
- Move validation to Form Requests if found in controllers.
- Ensure separation of concerns after refactor.
