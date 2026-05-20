# Code Writing Prompt Template

Use this when asking Codex to implement code in this repo from a plan, checklist, bug report, or feature request.

```text
Implement the requested item using `CODE_WRITING_CONTEXT.md` first.
Only open deeper `.cursor` files when the task area matches the routing table in `CODE_WRITING_CONTEXT.md`.

Do not read the full `.cursor` tree unless explicitly asked.

Task:
- [Paste the exact checklist item, bug, feature, or plan section here]

Plan file if relevant:
- [Paste the plan file path here, for example `ADMIN_DASHBOARD_FIX_PLAN.md`]

Focus on:
- runtime correctness
- security and data integrity
- preserving existing Laravel architecture
- thin controllers
- Form Requests for non-trivial validation
- Services for business logic
- Blade presentation-only
- RBAC route-name/permission alignment
- N+1 and performance safety
- tests or verification for changed behavior

Rules:
- Inspect only the task, directly related files, nearby patterns, and routed `.cursor` files.
- Make the smallest safe change that fully solves the item.
- Do not touch unrelated files.
- Do not introduce new architecture unless clearly required.
- Reuse existing services, components, traits, base controllers, and CRUD patterns.
- Keep old behavior untouched unless the task explicitly changes it.
- If RBAC permission behavior is unclear, stop and ask before changing it.
- Update the plan/checklist only after implementation and verification.

Implementation flow:
1. Identify affected layers: route, controller, request, service, model, migration, view, translation, test.
2. Inspect existing patterns for those layers.
3. Implement the smallest safe change.
4. Add or update tests when behavior changes, a route is added, or a regression is fixed.
5. Run the most relevant verification available.
6. Report files changed, verification, tests, and remaining risk.

Final response must include:
1. What was implemented.
2. Files changed.
3. Verification performed.
4. Tests added or updated.
5. Any tests not run and why.
6. Any remaining risk or follow-up.
```

## Best Location

- Keep this file in the repo root for quick reuse during implementation work.
- Keep detailed standards in `.cursor/`.
- Use `CODE_WRITING_CONTEXT.md` as the compact implementation guide and routing map.
