# Realtime Chat — Laravel Reverb Transport

Load this reference when implementing a chat feature with Laravel Reverb **and** Reverb is already installed and configured, or an explicit plan has been authorized to install it.

## Contents

- [When to choose Reverb](#when-to-choose-reverb)
- [Prerequisite checks before using this reference](#prerequisite-checks-before-using-this-reference)
  - [Dependency checks](#dependency-checks)
  - [Configuration checks](#configuration-checks)
  - [Verification commands (read-only)](#verification-commands-read-only)
- [If Reverb is not installed](#if-reverb-is-not-installed)
- [Implementation rules](#implementation-rules)
- [Backend flow with Reverb](#backend-flow-with-reverb)
- [Vue UI rules](#vue-ui-rules)
- [Documentation checklist](#documentation-checklist)

## When to choose Reverb

- You want Laravel-native broadcasting.
- You want channel auth integrated with Laravel.
- You want to use Laravel Echo on the frontend.
- You want fewer moving parts than a separate Node service.

## Prerequisite checks before using this reference

### Dependency checks

Verify Reverb is a **direct** dependency by checking `composer.json`. A package is directly required only when it appears under `require` or `require-dev`.

```powershell
# Read composer.json and check for direct declaration
$composer = Get-Content -Raw composer.json | ConvertFrom-Json
$directRequirement = `
    $composer.require.PSObject.Properties.Name -contains 'laravel/reverb' -or `
    $composer.'require-dev'.PSObject.Properties.Name -contains 'laravel/reverb'

Write-Host "Direct dependency: $directRequirement"
```

After confirming direct declaration, verify installation and version:

```powershell
# Check installation status and version
composer show laravel/reverb
```

If browser subscriptions are required, also verify frontend Echo client dependencies are declared in `package.json`:

```powershell
$pkg = Get-Content -Raw package.json | ConvertFrom-Json
$dependencyNames = @()

if ($pkg.dependencies) {
    $dependencyNames += $pkg.dependencies.PSObject.Properties.Name
}

if ($pkg.devDependencies) {
    $dependencyNames += $pkg.devDependencies.PSObject.Properties.Name
}

$hasEcho = $dependencyNames -contains 'laravel-echo'
$hasPusher = $dependencyNames -contains 'pusher-js'

Write-Host "Laravel Echo directly declared: $hasEcho"
Write-Host "Pusher JS directly declared: $hasPusher"
```

### Configuration checks

- `config/broadcasting.php` exists and contains a Reverb connection.
- `routes/channels.php` exists and defines authorization for chat channels.
- Broadcasting service providers are registered.
- Required environment variables are documented in `.env.example`:
  - `REVERB_APP_ID`
  - `REVERB_APP_KEY`
  - `REVERB_APP_SECRET`
  - `REVERB_HOST`
  - `REVERB_PORT`
  - `REVERB_SCHEME`
- Frontend bootstrap code subscribes through Echo.

### Verification commands (read-only)

```powershell
# Confirm direct declaration in composer.json
$composer = Get-Content -Raw composer.json | ConvertFrom-Json
$directRequirement = `
    $composer.require.PSObject.Properties.Name -contains 'laravel/reverb' -or `
    $composer.'require-dev'.PSObject.Properties.Name -contains 'laravel/reverb'
Write-Host "Direct dependency: $directRequirement"

# Check installation status and version
composer show laravel/reverb

# Inspect configuration
Test-Path config/broadcasting.php
Test-Path routes/channels.php
Select-String -Path .env.example -Pattern 'REVERB_'

# Inspect frontend dependencies
Test-Path package.json
$pkg = Get-Content -Raw package.json | ConvertFrom-Json
$dependencyNames = @()

if ($pkg.dependencies) {
    $dependencyNames += $pkg.dependencies.PSObject.Properties.Name
}

if ($pkg.devDependencies) {
    $dependencyNames += $pkg.devDependencies.PSObject.Properties.Name
}

$dependencyNames -contains 'laravel-echo'
$dependencyNames -contains 'pusher-js'
```

## If Reverb is not installed

- Do not generate code that assumes Reverb exists.
- Report Reverb installation/configuration as a prerequisite.
- Request explicit authorization before adding packages or modifying infrastructure.
- Produce an implementation and dependency plan first.
- Do not run `composer require laravel/reverb`, `npm install`, or similar commands automatically.

## Implementation rules

- Use Laravel events implementing broadcast contracts.
- Define private/presence channel authorization in Laravel.
- Do not expose rooms or threads to non-members/non-participants.
- Use Echo on the frontend only after confirming it is installed.
- Broadcast after message persistence succeeds.
- Decide queued vs immediate broadcasting intentionally.
- Document required `.env` variables.
- Include local and production run commands / checklist.

## Backend flow with Reverb

1. migrations
2. models and relationships
3. services
4. Form Requests
5. controllers
6. API Resources
7. routes
8. broadcast events and channel definitions
9. UI
10. tests
11. documentation

## Vue UI rules

- Vue is optional and only added when needed.
- Use Vite.
- Keep components small and focused.
- Use Echo for Reverb subscriptions only after confirming Echo is installed.
- Keep message rendering escaped/safe.
- Support loading, empty, failed-send, reconnecting, and pagination states.
- Do not hide backend validation errors.
- Keep admin UI consistent with the existing dark RTL dashboard.

## Documentation checklist

- selected transport: Reverb
- prerequisite inspection results
- env variables
- run commands
- channel event names
- payload examples
- auth flow
- deployment notes
- test coverage
