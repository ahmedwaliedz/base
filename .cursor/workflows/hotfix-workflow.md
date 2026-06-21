# Hotfix Workflow

## Purpose

Define the safest and fastest workflow for urgent fixes in an existing project. This workflow focuses on identifying the root cause, applying the smallest safe fix, and reducing regression risk.

## Trigger

- Fixing urgent production or staging issues
- A bug blocking a critical business flow
- A minimal fix is needed quickly
- Broad refactoring is not appropriate

## Preconditions

- The bug is reproduced or clearly described.
- Affected code and flows are identified.

## Workflow

### Step 1: Identify the Problem Clearly

- **Primary skill:** [`bug-fixing`](../skills/development-phase/bug-fixing/SKILL.md)
- **Output:** Bug summary, affected area, expected vs actual behavior

### Step 2: Find the Root Cause

- **Primary skill:** [`bug-fixing`](../skills/development-phase/bug-fixing/SKILL.md)
- **Output:** Root cause and affected files/layers

### Step 3: Apply the Smallest Safe Fix

- **Primary skill:** [`bug-fixing`](../skills/development-phase/bug-fixing/SKILL.md)
- **Output:** Fix approach, changed files, risk notes

### Step 4: Validate Regression Risk

- **Primary skill:** [`testing`](../skills/development-phase/testing/SKILL.md)
- **Supporting skill:** [`feature-finalization-and-validation`](../skills/development-phase/feature-finalization-and-validation/SKILL.md) when needed
- **Rules:** [`../rules/16-testing-qa.mdc`](../rules/16-testing-qa.mdc)
- **Output:** Regression notes and test results

### Step 5: Final Review

- Confirm the issue is fixed.
- Confirm no critical side effects.
- Confirm the fix remains aligned with project rules.

## Guard passes

Run guards after the affected content is changed. In authorized hotfix mode, fix confirmed critical/high findings in scope, rerun the guard, and repeat up to two cycles. Report persistent or unclear findings to the user.

- Production code changed → [`clean-code-guard`](../skills/guards/clean-code-guard/SKILL.md)
- Tests changed → [`test-guard`](../skills/guards/test-guard/SKILL.md)
- Documentation changed → [`docs-guard`](../skills/guards/docs-guard/SKILL.md)

## Verification

- Run targeted tests for the broken flow.
- Run broader relevant tests when the fix touches shared code.
- `php artisan test`

## Completion criteria

A hotfix is complete only when:

- The root cause is identified.
- The smallest safe fix is applied.
- Regression risk is checked.
- Relevant guard passes produce no unresolved critical or high findings.
- The issue is confirmed resolved.
- No known correctness, security, or data-integrity defect remains.
- Medium/low findings are either fixed, accepted with justification, or reported as residual risk.

## Rules enforcement

- Do not apply broad refactors in a hotfix unless absolutely required.
- Do not fix symptoms without identifying root cause.
- Do not break architecture rules while rushing.
- Keep the fix isolated and reviewable.
