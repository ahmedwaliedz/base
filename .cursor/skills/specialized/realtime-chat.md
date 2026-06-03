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
- Laravel broadcast channels
- Node.js websocket gateways
- Vue realtime chat screens

---

## Transport Decision

- Choose **Laravel Reverb** OR **Node.js websocket gateway** per implementation.
- Do not implement both unless explicitly requested.
- Use Reverb when we want Laravel-native broadcasting, channel auth, Echo, and fewer moving parts.
- Use Node.js when the product needs a separate websocket process, custom socket rooms, transport-level fanout, or future non-Laravel clients.
- In both cases, Laravel remains the source of truth for database writes, authorization, validation, and business rules.

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

## Suggested Data Model

The room-based model below is the recommended default for reusable chat features in this project. The schema is still a starting point and must be adapted after feature analysis. It supports direct chat, customer service chat, group rooms, multiple member types, multiple message types, and optional per-message state. Use different table names only if the project or domain already has stronger naming conventions. Regardless of the chosen schema, always maintain Laravel data integrity, authorization, indexing, and performance rules.

### `rooms`

| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | primary key |
| private | boolean | default true or false depending on feature |
| type | string / enum | `direct`, `customer_service`, `group`, `advertising` |
| creatable_type, creatable_id | polymorphic, nullable | optional creator of the room |
| last_message_id | unsignedBigInteger, nullable | optional FK to `messages` if migration order allows |
| created_at, updated_at | timestamp | |

`rooms` represents the conversation or thread. Do not add `order_id` or other domain-specific columns by default. If the chat belongs to an order, ad, ticket, booking, or any domain object later, add a domain-specific relation or a flexible context polymorphic relation at that time.

`last_message_id` should be nullable. Prefer nullable `last_message_id` over default `0`. Add it as an indexed nullable ID in the `rooms` migration if needed. Add a foreign key only after the `messages` table exists, or leave it as an indexed nullable ID if migration order makes the FK awkward. Avoid circular migration problems.

Add indexes for `type`, `private`, creator columns, and `last_message_id` only when they are queried.

### `room_members`

| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | primary key |
| room_id | unsignedBigInteger | FK to `rooms`, cascade on delete |
| memberable_type | string | polymorphic participant |
| memberable_id | unsignedBigInteger | polymorphic participant ID |
| last_read_message_id | unsignedBigInteger, nullable | optional participant-level read pointer |
| last_read_at | timestamp, nullable | when participant last read |
| unread_count | unsignedInteger, default 0 | store only if performance requires it |
| created_at | timestamp | |
| updated_at | timestamp | |

Indexes: unique on `(room_id, memberable_type, memberable_id)`, index on `(memberable_type, memberable_id)` for inbox queries.

Participant-level read fields are the lightweight default for unread counts. If `last_read_message_id` uses an FK to `messages`, add it only after both tables exist or leave it as an indexed nullable ID to avoid circular migration problems.

### `messages`

| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | primary key |
| room_id | unsignedBigInteger | FK to `rooms`, cascade on delete |
| senderable_type | string | polymorphic sender |
| senderable_id | unsignedBigInteger | polymorphic sender ID |
| body | text, nullable | null when non-text messages are allowed |
| name | string, nullable | original file / display name if needed |
| type | string / enum | `text`, `file`, `map`, `sound`, `image`, `video` |
| duration | decimal / unsignedInteger, nullable | milliseconds for sound / video if needed |
| created_at | timestamp | |
| updated_at | timestamp | |

Index on `(room_id, created_at)` for message pagination. Validate the sender is a room member before storing a message. Keep large file metadata or media-library records separate if messages become attachment-heavy.

### `message_notifications` (optional)

| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | primary key |
| room_id | unsignedBigInteger | FK to `rooms`, cascade on delete |
| message_id | unsignedBigInteger | FK to `messages`, cascade on delete |
| userable_type | string | polymorphic user |
| userable_id | unsignedBigInteger | polymorphic user ID |
| is_seen | boolean, default false | |
| is_sender | boolean, default false | |
| is_flagged | boolean, default false | |
| created_at | timestamp | |
| updated_at | timestamp | |

Indexes: unique on `(message_id, userable_type, userable_id)`, index on `(room_id, userable_type, userable_id)`.

Use this table only when the product needs per-message seen / sender / flagged state. For simple unread counts, prefer `room_members.last_read_message_id` or `last_read_at`.

### Naming Alternative

`chat_threads`, `chat_participants`, and `chat_messages` are acceptable naming alternatives if the project prefers thread terminology. The architectural idea is the same: conversation container, participants, messages, and optional per-user message state. Follow existing project or domain naming conventions if they are already established.

**Guidance:**

- Use polymorphic participants / senders if admins and users share chat tables.
- Index participant and member lookup columns.
- Index `room_id` (or `chat_thread_id`) and `created_at` for message pagination.
- Participant-level read fields are the lightweight default for unread counts. These fields are optional and should be chosen based on product needs.
- If `last_read_message_id` uses an FK, add it only after both tables exist or leave it as an indexed nullable ID to avoid circular migration problems.
- Use `message_notifications` (or `chat_message_reads`) only when the product needs per-message state.
- Avoid expensive full message scans for inbox unread counts.
- Avoid storing duplicate calculated values unless needed for performance.

---

## Backend Flow

Implementation order:

1. migrations
2. models and relationships
3. services
4. Form Requests
5. controllers
6. API Resources
7. routes
8. events / broadcasts or Node gateway contract
9. UI
10. tests
11. documentation

---

## Service Responsibilities

Service methods:

- `findOrCreateDirectRoom(participantA, participantB)` / `findOrCreateDirectThread`
- `sendMessage(room, sender, body, type?, attachments?)` / `sendMessage(thread, sender, body, attachments?)`
- `getRoomMessages(room, pagination)` / `getThreadMessages(thread, pagination)`
- `markRoomAsRead(room, member)` / `markThreadAsRead(thread, participant)`
- `getInbox(participant)` — returns room or thread list
- `authorizeMember(room, member): bool` / `authorizeParticipant(thread, participant): bool`
- `getUnreadCounts(member)` / `getUnreadCounts(participant)`

Services must eager-load displayed relations and avoid N+1.

---

## Reverb Implementation Rules

- Use Laravel events implementing broadcast contracts.
- Define private / presence channel authorization in Laravel.
- Do not expose rooms or threads to non-members / non-participants.
- Use Echo on frontend.
- Broadcast after message persistence succeeds.
- Decide queued vs immediate broadcasting intentionally.
- Document required `.env` variables.
- Include local and production run commands / checklist.

---

## Node.js Websocket Gateway Rules

- Node handles websocket connections and rooms only.
- Laravel owns persistence and authorization.
- Clients must authenticate before joining rooms.
- Node must call Laravel API / internal endpoint or verify signed tokens before room join.
- Node must not write directly to DB unless explicitly approved.
- Define message contract between Laravel and Node.
- Handle reconnects, duplicate delivery, and idempotency.
- Document required `.env` variables, ports, CORS / origin rules, and process manager setup.

---

## Vue UI Rules

- Vue is optional and only added when needed.
- Use Vite.
- Keep components small and focused.
- Use Echo for Reverb subscriptions.
- Use websocket client for Node path.
- Keep message rendering escaped / safe.
- Support loading, empty, failed-send, reconnecting, and pagination states.
- Do not hide backend validation errors.
- Keep admin UI consistent with existing dark RTL dashboard.

---

## Security Rules

- Every room, thread, or message action must verify member / participant access.
- Admin routes must follow RBAC route-name permission convention.
- Use Sanctum / session auth as appropriate.
- Rate-limit message send endpoints.
- Validate body length and attachment limits.
- Escape message content in UI.
- Do not log sensitive message body unless explicitly justified.
- Protect websocket auth endpoints from CSRF / CORS mistakes.

---

## Performance Rules

- Paginate messages.
- Eager-load sender / participant relations.
- Avoid N+1 in inbox and thread views.
- Use indexes for unread counts and message history.
- Consider pruning / archiving only if product requires it.
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

- selected transport: Reverb OR Node.js
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

- architecture rules followed
- transport choice documented
- authorization enforced
- tests added
- UI states handled if UI exists
- deployment / run instructions documented
- final review against `.cursor/rules/22-code-review.mdc`

---

## Prompt Template

```
Use `.cursor/skills/specialized/realtime-chat.md` to implement [feature name].

Transport choice:
- [Laravel Reverb OR Node.js websocket gateway]

Scope:
- [customer-admin chat / customer-customer chat / both]
- [API only / admin UI / customer UI / Vue realtime UI]

Follow project rules:
- thin controllers
- Form Requests
- Services
- RBAC for admin routes
- Laravel source of truth
- no business logic in Blade/Vue/Node
- tests and documentation required
```
