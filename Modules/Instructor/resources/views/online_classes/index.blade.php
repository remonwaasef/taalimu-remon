@extends('instructor::components.layouts.hope-master')

@section('page-title', __('instructor::dashboard.online_classes'))
@section('page-subtitle', __('instructor::online_classes.subtitle'))

@section('page-actions')
    <a href="{{ route('instructor.online_classes.create') }}" class="btn btn-glass">
        <i class="fas fa-plus me-2"></i> {{ __('instructor::online_classes.add_new') }}
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
                        <input type="text" id="lessonSearchInput" class="form-control border-start-0 rounded-end-pill" placeholder="{{ __('instructor::online_classes.search') }}">
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
                            <th class="border-0 px-4 py-3 text-start">{{ __('instructor::online_classes.lesson_details') }}</th>
                            <th class="border-0">{{ __('instructor::online_classes.group') }}</th>
                            <th class="border-0">{{ __('instructor::online_classes.start_time') }}</th>
                            <th class="border-0">{{ __('instructor::online_classes.meeting_link') }}</th>
                            <th class="border-0">{{ __('instructor::online_classes.status') }}</th>
                            <th class="border-0">{{ __('instructor::online_classes.actions') }}</th>
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
                                <div class="text-muted small">{{ $lesson->start_time->format('h:i A') }} ({{ $lesson->duration_minutes }} {{ __('instructor::online_classes.minutes') }})</div>
                            </td>
                            <td>
                                <a href="{{ $lesson->meeting_link }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-external-link-alt me-1"></i> {{ __('instructor::online_classes.open_link') }}
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
                                    <span class="badge bg-warning text-dark rounded-pill px-3">{{ __('instructor::online_classes.scheduled') }}</span>
                                @elseif($lesson->status == 'in_progress')
                                    <span class="badge bg-info text-white rounded-pill px-3">{{ __('instructor::online_classes.in_progress') }}</span>
                                @elseif($lesson->status == 'completed')
                                    <span class="badge bg-success text-white rounded-pill px-3">{{ __('instructor::online_classes.completed') }}</span>
                                @else
                                    <span class="badge bg-danger text-white rounded-pill px-3">{{ __('instructor::online_classes.canceled') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown" data-bs-boundary="viewport" style="color: var(--primary-color);">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 p-2">
                                        <li><a class="dropdown-item rounded-3" href="{{ route('instructor.online_classes.edit', $lesson->id) }}"><i class="fas fa-edit me-2 text-muted"></i> {{ __('instructor::online_classes.edit') }}</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('instructor.online_classes.destroy', $lesson->id) }}" method="POST" id="deleteForm_{{ $lesson->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item text-danger" onclick="if(confirm('{{ __('instructor::online_classes.confirm_delete') }}')) document.getElementById('deleteForm_{{ $lesson->id }}').submit();">
                                                    <i class="fas fa-trash me-2"></i> {{ __('instructor::online_classes.delete') }}
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
                                <div class="mb-4">
                                    <div class="d-inline-flex p-4 rounded-circle mb-3" style="background: rgba(16, 185, 129, 0.05);">
                                        <i class="fas fa-video-slash text-primary" style="font-size: 3rem; opacity: 0.5;"></i>
                                    </div>
                                </div>
                                <h6 class="text-muted fw-bold">{{ __('instructor::online_classes.no_classes') }}</h6>
                                <p class="text-muted small">{{ __('instructor::online_classes.no_classes_desc') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div id="noLessonsResults" class="text-center py-5 d-none">
                <i class="fas fa-search-minus display-4 text-light mb-3"></i>
                <p class="text-muted">{{ __('instructor::online_classes.no_results') }}</p>
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
