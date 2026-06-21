# Admin CRUD Completion Checklist

Use this reference as the final gate before marking an admin CRUD module complete.

## Required for every CRUD module

- [ ] Inputs fully collected
- [ ] Feature analysis complete
- [ ] Database schema designed from columns
- [ ] Model created with relationships and fillable
- [ ] Form Requests created for store/update
- [ ] Service created with business logic
- [ ] Controller is thin and correct
- [ ] Views created and clean
- [ ] Routes registered with proper `admin.*` names
- [ ] Translations complete for routes, inputs, and main labels
- [ ] Factory/seeder created when enabled
- [ ] Tests added
- [ ] Final validation passed

## Required when optional features are enabled

- [ ] Export implemented end-to-end
- [ ] Statistics cards/charts implemented
- [ ] File uploads validated, stored, and cleaned up
- [ ] Map shown in read-only mode on show page
- [ ] Soft deletes + restore implemented
- [ ] Multilingual fields stored and seeded correctly

## Quality gates

- [ ] No business logic in controllers
- [ ] No business logic in Blade
- [ ] No DB queries in Blade
- [ ] Eager loading applied for displayed relations
- [ ] No debug code left (`dd`, `dump`, `ray`)
- [ ] Route names match permission strings in `lang/*/admin/routes.php`
- [ ] Guard passes produce no unresolved critical/high findings
