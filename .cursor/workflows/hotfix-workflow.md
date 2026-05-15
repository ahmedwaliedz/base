# Hotfix Workflow

## Purpose
Define the safest and fastest workflow for urgent fixes in an existing project.

This workflow focuses on identifying the root cause, applying the smallest safe fix, and reducing regression risk.

---

## When to Use
- When fixing urgent production or staging issues
- When a bug blocks a critical business flow
- When a minimal fix is needed quickly
- When broad refactoring is not appropriate

---

## Workflow Steps

### Step 1: Identify the Problem Clearly
Use:
- bug-fixing skill

Goals:
- define the exact bug
- understand expected behavior
- identify affected flows
- identify urgency and impact

Output:
- bug summary
- affected area
- expected vs actual behavior

---

### Step 2: Find the Root Cause
Use:
- bug-fixing skill

Goals:
- trace execution path
- inspect validation, service, controller, DB, or integration layers
- isolate the actual cause
- avoid treating symptoms only

Output:
- root cause
- affected files/layers

---

### Step 3: Apply the Smallest Safe Fix
Use:
- bug-fixing skill

Goals:
- implement the minimum safe change
- avoid unrelated refactors
- preserve existing behavior outside the fix scope

Output:
- fix approach
- changed files
- risk notes

---

### Step 4: Validate Regression Risk
Use:
- testing skill
- feature-finalization-and-validation skill when needed

Goals:
- test the broken flow
- test nearby flows likely to be affected
- confirm architecture was not broken during the fix

Output:
- regression notes
- test results

---

### Step 5: Final Review
Goals:
- confirm the issue is fixed
- confirm no critical side effects
- confirm the fix remains aligned with project rules

Output:
- final hotfix summary
- ready/not ready decision

---

## Rules Enforcement
- Do not apply broad refactors in a hotfix unless absolutely required.
- Do not fix symptoms without identifying root cause.
- Do not break architecture rules while rushing.
- Keep the fix isolated and reviewable.

---

## Completion Standard
A hotfix is complete only when:
- the root cause is identified
- the smallest safe fix is applied
- regression risk is checked
- the issue is confirmed resolved
- no major side effects remain
