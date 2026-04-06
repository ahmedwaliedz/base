# Feature to Module Execution Skill

## Purpose
Transform a feature requirement into a fully structured module aligned with the project architecture.

---

## When to Use
- After feature analysis is completed
- When implementing a new feature or extending an existing module

---

## Core Principle
- Every feature must map to a clear module or extend an existing one.
- Do not implement features in isolation without module structure.

---

## Process

### 1. Identify Target Module
- Determine:
  - existing module → extend
  - new module → create

---

### 2. Define Scope
- Entities involved
- Actions (CRUD or workflows)
- API or UI or both

---

### 3. Map Feature to Layers

- Database (if needed)
- Validation (Form Request)
- Service (business logic)
- Controller
- Routes
- API/UI layer

---

### 4. Ensure Architecture Compliance

- Validation in Form Requests
- Logic in Services
- Thin Controllers
- Blade presentation-only

---

### 5. Prepare Execution Plan

- Step-by-step implementation plan
- Order of execution

---

## Output Format

- Module name
- Feature scope
- Layers mapping
- Execution plan
