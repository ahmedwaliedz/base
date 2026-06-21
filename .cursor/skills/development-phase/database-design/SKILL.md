---
name: database-design
description: Design database schema, relationships, indexes, and multilingual/media storage. Trigger for new entities, columns, or data changes.
---

# Database Design Skill

## Purpose

Design database schemas following Laravel conventions and project requirements.

---

## When to Use

- Creating new entities/tables
- Adding columns to existing tables
- Defining relationships between models
- Setting up indexes and constraints
- Implementing multilingual data
- Adding file/media handling

---

## Process

### Step 1: Identify Entities

- Define the main entity (singular name, e.g., "Category")
- Determine what data the entity holds
- Identify if entity needs translations (multilingual)

### Step 2: Define Fields

- List all required columns
- Choose appropriate data types
- Determine nullable vs required
- Set defaults where appropriate

### Step 3: Define Relationships

- Identify related entities (hasMany, belongsTo, etc.)
- Add foreign keys with proper constraints
- Consider pivot tables for many-to-many

### Step 4: Consider Features

- **Soft deletes** â†’ add `deleted_at` column
- **Timestamps** â†’ use `timestamps()` or custom
- **Multilingual** â†’ use Astrotomic Translatable package
- **Media** â†’ use Spatie Media Library
- **Sorting** â†’ add `order` column (integer)

### Step 5: Design Migration

- Create migration in `database/migrations/`
- Use proper naming: `create_{table}_table`
- Define columns, indexes, foreign keys
- Add soft deletes if needed

---

## Project Packages

This project uses:

| Package | Purpose | When to Use |
|---------|---------|-------------|
| Astrotomic Translatable | Multilingual | When entity needs Arabic/English content |
| Spatie Media Library | File/Image handling | When entity has uploads |
| Maatwebsite Excel | Export | When export functionality needed |

---

## Standard Field Types

| Data | Type | Example |
|------|------|---------|
| Names, titles | string(255) | `name`, `title` |
| Descriptions | text | `description`, `notes` |
| Status | boolean | `is_active`, `is_featured` |
| Order/Sort | integer | `order`, `sort_order` |
| Dates | date/datetime | `start_date`, `created_at` |
| Foreign key | foreignId | `user_id`, `category_id` |
| Files | morphs | Spatie Media Library |

---

## Multilingual Strategy

When entity needs translations (e.g., name in Arabic & English):

```
# Main table: categories
id, is_active, order, created_at, updated_at, deleted_at

# Translation table: category_translations
id, category_id, locale, name, description
```

Use Astrotomic Translatable trait in Model:
```php
use Astrotomic\Translatable\Translatable;

class Category extends Model
{
    use Translatable;
    public $translatedAttributes = ['name', 'description'];
}
```

---

## Media Strategy

When entity needs file uploads (images, documents):

- Use Spatie Media Library
- Add `HasMedia` trait to Model
- Define collections in Model's `registerMediaCollections()`

---

## Naming Conventions

| Element | Convention | Example |
|---------|------------|---------|
| Table | snake_case, plural | `categories` |
| Primary key | `id` | `id` |
| Foreign key | `{table}_id` | `user_id` |
| Index | `idx_{table}_{column}` | `idx_categories_is_active` |
| Unique | `uq_{table}_{column}` | `uq_categories_slug` |

---

## Data Integrity Rules

- **Avoid calculated values** - store source, calculate when needed
- **Separate transactional and derived** - don't store counts, calculate
- **Use foreign keys** - maintain relationship integrity
- **Handle soft deletes** - use for reversible deletions
- **Avoid orphan records** - use `onDelete('cascade')`

---

## Migration Structure

```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->integer('order')->default(0);
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    $table->softDeletes();

    $table->index('is_active');
    $table->index('order');
});
```

---

## Completion Standard

A database design is NOT complete until:

- [ ] All required fields defined with correct types
- [ ] Foreign keys properly defined with constraints
- [ ] Indexes added for frequently queried columns
- [ ] Soft deletes considered if applicable
- [ ] Multilingual strategy defined if needed
- [ ] Media strategy defined if uploads needed
- [ ] Migration is runnable without errors

---

## Output Format

- Table name
- Columns with types and constraints
- Relationships
- Indexes
- Migration file ready to run
- Any special considerations (translatable, media)