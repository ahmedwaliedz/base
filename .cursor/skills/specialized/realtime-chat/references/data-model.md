# Realtime Chat Data Model

Load this reference when designing the database layer for a chat feature.

## Recommended default schema

The room-based model below is the starting point. Adapt table names to existing project conventions if they are already established.

### `rooms`

| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | primary key |
| private | boolean | default depends on feature |
| type | string / enum | `direct`, `customer_service`, `group`, `advertising` |
| creatable_type, creatable_id | polymorphic, nullable | optional creator |
| last_message_id | unsignedBigInteger, nullable | optional FK to `messages` |
| created_at, updated_at | timestamp | |

`rooms` represents the conversation or thread. Do not add domain-specific columns by default; use a polymorphic context relation when a chat belongs to an order, ticket, etc.

`last_message_id` should be nullable. Add it as an indexed nullable ID. Add a foreign key only after the `messages` table exists, or leave it as an indexed nullable ID to avoid circular migration problems.

Add indexes for `type`, `private`, creator columns, and `last_message_id` only when queried.

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

Indexes:

- unique on `(room_id, memberable_type, memberable_id)`
- index on `(memberable_type, memberable_id)` for inbox queries

Participant-level read fields are the lightweight default for unread counts. Add `last_read_message_id` as an FK only after both tables exist, or leave it as an indexed nullable ID.

### `messages`

| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | primary key |
| room_id | unsignedBigInteger | FK to `rooms`, cascade on delete |
| senderable_type | string | polymorphic sender |
| senderable_id | unsignedBigInteger | polymorphic sender ID |
| body | text, nullable | null when non-text messages are allowed |
| name | string, nullable | original file / display name |
| type | string / enum | `text`, `file`, `map`, `sound`, `image`, `video` |
| duration | decimal / unsignedInteger, nullable | milliseconds for sound/video |
| created_at | timestamp | |
| updated_at | timestamp | |

Index on `(room_id, created_at)` for message pagination. Validate the sender is a room member before storing a message.

### `message_notifications` (optional)

Use only when the product needs per-message seen/sender/flagged state. For simple unread counts, prefer `room_members.last_read_message_id` or `last_read_at`.

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

Indexes:

- unique on `(message_id, userable_type, userable_id)`
- index on `(room_id, userable_type, userable_id)`

## Naming alternatives

`chat_threads`, `chat_participants`, and `chat_messages` are acceptable if the project prefers thread terminology.

## Guidance

- Use polymorphic participants/senders if admins and users share chat tables.
- Index participant and member lookup columns.
- Index `room_id` (or `chat_thread_id`) and `created_at` for message pagination.
- Avoid expensive full message scans for inbox unread counts.
- Avoid storing duplicate calculated values unless needed for performance.
