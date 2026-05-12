# Plan 01 — Simplify Index Modals (Users CRUD)

## Problem

- Modals use `modal-lg` — too large for simple forms (email, notification)
- Modal appears **under the fixed navbar** because `z-index` of the modal backdrop is lower than the navbar's `z-index`
- Modal content has hardcoded Arabic strings instead of using `lang` keys
- `x-model.email` and `x-model.notification` components are not i18n-ready

---

## Files to Change

| File | Change |
|------|--------|
| `resources/views/components/model/email.blade.php` | Resize + fix z-index + i18n |
| `resources/views/components/model/notification.blade.php` | Resize + fix z-index |
| `resources/views/components/Model/email.blade.php` | Same (duplicate — check which one is loaded) |
| `resources/views/components/Model/notification.blade.php` | Same |
| `resources/css/app.css` or a dedicated admin override file | Add `.modal-above-navbar` utility |

---

## Implementation Steps

### Step 1 — Fix z-index so modal renders above navbar

The Sneat admin theme navbar has `z-index: 1080` (Bootstrap default for fixed navbar).
Bootstrap modals default to `z-index: 1055` (below navbar).

Add a global override in the admin CSS or inline on the components:

```css
/* modal renders above fixed navbar */
.modal-above-navbar.modal          { z-index: 1090; }
.modal-above-navbar + .modal-backdrop { z-index: 1089; }
```

Add class `modal-above-navbar` to every modal's root `<div class="modal fade">`.

### Step 2 — Resize modals to `modal-md` (default) or `modal-sm`

Change in both email and notification components:

```html
<!-- Before -->
<div class="modal-dialog modal-lg modal-simple modal-add-new-address">

<!-- After -->
<div class="modal-dialog modal-md modal-simple">
```

Remove `modal-add-new-address` if it pulls in extra padding/width from the theme.

### Step 3 — Replace hardcoded strings with `lang` keys

In `email.blade.php`:

```html
<!-- Before -->
<h3>ارسال ايميل</h3>
<label>الرسالة بالعربية</label>
<label>الرسالة بالانجليزية</label>
<button>ارسال</button>
<button>الغاء</button>

<!-- After -->
<h3>{{ __('admin/main.send_email') }}</h3>
<label>{{ __('admin/main.message_ar') }}</label>
<label>{{ __('admin/main.message_en') }}</label>
<button>{{ __('admin/main.send') }}</button>
<button>{{ __('admin/main.cancel') }}</button>
```

Add matching keys in `lang/ar/admin/main.php` and `lang/en/admin/main.php`.

### Step 4 — Add `modal-dialog-centered` for vertical centering

```html
<div class="modal-dialog modal-md modal-simple modal-dialog-centered">
```

### Step 5 — Remove `tabindex="-1"` issue on focused inputs under navbar

Ensure `autofocus` is not set on modal inputs (causes scroll jump on open).

---

## Acceptance Criteria

- [ ] Modal opens fully visible above the navbar
- [ ] Modal width is compact (≤ 500px on desktop)
- [ ] All strings are loaded from `lang` files (AR + EN)
- [ ] Modal is vertically centered on screen
- [ ] No layout shift or scroll jump on open
