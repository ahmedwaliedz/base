# Plan — Modals: Dark-Mode Opacity + Sizing + Z-Index

**Status:** Draft (no code shipped yet)
**Audience:** Cursor / coding agent
**Scope:** All Bootstrap modals used in the admin — primarily the email +
notification modals shipped via `<x-model.email />` and
`<x-model.notification />`, plus any other `.modal` that uses the same
`.modal-add-new-address` / `.modal-simple` class chain.
**Branches affected:** master
**Companion to:** [`theme_switcher_chrome_integration.md`](./theme_switcher_chrome_integration.md),
[`sweetalert_toaster_styling.md`](./sweetalert_toaster_styling.md)

---

## TL;DR — three issues

1. **Modal sits under the navbar.** Bootstrap modal default `z-index: 1055`;
   our navbar is bumped to `1200` so the modal's top edge is covered.
2. **Dark-mode backdrop is too transparent** — the page behind the modal
   bleeds through, the modal looks washed out. Plus the modal content
   background is also too transparent.
3. **Modal feels oversized for short forms** — current width is
   `modal-lg` (~800px) with ~2.5rem body padding. For a 2-textarea form
   this is too roomy.

All three fixed in **one append to** `public/style/admin/css/brand-active-states.css`
(section 8 already exists for modals — extend it). No Blade changes
(except optionally swapping `modal-lg` → `modal-md`).

---

## Part 1 · Current state audit

### Modal markup pattern (from `email.blade.php` + `notification.blade.php`)

```html
<div class="modal fade" id="emailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
        <div class="modal-content">
            <div class="modal-body p-0">…</div>
        </div>
    </div>
</div>
```

Classes in play:
- `.modal` — Bootstrap shell, z-index 1055
- `.modal-backdrop` — Bootstrap backdrop, z-index 1050, default opacity 0.5
- `.modal-lg` — Bootstrap large size, max-width 800px
- `.modal-simple` + `.modal-add-new-address` — Sneat vendor classes

### Existing themed overrides (in `brand-active-states.css` Section 8)

```css
.modal-add-new-address { max-width: 580px; }
.modal-add-new-address .modal-content {
    /* glass card with brand tint */
}
[data-theme*='dark'] .modal-add-new-address .modal-content {
    background:
        linear-gradient(135deg,
            rgba(var(--color-brand-primary-rgb), 0.20) 0%,
            rgba(30, 26, 64, 0.96) 100%);
    /* … */
}
```

**Already applied:**
- ✅ Glass card
- ✅ Top accent bar
- ✅ Brand-tinted inputs
- ✅ Animated entrance
- ✅ Rotating close button

**Missing / broken:**
- ❌ Modal z-index — still 1055, covered by navbar
- ❌ Backdrop opacity in dark mode — still 0.5, page bleeds through
- ❌ Modal sizing for short forms (currently `modal-lg` = too wide)

### Z-index audit (confirmed)

| Element                | z-index | Notes                                    |
| ---------------------- | ------- | ---------------------------------------- |
| `.modal-backdrop.show` | 1050    | vendor bootstrap                         |
| `.modal`               | 1055    | vendor bootstrap                         |
| `.layout-navbar`       | 1200    | brand-colors.css patch (already shipped) |
| `.layout-menu`         | 1100    | vendor core                              |

The modal `.modal` (1055) loses to the navbar (1200). The backdrop (1050)
correctly sits between page content and navbar so that's fine, **but the
modal content itself is hidden behind the navbar's top strip**.

---

## Part 2 · Fixes

### Fix 1 · Z-index — modal above navbar

Append to `brand-active-states.css`:

```css
/* ── Modal stack: lift above navbar (1200) ─────────────── */
.modal,
.modal.show,
div.modal {
    z-index: 1300 !important;
}
.modal-backdrop,
.modal-backdrop.show {
    z-index: 1290 !important;
}
/* When modal opens, the navbar can stay where it is — backdrop covers it visually */
```

Backdrop at 1290 sits **above** the navbar (1200), so the backdrop blur
covers the navbar too — gives proper "everything dims except modal" feel.

### Fix 2 · Backdrop opacity (dark + light)

```css
/* Stronger backdrop with subtle brand-blue tint */
.modal-backdrop.show {
    background: rgba(15, 12, 40, 0.72) !important;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* Dark mode → push opacity further (dark page bleeds through more) */
[data-theme*='dark'] .modal-backdrop.show,
.dark-style .modal-backdrop.show {
    background: rgba(10, 8, 24, 0.85) !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

/* Light mode → softer tint */
html.light-style .modal-backdrop.show {
    background: rgba(45, 42, 94, 0.45) !important;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
}
```

Note: `.modal-backdrop` already has `opacity` set via Bootstrap. We
override the **background** instead. Bootstrap's transition still
animates opacity from 0→1; the brand-tinted bg + blur is what shows.

### Fix 3 · Make modal content less transparent in dark mode

Current dark-mode rule (already in Section 8) sets:
```css
background:
    linear-gradient(135deg,
        rgba(var(--color-brand-primary-rgb), 0.20) 0%,
        rgba(30, 26, 64, 0.96) 100%);
```

The `0.96` is good but the top-end `0.20` brand-tint makes the top area
look washed. Tighten:

```css
[data-theme*='dark'] .modal-add-new-address .modal-content,
.dark-style .modal-add-new-address .modal-content {
    background:
        linear-gradient(135deg,
            rgba(var(--color-brand-primary-rgb), 0.12) 0%,
            rgba(20, 18, 44, 0.98) 60%,
            rgba(15, 13, 36, 1)    100%) !important;
    border-color: rgba(var(--color-brand-primary-rgb), 0.36);
    box-shadow:
        0 24px 70px rgba(0, 0, 0, 0.62),
        0 8px 24px rgba(0, 0, 0, 0.40),
        inset 0 1px 0 rgba(var(--color-brand-primary-rgb), 0.16);
}
```

- Top-end alpha 0.12 (was 0.20) → less wash.
- Bottom hits **1.0 alpha** → fully opaque so page content can't bleed.
- Stronger drop shadow + inset highlight → more depth.

### Fix 4 · Compact sizing — replace `modal-lg` with `modal-md`

Two routes:

**Route A (CSS-only, minimal blade change):** keep `modal-lg` but force
the wrapper width down:

```css
.modal-add-new-address {
    max-width: 480px !important;  /* was 580 */
}
.modal-add-new-address .modal-body {
    padding: 1.85rem 1.6rem 1.5rem !important;  /* was 2.5rem 2.25rem 2rem */
}
.modal-add-new-address .modal-content {
    border-radius: 16px !important;  /* was 18 */
}
.modal-add-new-address .address-title {
    font-size: 1.25rem !important;   /* was 1.45rem */
    margin-top: 0.25rem !important;
}
.modal-add-new-address .address-subtitle {
    font-size: 0.82rem;
    margin-bottom: 1.1rem !important;
}
.modal-add-new-address textarea.form-control {
    min-height: 90px;                /* was 110 */
}
.modal-add-new-address .form-control {
    padding: 0.6rem 0.85rem !important;  /* was 0.75rem 1rem */
    font-size: 0.88rem;
}
.modal-add-new-address .btn-primary,
.modal-add-new-address .btn-label-danger {
    padding: 0.5rem 1.5rem !important;
    font-size: 0.88rem !important;
}
.modal-add-new-address .btn-close {
    width: 28px !important;
    height: 28px !important;
    top: 0.75rem !important;
    inset-inline-end: 0.75rem !important;
}
```

**Route B (semantic blade change):** change `modal-lg` → `modal-md` in the
two blade files. Bootstrap will auto-size to ~500px. Both files:

```diff
- <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
+ <div class="modal-dialog modal-simple modal-add-new-address">
```

Removing `modal-lg` falls back to Bootstrap's default modal size (500px).
Combined with our `max-width: 480px` override, the modal lands at the
target size.

**Pick Route B** — cleaner, matches Bootstrap conventions, less CSS
override surface.

### Fix 5 · Subtle border glow when modal opens (delightful detail)

```css
.modal-add-new-address.show .modal-content {
    box-shadow:
        0 24px 70px rgba(var(--color-brand-primary-rgb), 0.32),
        0 0 0 1px rgba(var(--color-brand-primary-rgb), 0.16),
        0 0 32px rgba(var(--color-brand-primary-rgb), 0.20);
}
```

Just visible enough to feel premium without screaming. Adapts via the
existing brand-color variable.

### Fix 6 · Mobile — ensure modal scrolls if short viewport

```css
@media (max-width: 575.98px) {
    .modal-add-new-address { margin: 0.75rem auto; max-width: calc(100% - 1.5rem) !important; }
    .modal-add-new-address .modal-body { padding: 1.35rem 1rem 1.1rem !important; }
    .modal-add-new-address .address-title { font-size: 1.1rem !important; }
}
```

---

## Part 3 · Files touched

| File                                                       | Change                                    |
| ---------------------------------------------------------- | ----------------------------------------- |
| `public/style/admin/css/brand-active-states.css`           | Append "Section 8.1: modal fixes" — Fixes 1–6 above. Update the existing Section 8 dark-mode block to use the tightened gradient (Fix 3). |
| `resources/views/components/model/email.blade.php`         | Remove `modal-lg` class.                  |
| `resources/views/components/model/notification.blade.php`  | Remove `modal-lg` class.                  |

No JS changes. No new files.

---

## Part 4 · Acceptance criteria

- Open the email modal from the users list: it sits **fully above the
  navbar** (top edge visible, never clipped).
- The backdrop covers the navbar too — the only thing fully bright is
  the modal card. Dark mode looks **decisively dark** (no bleed-through).
- Modal width feels compact for 2-textarea forms: ~480px wide on desktop.
- Padding around content is reduced ~25% vs the previous spec.
- Title is smaller (1.25rem), subtitle is smaller (0.82rem).
- Textareas are slightly smaller (90px min height).
- Close button is smaller (28px), still rotates 90° on hover.
- The animated top accent stripe + scale-in entrance still work.
- Brand color picker live-recolors the modal (border, accent stripe,
  glow shadow, inputs focus).
- Mobile (< 576px): modal nearly full-width with reduced padding;
  scrolls if viewport is short.
- No regression on other Bootstrap modals in the project (image preview
  modal, anything else with `.modal` but **not** `.modal-add-new-address`
  — the sizing CSS is scoped to that class).

---

## Part 5 · Out of scope

- **Replacing Bootstrap modals with a custom lightbox library.** Stay on
  Bootstrap.
- **Per-modal custom widths.** All `.modal-add-new-address` modals share
  the same width. If a future modal needs different sizing, give it a
  new class.
- **Image preview modal redesign** (`#globalImagePreviewModal` in
  `footer-links.blade.php`). Out of scope.
- **Modal stacking** (opening modal-B from inside modal-A). Bootstrap
  handles this natively; no extra CSS needed for now.

---

## Notes for the implementing agent

1. **Search Section 8 in `brand-active-states.css`.** That block already
   exists from a prior plan. This plan **extends** it — don't duplicate
   the existing rules, only append the new ones (Fix 1–6).

2. **Backdrop background is the trick.** Don't override `.modal-backdrop`
   `opacity` directly — Bootstrap animates that and stomping it breaks
   the fade-in. Override `background-color` instead; the brand-tinted
   color you set has no animation conflict.

3. **`!important` is required on z-index** because Bootstrap's
   `.modal.show` inline-styled by JS would otherwise win.

4. **Don't change the Bootstrap modal data-bs-* attributes.** The
   `data-bs-toggle="modal"`, `data-bs-target="#emailModal"`, and
   `data-bs-dismiss="modal"` triggers are JS hooks — leave them alone.

5. After QA, if the modal still looks too tall on short pages, consider
   adding `.modal-dialog-centered` to the dialog class so it
   vertically-centers. Out of scope for this plan but cheap follow-up.
