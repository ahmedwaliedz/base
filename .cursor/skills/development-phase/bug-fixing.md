# Bug Fixing Skill

When fixing a bug:

## Process
1. Identify the actual root cause.
2. Confirm the affected files and execution path.
3. Make the smallest safe fix.
4. Avoid broad refactors unless necessary to solve the bug.
5. Check for likely regressions nearby.

## Output Format
- Bug summary
- Root cause
- Fix approach
- Files changed
- Regression considerations

## Laravel Awareness

- Check validation logic (Form Requests).
- Check service layer for business logic issues.
- Verify database queries and relationships.

## Safety Rules

- Do not introduce new patterns while fixing bugs.
- Do not refactor unrelated parts of the codebase.
- Keep fix minimal and isolated.

## Root Cause Discipline

- Do not fix symptoms.
- Always identify the actual root cause before applying a fix.
