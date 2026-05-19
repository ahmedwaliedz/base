# Admin Dashboard Fix Plan

## Goal

Fix the current admin dashboard crashes, missing translations, notification page behavior, wallet profile area, and country/user UI issues while preserving the existing Laravel admin architecture.

## Context

Project conventions from `.cursor` say to keep controllers thin, put business logic in services, use Blade for presentation only, reuse existing base CRUD patterns, and avoid introducing new patterns unless needed.

The current issues fall into two groups:

- Blocking runtime errors that prevent routes from rendering.
- UI and translation polish after the pages are reachable.

## Phase 1 - Unblock Runtime Errors

Status: completed in the first implementation pass.

### 1. Add shared model naming helpers

- [x] Fix shared model naming helpers.

Problem:

Several admin CRUD controllers call:

```php
$this->model::smallPluralName();
$this->model::smallSingularName();
```

These methods exist in `App\Traits\GeneralTrait`, but several CRUD models do not use the trait.

Files to update:

- [x] `app/Models/Page.php`
- [x] `app/Models/Faq.php`
- [x] `app/Models/Post.php`
- [x] `app/Models/IntroPage.php`
- [x] `app/Models/Slider.php`

Fix:

- [x] Import `App\Traits\GeneralTrait`.
- [x] Add `GeneralTrait` to the model `use` list.

Expected result:

- Fixes errors like `Call to undefined method App\Models\Page::smallPluralName()`.
- Fixes the same pattern for FAQ, Post, Intro Page, Slider, and any admin CRUD route relying on `AdminBaseController`.

### 2. Fix slider service method signature

- [x] Fix slider service method signature.

Problem:

`SliderService::switchActive(int|string $id): bool` conflicts with `CrudBaseService::switchActive($id)`.

Files to update:

- [x] `app/Services/Admin/SliderService.php`
- [x] Check whether `app/Services/Admin/Base/CrudBaseService.php` should be adjusted.
- [ ] Check `app/Http/Controllers/Admin/SliderController.php`.

Recommended fix:

- [x] Make the base service and child service method contracts consistent.
- [x] Prefer service methods returning booleans and controllers returning JSON responses.

Expected result:

- Fixes fatal error:

```text
Declaration of App\Services\Admin\SliderService::switchActive(string|int $id): bool must be compatible with App\Services\Admin\Base\CrudBaseService::switchActive($id)
```

### 3. Fix categories relation count

- [x] Fix categories relation count.

Problem:

`CategoryService` calls `withCount(['children', 'posts'])`, but `Category` does not define `posts()`.

Files to update:

- [x] `app/Services/Admin/CategoryService.php`
- [x] Check `app/Models/Category.php` only if a real post-category relation exists in the domain.

Recommended fix:

- [x] If posts are not related to categories, remove `posts` from `withCount`.
- [x] If posts should belong to categories, add the real relationship and confirm the database column/pivot table first.

Expected result:

- Fixes `/admin/categories` error:

```text
Call to undefined method App\Models\Category::posts()
```

### 4. Normalize select component options

- [x] Normalize select component options.

Problem:

`App\View\Components\Form\Select::$options` is typed as `array`, but several Blade views pass Laravel collections from `->get()->map(...)`.

Files to update:

- [x] `app/View/Components/Form/Select.php`
- [ ] Check `resources/views/components/form/select.blade.php` if needed.

Recommended fix:

- [x] Normalize incoming options in the component constructor:

```php
$this->options = collect($options['options'] ?? [])->all();
```

Expected result:

- Fixes `/admin/categories/create` error:

```text
Cannot assign Illuminate\Support\Collection to property App\View\Components\Form\Select::$options of type array
```

- Also protects regions, cities, districts, users, admins, profile, and other forms that pass collections.

## Phase 2 - Translation Fixes

### 5. Fix notification form user type key

- [ ] Fix notification form user type key.

Problem:

`admin/inputs.user_type` or the typo-like `notification_user_ype` appears on `/admin/notifications` in the send notification tab.

Files to update:

- [ ] `resources/views/admin/notifications/parts/tab-forms/notification.blade.php`
- [ ] `lang/ar/admin/inputs.php`
- [ ] `lang/en/admin/inputs.php`
- [ ] Check `lang/ar/admin/main.php`.
- [ ] Check `lang/en/admin/main.php`.

Fix:

- [ ] Use a consistent key: `notification_user_type` or `user_type`.
- [ ] Add Arabic and English translations.
- [ ] Replace the typo key `notification_user_ype`.

### 6. Fix users filter blocked key

- [ ] Fix users filter blocked key.

Problem:

`admin/inputs.is_blocked (اختياري)` appears in `/admin/users` filter.

Files to update:

- [ ] `lang/ar/admin/inputs.php`
- [ ] `lang/en/admin/inputs.php`
- [ ] Check `resources/views/admin/users/index.blade.php`.

Fix:

- [ ] Add `is_blocked` to admin input translations.
- [ ] Confirm the filter component uses the intended translation namespace.

### 7. Fix admin statistics route translation

- [ ] Fix admin statistics route translation.

Problem:

`admin/routes.admin.admins.statistics.index` appears on `/admin/admins/100`.

Files to update:

- [ ] `lang/ar/admin/routes.php`
- [ ] `lang/en/admin/routes.php`
- [ ] Check `app/Traits/Role/RoleTrait.php`.

Fix:

- [ ] Add `statistics` under the `admins` route group in both languages.
- [ ] Consider improving `RoleTrait::translateRouteName()` fallback so it handles route actions without appending `.index` incorrectly.

## Phase 3 - Notifications Page

### 8. Improve admin notification icon and route behavior

- [ ] Improve admin notification icon and route behavior.

Current state:

- Header notification bell already routes to `admin.notifications.index`.
- The sidebar already has notifications.

Files to update:

- [ ] `resources/views/admin/layouts/parts/notifications.blade.php`
- [ ] Check nav/header CSS files.

Fix:

- [ ] Keep route as `/admin/notifications`.
- [ ] Style the header notification icon/count similarly to the reference dashboard.
- [ ] Keep accessibility label and unread count behavior.

### 9. Build full admin notifications page

- [ ] Build full admin notifications page.

Current state:

- `/admin/notifications` exists and contains send notification, email, and SMS tabs.

Files to update:

- [ ] `resources/views/admin/notifications/index.blade.php`
- [ ] `resources/views/admin/notifications/parts/tab-forms/notification.blade.php`
- [ ] Check `app/Http/Controllers/Admin/NotificationController.php`.

Fix:

- [ ] Keep send notification functionality.
- [ ] Add a visible admin notification list/history section if data is available from Laravel notifications.
- [ ] Show unread/read state, timestamp, and message.
- [ ] Add empty state if no notifications exist.

## Phase 4 - User Profile Fixes

### 10. Add wallet charge section

- [ ] Add wallet charge section.

Current state:

`resources/views/admin/users/parts/tab-wallet.blade.php` is only a placeholder.

Files to update:

- [ ] `resources/views/admin/users/parts/tab-wallet.blade.php`
- [ ] `resources/views/admin/users/show.blade.php`
- [ ] `lang/ar/admin/main.php`
- [ ] `lang/en/admin/main.php`
- [ ] Backend files only if wallet storage already exists or is confirmed.

Recommended scope:

- [ ] Add UI for wallet balance and charge form only after confirming whether wallet tables/columns exist.
- [ ] Current search found no wallet model, transaction model, balance column, or charge service.

Decision needed before backend implementation:

- [ ] Decide whether to add only the UI placeholder for now.
- [ ] Decide whether to create wallet persistence, wallet transactions, service methods, validation, and routes.

### 11. Fix account age card overflow

- [ ] Fix account age card overflow.

Problem:

`عمر الحساب` displays a long decimal value like `6.002270240741 يوم`, which does not fit inside the card.

Files to update:

- [ ] `resources/views/admin/users/show.blade.php`
- [ ] User profile CSS, likely `public/style/admin/css/user-crud.css`
- [ ] Check `app/Http/Controllers/Admin/UserController.php` or `app/Services/Admin/UserService.php` where stats are calculated.

Fix:

- [ ] Format account age as an integer day count or a human readable duration.
- [ ] Add CSS constraints so stat values wrap or truncate cleanly.

Expected result:

- The value fits inside the card in Arabic and English.

## Phase 5 - Countries UI Fixes

### 12. Align countries table columns

- [ ] Align countries table columns.

Problem:

`المناطق`, `المدن`, and `الحالة` look shifted in the countries table.

Files to update:

- [ ] `resources/views/admin/countries/table.blade.php`
- [ ] `resources/views/admin/countries/index.blade.php`
- [ ] `public/style/admin/css/countries.css`

Fix:

- [ ] Align header and cell classes.
- [ ] Ensure count badges are centered in their columns.
- [ ] Ensure the status cell has fixed alignment.

### 13. Make country active state clearer

- [ ] Make country active state clearer.

Problem:

The switch does not clearly communicate whether the country is active or inactive.

Files to update:

- [ ] `resources/views/admin/countries/table.blade.php`
- [ ] `public/style/admin/css/countries.css`

Fix:

- [ ] Add a visible status badge near the switch, or use clearer switch colors/states.
- [ ] Keep the toggle action behavior unchanged.

### 14. Fix country show icons and flag fallback

- [ ] Fix country show icons and flag fallback.

Problems:

- Cities icon is not showing on the country show page.
- Flag area shows fallback path/alt instead of a real default flag image.

Files to update:

- [ ] `resources/views/admin/countries/show.blade.php`
- [ ] `app/Models/Country.php`
- [ ] Check media/file trait used by country flags.
- [ ] `public/style/admin/css/countries.css`

Fix:

- [ ] Replace missing icon class with a valid Tabler icon.
- [ ] Ensure `default.png` resolves to a real asset URL.
- [ ] Add Blade fallback if the flag URL is empty or invalid.

## Phase 6 - Verify Broken Routes

After the runtime fixes, verify these routes render:

- [ ] `/admin/pages`
- [ ] `/admin/sliders`
- [ ] `/admin/faqs`
- [ ] `/admin/posts`
- [ ] `/admin/intro-pages`
- [ ] `/admin/seo`
- [ ] `/admin/socials`
- [ ] `/admin/regions`
- [ ] `/admin/districts`
- [ ] `/admin/contact-messages`
- [ ] `/admin/complaints`
- [ ] `/admin/categories`
- [ ] `/admin/categories/create`
- [ ] `/admin/notifications`
- [ ] `/admin/users`
- [ ] `/admin/admins/100`
- [ ] `/admin/countries`
- [ ] `/admin/countries/{id}`

## Validation Checklist

- [ ] Fatal errors are gone from all listed routes.
- [ ] Category index renders without `posts()` relation error.
- [ ] Category create renders without select collection type error.
- [ ] Notification tab labels are translated.
- [ ] Users filter blocked label is translated.
- [ ] Admin statistics route label is translated.
- [ ] Header notification icon routes to `/admin/notifications`.
- [ ] Admin notifications page has a usable notification area.
- [ ] User account age card fits and displays a clean value.
- [ ] Wallet tab has the agreed charge UI or backend-backed wallet flow.
- [ ] Countries table columns align correctly.
- [ ] Country active/inactive state is visually clear.
- [ ] Country show page displays cities icon and flag fallback correctly.

## Recommended Implementation Order

1. [x] Add `GeneralTrait` to missing CRUD models.
2. [x] Fix `switchActive` service signature consistency.
3. [x] Fix `CategoryService` relation count.
4. [x] Normalize select component options.
5. [ ] Fix translations.
6. [ ] Improve notifications header and page.
7. [ ] Fix user profile account age.
8. [ ] Add wallet charge section according to confirmed backend scope.
9. [ ] Polish countries table and show page.
10. [ ] Verify all listed routes in browser.
