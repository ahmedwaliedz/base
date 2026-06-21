# Realtime Chat — Node.js Websocket Gateway Transport

Load this reference when implementing a chat feature with a Node.js websocket gateway **and** an existing Node websocket stack is present, or an explicit plan has been authorized to create one.

## Contents

- [When to choose Node.js](#when-to-choose-nodejs)
- [Prerequisite checks before using this reference](#prerequisite-checks-before-using-this-reference)
  - [Existing Node websocket stack checks](#existing-node-websocket-stack-checks)
  - [Required information](#required-information)
  - [Verification commands](#verification-commands-read-only)
- [If no Node websocket stack exists](#if-no-node-websocket-stack-exists)
- [Architecture rule](#architecture-rule)
- [Implementation rules](#implementation-rules)
- [Backend flow with Node](#backend-flow-with-node)
- [Vue UI rules](#vue-ui-rules)
- [Documentation checklist](#documentation-checklist)

## When to choose Node.js

- The product needs a separate websocket process.
- You need custom socket rooms or transport-level fanout.
- You anticipate future non-Laravel clients.

## Prerequisite checks before using this reference

### Existing Node websocket stack checks

Verify an existing Node websocket service or package exists before generating code. Use a layered inspection approach:

**Layer 1: Direct declaration in `package.json`**

Parse the manifest file to check for directly declared websocket packages:

```powershell
$pkg = Get-Content -Raw package.json | ConvertFrom-Json
$dependencyNames = @()

if ($pkg.dependencies) {
    $dependencyNames += $pkg.dependencies.PSObject.Properties.Name
}

if ($pkg.devDependencies) {
    $dependencyNames += $pkg.devDependencies.PSObject.Properties.Name
}

# Check for common candidates (not an exhaustive list)
$knownPackages = @('ws', 'socket.io', 'uWebSockets.js', 'websocket')
$directDeclared = $dependencyNames | Where-Object { $_ -in $knownPackages }

if ($directDeclared) {
    Write-Host "Directly declared websocket package(s): $($directDeclared -join ', ')"
} else {
    Write-Host "No directly declared websocket package found"
}
```

Manifest presence establishes **direct declaration**. A package is directly declared only when it appears under `dependencies` or `devDependencies` in `package.json`.

**Layer 2: Resolved or installed state**

If a package is not directly declared, check whether it is resolved or installed as a transitive dependency:

```powershell
# Check if the package appears in lockfiles (indicates resolved state, not direct declaration)
Test-Path package-lock.json
Test-Path yarn.lock
Test-Path pnpm-lock.yaml

# Or use package manager commands to list installed packages
npm ls ws 2>$null
```

Lockfiles and package-manager listing commands prove **resolved or installed state only**. They do not prove direct declaration.

**Layer 3: Adoption and implementation evidence**

If a websocket package is directly declared, verify it is actually integrated into the project:

```powershell
$sourceFiles = Get-ChildItem -LiteralPath . -Recurse -File |
    Where-Object {
        $_.Extension -in @('.js', '.mjs', '.cjs', '.ts') -and
        $_.FullName -notmatch '[\\/](node_modules|vendor|storage)([\\/]|$)' -and
        $_.FullName -notmatch '[\\/]public[\\/]build([\\/]|$)'
    }

$adoptionPatterns = @(
    '\bfrom\s+[''"](?:ws|socket\.io|uWebSockets\.js|websocket)[''"]',
    '\bimport\s+[''"](?:ws|socket\.io|uWebSockets\.js|websocket)[''"]',
    '\brequire\(\s*[''"](?:ws|socket\.io|uWebSockets\.js|websocket)[''"]\s*\)',
    '\bnew\s+WebSocketServer\b',
    '\bnew\s+Server\s*\('
)

$matches = $sourceFiles |
    Select-String -Pattern $adoptionPatterns -CaseSensitive:$false

if ($matches) {
    Write-Host "Found $($matches.Count) adoption evidence item(s):"
    $matches | ForEach-Object {
        Write-Host "  $($_.Path): $($_.Line)"
    }
} else {
    Write-Host 'No websocket adoption evidence found'
}
```

Source matches prove **adoption and configuration**. Generic occurrences of the words `socket`, `ws`, or `websocket` are not sufficient evidence.

**Layer 4: Existing transport decision**

If a working websocket transport already exists in the project, preserve it. Do not introduce another transport unless explicitly authorized and justified.

### Required information

Before implementing, know:

- The project location of the Node websocket server.
- Which server package is used (e.g., `ws`, `socket.io`, raw `WebSocket`, or another).
- Which client package is used in the browser or mobile app.
- How clients authenticate before joining rooms.
- The message contract between Laravel and Node.
- How the Node process is managed in development and production.
- Deployment and CORS/origin rules.

### Verification commands (read-only)

Run the canonical Layer 3 source-inspection command above.

## If no Node websocket stack exists

- Treat it as new infrastructure.
- Require explicit authorization.
- Produce an implementation and dependency plan before creating it.
- Do not invent package choices silently.
- Do not assume Socket.IO; raw WebSocket or another transport may be selected deliberately.
- Do not run `npm install` or create a `package.json` automatically.

## Architecture rule

Laravel remains the source of truth for database writes, authorization, validation, and business rules. Node handles websocket delivery and transport concerns only.

## Implementation rules

- Node handles websocket connections and rooms only.
- Clients must authenticate before joining rooms.
- Node must call a Laravel API / internal endpoint or verify signed tokens before room join.
- Node must not write directly to DB unless explicitly approved.
- Define the message contract between Laravel and Node.
- Handle reconnects, duplicate delivery, and idempotency.
- Document required `.env` variables, ports, CORS/origin rules, and process manager setup.

## Backend flow with Node

1. migrations
2. models and relationships
3. services
4. Form Requests
5. controllers
6. API Resources
7. routes
8. Node gateway contract and auth endpoint
9. UI
10. tests
11. documentation

## Vue UI rules

- Vue is optional and only added when needed.
- Use Vite.
- Keep components small and focused.
- Use a websocket client matching the selected Node transport (do not assume Socket.IO).
- Keep message rendering escaped/safe.
- Support loading, empty, failed-send, reconnecting, and pagination states.
- Do not hide backend validation errors.
- Keep admin UI consistent with the existing dark RTL dashboard.

## Documentation checklist

- selected transport: Node.js websocket gateway
- prerequisite inspection results
- chosen server/client packages
- env variables
- ports and CORS/origin rules
- process manager setup
- socket event names
- payload examples
- Laravel → Node auth flow
- deployment notes
- test coverage