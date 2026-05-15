# Feature Finalization and Validation Skill

## Purpose

Verify that a feature is complete, correct, and production-ready before considering it done. This is the final gate before delivery.

---

## When to Use

- After finishing feature implementation
- Before marking a task as complete
- After bug fix to verify no regressions
- Before creating a pull request
- After refactoring to verify behavior preserved

---

## Process

### Step 1: Architecture Compliance Check

Verify the feature follows project architecture rules:

| Layer | Check |
|-------|-------|
| Form Request | All validation in `app/Http/Requests/` — none in controller |
| Service | Business logic in `app/Services/Admin/` or `app/Services/Api/` — none in controller |
| Controller | Thin — only orchestration (receive request → call service → return response) |
| Model | Relationships defined, fillable set, traits applied (Translatable, HasMedia) |
| Blade | Presentation only — no queries, no business logic, no validation |
| Routes | Registered in `routes/admin.php` or `routes/api.php` with proper naming |

### Step 2: Functional Verification

| Check | How |
|-------|-----|
| Feature works as expected | Test the main use case manually or via tests |
| Edge cases handled | Empty data, invalid input, missing relations |
| Error handling | Exceptions caught, proper error responses returned |
| State management | Loading, empty, error, success states in UI |

### Step 3: API Verification (if applicable)

| Check | Details |
|-------|---------|
| Response structure | Follows project response traits (`successResponse`, `errorResponse`) |
| Status codes | 200 success, 201 created, 422 validation, 401 unauthorized, 404 not found |
| Validation errors | Return proper field-level error messages |
| Postman examples | All endpoints documented with request/response examples |
| Authentication | Protected endpoints require Sanctum token |

### Step 4: UI Verification (if applicable)

| Check | Details |
|-------|---------|
| Uses project components | `<x-form.*>`, `<x-table.*>` components used |
| Consistent layout | Follows existing page patterns |
| States handled | Loading, empty, error, success feedback |
| Validation display | Form errors display properly |
| Actions work | Buttons, links, forms all functional |
| Translations | Keys in `lang/ar/admin/` and `lang/en/admin/` |

### Step 5: Testing Verification

| Check | Details |
|-------|---------|
| Success test | Happy path works |
| Validation test | Invalid input returns errors |
| Auth test | Unauthorized access blocked |
| Not found test | Invalid ID returns 404 |
| Edge cases | Boundary conditions covered |
| Tests pass | `php artisan test` passes |

### Step 6: Code Quality

| Check | Details |
|-------|---------|
| No duplication | Shared logic extracted to service/trait |
| Clean naming | Follows project naming conventions |
| No dead code | Unused imports, methods, variables removed |
| No debug code | No `dd()`, `dump()`, `Log::debug()` left |
| Eager loading | No N+1 queries (use `->with()`) |

### Step 7: Documentation

| Check | Details |
|-------|---------|
| API documented | Postman collection updated |
| Request/Response examples | All cases documented |
| Complex logic explained | Inline comments for "why" |

---

## Verification Checklist

Use this as a final pass before marking complete:

- [ ] **Architecture:** Form Request → Service → Controller (thin) → Blade (presentation)
- [ ] **Validation:** All rules in Form Request, tested
- [ ] **Business logic:** All in Service, tested
- [ ] **API responses:** Consistent structure, all status codes correct
- [ ] **UI:** Uses project components, handles all states
- [ ] **Translations:** Arabic and English keys present
- [ ] **Tests:** Success + failure + edge cases pass
- [ ] **Documentation:** API documented with Postman examples
- [ ] **No debug code:** No `dd()`, `dump()`, `ray()` left
- [ ] **No N+1:** Eager loading applied

---

## Final Decision

After running through all checks:

| Decision | When |
|----------|------|
| ✅ **Ready** | All checks pass, no issues found |
| ⚠️ **Needs minor fixes** | Small issues found, list them |
| ❌ **Not ready** | Architecture violations or missing tests |

---

## Output Format

```
## Finalization Report

### Architecture: ✅/❌
- [details]

### Functionality: ✅/❌
- [details]

### API/UI: ✅/❌
- [details]

### Testing: ✅/❌
- [details]

### Code Quality: ✅/❌
- [details]

### Documentation: ✅/❌
- [details]

### Issues Found:
- [list any issues]

### Final Decision: Ready / Needs Fixes / Not Ready
```

---

## Completion Standard

A finalization review is NOT complete until:

- [ ] All 7 verification steps executed
- [ ] Issues documented with specific file/line references
- [ ] Final decision given with justification
- [ ] Any fixes needed are listed as actionable items