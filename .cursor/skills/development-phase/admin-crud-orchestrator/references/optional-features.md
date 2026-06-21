# Admin CRUD Optional Features

Load this reference when the CRUD inputs enable optional features such as export, statistics/charts, file uploads, maps, soft deletes, or multilingual storage.

## Export

When export is enabled:

- Create an export class.
- Add an export controller action.
- Add an export route.
- Add an export UI trigger.
- Export only selected columns.
- Export headers must be translatable.
- Export types must match selected types.
- Do not include unlisted columns.

## Statistics and charts

When statistics are enabled:

- Prepare statistics cards data in the service or controller.
- Prepare chart datasets when enabled.
- Render cards dynamically in the index view.
- Render charts in a collapsible section.
- Cards must be dynamic, not hardcoded.
- Cards and charts must have animation.
- Chart data must come from controller/service, not Blade.
- The collapsible charts section must use the project translation key for title.

## File uploads

When file uploads are enabled:

- Use Spatie Media Library (`HasMedia` interface + `InteractsWithMedia` trait).
- Define media collections in the model (e.g., `image`, `gallery`, `documents`).
- Validate file type, MIME, and size in the Form Request.
- Handle upload/replacement in the service.
- Clean up media on delete.
- Use existing form components: `<x-form.image>`, `<x-form.multi-image>`.

## Maps / location

When map/location is enabled:

- Store coordinates in the migration if needed.
- Show the map in read-only mode on the show page.
- Follow existing project map components and libraries.

## Soft deletes

When soft deletes are enabled:

- Add `softDeletes()` to the migration.
- Add restore/destroy actions and routes.
- Show a restore button for deleted records.

## Multilingual storage

When multilingual is enabled:

- Use Astrotomic Laravel Translatable.
- Follow the project translation seeding pattern.
- Ensure both Arabic and English keys are present.
