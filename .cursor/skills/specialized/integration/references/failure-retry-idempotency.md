# Integration Failure Handling, Retries, and Idempotency

Load this reference when designing how an integration responds to external failures.

## Failure scenarios to handle

- timeouts
- connection failures
- authentication failures
- invalid responses
- rate limiting
- partial failures
- provider downtime
- business-level rejection from the provider

## Rules

- Do not allow raw external failures to break unrelated parts of the system.
- Return predictable internal errors.
- Distinguish between technical failure and business failure.
- Surface enough information for debugging without exposing sensitive data.

## Retry strategy

When relevant, define:

- whether the request is safe to retry
- how many retries are acceptable
- how retry delay should work
- whether the external action must be idempotent

Examples:

- payment attempts
- webhook processing
- order sync jobs
- notification dispatch

Do not retry blindly. Do not create duplicate external operations.

## Idempotency

- Use idempotency keys when the provider supports them.
- Track processed provider references to avoid duplicate effects.
- Make internal handlers idempotent so repeated safe retries do not corrupt state.
