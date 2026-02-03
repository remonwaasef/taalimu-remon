@extends('center::layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::classrooms.title') }}</h2>
        <a href="{{ route('center.classrooms.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <span class="me-2">+</span> {{ __('center::classrooms.add_new') }}
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <!-- Classrooms Table -->
            <div class="table-responsive" style="min-height: 350px; overflow-x: auto;">
                <table class="table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start">{{ __('center::classrooms.name') }}</th>
                            <th class="border-0">{{ __('center::classrooms.capacity') }}</th>
                            <th class="border-0">{{ __('center::classrooms.created_at') }}</th>
                            <th class="border-0 rounded-end">{{ __('center::classrooms.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classrooms as $classroom)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            🏢
                                        </div>
                                        <div class="fw-bold">{{ $classroom->name }}</div>
                                    </div>
                                </td>
                                <td>{{ $classroom->capacity ?? __('center::classrooms.not_specified') }} {{ trans_choice('center::classrooms.student|center::classrooms.students', $classroom->capacity ?? 0) }}</td>
                                <td class="text-muted">{{ $classroom->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="{{ ($loop->remaining < 2 && $classrooms->count() > 2) ? 'dropup' : 'dropdown' }}">
                                        <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                            ⋮
                                        </button>
                                        <ul class="dropdown-menu border-0 shadow">
                                            <li><a class="dropdown-item" href="{{ route('center.classrooms.show', $classroom) }}"><i class="fas fa-eye me-2 text-primary"></i> {{ __('center::classrooms.view_schedule') }}</a></li>
                                            <li><a class="dropdown-item" href="{{ route('center.classrooms.edit', $classroom) }}"><i class="fas fa-edit me-2 text-warning"></i> {{ __('center::classrooms.edit') }}</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('center.classrooms.destroy', $classroom) }}" method="POST" onsubmit="return confirm('{{ __('center::classrooms.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item text-danger">{{ __('center::classrooms.delete') }}</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">{{ __('center::classrooms.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $classrooms->links() }}
            </div>
        </div>
    </div>
@endsection
