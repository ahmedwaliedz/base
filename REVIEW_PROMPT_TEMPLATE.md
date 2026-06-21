# Review Prompt Template

Use this template when asking an agent to review code in this repo.

## User-provided information

```text
Review scope:
- [What is being reviewed?]

Changed files or diff:
- [List or paste diff]

Intended behavior:
- [What should the changed code do?]

Review type:
- [Correctness / Security / Architecture / Performance / Test / Documentation / Refactor behavior-preservation / Full pre-merge]

Known risks:
- [Any areas already known to be risky]

Areas requiring special attention:
- [Specific files, patterns, or behaviors]

Expected compatibility:
- [Laravel 11, PHP 8.2, PHPUnit 11]

Verification commands:
- [Tests or checks that can be run]

Are fixes authorized?
- [Yes / No / Only with confirmation]
```

## Agent-derived routing

The agent populates this section after inspecting the repository and diff.

| Field | Value |
|---|---|
| Detected review category | [Production code / Tests / Documentation / Mixed] |
| Guard selected per category | [clean-code-guard / test-guard / docs-guard] |
| Minimal baseline selected per category | [list] |
| Conditional rules selected | [list] |
| Governing skills selected | [list] |
| References selected | [only relevant references] |
| Source implementation inspected | [files backing the documented claims] |
| Verification selected | [commands to run] |
| Unchecked areas | [anything excluded from the review] |

## Review instructions

Read [`REVIEW_CONTEXT.md`](REVIEW_CONTEXT.md) first. Determine the review category (production code / tests / documentation / mixed) and use the appropriate guard(s). Open deeper `.cursor` files only when the changed area matches the routing table. Do not read the full `.cursor` tree unless explicitly asked.

**Critical:** Do not apply the production-code baseline to a test-only or documentation-only review. For mixed reviews, combine only the baselines required by the changed-file categories.

### Review by category:

- **Production code** (controllers, services, requests, FK validation, Blade restrictions, API, RBAC, file uploads, integrations) → [`.cursor/skills/guards/clean-code-guard/SKILL.md`](.cursor/skills/guards/clean-code-guard/SKILL.md) baseline + conditional domain rules
- **Tests** (PHPUnit test files) → [`.cursor/skills/guards/test-guard/SKILL.md`](.cursor/skills/guards/test-guard/SKILL.md) baseline + governing production skill + conditional domain rules
- **Documentation** (README, API docs, PHPDoc, workflows, skills) → [`.cursor/skills/guards/docs-guard/SKILL.md`](.cursor/skills/guards/docs-guard/SKILL.md) baseline + [verification procedure](.cursor/skills/guards/docs-guard/references/verification-procedure.md) + conditional documentation-type rules
- **Mixed** (multiple categories) → all applicable guards + union of applicable baselines only

### Focus on:

- Runtime errors
- Broken routes or pages
- Security issues
- Data integrity issues
- N+1 queries and performance regressions
- Architecture violations
- Missing validation
- Missing or weak tests
- RBAC mismatches

Rules:

- Be strict and specific.
- Findings come first, ordered by severity.
- Include exact file paths and line numbers when possible.
- Do not reference files, methods, or conventions that do not exist.
- Label inference-based findings as `[inference]`.
- If there are no findings, say that clearly and mention residual risk.
- Give a short, direct fix prompt for each issue.
- Preserve the existing Laravel architecture.
- Make the smallest safe change.
- Do not touch unrelated files.
- Keep token usage efficient.

## Required review output

1. Findings ordered by severity
2. Exact file and line
3. Observed behavior or evidence
4. Why it matters
5. Minimal recommended fix
6. Testing gaps
7. Residual risks
8. Final assessment
