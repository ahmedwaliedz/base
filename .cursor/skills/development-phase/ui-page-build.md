# UI Page Build Skill

## Purpose

Build admin UI pages using Blade templates following project conventions. Pages must be presentation-only with no business logic.

---

## When to Use

- Building index pages with tables, filters, pagination
- Building create/edit forms
- Building show/detail pages
- Adding page actions (delete, export, restore)
- Adding statistics cards or charts
- Extending existing pages

---

## Process

### Step 1: Define Page Requirements

- Identify page goal (list, create, edit, show)
- List required sections (table, form, details, actions)
- Determine data needed from controller
- Identify states to handle (loading, empty, error, success)

### Step 2: Choose Components

- Use existing Blade components from `resources/views/components/`
- Do not create new components unless existing ones don't fit

### Step 3: Build Page Structure

- Create or extend from existing layout
- Add required components in logical order
- Add file inputs at top of create/edit forms
- Add actions in proper location (table actions, form buttons)

### Step 4: Add States

- Loading state for async operations
- Empty state when no data
- Error state for failures
- Success feedback after actions

### Step 5: Ensure Consistency

- Follow existing page patterns in the project
- Use consistent button styles and placements
- Use consistent table structures
- Follow existing form layouts
- Reference `.cursor/styles/admin-ui-standards.md` for design patterns

### Show Page Pattern

- Header with icon + title + action buttons
- Stat cards row (4 cards: primary, success, info, warning)
- Profile card (left, 4 cols) + details card (right, 8 cols) - side by side
- Related data section (optional, below cards)
- Use `include('admin.{section}.parts.detail-row')` for field rows

### Action Button Classes

| Button | CSS Class | Example |
|--------|-----------|---------|
| View | `{section}-action-view` | `{section}-action-view` (blue) |
| Edit | `{section}-action-edit` | `{section}-action-edit` (green) |
| Delete | `{section}-action-delete` | `{section}-action-delete` (red) |
| Restore | `{section}-action-restore` | `{section}-action-restore` (teal) |

Each button also includes the base shape class `{section}-action-btn`. Generic selectors in `filter.css`: `[class*="-action-btn"]`, `[class*="-action-view/edit/delete/restore"]`.

---

## Existing Blade Components

### Form Components

Use these for input fields:
```
<x-form.text>      - Text input
<x-form.email>     - Email input
<x-form.password>  - Password input
<x-form.number>    - Number input
<x-form.date>      - Date picker
<x-form.datetime>  - DateTime picker
<x-form.text-area> - Textarea
<x-form.select>    - Dropdown select
<x-form.checkbox>  - Checkbox
<x-form.image>     - Single image upload
<x-form.multi-image> - Multiple image upload
<x-form.map>       - Map/location picker
```

### Table Components

Use these for data display:
```
<x-table.table>        - Main table
<x-table.filter>       - Filter section
<x-table.buttons>      - Action buttons (edit, delete, etc.)
<x-table.bulk-actions> - Bulk action dropdown
<x-table.statistics>   - Statistics cards row
```

### Model Components (for notifications, emails, etc.)
```
<x-model.notification>
<x-model.email>
```

---

## Data Flow Rules

| What | Where |
|------|-------|
| Business logic | Controller or Service |
| Data preparation | Controller |
| Data display | Blade |
| Form validation | Form Request (not in Blade) |
| Data queries | Service (not in Blade) |

**CRITICAL:** Blade must NEVER:
- Make database queries
- Call Services directly
- Contain business logic
- Validate input

---

## Page Types and Patterns

### Index Page
- Table with data
- Filter section
- Pagination
- Bulk actions (if applicable)
- Statistics cards (if applicable)
- Create button in header

### Create/Edit Page
- Form with all input fields
- File inputs at top
- Save/Cancel buttons
- Edit page shows existing data

### Show Page
- Display all fields
- Read-only map (if applicable)
- Related data sections
- Actions (edit, delete)

---

## Standard Fields Order

For create/edit forms, use this order:
1. File uploads (image, multi-image, map) - at top
2. Main fields (name, title, etc.)
3. Status fields (active, order, etc.)
4. Relations (select dropdowns)
5. Notes/description fields - at bottom

---

## Completion Standard

A UI page is NOT complete until:

- [ ] All required data comes from controller
- [ ] No business logic in Blade
- [ ] Uses existing Blade components
- [ ] Handles loading, empty, error states
- [ ] Follows project layout patterns
- [ ] Actions work correctly (buttons, forms)
- [ ] Validation errors display properly

---

## Output Format

- Page file location
- Components used
- Data required from controller
- States handled
- Any deviations from standard patterns
