# Domain Context

## Purpose

Define the business domain, its entities, relationships, and workflows.

---

## Domain Overview

This is a **base Laravel project** designed to be adapted for various domains (e-commerce, HR, booking, etc.).

Current built-in modules:
- User/Admin management
- Geographic data (Countries, Cities, Regions)
- Notifications
- Settings
- Authentication (password + OTP)

---

## Core Entities

### Built-in Entities

| Entity | Description |
|--------|-------------|
| Admin | System administrators |
| User | Application users (customers, employees) |
| Role | User roles (Super Admin, Admin, User) |
| Country | Geographic countries |
| City | Cities within countries |
| Region | Regions/states within countries |
| Notification | System notifications |
| Setting | Application settings |

### Entity Relationships

```
Country
  └── Cities (hasMany)
  └── Regions (hasMany)

Admin
  └── Role (belongsTo)
  └── Notifications (hasMany - as creator)

User
  └── Role (belongsTo)
  └── Notifications (hasMany)

Notification
  └── Notifiable (morphTo - Admin or User)
```

---

## Key Workflows

### Authentication Flow
1. User enters credentials (email/phone + password OR phone + OTP)
2. System validates credentials
3. On success: Create Sanctum token
4. Return token to client

### OTP Flow
1. User requests code (enters phone/email)
2. System generates random code
3. System sends via SMS/Email
4. User enters code
5. System verifies code
6. On success: Create token

### Export Flow
1. User clicks export button
2. Controller receives request
3. Service prepares data
4. Export strategy (Excel/PDF/Word) generates file
5. Return file to user

---

## Roles

| Role | Description | Access Level |
|------|-------------|---------------|
| Super Admin | Full system access | All |
| Admin | Admin panel access | Limited to assigned |
| User | Regular user | Basic |

---

## Permissions

Currently managed via roles. Permissions can be assigned to roles:
- view_{module}
- create_{module}
- update_{module}
- delete_{module}
- export_{module}

---

## Important Business Rules

- Admins cannot delete themselves
- Soft deletes used for reversible deletions
- All timestamps use UTC
- Language defaults to Arabic (ar)

---

## Enums / Statuses

### Common Statuses

| Field | Values |
|-------|--------|
| is_active | true (active), false (inactive) |
| is_featured | true (featured), false (not featured) |

### Authentication

| Status | Description |
|--------|-------------|
| pending | OTP sent, awaiting verification |
| verified | OTP verified, login complete |
| failed | Authentication failed |

---

## Edge Cases

- **Duplicate entries** - Prevent duplicate emails/phones
- **Orphan records** - Use cascade deletes
- **Empty responses** - Return appropriate empty states
- **Large datasets** - Use pagination
- **Export timeouts** - Use queued exports for large data

---

## Domain Constraints

- All dates in UTC
- Primary language: Arabic
- Secondary language: English
- Currency: SAR (configurable)
- Timezone: Asia/Riyadh (configurable)

---

## Adaptability Notes

This base project can be extended with:
- E-commerce (products, orders, payments)
- HR (employees, leaves, payroll)
- Booking (reservations, schedules)
- CMS (pages, posts, categories)

To adapt, add:
- Domain-specific models
- Domain-specific services
- Domain-specific views
- Domain-specific API endpoints