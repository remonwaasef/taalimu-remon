@extends('instructor::components.layouts.hope-master')

@section('page-title', __('instructor::groups.title'))
@section('page-subtitle', __('instructor::groups.subtitle'))

@section('page-actions')
    <a href="{{ route('instructor.groups.create') }}" class="btn btn-glass">
        <i class="fas fa-plus me-2"></i> {{ __('instructor::groups.create_new') }}
    </a>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Search Bar --}}

    {{-- Search Bar --}}
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="groupSearchInput" class="form-control border-start-0 rounded-end-pill" placeholder="{{ __('instructor::groups.search_placeholder') }}">
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <span id="groupResultCount" class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive" style="min-height: 300px;">
                <table class="table table-hover align-middle mb-0 text-center" id="groupsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 text-start">{{ __('instructor::groups.table_group') }}</th>
                            <th class="border-0">{{ __('instructor::groups.students_count') }}</th>
                            <th class="border-0">{{ __('instructor::groups.registration_link') }}</th>
                            <th class="border-0">{{ __('instructor::groups.status') }}</th>
                            <th class="border-0">{{ __('instructor::groups.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                        <tr class="group-row" data-title="{{ $course->title }}">
                            <td class="px-4 py-3 text-start">
                                <div class="fw-bold fs-5" style="color: var(--primary-color);">{{ $course->title }}</div>
                                <div class="text-muted small mb-2">{{ __('instructor::groups.code', ['code' => $course->code ?? 'N/A']) }}</div>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($course->schedules as $schedule)
                                        @php
                                            $days = [
                                                __('instructor::groups.days.Sunday'),
                                                __('instructor::groups.days.Monday'),
                                                __('instructor::groups.days.Tuesday'),
                                                __('instructor::groups.days.Wednesday'),
                                                __('instructor::groups.days.Thursday'),
                                                __('instructor::groups.days.Friday'),
                                                __('instructor::groups.days.Saturday')
                                            ];
                                        @endphp
                                        <span class="badge border border-primary text-primary rounded-pill fw-normal" style="color: var(--primary-color) !important; border-color: var(--primary-color) !important; background: transparent;">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            {{ $days[$schedule->day_of_week] }} 
                                            ({{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }})
                                        </span>
                                    @empty
                                        <span class="badge bg-light text-muted border rounded-pill fw-normal">{{ __('instructor::groups.no_schedules') }}</span>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-opacity-10 rounded-pill px-3" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">{{ $course->enrollments_count ?? 0 }} {{ __('instructor::groups.student') }}</span>
                            </td>
                            <td>
                                @if($course->registration_token)
                                    <div class="input-group input-group-sm rounded-pill overflow-hidden" style="max-width: 250px; margin: 0 auto; border: 1px solid var(--primary-color);">
                                        <input type="text" class="form-control border-0 bg-light text-center" value="{{ $course->getRegistrationUrl() }}" readonly id="link_{{ $course->id }}">
                                        <button class="btn btn-primary px-3 border-0" style="background: var(--primary-color);" onclick="copyLink('link_{{ $course->id }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-muted small">{{ __('instructor::groups.no_link') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ __('instructor::groups.active') }}</span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown" style="color: var(--primary-color);">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 p-2">
                                        <li><a class="dropdown-item rounded-3" href="{{ route('instructor.scanner', $course->id) }}"><i class="fas fa-qrcode me-2" style="color: var(--primary-color);"></i> {{ __('instructor::groups.qr_scanner') }}</a></li>
                                        <li><a class="dropdown-item rounded-3" href="{{ route('instructor.groups.edit', $course->id) }}"><i class="fas fa-edit me-2 text-muted"></i> {{ __('instructor::groups.edit_data') }}</a></li>
                                        <li>
                                            <form action="{{ route('instructor.groups.duplicate', $course->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item"><i class="fas fa-copy me-2 text-muted"></i> {{ __('instructor::groups.duplicate_group') }}</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('instructor.groups.rotate-link', $course->id) }}" method="POST" id="rotateForm_{{ $course->id }}">
                                                @csrf
                                                <button type="button" class="dropdown-item" onclick="if(confirm('{{ __('instructor::groups.confirm_rotate_link') }}')) document.getElementById('rotateForm_{{ $course->id }}').submit();">
                                                    <i class="fas fa-sync me-2 text-muted"></i> {{ __('instructor::groups.generate_new_link') }}
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('instructor.groups.destroy', $course->id) }}" method="POST" id="deleteForm_{{ $course->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item text-danger" onclick="if(confirm('{{ __('instructor::groups.confirm_delete_group') }}')) document.getElementById('deleteForm_{{ $course->id }}').submit();">
                                                    <i class="fas fa-trash me-2"></i> {{ __('instructor::groups.delete_group') }}
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="5" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/fogg-searching.png" alt="No data" style="width: 150px;" class="mb-3 opacity-50">
                                <h6 class="text-muted">{{ __('instructor::groups.no_groups') }}</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="noGroupsResults" class="text-center py-5 d-none">
                <i class="fas fa-search-minus display-4 text-light mb-3"></i>
                <p class="text-muted">{{ __('instructor::groups.no_results') }}</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    var copyText = document.getElementById(id);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    alert("{{ __('instructor::groups.link_copied') }}");
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('groupSearchInput');
    const rows = document.querySelectorAll('.group-row');
    const noResults = document.getElementById('noGroupsResults');
    const resultCount = document.getElementById('groupResultCount');
    const table = document.getElementById('groupsTable');

    function applyGroupFilters() {
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

        if (resultCount) resultCount.textContent = visibleCount + ' {{ __('instructor::groups.student') }}';
        if (noResults) noResults.classList.toggle('d-none', visibleCount > 0 || rows.length === 0);
        if (table) table.classList.toggle('d-none', visibleCount === 0 && rows.length > 0);
    }

    if (searchInput) searchInput.addEventListener('input', applyGroupFilters);
    applyGroupFilters();
});
</script>
@endpush
@endsection
