# File Upload Skill

## Purpose

Handle file uploads securely and consistently using project conventions: Spatie Media Library for model-attached files, Laravel Storage for standalone files.

---

## When to Use

- Adding image/file uploads to a model (avatar, gallery, documents)
- Building file input fields in admin create/edit forms
- Handling API file uploads
- Managing file replacement on update
- Cleaning up files on record deletion

---

## Process

### Step 1: Determine Upload Strategy

| Strategy | When to Use | Implementation |
|----------|-------------|----------------|
| Spatie Media Library | Files attached to a model (images, documents) | `HasMedia` trait on model |
| Laravel Storage | Standalone files not tied to a model | `Storage::put()` |
| Upload Trait | Custom upload logic shared across services | `app/Traits/Upload` |

**Default:** Use Spatie Media Library unless there's a specific reason not to.

### Step 2: Model Setup (Spatie)

Add `HasMedia` interface and trait to the model:

```php
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('gallery');
        $this->addMediaCollection('documents');
    }
}
```

### Step 3: Validation (Form Request)

Add file validation rules in Form Request (`app/Http/Requests/`):

```php
public function rules(): array
{
    return [
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'gallery' => 'nullable|array',
        'gallery.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        'document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
    ];
}
```

### Step 4: Service Layer Upload

Handle upload in Service class (`app/Services/Admin/` or `app/Services/Api/`):

```php
// Single file
if (!empty($data['image'])) {
    $model->clearMediaCollection('image');
    $model->addMedia($data['image'])->toMediaCollection('image');
}

// Multiple files
if (!empty($data['gallery'])) {
    foreach ($data['gallery'] as $file) {
        $model->addMedia($file)->toMediaCollection('gallery');
    }
}
```

### Step 5: Blade Form Integration

Use existing form components in `resources/views/components/form/`:

```blade
{{-- Single image --}}
<x-form.image name="image" :value="$item->getFirstMediaUrl('image')" />

{{-- Multiple images --}}
<x-form.multi-image name="gallery" :value="$item->getMedia('gallery')" />
```

### Step 6: Cleanup on Delete

Handle file cleanup in Service:

```php
public function delete(Model $model): void
{
    $model->clearMediaCollection('image');
    $model->clearMediaCollection('gallery');
    $model->delete();
}
```

---

## Project-Specific Paths

| Component | Location |
|-----------|----------|
| Media Library Config | `config/medialibrary.php` |
| Upload Trait | `app/Traits/Upload` |
| Form Components | `resources/views/components/form/` |
| Image Component | `<x-form.image>` |
| Multi-Image Component | `<x-form.multi-image>` |
| Storage Config | `config/filesystems.php` |

---

## Security Rules

| Rule | Implementation |
|------|----------------|
| Validate file type | Use `mimes:` rule in Form Request |
| Validate MIME type | Don't trust extension only |
| Validate file size | Use `max:` rule (in KB) |
| Prevent executables | Never allow `php,exe,sh,bat` |
| Use unique names | Spatie handles this automatically |
| Control access | Use signed URLs or middleware for private files |

---

## File Handling on Update

```php
// Replace single file (clear old, add new)
if ($request->hasFile('image')) {
    $model->clearMediaCollection('image');
    $model->addMedia($request->file('image'))->toMediaCollection('image');
}

// Keep existing if no new file uploaded
// (do nothing — Spatie preserves existing media)
```

---

## Completion Standard

A file upload is NOT complete until:

- [ ] Model implements `HasMedia` with collections defined
- [ ] Form Request validates file type, MIME, and size
- [ ] Service handles upload (not controller)
- [ ] Blade uses existing form components (`<x-form.image>`, `<x-form.multi-image>`)
- [ ] Files cleaned up on delete
- [ ] Files replaced correctly on update
- [ ] No executable file types allowed

---

## Output Format

- Model media collections setup
- Validation rules
- Service upload/cleanup logic
- Blade component usage
- Storage strategy used