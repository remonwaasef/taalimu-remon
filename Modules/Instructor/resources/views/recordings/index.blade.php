@extends('layouts.app-next')

@section('title', __('instructor::recordings.title'))

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'recordings'])
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary-color);">{{ __('instructor::recordings.title') }}</h4>
            <p class="text-muted small mb-0">{{ __('instructor::recordings.subtitle') }}</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive" data-mobile-cards style="min-height: 300px;">
                <table class="table table-hover align-middle mb-0 text-center" id="recordingsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 text-start">{{ __('instructor::recordings.lesson') }}</th>
                            <th class="border-0">{{ __('instructor::recordings.group') }}</th>
                            <th class="border-0">{{ __('instructor::recordings.available_at') }}</th>
                            <th class="border-0">{{ __('instructor::recordings.duration') }}</th>
                            <th class="border-0">{{ __('instructor::recordings.unique_viewers') }}</th>
                            <th class="border-0">{{ __('instructor::recordings.avg_watch') }}</th>
                            <th class="border-0">{{ __('instructor::recordings.completion_rate') }}</th>
                            <th class="border-0">{{ __('instructor::recordings.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recordings as $rec)
                        <tr class="recording-row" data-title="{{ $rec->onlineClass->title ?? '' }}">
                            <td class="px-4 py-3 text-start">
                                <div class="fw-bold" style="color: var(--primary-color);">{{ $rec->onlineClass->title ?? 'N/A' }}</div>
                                <div class="text-muted small">{{ $rec->available_at->format('Y-m-d H:i') }}</div>
                            </td>
                            <td>
                                <span class="badge bg-opacity-10 rounded-pill px-3" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">{{ $rec->onlineClass->course->title ?? 'N/A' }}</span>
                            </td>
                            <td>{{ $rec->available_at->format('Y-m-d') }}</td>
                            <td>{{ number_format($rec->duration_seconds / 60, 1) }} {{ __('instructor::recordings.minutes') }}</td>
                            <td>{{ $rec->analytics['unique_viewers'] ?? 0 }}</td>
                            <td>{{ number_format(($rec->analytics['avg_watch_seconds'] ?? 0) / 60, 1) }} {{ __('instructor::recordings.minutes') }}</td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px;" role="progressbar" aria-valuenow="{{ $rec->analytics['completion_rate'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar bg-success" style="width: {{ $rec->analytics['completion_rate'] ?? 0 }}%"></div>
                                    </div>
                                    <span class="fw-bold small">{{ $rec->analytics['completion_rate'] ?? 0 }}%</span>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('instructor.recordings.show', $rec) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                    <i class="fas fa-chart-line me-1"></i> {{ __('instructor::recordings.view') }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-clapperboard fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted fw-bold">{{ __('instructor::recordings.no_recordings') }}</h6>
                                <p class="text-muted small">{{ __('instructor::recordings.no_recordings_desc') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 pt-0 d-flex justify-content-center">
            {{ $recordings->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('lessonSearchInput');
    if (searchInput) {
        const rows = document.querySelectorAll('.recording-row');
        const table = document.getElementById('recordingsTable');
        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            let visible = 0;
            rows.forEach(row => {
                const match = !query || row.dataset.title.toLowerCase().includes(query);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            if (table) table.classList.toggle('d-none', visible === 0 && rows.length > 0);
        });
    }
});
</script>
@endpush