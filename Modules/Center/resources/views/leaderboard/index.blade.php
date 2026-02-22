@extends('center::layouts.master')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-4">
        <h4 class="fw-bold mb-1"><i class="fas fa-trophy me-2 text-warning"></i>{{ __('center::messages.blade_0458') }}</h4>
        <p class="text-muted small mb-0">{{ __('center::messages.blade_0459') }}</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 ps-4" style="width: 80px;">{{ __('center::messages.blade_0460') }}</th>
                                    <th class="border-0">{{ __('center::messages.blade_0461') }}</th>
                                    <th class="border-0">{{ __('center::messages.blade_0462') }}</th>
                                    <th class="border-0 text-end pe-4">{{ __('center::messages.blade_0463') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaderboard as $index => $user)
                                <tr class="{{ $index < 3 ? 'bg-opacity-10 bg-warning' : '' }}">
                                    <td class="ps-4">
                                        @if($index == 0)
                                            <div class="rank-badge rank-1"><i class="fas fa-crown text-warning fs-4"></i></div>
                                        @elseif($index == 1)
                                            <div class="rank-badge rank-2"><i class="fas fa-medal text-secondary fs-5"></i></div>
                                        @elseif($index == 2)
                                            <div class="rank-badge rank-3"><i class="fas fa-medal text-orange fs-5" style="color: #cd7f32;"></i></div>
                                        @else
                                            <span class="fw-bold text-muted ps-2">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-sm rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $user->name }}</div>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-star text-warning"></i>
                                            <span class="fw-bold fs-5">{{ number_format($user->points) }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        @php
                                            $level = __('center::messages.blade_0474');
                                            $badgeClass = 'bg-secondary';
                                            if ($user->points >= 1000) { $level = __('center::messages.blade_0475'); $badgeClass = 'bg-danger'; }
                                            elseif ($user->points >= 500) { $level = __('center::messages.blade_0476'); $badgeClass = 'bg-primary'; }
                                            elseif ($user->points >= 200) { $level = __('center::messages.blade_0477'); $badgeClass = 'bg-success'; }
                                        @endphp
                                        <span class="badge {{ $badgeClass }} rounded-pill px-3">{{ $level }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">{{ __('center::messages.blade_0464') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white mb-4 overflow-hidden">
                <div class="card-body p-4 position-relative" style="z-index: 1;">
                    <h5 class="fw-bold mb-3">{{ __('center::messages.blade_0465') }}</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-start gap-3">
                            <i class="fas fa-check-circle mt-1"></i>
                            <div>
                                <div class="fw-bold">{{ __('center::messages.blade_0466') }}</div>
                                <small class="text-white-50">{{ __('center::messages.blade_0467') }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <i class="fas fa-graduation-cap mt-1"></i>
                            <div>
                                <div class="fw-bold">{{ __('center::messages.blade_0468') }}</div>
                                <small class="text-white-50">{{ __('center::messages.blade_0469') }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <i class="fas fa-tasks mt-1"></i>
                            <div>
                                <div class="fw-bold">{{ __('center::messages.blade_0470') }}</div>
                                <small class="text-white-50">{{ __('center::messages.blade_0471') }}</small>
                            </div>
                        </div>
                    </div>
                    <i class="fas fa-star position-absolute end-0 bottom-0 mb-n4 me-n4 text-white-50" style="font-size: 150px; opacity: 0.1;"></i>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h6 class="fw-bold mb-0">{{ __('center::messages.blade_0472') }}</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column gap-3">
                        @php
                            $recentLogs = \App\Models\PointLog::with('user')->latest()->take(5)->get();
                        @endphp
                        @forelse($recentLogs as $log)
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-xs rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="small fw-bold text-truncate">{{ $log->user->name ?? __('center::messages.blade_0478') }}</div>
                                <div class="text-muted" style="font-size: 0.7rem;">{{ $log->reason }}</div>
                            </div>
                            <div class="text-success small fw-bold">+{{ $log->points }}</div>
                        </div>
                        @empty
                        <p class="small text-muted mb-0">{{ __('center::messages.blade_0473') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
