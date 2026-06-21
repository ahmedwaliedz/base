# `.cursor` Agent System

This directory contains the agent instructions, rules, skills, workflows, templates, and context files used by AI assistants working on this Laravel base project.

The system is designed around **progressive disclosure**: agents load only the smallest relevant context for a task instead of reading every file.

---

## Current technology baseline

The single authoritative baseline is [`.cursor/context/technology-baseline.md`](context/technology-baseline.md).

| Layer | Version |
|---|---|
| PHP | `^8.2` (production compatibility) |
| Laravel Framework | `^11.0` (installed `11.46.1`) |
| PHPUnit | `^11.5.3` (installed `11.5.42`) |
| Laravel Pint | `^1.13` (installed `1.25.1`) |

The local PHP CLI may be newer, but generated code must remain PHP 8.2-compatible until the baseline is intentionally upgraded.

**Future upgrade targets:** Laravel 13, PHP 8.4 or PHP 8.5. These are documented as future/optional only.

---

## Directory structure

Router files live at project root because that is their canonical location in this repo. The `.cursor/` directory contains supporting rules, skills, workflows, templates, and context.

```text
project-root/
├── CODE_WRITING_CONTEXT.md         # Execution router
├── CODE_WRITING_PROMPT_TEMPLATE.md
├── REVIEW_CONTEXT.md               # Review router
├── REVIEW_PROMPT_TEMPLATE.md
└── .cursor/
    ├── README.md                   # This file
    ├── context/                    # Project, domain, team, and technology context
    ├── rules/                      # Always-on or glob-specific constraints (.mdc)
    ├── skills/
    │   ├── setup-phase/            # Project initialization skills
    │   ├── development-phase/      # Feature implementation skills
    │   ├── specialized/            # Domain-specific skills (RBAC, uploads, integrations, chat)
    │   └── guards/                 # Post-implementation review skills
    ├── styles/                     # UI/UX standards
    ├── templates/                  # Reusable code skeletons
    ├── workflows/                  # Multi-step coordination workflows
    └── prompt-library/             # Ready-to-use prompt building blocks
```

Skills are organized into categories, but agents discover each skill by its `SKILL.md` file recursively. Category folders are organizational only; routing uses the full path `skills/<category>/<skill-name>/SKILL.md`.

---

## Concepts

### Rules (`rules/*.mdc`)

Short, authoritative constraints. They are the source of truth for project standards.

- Always-on rules apply to every task.
- Glob-specific rules activate for matching file paths.
- Rules state **what** must be true; they do not contain full execution procedures.

### Skills (`skills/*/*/SKILL.md`)

Task procedures, decision points, resource selection, verification, and output expectations.

- Each skill lives in its own directory with a `SKILL.md` file.
- The `SKILL.md` frontmatter contains `name` and `description`.
- Skills load only the references they need.
- Skills point to rules instead of duplicating them.

### References (`skills/*/*/references/*.md`)

Detailed examples, checklists, project conventions, and large technical explanations. Loaded only when the governing skill says so.

### Workflows (`workflows/*.md`)

Coordinate multiple skills in the correct order. Workflows define entry conditions, sequence, checkpoints, guards, and completion gates. They do not duplicate full skill instructions.

### Templates (`templates/*.md`)

Concrete output or implementation skeletons. Templates are not policy documents; they provide starting structures that match the project architecture.

### Context files (`context/*.md`)

Intelligent routing systems and shared project knowledge. They determine which rules, skills, references, workflows, and templates are needed.

### Guards (`skills/guards/*/SKILL.md`)

Post-implementation review skills. Each guard loads a minimal mandatory baseline and then conditionally adds rules, skills, and references based on the changed area or documentation type:

- `clean-code-guard` — production code review
- `test-guard` — test review
- `docs-guard` — documentation review

---

## Skill inventory

### Setup phase

| Skill | Trigger |
|---|---|
| [`initialize-from-base-project`](skills/setup-phase/initialize-from-base-project/SKILL.md) | Starting a new project from the base project |
| [`adapt-base-project-to-new-domain`](skills/setup-phase/adapt-base-project-to-new-domain/SKILL.md) | Adapting the base project to a new domain |
| [`project-readiness-review`](skills/setup-phase/project-readiness-review/SKILL.md) | Validating readiness before feature work |

### Development phase

| Skill | Trigger |
|---|---|
| [`feature-analysis`](skills/development-phase/feature-analysis/SKILL.md) | Analyzing a new feature |
| [`feature-to-module-execution`](skills/development-phase/feature-to-module-execution/SKILL.md) | Mapping a feature to a module |
| [`create-module`](skills/development-phase/create-module/SKILL.md) | Creating a complete new module |
| [`database-design`](skills/development-phase/database-design/SKILL.md) | Designing schema changes |
| [`backend-feature-implementation`](skills/development-phase/backend-feature-implementation/SKILL.md) | Implementing backend logic |
| [`create-api-with-postman`](skills/development-phase/create-api-with-postman/SKILL.md) | Building an API endpoint with Postman docs |
| [`ui-page-build`](skills/development-phase/ui-page-build/SKILL.md) | Building Blade admin pages |
| [`admin-crud-orchestrator`](skills/development-phase/admin-crud-orchestrator/SKILL.md) | Building a full admin CRUD module |
| [`laravel-feature-end-to-end`](skills/development-phase/laravel-feature-end-to-end/SKILL.md) | Executing a full feature end-to-end |
| [`testing`](skills/development-phase/testing/SKILL.md) | Writing or updating tests |
| [`bug-fixing`](skills/development-phase/bug-fixing/SKILL.md) | Fixing bugs |
| [`refactor`](skills/development-phase/refactor/SKILL.md) | Refactoring without behavior change |
| [`feature-finalization-and-validation`](skills/development-phase/feature-finalization-and-validation/SKILL.md) | Final gate before delivery |

### Specialized

| Skill | Trigger |
|---|---|
| [`auth-permissions`](skills/specialized/auth-permissions/SKILL.md) | Authentication and custom RBAC |
| [`file-upload`](skills/specialized/file-upload/SKILL.md) | File, image, and document uploads |
| [`integration`](skills/specialized/integration/SKILL.md) | External APIs and integrations |
| [`realtime-chat`](skills/specialized/realtime-chat/SKILL.md) | Realtime chat and messaging |

### Guards

| Guard | Trigger |
|---|---|
| [`clean-code-guard`](skills/guards/clean-code-guard/SKILL.md) | Production code changed |
| [`test-guard`](skills/guards/test-guard/SKILL.md) | Tests changed |
| [`docs-guard`](skills/guards/docs-guard/SKILL.md) | Documentation changed |

---

## Execution routing

Start every implementation session with [`CODE_WRITING_CONTEXT.md`](../CODE_WRITING_CONTEXT.md).

Typical flow:

```text
CODE_WRITING_CONTEXT.md
  → baseline rules
  → primary implementation skill
  → required references
  → relevant workflow/templates
  → inspect project source
  → implement
  → write/update tests
  → clean-code-guard
  → test-guard
  → docs-guard (when applicable)
  → feature finalization
  → verification
```

Task-to-skill examples:

| Task | Primary skill |
|---|---|
| New admin CRUD | `admin-crud-orchestrator` |
| API endpoint + Postman | `create-api-with-postman` |
| Database schema change | `database-design` |
| Bug fix | `bug-fixing` |
| Refactor | `refactor` |
| Tests only | `testing` |
| RBAC change | `auth-permissions` |
| File upload | `file-upload` |
| Third-party integration | `integration` |
| Realtime chat | `realtime-chat` |

---

## Review routing

Start every review session with [`REVIEW_CONTEXT.md`](../REVIEW_CONTEXT.md).

Typical flow:

```text
REVIEW_CONTEXT.md
  → classify review category
     (production code / tests / documentation / mixed)
  → select applicable guard(s)
  → load that guard's minimal baseline
  → load conditional domain rules
     (only for changed files matching the category)
  → inspect source behavior and implementation
  → prioritized findings
  → unchecked areas
```

Review categories and corresponding guards:

| Category | Guard | Minimal baseline |
|---|---|---|
| Production code | `clean-code-guard` | technology baseline + rules 01, 02, 18, 22 |
| Tests | `test-guard` | technology baseline + rule 16 + PHPUnit reference |
| Documentation | `docs-guard` | technology baseline + verification procedure |
| Mixed | all applicable | union of applicable baselines only |

**Critical:** Do not apply the production-code baseline to a test-only or documentation-only review. Load only the conditional domain rules applicable to the changed files.

---

## Progressive disclosure

1. Load the relevant context/router file first.
2. Load mandatory baseline rules.
3. Load the primary skill.
4. Load references only when the skill explicitly says so.
5. Do not read the full `.cursor` tree unless explicitly asked.

---

## Task classification

Classify tasks by the layer that changes:

- **Backend:** controllers, services, Form Requests, models, jobs
- **Frontend:** Blade, components, CSS, JS
- **Database:** migrations, seeders, factories, Eloquent
- **API:** routes, resources, controllers, Postman
- **RBAC:** permissions, roles, admin routes
- **Tests:** PHPUnit feature/unit tests
- **Documentation:** README, PHPDoc, Postman, `.cursor` docs

---

## Mandatory vs conditional files

### Mandatory

- [`CODE_WRITING_CONTEXT.md`](../CODE_WRITING_CONTEXT.md) for every implementation task
- [`REVIEW_CONTEXT.md`](../REVIEW_CONTEXT.md) for every review task
- [`context/technology-baseline.md`](context/technology-baseline.md) when generating code
- Relevant baseline rules for the changed layer

### Conditional

- Skills: load only the one matching the task type
- References: load only when the skill directs it
- Workflows: load when coordinating multiple skills
- Templates: load when the skill directs it
- Guards: run only when the corresponding content changed

---

## How to add a new skill

1. Choose the correct phase or `specialized/` / `guards/` category.
2. Create a directory named with the skill: `skills/<category>/<skill-name>/`.
3. Add `SKILL.md` with YAML frontmatter:
   ```yaml
   ---
   name: skill-name
   description: Clear description of what the skill does and when to trigger it.
   ---
   ```
4. Keep the main workflow in `SKILL.md`.
5. Add references in `references/` only if they contain distinct useful knowledge.
6. Update this README inventory.
7. Update any workflow or context file that should route to the new skill.

---

## How to add a reference

1. Create the reference inside the governing skill's `references/` directory.
2. Link it directly from `SKILL.md` with a relative path.
3. Explain exactly when the reference must be loaded.
4. Do not duplicate authoritative rules; point to `rules/` instead.

---

## How to update the Laravel/PHP baseline

1. Update the installed versions in `composer.json` and `composer.lock`.
2. Update [`.cursor/context/technology-baseline.md`](context/technology-baseline.md).
3. Update any rule, skill, or template that mentions the old baseline.
4. Re-audit code for version-specific syntax.
5. Update this README.

---

## How to invoke common workflows

Use these prompts with an agent that has loaded the `.cursor` system:

- **New feature:** "Use the development workflow to implement [feature]."
- **Admin CRUD:** "Use the admin-crud-orchestrator skill to create a CRUD module for [Entity]."
- **API endpoint:** "Use create-api-with-postman to add a [description] endpoint."
- **Bug fix:** "Use the hotfix workflow to fix [bug]."
- **Code review:** "Review the current diff using REVIEW_CONTEXT.md."
- **Documentation review:** "Review [file] using docs-guard."

---

## How final guards are selected

Guards are selected based on what changed, not on the task type:

- Production code changed → `clean-code-guard`
- Tests changed → `test-guard`
- Documentation or Postman changed → `docs-guard`
- Mixed changes → run all applicable guards
- Substantial features, modules, APIs, CRUD work, cross-layer changes, or risky fixes also require `feature-finalization-and-validation`

---

## Current counts

Calculated from the final filesystem:

- Total `.cursor` files: **75**
- Skills: **23** (20 implementation/specialized + 3 guards)
- Guards: **3**
- Rules: **22**
- Templates: **7**
- Workflows: **3**
- Context files: **4**
- References: **13**

---

## Quick reference

| Concept | Location pattern |
|---|---|
| Rules | `.cursor/rules/*.mdc` |
| Skills | `.cursor/skills/*/<skill-name>/SKILL.md` |
| References | `.cursor/skills/*/<skill-name>/references/*.md` |
| Workflows | `.cursor/workflows/*.md` |
| Templates | `.cursor/templates/*.md` |
| Context | `.cursor/context/*.md` |
| Execution router | `CODE_WRITING_CONTEXT.md` (project root) |
| Review router | `REVIEW_CONTEXT.md` (project root) |
