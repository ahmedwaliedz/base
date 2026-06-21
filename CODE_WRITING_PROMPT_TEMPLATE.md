# Code Writing Prompt Template

Use this template when asking an agent to implement code in this repo from a plan, checklist, bug report, or feature request.

## User-provided information

Fill in everything the agent needs to know. Unknown fields can be left blank or marked "not provided."

```text
Requested outcome:
- [What should the code do?]

Business behavior:
- [Why is this change needed?]

Scope:
- [What is included and excluded?]

Target project area:
- [Backend / API / UI / DB / RBAC / Integration / etc.]

Known target files:
- [File paths if already known]

Expected inputs and outputs:
- [Request/response shape, UI state, etc.]

Validation requirements:
- [Rules, constraints, required fields]

Authorization requirements:
- [Roles, permissions, ownership]

Database effects:
- [Migrations, new columns, relationships]

API requirements:
- [Endpoints, methods, status codes]

UI requirements:
- [Pages, components, user flow]

Test expectations:
- [Required coverage or specific scenarios]

Documentation expectations:
- [README, PHPDoc, Postman, .cursor docs]

Compatibility constraints:
- [Laravel 11, PHP 8.2, PHPUnit 11, etc.]

Explicit exclusions:
- [What should NOT be changed]

Acceptance criteria:
- [How to know the task is complete]

Available verification commands:
- [Tests, linting, manual checks]
```

## Agent-derived routing

The agent populates this section after inspecting the repository.

| Field | Value |
|---|---|
| Detected task type | [e.g. backend behavior, admin CRUD, API endpoint] |
| Current technology baseline | [from `.cursor/context/technology-baseline.md`] |
| Mandatory rules | [list] |
| Primary skill | [path to `SKILL.md`] |
| Secondary skills | [paths if needed] |
| References to load | [only relevant references] |
| Workflow to use | [development-workflow / hotfix-workflow / setup-workflow] |
| Templates to use | [relevant template names] |
| Required guards | [clean-code-guard / test-guard / docs-guard] |
| Verification plan | [commands and manual checks] |

## Implementation instructions

Use [`CODE_WRITING_CONTEXT.md`](CODE_WRITING_CONTEXT.md) first. Open deeper `.cursor` files only when the task area matches the routing table. Do not read the full `.cursor` tree unless explicitly asked.

Focus on:

- Runtime correctness
- Security and data integrity
- Preserving existing Laravel architecture
- Thin controllers
- Form Requests for non-trivial validation
- Services for business logic
- Blade presentation-only
- RBAC route-name/permission alignment
- N+1 and performance safety
- Tests or verification for changed behavior

Rules:

- Inspect only the task, directly related files, nearby patterns, and routed `.cursor` files.
- Make the smallest complete change that solves the item.
- Do not touch unrelated files.
- Do not introduce new architecture unless clearly required.
- Reuse existing services, components, traits, base controllers, and CRUD patterns.
- Keep old behavior untouched unless the task explicitly changes it.
- Use `$request->validated()` — never `$request->all()` for create/update.
- Validate foreign keys with `exists:table,id`.
- Keep Blade free of DB queries, service calls, and model lookups.
- Eager-load all relations displayed in admin views.
- If RBAC permission behavior is unclear, stop and ask before changing it.
- Update the plan/checklist only after implementation and verification.

## Final response

The final response must include:

1. What was implemented.
2. Files changed.
3. Verification performed.
4. Guard passes run and their results.
5. Tests added or updated.
6. Any tests not run and why.
7. Any remaining risk or follow-up.
