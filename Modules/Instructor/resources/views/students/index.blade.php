@extends('instructor::components.layouts.master')

@section('page-title', 'قائمة الطلاب')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h3 class="fw-bold mb-0">إدارة الطلاب</h3>
            <p class="text-muted small">عرض جميع الطلاب المسجلين في مجموعاتك</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('instructor.students.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold border-0" style="background: var(--primary-color);">
                <i class="fas fa-user-plus me-2"></i> إضافة طالب جديد
            </a>
        </div>
    </div>

    {{-- Search & Group Filter Bar --}}
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="studentSearchInput" class="form-control border-start-0 rounded-end-pill" placeholder="بحث بالاسم أو رقم الهاتف...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select id="groupFilter" class="form-select rounded-pill">
                        <option value="all">كل المجموعات</option>
                        @php
                            $uniqueCourses = collect();
                            foreach($students as $student) {
                                foreach($student->enrollments as $enrollment) {
                                    if($enrollment->course) {
                                        $uniqueCourses->put($enrollment->course->id, $enrollment->course->title);
                                    }
                                }
                            }
                        @endphp
                        @foreach($uniqueCourses as $id => $title)
                            <option value="{{ $id }}">{{ $title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 text-end">
                    <span id="studentResultCount" class="badge rounded-pill px-3 py-2" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="studentsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3">الطالب</th>
                            <th class="border-0">المجموعات المسجل بها</th>
                            <th class="border-0">الحالة</th>
                            <th class="border-0">تاريخ التسجيل</th>
                            <th class="border-0 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        @php
                            $courseIds = $student->enrollments->pluck('course_id')->filter()->toArray();
                        @endphp
                        <tr class="student-row" data-name="{{ $student->name }}" data-phone="{{ $student->phone }}" data-groups="{{ json_encode($courseIds) }}">
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">
                                            <a href="{{ route('instructor.students.show', $student->id) }}" class="text-decoration-none text-dark" style="color: var(--primary-color) !important;">
                                                {{ $student->name }}
                                            </a>
                                        </div>
                                        <div class="text-muted small">{{ $student->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @foreach($student->enrollments as $enrollment)
                                    @if($enrollment->course)
                                        <span class="badge bg-light text-dark fw-normal rounded-pill border">{{ $enrollment->course->title }}</span>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">نشط</span>
                            </td>
                            <td>{{ $student->created_at?->format('Y-m-d') ?? '--' }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $student->phone);
                                        if (str_starts_with($cleanPhone, '0')) {
                                            $cleanPhone = '20' . substr($cleanPhone, 1);
                                        }
                                    @endphp
                                    <a href="https://api.whatsapp.com/send?phone={{ $cleanPhone }}" target="_blank" class="btn btn-light btn-sm rounded-circle p-2 mx-1 text-success" title="WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="{{ route('instructor.students.show', $student->id) }}" class="btn btn-light btn-sm rounded-circle p-2 mx-1 text-primary" title="عرض الملف التفصيلي">
                                        <i class="fas fa-id-card"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="5" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/fogg-searching.png" alt="No data" style="width: 150px;" class="mb-3 opacity-50">
                                <h6 class="text-muted">لا يوجد طلاب مسجلين حالياً</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="noStudentsResults" class="text-center py-5 d-none">
                <i class="fas fa-user-slash display-4 text-light mb-3"></i>
                <p class="text-muted">لا توجد نتائج مطابقة للبحث.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('studentSearchInput');
    const groupFilter = document.getElementById('groupFilter');
    const rows = document.querySelectorAll('.student-row');
    const noResults = document.getElementById('noStudentsResults');
    const resultCount = document.getElementById('studentResultCount');
    const table = document.getElementById('studentsTable');

    function applyStudentFilters() {
        const query = searchInput.value.trim().toLowerCase();
        const filterGroupId = groupFilter.value;
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.dataset.name.toLowerCase();
            const phone = row.dataset.phone.toLowerCase();
            const groups = JSON.parse(row.dataset.groups);

            let matchSearch = !query || name.includes(query) || phone.includes(query);
            let matchGroup = filterGroupId === 'all' || groups.includes(parseInt(filterGroupId));

            if (matchSearch && matchGroup) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (resultCount) resultCount.textContent = visibleCount + ' طالب';
        
        if (noResults) {
            noResults.classList.toggle('d-none', visibleCount > 0 || rows.length === 0);
        }
        
        if (table) {
            const tbody = table.querySelector('tbody');
            const hasData = rows.length > 0;
            table.classList.toggle('d-none', visibleCount === 0 && hasData);
        }
    }

    if (searchInput) searchInput.addEventListener('input', applyStudentFilters);
    if (groupFilter) groupFilter.addEventListener('change', applyStudentFilters);

    // Initial count
    applyStudentFilters();
});
</script>

<style>
    .hover-primary-link:hover {
        color: var(--primary-color) !important;
        text-decoration: underline !important;
    }
</style>
@endsection
