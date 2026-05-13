@extends('center::layouts.hope-master')

@section('page-title', __('center::dashboard.recent_activities') ?? 'سجل النشاطات')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table align-middle custom-table">
                <thead>
                    <tr>
                        <th class="border-0 bg-transparent">{{ __('center::dashboard.user') ?? 'المستخدم' }}</th>
                        <th class="border-0 bg-transparent">{{ __('center::dashboard.action') ?? 'الإجراء' }}</th>
                        <th class="border-0 bg-transparent">{{ __('center::dashboard.subject') ?? 'العنصر' }}</th>
                        <th class="border-0 bg-transparent">{{ __('center::dashboard.changes') ?? 'التفاصيل' }}</th>
                        <th class="border-0 bg-transparent">{{ __('center::dashboard.date') ?? 'التاريخ' }}</th>
                    </tr>
                </thead>
                <tbody class="border-0">
                    @forelse($activities as $activity)
                    <tr class="border-bottom">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 38px; height: 38px;">
                                    <i class="fas fa-user-edit small"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark small">
                                        @if($activity->causer)
                                            {{ $activity->causer->name }}
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </div>
                                    @if($activity->causer)
                                        <small class="text-muted extra-small">{{ ucfirst($activity->causer->role ?? 'user') }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $eventColors = [
                                    'created' => 'success',
                                    'updated' => 'warning',
                                    'deleted' => 'danger',
                                    'login' => 'info',
                                    'logout' => 'secondary'
                                ];
                                $color = $eventColors[$activity->event] ?? 'primary';
                            @endphp
                            <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill px-3 py-1" style="font-size: 0.7rem;">
                                {{ ucfirst($activity->event ?: $activity->description) }}
                            </span>
                        </td>
                        <td>
                            <div class="text-dark small fw-bold">
                                {{ class_basename($activity->subject_type) }}
                            </div>
                            @if($activity->subject)
                                <small class="text-muted extra-small">ID: #{{ $activity->subject->id }}</small>
                            @endif
                        </td>
                        <td>
                            @if($activity->event == 'updated')
                                <div class="changes-list extra-small">
                                    @foreach($activity->changes['attributes'] ?? [] as $key => $value)
                                        @if(!in_array($key, ['password', 'remember_token', 'google2fa_secret', 'updated_at']))
                                            <div class="mb-1">
                                                <span class="fw-bold text-muted">{{ $key }}:</span>
                                                <span class="text-decoration-line-through text-danger opacity-50">{{ is_array($activity->changes['old'][$key] ?? '') ? '...' : ($activity->changes['old'][$key] ?? 'null') }}</span>
                                                <i class="fas fa-long-arrow-alt-right mx-1 text-muted"></i>
                                                <span class="text-success fw-bold">{{ is_array($value) ? '...' : $value }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <small class="text-muted extra-small fst-italic">{{ $activity->description }}</small>
                            @endif
                        </td>
                        <td class="text-muted small">
                            <div class="d-flex flex-column">
                                <span class="fw-bold">{{ $activity->created_at->format('Y-m-d') }}</span>
                                <span class="extra-small opacity-75">{{ $activity->created_at->format('H:i:s') }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="mb-3">
                                <img src="{{ asset('assets/images/empty-state.svg') }}" alt="No activities" style="width: 100px; opacity: 0.6;">
                            </div>
                            <h6 class="text-muted fw-bold">لا توجد نشاطات مسجلة</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $activities->links() }}
        </div>
    </div>
</div>

<style>
    .extra-small { font-size: 0.7rem; }
    .custom-table thead th { font-size: 0.75rem; text-transform: uppercase; color: #6c757d; padding-bottom: 15px; }
</style>
@endsection
