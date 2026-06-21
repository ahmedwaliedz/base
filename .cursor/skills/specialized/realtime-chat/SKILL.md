---
name: realtime-chat
description: Implement realtime chat rooms and messaging. Trigger for chat, rooms, read receipts, or typing indicators.
---

# Realtime Chat Skill

## Purpose

Guide implementation of private realtime chat in this Laravel 11 base project, including:

- customer to admin chat
- customer to customer chat
- Laravel Reverb transport option
- Node.js websocket transport option
- optional Vue UI for realtime screens

---

## When to Use

Use this skill when implementing:

- chat inboxes
- chat rooms / threads
- private messages
- read receipts
- unread counters
- typing indicators
- presence / online status

---

## Prerequisite inspection

Before choosing a transport, inspect the actual project state. Do not assume a realtime stack is installed or configured.

### Files to inspect

- `composer.json`
- `composer.lock`
- `package.json`
- JavaScript lockfiles when present (`package-lock.json`, `yarn.lock`, `pnpm-lock.yaml`)
- `config/broadcasting.php`
- `routes/channels.php`
- Broadcasting service providers / bootstrap configuration
- `.env.example`
- Frontend bootstrap files (`resources/js/bootstrap.js` or similar)
- Existing websocket or broadcasting code

### Classify the current state

| State | Meaning |
|---|---|
| **Directly declared, installed, and configured** | The transport package is directly declared in the appropriate manifest (`composer.json` under `require` or `require-dev`, or `package.json` under `dependencies` or `devDependencies`). Installation or resolution is confirmed. Required configuration, channel authorization, frontend integration, and environment variables are present. |
| **Directly declared and installed, but not fully configured** | The transport package is directly declared in the manifest. Installation or resolution is confirmed. Some configuration, channel authorization, frontend initialization, or integration is missing. |
| **Directly declared, but not installed or resolved** | The package is directly declared in the manifest but not present in the relevant lockfile or package-manager installed-state output. It is not executable until installation is completed with explicit authorization where required. |
| **Transitively resolved only** | The package is absent from the direct dependency sections of the manifest but appears in a lockfile or package-manager listing because another package depends on it. It must not be treated as an authorized direct project dependency. |
| **Not declared or resolved** | The package is not directly declared in any manifest and not present in resolved or installed package state. No reliable project adoption exists. Adding it is a new infrastructure decision requiring explicit authorization. |
| **Existing custom or alternative transport** | A working Reverb, Pusher, Socket.IO, `ws`, raw WebSocket, or other transport already exists in the project. |

If the project already has broadcasting or websocket infrastructure, **prefer the established transport**. Do not introduce Reverb or a Node gateway as a second transport without explicit justification.

---

## Secrets safety

- Inspect `.env.example` for expected variable names and documentation.
- Inspect configuration files for how environment keys are consumed.
- Inspect application code for required config keys.
- Never read, expose, print, or include the contents of the current `.env` file.
- Never use `Get-Content`, `Select-String`, `type`, `cat`, or equivalent commands on `.env`.
- Use `Test-Path .env` only to establish whether a local environment file exists; do not read its contents.
- If runtime environment verification is needed, ask the user for authorization and verify only specific key presence without exposing its value.
- Prefer Laravel configuration access and safe diagnostic commands that redact values.
- `.env.example` is the documentation source of truth for environment variables.

---

## Transport Decision

- Choose **Laravel Reverb** OR **Node.js websocket gateway** only after prerequisite inspection.
- Do not implement both unless explicitly requested.
- If the desired transport is **not installed**, treat it as a new infrastructure dependency.
  - Report the missing prerequisite.
  - Request explicit authorization before adding packages or modifying infrastructure.
  - Produce an implementation and dependency plan before creating code that assumes the transport exists.
  - Do **not** run `composer require`, `npm install`, or similar commands automatically from this skill.
- If the project already uses a different transport, prefer that transport unless the user explicitly requests migration.
- In all cases, Laravel remains the source of truth for database writes, authorization, validation, and business rules.

### Reverb path

Before using Reverb, verify:

- `laravel/reverb` is **directly declared** in `composer.json` (under `require` or `require-dev`). Use `composer.json` as the authoritative source; do not rely on `composer.lock` or `composer show` output alone, as they may reflect transitive dependencies.
- `config/broadcasting.php` exists and Reverb is configured.
- `routes/channels.php` contains channel authorization for chat rooms.
- Laravel Echo and the necessary frontend client dependencies exist when browser subscriptions are required. These should be **directly declared** in `package.json` (under `dependencies` or `devDependencies`).
- Required environment variables are documented in `.env.example`.

If Reverb is not installed, do not generate Reverb-specific code. Load the reference only to build the plan.

### Node.js websocket path

Before using a Node gateway, verify:

- An existing Node websocket service or package exists and is **directly declared** in `package.json` (under `dependencies` or `devDependencies`). Use the manifest as the authoritative source; do not rely on lockfiles or `npm ls` output alone to prove direct declaration.
- Its project location is known.
- Server/client packages are properly installed and integrated.
- Authentication and message contracts are known.
- Process management and deployment approach are defined.

If no Node websocket stack exists, treat it as new infrastructure. Require explicit authorization. Produce an implementation and dependency plan before creating it. Do not assume Socket.IO; raw WebSocket or another transport may be selected deliberately.

---

## Core Architecture

- thin controllers
- Form Requests for validation
- Services for business logic
- Eloquent models and relationships
- API Resources for API responses
- Blade / Vue only for presentation and local UI state
- no DB queries or service calls in Blade
- no business rules in Node.js
- route names aligned with RBAC for admin routes

---

## Data Model

Load [`references/data-model.md`](references/data-model.md) when designing the schema.

Key responsibilities:

- `findOrCreateDirectRoom(participantA, participantB)` / `findOrCreateDirectThread`
- `sendMessage(room, sender, body, type?, attachments?)`
- `getRoomMessages(room, pagination)`
- `markRoomAsRead(room, member)`
- `getInbox(participant)`
- `authorizeMember(room, member): bool`
- `getUnreadCounts(member)`

Services must eager-load displayed relations and avoid N+1.

---

## Transport-specific Guidance

Load only the selected transport reference, and only after prerequisites are satisfied or an explicit plan exists:

- **Laravel Reverb:** load [`references/reverb-transport.md`](references/reverb-transport.md)
- **Node.js websocket gateway:** load [`references/node-websocket-transport.md`](references/node-websocket-transport.md)

---

## Implementation workflow

1. **Inspect prerequisites** — check `composer.json`, `composer.lock`, `package.json`, lockfiles, `config/broadcasting.php`, `routes/channels.php`, providers, `.env.example`, and frontend bootstrap files.
2. **Classify current state** — directly declared, installed, and configured / directly declared and installed, but not fully configured / directly declared, but not installed or resolved / transitively resolved only / not declared or resolved / existing custom or alternative transport.
3. **Choose or confirm transport** — Reverb, Node.js, or the project's existing transport.
4. **Request authorization** when installation or infrastructure changes are needed.
5. **Load the selected reference** only after the transport is confirmed or planned.
6. **Implement** after prerequisites are satisfied.
7. **Test** the selected path.
8. **Document** the selected transport, env variables, run commands, events, payloads, auth flow, and deployment notes.

---

## Security Rules

- Every room, thread, or message action must verify member/participant access.
- Admin routes must follow the RBAC route-name permission convention (see [`../../../rules/08-custom-rbac.mdc`](../../../rules/08-custom-rbac.mdc)).
- Use Sanctum / session auth as appropriate.
- Rate-limit message send endpoints.
- Validate body length and attachment limits.
- Escape message content in UI.
- Do not log sensitive message body unless explicitly justified.
- Protect websocket auth endpoints from CSRF / CORS mistakes.

---

## Performance Rules

- Paginate messages.
- Eager-load sender/participant relations.
- Avoid N+1 in inbox and thread views.
- Use indexes for unread counts and message history.
- Avoid broadcasting huge payloads.

---

## Testing Strategy

Require tests for:

- creating / opening a room or thread
- sending a message
- unauthorized participant access blocked
- admin / customer authorization
- customer / customer authorization
- read receipt / unread count updates
- validation failures
- broadcast event dispatched or Node gateway contract mocked
- API response format where APIs are exposed

---

## Documentation Checklist

Require docs for:

- prerequisite inspection results (current transport state)
- selected transport: Reverb OR Node.js OR existing project transport
- authorization decisions for new packages or infrastructure
- env variables
- run commands
- channel / socket event names
- payload examples
- auth flow
- deployment notes
- test coverage

---

## Completion Standard

Feature is not complete unless:

- architecture rules are followed
- transport choice is documented
- authorization is enforced
- tests are added
- UI states are handled if UI exists
- deployment/run instructions are documented
- final review against [`../../../rules/22-code-review.mdc`](../../../rules/22-code-review.mdc) is complete

---

## Prompt Template

```text
Use the `realtime-chat` skill to implement [feature name].

Transport choice after prerequisite inspection:
- [Laravel Reverb / Node.js websocket gateway / existing project transport]

Current state:
- [directly declared, installed, and configured / directly declared and installed, but not fully configured / directly declared, but not installed or resolved / transitively resolved only / not declared or resolved / existing custom or alternative transport]

Authorization:
- [authorization already granted / request authorization for new packages or infrastructure]

Scope:
- [customer-admin chat / customer-customer chat / both]
- [API only / admin UI / customer UI / Vue realtime UI]

Follow project rules:
- inspect prerequisites before generating transport code
- Form Requests
- Services
- RBAC for admin routes
- Laravel source of truth
- no business logic in Blade/Vue/Node
- tests and documentation required
```
