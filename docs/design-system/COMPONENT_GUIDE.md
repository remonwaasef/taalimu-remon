# Taalimu Blade Component Guide

> Complete catalog of reusable Blade components in `resources/views/components/ui/`.

---

## 1. Buttons `<x-ui.button>`

```blade
<!-- Primary Action -->
<x-ui.button variant="primary" icon="fas fa-plus">
    Add Student
</x-ui.button>

<!-- Secondary Action -->
<x-ui.button variant="secondary" size="sm">
    Filter
</x-ui.button>

<!-- Outline / Ghost -->
<x-ui.button variant="outline" icon="fas fa-download">
    Export CSV
</x-ui.button>
<x-ui.button variant="ghost">
    Cancel
</x-ui.button>

<!-- Loading State -->
<x-ui.button variant="primary" :loading="true">
    Saving...
</x-ui.button>

<!-- Link Mode -->
<x-ui.button variant="primary" href="{{ route('center.students.index') }}">
    View Students
</x-ui.button>
```

**Props**:
- `variant`: `primary` | `secondary` | `outline` | `ghost` | `danger` | `success` | `warning` | `info` | `link`
- `size`: `xs` | `sm` | `md` | `lg` | `icon` | `icon-sm` | `icon-lg`
- `icon`: FontAwesome class string (e.g. `fas fa-plus`)
- `iconRight`: FontAwesome class string
- `loading`: `bool`
- `disabled`: `bool`
- `href`: `string|null`

---

## 2. Form Fields & Inputs

### Form Field `<x-ui.form-field>`
```blade
<x-ui.form-field label="Student Name" name="name" :required="true" helper="Enter full legal name">
    <x-ui.input name="name" placeholder="e.g. Ahmed Ali" icon="fas fa-user" />
</x-ui.form-field>
```

### Input `<x-ui.input>`
```blade
<x-ui.input type="email" name="email" placeholder="student@example.com" icon="fas fa-envelope" />
```

### Select `<x-ui.select>`
```blade
<x-ui.select name="gender" placeholder="Select Gender">
    <option value="male">Male</option>
    <option value="female">Female</option>
</x-ui.select>
```

### Textarea `<x-ui.textarea>`
```blade
<x-ui.textarea name="notes" rows="4" placeholder="Additional notes..." />
```

---

## 3. Feedback & Alerts

### Alert `<x-ui.alert>`
```blade
<x-ui.alert type="success" title="Payment Recorded">
    The receipt #1042 was generated successfully.
</x-ui.alert>

<x-ui.alert type="error" title="Validation Failed">
    Please check required fields.
</x-ui.alert>
```

### Toast `<x-ui.toast>`
Trigger via JavaScript or Alpine:
```javascript
window.dispatchEvent(new CustomEvent('toast', {
    detail: { type: 'success', title: 'Saved', message: 'Record saved successfully.' }
}));
```

---

## 4. Cards & Containers `<x-ui.card>`

```blade
<x-ui.card title="Recent Enrollments" subtitle="Students registered this week">
    <x-slot name="action">
        <x-ui.button variant="outline" size="sm">View All</x-ui.button>
    </x-slot>

    <!-- Card Content -->
    <p>Body content goes here</p>

    <x-slot name="footer">
        Showing 5 of 120 students
    </x-slot>
</x-ui.card>
```

---

## 5. Tables `<x-ui.table>`

```blade
<x-ui.table :headers="['Student', 'Course', 'Status', 'Actions']">
    @foreach($students as $student)
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
            <td class="px-6 py-4 flex items-center gap-3">
                <x-ui.avatar :name="$student->name" size="sm" />
                <span class="font-bold text-slate-900 dark:text-slate-100">{{ $student->name }}</span>
            </td>
            <td class="px-6 py-4">{{ $student->course }}</td>
            <td class="px-6 py-4">
                <x-ui.badge variant="success" dot="true">Active</x-ui.badge>
            </td>
            <td class="px-6 py-4">
                <x-ui.button variant="ghost" size="icon-sm" icon="fas fa-ellipsis-v" />
            </td>
        </tr>
    @endforeach
</x-ui.table>
```

---

## 6. Overlays

### Modal `<x-ui.modal>`
```blade
<x-ui.button @click="$dispatch('open-modal', 'student-modal')">
    Open Modal
</x-ui.button>

<x-ui.modal id="student-modal" title="Add New Student" size="lg">
    <!-- Form content -->
    <x-slot name="footer">
        <x-ui.button variant="ghost" @click="$dispatch('close-modal', 'student-modal')">Cancel</x-ui.button>
        <x-ui.button variant="primary">Submit</x-ui.button>
    </x-slot>
</x-ui.modal>
```

### Drawer `<x-ui.drawer>`
```blade
<x-ui.button @click="$dispatch('open-drawer', 'filter-drawer')">
    Filters
</x-ui.button>

<x-ui.drawer id="filter-drawer" title="Filter Students" size="md">
    <!-- Filters content -->
</x-ui.drawer>
```

---

## 7. Badges `<x-ui.badge>`

```blade
<x-ui.badge variant="brand" dot="true">Enrolled</x-ui.badge>
<x-ui.badge variant="success" icon="fas fa-check">Paid</x-ui.badge>
<x-ui.badge variant="danger">Overdue</x-ui.badge>
<x-ui.badge variant="warning">Trial</x-ui.badge>
```
