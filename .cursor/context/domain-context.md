# Domain Context

## Purpose
Define the business domain, its entities, relationships, and workflows.

---

## Domain Overview

Describe the business domain in simple terms.

Example:
"This system manages orders, payments, and delivery processes."

---

## Core Entities

List main entities:

- User
- Order
- Product
- Payment
- etc.

---

## Relationships

Describe relationships:

- User has many Orders
- Order belongs to User
- Order has many Products

---

## Key Workflows

Describe main flows:

### Example:
Order Flow:
- Created
- Confirmed
- Processing
- Shipped
- Delivered

---

## Roles

List roles:

- Admin
- Manager
- User

---

## Permissions

Describe what each role can do.

---

## Important Business Rules

Examples:
- Order cannot be shipped before payment
- User cannot delete processed order

---

## Enums / Statuses

List all important enums:

- order_status
- payment_status

Explain values and meaning.

---

## Edge Cases

List special cases:

- failed payments
- canceled orders
- expired sessions

---

## Domain Constraints

Any domain-specific limitations.
