# Integration Webhook Security

Load this reference when receiving webhooks or callbacks from an external provider.

## Required implementation

- dedicated endpoints
- signature or secret validation
- payload validation
- idempotent processing
- logging for traceability

## Ensure

- webhook requests are authenticated or verified properly
- repeated callbacks do not create duplicate effects
- malformed payloads are handled safely

## Security review

Before finalizing the integration, verify:

- secrets are in environment/config only
- incoming payloads are validated
- webhook signatures are verified
- sensitive data is protected
- provider errors do not leak sensitive internals
- external inputs are not trusted blindly

Security is part of completion, not an optional enhancement.
