# Setup Workflow

## Purpose
Define the correct sequence for preparing a new project starting from the team base project.

This workflow ensures the new project is initialized, adapted to the new domain, and reviewed before feature development begins.

---

## When to Use
- When starting a new project from the base project
- When preparing the project foundation before the first feature
- When onboarding a new domain on top of the base project

---

## Workflow Steps

### Step 1: Initialize from Base Project
Use:
- initialize-from-base-project skill

Goals:
- understand the existing base project
- identify reusable foundations
- identify required customizations
- identify parts to remove, ignore, or defer

Output:
- base project summary
- reusable parts
- customization needs
- setup gaps

---

### Step 2: Adapt Base Project to New Domain
Use:
- adapt-base-project-to-new-domain skill

Goals:
- define the domain model
- define modules
- align naming with business terminology
- define workflows, roles, and permissions
- remove irrelevant base features

Output:
- domain summary
- modules
- entities and relationships
- roles and permissions
- workflows and enums

---

### Step 3: Prepare Project Context
Use or update:
- project-context.md
- domain-context.md
- team-context.md

Goals:
- document the project clearly
- define business meaning
- keep team preferences visible
- make future AI decisions more accurate

Output:
- completed context files

---

### Step 4: Review Project Readiness
Use:
- project-readiness-review skill

Goals:
- confirm architecture compliance
- confirm domain clarity
- confirm module clarity
- confirm API/UI readiness
- confirm configuration and test readiness

Output:
- readiness report
- identified gaps
- final readiness decision

---

## Rules Enforcement
- Do not start feature development before readiness review is complete.
- Do not skip domain adaptation.
- Do not rebuild shared base project foundations.
- Reuse existing architecture and team conventions.

---

## Completion Standard
The setup workflow is complete only when:
- the base project is understood
- the project is adapted to the new domain
- context files are prepared
- readiness review is complete
- the project is ready for feature implementation
