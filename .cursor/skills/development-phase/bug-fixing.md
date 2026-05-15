# Bug Fixing Skill

## Purpose

Fix bugs efficiently by identifying root cause, not symptoms. Preserve behavior while fixing.

---

## When to Use

- User reports unexpected behavior
- Tests are failing
- Edge case causes error
- Feature not working as expected
- Regression discovered after changes

---

## Process

### Step 1: Reproduce the Bug

- Understand the exact steps to trigger the bug
- Try to reproduce locally
- Note the expected vs actual behavior

### Step 2: Identify Root Cause

- Trace the execution path
- Check validation logic (Form Requests)
- Check service layer for business logic issues
- Verify database queries and relationships
- Check authentication/authorization logic
- **Never fix symptoms** - always find the actual cause

### Step 3: Analyze Affected Files

- Confirm files involved in the bug
- Identify what needs to change
- Check for similar patterns in codebase that might have same issue

### Step 4: Plan Fix

- Make the smallest safe fix
- Avoid broad refactors unless necessary to solve the bug
- Consider side effects and regressions
- Document what you're changing and why

### Step 5: Implement Fix

- Apply the minimal change
- Test the specific bug case
- Check related functionality for regressions

### Step 6: Verify

- Run related tests
- Test edge cases around the fix
- Ensure no new issues introduced

---

## Project-Specific Areas to Check

### Validation Layer
- Check `app/Http/Requests/` for Form Request rules
- Check if validation is incorrectly in controller

### Service Layer
- Check `app/Services/Admin/` or `app/Services/Api/`
- Business logic errors
- Missing error handling

### Response Handling
- Check `app/Traits/Api/` for response structure
- Check for consistent error responses

### Database/Models
- Check `app/Models/` for relationships
- Check for N+1 queries
- Check for missing eager loading

### Authentication
- Check middleware in routes
- Check policies in `app/Policies/`

---

## Safety Rules

- **Do not introduce new patterns** while fixing bugs
- **Do not refactor unrelated code** - stay focused
- **Keep fix minimal and isolated** - don't fix multiple things
- **Never change behavior** beyond fixing the bug
- **Document** what was fixed and why
- **Test the fix works** before considering complete

---

## Common Bug Patterns in Laravel

| Issue | Check |
|-------|-------|
| 404 on existing record | Route model binding, soft deletes |
| Validation not working | Form Request binding |
| N+1 queries | Eager loading in controller/service |
| Auth failures | Middleware, policies |
| Data not saving | Mass assignment, fillable |
| Foreign key errors | Relationship definitions |
| File upload issues | Storage config, permissions |

---

## Root Cause Discipline

**The most important rule:** Do not fix symptoms.

Examples of fixing symptoms vs fixing causes:

| ❌ Wrong (Symptoms) | ✅ Right (Root Cause) |
|-------------------|---------------------|
| Add null check in view | Fix why data is null in service |
| Add try-catch hiding error | Fix what's causing the exception |
| Return default value | Fix why value is missing |
| Disable validation rule | Fix why validation is failing |

---

## Completion Standard

A bug fix is NOT complete until:

- [ ] Root cause identified and fixed (not just symptoms)
- [ ] Fix is minimal and focused
- [ ] No new patterns introduced
- [ ] Related functionality still works
- [ ] Tests pass
- [ ] Fix documented (what was changed and why)

---

## Output Format

- Bug summary
- Root cause identified
- Fix approach
- Files changed
- Regression considerations
- Verification steps taken