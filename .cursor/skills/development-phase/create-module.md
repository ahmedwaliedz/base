# Create Module Skill

When creating a new module, build it end-to-end in a structured way.

## Include When Relevant
- database schema or migration
- model / entity
- relationships
- validation
- service / business logic
- controller / handler
- routes
- UI pages or components
- list page
- create form
- edit form
- details page

## Standards
- Follow the existing project structure.
- Reuse existing abstractions.
- Keep naming consistent.
- Add search, filters, and pagination where appropriate.
- Support loading, empty, and error states in UI where relevant.

## Laravel Architecture Enforcement

- Use Form Request classes for validation (do not validate inside controllers).
- Place business logic inside Service classes.
- Keep controllers thin (only orchestration).
- Do not place business logic in Blade templates.
- Follow project API response structure if building APIs.

## Execution Order

- Always follow this order:
  1. Database (migration)
  2. Model & relationships
  3. Form Request validation
  4. Service class
  5. Controller
  6. Routes
  7. UI or API
