@extends('center::layouts.app-next')

@section('page-title', __('center::classrooms.title'))

@section('page-actions')
    <a href="{{ route('center.classrooms.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus me-2"></i> {{ __('center::classrooms.add_new') }}
    </a>
@endsection

@section('panel-content')

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <!-- Classrooms Table -->
            <div class="table-responsive" data-mobile-cards style="min-height: 350px; overflow-x: auto;">
                <table class="table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start">{{ __('center::classrooms.name') }}</th>
                            <th class="border-0">{{ __('center::classrooms.capacity') }}</th>
                            <th class="border-0">{{ __('center::classrooms.assets_count') }}</th>
                            <th class="border-0">{{ __('center::classrooms.created_at') }}</th>
                            <th class="border-0 rounded-end">{{ __('center::classrooms.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classrooms as $classroom)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 shadow-sm border border-2 border-white" style="width: 40px; height: 40px; background-color: {{ $classroom->color ?? '#435ebe' }}; color: white; font-size: 1.2rem;">
                                            @if($classroom->type == 'lab') 💻 @elseif($classroom->type == 'virtual') 🌐 @else 🏢 @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $classroom->name }}</div>
                                            <span class="badge bg-light text-muted border-0 p-0" style="font-size: 0.7rem;">
                                                {{ $classroom->type == 'lab' ? __('center::messages.blade_0221') : ($classroom->type == 'virtual' ? __('center::messages.blade_0222') : __('center::messages.blade_0223')) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $classroom->capacity ? trans_choice('center::classrooms.students_count', $classroom->capacity, ['count' => $classroom->capacity]) : __('center::classrooms.not_specified') }}</td>
                                <td>
                                    @if($classroom->assets->count() > 0)
                                        <div class="d-flex flex-wrap gap-1" style="max-width: 250px;">
                                            @foreach($classroom->assets->take(4) as $asset)
                                                @php
                                                    $icon = 'fa-box';
                                                    $color = 'info';
                                                    if (Str::contains($asset->name, [__('center::messages.blade_0224'), 'Projector'])) { $icon = 'fa-video'; $color = 'primary'; }
                                                    elseif (Str::contains($asset->name, [__('center::messages.blade_0225'), 'AC'])) { $icon = 'fa-snowflake'; $color = 'info'; }
                                                    elseif (Str::contains($asset->name, [__('center::messages.blade_0226'), 'TV'])) { $icon = 'fa-tv'; $color = 'dark'; }
                                                    elseif (Str::contains($asset->name, [__('center::messages.blade_0227')])) { $icon = 'fa-chalkboard'; $color = 'secondary'; }
                                                    elseif (Str::contains($asset->name, [__('center::messages.blade_0228')])) { $icon = 'fa-video-slash'; $color = 'danger'; }
                                                    elseif (Str::contains($asset->name, [__('center::messages.blade_0229')])) { $icon = 'fa-volume-up'; $color = 'warning'; }
                                                @endphp
                                                <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }} border-opacity-25 py-1 px-2" style="font-size: 0.65rem;" title="{{ $asset->name }}">
                                                    <i class="fas {{ $icon }} me-1"></i> {{ $asset->name }}
                                                </span>
                                            @endforeach
                                            @if($classroom->assets->count() > 4)
                                                <span class="badge bg-light text-muted border py-1 px-1" style="font-size: 0.65rem;">
                                                    +{{ $classroom->assets->count() - 4 }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small">---</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $classroom->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="{{ ($loop->remaining < 2 && $classrooms->count() > 2) ? 'dropup' : 'dropdown' }}">
                                        <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                            ⋮
                                        </button>
                                        <ul class="dropdown-menu border-0 shadow">
                                            <li><a class="dropdown-item" href="{{ route('center.classrooms.show', $classroom) }}"><i class="fas fa-eye me-2 text-primary"></i> {{ __('center::classrooms.view_schedule') }}</a></li>
                                            <li><a class="dropdown-item" href="{{ route('center.assets.create', ['classroom_id' => $classroom->id]) }}"><i class="fas fa-plus me-2 text-info"></i> {{ __('center::classrooms.add_asset') }}</a></li>
                                            <li><a class="dropdown-item" href="{{ route('center.classrooms.edit', $classroom) }}"><i class="fas fa-edit me-2 text-warning"></i> {{ __('center::classrooms.edit') }}</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('center.classrooms.destroy', $classroom) }}" method="POST" id="deleteRowForm_1">
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
                                <td colspan="5" class="text-center py-5 text-muted">{{ __('center::classrooms.empty') }}</td>
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
