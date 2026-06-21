# Docs Guard — Verification Procedure

Use this procedure when verifying documentation claims.

## Generic verification steps

1. Identify the audience and purpose of the documentation.
2. List every factual claim (class, method, route, config key, env key, field, status code, path, version, example).
3. For each claim, locate the authoritative source in the project.
4. Mark claims as verified, outdated, or unverifiable.
5. Check internal links resolve.
6. Check code samples compile/run against the current baseline.

## API / Postman documentation

1. Verify endpoints exist in `routes/api/v1/*.php` or the relevant route file.
2. Verify HTTP methods and route names.
3. Verify the controller method exists and uses the documented Form Request.
4. Derive the request body from the Form Request `rules()` method.
5. Verify response fields from the JsonResource or response trait.
6. Verify status codes:
   - `200` for success
   - `201` for created
   - `422` for validation errors
   - `401` for unauthenticated
   - `403` for forbidden
   - `404` for not found
   - `409` for conflict where applicable
7. Use realistic seeded values in examples.
8. Ensure examples cover success, validation, unauthorized, forbidden, and not-found cases.

## README / configuration documentation

1. Verify installation steps match `composer.json`, `package.json`, and `.env.example`.
2. Verify command examples exist as Artisan commands.
3. Verify environment variables appear in `.env.example` or config files.
4. Verify referenced paths exist.
5. Verify version statements match `.cursor/context/technology-baseline.md`.

## PHPDoc

1. Verify parameter types match method signatures.
2. Verify return types match actual returns.
3. Verify thrown exceptions are actually thrown.
4. Remove PHPDoc that merely repeats the type hint.

## Workflows / templates / .cursor documentation

1. Verify every referenced skill path uses the new `skill-name/SKILL.md` structure.
2. Verify every referenced rule file uses the `.mdc` extension.
3. Verify every referenced template exists.
4. Verify version guidance is clearly marked as current or future.
