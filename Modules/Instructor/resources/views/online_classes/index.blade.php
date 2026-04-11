@extends('instructor::components.layouts.hope-master')

@section('page-title', __('instructor::dashboard.online_classes') ?? 'الدروس الأونلاين')
@section('page-subtitle', 'إدارة وتفعيل روابط الدروس الخاصة بك')

@section('page-actions')
    <a href="{{ route('instructor.online_classes.create') }}" class="btn btn-glass">
        <i class="fas fa-plus me-2"></i> إضافة درس جديد
    </a>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Search Bar --}}

    {{-- Search Bar --}}
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="lessonSearchInput" class="form-control border-start-0 rounded-end-pill" placeholder="ابحث عن درس...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive" style="min-height: 300px;">
                <table class="table table-hover align-middle mb-0 text-center" id="lessonsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 text-start">تفاصيل الدرس</th>
                            <th class="border-0">المجموعة</th>
                            <th class="border-0">توقيت البدء</th>
                            <th class="border-0">رابط البث</th>
                            <th class="border-0">الحالة</th>
                            <th class="border-0">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($onlineClasses as $lesson)
                        <tr class="lesson-row" data-title="{{ $lesson->title }}">
                            <td class="px-4 py-3 text-start">
                                <div class="fw-bold fs-5" style="color: var(--primary-color);">{{ $lesson->title }}</div>
                                <div class="text-muted small mb-2"><i class="fas fa-video me-1"></i> {{ ucfirst($lesson->platform) }}</div>
                            </td>
                            <td>
                                <span class="badge bg-opacity-10 rounded-pill px-3" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">{{ $lesson->course->title ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div>{{ $lesson->start_time->format('Y-m-d') }}</div>
                                <div class="text-muted small">{{ $lesson->start_time->format('h:i A') }} ({{ $lesson->duration_minutes }} دقيقة)</div>
                            </td>
                            <td>
                                <a href="{{ $lesson->meeting_link }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-external-link-alt me-1"></i> فتح الرابط
                                </a>
                                @if($lesson->meeting_id)
                                    <div class="text-muted small mt-1">ID: {{ $lesson->meeting_id }}</div>
                                @endif
                                @if($lesson->meeting_password)
                                    <div class="text-muted small">Pass: {{ $lesson->meeting_password }}</div>
                                @endif
                            </td>
                            <td>
                                @if($lesson->status == 'scheduled')
                                    <span class="badge bg-warning text-dark rounded-pill px-3">مجدول</span>
                                @elseif($lesson->status == 'in_progress')
                                    <span class="badge bg-info text-white rounded-pill px-3">قيد الانعقاد</span>
                                @elseif($lesson->status == 'completed')
                                    <span class="badge bg-success text-white rounded-pill px-3">منتهي</span>
                                @else
                                    <span class="badge bg-danger text-white rounded-pill px-3">ملغي</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown" style="color: var(--primary-color);">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 p-2">
                                        <li><a class="dropdown-item rounded-3" href="{{ route('instructor.online_classes.edit', $lesson->id) }}"><i class="fas fa-edit me-2 text-muted"></i> تعديل</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('instructor.online_classes.destroy', $lesson->id) }}" method="POST" id="deleteForm_{{ $lesson->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item text-danger" onclick="if(confirm('هل أنت متأكد من الحذف؟')) document.getElementById('deleteForm_{{ $lesson->id }}').submit();">
                                                    <i class="fas fa-trash me-2"></i> حذف
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="6" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/fogg-searching.png" alt="No data" style="width: 150px;" class="mb-3 opacity-50">
                                <h6 class="text-muted">لا توجد دروس أونلاين حتى الآن.</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div id="noLessonsResults" class="text-center py-5 d-none">
                <i class="fas fa-search-minus display-4 text-light mb-3"></i>
                <p class="text-muted">لا توجد نتائج مطابقة</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('lessonSearchInput');
    const rows = document.querySelectorAll('.lesson-row');
    const noResults = document.getElementById('noLessonsResults');
    const table = document.getElementById('lessonsTable');

    function applyFilters() {
        const query = searchInput.value.trim().toLowerCase();
        let visibleCount = 0;

        rows.forEach(row => {
            const title = row.dataset.title.toLowerCase();
            if (!query || title.includes(query)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (noResults) noResults.classList.toggle('d-none', visibleCount > 0 || rows.length === 0);
        if (table) table.classList.toggle('d-none', visibleCount === 0 && rows.length > 0);
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
});
</script>
@endpush
@endsection
