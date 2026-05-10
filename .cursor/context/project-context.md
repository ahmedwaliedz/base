# Project Context

## Purpose
Provide a clear understanding of the current project, its type, structure, and scope.

This file defines the overall identity of the project so that all development decisions remain aligned with its purpose.

---

## Project Overview

- Project Name: <project_name>
- Project Type:
  - API only / Admin Dashboard / Fullstack
- Target Users:
  - Admins / Customers / Employees / External systems

---

## Core Goal
Describe the main purpose of the project.

Examples:
- Order management system
- HR system
- E-commerce backend
- Booking system

---

## Tech Stack

- Backend: Laravel (version if known)
- Language: PHP
- Database: MySQL / PostgreSQL
- Queue: Redis / Database / None
- Cache: Redis / File / None
- Auth: Sanctum / Passport / Session

---

## Architecture Style

- Service-based architecture
- Form Request validation
- Thin controllers
- Blade (if used) = presentation only
- API responses use shared response traits

---

## Project Structure

Describe important folders and patterns used:

- Services layer
- Requests (Form Requests)
- Controllers (thin)
- Models
- Policies
- Resources (if used)
- Traits/helpers

---

## Existing Base Features

List reusable features already available in base project:

- Authentication system
- User management
- Role/permission system (if exists)
- Response helpers/traits
- File upload handling
- Common services/utilities

---

## Main Modules (High-Level)

List current or expected modules:

- Users
- Orders
- Payments
- Reports
- Settings
- etc.

---

## API or UI

- API:
  - yes / no
  - public / internal
- UI:
  - admin panel / dashboard / none

---

## Naming Conventions

- Follow project naming patterns
- Use domain-based naming
- Avoid generic names

---

## Constraints

List any important constraints:

- Must follow base project structure
- Must reuse existing services
- Must not introduce new architecture patterns
- Must follow response format

---

## Integration Needs

List known integrations (if any):

- Payment gateway
- SMS provider
- Email service
- External APIs

---

## Documentation Requirements

- APIs must be documented
- Postman examples required (if API exists)
- Response format must be consistent

---

## Testing Expectations

- Validation must be testable
- Services must be testable
- API endpoints must be testable

---

## Current Phase

- Setup / Development / Maintenance

---

## Notes

Any additional project-specific notes.
