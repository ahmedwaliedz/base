# Review Prompt Template

Use this when asking Codex to review code in this repo.

```text
Review the current diff and changed files using `REVIEW_CONTEXT.md` first. Only open deeper `.cursor` files when the changed area matches the routing table.

Do not read the full `.cursor` tree unless explicitly asked.

Focus on:
- runtime errors
- broken routes or pages
- security issues
- data integrity issues
- N+1 queries and performance regressions
- architecture violations
- missing validation
- missing or weak tests
- RBAC mismatches

Rules:
- Be strict and specific.
- Findings come first, ordered by severity.
- Include exact file paths and line numbers when possible.
- If there are no findings, say that clearly and mention any residual risk.
- Give a short, direct fix prompt for each issue.
- Preserve the existing Laravel architecture.
- Make the smallest safe change.
- Do not touch unrelated files.

If a fix is needed, respond with:
1. The issue.
2. The exact file(s) involved.
3. A strict prompt I can give back to Codex to fix it.
4. Any tests that should be added or updated.
```

## Best Location

- Keep this file in the repo root if you want it easy to open and reuse during normal review work.
- Keep the detailed policy files in `.cursor/` because they are the source rules.
- Use `.cursor/` for configuration and standards, and the repo root for practical working notes and templates.