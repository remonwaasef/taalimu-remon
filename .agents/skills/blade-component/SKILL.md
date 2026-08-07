---
name: Blade Component Builder
description: Skill for creating Blade views and components that strictly follow the Taalimu Design System, using design tokens, supporting RTL/LTR, dark mode, and maintaining consistent visual language across the platform.
---

# Blade Component Builder — مهارة إنشاء واجهات Blade

## متى تُستخدم هذه المهارة؟
عند إنشاء صفحات Blade جديدة، تعديل واجهات موجودة، أو بناء Components مشتركة.

---

## المرجع الإلزامي

قبل أي عمل على الواجهات، اقرأ:
1. `.agents/DESIGN_SYSTEM.md` — الألوان والخطوط والمسافات
2. `resources/css/design-tokens.css` — CSS Variables (المصدر الرسمي)
3. `resources/css/global-components.css` — الأنماط المشتركة

---

## Design Tokens — المتغيرات الأساسية

### لا تكتب ألوانًا يدوية أبدًا. استخدم CSS Variables:

```css
/* ✅ صحيح */
.card {
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text-primary);
}

.btn-primary {
    background: var(--primary-500);
}

.btn-primary:hover {
    background: var(--primary-600);
}

/* ❌ خطأ — ألوان يدوية */
.card {
    background: #ffffff;
    border: 1px solid #e7ecef;
    color: #1f2937;
}
```

### مرجع الألوان السريع:
| Token | Light | Dark | الاستخدام |
|---|---|---|---|
| `--primary-500` | `#2E8B83` | `#2E8B83` | اللون الأساسي |
| `--primary-600` | `#25746D` | `#25746D` | Hover |
| `--primary-700` | `#1E5E58` | `#1E5E58` | Active/Pressed |
| `--bg` | `#F6F8FA` | `#0F1720` | خلفية الصفحة |
| `--surface` | `#FFFFFF` | `#17202B` | سطح البطاقات |
| `--card` | `#FFFFFF` | `#1D2935` | خلفية البطاقة |
| `--border` | `#E7ECEF` | `#2B3644` | الحدود |
| `--text-primary` | `#1F2937` | `#F9FAFB` | النص الرئيسي |
| `--text-secondary` | `#6B7280` | `#CBD5E1` | النص الثانوي |
| `--success` | `#22C55E` | `#22C55E` | نجاح |
| `--warning` | `#F59E0B` | `#F59E0B` | تحذير |
| `--error` | `#EF4444` | `#EF4444` | خطأ |
| `--info` | `#3B82F6` | `#3B82F6` | معلومات |

---

## قالب صفحة Blade كاملة

```blade
@extends('center::layouts.app')

@section('title', __('module.page_title'))

@section('content')
<div class="page-container">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">{{ __('module.page_title') }}</h1>
            <p class="page-subtitle">{{ __('module.page_description') }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('center.resource.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                {{ __('module.add_new') }}
            </a>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Main Content Card --}}
    <div class="content-card">
        {{-- Content here --}}
    </div>
</div>
@endsection

@push('styles')
<style>
    /* استخدم design tokens فقط */
    .page-container {
        padding: var(--spacing-6, 24px);
    }
    .content-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: var(--spacing-6, 24px);
    }
</style>
@endpush

@push('scripts')
<script>
    // Alpine.js للتفاعلات البسيطة
    document.addEventListener('alpine:init', () => {
        // ...
    });
</script>
@endpush
```

---

## قالب جدول بيانات (Data Table)

```blade
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('module.name') }}</th>
                <th>{{ __('module.status') }}</th>
                <th>{{ __('module.created_at') }}</th>
                <th class="text-center">{{ __('common.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-medium">{{ $item->name }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-{{ $item->status === 'active' ? 'success' : 'secondary' }}-subtle text-{{ $item->status === 'active' ? 'success' : 'secondary' }}">
                            {{ __("statuses.{$item->status}") }}
                        </span>
                    </td>
                    <td>{{ $item->created_at->format('Y-m-d') }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            @can('update', $item)
                            <a href="{{ route('center.resource.edit', $item) }}"
                               class="btn btn-sm btn-outline-primary"
                               title="{{ __('common.edit') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan

                            @can('delete', $item)
                            <form action="{{ route('center.resource.destroy', $item) }}"
                                  method="POST"
                                  onsubmit="return confirm('{{ __('common.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        title="{{ __('common.delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        {{ __('common.no_data') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($items->hasPages())
    <div class="d-flex justify-content-center mt-3">
        {{ $items->links() }}
    </div>
@endif
```

---

## قالب Form (نموذج إدخال)

```blade
<form action="{{ route('center.resource.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-3">
        {{-- حقل نصي --}}
        <div class="col-md-6">
            <label for="name" class="form-label">{{ __('module.name') }} <span class="text-danger">*</span></label>
            <input type="text"
                   id="name"
                   name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $item->name ?? '') }}"
                   required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- حقل اختياري (Select) --}}
        <div class="col-md-6">
            <label for="status" class="form-label">{{ __('module.status') }}</label>
            <select id="status"
                    name="status"
                    class="form-select @error('status') is-invalid @enderror">
                <option value="active" {{ old('status', $item->status ?? '') === 'active' ? 'selected' : '' }}>
                    {{ __('statuses.active') }}
                </option>
                <option value="inactive" {{ old('status', $item->status ?? '') === 'inactive' ? 'selected' : '' }}>
                    {{ __('statuses.inactive') }}
                </option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- حقل ملف --}}
        <div class="col-md-6">
            <label for="attachment" class="form-label">{{ __('module.attachment') }}</label>
            <input type="file"
                   id="attachment"
                   name="attachment"
                   class="form-control @error('attachment') is-invalid @enderror"
                   accept=".jpg,.png,.pdf">
            @error('attachment')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- أزرار الإرسال --}}
    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('center.resource.index') }}" class="btn btn-outline-secondary">
            {{ __('common.cancel') }}
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>
            {{ __('common.save') }}
        </button>
    </div>
</form>
```

---

## دعم RTL/LTR

### قواعد أساسية:
- **لا تستخدم** `margin-left` أو `margin-right` مباشرة — استخدم `ms-*` و `me-*` (margin-start, margin-end)
- **لا تستخدم** `text-left` أو `text-right` — استخدم `text-start` و `text-end`
- **لا تستخدم** `float-left` أو `float-right` — استخدم `float-start` و `float-end`
- **أيقونات الأسهم**: استخدم `fa-arrow-start` / `fa-arrow-end` أو `flip` مع CSS

```blade
{{-- ✅ صحيح — RTL-safe --}}
<div class="d-flex align-items-center gap-2">
    <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}"></i>
    <span class="ms-2">{{ $text }}</span>
</div>

{{-- CSS RTL-safe --}}
<style>
.sidebar {
    padding-inline-start: 16px;  /* RTL-safe */
    border-inline-end: 1px solid var(--border);  /* RTL-safe */
}
</style>
```

---

## دعم Dark Mode

كل الأنماط يجب أن تعمل في الوضعين الفاتح والداكن:

```css
/* Design Tokens تتغير تلقائيًا مع Dark Mode */
.card {
    background: var(--card);        /* أبيض في Light، داكن في Dark */
    color: var(--text-primary);     /* أسود في Light، أبيض في Dark */
    border: 1px solid var(--border); /* فاتح في Light، داكن في Dark */
}

/* لا تكتب ألوانًا ثابتة */
```

---

## الأيقونات

- **المكتبة**: Font Awesome 6
- **النمط**: Outline فقط (`far` أو `fas` بدون تعبئة ثقيلة)
- **الحجم الافتراضي**: `20px`
- **لا تضيف مكتبة أيقونات جديدة** بدون مراجعة

```blade
{{-- ✅ صحيح --}}
<i class="fas fa-users"></i>
<i class="far fa-calendar"></i>

{{-- ❌ خطأ — مكتبة مختلفة --}}
<span class="material-icons">person</span>
```

---

## فلسفة التصميم

- **90% ألوان محايدة** — لا تكثر من الألوان
- **8% ألوان العلامة التجارية** — الأخضر `#2E8B83` فقط للعناصر المهمة
- **2% ألوان الحالة** — أحمر/أخضر/أصفر/أزرق للتنبيهات فقط
- **المسافات البيضاء جزء من التصميم** — لا تزدحم العناصر
- **ظلال خفيفة فقط** — `shadow-sm` أو `shadow-md` — لا تستخدم ظلال ثقيلة
- **حدود مستديرة**: بطاقات `16px`، أزرار `12px`، مدخلات `12px`، شارات `999px`

---

## قائمة التحقق

- [ ] جميع الألوان من Design Tokens (لا ألوان يدوية)
- [ ] جميع النصوص من ملفات الترجمة (`__()` أو `@lang`)
- [ ] النصوص موجودة في 3 لغات (ar, en, fr)
- [ ] الصفحة تعمل في RTL و LTR
- [ ] الصفحة تعمل في Dark Mode
- [ ] الأيقونات من Font Awesome 6
- [ ] كل Form يحتوي `@csrf`
- [ ] كل عنصر تفاعلي له `id` وصفي وفريد
- [ ] Error messages تظهر بشكل صحيح (`@error`)
- [ ] Pagination مستخدمة (لا `get()` على جداول كبيرة)
- [ ] Authorization checks (`@can`) على الأزرار الحساسة
- [ ] الصفحة responsive وتعمل على الموبايل
