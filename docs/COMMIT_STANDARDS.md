# معايير رسائل الـ Commits — مشروع Taalimu

## الصيغة المطلوبة

```
<type>(<scope>): <subject>

<body> (اختياري)
```

## أنواع الـ Commits (type)

| النوع | الاستخدام | مثال |
|:---|:---|:---|
| `feat` | ميزة جديدة | `feat(attendance): add QR scanner` |
| `fix` | إصلاح خطأ | `fix(payment): resolve Paymob callback` |
| `security` | إصلاح أمني | `security(auth): add rate limiting` |
| `perf` | تحسين أداء | `perf(analytics): add query caching` |
| `refactor` | إعادة هيكلة بدون تغيير سلوك | `refactor(payment): extract service` |
| `docs` | توثيق | `docs: update developer guide` |
| `test` | اختبارات | `test(registration): add flow tests` |
| `chore` | مهام صيانة | `chore: archive dead commands` |
| `i18n` | ترجمة | `i18n: add payment translations` |
| `migration` | تغيير قاعدة بيانات | `migration: add performance indexes` |

## النطاق (scope) — اختياري

`auth`, `payment`, `student`, `instructor`, `course`, `attendance`, `finance`, `admin`, `subscription`, `api`, `ui`

## قواعد مهمة

1. **الموضوع (subject):** بالإنجليزية، يبدأ بفعل أمر (add, fix, update, remove)، بدون نقطة في النهاية
2. **الحد الأقصى:** 72 حرف للسطر الأول
3. **الأمان:** أي commit يحتوي `security` يجب مراجعته من شخصين
4. **قاعدة البيانات:** أي commit يحتوي `migration` يجب اختباره في بيئة تجريبية أولاً
5. **ممنوع:** Commits بعنوان `fix`, `update`, `changes` بدون وصف

## أمثلة صحيحة

```
feat(student): add batch import from Excel
fix(payment): handle Paymob session expiry fallback
security(upload): validate MIME types before storing
perf(dashboard): cache analytics queries for 30 minutes
refactor(payment): extract shared logic to PaymentProcessingService
i18n: add Arabic/English/French payment translations
migration: add composite index on sales(tenant_id, student_id)
```

## أمثلة خاطئة ❌

```
fixed bug                    ← بدون نوع أو وصف
update                       ← غير واضح
feat: stuff                  ← غير وصفي
added new feature to system  ← بدون نوع
```
