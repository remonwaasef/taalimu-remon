@extends('center::layouts.hope-master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">{{ __('center::assets.title') }}</h2>
            <p class="text-muted small mb-0">{{ __('center::messages.blade_0107') }}</p>
        </div>
        <a href="{{ route('center.assets.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <span class="me-2">+</span> {{ __('center::assets.add_new') }}
        </a>
    </div>

    @forelse($assets as $classroomName => $items)
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-light bg-opacity-50 py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="fas fa-door-open me-2 text-primary"></i> 
                    {{ $classroomName == '---' ? __('center::messages.blade_0110') : $classroomName }}
                    <span class="badge bg-white text-primary border rounded-pill ms-2 fw-normal" style="font-size: 0.75rem;">{{ trans_choice('center::assets.pieces_count', $items->count()) }}</span>
                </h6>
                @if($classroomName != '---')
                    @php $cid = $items->first()->classroom_id; @endphp
                    <a href="{{ route('center.assets.create', ['classroom_id' => $cid]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" style="font-size: 0.75rem;">{{ __('center::assets.add_to_classroom') }}</a>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-white">
                            <tr class="text-muted" style="font-size: 0.85rem;">
                                <th class="border-0 ps-4">{{ __('center::assets.name') }}</th>
                                <th class="border-0">{{ __('center::assets.type') }}</th>
                                <th class="border-0">{{ __('center::assets.status') }}</th>
                                <th class="border-0 text-end pe-4">{{ __('center::assets.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $asset)
                                <tr class="border-top">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center py-2">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                                <i class="fas fa-box small"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $asset->name }}</div>
                                                @if($asset->code) <small class="text-muted">{{ $asset->code }}</small> @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border-0 rounded-pill px-3 fw-normal" style="font-size: 0.75rem;">
                                            {{ __('center::assets.' . $asset->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'active' => 'success',
                                                'maintenance' => 'warning',
                                                'broken' => 'danger',
                                                'lost' => 'secondary'
                                            ];
                                            $color = $statusColors[$asset->status] ?? 'info';
                                        @endphp
                                        <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill px-3 fw-bold" style="font-size: 0.7rem;">
                                            <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                            {{ __('center::assets.' . $asset->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                                ⋮
                                            </button>
                                            <ul class="dropdown-menu border-0 shadow">
                                                <li><a class="dropdown-item" href="{{ route('center.assets.edit', $asset) }}"><i class="fas fa-edit me-2 text-warning"></i> {{ __('center::assets.edit') }}</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('center.assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('{{ __('center::assets.confirm_delete') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="dropdown-item text-danger">{{ __('center::messages.blade_0108') }}</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-5 text-center">
                <div class="text-muted opacity-25 mb-3">
                    <i class="fas fa-boxes fa-5x"></i>
                </div>
                <h5 class="text-muted">{{ __('center::assets.empty') }}</h5>
                <a href="{{ route('center.assets.create') }}" class="btn btn-primary rounded-pill mt-3 px-4">{{ __('center::messages.blade_0109') }}</a>
            </div>
        </div>
    @endforelse
@endsection
