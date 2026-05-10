# خطة إصلاح صفحة Users CRUD — Index & Table

## المصدر
مراجعة شاملة لصفحتَي `admin/users/index` و `admin/users/table` — اكتُشفت 12 مشكلة مقسّمة إلى: أخطاء حقيقية، مشاكل جودة كود، واقتراحات UX.

---

## الملفات المعنية

| الملف | نوع التغيير |
|-------|------------|
| `resources/views/admin/users/index.blade.php` | Bug fix |
| `resources/views/admin/users/table.blade.php` | Bug fix + UX |
| `resources/views/admin/users/parts/statistics.blade.php` | جودة كود |
| `public/style/admin/css/crud-stats.css` | جودة كود |
| `public/style/admin/css/users-stats.css` | حذف (dead code) |
| `app/Http/Controllers/Admin/UserController.php` | UX / تسمية |
| `app/Services/Admin/UserService.php` | جودة كود |

---

## 🐛 الأخطاء الحقيقية (Bugs) — أولوية عالية

---

### Bug 1 — `index.blade.php`: `:exportCopy` مكرر

**الحالي:**
```blade
<x-table.buttons
    :exportCopy="true" :exportPdf="true" :exportExcel="true"
    :exportWord="true"
    :exportJson="true"
    :exportCopy="true"   {{-- ← مكرر --}}
    ...>
```

**الإصلاح:** احذف السطر المكرر الثاني `:exportCopy="true"`.

---

### Bug 2 — `table.blade.php`: `data-bs-toggle` مكرر على زرَّي Notify و Email

**المشكلة:** HTML يقرأ أول `data-bs-toggle` فقط، الثاني يُتجاهَل.
نتيجة: الـ Modal يعمل ✓ لكن الـ Tooltip لا يظهر أبداً ✗

**الحالي (زر Notify):**
```blade
<a data-bs-toggle="modal" data-bs-target="#notificationModal" data-id="{{ $user->id }}"
   class="send-notification custom-icon users-action-btn users-action-notify"
   data-bs-toggle="tooltip"   {{-- ← يُتجاهَل --}}
   data-placement="top"
   title="@lang('admin/main.send_notification')">
```

**الإصلاح — استخدام wrapper `<span>` للـ tooltip:**
```blade
<span data-bs-toggle="tooltip" data-placement="top" title="@lang('admin/main.send_notification')">
    <a data-bs-toggle="modal" data-bs-target="#notificationModal"
       data-id="{{ $user->id }}"
       class="send-notification custom-icon users-action-btn users-action-notify">
        <i class="ti ti-bell-plus"></i>
    </a>
</span>
```

طبّق نفس الحل على زر Email.

---

### Bug 3 — `table.blade.php`: زر Email بدون `data-id`

**المشكلة:** زر الإشعار يمرر `data-id="{{ $user->id }}"` بشكل صحيح، لكن زر الإيميل لا يمرره. إذا كان الـ email modal يقرأ `data-id` لمعرفة المستخدم المستهدف، الإيميل سيُرسل للمستخدم الغلط أو لن يعمل.

**الحالي:**
```blade
<a data-bs-toggle="modal" data-bs-target="#emailModal"
   class="custom-icon users-action-btn users-action-email"
   ...>
```

**الإصلاح:** أضف `data-id="{{ $user->id }}"`:
```blade
<a data-bs-toggle="modal" data-bs-target="#emailModal"
   data-id="{{ $user->id }}"
   class="custom-icon users-action-btn users-action-email"
   ...>
```

---

### Bug 4 — `table.blade.php`: `alt` الصورة خطأ نسخ-لصق

**الحالي:**
```blade
<img src="{{ $user->image }}" alt="Product-9" class="rounded-2">
```

**الإصلاح:**
```blade
<img src="{{ $user->image }}" alt="{{ $user->name }}" class="rounded-2">
```

---

### Bug 5 — `table.blade.php`: كلاس `sorting_1` مثبَّت يدوياً على عمودين

**المشكلة:** `sorting_1` كلاس يُضيفه DataTables ديناميكياً على العمود المرتَّب حالياً. تثبيته على عمودين دائماً يُحدث تعارضاً مع منطق DataTables.

**الحالي:**
```blade
<td class="sorting_1">                    {{-- عمود الاسم --}}
...
<td class="sorting_1 users-status-cell">  {{-- عمود الحالة --}}
```

**الإصلاح:** احذف `sorting_1` من كلا الـ `<td>`:
```blade
<td>
...
<td class="users-status-cell">
```

---

## ⚠️ مشاكل جودة الكود — أولوية متوسطة

---

### جودة 1 — `statistics.blade.php` + `crud-stats.css`: Inline styles تتجاوز نظام التوكنز

**المشكلة:** الـ CSS يُعرّف `--card-accent-rgb` لكل نوع كارد:
```css
.crud-stats__card--active  { --card-accent-rgb: var(--color-success-rgb); }
.crud-stats__card--inactive { --card-accent-rgb: var(--color-warning-rgb); }
.crud-stats__card--today   { --card-accent-rgb: var(--color-info-rgb); }
```
لكن `.crud-stats__icon` يستخدم لون بنفسجي ثابت بدلاً من `--card-accent-rgb`، فيُعوَّض بـ inline styles في الـ blade.

**الإصلاح في `crud-stats.css`** — غيّر:
```css
/* قبل */
.crud-stats__icon {
  background: rgba(105, 108, 255, 0.14);
  color: #696cff;
}
```
إلى:
```css
/* بعد */
.crud-stats__icon {
  background: rgba(var(--card-accent-rgb), 0.14);
  color: rgb(var(--card-accent-rgb));
}
```

**الإصلاح في `statistics.blade.php`** — احذف كل `style="..."` من عناصر `<span class="crud-stats__icon">`:
```blade
{{-- قبل --}}
<span class="crud-stats__icon" style="background: rgba(40, 199, 111, 0.14); color: #28c76f;">

{{-- بعد --}}
<span class="crud-stats__icon">
```
الكلاسات `.crud-stats__card--active` وغيرها ستُعيّن `--card-accent-rgb` تلقائياً.

---

### جودة 2 — `users-stats.css`: ملف dead code كامل

**المشكلة:** `users-stats.css` هو نسخة طبق الأصل من `crud-stats.css` بـ prefix مختلف (`users-stats__` بدل `crud-stats__`). الـ component `x-table.statistics` يستخدم كلاسات `crud-stats__` فقط، ما يعني أن `users-stats.css` **لا يُستخدم في أي مكان**.

**الإصلاح:**
1. تحقق بـ grep أن لا يوجد استخدام لأي `users-stats__` كلاس في المشروع
2. احذف الملف `public/style/admin/css/users-stats.css` نهائياً

---

### جودة 3 — `UserService.php`: `createVars()` و `editVars()` متطابقان

**الحالي:**
```php
public function createVars(): array {
    return [
        'countries' => Country::where('is_active', true)->forSelect([...])->toArray(),
        'receiveNotificationsOptions' => [...],
    ];
}

public function editVars(): array {
    return [  // نفس الكود تماماً
        'countries' => Country::where('is_active', true)->forSelect([...])->toArray(),
        'receiveNotificationsOptions' => [...],
    ];
}
```

**الإصلاح:**
```php
public function editVars(): array {
    return $this->createVars();
}
```

---

## 💡 اقتراحات UX — أولوية اختيارية

---

### UX 1 — `index.blade.php`: إضافة فلتر الحالة (is_blocked)

**الحالي:** الفلاتر = name, phone, email فقط.

**الإضافة المقترحة:**
```blade
:filters="[
    ['type' => 'text',   'name' => 'name'],
    ['type' => 'text',   'name' => 'phone'],
    ['type' => 'text',   'name' => 'email'],
    [
        'type'    => 'select',
        'name'    => 'is_blocked',
        'options' => [
            ['id' => '',  'name' => __('admin/main.all')],
            ['id' => '0', 'name' => __('admin/main.active')],
            ['id' => '1', 'name' => __('admin/main.blocked')],
        ],
    ],
]"
```

---

### UX 2 — `UserController.php`: تصحيح label إحصائية "inactive"

**المشكلة:** `$inactive = where('is_blocked', true)` — لكن البطاقة تقول "inactive". المحظور ≠ غير النشط دلالياً.

**خياران:**
- **أ)** غيّر label البطاقة في `statistics.blade.php` من `__('admin/main.inactive')` إلى `__('admin/main.blocked')` وغيّر الأيقونة من `ti-player-pause` إلى `ti-lock`
- **ب)** أضف مفتاح `admin/main.blocked` في ملفات اللغة وحدّث البطاقة

---

### UX 3 — `table.blade.php`: `title` زر Block ديناميكي

**الحالي:**
```blade
title="{{ __('admin/main.blocked') }}"   {{-- ثابت دائماً --}}
```

**الإصلاح:**
```blade
title="{{ $user->is_blocked ? __('admin/main.unblock') : __('admin/main.block') }}"
```

---

### UX 4 — `table.blade.php`: إخفاء أزرار Edit/Notify/Email للمحذوفين

**الحالي:** جميع أزرار (Edit, View, Notify, Email) تظهر للمستخدم المحذوف.

**المقترح:**
```blade
@if (!$user->deleted_at)
    <a href="{{ route('admin.users.edit', ...) }}" ...>edit</a>
    {{-- notify --}}
    {{-- email --}}
@endif

{{-- View يمكن يبقى لكل الحالات --}}
<a href="{{ route('admin.users.show', ...) }}" ...>view</a>

@if ($user->deleted_at)
    {{-- restore --}}
@else
    {{-- delete --}}
@endif
```

---

## ترتيب التنفيذ المقترح

```
المرحلة 1 — Bugs (يجب أولاً):
  Step 1 → index.blade.php      : احذف :exportCopy المكرر
  Step 2 → table.blade.php      : أضف data-id لزر Email
  Step 3 → table.blade.php      : صحح alt الصورة
  Step 4 → table.blade.php      : احذف sorting_1 من العمودين
  Step 5 → table.blade.php      : فصل tooltip/modal بـ wrapper <span>

المرحلة 2 — جودة الكود:
  Step 6 → crud-stats.css       : اجعل .crud-stats__icon يرث --card-accent-rgb
  Step 7 → statistics.blade.php : احذف inline styles من <span class="crud-stats__icon">
  Step 8 → UserService.php      : editVars() يستدعي createVars()
  Step 9 → users-stats.css      : تحقق من عدم الاستخدام ثم احذف الملف

المرحلة 3 — UX (اختياري):
  Step 10 → index.blade.php     : أضف فلتر is_blocked
  Step 11 → statistics.blade.php: غيّر label/icon بطاقة "inactive" → "blocked"
  Step 12 → table.blade.php     : title زر Block ديناميكي
  Step 13 → table.blade.php     : إخفاء أزرار غير ملائمة للمحذوفين
```

---

## قائمة تحقق نهائية

- [ ] لا يوجد prop مكرر في `x-table.buttons`
- [ ] Tooltip يظهر على جميع أزرار الأكشن (بما فيها Notify و Email)
- [ ] `data-id` موجود على زر Email في كل صف
- [ ] `alt` الصورة = اسم المستخدم
- [ ] لا يوجد `sorting_1` مثبَّت يدوياً في الـ `<td>`
- [ ] أيقونات بطاقات الإحصائيات ملوّنة بلون الكارد المناسب (بدون inline styles)
- [ ] لا يوجد `users-stats.css` في المشروع
- [ ] `UserService::editVars()` لا يكرر كود `createVars()`
- [ ] فلتر الحالة (Active/Blocked) موجود في الـ filter panel
- [ ] label و icon بطاقة "blocked" واضحان دلالياً
- [ ] title زر Block يعكس الحالة الحالية
