# Plan — SweetAlert Toaster Styling + Z-Index Fix

**Status:** Draft (no code shipped yet)
**Audience:** Cursor / coding agent
**Scope:** SweetAlert2 popups across the admin — top-start success toasts,
error alerts, and any inline alert built via `Swal.fire()`.
**Branches affected:** master
**Companion to:** [`theme_switcher_chrome_integration.md`](./theme_switcher_chrome_integration.md),
[`modals_dark_mode_and_sizing.md`](./modals_dark_mode_and_sizing.md)

---

## TL;DR — three issues, one CSS file

1. **Toast appears under the navbar.** The "تم الإنشاء بنجاح" success toast
   slides down from the top-start corner but is **clipped/covered by the
   navbar**. SweetAlert2 default `z-index = 1060`; our navbar was bumped to
   `z-index: 1200` by the brand-switcher patch, so the toast loses.

2. **Toast looks generic** — Sneat's vendor sweetalert.css renders a plain
   white box. It doesn't follow the brand color, doesn't sit on a glass
   surface, doesn't fit dark mode well.

3. **Toast is oversized** — current popup is full-width-ish with
   ~1.25rem padding. The user wants a tighter, "notification-pill" feel.

All three fixes live in **one new CSS file** —
`public/style/admin/css/sweetalert-themed.css` — loaded after the vendor
sweetalert2 sheet. No JS changes. No Blade changes.

---

## Part 1 · Current state audit

### Callers of `Swal.fire()` in the project

| File                                                              | Use                                  |
| ----------------------------------------------------------------- | ------------------------------------- |
| `public/style/admin/custom-js/submit-form.js`                     | Success toast on AJAX form submit   |
| `public/style/admin/custom-js/delete.js`                          | Confirm dialog + success/error toast |
| `public/style/admin/custom-js/restore.js`                         | Confirm + success/error              |
| `public/style/admin/custom-js/admin-table.js`                     | Switch block / status toasts         |
| `public/style/admin/custom-js/error-handlers/show-validation-on-inputs.js` | Validation error fallback |
| `public/style/admin/custom-js/error-handlers/show-block.js`       | 423 status error                     |
| `public/style/admin/custom-js/error-handlers/show-un-authorize.js`| 400 status error                     |
| `public/style/admin/custom-js/error-handlers/show-unknown-error.js`| Generic fallback                    |

### Toast pattern in code (submit-form.js, line ~25)

```js
Swal.fire({
    icon: 'success',
    position: 'top-start',
    text: response.message,
    showConfirmButton: false,
    timer: 2000
});
```

`position: 'top-start'` triggers `.swal2-top-start` container. In RTL that
auto-mirrors to top-right.

### Z-index audit (confirmed against vendor CSS earlier in this session)

| Element                       | z-index | Source              |
| ----------------------------- | ------- | ------------------- |
| `.swal2-container`            | 1060    | vendor sweetalert2  |
| `.layout-navbar` (current)    | 1200    | brand-colors.css    |
| `.layout-menu` (sidebar)      | 1100    | vendor core         |
| `.modal-add-new-address` (modal popover) | 1055 | bootstrap |
| `.dropdown-menu.brand-color-picker` | 1210 | brand-colors.css |

**The toast must beat the navbar.** Set it at `1300` (above everything that
isn't a modal-backdrop). Modals themselves stay below the toast — a toast
should still announce on top of an open modal.

---

## Part 2 · Fixes

### Fix 1 · Z-index lift

```css
.swal2-container {
    z-index: 1300 !important;
}

/* Backdrop (used by confirm dialog, not by toasts) stays just below */
.swal2-container.swal2-backdrop-show {
    z-index: 1300 !important;
}
```

### Fix 2 · Compact "notification-pill" toast geometry

The user wants smaller. Override the toast's default sizes:

```css
/* Targets only the top-start / top-end position (toasts) */
.swal2-container.swal2-top-start > .swal2-popup,
.swal2-container.swal2-top-end   > .swal2-popup,
.swal2-container.swal2-top       > .swal2-popup {
    width: auto !important;
    max-width: 380px;
    min-width: 260px;
    padding: 0.85rem 1.1rem !important;
    border-radius: 14px !important;
    box-shadow:
        0 12px 36px rgba(var(--color-brand-primary-rgb), 0.18),
        0 4px 12px rgba(0, 0, 0, 0.20);
    font-size: 0.88rem;
}

/* Compact title */
.swal2-container.swal2-top-start .swal2-title,
.swal2-container.swal2-top-end   .swal2-title,
.swal2-container.swal2-top       .swal2-title {
    font-size: 0.95rem !important;
    font-weight: 700;
    padding: 0 !important;
    margin: 0 !important;
}
.swal2-container.swal2-top-start .swal2-html-container,
.swal2-container.swal2-top-end   .swal2-html-container,
.swal2-container.swal2-top       .swal2-html-container {
    font-size: 0.85rem !important;
    padding: 0 !important;
    margin: 0.1rem 0 0 !important;
    color: var(--text-body);
}

/* Compact icon (success check, error X, etc.) */
.swal2-container.swal2-top-start .swal2-icon,
.swal2-container.swal2-top-end   .swal2-icon,
.swal2-container.swal2-top       .swal2-icon {
    width: 28px !important;
    height: 28px !important;
    margin: 0 0.75rem 0 0 !important;
    border-width: 2px !important;
}
[dir='rtl'] .swal2-container.swal2-top-start .swal2-icon,
[dir='rtl'] .swal2-container.swal2-top-end   .swal2-icon {
    margin: 0 0 0 0.75rem !important;
}
.swal2-container.swal2-top-start .swal2-icon .swal2-icon-content,
.swal2-container.swal2-top-end   .swal2-icon .swal2-icon-content,
.swal2-container.swal2-top       .swal2-icon .swal2-icon-content {
    font-size: 1.05rem !important;
}
.swal2-container.swal2-top-start .swal2-success-ring,
.swal2-container.swal2-top-end   .swal2-success-ring {
    width: 28px !important;
    height: 28px !important;
}

/* Layout: icon + content inline */
.swal2-container.swal2-top-start .swal2-popup,
.swal2-container.swal2-top-end   .swal2-popup,
.swal2-container.swal2-top       .swal2-popup {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 0.5rem;
}
.swal2-container.swal2-top-start .swal2-popup .swal2-html-container,
.swal2-container.swal2-top-end   .swal2-popup .swal2-html-container {
    flex: 1;
    text-align: start;
}
```

### Fix 3 · Brand & theme integration

```css
/* Default toast → glass with brand-tint */
.swal2-container.swal2-top-start > .swal2-popup,
.swal2-container.swal2-top-end   > .swal2-popup,
.swal2-container.swal2-top       > .swal2-popup {
    background:
        linear-gradient(135deg,
            rgba(var(--color-brand-primary-rgb), 0.08) 0%,
            rgba(255, 255, 255, 0.98) 100%) !important;
    border: 1px solid rgba(var(--color-brand-primary-rgb), 0.22);
    backdrop-filter: blur(14px) saturate(160%);
    -webkit-backdrop-filter: blur(14px) saturate(160%);
}

/* Top accent stripe — brand gradient */
.swal2-container.swal2-top-start > .swal2-popup::before,
.swal2-container.swal2-top-end   > .swal2-popup::before,
.swal2-container.swal2-top       > .swal2-popup::before {
    content: '';
    position: absolute;
    inset-block-start: 0;
    inset-inline: 0;
    height: 3px;
    background: linear-gradient(90deg,
        var(--color-brand-primary)   0%,
        var(--color-brand-secondary) 100%);
    border-radius: 14px 14px 0 0;
}

/* Icon colors by status — use semantic vars, not vendor's hard-coded hex */
.swal2-container.swal2-top-start .swal2-icon.swal2-success,
.swal2-container.swal2-top-end   .swal2-icon.swal2-success {
    border-color: var(--color-success, #28C76F) !important;
    color: var(--color-success, #28C76F) !important;
}
.swal2-container.swal2-top-start .swal2-icon.swal2-success [class^='swal2-success-line'] {
    background-color: var(--color-success, #28C76F) !important;
}
.swal2-container.swal2-top-start .swal2-icon.swal2-error,
.swal2-container.swal2-top-end   .swal2-icon.swal2-error {
    border-color: var(--color-danger, #EA5455) !important;
    color: var(--color-danger, #EA5455) !important;
}
.swal2-container.swal2-top-start .swal2-icon.swal2-error [class^='swal2-x-mark-line'] {
    background-color: var(--color-danger, #EA5455) !important;
}
.swal2-container.swal2-top-start .swal2-icon.swal2-warning,
.swal2-container.swal2-top-end   .swal2-icon.swal2-warning {
    border-color: var(--color-warning, #FF9F43) !important;
    color: var(--color-warning, #FF9F43) !important;
}
.swal2-container.swal2-top-start .swal2-icon.swal2-info,
.swal2-container.swal2-top-end   .swal2-icon.swal2-info {
    border-color: var(--color-brand-secondary) !important;
    color: var(--color-brand-secondary) !important;
}

/* Smooth slide-in (default has a coarse animation) */
@keyframes themed-toast-in {
    from { opacity: 0; transform: translateY(-14px) scale(0.96); }
    to   { opacity: 1; transform: translateY(0)    scale(1);    }
}
.swal2-container.swal2-top-start.swal2-shown > .swal2-popup,
.swal2-container.swal2-top-end.swal2-shown   > .swal2-popup,
.swal2-container.swal2-top.swal2-shown       > .swal2-popup {
    animation: themed-toast-in 0.28s var(--ease-bounce);
}
```

### Fix 4 · Dark-mode tuning

```css
[data-theme*='dark'] .swal2-container.swal2-top-start > .swal2-popup,
[data-theme*='dark'] .swal2-container.swal2-top-end   > .swal2-popup,
[data-theme*='dark'] .swal2-container.swal2-top       > .swal2-popup,
.dark-style .swal2-container.swal2-top-start > .swal2-popup,
.dark-style .swal2-container.swal2-top-end   > .swal2-popup,
.dark-style .swal2-container.swal2-top       > .swal2-popup {
    background:
        linear-gradient(135deg,
            rgba(var(--color-brand-primary-rgb), 0.18) 0%,
            rgba(30, 26, 64, 0.94) 100%) !important;
    border-color: rgba(var(--color-brand-primary-rgb), 0.40);
    color: #f3f4ff;
    box-shadow:
        0 12px 36px rgba(0, 0, 0, 0.45),
        0 0 24px rgba(var(--color-brand-primary-rgb), 0.18);
}
[data-theme*='dark'] .swal2-container .swal2-title,
[data-theme*='dark'] .swal2-container .swal2-html-container,
.dark-style .swal2-container .swal2-title,
.dark-style .swal2-container .swal2-html-container {
    color: #f3f4ff !important;
}
```

### Fix 5 · Position margin (avoid hugging the screen edge)

```css
.swal2-container.swal2-top-start  { padding-block-start: 1.25rem !important; padding-inline-start: 1.25rem !important; }
.swal2-container.swal2-top-end    { padding-block-start: 1.25rem !important; padding-inline-end: 1.25rem !important; }
```

In RTL the start corner is on the right — `padding-inline-start` handles
both directions automatically.

---

## Part 3 · File to add / wire

### New file: `public/style/admin/css/sweetalert-themed.css`

All five fixes above (~180 lines total).

### Wire into `header-links.blade.php`

Add the link **after** the vendor sweetalert2.css. There's no global
include site for it today — every page that uses sweetalert links the CSS
itself in its `@push('css')` stack. Two options:

**Option A (recommended):** Make the include global by adding both vendor
and themed CSS to `header-links.blade.php`. One link removes duplication
across login, crud/create, crud/edit, crud/show, etc.

```html
{{-- in header-links.blade.php, after navbar.css --}}
<link rel="stylesheet" href="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css') }}">
<link rel="stylesheet" href="{{ asset('style/admin/css/sweetalert-themed.css') }}">
```

Then **delete** the per-page `<link rel="stylesheet" href="…sweetalert2.css">`
lines from:
- `resources/views/admin/auth/login.blade.php`
- `resources/views/admin/layouts/crud/create.blade.php`
- `resources/views/admin/layouts/crud/edit.blade.php`
- `resources/views/admin/layouts/crud/show.blade.php`

**Option B (minimal change):** Keep the per-page includes, just add the
themed file after each vendor sweetalert2.css link. Higher duplication,
zero risk to existing pages.

Pick Option A unless QA finds an admin page that doesn't currently link
sweetalert (unlikely — every form page does).

---

## Part 4 · Acceptance criteria

- After a successful AJAX form submit, the success toast slides in from
  top-start (or top-end in RTL) and is **fully visible above the navbar**.
- Toast width is ≤ 380px, padded ~0.85rem, with the title at 0.95rem and
  the icon at 28px.
- A 3px brand gradient stripe sits at the top of the toast.
- Success → green icon. Error → red. Warning → amber. Info → cyan.
  Switching brand color does NOT change semantic colors.
- In dark mode the toast is a deep brand-tinted glass; text is white;
  shadow is darker.
- Smooth `translateY(-14px) → 0 + scale(.96 → 1)` slide-in.
- Multiple toasts firing in quick succession stack (SweetAlert handles
  this; just verify no z-index clash with each other).
- Confirm dialogs (centered, with backdrop) are **not** affected by this
  plan — they're handled in a separate plan (`confirm_dialog_advanced.md`).

---

## Part 5 · Out of scope

- **Confirm-dialog styling.** Separate plan
  ([`confirm_dialog_advanced.md`](./confirm_dialog_advanced.md)).
- **Per-call-site customization.** Don't touch the JS — every `Swal.fire`
  call keeps the same options. The CSS handles all visual differences.
- **Replacing SweetAlert with a different lib** (Toastify, etc.). Stays
  on SweetAlert.
- **Sound effects on success/error.** Out of scope.
- **Toast queue management** (e.g. dedupe identical toasts within 500ms).
  Possible follow-up; needs JS work.

---

## Notes for the implementing agent

1. The selectors `.swal2-container.swal2-top-start` etc. are **only**
   present on toast-style popups (when `position: 'top-start'` is set).
   Centered confirm dialogs use `.swal2-container.swal2-center` — the
   CSS in this plan does NOT match them. That's intentional.

2. SweetAlert2 vendor CSS is huge (~1500 lines). Don't fork it — only
   override.

3. **`!important` is fine here.** The vendor selectors are very specific
   and we need to overrule them with a tighter file. Be liberal.

4. Some toasts use `icon: 'success'` without `title` — i.e. only
   `.swal2-html-container` is filled. The icon-inline layout in Fix 2
   handles this (flexbox auto-distributes).

5. The `--color-success`, `--color-danger`, `--color-warning` CSS vars
   should already exist in `tokens.css`. Verify before shipping; if any
   are missing, fall back to the hex defaults in the snippets (#28C76F,
   #EA5455, #FF9F43).
