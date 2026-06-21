# Technology Baseline

This file is the single authoritative compatibility reference for the `.cursor` system. Skills, rules, templates, and workflows should link here instead of repeating version warnings.

## Current enforced baseline

The project must generate code compatible with these versions. The active development environment may run newer interpreters, but production code must remain compatible with the versions declared here until this baseline is intentionally upgraded.

| Layer | Version | Source |
|---|---|---|
| PHP | `^8.2` (production compatibility) | `composer.json` `require.php` |
| Laravel Framework | `^11.0` (installed `11.46.1`) | `composer.json` / `composer.lock` |
| PHPUnit | `^11.5.3` (installed `11.5.42`) | `composer.json` / `composer.lock` |
| Laravel Pint | `^1.13` (installed `1.25.1`) | `composer.json` / `composer.lock` |
| Laravel Sanctum | `^4.2` (installed `4.2.4`) | `composer.json` / `composer.lock` |
| Mockery | `^1.6` (installed `1.6.12`) | `composer.json` / `composer.lock` |
| nunomaduro/collision | `^8.6` (installed `8.8.2`) | `composer.json` / `composer.lock` |
| Spatie Media Library | `^11.13` (installed `11.15.0`) | `composer.json` / `composer.lock` |
| Astrotomic Laravel Translatable | `^11.15` (installed `11.16.1`) | `composer.json` / `composer.lock` |

## Current environment note

The local PHP CLI may report a version newer than `8.2` (e.g. `8.4.x`). Do not generate PHP 8.3+ or 8.4+ syntax into project code unless this baseline is explicitly updated. Always prefer features available in PHP 8.2 and Laravel 11.

## Tools that are NOT installed directly

Do not treat these as available project tooling unless they are added to `composer.json` and installed:

- Pest
- Larastan / PHPStan
- Rector
- Infection
- Laravel Dusk

Composer packages may bring in development dependencies of their own; that does not mean the tool is executable for this project.

## Future upgrade targets

The `.cursor` system is designed to support future upgrades. When the project moves to these versions, update this file and version-specific references, then regenerate or re-audit affected code:

- Laravel 13
- PHP 8.4 or PHP 8.5

Until that upgrade is performed, all future-version guidance must be clearly marked as **future/optional** and must not leak into current production code.

## Rules derived from this baseline

1. Generate PHP 8.2-compatible syntax for all project code.
2. Generate Laravel 11-compatible code and configuration.
3. Generate PHPUnit 11 tests. Do not use Pest syntax.
4. Do not reference PHP 8.3+, 8.4+, or Laravel 12/13 features as currently available.
5. Future-version guidance may be documented, but it must be visibly separated from current guidance.
