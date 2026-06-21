# Domain Modeling Reference

Load this reference when defining the domain model during base-project adaptation.

## Define the domain model

Identify and define:

- core entities (e.g., User, Order, Product, Payment)
- relationships between entities
- domain terminology
- business rules
- lifecycle of main entities
- statuses and enums

Avoid generic naming. Use domain-specific language everywhere.

## Define modules based on domain

Break the system into clear modules based on domain logic.

Examples:

- Users
- Orders
- Payments
- Reports
- Settings

For each module define:

- responsibility
- main entities
- expected actions (CRUD or workflows)

Do not mix unrelated responsibilities in one module.

## Align naming with domain

Ensure all naming reflects the business domain:

- model names
- table names
- route names
- service names
- request classes
- controllers
- variables

Avoid:

- generic names (data, item, thing)
- inconsistent naming

Naming must be clear, consistent, and domain-driven.

## Define roles and permissions

Based on the domain:

- identify user roles
- define permissions per role
- map actions to permissions

Examples:

- Admin
- Manager
- Employee
- Customer

Ensure:

- permissions align with real business rules
- admin route permissions use the `CheckRolePermission` middleware and route-name-to-permission matching
- record-level policies are only used when ownership authorization is genuinely required
- no authorization logic is placed in controllers directly

## Define business workflows

For each main entity:

- define lifecycle
- define state transitions
- define allowed actions per state
- define validation rules per action

Examples:

- Order: created → processing → shipped → delivered
- Payment: pending → paid → failed

Make workflows explicit and consistent.
