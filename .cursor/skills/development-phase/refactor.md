# Refactor Skill

## Purpose

Improve existing code quality while preserving behavior. Use when code works but is difficult to maintain, has duplication, or doesn't follow project architecture.

---

## When to Use

- Code has duplication that could be extracted
- Business logic is in controller or Blade (should be in Service)
- Validation is in controller (should be in Form Request)
- Methods are too long or doing too many things
- Naming is unclear or inconsistent with project conventions
- Class has too many responsibilities

---

## Process

### Step 1: Analyze Current Code

- Read and understand the existing code
- Identify what the code does (preservation of behavior is critical)
- Find the exact files and methods that need refactoring

### Step 2: Identify Issues

- Duplication → extract to shared method/trait/service
- Controller logic → move to Service class
- Blade logic → move to controller/service, keep Blade presentation-only
- Validation in controller → move to Form Request
- Long method → split into smaller focused methods
- Poor naming → rename following project conventions

### Step 3: Plan Changes

- List files to modify
- Plan smallest safe changes
- Identify what will be added, moved, or removed
- Ensure no behavior change unless explicitly requested

### Step 4: Execute Refactor

- Apply changes in logical order
- Update references after moving code
- Test after each significant change

### Step 5: Verify

- Run existing tests
- Check that behavior is unchanged
- Verify no regressions in related functionality

---

## Project-Specific Paths

Reference these patterns when refactoring:

- **Services:** `app/Services/Admin/` or `app/Services/Api/`
- **Form Requests:** `app/Http/Requests/`
- **Controllers:** `app/Http/Controllers/Admin/` or `app/Http/Controllers/Api/`
- **Traits:** `app/Traits/` for shared behavior
- **Models:** `app/Models/`

---

## Architecture Alignment Rules

| Issue | Solution |
|-------|----------|
| Business logic in Controller | Move to `app/Services/` |
| Validation in Controller | Move to `app/Http/Requests/` |
| Logic in Blade | Move to Controller/Service, keep Blade for display |
| Duplication | Extract to Trait, Service, or helper |
| Fat Model | Move complex logic to Service |

---

## Safety Rules

- **Never change behavior** unless explicitly requested by user
- **Keep interfaces stable** unless breaking change is justified
- **Preserve compatibility** with existing API/UI contracts
- **Refactor in small steps** - one logical change at a time
- **Do not refactor unrelated code** - stay focused on the issue
- **Do not add new features** during refactoring

---

## Completion Standard

A refactor is NOT complete until:

- [ ] Code behavior is preserved (verified by tests)
- [ ] Architecture rules are followed (Service for logic, Form Request for validation)
- [ ] No business logic in Controllers or Blade
- [ ] Duplication eliminated or documented as intentional
- [ ] Naming follows project conventions
- [ ] Related tests still pass

---

## Output Format

- Refactor summary
- Files changed
- What was moved where
- Behavior verification notes
- Any intentional trade-offs documented