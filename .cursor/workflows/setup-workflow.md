# Setup Workflow

## Purpose

Coordinate the correct sequence for preparing a new project starting from the team base project. This workflow ensures the new project is initialized, adapted to the new domain, and reviewed before feature development begins.

## Trigger

- Starting a new project from the base project
- Preparing the project foundation before the first feature
- Onboarding a new domain on top of the base project

## Preconditions

- The base project repository is available.
- Domain requirements are known at a high level.

## Workflow

### Step 1: Initialize from Base Project

- **Primary skill:** [`initialize-from-base-project`](../skills/setup-phase/initialize-from-base-project/SKILL.md)
- **Output:** Base project summary, reusable parts, customization needs, setup gaps

### Step 2: Adapt Base Project to New Domain

- **Primary skill:** [`adapt-base-project-to-new-domain`](../skills/setup-phase/adapt-base-project-to-new-domain/SKILL.md)
- **Output:** Domain summary, modules, entities, roles, permissions, workflows, enums

### Step 3: Prepare Project Context

- **Files:** `.cursor/context/project-context.md`, `.cursor/context/domain-context.md`, `.cursor/context/team-context.md`
- **Output:** Completed context files

### Step 4: Review Project Readiness

- **Primary skill:** [`project-readiness-review`](../skills/setup-phase/project-readiness-review/SKILL.md)
- **Output:** Readiness report, identified gaps, final readiness decision

## Guard passes

- Context or documentation changed → [`docs-guard`](../skills/guards/docs-guard/SKILL.md)
- Configuration or setup code changed → [`clean-code-guard`](../skills/guards/clean-code-guard/SKILL.md)

## Verification

- Confirm context files exist and are internally consistent.
- Confirm no broken links between setup artifacts and project source.
- Confirm the project can run basic commands (`php artisan --version`, `composer install` if needed).

## Completion criteria

The setup workflow is complete only when:

- The base project is understood.
- The project is adapted to the new domain.
- Context files are prepared.
- Readiness review is complete.
- The project is ready for feature implementation.

## Rules enforcement

- Do not start feature development before readiness review is complete.
- Do not skip domain adaptation.
- Do not rebuild shared base project foundations.
- Reuse existing architecture and team conventions.
