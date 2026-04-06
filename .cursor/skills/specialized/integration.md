# Integration Skill

## Purpose
Implement and manage integrations with external systems and third-party services in a clean, secure, and maintainable way.

This includes integrations such as:
- external APIs
- payment gateways
- SMS providers
- email providers
- shipping providers
- ERP/CRM systems
- webhooks
- any third-party service required by the project

---

## When to Use
- When connecting the project to any external system
- When consuming third-party APIs
- When sending data to external services
- When receiving webhooks or callbacks
- When syncing data between systems
- When adding payment, messaging, shipping, or notification providers

---

## Core Principle
- Isolate integration logic from core business logic.
- Do not call external services directly from controllers, Blade views, or unrelated classes.
- Keep integrations explicit, testable, and replaceable.

---

## Goals
- Keep integration code organized
- Protect the system from external failures
- Maintain clean separation of concerns
- Normalize external data before using it internally
- Ensure secure and observable integration behavior

---

## Process

### 1. Define the Integration Scope
Clearly define:

- external service name
- purpose of the integration
- request direction:
  - outbound requests
  - inbound webhooks/callbacks
  - both
- required actions
- expected responses
- failure scenarios
- authentication method
- configuration needs

Do not start implementation before the integration contract is understood.

---

### 2. Create a Dedicated Integration Layer
Place external integration logic in dedicated classes.

Examples:
- Service classes
- Clients
- Connectors
- Adapters

Responsibilities of the integration layer:
- build requests
- send requests
- parse responses
- map external data into internal format
- handle provider-specific logic

Do not mix integration-specific code into controllers or unrelated domain services.

---

### 3. Keep Controllers Thin
Controllers should only:
- validate request input
- call internal service or integration service
- return the project-standard response

Do not:
- build external HTTP requests in controllers
- handle raw external responses directly in controllers
- place retries or provider-specific conditions inside controllers

---

### 4. Configuration and Secrets
All provider configuration must be centralized.

Use:
- config files
- environment variables

Configuration may include:
- API keys
- secrets
- base URLs
- webhook secrets
- timeout values
- retry settings

Rules:
- never hardcode secrets
- never expose secrets in logs or responses
- keep provider config explicit and versionable where appropriate

---

### 5. Request and Response Mapping
External systems often have formats that should not leak into the core application.

Always:
- map external request/response structures explicitly
- normalize provider data before using it internally
- convert external field names into project naming conventions
- isolate provider-specific enums/statuses from internal business logic when needed

Do not let the internal system depend directly on unstable third-party response shapes.

---

### 6. Error Handling and Failure Strategy
External integrations can fail for many reasons.

Handle explicitly:
- timeouts
- connection failures
- authentication failures
- invalid responses
- rate limiting
- partial failures
- provider downtime
- business-level rejection from the provider

Rules:
- do not allow raw external failures to break unrelated parts of the system
- return predictable internal errors
- distinguish between technical failure and business failure
- surface enough information for debugging without exposing sensitive data

---

### 7. Retry and Idempotency
When relevant, define a retry strategy.

Consider:
- whether the request is safe to retry
- how many retries are acceptable
- how retry delay should work
- whether the external action must be idempotent

Examples:
- payment attempts
- webhook processing
- order sync jobs
- notification dispatch

Do not retry blindly.  
Do not create duplicate external operations.

---

### 8. Webhooks and Incoming Callbacks
If the provider sends data into the system:

Implement:
- dedicated endpoints
- signature or secret validation
- payload validation
- idempotent processing
- logging for traceability

Ensure:
- webhook requests are authenticated or verified properly
- repeated callbacks do not create duplicate effects
- malformed payloads are handled safely

---

### 9. Logging and Observability
Integration behavior must be traceable.

Log when appropriate:
- outbound requests metadata
- inbound webhook events
- failures
- retry attempts
- provider errors
- sync issues

Rules:
- do not log secrets
- do not log sensitive personal data unless explicitly justified and safe
- keep logs useful for debugging and operations

---

### 10. Testing Strategy
Integration code must be testable.

Test:
- successful external response handling
- failure handling
- timeout scenarios
- invalid payload handling
- mapping correctness
- webhook verification logic
- idempotency when relevant

Prefer:
- mocked provider responses
- isolated service tests
- feature tests for integration endpoints/webhooks where useful

Do not make tests depend on live third-party systems unless explicitly required.

---

### 11. Documentation
Document the integration clearly.

Include:
- provider name
- purpose
- endpoints used
- required environment variables
- authentication method
- expected request/response flow
- failure behavior
- retry behavior
- webhook details
- setup steps for local/staging/production

Documentation must be explicit enough for another developer to maintain the integration safely.

---

### 12. Security Review
Before finalizing the integration, verify:

- secrets are in environment/config only
- incoming payloads are validated
- webhook signatures are verified
- sensitive data is protected
- provider errors do not leak sensitive internals
- external inputs are not trusted blindly

Security is part of completion, not an optional enhancement.

---

## Rules Enforcement

- Do not call third-party services directly from controllers.
- Do not hardcode API keys, secrets, or provider URLs.
- Do not leak external response structures into core domain logic without mapping.
- Do not skip timeout, error, and failure handling.
- Do not process webhooks without verification and validation.
- Do not build integrations without considering retries and idempotency where relevant.
- Keep integration code isolated, configurable, and testable.

---

## Output Format

- Integration summary
- Provider/system name
- Use case
- Configuration requirements
- Service/client structure
- Request/response mapping
- Error handling strategy
- Retry/idempotency strategy
- Webhook handling approach (if applicable)
- Logging/observability notes
- Testing strategy
- Documentation notes

---

## Completion Standard

An integration is NOT complete unless:

- integration scope is clearly defined
- dedicated integration classes are used
- configuration and secrets are externalized
- request/response mapping is explicit
- failure handling is implemented
- retries/idempotency are considered where relevant
- webhook handling is secure and validated when applicable
- logging is sufficient
- tests are included
- documentation is complete
