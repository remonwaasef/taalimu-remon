@extends('layouts.app-next')

@section('title', __('instructor::online_classes.title'))

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'online_classes'])
@endsection

@section('content')
<div class="container-fluid">
    {{-- Header with Create Button --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary-color);">{{ __('instructor::online_classes.title') }}</h4>
            <p class="text-muted small mb-0">{{ __('instructor::online_classes.subtitle') }}</p>
        </div>
        <a href="{{ route('instructor.online_classes.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: var(--primary-color);">
            <i class="fas fa-plus me-2"></i> {{ __('instructor::online_classes.add_new') }}
        </a>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="lessonSearchInput" class="form-control border-start-0 rounded-end-pill" placeholder="{{ __('instructor::online_classes.search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="statusFilter" class="form-select rounded-pill px-3">
                        <option value="">{{ __('instructor::online_classes.all_status') }}</option>
                        <option value="scheduled">{{ __('instructor::online_classes.scheduled') }}</option>
                        <option value="in_progress">{{ __('instructor::online_classes.in_progress') }}</option>
                        <option value="completed">{{ __('instructor::online_classes.completed') }}</option>
                        <option value="cancelled">{{ __('instructor::online_classes.canceled') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="platformFilter" class="form-select rounded-pill px-3">
                        <option value="">{{ __('instructor::online_classes.all_platforms') }}</option>
                        <option value="zoom">Zoom</option>
                        <option value="manual">يدوي</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive" data-mobile-cards style="min-height: 300px;">
                <table class="table table-hover align-middle mb-0 text-center" id="lessonsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 text-start">{{ __('instructor::online_classes.lesson_details') }}</th>
                            <th class="border-0">{{ __('instructor::online_classes.group') }}</th>
                            <th class="border-0">{{ __('instructor::online_classes.start_time') }}</th>
                            <th class="border-0">{{ __('instructor::online_classes.platform') }}</th>
                            <th class="border-0">{{ __('instructor::online_classes.recording_status') }}</th>
                            <th class="border-0">{{ __('instructor::online_classes.status') }}</th>
                            <th class="border-0">{{ __('instructor::online_classes.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($onlineClasses as $lesson)
                        <tr class="lesson-row" data-title="{{ $lesson->title }}" data-status="{{ $lesson->status }}" data-platform="{{ $lesson->platform }}">
                            <td class="px-4 py-3 text-start">
                                <div class="fw-bold fs-5" style="color: var(--primary-color);">{{ $lesson->title }}</div>
                                @if($lesson->description)
                                    <div class="text-muted small mb-1">{{ Str::limit($lesson->description, 60) }}</div>
                                @endif
                                <div class="text-muted small mb-2"><i class="fas fa-video me-1"></i> {{ ucfirst($lesson->platform) }}</div>
                            </td>
                            <td>
                                <span class="badge bg-opacity-10 rounded-pill px-3" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">{{ $lesson->course->title ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div>{{ $lesson->start_time->format('Y-m-d') }}</div>
                                <div class="text-muted small">{{ $lesson->start_time->format('H:i') }} ({{ $lesson->duration_minutes }} {{ __('instructor::online_classes.minutes') }})</div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $lesson->platform === 'zoom' ? 'info' : 'secondary' }} rounded-pill px-3">
                                    {{ ucfirst($lesson->platform) }}
                                </span>
                            </td>
                            <td>
                                @if($lesson->recording_status === 'available' && $lesson->readyRecording)
                                    <span class="badge bg-success rounded-pill px-3">
                                        <i class="fas fa-clapperboard me-1"></i> {{ __('instructor::online_classes.recording_ready') }}
                                    </span>
                                @elseif($lesson->recording_status === 'processing')
                                    <span class="badge bg-warning text-dark rounded-pill px-3">
                                        <i class="fas fa-spinner fa-spin me-1"></i> {{ __('instructor::online_classes.recording_processing') }}
                                    </span>
                                @elseif($lesson->recording_status === 'failed')
                                    <span class="badge bg-danger rounded-pill px-3">
                                        <i class="fas fa-times-circle me-1"></i> {{ __('instructor::online_classes.recording_failed') }}
                                    </span>
                                @else
                                    <span class="text-muted small">{{ __('instructor::online_classes.no_recording') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($lesson->status == 'scheduled')
                                    <span class="badge bg-warning text-dark rounded-pill px-3">{{ __('instructor::online_classes.scheduled') }}</span>
                                @elseif($lesson->status == 'in_progress')
                                    <span class="badge bg-danger text-white rounded-pill px-3 animate__animated animate__pulse">
                                        <i class="fas fa-circle-play me-1"></i> {{ __('instructor::online_classes.in_progress') }}
                                    </span>
                                @elseif($lesson->status == 'completed')
                                    <span class="badge bg-success text-white rounded-pill px-3">{{ __('instructor::online_classes.completed') }}</span>
                                @else
                                    <span class="badge bg-secondary text-white rounded-pill px-3">{{ __('instructor::online_classes.canceled') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown" data-bs-boundary="viewport" style="color: var(--primary-color);">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 p-2">
                                        <li>
                                            <a class="dropdown-item rounded-3" href="{{ route('instructor.online_classes.show', $lesson) }}">
                                                <i class="fas fa-video me-2 text-primary"></i> {{ __('instructor::online_classes.enter_classroom') }}
                                            </a>
                                        </li>
                                        @if($lesson->recording_status === 'available' && $lesson->readyRecording)
                                            <li>
                                                <a class="dropdown-item rounded-3" href="{{ route('instructor.recordings.show', $lesson->readyRecording) }}">
                                                    <i class="fas fa-chart-line me-2 text-info"></i> {{ __('instructor::online_classes.view_analytics') }}
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                        @endif
                                        <li><a class="dropdown-item rounded-3" href="{{ route('instructor.online_classes.edit', $lesson) }}"><i class="fas fa-edit me-2 text-muted"></i> {{ __('instructor::online_classes.edit') }}</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('instructor.online_classes.destroy', $lesson) }}" method="POST" id="deleteForm_{{ $lesson->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item text-danger" data-confirm-delete data-form="deleteForm_{{ $lesson->id }}">
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
                            <td colspan="7" class="text-center py-5">
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

    {{-- Pagination --}}
    <div class="card-footer bg-white border-0 pt-0 d-flex justify-content-center">
        {{ $onlineClasses->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('lessonSearchInput');
    const statusFilter = document.getElementById('statusFilter');
    const platformFilter = document.getElementById('platformFilter');
    const rows = document.querySelectorAll('.lesson-row');
    const noResults = document.getElementById('noLessonsResults');
    const table = document.getElementById('lessonsTable');

    function applyFilters() {
        const query = searchInput.value.trim().toLowerCase();
        const status = statusFilter.value;
        const platform = platformFilter.value;
        let visibleCount = 0;

        rows.forEach(row => {
            const title = row.dataset.title.toLowerCase();
            const rowStatus = row.dataset.status;
            const rowPlatform = row.dataset.platform;
            
            const matchText = !query || title.includes(query);
            const matchStatus = !status || rowStatus === status;
            const matchPlatform = !platform || rowPlatform === platform;
            
            if (matchText && matchStatus && matchPlatform) {
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
    if (statusFilter) statusFilter.addEventListener('change', applyFilters);
    if (platformFilter) platformFilter.addEventListener('change', applyFilters);
});
</script>
@endpush