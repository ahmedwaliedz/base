# Plan 02 — Toaster Redesign

## Problem

1. `showTopToast()` uses SweetAlert2 with `position: 'top-end'` — renders under the fixed navbar (`z-index` conflict)
2. Delete success toast uses `position: 'top-start'` — inconsistent position
3. SweetAlert2 toast style doesn't match the Sneat dashboard theme (rounded corners, colors, font)
4. Delete confirm dialog uses `buttonsStyling: false` but no custom CSS classes are applied — buttons render unstyled
5. Fallback plain-DOM toast (in `showTopToast`) uses hardcoded `top: 12px` which also collides with navbar height

---

## Files to Change

| File | Change |
|------|--------|
| `public/style/admin/custom-js/admin-table.js` | Fix `showTopToast` position + style |
| `public/style/admin/custom-js/delete.js` | Fix confirm dialog buttons + success toast position |
| `public/style/admin/custom-js/submit-form.js` | Fix success toast (line 24) |
| Admin CSS override file | Add `.swal-dashboard-toast` theme styles |

---

## Implementation Steps

### Step 1 — Fix navbar offset for all toasts

The Sneat navbar height is `~64px`. Any `position: fixed` toast must clear it.

**Option A — Use SweetAlert2 `customClass` + CSS:**

```js
// showTopToast in admin-table.js
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: icon,
    text: message,
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true,
    customClass: {
        popup: 'dashboard-toast'
    }
});
```

```css
/* push toast below navbar */
.swal2-container.swal2-top-end {
    top: 72px !important;   /* navbar height + 8px gap */
    right: 16px !important;
}

/* match Sneat theme */
.swal2-popup.dashboard-toast {
    font-size: 0.85rem;
    border-radius: 8px;
    padding: 0.6rem 1rem;
    box-shadow: 0 4px 18px rgba(105, 108, 255, 0.18);
    min-width: 260px;
    max-width: 340px;
}
```

**Option B — Replace SweetAlert2 toast with a lightweight custom component** (Bootstrap Toast API — already available via `bootstrap.js`):

```js
function showTopToast(message, icon = 'success') {
    const colorMap = { success: '#696cff', error: '#ff4c51', warning: '#ffab00', info: '#03c3ec' };
    const iconMap  = { success: 'ti-check', error: 'ti-x', warning: 'ti-alert-triangle', info: 'ti-info-circle' };
    const el = document.createElement('div');
    el.className = 'toast align-items-center show dashboard-toast';
    el.style.cssText = `position:fixed;top:72px;inset-inline-end:16px;z-index:9999;border-left:3px solid ${colorMap[icon]||colorMap.success}`;
    el.innerHTML = `
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="ti ${iconMap[icon]||iconMap.success} fs-5" style="color:${colorMap[icon]||colorMap.success}"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>`;
    document.body.appendChild(el);
    setTimeout(() => { el.remove(); }, 3000);
}
```

**Recommendation: Option B** — lighter, fully themed, no SweetAlert2 dependency for toasts.

### Step 2 — Fix delete confirm dialog button styles

In `delete.js`, `buttonsStyling: false` is set but no `customClass` is provided.

```js
// delete.js — deleteWithSwl()
Swal.fire({
    title: window.translations.are_you_sure,
    text: window.translations.are_you_sure_want_delete,
    icon: 'warning',
    showCancelButton: true,
    buttonsStyling: false,
    customClass: {
        confirmButton: 'btn btn-primary me-2',
        cancelButton:  'btn btn-outline-secondary',
        actions:       'gap-2',
    },
    confirmButtonText: window.translations.confirmButtonText,
    cancelButtonText:  window.translations.cancelButtonText,
});
```

### Step 3 — Standardize all toast positions

Replace all scattered `Swal.fire({ toast: true, position: 'top-start' ... })` in `delete.js` with calls to `showTopToast()`:

```js
// delete.js — after successful delete
showTopToast(window.translations.deleted_successfully, 'success');
```

```js
// delete.js — after error
showTopToast(xhr.responseJSON?.message || 'An error occurred', 'error');
```

### Step 4 — Fix fallback DOM toast in `showTopToast`

Replace the fallback block with the Bootstrap Toast from Step 1, removing all inline style hardcoding.

---

## Acceptance Criteria

- [ ] All toasts appear below the navbar (≥ 72px from top)
- [ ] Toast size is compact (max-width 340px)
- [ ] Toast colors match Sneat theme palette (`#696cff` primary, etc.)
- [ ] Delete confirm dialog has styled Bootstrap buttons
- [ ] All toast positions are consistent (`top-end` / `inset-inline-end`)
- [ ] RTL layout respected (`inset-inline-end` instead of hardcoded `right`)
